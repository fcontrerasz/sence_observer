<?php
require_once('funciones.php');

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
 * Email signup notification event observers.
 *
 * @package    local_notifyemailsignup
 * @author     Iñaki Arenaza
 * @copyright  2017 Iñaki Arenaza
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Email signup notification event observers.
 *
 * @package    local_notifyemailsignup
 * @author     Iñaki Arenaza
 * @copyright  2017 Iñaki Arenaza
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_scormevents_observer {
    /**
     * Event processor - user created
     *
     * @param \core\event\user_created $event
     * @return bool
     */

    public function object2array($object) {
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            $array[$key] = $value;
        }
    }
    else {
        $array = $object;
    }
    return $array;
	}

    public static function scorm_t_enviado(\mod_scorm\event\tracks_viewed $event) {
        global $DB, $CFG;
        $site = get_site();
        ob_start();
        var_dump($event);
        $result = ob_get_clean();
        $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . "/sence/events/track_events.txt", "r+") or die("No se puede abrir! - ".getcwd());
        $txt = $site;
        fwrite($myfile, $txt);
        $txt = $result;
        fwrite($myfile, $txt);
        fclose($myfile);
        $record = new object();
        $record->userid = 7;
        $record->modulo = 'TRACK_ENVIADO';
        $record->glosa = $result;
        $DB->insert_record('sence_track', $record);
        return true;
    }
    public static function scorm_enviado(\mod_scorm\event\sco_launched $event) {
        global $DB, $CFG;
        $soapURL = "http://elearning.sence.cl/Webservice/SenceElearning.svc?wsdl"; 
		$soapFunction = "RegistrarActividad";
        $idxuser = $event->userid;
        $idxcurso = $event->courseid;
        $claveunica = $DB->get_field('user_info_data', 'data', array('fieldid' =>1, 'userid' => $idxuser));
        $rut = $DB->get_field('user_info_data', 'data', array('fieldid' =>2, 'userid' => $idxuser));
        $site = get_site();
        ob_start();
        var_dump($event);
        $result = ob_get_clean();
        $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . "/sence/events/sco_events.txt", "r+") or die("No se puede abrir! - ".getcwd());
        $txt = $site;
        fwrite($myfile, $txt);
        $txt = $result;
        fwrite($myfile, $txt);
        fclose($myfile);
        $record = new object();
        $record->userid = $idxuser;
        $record->curso = $idxcurso;
        $record->modulo = 'SCORM_INFO';
        $record->glosa = $result;
        $DB->insert_record('sence_track', $record);
        return true;
    }
    public static function user_login(\core\event\user_loggedin $event) {
        global $DB, $CFG;
        $idxuser = $event->userid;
        $site = get_site();
        ob_start();
        var_dump($event);
        $result = ob_get_clean();
        $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . "/sence/events/login.txt", "r+") or die("No se puede abrir! - ".getcwd());
        $txt = $site;
        fwrite($myfile, $txt);
        $txt = $result;
        fwrite($myfile, $txt);
        fclose($myfile);
        $record = new object();
        $record->userid = $idxuser;
        $record->modulo = 'LOGIN';
        $record->glosa = $result;
        $DB->insert_record('sence_track', $record);
        return true;
    }
    public static function user_salir(\core\event\user_loggedout $event) {
        global $DB, $CFG;
        $soapURL = "http://elearning.sence.cl/Webservice/SenceElearning.svc?wsdl"; 
		$soapFunction = "RegistrarActividad";
        $idxuser = $event->userid;
        $claveunica = $DB->get_field('user_info_data', 'data', array('fieldid' =>1, 'userid' => $idxuser));
        $rut = $DB->get_field('user_info_data', 'data', array('fieldid' =>2, 'userid' => $idxuser));
         $site = get_site();
        ob_start();
        var_dump($event);
        $result = ob_get_clean();
        $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . "/sence/events/salir.txt", "w+") or die("No se puede abrir! - ".getcwd());
        $txt = $site;
        fwrite($myfile, $txt);
        $txt = $result;
        fwrite($myfile, $txt);
        fclose($myfile);
        $record = new object();
        $record->userid = $idxuser;
        $record->modulo = 'LOGOUT';
        $record->glosa = $result;
        $soapFunctionParameters = array('codigoSence' => '1237977973', 'rutAlumno' => $rut,'claveAlumno' => $claveunica, 'rutOtec' => '76450050', 'claveOtec' => 'YM142650', 'estadoActividad' => '2');
        $record->parametros = json_encode($soapFunctionParameters, JSON_PRETTY_PRINT);
        $soapClient = new SoapClient($soapURL);
        $soapResult = $soapClient->__soapCall($soapFunction, array($soapFunctionParameters));
        $soapResult = obj2array($soapResult);
        $record->estado = $soapResult["RegistrarActividadResult"];
        $DB->insert_record('sence_track', $record);
        return true;
    }
    public static function curso_terminado(\core\event\course_completed $event) {
        global $DB, $CFG;
        $idxuser = $event->userid;
        $idxcurso = $event->courseid;
        $claveunica = $DB->get_field('user_info_data', 'data', array('fieldid' =>1, 'userid' => $idxuser));
        $rut = $DB->get_field('user_info_data', 'data', array('fieldid' =>2, 'userid' => $idxuser));
        $site = get_site();
        ob_start();
        var_dump($event);
        $result = ob_get_clean();
        $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . "/sence/events/curso_terminado.txt", "w+") or die("No se puede abrir! - ".getcwd());
        $txt = $site;
        fwrite($myfile, $txt);
        $txt = $result;
        fwrite($myfile, $txt);
        fclose($myfile);
        $record = new object();
        $record->userid = $idxuser;
        $record->curso = $idxcurso;
        $record->modulo = 'CURSO_TERMINADO';
        $record->glosa = $result;
        $DB->insert_record('sence_track', $record);
        return true;
    }
    public static function modulo_visto(\mod_scorm\event\course_module_viewed $event) {
        global $DB, $CFG;
        $soapURL = "http://elearning.sence.cl/Webservice/SenceElearning.svc?wsdl"; 
		$soapFunction = "RegistrarActividad";
        $idxuser = $event->userid;
        $idxcurso = $event->courseid;
        $claveunica = $DB->get_field('user_info_data', 'data', array('fieldid' =>1, 'userid' => $idxuser));
        $rut = $DB->get_field('user_info_data', 'data', array('fieldid' =>2, 'userid' => $idxuser));
         $site = get_site();
        ob_start();
        var_dump($event);
        $result = ob_get_clean();
        $myfile = fopen($_SERVER['DOCUMENT_ROOT'] . "/sence/events/mod_visto.txt", "w+") or die("No se puede abrir! - ".getcwd());
        $txt = $site;
        fwrite($myfile, $txt);
        $txt = $result;
        fwrite($myfile, $txt);
        fclose($myfile);
        $record = new object();
        $record->userid = $idxuser;
        $record->curso = $idxcurso;
        $record->modulo = 'INGRESO_CURSO';
        $record->glosa = $result;
        $soapFunctionParameters = array('codigoSence' => '1237977973', 'rutAlumno' => $rut,'claveAlumno' => $claveunica, 'rutOtec' => '76450050', 'claveOtec' => 'YM142650', 'estadoActividad' => '1');
        $record->parametros = json_encode($soapFunctionParameters, JSON_PRETTY_PRINT);
        $soapClient = new SoapClient($soapURL);
        $soapResult = $soapClient->__soapCall($soapFunction, array($soapFunctionParameters));
        $soapResult = obj2array($soapResult);
        $record->estado = $soapResult["RegistrarActividadResult"];
        $DB->insert_record('sence_track', $record);
        return true;
    }





    public static function user_signup(\core\event\user_created $event) {
        global $DB, $CFG;

        // Make sure the user was created through email signup plugin. Otherwise, ignore the event.
        $user = $DB->get_record('user', array('id' => $event->objectid));
        if ($user->auth !== 'email') {
            return true;
        }

        // It was, so send a notification email to the notification address(es), withi the account details.
        $site = get_site();
        $supportuser = core_user::get_support_user();

        // No need to send the password at all (even it it's encrypted).
        $user->password = '++hidden for security reasons++';

        $data = array();
        $data['supportname'] = fullname($supportuser);
        $data['sitename'] = format_string($site->fullname);
        $data['signoff'] = generate_email_signoff();

        // Add the user table fields.
        foreach ($user as $key => $value) {
            $data['signup_user_'.$key] = $value;
        }

        // Add the custom profile fields too.
        $user->profile = array();
        require_once($CFG->dirroot.'/user/profile/lib.php');
        profile_load_custom_fields($user);
        foreach ($user->profile as $key => $value) {
            $data['signup_profile_'.$key] = $value;
        }

        $subject = get_string('notifyemailsignupsubject', 'local_notifyemailsignup', format_string($site->fullname));
        $message  = get_string('notifyemailsignupbody', 'local_notifyemailsignup', $data);
        $messagehtml = text_to_html($message, false, false, true);

        $supportuser->mailformat = 1; // Always send HTML version as well.

        // Directly email rather than using the messaging system to ensure its not routed to a popup or jabber.
        return email_to_user($supportuser, $supportuser, $subject, $message, $messagehtml);

        return true;
    }
}
