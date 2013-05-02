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
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
			$this->load->view("templates/message", $message);
		} else {
			$this->load->view('templates/header');
			$this->load->view('templates/message', $message);
			$this->load->view('templates/footer');
		}
	}
	function displayPage($view, $data = ""){
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
			$this->load->view($view, $data);
		} else {
			$this->load->view('templates/header');
			$this->load->view($view, $data);
			$this->load->view('templates/footer');
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
	function addRedirect($url, $title){
		echo "<form action='$url' method='POST'>";
		echo '<input type="hidden" name="redirect" value="'.current_url().'" />';
		echo '<input type="hidden" name="fromForm" value="1" />';
		echo '<input type="submit" value="'.$title.'" />';
		echo '</form>';
	}
}
?>