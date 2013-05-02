<?php
class Node extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->database();
		$this->load->library('session');
		$this->load->helper('date');
		$this->load->model('Datemodel');
		
		$this->load->model('Seshmodel');
		$this->Seshmodel->checkSesh();
		
		$this->load->model('Permission');
		$this->Permission->level("nodes:read all, nodes:read own", false);
		
		$this->load->model('Viewmodel');
		$this->load->model('Dbmodel');
	}

	function index()
	{	
		if (! $this->Permission->level("nodes:read all")){
			$this->db->where('created_by', $this->session->userdata('id'));
		}
		$this->db->order_by('content_type');
		$data['nodes'] = $this->db->get('node');
		
		$this->Viewmodel->displayPage('show/nodes', $data);
	}
	function name($id) {
		$node = $this->Dbmodel->getNodeByName($id);
		
		if (! $this->Permission->level("nodes:read all")){
			if ($node->created_by != $this->session->userdata('id')){
				$this->Permission->level("nodes:read all", false);
			}
		}
		
		$contentType = $this->Dbmodel->getContentTypeById($node->content_type);
		
		$data['node'] = $node;
		$data['contentType'] = $contentType;
		
		$this->Viewmodel->displayPage('show/view/page', $data);
	}
}
?>