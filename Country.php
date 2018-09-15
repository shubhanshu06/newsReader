    <?php 
    class Country extends CI_Controller
    {

     function __construct()
     {
      parent::__construct();
      $this->load->model('common_model');

    }
    public function index()
    {
      $data['title']='Country';
      $data['view']='country/add';
     // $data['country'] = $this->common_model->getData('country');
      if ($this->session->userdata('login')== 'TRUE') {
          $this->load->view('admin/index',$data);
       }  
  else{
    $url = base_url('index.php/admin');
     header("location: ".$url." " );
  }

    }
    /*to add and get country*/
    public function addCountry()
    {  
      $this->form_validation->set_rules('name','Country','required|is_unique[country.name]');
      if($this->form_validation->run() === FALSE)
      {
        echo strip_tags(validation_errors());
      }
      else{
        $this->common_model->insertData('country');
      }
    }
    public function getCountry()
    {
      $coun = $this->common_model->getWhereRow('country','id');
      echo json_encode($coun);

    }
    public function updateCountry()
    {
     $this->form_validation->set_rules('name','Country','required|is_unique[country.name]');
     if($this->form_validation->run() === FALSE)
     {
      echo strip_tags(validation_errors());
    }
    else{
     $array = array(
      'name' => $this->input->post('name')   
    );
     $this->common_model->updateData('country','id',$array);
   }
 }
 public function deleteCountry(){
  $this->common_model->deleteData('country','id');
}
public function pagination(){
  $columns = array('id','name');
  $totalData=$this->common_model->getData('country');
  $data=$this->common_model->getAllWhere('country','',
    $columns[$this->input->get('order[0][column]')], 
    $this->input->get('order[0][dir]'), 
    $this->input->get('length'), 
    $this->input->get('start'));

  $result = array();
  $result['draw'] = $this->input->get('draw');
  $result['recordsTotal'] = count($totalData);
  $result['recordsFiltered'] = count($totalData);
  if($this->input->get('search[value]')!=''){
       $data=$this->common_model->getAllWhere('country',$this->input->get('search[value]'),
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