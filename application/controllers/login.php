<?php
class Login extends CI_Controller {

	public function __construct()
    {
		parent::__construct();

		$this->load->database();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('session');
	}
	function index()
	{  
		$this->load->view('extranet/login');
	}
	function login_action()
	{		
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$redirect = $this->input->post('redirect');
		
		$this->db->where ('email', $email);
		$this->db->where ('password', $password);
		$retrieved_users = $this->db->get('users', 1);
		$retrieved_user = $retrieved_users->row();
				
		if ($retrieved_users->num_rows() != "")
		{
			$newdata = array(
				'id'  => $retrieved_user->id,
				'username'  => $retrieved_user->username,
				'email'  => $email,
				'logged_in' => 'yes',
				'permissions' => $retrieved_user->permissions
			);

			$this->session->set_userdata($newdata);
			
			redirect($redirect);
		} else {
			$newdata = array(
				'logged_in' => 'incorrect'
			);

			$this->session->set_userdata($newdata);

			redirect($redirect);
		}
	}
	function logout()
	{
		$array_items = array('id' => '', 'username' => '', 'email' => '', 'logged_in' => '', 'permissions' => '');
		$this->session->unset_userdata($array_items);
		redirect('');
	}
}
?>
