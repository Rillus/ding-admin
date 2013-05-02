<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Form_validation extends CI_Form_validation {

    public function __construct()
    {
        parent::__construct();

        $this->_error_prefix = '<span class="error">';
        $this->_error_suffix = '</span>';
    }
}