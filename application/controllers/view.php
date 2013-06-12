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
		
		$this->load->model('Datemodel');
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
		
		//stop empty variables displaying by checking all have filled values
		$node[0]['values'][0]['value'] =
		$node[0]['title'] =
		$node[0]['view_button'] = 
		$node[0]['edit_button'] = 
		$node[0]['delete_button'] = "";
		
		$contentType = $this->Dbmodel->getContentTypeById($view->content_type);
		if (! $this->Permission->level("nodes:read all")){
			$this->db->where('created_by', $this->session->userdata('id'));
		}
		$nodes = $this->Dbmodel->getNodesByContentType($view->content_type);
		
		$viewFields = unserialize($view->fields);
		$contentFields = unserialize($contentType->fields);
		$viewSettings = unserialize($view->settings);

		$thisNode = 0;

		foreach ($nodes->result() as $result){
			$node[$thisNode]['title'] = $result->title;
			$nodeContent = unserialize($result->content);
			for ($i = 0; $i < count($contentFields['type']); $i++){
				if (isset($viewFields[$contentFields['safe_name'][$i]])){
					if ($viewFields[$contentFields['safe_name'][$i]] == "on"){
						$node[$thisNode]['values'][$i]['value'] = $nodeContent[$contentFields['safe_name'][$i]];
					}
				}
			} 
			$node[$thisNode]['view_button'] = $this->Viewmodel->addRedirect('node/'.$result->safe_title, "Link", "nodes:view all, nodes:view own"); 
			$node[$thisNode]['edit_button'] = $this->Viewmodel->addRedirect('add/node_edit/'.$result->id, "Edit", "nodes:edit all, nodes:edit own");
			$node[$thisNode]['delete_button'] = $this->Viewmodel->addRedirect('delete/node/'.$result->id, "Delete", "nodes:delete all, nodes:delete own");

			$thisNode++;
		}
		
		$addNewRowBtn = $this->Viewmodel->addRedirect("add/node/".$contentType->id, "Add new row", "nodes:create");
		
		$data = array(
		  'view_title' => $view->name,
		  'view_description' => $view->description,
		  'content' => $node,
		  'add_new_row_button' => $addNewRowBtn
		);
		
		if ($view->view_type == 1){ //then it's a table
			for ($i = 0; $i < count($contentFields['type']); $i++){ 
				if (isset($viewFields[$contentFields['safe_name'][$i]])){
					if ($viewFields[$contentFields['safe_name'][$i]] == "on"){
						$fields[$i]['field_name'] = $contentFields['name'][$i];
					}
				}
			}
			$data['fields'] = $fields;
			$this->Viewmodel->displayPage('show/view/table', $data, true);
		} else if ($view->view_type == 2){ //then it's a taskboard
			$data = array(
				'view' => $view,
				'width' => (100/count($viewFields)),
				'contentType' => $contentType,
				'nodes' => $nodes,
			);
			$data['width'] = (100/count($viewFields));
			$data['scripts'] = array ("jquery-ui", "touchpunch", "taskboard");

			$this->Viewmodel->displayPage('show/view/taskboard', $data);
		} else if ($view->view_type == 3){ //then it's a Check list
			$data = array(
				'view' => $view,
				'width' => (100/count($viewFields)),
				'contentType' => $contentType,
				'nodes' => $nodes,
			);
			$this->Viewmodel->displayPage('show/view/checklist', $data);
		} else if ($view->view_type == 4){ //then it's a Timeline
			$data = array(
				'view' => $view,
				'width' => (100/count($viewFields)),
				'contentType' => $contentType,
				'nodes' => $nodes,
			);
			$this->Viewmodel->displayPage('show/view/timeline', $data);
		}
	}
}
?>