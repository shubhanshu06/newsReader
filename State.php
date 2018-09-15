<?php 
    /**
     * 
     */
    class State extends CI_Controller
    {
    	
    	function __construct()
    	{
    		parent::__construct();
    		$this->load->model('common_model');
    	}
    	public function index()
    	{   
            $data['title']='State';
            $data['view']='state/add';
            $data['country']= $this->common_model->getData('country');
            $data['state'] = $this->common_model->getJoinRecordNew('state','country');
 if ($this->session->userdata('login')== 'TRUE') {
          $this->load->view('admin/index',$data);
       }  
  else{
    $url = base_url('index.php/admin');
header("location: ".$url." " );
  }
        }
        /*to add and get state table*/
        public function addState()
        {

            $this->form_validation->set_rules('countryid','Country',
                'required');
            $this->form_validation->set_rules('name','State','required|is_unique[state.name]');
            if($this->form_validation->run() === FALSE)
            {

                echo strip_tags(validation_errors());
            }
            else{
                $this->common_model->insertData('state');
            }
        }
        public function getState()
        {
            $coun = $this->common_model->getWhereRow('state','id');
            echo json_encode($coun);

        }
        public function updateState()
        {
           $this->form_validation->set_rules('name','State','required|is_unique[state.name]|is_unique[country.name]');
           if($this->form_validation->run() === FALSE)
           {

            echo strip_tags(validation_errors());
        }
        else
        {
            $array = array(
                'name' => $this->input->post('name'),
                'countryid' => $this->input->post('countryid')         
            );
            $this->common_model->updateData('state','id',$array);            
        }
    }
    public function deleteState(){
        $this->common_model->deleteData('state','id');
    }
public function pagination(){
  $columns = array('id','name','country');
  $totalData=$this->common_model->getData('state');
  
  $data=$this->common_model->GetJoinRecord('state','country','countryid','id','state.id,state.name,country.name as country','',
    $columns[$this->input->get('order[0][column]')], 
    $this->input->get('order[0][dir]'), 
    $this->input->get('length'), 
    $this->input->get('start'));

  $result = array();
  $result['draw'] = $this->input->get('draw');
  $result['recordsTotal'] = count($totalData);
  $result['recordsFiltered'] = count($totalData);
  if($this->input->get('search[value]')!=''){
    $search = $this->input->get('search[value]');
       $data=$this->common_model->GetJoinRecord('state','country','countryid','id','state.id,state.name,country.name as country', $this->input->get('search[value]'),
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
    $result['data'][$i][2] = $value['country'];
    $result['data'][$i][3] = '<button name="button" data-toggle="modal" data-target="#popupdate" class="btn btn-success update_button" value='.$value['id'].'>Update</button> 
    <button name="button" class="btn btn-danger delete_button" value='.$value['id'].'>Delete</button>';
    $i++;
  }

  echo json_encode($result);
}

}
?>

