<?php
class Permissions extends CI_Controller {

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
		
		$minPermission = "3:0, 3:1";
		$this->load->model('Permission');
		$this->Permission->level($minPermission, false);
		
		$this->load->model('Dbmodel');
		$this->load->model('Viewmodel');
	}

	function index() {	

		$this->load->library('form_validation');
				
		$this->form_validation->set_rules('name', 'new permission level', 'trim');
		
		if ($this->form_validation->run() == FALSE) {
			$data['permissions'] = $this->db->get('permissions');
			
			$this->Viewmodel->displayPage('add/permission', $data);
		} else {
			$name = $_POST['name'];
			$permissions = $this->input->post('permissions');
			
			if ((isset($name)) && ($name != "")){
				$data = array (
					'name' => $name,
				);

				$this->db->insert('permissions', $data);
			};	
			
			foreach ($permissions as $key => $value) {
				$data = array (
					'permissions' => serialize($permissions[$key]),
				);

				$this->db->where('id', $key);
				$this->db->update('permissions', $data);
			};
			
			redirect('permissions');
		}
	}
}
?>