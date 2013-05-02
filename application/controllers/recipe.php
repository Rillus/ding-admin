<?php
class Recipe extends CI_Controller {

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
		
		$this->load->model('Permission');
		
		$this->load->model('Dbmodel');
		$this->load->model('Viewmodel');
	}

	function index()
	{	
		if ($this->Permission->level("views:create")){
			$this->Viewmodel->displayPage('recipes/index');
		} else {
			$message = "You gots nuiffink";
			$this->Viewmodel->displayMessage($message);
		}
	}
	function id($viewType = 0, $stage = 0){
		if ($viewType == 0){
			redirect('');
		}
		
		$recipes = array (
			"table",
			"task board",
			"checklist"
		);
		$data['thisRecipe'] = $recipes[$viewType-1];
		$data['viewType'] = $viewType;
		if ($stage == 0){
			$data['contentTypes'] = $this->db->get("content_type");
		} else if ($stage == 1){
			$this->Permission->level("content types:create", false);
			$data['templateType'] = $this->input->post('templateType');
			$data['contentType'] = $this->Dbmodel->getContentTypeById($this->input->post('contentType'));
			$data['fields'] = $this->db->get('field_type');
			$data['contentTypes'] = $this->db->get('content_type');
		} else if ($stage == 2){
			$this->Permission->level("content types:create", false);
			$data['contentType'] = $this->Dbmodel->getContentTypeById($this->input->post('contentType'));

			$this->form_validation->set_rules('name', 'Name', 'trim|required|callback_is_unique');
			$this->form_validation->set_rules('description', 'Description', 'trim|required');
			$this->form_validation->set_rules('fieldType[]', 'Field Type', 'required|integer');
			$this->form_validation->set_rules('fieldName[]', 'Field Name', 'required');
			$this->form_validation->set_rules('fieldDescription[]', 'Field Description', 'required');
			$this->form_validation->set_rules('fieldRequired[]', 'Required', '');
			$this->form_validation->set_rules('fieldCount', 'Field Count', '');
			
			if($this->input->post('fromForm')){
				$data['contentType'] = $this->Dbmodel->getContentTypeById($this->input->post('contentType'));
				$data['fields'] = $this->db->get('field_type');
				$data['contentTypes'] = $this->db->get('content_type');
				
				$this->Viewmodel->displayPage('recipes/stage1', $data);
				return;
			}
			if ($this->form_validation->run() == FALSE) {
				$data['contentType'] = $this->Dbmodel->getContentTypeById($this->input->post('contentType'));
				$data['fields'] = $this->db->get('field_type');
				$data['contentTypes'] = $this->db->get('content_type');
				
				$this->Viewmodel->displayPage('recipes/stage1', $data);
				return;
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
				$viewId = $this->db->insert_id('content_type', $data);
				
				$data['thisRecipe'] = $recipes[$viewType-1];
				$data['viewType'] = $viewType;
				$data['contentType'] = $this->Dbmodel->getContentTypeById($viewId);
			}
			
		} else if ($stage == 3){
			$this->Permission->level("views:create", false);
			
			$this->form_validation->set_rules('name1', 'Name', 'trim|required');
			$this->form_validation->set_rules('description1', 'Description', 'trim|required');
			$this->form_validation->set_rules('viewType', 'View Type', 'required|integer');
			$this->form_validation->set_rules('contentType', 'Content Type', 'required|integer');
			$this->form_validation->set_rules('columns[]', 'Columns', 'required');
			$this->form_validation->set_rules('fieldCount', 'Field Count', '');
			if($this->input->post('fromForm')){
				$data['views'] = $this->db->get('view_type');
				$data['contentType'] = $this->Dbmodel->getContentTypeById($this->input->post('contentType'));
				
				$this->Viewmodel->displayPage('recipes/stage2', $data);
				return;
			}
			if ($this->form_validation->run() == FALSE) {
				$data['views'] = $this->db->get('view_type');
				$data['contentType'] = $this->Dbmodel->getContentTypeById($this->input->post('contentType'));
				
				$this->Viewmodel->displayPage('recipes/stage2', $data);
				return;
			} else {
				$name = $_POST['name1'];
				$description = $_POST['description1'];
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
				$viewId = $this->db->insert_id();
				
				$viewUrl = "view/".$viewId;
				redirect($viewUrl);
			}
			
		}
		
		$this->Viewmodel->displayPage('recipes/stage'.$stage, $data);
	}
	function is_unique($str){
		$str = preg_replace('/[^a-zA-Z0-9_-]/s', '', strtolower(str_replace(" ", "_", $str)));
		$result = $this->Dbmodel->getContentType($str);
		
		if (isset($result->safe_name)){
			$this->form_validation->set_message('is_unique', 'That name already exists. Please enter a slightly different one.');
			return false;
		}
		return true;
		
	}
}
?>