<?php 
    /**
     * 
     */
    class City extends CI_Controller
    {
    	
    	function __construct()
    	{
    		parent::__construct();
    		$this->load->model('common_model');
    	}
    	public function index()
    	{   
           $data['title']='City';
           $data['view']='city/add';
           $data['country'] = $this->common_model->getData('country');
           $data['state'] = $this->common_model->getJoinRecordNew('state','country');
           $data['city']= $this->common_model->getJoinData('city','state','country');

        if ($this->session->userdata('login')== 'TRUE') {
          $this->load->view('admin/index',$data);
       }  
  else{
    $url = base_url('index.php/admin');
header("location: ".$url." " );
  }
       }
       public function getState()
       {
         $this->common_model->getWhereData('state','countryid');
     }
     public function getCity1()
       {
         $this->common_model->getWhereData('city','stateid');
     }
     public function addCity()
     {

            $this->form_validation->set_rules('countryid','Country',
            'required');
            $this->form_validation->set_rules('stateid','State',
                'required');
            $this->form_validation->set_rules('name','City','required|is_unique[city.name]');
            if($this->form_validation->run() === FALSE)
            {

                echo strip_tags(validation_errors());
            }
            else{
                $this->common_model->insertData('city');
            }

        }
        public function getCity()
        {
            $coun = $this->common_model->getWhereRow('city','id');

            $coun1 =  $this->common_model->getWhereRow1('state',$coun['stateid']);
            $city = array(

                'city' => $coun,
                'state' => $coun1

            );
            echo json_encode($city);
        }
        public function updateCity()
        {
           $this->form_validation->set_rules('name','City','required|is_unique[country.name]|is_unique[state.name]|is_unique[city.name]');
           if($this->form_validation->run() === FALSE)
           {
            echo strip_tags(validation_errors());
        }
        else{
            $array = array(
             'name' => $this->input->post('name'),
             'stateid' => $this->input->post('stateid'),
             'countryid' => $this->input->post('countryid')

         );
            $this->common_model->updateData('city','id',$array);
        }
    }
    public function deleteCity(){
        $this->common_model->deleteData('city','id');
    }
    public function pagination(){
  $columns = array('id','name','state','country');
  $totalData=$this->common_model->getData('city');
  
  $data=$this->common_model->GetJoinRecordThree('city','state','country','stateid','id','countryid','id','city.id,city.name, state.name as state, country.name as country','',
    $columns[$this->input->get('order[0][column]')], 
    $this->input->get('order[0][dir]'), 
    $this->input->get('length'), 
    $this->input->get('start'));

  $result = array();
  $result['draw'] = $this->input->get('draw');
  $result['recordsTotal'] = count($totalData);
  $result['recordsFiltered'] = count($totalData);
  if($this->input->get('search[value]')!=''){

       $data=$this->common_model->GetJoinRecordThree('city','state','country','stateid','id','countryid','id','city.id,city.name, state.name as state, country.name as country', $this->input->get('search[value]'),
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
    $result['data'][$i][2] = $value['state'];
    $result['data'][$i][3] = $value['country'];
    $result['data'][$i][4] = '<button name="button" data-toggle="modal" data-target="#popupdate" class="btn btn-success update_button" value='.$value['id'].'>Update</button> 
    <button name="button" class="btn btn-danger delete_button" value='.$value['id'].'>Delete</button>';
    $i++;
  }

  echo json_encode($result);
}

}
?>