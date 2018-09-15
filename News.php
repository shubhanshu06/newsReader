 <?php 
 class News extends CI_Controller
 {
 	function __construct()
 	{
 		parent::__construct();
 		$this->load->model('common_model');

 	}
 	public function index()
 	{
 		$data['title']='News';
 		$data['view']='news/add';
 		$data['country']=$this->common_model->getData('country');
 		$data['category']= $this->common_model->getData('category');
 		$data['city']= $this->common_model->getJoinData('city','state','country');
 		$data['news']=$this->common_model->getData('news');
 		if ($this->session->userdata('login')== 'TRUE') {
 			$this->load->view('admin/index',$data);
 		}  
 		else{
 			$url = base_url('index.php/admin');
 			header("location: ".$url." " );
 		}
 	}
 	public function addNews(){
 		
 		$this->common_model->insertData('news');

 	}
 	public function upload(){
 		if($_FILES["files"]["name"] != '')
 		{
 			$validextensions = array("jpeg", "jpg", "png");
 			$temporary = explode(".", $_FILES["files"]["name"]);
 			$file_extension = end($temporary);
  	        $sourcePath = $_FILES["files"]["name"];       // Storing source path of the file in a variable
            $targetPath = "upload/".$_FILES["files"]["name"]; // Target path where file is to be stored
            move_uploaded_file($sourcePath,$targetPath);
}
}

public function getNews(){
	$this->common_model->getWhereData('news','id');
}
public function pagination(){
	$columns = array('id','title','date','content','image','city','category');
	$totalData=$this->common_model->getData('news');

	$data=$this->common_model->GetJoinRecordNews('news','city','category','cityid','id','categoryid','id','news.id,news.title,news.date,news.content,city.name as city,category.name as category','',
		$columns[$this->input->get('order[0][column]')], 
		$this->input->get('order[0][dir]'), 
		$this->input->get('length'), 
		$this->input->get('start'));

	$result = array();
	$result['draw'] = $this->input->get('draw');
	$result['recordsTotal'] = count($totalData);
	$result['recordsFiltered'] = count($totalData);
	if($this->input->get('search[value]')!=''){

		$data=$this->common_model->GetJoinRecordNews('news','city','category','cityid','id','categoryid','id','news.id,news.title,news.date,news.content,city.name as city,category.name as category', $this->input->get('search[value]'),
			$columns[$this->input->get('order[0][column]')], 
			$this->input->get('order[0][dir]'), 
			$this->input->get('length'), 
			$this->input->get('start'));
		$result['recordsFiltered'] = count($data);
	}
	$i = 0;
	foreach ($data as $value) {
		$result['data'][$i][0] = $value['id'];
		$result['data'][$i][1] = $value['title'];
		$result['data'][$i][2] = $value['date'];
		$result['data'][$i][3] = $value['content'];
/*		$result['data'][$i][4] = $value['image'];
*/		$result['data'][$i][4] = $value['city'];
		$result['data'][$i][5] = $value['category'];
		$result['data'][$i][6] = '<button name="button" data-toggle="modal" data-target="#popupdate" class="btn btn-success update_button" value='.$value['id'].'>Update</button> 
		<button name="button" class="btn btn-danger delete_button" value='.$value['id'].'>Delete</button>';
		$i++;
	}

	echo json_encode($result);
}

}













/*
$config["upload_path"] = './upload/';
   $config["allowed_types"] = 'gif|jpg|png';
   $this->upload->initialize($config);
   for($count = 0; $count<count($_FILES["files"]["name"]); $count++)
   {
    $_FILES["file"]["name"] = $_FILES["files"]["name"][$count];
    $_FILES["file"]["type"] = $_FILES["files"]["type"][$count];
    $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$count];
    $_FILES["file"]["error"] = $_FILES["files"]["error"][$count];
    $_FILES["file"]["size"] = $_FILES["files"]["size"][$count];
    if($this->upload->do_upload('file'))
    {
     $data = $this->upload->data();
     $output = '
     <div class="col-md-3">
      <img src="'.base_url().'upload/'.$data["file_name"].'" class="img-responsive img-thumbnail" />
     </div>
     ';
     echo json_encode($output); 
    }
   }*/