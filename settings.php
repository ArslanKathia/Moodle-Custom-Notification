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
 * Settings for the THA Notification plugin.
 *
 * @package    local_thanotification
 * @copyright  2025 Your Name <your.email@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
  $settings = new admin_settingpage('local_thanotification', get_string('pluginname', 'local_thanotification'));
  $ADMIN->add('localplugins', $settings);

  // Time after registration (in minutes) to send the notification
  $settings->add(new admin_setting_configtext(
    'local_thanotification/timeafterregistration',
    get_string('timeafterregistration', 'local_thanotification'),
    get_string('timeafterregistration_desc', 'local_thanotification'),
    10, // Default to 10 minutes
    PARAM_INT
  ));

  // Days of inactivity before sending notification
  $settings->add(new admin_setting_configtext(
    'local_thanotification/inactivedays',
    get_string('inactivedays', 'local_thanotification'),
    get_string('inactivedays_desc', 'local_thanotification'),
    7, // Default to 7 days
    PARAM_INT
  ));

  // Email sender name
  $settings->add(new admin_setting_configtext(
    'local_thanotification/emailsendername',
    get_string('emailsendername', 'local_thanotification'),
    get_string('emailsendername_desc', 'local_thanotification'),
    get_string('emailsendername_default', 'local_thanotification'),
    PARAM_TEXT
  ));

  // Email subject
  $settings->add(new admin_setting_configtext(
    'local_thanotification/emailsubject',
    get_string('emailsubject', 'local_thanotification'),
    get_string('emailsubject_desc', 'local_thanotification'),
    get_string('emailsubject_default', 'local_thanotification'),
    PARAM_TEXT
  ));

  // Course name (for Home Inspections Certification Training Program)
  $settings->add(new admin_setting_configtext(
    'local_thanotification/coursename',
    get_string('coursename', 'local_thanotification'),
    get_string('coursename_desc', 'local_thanotification'),
    get_string('coursename_default', 'local_thanotification'),
    PARAM_TEXT
  ));

   // Minimum hours between repeat halfway notifications
   $settings->add(new admin_setting_configtext(
    'local_thanotification/halfway_notification_interval',
    get_string('halfway_notification_interval', 'local_thanotification'),
    get_string('halfway_notification_interval_desc', 'local_thanotification'),
    24, // Default to 24 hours
    PARAM_INT
  ));

   // Courses to track for halfway completion
   $courseoptions = [];

   // Get all visible courses
   $courses = get_courses();
   foreach ($courses as $course) {
     if ($course->id == SITEID) { // Skip site course
       continue;
     }
     $courseoptions[$course->id] = $course->fullname;
   }

   $settings->add(new admin_setting_configmultiselect(
     'local_thanotification/trackingcourses',
     get_string('trackingcourses', 'local_thanotification'),
     get_string('trackingcourses_desc', 'local_thanotification'),
     [], // Default to no courses selected
     $courseoptions
   ));

}
