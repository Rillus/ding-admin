<?php
class Post extends CI_Controller {

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
		redirect('node');
	}
	function view_settings($id) {
		$widgetId = $this->input->post('id');
		$colour = $this->input->post('colour');
		$column = $this->input->post('column');
		$order = $this->input->post('order');
		
		$sets = array(
			'colour' => $colour,
			'column' => $column,
			'order' => $order,
		);
		
		$view = $this->Dbmodel->getViewById($id);
		
		$settings = unserialize($view->settings);
		
		foreach ($settings as $key => $value){
			if ($value['column'] == $column){
				if ($value['column'] != $settings[$widgetId]['column']){
					if ($value['order'] >= $order){
						$settings[$key]['order']++;
					} else if ($value['order'] == $order){
						//$settings[$key]['order']--;
					}
				} else {
					if (($value['order'] >= $settings[$widgetId]['order']) && ($value['order'] <= $order)){
						$settings[$key]['order']--;
					} else if (($value['order'] <= $settings[$widgetId]['order']) && ($value['order'] >= $order)){
						$settings[$key]['order']++;
					}
				}
			};
		};

		$settings[$widgetId] = $sets;
		
		$data = array (
			'settings' => serialize($settings)
		);
		
		$this->db->where('id', $id);
		$this->db->update('views', $data);
		
		echo print_r($settings);
		//echo '  '.$id;
	}
	function checklist_settings($id) {
		$checkboxId = $this->input->post('id');
		$checked = $this->input->post('check');
		$view = $this->Dbmodel->getViewById($id);
		
		$settings = unserialize($view->settings);
		if ($checked == "1"){
			$settings[$checkboxId] = "1";
		} else {
			unset($settings[$checkboxId]);
		}
		$data = array (
			'settings' => serialize($settings)
		);
		
		$this->db->where('id', $id);
		$this->db->update('views', $data);
		
		echo print_r($data);
		//echo '  '.$id;
	}
}
?>