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
		
		$data['contentType'] = get_object_vars($contentType);
		$field_arr = unserialize($contentType->fields);
		$content_arr = unserialize($node->content);
		for ($i = 0; $i < count($field_arr['type']); $i++){ 
			$type = $field_arr['type'][$i];
			$content[$i]['field_name'] = $field_arr['name'][$i];
			if ($type == 11){
				$content[$i]['value'] = '<img src="'.base_url().'uploads/'.$node->created_by.'/'.$content_arr[$field_arr['safe_name'][$i]].'" alt="" />';
			} else {
				$content[$i]['value'] = $content_arr[$field_arr['safe_name'][$i]];
			}
		 }
			 
		$data = array(
		  'title'   => $node->title,
		  'created' => $this->Datemodel->formatDate($node->create_date),
		  'content_type' => $contentType->name,
		  'content' => $content
		);
		
		$this->Viewmodel->displayPage('show/view/page', $data, true);
	}
}
?>