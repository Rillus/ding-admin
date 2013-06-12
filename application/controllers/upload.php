<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
			
		$this->load->helper('form');
		$this->load->database();
		$this->load->library('session');
		$this->load->helper('date');
		$this->load->model('Seshmodel');
		$this->load->model('Viewmodel');
		$this->Seshmodel->checkSesh();
		date_default_timezone_set('Europe/London');
	}
	public function index()
	{		
		if ($this->uri->segment(2) == ""){
			$id = $this->input->post('id');		
		} else {
			$id = $this->uri->segment(2);
		}
		$user = $this->Seshmodel->getCurrentUser();
		$url = rawurlencode($user->id);

		$path = "uploads/$url";
		$clientpath = "./".$path;
		//echo dirname( __FILE__ );
		if (! file_exists($clientpath)){
			if(! mkdir($clientpath,0777, true)){
				$this->Viewmodel->displayMessage("I'm afraid we failed to create the directory required to store your images. Probably worth getting in touch with us to sort this.");
				
				return;
			}
		}
		
		$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
		
		if ($fn) {
			// I am an ajax request
			if (substr($fn, -3, 3) != "gif" && substr($fn, -3, 3) != "png" && substr($fn, -3, 3) != "jpg"){
				echo "error - incorrect file type";
				return;
			}
			file_put_contents(
				$path.'/'. $fn,
				file_get_contents('php://input')
			);
			echo "$fn uploaded";
			
			$path = $path.'/'.$fn;
		} else {
		
			$config['upload_path'] = $clientpath.'/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']	= '0';
			$config['max_width']  = '0';
			$config['max_height']  = '0';
			
			$this->load->library('upload', $config);
			
			
			if ( ! $this->upload->do_upload()){
				$error = $this->upload->display_errors();
				
				$this->Viewmodel->displayMessage($error);
				
				return;
			} else {
				$data = array('upload_data' => $this->upload->data());
				$path = $data['upload_data'];
				$path = $path.'/'.$path['file_name'];
			}
		}
		
		// Resizing and cropping shenannigans
		/*
		//getting the image dimensions
		list($width, $height) = getimagesize(base_url().$path); 
			
		if($width > $height) {
			$biggestSide = $width;
			$h = 75;
			$w = 200;
		} else { 
			$biggestSide = $height;
			$w = 75;
			$h = 200;
		}
		//$config['image_library'] = 'gd2';
		$config['source_image'] = $path;
		//$config['create_thumb'] = TRUE;
		$config['height'] = $h;
		$config['width'] = $w;
		$config['quality'] = '100%';
		$config['maintain_ratio'] = TRUE;
		$this->load->library('image_lib', $config);

		$this->image_lib->resize();
		
		$config['source_image'] = $path;
		$config['maintain_ratio'] = FALSE;
		
		$config['height'] = 75;
		$config['width'] = 75;
		$config['x_axis'] = 0;
		$config['y_axis'] = 0;
		$this->image_lib->initialize($config);

		$this->image_lib->crop();
		*/
		//$path = base_url().$path;
		
		//echo $this->image_lib->display_errors()."<img src='$path'/>";
		
		$data = array(
			'image' => $path,
		);
		
		//$this->db->where('id', $id);
		//$this->db->update('events', $data);
		
		$this->Viewmodel->displayMessage("Image uploaded to $path");
	}
}