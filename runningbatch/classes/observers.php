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
 * Displays help via AJAX call or in a new page
 *
 * Use {@link core_renderer::help_icon()} or {@link addHelpButton()} to display
 * the help icon.
 *
 * @copyright 2002 onwards Martin Dougiamas
 * @package   core
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_runningbatch;
defined('MOODLE_INTERNAL') || die();

class observers {
    public static function dashboard(\core\event\dashboard_viewed $event) {
        global $DB,$CFG,$USER;
        $if_student = $DB->get_record_sql("SELECT * from {role_assignments} as ra join {role} as r on ra.roleid=r.id where r.shortname='student' and ra.userid=$USER->id");
        echo '<style> 
        body{
            display: none;
        }
        </style>';
        if($if_student){
        echo'<script>
        window.location.replace("'.$CFG->wwwroot.'/local/runningbatch/batch_details.php");
        </script>';} else {
        echo'<script>
        window.location.replace("'.$CFG->wwwroot.'/local/runningbatch/dashboard.php");
        </script>';}
    }    
}
