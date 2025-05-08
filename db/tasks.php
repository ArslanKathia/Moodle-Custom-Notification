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
 * Task definition for THA Notification plugin.
 *
 * @package    local_thanotification
 * @copyright  2025 Your Name <your.email@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$tasks = [
  [
    'classname' => 'local_thanotification\task\check_user_login',
    'blocking' => 0,
    'minute' => '0',     // Run at minute 0 (top of the hour)
    'hour' => '0',       // Run at hour 0 (midnight)
    'day' => '*',        // Run every day
    'month' => '*',      // Run every month
    'dayofweek' => '*'   // Run on every day of the week
  ],
  [
    'classname' => 'local_thanotification\task\check_inactive_users',
    'blocking' => 0,
    'minute' => '0', // Run once per day at 1 AM
    'hour' => '1',
    'day' => '*',
    'month' => '*',
    'dayofweek' => '*'
  ],
  [
    'classname' => 'local_thanotification\task\check_halfway_completion',
    'blocking' => 0,
    'minute' => '*/5',  // Run every 5 minutes
    'hour' => '*',      // Run every hour
    'day' => '*',       // Run every day
    'month' => '*',     // Run every month
    'dayofweek' => '*'  // Run on every day of the week
  ],
  [
    'classname' => 'local_thanotification\task\check_course_completion',
    'blocking' => 0,
    'minute' => '*/5',  // Run every 5 minutes
    'hour' => '*',      // Run every hour
    'day' => '*',       // Run every day
    'month' => '*',     // Run every month
    'dayofweek' => '*'  // Run on every day of the week
  ]
];
