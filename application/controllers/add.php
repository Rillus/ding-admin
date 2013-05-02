<?php
class Add extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error">
  <button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>');
		
		$this->load->database();
		$this->load->library('session');
		$this->load->helper('date');
		
		$this->load->model('Seshmodel');
		$this->Seshmodel->checkSesh();
		
		$this->load->model('Permission');
		
		$this->load->model('Dbmodel');
		$this->load->model('Viewmodel');
	}

	function index()
	{	
		$this->Viewmodel->redirect();
	}
	function content_type() {
		$this->Permission->level("content types:create", false);

		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('fieldType[]', 'Field Type', 'required|integer');
		$this->form_validation->set_rules('fieldName[]', 'Field Name', 'required');
		$this->form_validation->set_rules('fieldDescription[]', 'Field Description', 'required');
		$this->form_validation->set_rules('fieldRequired[]', 'Required', '');
		$this->form_validation->set_rules('fieldCount', 'Field Count', '');
		
		if ($this->form_validation->run() == FALSE) {
			$data['fields'] = $this->db->get('field_type');
			$data['contentTypes'] = $this->db->get('content_type');
			$data['redirect'] = $this->input->post('redirect');
			
			$this->Viewmodel->displayPage('add/content_type', $data);
		} else {
			$name = $_POST['name'];
			$description = $_POST['description'];
			$safeName = preg_replace('/[^a-zA-Z0-9_-]/s', '', strtolower(str_replace(" ", "_", $name)));
			
			$fieldType = $this->input->post('fieldType');
			$fieldName = $this->input->post('fieldName');
			$fieldDescription = $this->input->post('fieldDescription');
			$fieldRequired = $this->input->post('fieldRequired');
			
			foreach ($fieldRequired as $key => $value){
				$reqd = strtolower($fieldRequired[$key]);
				if (($reqd == "") || ($reqd == "no") || ($reqd == "false") || ($reqd == "0") || ($reqd == "-")){
					$fieldRequired[$key] = "0";
				} else {
					$fieldRequired[$key] = "1";
				}
			}
			$fieldSafeName = $fieldName;
			foreach ($fieldSafeName as $key => $value){
				$fieldSafeName[$key] = preg_replace('/[^a-zA-Z0-9_-]/s', '', strtolower(str_replace(" ", "_", $value)));
			}
			
			$fields = array(
				'type' => $fieldType,
				'name' => $fieldName,
				'safe_name' => $fieldSafeName,
				'description' => $fieldDescription,
				'required' => $fieldRequired
			);
						
			$fields = serialize($fields);
						
			$data = array (
				'name' => $name,
				'safe_name' => $safeName,
				'description' => $description,
				'fields' => $fields
			);

			$this->db->insert('content_type', $data);
			
			$this->Viewmodel->redirect();
		}
	}
	function edit_content_type($id = "") {
		$minPermission = "content types:edit own, content types:edit all";
		$this->Permission->level($minPermission, false);
		
		if ($id == ""){
			$this->Viewmodel->displayMessage('No id set - try going <a href="'.site_url('node').'">here</a>');
			return;
		}
		if (! $contentType = $this->Dbmodel->getContentTypeById($id)){
			$this->Viewmodel->displayMessage('No matching content type found.');
			return;
		}
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('fieldType[]', 'Field Type', 'required|integer');
		$this->form_validation->set_rules('fieldName[]', 'Field Name', 'required');
		$this->form_validation->set_rules('fieldDescription[]', 'Field Description', 'required');
		$this->form_validation->set_rules('fieldRequired[]', 'Required', '');
		$this->form_validation->set_rules('fieldCount', 'Field Count', '');
		
		if ($this->form_validation->run() == FALSE) {
			$data['fields'] = $this->db->get('field_type');
			$data['redirect'] = $this->input->post('redirect');
			
			$this->Viewmodel->displayPage('add/content_type', $data);
		} else {
			$name = $_POST['name'];
			$description = $_POST['description'];
			$safeName = preg_replace('/[^a-zA-Z0-9_-]/s', '', strtolower(str_replace(" ", "_", $name)));
			
			$fieldType = $this->input->post('fieldType');
			$fieldName = $this->input->post('fieldName');
			$fieldDescription = $this->input->post('fieldDescription');
			$fieldRequired = $this->input->post('fieldRequired');
			
			foreach ($fieldRequired as $key => $value){
				$reqd = strtolower($fieldRequired[$key]);
				if (($reqd == "") || ($reqd == "no") || ($reqd == "false") || ($reqd == "0") || ($reqd == "-")){
					$fieldRequired[$key] = "0";
				} else {
					$fieldRequired[$key] = "1";
				}
			}
			$fieldSafeName = $fieldName;
			foreach ($fieldSafeName as $key => $value){
				$fieldSafeName[$key] = preg_replace('/[^a-zA-Z0-9_-]/s', '', strtolower(str_replace(" ", "_", $value)));
			}
			
			$fields = array(
				'type' => $fieldType,
				'name' => $fieldName,
				'safe_name' => $fieldSafeName,
				'description' => $fieldDescription,
				'required' => $fieldRequired
			);
						
			$fields = serialize($fields);
						
			$data = array (
				'name' => $name,
				'safe_name' => $safeName,
				'description' => $description,
				'fields' => $fields
			);

			$this->db->insert('content_type', $data);
			
			$this->Viewmodel->redirect();
		}
	}
	function node($id = "") {
		$this->Permission->level("nodes:create", false);
		
		if ($id == ""){
			$contentType = $this->db->get('content_type');
			$content = "<p>Please select a content type to add a node to:</p>";
			$content .= "<ul>";
			foreach ($contentType->result() as $type){
				$content .= "<li><a href='".site_url('add/node/'.$type->id)."'>".$type->name."</a></li>";
			}
			$content .= "</ul>";
			
			$this->Viewmodel->displayMessage($content, "Add a node");
			return;
		}
						
		if (! $contentType = $this->Dbmodel->getContentTypeById($id)){
			$this->Viewmodel->displayMessage('Not a valid content type - try <a href="'.site_url('add/node/page').'">Page</a>');
			return;
		}
		
		$data['contentType'] = $contentType;
		
		$fields = unserialize($contentType->fields); 
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		
		for($i = 0; $i < count($fields['type']); $i++){
			$type = $fields['type'][$i];
			$name = $fields['name'][$i];
			$safeName = $fields['safe_name'][$i];
			$required = $fields['required'][$i];
			$rules = "trim"; // work out rules and add them here!
			if ($required == "1"){
				$rules .= "|required";
			}
			if ($type == "3"){
				$rules .= "|exact_length[10]";
			} else if ($type == "4"){
				$rules .= "|exact_length[16]";
			} else if ($type == "6"){
				$rules .= "|integer";
			} else if ($type == "7"){
				$rules .= "|decimal";
			} else if ($type == "9"){
				$rules .= "|callback_valid_url";
			} else if ($type == "10"){
				$rules .= "|valid_email";
			}
			$this->form_validation->set_rules($safeName, $name, $rules);
		}

		if ($this->form_validation->run() == FALSE) {
			$data['redirect'] = $this->input->post('redirect');
			$this->Viewmodel->displayPage('add/node', $data);
		} else {
			$title = $this->input->post('title');
			$safeTitle = preg_replace('/[^a-zA-Z0-9_-]/s', '', strtolower(str_replace(" ", "_", $title)));
			
			$type = $contentType->id;
			
			for($i = 0; $i < count($fields['type']); $i++){
				$safeName = $fields['safe_name'][$i];
				
				$content[$safeName] = $this->input->post($safeName);
			}
			
			//print_r($content);
			$content = serialize($content);
			
			$data = array (
				'title' => $title,
				'safe_title' => $safeTitle,
				'content_type' => $type,
				'content' => $content,
				'created_by' => $this->Seshmodel->getCurrentUserId()
			);

			$this->db->insert('node', $data);
			
			$this->Viewmodel->redirect();
		}
	}
	function node_edit($id = "") {
		$this->Permission->level("nodes:edit all, nodes:edit own", false);
		
		if ($id == ""){
			$this->Viewmodel->displayMessage('No id set - try going <a href="'.site_url('node').'">here</a>');
			return;
		}
		
		if (! $node = $this->Dbmodel->getNodeById($id)){
			$this->Viewmodel->displayMessage('No node found matching that id.');
			return;
		}
		
		if (! $this->Permission->level("nodes:edit all")){
			if ($node->created_by != $this->session->userdata('id')){
				$this->Permission->level("nodes:edit all", false);
			}
		}
		
		if (! $contentType = $this->Dbmodel->getContentTypeById($node->content_type)){
			$this->Viewmodel->displayMessage('No content type is associated with that node.');
			return;
		}
		
		$data['node'] = $node;
		$data['contentType'] = $contentType;
		
		$fields = unserialize($contentType->fields); 
				
		$this->form_validation->set_rules("title", "title", "trim|required");
		for($i = 0; $i < count($fields['type']); $i++){
			$type = $fields['type'][$i];
			$name = $fields['name'][$i];
			$safeName = $fields['safe_name'][$i];
			$required = $fields['required'][$i];
			$rules = "trim"; // work out rules and add them here!
			if ($required == "1"){
				$rules .= "|required";
			}
			if ($type == "3"){
				$rules .= "|exact_length[10]";
			} else if ($type == "4"){
				$rules .= "|exact_length[16]";
			} else if ($type == "6"){
				$rules .= "|integer";
			} else if ($type == "7"){
				$rules .= "|decimal";
			} else if ($type == "9"){
				$rules .= "|callback_valid_url";
			} else if ($type == "10"){
				$rules .= "|valid_email";
			}
			$this->form_validation->set_rules($safeName, $name, $rules);
		}
		
		
		for($i = 0; $i < count($fields['type']); $i++){
			$name = $fields['name'][$i];
			$safeName = $fields['safe_name'][$i];
			$this->form_validation->set_rules($safeName, $name, 'trim|required');
		}
		if($this->input->post('fromForm')){
			$data['redirect'] = $this->input->post('redirect');
			$this->Viewmodel->displayPage('add/edit_node', $data);
			return;
		}
		if ($this->form_validation->run() == FALSE) {
			$data['redirect'] = $this->input->post('redirect');
			$this->Viewmodel->displayPage('add/edit_node', $data);
			return;
		}
		
		$title = $this->input->post('title');
		$safeTitle = preg_replace('/[^a-zA-Z0-9_-]/s', '', strtolower(str_replace(" ", "_", $title)));
		
		$type = $contentType->id;
		
		for($i = 0; $i < count($fields['type']); $i++){
			$safeName = $fields['safe_name'][$i];
			
			$content[$safeName] = $this->input->post($safeName);
		}
		
		//print_r($content);
		$content = serialize($content);
		
		$data = array (
			'title' => $title,
			'safe_title' => $safeTitle,
			'content_type' => $type,
			'content' => $content
		);

		$this->db->where('id', $id);
		$this->db->update('node', $data);
		
		$this->Viewmodel->redirect();
	}
	function view () {
		$this->Permission->level("views:create", false);
					
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('viewType', 'View Type', 'required|integer');
		$this->form_validation->set_rules('contentType', 'Content Type', 'required|integer');
		
		if ($this->form_validation->run() == FALSE) {
			$data['views'] = $this->db->get('view_type');
			$data['contentTypes'] = $this->db->get('content_type');
			$data['redirect'] = $this->input->post('redirect');
			$this->Viewmodel->displayPage('add/view', $data);
		} else {
		
			$name = $_POST['name'];
			$description = $_POST['description'];
			$safeName = preg_replace('/[^a-zA-Z0-9_-]/s', '', strtolower(str_replace(" ", "_", $name)));
			$viewType = $_POST['viewType'];
			$contentType = $_POST['contentType'];
			
			$columns = $this->input->post('columns');
			$columns = serialize($columns);
			
			$data = array (
				'name' => $name,
				'safe_name' => $safeName,
				'description' => $description,
				'view_type' => $viewType,
				'content_type' => $contentType,
				'fields' => $columns,
				'created_by' => $this->session->userdata('id')
			);

			$this->db->insert('views', $data);
			
			$this->Viewmodel->redirect();
		}
	}
	function valid_url($url)
    {
        if (!isset($url)){
			return TRUE;
		}
		$pattern = "/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/";
        if (!preg_match($pattern, $url))
        {
            return FALSE;
        }

        return TRUE;
    }/*
	function real_url($url)
    {
        return @fsockopen("$url", 80, $errno, $errstr, 30);
    }*/
}
?>