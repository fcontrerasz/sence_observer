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
 * Email Signup Notification Plugin
 *
 * @package    local_notifyemailsignup
 * @author     Iñaki Arenaza
 * @copyright  2017 Iñaki Arenaza
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 mod_scorm\event\tracks_viewed
 */

defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname' => '\mod_scorm\event\sco_launched',
        'callback' => 'local_scormevents_observer::scorm_enviado',
    ),
    array(
        'eventname' => '\mod_scorm\event\tracks_viewed',
        'callback' => 'local_scormevents_observer::scorm_t_enviado',
    ),
   /* array(
        'eventname' => '\mod_scorm\event\interactions_viewed',
        'callback' => 'local_scormevents_observer::scorm_t_enviado',
    ),*/
    array(
        'eventname' => '\mod_scorm\event\course_module_viewed',
        'callback' => 'local_scormevents_observer::modulo_visto',
    ),
    array(
        'eventname' => '\core\event\user_loggedin',
        'callback' => 'local_scormevents_observer::user_login',
    ),
    array(
        'eventname' => '\core\event\user_loggedout',
        'callback' => 'local_scormevents_observer::user_salir',
    ),
    array(
        'eventname' => '\core\event\course_completed',
        'callback' => 'local_scormevents_observer::curso_terminado',
    ),
    /*array(
        'eventname' => '\mod_folder\event\course_module_viewed',
        'callback' => 'local_scormevents_observer::modulo_visto',
    ),
    array(
        'eventname' => '\mod_url\event\course_module_viewed',
        'callback' => 'local_scormevents_observer::scorm_t_enviado',
    ),*/
    
);
