<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dbmodel extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
		$this->load->database();
    }
	
	// Content Types
	function getContentType($type){
		$this->db->where('safe_name', $type);
		$contentType = $this->db->get('content_type', 1);
		$contentType = $contentType->row();
		
		return $contentType;
	}
	function getContentTypeById($id){
		$this->db->where('id', $id);
		$contentType = $this->db->get('content_type', 1);
		$contentType = $contentType->row();
		
		return $contentType;
	}
	
	// Nodes
	function getNodeById($id){
		$this->db->where('id', $id);
		$node = $this->db->get('node', 1);
		$node = $node->row();
		
		return $node;
	}
	function getNodeByName($id){
		$this->db->where('safe_title', $id);
		$node = $this->db->get('node', 1);
		$node = $node->row();
		
		return $node;
	}
	function getNodesByContentType($id){
		$this->db->where('content_type', $id);
		$nodes = $this->db->get('node');
		
		return $nodes;
	}
	
	// Views
	function getViewById($id){
		$this->db->where('id', $id);
		$view = $this->db->get('views', 1);
		$view = $view->row();
		
		return $view;
	}
	function getViewTypeById($id){
		$this->db->where('id', $id);
		$viewType = $this->db->get('view_type', 1);
		$viewType = $viewType->row();
		
		return $viewType;
	}
	
	// Users
	function getUserById($id){
		$this->db->where('id', $id);
		$user = $this->db->get('users', 1);
		$user = $user->row();
		
		return $user;
	}
	
	//Permissions
	function getPermissionsById($id){
		$this->db->where('id', $id);
		$perm = $this->db->get('permissions', 1);
		
		if ($perm->num_rows() > 0){
			$perm = $perm->row();
			return $perm;
		}
			
		return false;
	}
	
	//fieldType
	function getFieldTypeById($id){
		$this->db->where('id', $id);
		$ft = $this->db->get('field_type', 1);
		
		if ($ft->num_rows() > 0){
			$ft = $ft->row();
			return $ft;
		}
			
		return false;
	}
}
?>