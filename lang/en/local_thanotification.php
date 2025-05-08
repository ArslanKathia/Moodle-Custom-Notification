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
 * Language strings for THA Notification plugin.
 *
 * @package    local_thanotification
 * @copyright  2025 Your Name <your.email@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'THA Notification';
$string['timeafterregistration'] = 'Time after registration';
$string['timeafterregistration_desc'] = 'Time in minutes after registration to send notification if user has not logged in.';
$string['emailsendername'] = 'Email sender name';
$string['emailsendername_desc'] = 'Name that appears as the sender of the notification email.';
$string['emailsendername_default'] = 'THA Support Team';
$string['emailsubject'] = 'Email subject';
$string['emailsubject_desc'] = 'Subject line for the notification email.';
$string['emailsubject_default'] = 'We Miss You! Need Help Getting Back on Track?';
$string['coursename'] = 'Course name';
$string['coursename_desc'] = 'Name of the course to reference in the email.';
$string['coursename_default'] = 'Home Inspections Certification Training Program';
$string['taskname'] = 'Check for users who registered but haven\'t logged in';
$string['notification_sent'] = 'THA notification sent to user {$a}';


$string['email_template_subject_login'] = 'Welcome to Holmes Academy';
// Complete email template as a single string
$string['email_template'] = 'Dear {$a->fullname},

I hope this message finds you well. I noticed that you haven\'t yet logged into your account since receiving your credentials, and I wanted to reach out to see if you need any assistance.

Sometimes, students experience minor issues when first accessing the course platform, and I\'m here to help ensure your experience is smooth and enjoyable right from the start.

If you\'re having trouble with:
●       Logging in: Please double-check your username and password from the welcome email. If you\'re still having issues, feel free to reply to this email, and I\'ll assist you promptly.
●       Navigating the platform: If you\'re unsure how to get started, I can guide you through the initial steps and point you toward the first modules.
●       General questions: Whether it\'s about the course content, schedule, or anything else, I\'m here to help answer any questions you might have.

Please don\'t hesitate to reach out if there\'s anything you need. We\'re excited to have you on board and want to ensure you\'re set up for success from day one!

Looking forward to seeing you dive into the course.

Best regards,
THA Support Team';

// HTML version of the email template
$string['email_template_html'] = '<div style="font-family: Arial, sans-serif; max-width: 100%; margin: 0 auto;">
    <h3>Dear {$a->fullname},</h3>

    <p>I hope this message finds you well. I noticed that you haven\'t yet logged into your account since receiving your credentials, and I wanted to reach out to see if you need any assistance.</p>

    <p>Sometimes, students experience minor issues when first accessing the course platform, and I\'m here to help ensure your experience is smooth and enjoyable right from the start.</p>

    <h3>If you\'re having trouble with:</h3>
    <ul>
        <li><strong>Logging in</strong>: Please double-check your username and password from the welcome email. If you\'re still having issues, feel free to reply to this email, and I\'ll assist you promptly.</li>
        <li><strong>Navigating the platform</strong>: If you\'re unsure how to get started, I can guide you through the initial steps and point you toward the first modules.</li>
        <li><strong>General questions</strong>: Whether it\'s about the course content, schedule, or anything else, I\'m here to help answer any questions you might have.</li>
    </ul>

    <p>Please don\'t hesitate to reach out if there\'s anything you need. We\'re excited to have you on board and want to ensure you\'re set up for success from day one!</p>

    <p>Looking forward to seeing you dive into the course.</p>

    <p><strong>Best regards,</strong></p>

    <div>
        <img src="{$a->logourl}" alt="THA Logo" style="max-width: 150px;" width="200">
    </div>

</div>';

// Settings for inactive users notification
$string['inactivedays'] = 'Days of inactivity';
$string['inactivedays_desc'] = 'Number of days of user inactivity before sending a notification email.';
$string['inactiveemailsubject'] = 'Inactive users email subject';
$string['inactiveemailsubject_desc'] = 'Subject line for the email sent to inactive users.';
$string['inactiveemailsubject_default'] = 'We miss you! It\'s been a while since you logged in';

// Task name
$string['taskinactiveusers'] = 'Check for inactive users';

$string['inactiveusers_email_html'] = '
<div style="max-width: 100%; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
    <div style="margin-bottom: 20px;">
        <p>Dear {$a->fullname},</p>
        <p>I hope this message finds you well. I noticed that it\'s been {$a->dayselapsed} days since you\'ve logged into your account for the {$a->course}, and I wanted to check in with you.</p>
        <p>Starting a new course can sometimes feel overwhelming, and I\'m here to ensure you have all the support you need to succeed. Whether you\'ve had questions about the program, encountered any challenges, or just needed a little motivation to get started, I\'m here to help!</p>
        <p><strong>Here\'s how I can assist:</strong></p>
        <ul>
            <li><strong>Addressing Questions or Concerns</strong>: If there\'s anything you\'re uncertain about, whether it\'s course content, structure, or expectations, please feel free to reach out.</li>
            <li><strong>Technical Support</strong>: If you\'ve had trouble logging in or navigating the platform, I can guide you through the process and troubleshoot any issues.</li>
            <li><strong>Study Tips</strong>: Need help with time management or staying on track? I\'d be happy to share some tips and resources to make your learning experience smoother.</li>
        </ul>
        <p>Remember, this course is designed to set you up for success in the home inspection industry, and I\'m here to ensure you have everything you need to make the most of it.</p>
        <p>If you have any doubts or need assistance, please don\'t hesitate to get in touch. Your success is important to us, and we want to see you thrive!</p>

        <p>Looking forward to hearing from you soon.</p>
        <p>Best regards,</p>
        <div style="margin-top: 20px;">
            <img src="{$a->logourl}" alt="THA Logo" style="max-width: 150px;" width="200">
        </div>
    </div>
</div>';
$string['inactiveusers_email_text'] = 'We Miss You! Need Help Getting Back on Track?

Dear {$a->fullname},

I hope this message finds you well. I noticed that it\'s been {$a->dayselapsed} days since you\'ve logged into your account for the {$a->course}, and I wanted to check in with you.

Starting a new course can sometimes feel overwhelming, and I\'m here to ensure you have all the support you need to succeed. Whether you\'ve had questions about the program, encountered any challenges, or just needed a little motivation to get started, I\'m here to help!

Here\'s how I can assist:

- Addressing Questions or Concerns: If there\'s anything you\'re uncertain about, whether it\'s course content, structure, or expectations, please feel free to reach out.
- Technical Support: If you\'ve had trouble logging in or navigating the platform, I can guide you through the process and troubleshoot any issues.
- Study Tips: Need help with time management or staying on track? I\'d be happy to share some tips and resources to make your learning experience smoother.

Remember, this course is designed to set you up for success in the home inspection industry, and I\'m here to ensure you have everything you need to make the most of it.

If you have any doubts or need assistance, please don\'t hesitate to get in touch. Your success is important to us, and we want to see you thrive!

Log in now: {$a->loginurl}

Looking forward to hearing from you soon.

Best regards,
THA Support Team';

// Halfway course completion notification strings
$string['trackingcourses'] = 'Courses to track';
$string['trackingcourses_desc'] = 'Select which courses to monitor for halfway completion notifications.';
$string['taskhalfway'] = 'Check for users who have completed half of their course';

$string['halfway_notification_interval'] = 'Hours between repeat notifications';
$string['halfway_notification_interval_desc'] = 'Minimum number of hours that must pass before sending another halfway completion notification to the same user for the same course.';

// Halfway completion email template
$string['halfway_email_subject'] = 'Congratulations on Reaching the Halfway Mark!';

$string['halfway_email_text'] = 'Dear {$a->fullname},

I wanted to take a moment to congratulate you on reaching the halfway point of your Home Inspections Certification Training Program! Your dedication and hard work have brought you this far, and that\'s something to be proud of.

As you continue through the second half of the course, remember that you\'re building a strong foundation for a successful career in home inspection. The skills and knowledge you\'re acquiring now will serve you well in the field.

I\'m here to support you every step of the way, so please don\'t hesitate to reach out if:
●       You have any questions about the material you\'ve covered so far.
●       You encounter any challenges or need clarification on any topics.
●       You need advice or encouragement as you work through the remaining modules.

Your success is important to us, and we want to ensure you have everything you need to finish strong. Keep up the great work, and I\'m confident you\'ll excel in the remaining sections.

Thank you for your continued effort, and I look forward to celebrating your completion of the course soon!

Best regards,
THA Support Team';

$string['halfway_email_html'] = '<div style="font-family: Arial, sans-serif; max-width: 100%; margin: 0 auto; padding: 20px;">
    <h3>Dear {$a->fullname},</h3>

    <p>I wanted to take a moment to <strong>congratulate you on reaching the halfway point</strong> of your {$a->course} course! Your dedication and hard work have brought you this far, and that\'s something to be proud of.</p>

    <p>As you continue through the second half of the course, remember that you\'re building a strong foundation for a successful career in home inspection. The skills and knowledge you\'re acquiring now will serve you well in the field.</p>

    <h3>I\'m here to support you every step of the way, so please don\'t hesitate to reach out if:</h3>
    <ul>
        <li>You have any questions about the material you\'ve covered so far.</li>
        <li>You encounter any challenges or need clarification on any topics.</li>
        <li>You need advice or encouragement as you work through the remaining modules.</li>
    </ul>

    <p>Your success is important to us, and we want to ensure you have everything you need to finish strong. Keep up the great work, and I\'m confident you\'ll excel in the remaining sections.</p>

    <p>Thank you for your continued effort, and I look forward to celebrating your completion of the course soon!</p>

    <p><strong>Best regards,</strong></p>

    <div style="margin-top: 20px;">
        <img src="{$a->logourl}" alt="THA Logo" style="max-width: 150px;" width="200">
    </div>
</div>';

// Course completion task name
$string['taskcompletion'] = 'Check for users who have completed their course (ready for final exam)';

// Course completion email template
$string['completion_email_subject'] = 'Congratulations on Completing the Course! You\'re Ready for the Final Exam';

$string['completion_email_text'] = 'Dear {$a->fullname},

Congratulations on reaching this incredible milestone! Completing the {$a->course} course is a testament to your hard work, dedication, and commitment to investing in your future. You should be incredibly proud of what you\'ve accomplished so far.

Now that you\'ve completed the course, you\'re ready to take the final exam—your last step towards becoming a certified home inspector. I have no doubt that all the effort you\'ve put in has prepared you well for this moment.

As you approach the finish line, keep in mind:
●       You\'ve Got This!: The knowledge and skills you\'ve gained throughout the course have equipped you for success. Trust in what you\'ve learned, and give it your best shot!
●       Support is Still Here: If you have any last-minute questions or need clarification on anything before the exam, I\'m here to help. Don\'t hesitate to reach out.
●       Celebrate Your Achievement: Completing this course is a big deal, so take a moment to celebrate your hard work and dedication.

I\'m cheering you on as you take this final step. Good luck on your exam, {$a->fullname}! I can\'t wait to see you cross the finish line and step into your new career as a certified home inspector.

Best regards,
THA Support Team';

$string['completion_email_html'] = '<div style="font-family: Arial, sans-serif; max-width: 100%; margin: 0 auto; padding: 20px;">
    <h3>Dear {$a->fullname},</h3>

    <p><strong>Congratulations on reaching this incredible milestone!</strong> Completing the {$a->course} course is a testament to your hard work, dedication, and commitment to investing in your future. You should be incredibly proud of what you\'ve accomplished so far.</p>

    <p>Now that you\'ve completed the course, you\'re ready to take the final exam—your last step towards becoming a certified home inspector. I have no doubt that all the effort you\'ve put in has prepared you well for this moment.</p>

    <h3>As you approach the finish line, keep in mind:</h3>
    <ul>
        <li><strong>You\'ve Got This!</strong>: The knowledge and skills you\'ve gained throughout the course have equipped you for success. Trust in what you\'ve learned, and give it your best shot!</li>
        <li><strong>Support is Still Here</strong>: If you have any last-minute questions or need clarification on anything before the exam, I\'m here to help. Don\'t hesitate to reach out.</li>
        <li><strong>Celebrate Your Achievement</strong>: Completing this course is a big deal, so take a moment to celebrate your hard work and dedication.</li>
    </ul>

    <p>I\'m cheering you on as you take this final step. Good luck on your exam, {$a->fullname}! I can\'t wait to see you cross the finish line and step into your new career as a certified home inspector.</p>

    <p><strong>Best regards,</strong></p>

    <div style="margin-top: 20px;">
        <img src="{$a->logourl}" alt="THA Logo" width="200" style="max-width: 150px;">
    </div>
</div>';
