<?php
defined('BASEPATH') OR exit('No direct script access allowed');   

class Adminbiz extends CI_Controller {
                                 
    public function __construct()
    {
        parent::__construct();   
        error_reporting(E_ALL);
		date_default_timezone_set('Asia/Jakarta');
        $this->db->query("SET time_zone = '+07:00'"); 
        $this->core->https();
    }       
 
    public function index(){  
        $css = array();
        $map = directory_map('./admin/app/', 1, TRUE);

        foreach ($map as $rec) {
            if (!is_array($rec)) {
                $temp  = explode('.',$rec); 
                if(end($temp) == 'css'){
                    array_push($css,$rec);
                } 
                
            }
        }
       
        $data = array(
            "css" => $css, 
        );
        
        $this->load->view('admin/index',$data); 
    }

    
   
}   
