<?php
class Json extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->database();
		$this->load->library('session');		
		$this->load->model('Seshmodel');
		$this->Seshmodel->checkSesh();

		$this->load->model('Dbmodel');
		$this->load->model('Viewmodel');
	}

	function index()
	{	
		//redirect('node');
	}
	function content_type($id) {
		$contentType = $this->Dbmodel->getContentTypeById($id);
		$contentType = $contentType->fields;
		$contentType = unserialize($contentType);
		
		echo json_encode($contentType);
	}
}
?>