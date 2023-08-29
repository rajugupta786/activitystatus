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

namespace block_activitystatus\output;

use renderable;
use renderer_base;
use templatable;

class main implements renderable, templatable {
    protected $course;
    protected $userid;
    protected $info;

    public function __construct($course, $userid, $info) {
        $this->course = $course;
        $this->userid = $userid;
        $this->info = $info;
    }

    public function export_for_template(renderer_base $output) {
        global $USER, $OUTPUT, $DB, $CFG;
        $courseid = $this->course;
        $userid = $this->userid;
        $info = $this->info;

        $data = self::get_activity_data($courseid, $userid, $info);

        $defaultvariables = [
            'totalcoursecount' => $data,
        ];
        return $defaultvariables;
    }
    public static function get_activity_data($courseid, $userid, $info) {
        global $USER, $OUTPUT, $DB, $CFG;
        $course = $DB->get_record('course', array('id' => $courseid));
        $coursemodules = $DB->get_records('course_modules', array('course' => $courseid));
        $dataget = array();
        foreach ($coursemodules as $keyalue) {
            $modinfo = get_fast_modinfo($keyalue->course);
            $cm = $modinfo->get_cm($keyalue->id);
            $activity = $DB->get_record('course_modules', array('id' => $cm->id));
            $cdata = $info->get_data($activity, false, $userid);
            $dataget[] = array(
            'cmid' => $cm->id,
            'cmurl' => $cm->url,
            'activityname' => $cm->get_formatted_name(),
            'cmstateddate' => date('d-M-Y', $cm->added),
            'status' => $cdata->completionstate ? get_string('yes') : get_string('no'));
        }
        return $dataget;
    }
}
