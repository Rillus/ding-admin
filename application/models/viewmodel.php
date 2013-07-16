<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Viewmodel extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
		$this->load->database();
    }
	function displayMessage($body, $head = ""){
		$message['header'] = $head;
		$message['message'] = $body;
		if ($this->isAJAX()) {
			$this->load->view("templates/message", $message);
		} else {
			$header['contentTypes'] = $this->db->get('content_type');
			$this->load->view('templates/header', $header);
			$this->load->view('templates/message', $message);
			$this->load->view('templates/footer');
		}
	}
	function displayPage($view, $data = "", $parser = false){
		if ($parser == false){
			if ($this->isAJAX()) {
				$this->load->view($view, $data);
			} else {
				$header['contentTypes'] = $this->db->get('content_type');
				$this->load->view('templates/header', $header);
				$this->load->view($view, $data);
				$this->load->view('templates/footer', $data);
			}
		} else {
			$this->load->library('parser');
			
			$this->load->view('templates/header');
			$this->parser->parse($view, $data);
			$this->load->view('templates/footer', $data);
		}
	}
	function redirect(){
		$url = $this->input->post('redirect');
		if ($url == ""){
			redirect('');
		} else {
			redirect($url);
		}
	}
	function addRedirect($url, $title, $permissions = false){
		if ($permissions != false){
			if (! $this->Permission->level($permissions)){
				return "";
			}
		}
		$url = site_url($url); 
		$form = "<form action='$url' method='POST'>";
		$form .= '<input type="hidden" name="redirect" value="'.current_url().'" />';
		$form .= '<input type="hidden" name="fromForm" value="1" />';
		$form .='<input type="submit" value="'.$title.'" />';
		$form .= '</form>';
		
		return $form;
	}
	function isAJAX(){
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')){
			return true;
		} else {
			return false;
		}
	}
}
?>