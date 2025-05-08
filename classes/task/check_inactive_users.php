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
 * Scheduled task to check for inactive users.
 *
 * @package    local_thanotification
 * @copyright  2025 Your Name <your.email@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_thanotification\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Task to check for users who haven't logged in for 7 days.
 */
class check_inactive_users extends \core\task\scheduled_task {

    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return get_string('taskinactiveusers', 'local_thanotification');
    }

    /**
     * Execute the task.
     */
    public function execute() {
        global $DB, $CFG;

        require_once($CFG->dirroot . '/user/lib.php');

        mtrace('Starting check for inactive users...');

        // Get the configured number of days for inactivity check
        $inactiveDays = get_config('local_thanotification', 'inactivedays');
        if (empty($inactiveDays)) {
            $inactiveDays = 7; // Default to 7 days if not set
        }

        // Calculate the timestamp for X days ago
        $cutoffTime = time() - ($inactiveDays * DAYSECS);

        // Find users who:
        // 1. Have logged in at least once (firstaccess > 0)
        // 2. Haven't logged in for the last 7 days (lastaccess < $sevenDaysAgo)
        // 3. Are confirmed, not deleted, not suspended, and not guest users
        // LEFT JOIN {user_preferences} p ON p.userid = u.id AND p.name = 'local_thanotification_inactive_sent'
          //AND p.id IS NULL
        $sql = "SELECT u.*
                FROM {user} u
                WHERE u.firstaccess > 0
                AND u.lastaccess < :cutofftime
                AND u.lastaccess > 0
                AND u.deleted = 0
                AND u.confirmed = 1
                AND u.username != 'guest'
                AND u.suspended = 0
              ";

        $params = [
            'cutofftime' => $cutoffTime
        ];

        $users = $DB->get_records_sql($sql, $params);

        if (empty($users)) {
            mtrace("No inactive users found who haven't logged in for {$inactiveDays} days.");
            return;
        }

        mtrace("Found " . count($users) . " inactive users");

        // Get the email settings
        $emailsendername = get_config('local_thanotification', 'emailsendername');
        $emailsubject = get_config('local_thanotification', 'inactiveemailsubject');
        $coursename = get_config('local_thanotification', 'coursename');

        // If settings are empty, use defaults
        if (empty($emailsendername)) {
            $emailsendername = get_string('emailsendername_default', 'local_thanotification');
        }
        if (empty($emailsubject)) {
            $emailsubject = get_string('inactiveemailsubject_default', 'local_thanotification');
        }
        if (empty($coursename)) {
            $coursename = get_string('coursename_default', 'local_thanotification');
        }

        // THA logo URL
        $logourl = $CFG->wwwroot . '/local/thanotification/pix/logo.png';

        // Process each user
        foreach ($users as $user) {
            $this->send_inactive_notification($user, $emailsendername, $emailsubject, $coursename, $logourl,$inactiveDays);

            // Mark user as notified by setting a user preference
            set_user_preference('local_thanotification_inactive_sent', time(), $user);

            mtrace("Sent inactive notification to user {$user->id} ({$user->firstname} {$user->lastname})");

            // Log the notification
            //$this->log_notification($user->id);
        }

        mtrace('Finished processing inactive users.');
    }

    /**
     * Send the inactive notification email to a user.
     *
     * @param object $user The user object
     * @param string $emailsendername Sender name
     * @param string $emailsubject Email subject
     * @param string $coursename Course name
     * @param string $logourl URL to the THA logo
     * @return bool True if email was sent successfully
     */
    protected function send_inactive_notification($user, $emailsendername, $emailsubject, $coursename, $logourl,$inactiveDays) {
        global $CFG;

        // Get noreply user as the sender
        $noreplyuser = \core_user::get_noreply_user();
        $noreplyuser->firstname = $emailsendername;

        // Prepare email data
        $subject = $emailsubject;

        // Create a data object for the string parameters
        $a = new \stdClass();
        $a->fullname = fullname($user);
        $a->course = $coursename;
        $a->logourl = $logourl;
        $a->loginurl = $CFG->wwwroot . '/login/index.php';
        $a->dayselapsed = $inactiveDays; // Use the configured number of days

        // Get email content from language strings
        $messagehtml = get_string('inactiveusers_email_html', 'local_thanotification', $a);
        $messagetext = get_string('inactiveusers_email_text', 'local_thanotification', $a);

        // Send the email
        $success = email_to_user($user, $noreplyuser, $subject, $messagetext, $messagehtml);

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
