<?php
   /**
    * 
    */
   class Admin extends CI_Controller
   {
   	
   	function __construct()
   	{
   		parent::__construct();
      $this->load->model('common_model');

   	}
   	public function index()
   	{  
        $data['title']='Admin';
          $data['msg']="";
		$this->load->view('admin/signin/login',$data);
   	}
    public function login(){

     
      $this->form_validation->set_rules('email', 'email', 'required');
      $this->form_validation->set_rules('password', 'password', 'required');
      
       

      if ($this->form_validation->run() === FALSE)
      {   
        $data['msg']= validation_errors();
              $this->load->view('admin/signin/login',$data);

         
        }
      else
      {   
             $email =$this->input->post('email');
             $password =$this->input->post('password');
            $data['profile']=$this->common_model->getWhereRow('login','email');

             if(empty( $data['profile'])){
              $data['msg']= "email does'nt exist";
             
             
               $this->load->view('admin/signin/login',$data);
             }
               elseif ($email==$data['profile']['email'] && $password==$data['profile']['password']) {
                $data['profile']['login'] = 'TRUE'; 
                $this->session->set_userdata($data['profile']);
                          $url= base_url();
                          header('location:'.$url.'');
               }
               else{
              $data['msg']= "wrong email or password";  
                        
            $this->load->view('admin/signin/login',$data);
          
                           }       
      }
}
        public function logout(){
          $sessionData = array('email','password','login');
           $this->session->unset_userdata($sessionData);
           $url= base_url();
        header('location:'.$url.'');

        }   
   }
?>