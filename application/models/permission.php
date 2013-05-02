<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
		$this->load->database();
		$this->load->library('session');
		$this->load->model('Dbmodel');
    }
	function level($minLevel, $callback = true){
		if ($minLevel == "0"){
			return true;
		}
		/* $minLevel should be passed in the form "2:1, 3:0, 4:1" etc. The first digit before the : is the type, the second is the permission level as below. The comma separates a different minimum permission level. If any of these evalutaes as true the function will return true. You'll need to check back again for more granular permission levels e.g. for specific fields or buttons
		
		These are for reference only - this is what our array keys match up to*/
		$types = array (
			"views",
			"content types",
			"nodes",
			"permissions",
			"users",
		);
		$permLevel = array (
			"read all",
			"read own",
			"create",
			"edit all",
			"edit own",
			"delete all",
			"delete own",
		);
		
		
		// first get the permission levels for this user
		$myPermType = $this->session->userdata('permissions');
		
		if ($myPermType == ""){
			if ($callback == false){
				redirect('');
			}
			return false;
		}
		
		$permission = $this->Dbmodel->getPermissionsById($myPermType);
		$permission = unserialize($permission->permissions);
		
		// break apart the passed string to try and match against our minimum permission levels
		$minLevel = explode(', ', $minLevel);
		
		// go through each of these
		for ($i = 0; $i < count($minLevel); $i++){
			$thisMinLevel = explode(':', $minLevel[$i]);
			//$thisMinLevel[0] = type;
			//$thisMinLevel[1] = permLevel;
			if (is_numeric($thisMinLevel[0])){
				if ($permission[$thisMinLevel[0]][$thisMinLevel[1]] == "1"){
					return true;
				}
			} else {
				// allow plain english permissions
				if ($permission[array_search($thisMinLevel[0], $types)][array_search($thisMinLevel[1], $permLevel)] == "1"){
					return true;
				}
			}
		}
		
		// if the $callback is set to false, then we redirect back to the base Url
		if ($callback == false){
			redirect('');
		}
		
		return false;
	}
}
?>