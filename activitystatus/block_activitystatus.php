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

class block_activitystatus extends block_base {
    public function init() {
        global $CFG;
        require_once("{$CFG->libdir}/completionlib.php");
        $this->title = get_string('pluginname', 'block_activitystatus');
    }

    public function get_content() {
        global $OUTPUT, $USER, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $course = $this->page->course;
        $context = context_course::instance($course->id);
        $courses = $DB->get_record('course', array('id' => $course->id));
        $info = new completion_info($courses);

        $renderable = new \block_activitystatus\output\main($course->id, $USER->id, $info);
        $renderer = $this->page->get_renderer('block_activitystatus');

        $this->content = new stdClass();
        $this->content->text = $renderer->render($renderable);
        $this->content->footer = '';

        return $this->content;
    }
    public function applicable_formats() {
        return ['all' => false, 'course-view' => true];
    }
    public function instance_allow_multiple() {
        return false;
    }
    public function has_config() {
        return false;
    }
} /*end line
