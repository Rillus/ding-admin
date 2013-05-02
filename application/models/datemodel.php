<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datemodel extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
		$this->load->helper('date');
		$this->load->database();
    }
	function nowTime(){
		$now = time();
		$now = unix_to_human($now);
		if (substr($now, -2, 2) == "PM"){
			$nowHours = substr($now, -8, 2);
			$nowHours += 12;
			$now = substr($now, 0, 11).$nowHours.substr($now, -6, 3);
		}
		return $now;
	}
	function formatDate($str, $type = false){
		if ($type == false){
			if (($timestamp = strtotime($str)) === false) {
				return $str;
			} else {
				return date('l jS F\ Y, g\:ia', $timestamp);
			}
		} else {
			if (($timestamp = strtotime($str)) === false) {
				return $str;
			} else {
				return date($type, $timestamp);
			}
		}
	}
}
?>