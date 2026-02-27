<?php
defined('BASEPATH') OR exit('No direct script access allowed');   

class Login extends CI_Controller {
                                 
    public function __construct()
    {
        parent::__construct();        
        $this->core->https();  
    }       

    public function index(){
        $this->load->view('admin/login'); 
    }
    
    public function access()
    {
        $post = json_decode(file_get_contents('php://input'), true);
         
        $email      =  addslashes($post['email']);
        $password   =  md5(addslashes($post['password'] ) );
       
        if($this->core->select('id','account','presence = 1 and email="'.$email.'"')){
              
             $id            = $this->core->select('id','account','presence = 1 and email="'.$email.'"'); 
             $db_password   = $this->core->select('password','account','id ="'.$id.'"'); 
             if($password == $db_password){ 
                if( $this->core->select('status','account','id ="'.$id.'"') == '1'){ 
                    if( $this->core->select('token','account','id = "'.$id.'" ') == "" ) {   
                        $token = md5($id.$db_password.date('YMdHis')); 
                        $data = array(  
                            "token" => $token,    
                        );
                        $this->db->update('account', $data,'id ='.$id); 
                    }else{
                        $token =  $this->core->select('token','account','id = "'.$id.'" ');
                    }
                     
                    $json = array(
                        'error' => 0,
                        'warning' => "",
                        'data' => array( 
                            'id'            => $id,
                            'token'          => $token,
                        )
                    );

                    setcookie('mirrel5Login', $token, time() + (86400 * 30), "/"); // 86400 = 1 day
                 
                    
                }else{
                    $json = array(
                        'error' => 1,
                        'data' => [],
                        'warning' => 'Your account has been suspense, please contact your administrator!',
                    ); 
                }
             }else{ 
                // WRONG PASSWORD 
                $json = array(
                    'error' => 1,
                    'data' => [],
                    'warning' => 'Wrong password, please check your password!',
                    'email' => $email
                ); 
              
             }
           
        }else{
            // echo 'tidak terdafatr';
            $json = array(
                'error' => 1,
                'data' => [],
                'warning' => 'Wrong email login!',
      
            );  
        } 

        echo json_encode($json);
    } 
    
    function logout($id_pages="",$id_content="")
    {
        
        
        $data = array(  
            "token" => md5(date('YdMH:i:s').'cms3-closed'),   
        );
        $this->db->update('account', $data,'id ='. $this->session->userdata('id_account'));
        
        
        $clear_data = array(
           'id_account'     => "", 
           'token'  => "", 
        );
          
        
        $this->session->set_userdata($clear_data);
      
        
        redirect(base_url() );
    }  
    
    function token(){
        $token = $this->input->post('token');
        
        if(   $this->core->select('id','account','token = "'.$token.'"')  ){
            echo true;
        }else{
            echo false;
        }
        
    }
}   
