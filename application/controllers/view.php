<?php
class View extends CI_Controller {

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
		
		$minPermission = "views:read all, views:read own";
		$this->load->model('Permission');
		$this->Permission->level($minPermission, false);
		
		$this->load->model('Dbmodel');
		$this->load->model('Viewmodel');
	}

	function index() {	
		if (! $this->Permission->level("views:read all")){
			$this->db->where('created_by', $this->session->userdata('id'));
		}
		$this->db->order_by('view_type');
		$data['views'] = $this->db->get('views');
		
		$this->Viewmodel->displayPage('show/views', $data);
	}
	function name ($id) {
		$view = $this->Dbmodel->getViewById($id);
		
		if (! $this->Permission->level("views:read all")){
			if ($view->created_by != $this->session->userdata('id')){
				$this->Permission->level("views:read all", false);
			}
		}
		
		$contentType = $this->Dbmodel->getContentTypeById($view->content_type);
		if (! $this->Permission->level("nodes:read all")){
			$this->db->where('created_by', $this->session->userdata('id'));
		}
		$nodes = $this->Dbmodel->getNodesByContentType($view->content_type);
		
		$data['view'] = $view;
		$data['contentType'] = $contentType;
		$data['nodes'] = $nodes;
		
		if ($view->view_type == 1){ //then it's a table
			$this->Viewmodel->displayPage('show/view/table', $data);
		} else if ($view->view_type == 2){ //then it's a taskboard
			$this->Viewmodel->displayPage('show/view/taskboard', $data);
		} else if ($view->view_type == 3){ //then it's a Check list
			$this->Viewmodel->displayPage('show/view/checklist', $data);
		}
	}
}
?>