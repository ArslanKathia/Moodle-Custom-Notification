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
 * Scheduled task to check for users who haven't logged in after registration.
 *
 * @package    local_thanotification
 * @copyright  2025 Your Name <your.email@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_thanotification\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Task to check for users who registered but haven't logged in.
 */
class check_user_login extends \core\task\scheduled_task
{

  /**
   * Return the task's name as shown in admin screens.
   *
   * @return string
   */
  public function get_name()
  {
    return get_string('taskname', 'local_thanotification');
  }

  /**
   * Execute the task.
   */
  public function execute()
  {
    global $DB, $CFG;

    require_once($CFG->dirroot . '/user/lib.php');

    // Get the time threshold in minutes from the settings
    $timeafterregistration = get_config('local_thanotification', 'timeafterregistration');
    if (empty($timeafterregistration)) {
      $timeafterregistration = 10; // Default to 10 minutes if not set
    }

    // Calculate the timestamp for users who registered X minutes ago
    //$timewindowstart = $timenow - ($timeafterregistration * 60);
    //$timewindowend = $timewindowstart - (30 * 60); // Use a 30-minute window for testing

    // Find users who:
    // 1. Were created between X-1 and X minutes ago
    // 2. Have never logged in (firstaccess = 0)
    // 3. Have not already received a notification (custom field)
    // Find users who have never logged in and haven't received a notification
    $sql = "SELECT u.*
            FROM {user} u
            LEFT JOIN {user_preferences} p ON p.userid = u.id AND p.name = 'local_thanotification_sent'
            WHERE u.firstaccess = 0
            AND u.deleted = 0
            AND u.confirmed = 1
            AND u.username != 'guest'
            AND u.suspended = 0
            AND p.id IS NULL";

    //$params = [
    //  'timewindowstart' => $timewindowstart,
    //  'timewindowend' => $timewindowend
    //];

    $users = $DB->get_records_sql($sql, []);

    if (!$users) {
      mtrace("No users found who registered " . $timeafterregistration . " minutes ago and haven't logged in.");
      return;
    }

    mtrace("Found " . count($users) . " users");

    // Get the email settings
    $emailsendername = get_config('local_thanotification', 'emailsendername');
    $emailsubject = get_config('local_thanotification', 'emailsubject');
    $coursename = get_config('local_thanotification', 'coursename');

    // If settings are empty, use defaults
    if (empty($emailsendername)) {
      $emailsendername = get_string('emailsendername_default', 'local_thanotification');
    }
    if (empty($emailsubject)) {
      $emailsubject = get_string('emailsubject_default', 'local_thanotification');
    }
    if (empty($coursename)) {
      $coursename = get_string('coursename_default', 'local_thanotification');
    }

    // THA logo URL - you might want to make this configurable
    $logourl = $CFG->wwwroot . '/local/thanotification/pix/logo.png';

    // Process each user
    foreach ($users as $user) {
      $this->send_notification_email($user, $emailsendername, $emailsubject, $coursename, $logourl);

      // Mark user as notified by setting a user preference
      set_user_preference('local_thanotification_sent', $timenow, $user);

      mtrace("Sent notification to user {$user->id} ({$user->firstname} {$user->lastname})");
    }
  }

  /**
   * Send the notification email to a user.
   *
   * @param object $user The user object
   * @param string $emailsendername Sender name
   * @param string $emailsubject Email subject
   * @param string $coursename Course name
   * @param string $logourl URL to the THA logo
   * @return bool True if email was sent successfully
   */
  protected function send_notification_email($user, $emailsendername, $emailsubject, $coursename, $logourl)
  {
    global $CFG;

    // Get noreply user as the sender
    $noreplyuser = \core_user::get_noreply_user();
    $noreplyuser->firstname = $emailsendername;

    // Prepare email data
    $subject = get_string('email_template_subject_login', 'local_thanotification');

    // Create a data object for the string parameters
    $a = new \stdClass();
    $a->fullname = fullname($user);
    $a->course = $coursename;
    $a->logourl = $logourl;

    // Get email content from language strings
    $messagehtml = get_string('email_template_html', 'local_thanotification', $a);
    $messagetext = get_string('email_template', 'local_thanotification', $a);

    // Send the email
    $success = email_to_user($user, $noreplyuser, $subject, $messagetext, $messagehtml);

    if ($success) {
      // Log the notification
      $context = \context_system::instance();
      $event = \core\event\notification_sent::create([
        'context' => $context,
        'userid' => get_admin()->id, // The sender id (system)
        'relateduserid' => $user->id, // The recipient id (required field)
        'other' => [
          'notificationtype' => 'thanotification',
          'courseid' => SITEID  // Add this line to include the required courseid parameter
        ]
      ]);
      $event->trigger();
    }

    return $success;
  }
}
