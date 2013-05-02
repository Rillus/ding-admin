<?php
class Delete extends CI_Controller {

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
		$this->Permission->level("nodes:delete own", false);
		
		$this->load->model('Dbmodel');
		$this->load->model('Viewmodel');
	}

	function index()
	{	
		redirect('node');
	}
	function node($id = "") {
		$this->Permission->level("nodes:delete own, nodes:delete all", false);
		
		if ($id == ""){
			$body = "No id specified.";
			$this->Viewmodel->displayMessage($body);
		}
		
		if ($user = $this->Seshmodel->getCurrentUser()){;
			if (! $thisNode = $this->Dbmodel->getNodebyId($id)){
				$body = "Couldn't find node to match that id.";
				$this->Viewmodel->displayMessage($body);
				return;
			}
			if ($user->id != $thisNode->created_by){
				if (! $this->Permission->level("nodes:delete all")){
					$body = "You can't delete this node - it's not yours to delete!";
					$this->Viewmodel->displayMessage($body);
					return;				
				}
			}
			$this->db->where('id', $id);
			$this->db->delete('node');
		}
		
		$this->Viewmodel->redirect();
	}
}
?>