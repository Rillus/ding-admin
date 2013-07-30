<?php
class Users extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->database();
		$this->load->library('session');
		$this->load->helper('date');
		
		$this->load->model('Seshmodel');
		$this->Seshmodel->checkSesh();
		
		$this->load->model('Permission');
		$this->Permission->level("users:read all, users:read own", false);
		
		$this->load->model('Dbmodel');
		$this->load->model('Viewmodel');
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error">
  <button type="button" class="close" data-dismiss="alert" tabindex="-1">&times;</button>', '</div>');
	}

	function index()
	{	
		if (! $this->Permission->level("users:read all")){
			redirect('users/user/'.$this->session->userdata('id'));
		}
		$data['users'] = $this->db->get('users');
		
		$this->Viewmodel->displayPage("show/users", $data);
	}
	function user($id = "") {
		if ($id == ""){
			redirect('users');
		}
		if ((! $this->Permission->level("users:read all")) && ($id != $this->session->userdata('id'))){
			redirect('users/user/'.$this->session->userdata('id'));
		}
		$data['user'] = $this->Dbmodel->getUserById($id);
		
		$this->Viewmodel->displayPage('show/view/user', $data);
		
	}
	function add() {
		$this->Permission->level("users:create", false);
						
		$this->form_validation->set_rules('forename', 'forename', 'trim|required');
		$this->form_validation->set_rules('surname', 'surname', 'trim|required');
		$this->form_validation->set_rules('username', 'username', 'required|callback_preregistered_username');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_preregistered_email');
		$this->form_validation->set_rules('emailThem', 'email new user', '');
		$this->form_validation->set_rules('password', 'password', 'required');
		$this->form_validation->set_rules('confirm', 'confirm password', 'required|matches[password]');
		$this->form_validation->set_rules('permissions', 'select permissions', 'required');
		
		if ($this->form_validation->run() == FALSE) {
			$data['permissions'] = $this->db->get('permissions');
			
			$this->Viewmodel->displayPage('add/user', $data);
		} else {
			$forename = $_POST['forename'];
			$surname = $_POST['surname'];
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$permissions = $_POST['permissions'];
						
			$data = array (
				'forename' => $forename,
				'surname' => $surname,
				'email' => $email,
				'username' => $username,
				'password' => $password,
				'permissions' => $permissions
			);

			$this->db->insert('users', $data);
			if (isset($_POST['emailThem'])){
				$this->load->model('Emailmodel');
				$email = $username;
				$subject = "Invite to use the new Rank project management tool";
				$messageBody = "<p>You've been set up with an account at ".site_url()."</p>";
				$messageBody .= "<p>Your username is $username</p>";
				$messageBody .= "<p>Your password is $password</p>";
				$messageBody .= "<p>Enjoy!<br/>Riley</p>";
				$this->Emailmodel->sendEmail($email, $subject, $messageBody);
			}
			redirect('users');
		}
	}
	function edit($id) {
		$this->Permission->level("users:edit own, users:edit all", false);
		
		if ((! $this->Permission->level("users:edit all")) && ($id != $this->session->userdata('id'))){
			redirect('users/edit/'.$this->session->userdata('id'));
		}
				
		$this->form_validation->set_rules('forename', 'forename', 'trim|required');
		$this->form_validation->set_rules('surname', 'surname', 'trim|required');
		$this->form_validation->set_rules('username', 'username', 'required|');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'password', 'required');
		$this->form_validation->set_rules('confirm', 'confirm password', 'required|matches[password]');
		$this->form_validation->set_rules('permissions', 'select permissions', 'required');
		
		if ($this->form_validation->run() == FALSE) {
			$data['permissions'] = $this->db->get('permissions');
			$data['user'] = $this->Dbmodel->getUserById($id);
			
			$this->Viewmodel->displayPage('add/user', $data);
		} else {
			$forename = $_POST['forename'];
			$surname = $_POST['surname'];
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$permissions = $_POST['permissions'];
						
			$data = array (
				'forename' => $forename,
				'surname' => $surname,
				'username' => $username,
				'email' => $email,
				'password' => $password,
				'permissions' => $permissions
			);
			
			$this->db->where('id', $id);
			$this->db->update('users', $data);
			
			redirect('users');
		}
	}
	function preregistered_email($str)
	{
		$this->db->where('email', $str);
		$existing_email = $this->db->get('users');
		
		if ($existing_email->num_rows() > 0 ){
			$this->form_validation->set_message('preregistered_email', 'That email address has already been registered. Do you need a password reminder?');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	function preregistered_username($str)
	{
		$this->db->where('username', $str);
		$existing_email = $this->db->get('users');
		
		if ($existing_email->num_rows() > 0 ){
			$this->form_validation->set_message('preregistered_username', 'That username has already been registered. Please pick another.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
}
?>