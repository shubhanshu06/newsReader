<?php
class Category extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('common_model');
	}
	public function index()
	{
		$data['title']='Category';
		$data['view']='category/add';
    $data['category']= $this->common_model->getData('category');
    if ($this->session->userdata('login')== 'TRUE') {
          $this->load->view('admin/index',$data);
       }  
  else{
    $url = base_url('index.php/admin');
     header("location: ".$url." " );
  }
	}
	public function addCategory()
	{
		 $this->form_validation->set_rules('name','Category','required|is_unique[category.name]');
                if($this->form_validation->run() === FALSE)
                {
                    echo strip_tags(validation_errors());
                 }
                else{
                    $this->common_model->insertData('category');
                }

	}
	public function getCategory()
  {
     $this->common_model->getWhereData1('category','id');	    
	}
	public function deleteCategory(){
		$this->common_model->deleteData('category','id');
	}
	public function pagination(){
  $columns = array('id','name');
  $totalData=$this->common_model->getData('category');
  $data=$this->common_model->getAllWhere('category','',
    $columns[$this->input->get('order[0][column]')], 
    $this->input->get('order[0][dir]'), 
    $this->input->get('length'), 
    $this->input->get('start'));

  $result = array();
  $result['draw'] = $this->input->get('draw');
  $result['recordsTotal'] = count($totalData);
  $result['recordsFiltered'] = count($totalData);
  if($this->input->get('search[value]')!=''){
       $data=$this->common_model->getAllWhere('category',
    $this->input->get('search[value]'),
     $columns[$this->input->get('order[0][column]')], 
     $this->input->get('order[0][dir]'), 
     $this->input->get('length'), 
     $this->input->get('start'));
    $result['recordsFiltered'] = count($data);
  }
  $i = 0;
  foreach ($data as $value) {
    $result['data'][$i][0] = $value['id'];
    $result['data'][$i][1] = $value['name'];
    $result['data'][$i][2] = '<button name="button" data-toggle="modal" data-target="#popupdate" class="btn btn-success update_button" value='.$value['id'].'>Update</button> 
    <button name="button" class="btn btn-danger delete_button" value='.$value['id'].'>Delete</button>';
    $i++;
  }

  echo json_encode($result);
}

}

?>