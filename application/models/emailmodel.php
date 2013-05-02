<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailmodel extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
		$this->load->helper('date');
		$this->load->database();
    }
	function sendEmail($email, $subject, $messageBody) {
		// Now we need to send the ticket via email!
		$this->lang->load('email');
		$this->load->library('email');

		$config['mailtype'] = 'html';

		$this->email->initialize($config);

		$this->email->from('noreply@reviewtion.com', 'Rank Status tool.');
		$this->email->to($email);
		$this->email->bcc('riley@ticketlab.co.uk');
		
		$this->email->subject($subject);
		
		$this->email->message($messageBody);

		if (! $this->email->send()){
			echo $this->email->print_debugger();
		} else {
			return;
		}
	}
}
?>