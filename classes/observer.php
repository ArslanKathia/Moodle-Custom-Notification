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
 * Event observer for THA Notification plugin.
 *
 * @package    local_thanotification
 * @copyright  2025 Your Name <your.email@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_thanotification;

defined('MOODLE_INTERNAL') || die();

/**
 * Event observer class.
 */
class observer
{

  /**
   * Observe the user created event.
   *
   * @param \core\event\user_created $event
   */
  public static function user_created(\core\event\user_created $event)
  {
    global $DB;

    // No need to do anything here, as we're handling this via the scheduled task
    // This observer is provided as an alternative implementation option
  }
}
