<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Scheduled task to check for users who have completed half of their course.
 *
 * @package    local_thanotification
 * @copyright  2025 Your Name <your.email@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_thanotification\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Task to check for users who have completed approximately half of their course.
 */
class check_halfway_completion extends \core\task\scheduled_task {

    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return get_string('taskhalfway', 'local_thanotification');
    }

    /**
     * Execute the task.
     */
    public function execute() {
        global $DB, $CFG;

        require_once($CFG->dirroot . '/user/lib.php');
        require_once($CFG->dirroot . '/course/lib.php');
        require_once($CFG->libdir . '/completionlib.php');

        mtrace('Starting check for users who have completed half of their course activities...');

        // Get the courses to track from config
        $trackingcourses = get_config('local_thanotification', 'trackingcourses');

        if (empty($trackingcourses)) {
            mtrace('No courses configured for tracking. Please set up courses in the plugin settings.');
            return;
        }

        // Convert comma-separated course IDs to array
        $courseids = explode(',', $trackingcourses);

        if (empty($courseids)) {
            mtrace('No valid course IDs found in configuration.');
            return;
        }

        mtrace('Checking the following courses: ' . implode(', ', $courseids));

        // Process each configured course
        foreach ($courseids as $courseid) {
            $this->process_course($courseid);
        }

        mtrace('Finished checking for halfway course completion.');
    }

    /**
     * Process a single course to find users who have completed half of the activities.
     *
     * @param int $courseid The course ID to check
     */
    protected function process_course($courseid) {
        global $DB, $CFG;

        mtrace("Processing course ID: $courseid");

        // Get the course
        $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

        // Get all activities in the course that support completion
        $modinfo = get_fast_modinfo($course);
        $activities = [];

        foreach ($modinfo->cms as $cm) {
            if ($cm->completion != COMPLETION_TRACKING_NONE) {
                $activities[] = $cm;
            }
        }

        $totalactivities = count($activities);

        if ($totalactivities == 0) {
            mtrace("Course $courseid has no activities with completion tracking enabled.");
            return;
        }

        mtrace("Course $courseid has $totalactivities activities with completion tracking.");

        // Calculate the threshold for "half completed" (rounded up)
        $halfwayThreshold = ceil($totalactivities / 2);

        mtrace("Halfway threshold is $halfwayThreshold activities.");

        // Get all enrolled users
        $context = \context_course::instance($courseid);
        $enrolledusers = get_enrolled_users($context, '', 0, 'u.*', null, 0, 0, true);

        mtrace("Found " . count($enrolledusers) . " enrolled users in course $courseid");

        // For each user, check their completion status
        foreach ($enrolledusers as $user) {
            $completion = new \completion_info($course);
            $completedCount = 0;

            // Check if user has already received the halfway notification
            $notificationSent = $DB->record_exists('user_preferences', [
                'userid' => $user->id,
                'name' => 'local_thanotification_halfway_' . $courseid
            ]);

               // If the user has already received a notification
               if ($notificationSent) {
                // Skip this user as they've already received the notification
                continue;
              }


            // Count completed activities
            foreach ($activities as $cm) {
                $data = $completion->get_data($cm, false, $user->id);
                if ($data->completionstate == COMPLETION_COMPLETE ||
                    $data->completionstate == COMPLETION_COMPLETE_PASS) {
                    $completedCount++;
                }
            }

            mtrace("User {$user->id} ({$user->firstname} {$user->lastname}) has completed $completedCount/$totalactivities activities.");


             // If user has completed all activities, don't send a halfway notification
             if ($completedCount >= $totalactivities) {
              mtrace("User {$user->id} has already completed all activities in the course. Skipping halfway notification.");
              continue;
          }
            // Check if user has completed at least half but not all activities
            if ($completedCount >= $halfwayThreshold) {
                mtrace("User {$user->id} has reached the halfway point. Sending notification.");

                // Send notification
                $this->send_halfway_notification($user, $course);

                // Mark as notified
                set_user_preference('local_thanotification_halfway_' . $courseid, time(), $user);
            }
        }
    }

    /**
     * Send the halfway notification email to a user.
     *
     * @param object $user The user object
     * @param object $course The course object
     * @return bool True if email was sent successfully
     */
    protected function send_halfway_notification($user, $course) {
        global $CFG;

        // Get the email settings
        $emailsendername = get_config('local_thanotification', 'emailsendername');
        $coursename = get_config('local_thanotification', 'coursename');

        // If settings are empty, use defaults
        if (empty($emailsendername)) {
            $emailsendername = get_string('emailsendername_default', 'local_thanotification');
        }
        if (empty($coursename)) {
            $coursename = get_string('coursename_default', 'local_thanotification');
        } else {
            // Use actual course name if not using the default
            $coursename = $course->fullname;
        }

        // THA logo URL
        $logourl = $CFG->wwwroot . '/local/thanotification/pix/logo.png';

        // Get noreply user as the sender
        $noreplyuser = \core_user::get_noreply_user();
        $noreplyuser->firstname = $emailsendername;

        // Prepare email data
        $subject = get_string('halfway_email_subject', 'local_thanotification');

        // Create a data object for the string parameters
        $a = new \stdClass();
        $a->fullname = fullname($user);
        $a->course = $coursename;
        $a->logourl = $logourl;

        // Get email content from language strings
        $messagehtml = get_string('halfway_email_html', 'local_thanotification', $a);
        $messagetext = get_string('halfway_email_text', 'local_thanotification', $a);

        // Send the email
        $success = email_to_user($user, $noreplyuser, $subject, $messagetext, $messagehtml);

        if ($success) {
            // Log the notification
            $event = \core\event\notification_sent::create([
                'userid' => $user->id,
                'context' => \context_course::instance($course->id),
                'relateduserid' => $user->id, // The recipient id (required field)
                'other' => [
                    'type' => 'local_thanotification_halfway',
                    'courseid' => SITEID
                ]
            ]);
            $event->trigger();

            // Log to the plugin's database table
            $this->log_notification($user->id);
        }

        return $success;
    }

    /**
     * Log the notification to the plugin's database table.
     *
     * @param int $userid The user ID
     * @return bool Success/failure
     */
    protected function log_notification($userid) {
        global $DB;

        $record = new \stdClass();
        $record->userid = $userid;
        $record->timecreated = time();
        $record->status = 1; // 1 = Sent successfully

        return $DB->insert_record('local_thanotification_log', $record);
    }
}
