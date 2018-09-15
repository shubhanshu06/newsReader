<?php
class Front extends CI_Controller{

     function __construct(){

	parent::__construct();
}
      public function index(){
      $data['title']= 'Newsview';
      $this->load->view('front/index',$data);
}

} 
?>