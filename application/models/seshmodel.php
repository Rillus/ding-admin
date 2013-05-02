<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seshmodel extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();		
    }
    
    function checkSesh()
    {
		$loggedIn = $this->session->userdata('logged_in');
		if ((isset($loggedIn)) && ($loggedIn == "yes")){
			// user is logged in
			return true;
		} else {
			redirect('');
		}
    }
	function getCurrentUser(){
		$this->db->where('username', $this->session->userdata('username'));
		$user = $this->db->get('users', 1);
		if ($user->num_rows() > 0){
			return $user->row();
		}
		return false;
	}
	function getCurrentUserId(){
		$user = $this->getCurrentUser();
		
		return $user->id;
	}
	function checkEventOwnerOrAdmin($user_id){
		if ($this->session->userdata('id') == ""){
			return false;
		}
		if ($this->session->userdata('id') == $user_id){
			return true;
		}
		if ($this->session->userdata('user_type') == '0') {
			return true;
		}
		
		return false;
	}
}
?>