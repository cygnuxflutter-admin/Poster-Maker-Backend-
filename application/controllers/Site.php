<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Db_function');
		date_default_timezone_set('Asia/Kolkata');
	}

	public function index()
	{
		if ($this->session->userdata('poster_sess_user_name')) {
			redirect('admin/showAdmin');
		} else {
			redirect('admin');
		}
	}

	/*public function showaddposterform(){
		$this->load->view('header');
		$this->load->view('sidebar');
		$result = $this->Db_function->loadData('category','nor');
		$this->load->view('addposter',array('data'=>$result));
		$this->load->view('footer');
	}*/

	public function showaddposterform()
	{
		$this->load->view('header');
		$this->load->view('sidebar');
		if(isset($_REQUEST['edit']))
		{
			$dataEdit = $this->db->where('post_id',$_REQUEST['edit'])->get('poster')->row_array();
			$data['editposter'] = $dataEdit;
		}
		else
		{
			$data['editposter'] = array();
		}
		$data['category'] = $this->db->get('category')->result_array();
		$this->load->view('addposter',$data);
		$this->load->view('footer');
	}

	public function showaddcatform(){
		$this->load->view('header');
		$this->load->view('sidebar');
		
		$this->load->view('addcategory');
		$this->load->view('footer');
	}

	public function showaddbgcatform(){
		$this->load->view('header');
		$this->load->view('sidebar');
		
		$this->load->view('bgCategory/addcategory');
		$this->load->view('footer');
	}

	public function showadduserform(){
		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('adduser');
		$this->load->view('footer');
	}
	public function poster(){
		
		$this->load->view('header');
		$this->load->view('sidebar');

         $this->load->view('poster');
		//$result = $this->Db_function->loadData('poster','nor');
		//$this->load->view('poster',array('data'=>$result));
		$this->load->view('footer');
	}

	public function fetchdata()
	{
		$requestData= $_REQUEST;

		$columns = array( 
			0 =>'post_id', 
			1 =>'post_thumb',
			2 =>'back_image',
			3=> 'ratio',
			4=> 'cat_id',
			5=> 'sticker_info',
			6=> 'text_info',
			7=> 'Action'
		);

		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$order = $columns[$this->input->post('order')[0]['column']];
		$dir   = $this->input->post('order')[0]['dir'];

		$totalData = $this->Db_function->allData_count('poster');
		$totalFiltered = $totalData;
		if(empty($this->input->post('search')['value']))
		{            
			$result = $this->Db_function->allData($limit,$start,$order,$dir,'poster');
		}
		else
		{
			$search = $this->input->post('search')['value']; 
			$result =  $this->Db_function->Data_search($limit,$start,$search,$order,$dir,'poster',array('post_id'),'cat_name','cat_id','category');
			$totalFiltered = $this->Db_function->Data_search_count($search,'poster',array('post_id'),'cat_name','cat_id','category');
		}


		$data =array();

		if(!empty($result))
		{
			foreach($result as $val)
			{
				$cat_name_str = '';
				if(!empty($val['cat_id'])) {
					$cat_ids = explode(',', $val['cat_id']);
					$cat_names = array();
					foreach($cat_ids as $c_id) {
						$cat = $this->db->select('cat_name')->where('cat_id', $c_id)->get('category')->row_array();
						if(!empty($cat) && isset($cat['cat_name'])) {
							$cat_names[] = $cat['cat_name'];
						}
					}
					$cat_name_str = implode(', ', $cat_names);
				}

				$data[] = array(
					'<a href="'.base_url().$val['post_thumb'].'" target="_blank">'.$val['post_id'].'</a>',
					'<a href="' . base_url() . $val['post_thumb'] . '" target="_blank"><img class="img img-thumbnail" src="' . base_url() . $val['post_thumb'] . '" width="110" style="height: 77.5px;" alt="'.$val['post_thumb'].'"></a>',
					'<a href="' . base_url() . $val['back_image'] . '" target="_blank"><img class="img img-thumbnail" src="' . base_url() . $val['back_image'] . '" width="110" style="height: 77.5px;" alt="'.$val['back_image'].'"></a>',
					$val['ratio'],
					$cat_name_str,
					'<button data-toggle="modal" data-target="#stickerinfoModal" id="'.$val['post_id'].'" class="sticker_info btn btn-success">Sticker</button>',
					'<button data-toggle="modal" data-target="#textinfoModal" id="'.$val['post_id'].'" class="text_info btn btn-success">Text</button>',
					'<a href="'.base_url().'site/showaddposterform?edit='.$val['post_id'].'" class="button green">
					<div class="icon"><span class="ico-pencil"></span></div></a><a onclick="delete_poster('.$val['post_id'].');" id="" class="button red delete"><div class="icon"><span class="ico-remove"></span></div></a>'
				);
			}
		}

		$output = array
		(
			"draw" => $requestData['draw'],
			"recordsTotal" => $totalData,
			"recordsFiltered" => $totalFiltered,
			"data" => $data
		);

		echo json_encode($output);
		exit();
	}

	public function fetchlogodata()
	{
		$requestData= $_REQUEST;

		$columns = array( 
			0 =>'bg_id', 
			1 =>'bg_thumb',
			2=> 'cat_id',
			3=> 'Action'
		);

		$limit = $this->input->post('length');
		$start = $this->input->post('start');

		$order = $columns[$this->input->post('order')[0]['column']];
		$dir   = $this->input->post('order')[0]['dir'];

		$totalData = $this->Db_function->allData_count('poster_BG');
		$totalFiltered = $totalData;

		if(empty($this->input->post('search')['value']))
		{            
			$result = $this->Db_function->allData($limit,$start,$order,$dir,'poster_BG');
		}
		else
		{
			$search = $this->input->post('search')['value']; 
			$result =  $this->Db_function->Data_search($limit,$start,$search,$order,$dir,'poster_BG',array('bg_id'),'name','bg_cat_id','background_category');
			$totalFiltered = $this->Db_function->Data_search_count($search,'poster_BG',array('bg_id'),'name','bg_cat_id','background_category');
		}


		$data =array();

		if(!empty($result))
		{
			foreach($result as $val)
			{

				$data[] = array(
					$val['bg_id'],
					"<img src='".base_url().$val['bg_thumb']."' width=110 style='height: 77.5px;'>",
					$this->db->select('name')->where('bg_cat_id',$val['cat_id'])->get('background_category')->row()->name,
					'<a href="'.base_url().'site/showaddposterform?edit='.$val['bg_id'].'" class="button green">
					<div class="icon"><span class="ico-pencil"></span></div></a><a onclick="delete_poster('.$val['bg_id'].');" id="" class="button red delete"><div class="icon"><span class="ico-remove"></span></div></a>'
				);
			}
		}

		$output = array
		(
			"draw" => $requestData['draw'],
			"recordsTotal" => $totalData,
			"recordsFiltered" => $totalFiltered,
			"data" => $data
		);

		echo json_encode($output);
		exit();
	}





	public function loadRecord($rowno=0)
	{
		$this->load->library("pagination");
		
		$rowperpage = 5;

		if($rowno != 0){
			$rowno = ($rowno-1) * $rowperpage;
		}
		
		$allcount = $this->db->select('post_id')->get('poster')->num_rows();
		$users_record = $this->db->select('post_id,cat_id,post_thumb,ratio')->limit($rowperpage,$rowno)->get('poster')->result_array();

		$config['base_url'] = base_url().'site/loadRecord';
		$config['full_tag_open'] = "<ul class='pagination'>";
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';



		$config['prev_link'] = '<i class="fa fa-long-arrow-left"></i>Previous Page';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';


		$config['next_link'] = 'Next Page<i class="fa fa-long-arrow-right"></i>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['use_page_numbers'] = TRUE;
		$config['total_rows'] = $allcount;
		$config['per_page'] = $rowperpage;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['result'] = $users_record;
		$data['row'] = $rowno;


		echo json_encode($data);
	}

	public function loadCat(){
		$rec = $this->db->where('cat_id',$_POST['cat_id'])->select('cat_name')->get('category')->row()->cat_name;
		echo json_encode($rec);
	}

	public function posterbackground()
	{

		$this->load->view('header');
		$this->load->view('sidebar');
		$result = $this->Db_function->loadData('poster_BG','nor');
		$this->load->view('posterBG/posterbackground',array('data'=>$result));
		$this->load->view('footer');
	}

	public function category(){

		$this->load->view('header');
		$this->load->view('sidebar');
		$result = $this->Db_function->loadData('category','nor');
		$this->load->view('cattable',array('data'=>$result));
		$this->load->view('footer');
	}

	public function bgCategory(){

		$this->load->view('header');
		$this->load->view('sidebar');
		$result = $this->Db_function->loadData('background_category','nor');
		$this->load->view('bgCategory/cattable',array('data'=>$result));
		$this->load->view('footer');
	}

	public function showeditform(){
		$post_id = $this->uri->segment(3);
		$this->load->view('header');
		$this->load->view('sidebar');
		$result = $this->Db_function->loadDataForUpdate('poster','post_id',$post_id,'join');
		$this->load->view('postereditform',array('data'=>$result));	
		$this->load->view('footer');
	}

	public function showcateditform(){
		$cat_id = $this->uri->segment(3);
		$this->load->view('header');
		$this->load->view('sidebar');
		$result = $this->Db_function->loadDataForUpdate('category','cat_id',$cat_id,'nor');
		$this->load->view('cateditform',array('data'=>$result));	
		$this->load->view('footer');
	}

	public function user(){

		$this->load->view('header');
		$this->load->view('sidebar');
		$result = $this->Db_function->loadData('login_user','nor');
		$this->load->view('users',array('data'=>$result));
		$this->load->view('footer');
	}

	public function adduser()
	{	$menu = implode(",", $_POST['menu_id']);
	$data = array(
		'username' => $_POST['username'],
		'email'=> $_POST['email'],
		'password'=> $_POST['password'],
		'menu_id'=>$menu,
	);

	$answer = $this->Db_function->insertdata('login_user','username',$data);

	redirect('site/user');

}

public function deleteuser(){
	$this->Db_function->delete('login_user',$_POST['user_id'],'user_id');
	echo json_encode("User is Successfully Deleted from Table !");
}

public function insertposter()
{	
	$post_thumb = 2;
	$poster = 2;
	if(isset($_FILES['post_thumb']['name']) && !empty($_FILES['post_thumb']['name']))
	{
		$thumb_image = $_FILES['post_thumb']['name'];
		$ext = pathinfo($thumb_image, PATHINFO_EXTENSION);
		if(strtolower($ext) == 'jpg' || strtolower($ext) =='jpeg' || strtolower($ext) =='png')
		{	
			$imagename = uniqid() . "_" .date("d-m-Y") . "__" .time() . "." . $ext;
			$targetpath = 'upload/thumb_image/' . $imagename;
			if(move_uploaded_file($_FILES['post_thumb']['tmp_name'],$targetpath)){
				$post_thumb = 1;
			}else{
				$post_thumb = 0;
			}
		}
	}
	if(isset($_FILES['back_image']['name']) && !empty($_FILES['back_image']['name']))
	{
		$back_image = $_FILES['back_image']['name'];
		$ext = pathinfo($back_image, PATHINFO_EXTENSION);
		if(strtolower($ext) == 'jpg' || strtolower($ext) =='jpeg' || strtolower($ext) =='png')
		{	
			$background = uniqid() . "_" .date("d-m-Y") . "__" .time() . "." . $ext;
			$bg_target_path = 'upload/background/' . $background;
			if(move_uploaded_file($_FILES['back_image']['tmp_name'],$bg_target_path)){
				$poster = 1;
			}else{
				$poster = 0;
			}
		}
	}

	if($poster ==1 && $post_thumb==1)
	{	
		$tmp_name = $type = array();
		foreach ($_FILES as $fileVal)
		{
			$tmp_name = $fileVal['tmp_name'];
			$type = $fileVal['type'];
		}

		$i = 0;
		$sticker_data = array();
		foreach($type as $stid)
		{

			if(strtolower($stid) == "image/png" || strtolower($stid)== "image/jpg" || strtolower($stid)== "image/jpeg")
			{

				$imgtype = explode("/", $stid);
				$imgname= rand(1000, 9999) . "_" .date("d-m-Y") . "__" .time() . "." .$imgtype[1];
				$st_target = "upload/sticker/".$imgname; 

				if(move_uploaded_file($tmp_name[$i],$st_target))
				{
					$stdata = array();
					$stdata['st_image'] 	= $st_target;
					$stdata['sticker_id'] 	= $_POST['sticker_id'][$i];
					$stdata['st_x_pos'] 	= $_POST['st_x_pos'][$i];
					$stdata['st_y_pos'] 	= $_POST['st_y_pos'][$i];
					$stdata['st_width'] 	= $_POST['st_width'][$i];
					$stdata['st_height'] 	= $_POST['st_height'][$i];
					$stdata['st_rotation'] 	= $_POST['st_rotation'][$i];
					$stdata['st_res_id'] 	= $_POST['st_res_id'][$i];
					$stdata['st_res_uri'] 	= $_POST['st_res_uri'][$i];
					$stdata['st_order'] 	= $_POST['st_order'][$i];

					$sticker_data[] = $stdata;
				}
				$i++;
			}
		}

		$j=0;
		$text_data =array();
		foreach($_POST['text_id'] as $v){
			$textdata = array();
			$txtdata['text_id'] = $_POST['text_id'][$j];
			$txtdata['text'] 	= $_POST['text'][$j];
			$txtdata['font_family'] 	= $_POST['font_family'][$j];
			$txtdata['font_familyiOS'] 	= $_POST['font_family'][$j];
			$txtdata['txt_color'] 	= $_POST['txt_color'][$j];
			$txtdata['txt_x_pos'] 	= $_POST['txt_x_pos'][$j];
			$txtdata['txt_y_pos'] = $_POST['txt_y_pos'][$j];
			$txtdata['txt_width'] = $_POST['txt_width'][$j];
			$txtdata['txt_height'] = $_POST['txt_height'][$j];
			$txtdata['txt_rotation'] = $_POST['txt_rotation'][$j];
			$txtdata['txt_order'] = $_POST['txt_order'][$j];
			$text_data[]= $txtdata;
			$j++;
		}
		$alldata = array(
			'post_thumb'=>$targetpath,
			'back_image'=>$bg_target_path,
			'ratio' =>$_POST['ratio'],
			'cat_id'=>$_POST['cat_id'],
			'sticker_info'=>json_encode($sticker_data),
			'text_info' =>json_encode($text_data)
		);
	}


	$this->Db_function->insertdata('poster','post_thumb',$alldata);
	header("Location:".base_url()."site/poster");
}

/*public function insertposter()
{

	$sticker_id = explode(",",$_POST['sticker_id']);
	$st_x_pos = explode(",",$_POST['st_x_pos']);
	$st_y_pos = explode(",",$_POST['st_y_pos']);
	$st_width = explode(",",$_POST['st_width']);
	$st_height = explode(",",$_POST['st_height']);
	$st_rotation = explode(",",$_POST['st_rotation']);
	$st_y_rotation = explode(",",$_POST['st_y_rotation']);
	$st_res_id = explode(",",$_POST['st_res_id']);
	$st_res_uri = explode(",",$_POST['st_res_uri']);
	$st_bitmap_url = explode(",",$_POST['st_bitmap_url']);
	$st_type = explode(",",$_POST['st_type']);
	$st_order = explode(",",$_POST['st_order']);

	$text_id= explode(",",$_POST['text_id']);
	$text= explode(",",$_POST['text']);
	$font_family= explode(",",$_POST['font_family']);
	$txt_color= explode(",",$_POST['txt_color']);
	$txt_x_pos= explode(",",$_POST['txt_x_pos']);
	$txt_y_pos= explode(",",$_POST['txt_y_pos']);
	$txt_width= explode(",",$_POST['txt_width']);
	$txt_height= explode(",",$_POST['txt_height']);
	$txt_rotation= explode(",",$_POST['txt_rotation']);
	$txt_type= explode(",",$_POST['txt_type']);
	$txt_order= explode(",",$_POST['txt_order']);

	$post_thumb = 2;
	$poster = 2;

	if(isset($_FILES['post_thumb']['name']) && !empty($_FILES['post_thumb']['name']))
	{
		$thumb_image = $_FILES['post_thumb']['name'];
		$ext = pathinfo($thumb_image, PATHINFO_EXTENSION);
		if(strtolower($ext) == 'jpg' || strtolower($ext) =='jpeg' || strtolower($ext) =='png')
		{	
			$imagename = uniqid() . "_" .date("d-m-Y") . "__" .time() . "." . $ext;
			$targetpath = 'upload/thumb_image/' . $imagename;
			if(move_uploaded_file($_FILES['post_thumb']['tmp_name'],$targetpath)){
				$post_thumb = 1;
			}else{
				$post_thumb = 0;
			}
		}
	}
	if(isset($_FILES['back_image']['name']) && !empty($_FILES['back_image']['name']))
	{
		$back_image = $_FILES['back_image']['name'];
		$ext = pathinfo($back_image, PATHINFO_EXTENSION);
		if(strtolower($ext) == 'jpg' || strtolower($ext) =='jpeg' || strtolower($ext) =='png')
		{	
			$background = uniqid() . "_" .date("d-m-Y") . "__" .time() . "." . $ext;
			$bg_target_path = 'upload/background/' . $background;
			if(move_uploaded_file($_FILES['back_image']['tmp_name'],$bg_target_path)){
				$poster = 1;
			}else{
				$poster = 0;
			}
		}
	}

	if($poster ==1 && $post_thumb==1)
	{	


		$i = 0;
		$sticker_data = array();
		$text_data =array();
		foreach($sticker_id as $stid){
			$stdata = array();
			$stdata['sticker_id'] 	= $sticker_id[$i];
			$stdata['st_x_pos'] 	= $st_x_pos[$i];
			$stdata['st_y_pos'] 	= $st_y_pos[$i];
			$stdata['st_width'] 	= $st_width[$i];
			$stdata['st_height'] 	= $st_height[$i];
			$stdata['st_rotation'] 	= $st_rotation[$i];
			$stdata['st_image'] = $st_image[$i];
			$stdata['st_res_id'] 	= $st_res_id[$i];
			$stdata['st_res_uri'] 	= $st_res_uri[$i];
			$stdata['st_bitmap_url'] = $st_bitmap_url[$i];
			$stdata['st_type'] 		= $st_type[$i];
			$stdata['st_order'] 	= $st_order[$i];
			$sticker_data[] = $stdata;
			$i++;
		}
		$j=0;
		foreach($text_id as $v){
			$textdata = array();
			$txtdata['text_id'] = $text_id[$j];
			$txtdata['text'] 	= $text[$j];
			$txtdata['font_family'] 	= $font_family[$j];
			$txtdata['txt_color'] 	= $txt_color[$j];
			$txtdata['txt_x_pos'] 	= $txt_x_pos[$j];
			$txtdata['txt_y_pos'] = $txt_y_pos[$j];
			$txtdata['txt_width'] = $txt_width[$j];
			$txtdata['txt_height'] = $txt_height[$j];
			$txtdata['txt_rotation'] = $txt_rotation[$j];
			$txtdata['txt_order'] = $txt_type[$j];
			$txtdata['txt_type'] = $txt_order[$j];
			$text_data[]= $txtdata;
			$j++;
		}

		$alldata = array(
			'post_thumb'=>$targetpath,
			'back_image'=>$bg_target_path,
			'ratio' =>$_POST['ratio'],
			'cat_id'=>$_POST['cat_id'],
			'sticker_info'=>json_encode($sticker_data),
			'text_info' =>json_encode($text_data)
		);

		$this->Db_function->insertdata('poster','post_thumb',$alldata);

		echo json_encode("true");
	}
}*/

public function getsticker_info(){

	$result= $this->Db_function->loadDataForUpdate('poster','post_id',$_POST['sticker_id'],'nor');

	echo json_encode($result);

}

public function insertcat()
{
	if(isset($_FILES['cat_thumb']['name']) && !empty($_FILES['cat_thumb']['name']))
	{


		$filename = $_FILES['cat_thumb']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if(strtolower($ext) == 'jpg' || strtolower($ext) =='jpeg' || strtolower($ext) =='png')
		{	

			$imagename = uniqid() . "_" .date("d-m-Y") . "__" .time() . "." . $ext;
			$targetpath = 'upload/thumb_image/' . $imagename;
			$data = array(

				'cat_name' => $_POST['cat_name'],
				'sequence_number' => $_POST['sequence_number'],
				'cat_thumb' => $targetpath,
			);

			if(move_uploaded_file($_FILES['cat_thumb']['tmp_name'],$targetpath)){
				$this->Db_function->insertdata('category','cat_name',$data);
			}
			redirect('site/category');

		}
	}	
	$data = array('cat_name' => $_POST['cat_name'],'sequence_number' => $_POST['sequence_number']);

	$answer = $this->Db_function->insertdata('category','cat_name','sequence_number',$data);
	redirect('site/category');
}
public function category_sorting()
    {  
       $this->Db_function->sortUpdate();
    }

public function save_category_sequences()
{
    header('Content-Type: application/json');
    $sequences = $this->input->post('sequences');
    
    if(!empty($sequences) && is_array($sequences))
    {
        $updated = 0;
        foreach($sequences as $item) {
            $cat_id = isset($item['cat_id']) ? intval($item['cat_id']) : 0;
            $seq_num = isset($item['sequence_number']) ? intval($item['sequence_number']) : 0;
            if($cat_id > 0) {
                $this->db->where('cat_id', $cat_id);
                $this->db->update('category', array('sequence_number' => $seq_num));
                $updated++;
            }
        }
        echo json_encode(array(
            'status' => 'success', 
            'message' => "Successfully updated sequence for {$updated} categories!"
        ));
        return;
    }

    $single_id = $this->input->post('cat_id');
    $single_seq = $this->input->post('sequence_number');
    if($single_id !== null && $single_seq !== null) {
        $this->db->where('cat_id', intval($single_id));
        $this->db->update('category', array('sequence_number' => intval($single_seq)));
        echo json_encode(array(
            'status' => 'success', 
            'message' => 'Category sequence updated successfully!'
        ));
        return;
    }

    echo json_encode(array('status' => 'error', 'message' => 'No sequence data provided'));
}

public function auto_rotate_categories()
{
    header('Content-Type: application/json');
    $updatedCount = $this->execute_auto_category_rotation();
    $bgCount = $this->execute_auto_bg_category_rotation();
    echo json_encode(array(
        'status' => 'success',
        'message' => "Successfully auto-rotated {$updatedCount} poster categories & {$bgCount} background categories based on today's date (" . date('d M Y') . ")!"
    ));
}

public function auto_rotate_bg_categories()
{
    header('Content-Type: application/json');
    $bgCount = $this->execute_auto_bg_category_rotation();
    echo json_encode(array(
        'status' => 'success',
        'message' => "Successfully auto-rotated {$bgCount} background categories based on today's date (" . date('d M Y') . ")!"
    ));
}

public function cron_auto_rotate()
{
    $token = $this->input->get('token');
    if (!is_cli() && $token !== 'poster_cron_2026' && !$this->session->userdata('poster_sess_user_name')) {
        show_error('Access Denied. Invalid or missing token.', 403);
        return;
    }

    $updatedCount = $this->execute_auto_category_rotation();
    $bgCount = $this->execute_auto_bg_category_rotation();
    if (!is_cli()) {
        header('Content-Type: text/plain; charset=UTF-8');
    }
    echo "[OK] Auto-rotated {$updatedCount} poster categories and {$bgCount} background categories based on date " . date('Y-m-d H:i:s') . "\n";
}

public function save_bg_category_sequences()
{
    header('Content-Type: application/json');
    $sequences = $this->input->post('sequences');
    
    if(!empty($sequences) && is_array($sequences))
    {
        $updated = 0;
        foreach($sequences as $item) {
            $cat_id = isset($item['cat_id']) ? intval($item['cat_id']) : 0;
            $seq_num = isset($item['sequence_number']) ? intval($item['sequence_number']) : 0;
            if($cat_id > 0) {
                $this->db->where('bg_cat_id', $cat_id);
                $this->db->update('background_category', array('sequence_number' => $seq_num));
                $updated++;
            }
        }
        echo json_encode(array(
            'status' => 'success', 
            'message' => "Successfully updated sequence for {$updated} background categories!"
        ));
        return;
    }

    $single_id = $this->input->post('cat_id');
    $single_seq = $this->input->post('sequence_number');
    if($single_id !== null && $single_seq !== null) {
        $this->db->where('bg_cat_id', intval($single_id));
        $this->db->update('background_category', array('sequence_number' => intval($single_seq)));
        echo json_encode(array(
            'status' => 'success', 
            'message' => 'Background category sequence updated successfully!'
        ));
        return;
    }

    echo json_encode(array('status' => 'error', 'message' => 'No sequence data provided'));
}

public function bgdeletecat()
{
    $cat_id = $this->input->post('cat_id');
    if($cat_id) {
        $this->db->where('bg_cat_id', intval($cat_id))->delete('background_category');
        echo json_encode("Category deleted successfully");
    } else {
        echo json_encode("Error deleting category");
    }
}

public function showbgcateditform($id = null)
{
    if(!$id) $id = $this->uri->segment(3);
    $this->load->view('header');
    $this->load->view('sidebar');
    $data = $this->db->where('bg_cat_id', intval($id))->get('background_category')->result_array();
    $this->load->view('bgCategory/cateditform', array('data' => $data));
    $this->load->view('footer');
}

public function updatebgcat()
{
    $cat_id = $this->input->post('cat_id');
    $name = $this->input->post('cat_name');
    $sequence = $this->input->post('sequence_number');
    if($cat_id) {
        $data = array('name' => $name);
        if($sequence !== null) $data['sequence_number'] = intval($sequence);
        $this->db->where('bg_cat_id', intval($cat_id))->update('background_category', $data);
    }
    redirect('site/bgCategory');
}

private function execute_auto_bg_category_rotation($targetDate = null)
{
    if (!$targetDate) $targetDate = date('Y-m-d');
    $todayTs = strtotime($targetDate);
    $currentYear = (int)date('Y', $todayTs);

    $seasonal = array(
        4  => array('name' => 'Christmas', 'm' => 12, 'd' => 25, 'lead' => 40, 'after' => 2),
        55 => array('name' => 'Valentine Day', 'm' => 2, 'd' => 14, 'lead' => 20, 'after' => 1),
        9  => array('name' => 'Love', 'm' => 2, 'd' => 14, 'lead' => 20, 'after' => 1),
        56 => array('name' => 'Women\'s Day', 'm' => 3, 'd' => 8, 'lead' => 15, 'after' => 1),
        60 => array('name' => 'Eid Mubarak', 'm' => 4, 'd' => 15, 'lead' => 20, 'after' => 1),
    );

    $activeBg = array();
    $upcomingBg = array();
    $pastBg = array();

    foreach ($seasonal as $bgId => $s) {
        $eventTsThisYear = strtotime(sprintf("%04d-%02d-%02d", $currentYear, $s['m'], $s['d']));
        $eventTsNextYear = strtotime(sprintf("%04d-%02d-%02d", $currentYear + 1, $s['m'], $s['d']));
        $start = $eventTsThisYear - ($s['lead'] * 86400);
        $end = $eventTsThisYear + ($s['after'] * 86400);

        if ($todayTs >= $start && $todayTs <= $end) {
            $diff = ($eventTsThisYear - $todayTs) / 86400;
            $activeBg[$bgId] = $diff;
        } elseif ($todayTs < $start) {
            $diff = ($eventTsThisYear - $todayTs) / 86400;
            $upcomingBg[$bgId] = $diff;
        } else {
            $diff = ($eventTsNextYear - $todayTs) / 86400;
            $pastBg[$bgId] = $diff;
        }
    }

    asort($activeBg);
    asort($upcomingBg);
    asort($pastBg);

    $evergreenBg = array(8, 12, 1, 2, 11, 14, 7, 15, 5, 6, 13, 10, 3);

    $ordered = array();
    foreach(array_keys($activeBg) as $id) $ordered[] = $id;
    foreach($evergreenBg as $id) if(!in_array($id, $ordered)) $ordered[] = $id;
    foreach(array_keys($upcomingBg) as $id) if(!in_array($id, $ordered)) $ordered[] = $id;
    foreach(array_keys($pastBg) as $id) if(!in_array($id, $ordered)) $ordered[] = $id;

    $res = $this->db->select('bg_cat_id')->get('background_category');
    if ($res) {
        foreach($res->result_array() as $r) {
            $id = (int)$r['bg_cat_id'];
            if (!in_array($id, $ordered)) $ordered[] = $id;
        }
    }

    $seq = 0;
    foreach ($ordered as $bgCatId) {
        $this->db->where('bg_cat_id', $bgCatId);
        $this->db->update('background_category', array('sequence_number' => $seq));
        $seq++;
    }

    return count($ordered);
}

private function execute_auto_category_rotation($targetDate = null)
{
    if (!$targetDate) $targetDate = date('Y-m-d');
    $todayTs = strtotime($targetDate);
    $currentYear = (int)date('Y', $todayTs);

    // 1. Fetch active festivals from calendar
    $festivals = array();
    $festQuery = $this->db->where('is_active', 1)->get('festival_calendar');
    if ($festQuery && $festQuery->num_rows() > 0) {
        foreach ($festQuery->result_array() as $r) {
            $m = (int)$r['event_month'];
            $d = (int)$r['event_day'];
            $lead = (int)$r['lead_days'];
            $after = (int)$r['active_days_after'];

            $eventTsThisYear = strtotime(sprintf("%04d-%02d-%02d", $currentYear, $m, $d));
            $eventTsNextYear = strtotime(sprintf("%04d-%02d-%02d", $currentYear + 1, $m, $d));

            $activeStart = $eventTsThisYear - ($lead * 86400);
            $activeEnd = $eventTsThisYear + ($after * 86400);

            if ($todayTs >= $activeStart && $todayTs <= $activeEnd) {
                $diffDays = ($eventTsThisYear - $todayTs) / 86400;
                $r['group'] = 'ACTIVE';
                $r['score'] = $diffDays;
            } elseif ($todayTs < $activeStart) {
                $diffDays = ($eventTsThisYear - $todayTs) / 86400;
                $r['group'] = 'UPCOMING';
                $r['score'] = 1000 + $diffDays;
            } else {
                $diffDays = ($eventTsNextYear - $todayTs) / 86400;
                $r['group'] = 'PAST';
                $r['score'] = 5000 + $diffDays;
            }
            $festivals[] = $r;
        }
    }

    usort($festivals, function($a, $b) {
        return $a['score'] <=> $b['score'];
    });

    $allCats = array();
    $catQuery = $this->db->select('cat_id, cat_name')->get('category');
    if ($catQuery) {
        foreach ($catQuery->result_array() as $c) {
            $allCats[(int)$c['cat_id']] = $c['cat_name'];
        }
    }

    $evergreenIds = array(47, 46, 18, 44, 10, 11, 48, 19, 42, 79, 21);

    $finalOrderedIds = array();
    $activeFestIds = array();
    $upcomingFestIds = array();
    $pastFestIds = array();

    foreach ($festivals as $f) {
        $cid = (int)$f['cat_id'];
        if ($f['group'] === 'ACTIVE') {
            $activeFestIds[] = $cid;
        } elseif ($f['group'] === 'UPCOMING') {
            $upcomingFestIds[] = $cid;
        } else {
            $pastFestIds[] = $cid;
        }
    }

    foreach ($activeFestIds as $cid) {
        if (!in_array($cid, $finalOrderedIds)) $finalOrderedIds[] = $cid;
    }

    $immediateUpcoming = array_splice($upcomingFestIds, 0, 5);
    foreach ($immediateUpcoming as $cid) {
        if (!in_array($cid, $finalOrderedIds)) $finalOrderedIds[] = $cid;
    }

    foreach ($evergreenIds as $cid) {
        if (!in_array($cid, $finalOrderedIds)) $finalOrderedIds[] = $cid;
    }

    foreach ($upcomingFestIds as $cid) {
        if (!in_array($cid, $finalOrderedIds)) $finalOrderedIds[] = $cid;
    }

    foreach ($pastFestIds as $cid) {
        if (!in_array($cid, $finalOrderedIds)) $finalOrderedIds[] = $cid;
    }

    foreach ($allCats as $cid => $name) {
        if (!in_array($cid, $finalOrderedIds)) {
            $finalOrderedIds[] = $cid;
        }
    }

    $seq = 0;
    foreach ($finalOrderedIds as $catId) {
        $this->db->where('cat_id', $catId);
        $this->db->update('category', array('sequence_number' => $seq));
        $seq++;
    }

    return count($finalOrderedIds);
}

public function insertbgcat()
{
	$data = array(
		'name' => $_POST['cat_name'],
		'sequence_number' => isset($_POST['sequence_number']) ? intval($_POST['sequence_number']) : 0,
	);

	$this->Db_function->insertdata('background_category','name',$data);
	redirect('site/bgCategory');
}

public function updateposter()
{
	$post_thumb = 2;
	$bg_image = 2;

	if(isset($_FILES['post_thumb']['name']) && !empty($_FILES['post_thumb']['name']))
	{
		$thumb_image = $_FILES['post_thumb']['name'];
		$ext = pathinfo($thumb_image, PATHINFO_EXTENSION);
		if(strtolower($ext) == 'jpg' || strtolower($ext) =='jpeg' || strtolower($ext) =='png')
		{	
			$imagename = uniqid() . "_" .date("d-m-Y") . "__" .time() . "." . $ext;
			$targetpath = 'upload/thumb_image/' . $imagename;
			if(move_uploaded_file($_FILES['post_thumb']['tmp_name'],$targetpath)){
				$post_thumb = 1;
			}else{
				$post_thumb = 0;
			}
		}
	}
	if(isset($_FILES['back_image']['name']) && !empty($_FILES['back_image']['name']))
	{
		$back_image = $_FILES['back_image']['name'];
		$ext = pathinfo($back_image, PATHINFO_EXTENSION);
		if(strtolower($ext) == 'jpg' || strtolower($ext) =='jpeg' || strtolower($ext) =='png')
		{	
			$background = uniqid() . "_" .date("d-m-Y") . "__" .time() . "." . $ext;
			$bg_target_path = 'upload/background/' . $background;
			if(move_uploaded_file($_FILES['back_image']['tmp_name'],$bg_target_path)){
				$bg_image = 1;
			}else{
				$bg_image = 0;
			}
		}
	}

	if($bg_image ==1 && $post_thumb==1)
	{	
		foreach ($_FILES as $fileVal)
		{
			$tmp_name = $fileVal['tmp_name'];
			$type = $fileVal['type'];
		}

		$i = 0;
		$sticker_data = array();
		foreach($type as $stid)
		{

			if(strtolower($stid) == "image/png" || strtolower($stid)== "image/jpg" || strtolower($stid)== "image/jpeg")
			{

				$imgtype = explode("/", $stid);
				$imgname= rand(1000, 9999) . "_" .date("d-m-Y") . "__" .time() . "." .$imgtype[1];
				$st_target = "upload/sticker/".$imgname; 

				if(move_uploaded_file($tmp_name[$i],$st_target))
				{
					$stdata = array();
					$stdata['st_image'] 	= $st_target;
					$stdata['sticker_id'] 	= $_POST['sticker_id'][$i];
					$stdata['st_x_pos'] 	= $_POST['st_x_pos'][$i];
					$stdata['st_y_pos'] 	= $_POST['st_y_pos'][$i];
					$stdata['st_width'] 	= $_POST['st_width'][$i];
					$stdata['st_height'] 	= $_POST['st_height'][$i];
					$stdata['st_rotation'] 	= $_POST['st_rotation'][$i];
					$stdata['st_res_id'] 	= $_POST['st_res_id'][$i];
					$stdata['st_res_uri'] 	= $_POST['st_res_uri'][$i];
					$stdata['st_order'] 	= $_POST['st_order'][$i];
					$sticker_data[] = $stdata;
				}

			}
			else
			{
				$stdata = array();
				$stdata['st_image'] 	= $_POST['st_imageOLD'][$i];
				$stdata['sticker_id'] 	= $_POST['sticker_id'][$i];
				$stdata['st_x_pos'] 	= $_POST['st_x_pos'][$i];
				$stdata['st_y_pos'] 	= $_POST['st_y_pos'][$i];
				$stdata['st_width'] 	= $_POST['st_width'][$i];
				$stdata['st_height'] 	= $_POST['st_height'][$i];
				$stdata['st_rotation'] 	= $_POST['st_rotation'][$i];
				$stdata['st_res_id'] 	= $_POST['st_res_id'][$i];
				$stdata['st_res_uri'] 	= $_POST['st_res_uri'][$i];
				$stdata['st_order'] 	= $_POST['st_order'][$i];

				$sticker_data[] = $stdata;
			}
			$i++;
		}

		$j=0;
		$text_data =array();
		foreach($_POST['text_id'] as $v)
		{

			// if(isset($_POST['font_family'][$j]) && $_POST['font_family'][$j])
			// {
			// 	//echo $j." -> ".$_POST['font_family'][$j]."<br>";
				
			// 	$data = file_get_contents('http://<varnifonts>/api/v1/fonts/syncFont/?font_id='.$_POST['font_family'][$j]);
			// 	$Data = json_decode($data);
			// 	$txtdata['font_family'] 	=$Data->results->android_font;
			// 	$txtdata['font_familyiOS'] =$Data->results->ios_font;

			// }
			// else
			// {
				//echo $j." -> ".$_POST['font_family_old'][$j]."<br>";
				
				$txtdata['font_family']  	=$_POST['font_family'][$j];
				$txtdata['font_familyiOS']	=$_POST['font_family'][$j];
			// }
			//$data = file_get_contents('http://<varnifonts>/api/v1/fonts/syncFont/?font_id='.$_POST['font_family'][$j]);
			//$Data = json_decode($data);

			$textdata = array();
			$txtdata['text_id'] = $_POST['text_id'][$j];
			$txtdata['text'] 	= $_POST['text'][$j];
			//$txtdata['font_family'] 	= ()? $Data->results->android_font:$_POST['font_family_old'][$j];
			//$txtdata['font_familyiOS'] 	= ($Data->results->ios_font)? $Data->results->ios_font:$_POST['font_familyios_old'][$j] ;
			$txtdata['txt_color'] 	= $_POST['txt_color'][$j];
			$txtdata['txt_x_pos'] 	= $_POST['txt_x_pos'][$j];
			$txtdata['txt_y_pos'] = $_POST['txt_y_pos'][$j];
			$txtdata['txt_width'] = $_POST['txt_width'][$j];
			$txtdata['txt_height'] = $_POST['txt_height'][$j];
			$txtdata['txt_rotation'] = $_POST['txt_rotation'][$j];
			$txtdata['txt_order'] = $_POST['txt_order'][$j];
			$text_data[]= $txtdata;
			$j++;
		}

		
		$alldata = array(
			'post_thumb'=>$targetpath,
			'back_image'=>$bg_target_path,
			'ratio' =>$_POST['ratio'],
			'cat_id'=>$_POST['cat_id'],
			'sticker_info'=>json_encode($sticker_data),
			'text_info' =>json_encode($text_data)
		);

		$this->db->set($alldata)->where('post_id',$_POST['post_id'])->update('poster');

	}
	else if($bg_image == 1)
	{
		$tmp_name = $type = array();
		foreach ($_FILES as $fileVal)
		{
			$tmp_name = $fileVal['tmp_name'];
			$type = $fileVal['type'];
		}

		$i = 0;
		$sticker_data = array();
		foreach($type as $stid)
		{

			if(strtolower($stid) == "image/png" || strtolower($stid)== "image/jpg" || strtolower($stid)== "image/jpeg")
			{

				$imgtype = explode("/", $stid);
				$imgname= rand(1000, 9999) . "_" .date("d-m-Y") . "__" .time() . "." .$imgtype[1];
				$st_target = "upload/sticker/".$imgname; 

				if(move_uploaded_file($tmp_name[$i],$st_target))
				{
					$stdata = array();
					$stdata['st_image'] 	= $st_target;
					$stdata['sticker_id'] 	= $_POST['sticker_id'][$i];
					$stdata['st_x_pos'] 	= $_POST['st_x_pos'][$i];
					$stdata['st_y_pos'] 	= $_POST['st_y_pos'][$i];
					$stdata['st_width'] 	= $_POST['st_width'][$i];
					$stdata['st_height'] 	= $_POST['st_height'][$i];
					$stdata['st_rotation'] 	= $_POST['st_rotation'][$i];
					$stdata['st_res_id'] 	= $_POST['st_res_id'][$i];
					$stdata['st_res_uri'] 	= $_POST['st_res_uri'][$i];
					$stdata['st_order'] 	= $_POST['st_order'][$i];

					$sticker_data[] = $stdata;
				}

			}
			else
			{
				$stdata = array();
				$stdata['st_image'] 	= $_POST['st_imageOLD'][$i];
				$stdata['sticker_id'] 	= $_POST['sticker_id'][$i];
				$stdata['st_x_pos'] 	= $_POST['st_x_pos'][$i];
				$stdata['st_y_pos'] 	= $_POST['st_y_pos'][$i];
				$stdata['st_width'] 	= $_POST['st_width'][$i];
				$stdata['st_height'] 	= $_POST['st_height'][$i];
				$stdata['st_rotation'] 	= $_POST['st_rotation'][$i];
				$stdata['st_res_id'] 	= $_POST['st_res_id'][$i];
				$stdata['st_res_uri'] 	= $_POST['st_res_uri'][$i];
				$stdata['st_order'] 	= $_POST['st_order'][$i];

				$sticker_data[] = $stdata;
			}
			$i++;
		}

		$j=0;
		$text_data =array();
		foreach($_POST['text_id'] as $v){
			// if(isset($_POST['font_family'][$j]) && $_POST['font_family'][$j])
			// {
			// 	//echo $j." -> ".$_POST['font_family'][$j]."<br>";
				
			// 	$data = file_get_contents('http://<varnifonts>/api/v1/fonts/syncFont/?font_id='.$_POST['font_family'][$j]);
			// 	$Data = json_decode($data);
			// 	$txtdata['font_family'] 	=$Data->results->android_font;
			// 	$txtdata['font_familyiOS'] =$Data->results->ios_font;

			// }
			// else
			// {
				//echo $j." -> ".$_POST['font_family_old'][$j]."<br>";
				
				$txtdata['font_family']  	=$_POST['font_family'][$j];
				$txtdata['font_familyiOS']	=$_POST['font_family'][$j];
			// }

			$textdata = array();
			$txtdata['text_id'] = $_POST['text_id'][$j];
			$txtdata['text'] 	= $_POST['text'][$j];
			// $txtdata['font_family'] 	= $_POST['font_family'][$j];
			// $txtdata['font_familyiOS'] 	= $_POST['font_familyios'][$j];
			$txtdata['txt_color'] 	= $_POST['txt_color'][$j];
			$txtdata['txt_x_pos'] 	= $_POST['txt_x_pos'][$j];
			$txtdata['txt_y_pos'] = $_POST['txt_y_pos'][$j];
			$txtdata['txt_width'] = $_POST['txt_width'][$j];
			$txtdata['txt_height'] = $_POST['txt_height'][$j];
			$txtdata['txt_rotation'] = $_POST['txt_rotation'][$j];
			$txtdata['txt_order'] = $_POST['txt_order'][$j];
			$text_data[]= $txtdata;
			$j++;
		}

		$alldata = array(
			'back_image'=>$bg_target_path,
			'ratio' =>$_POST['ratio'],
			'cat_id'=>$_POST['cat_id'],
			'sticker_info'=>json_encode($sticker_data),
			'text_info' =>json_encode($text_data)
		);

		$this->db->set($alldata)->where('post_id',$_POST['post_id'])->update('poster');
	}
	else if($post_thumb ==1){

		$tmp_name = $type = array();
		foreach ($_FILES as $fileVal)
		{
			$tmp_name = $fileVal['tmp_name'];
			$type = $fileVal['type'];
		}

		$i = 0;
		$sticker_data = array();
		foreach($type as $stid)
		{

			if(strtolower($stid) == "image/png" || strtolower($stid)== "image/jpg" || strtolower($stid)== "image/jpeg")
			{

				$imgtype = explode("/", $stid);
				$imgname= rand(1000, 9999) . "_" .date("d-m-Y") . "__" .time() . "." .$imgtype[1];
				$st_target = "upload/sticker/".$imgname; 

				if(move_uploaded_file($tmp_name[$i],$st_target))
				{
					$stdata = array();
					$stdata['st_image'] 	= $st_target;
					$stdata['sticker_id'] 	= $_POST['sticker_id'][$i];
					$stdata['st_x_pos'] 	= $_POST['st_x_pos'][$i];
					$stdata['st_y_pos'] 	= $_POST['st_y_pos'][$i];
					$stdata['st_width'] 	= $_POST['st_width'][$i];
					$stdata['st_height'] 	= $_POST['st_height'][$i];
					$stdata['st_rotation'] 	= $_POST['st_rotation'][$i];
					$stdata['st_res_id'] 	= $_POST['st_res_id'][$i];
					$stdata['st_res_uri'] 	= $_POST['st_res_uri'][$i];
					$stdata['st_order'] 	= $_POST['st_order'][$i];

					$sticker_data[] = $stdata;
				}

			}
			else
			{
				$stdata = array();
				$stdata['st_image'] 	= $_POST['st_imageOLD'][$i];
				$stdata['sticker_id'] 	= $_POST['sticker_id'][$i];
				$stdata['st_x_pos'] 	= $_POST['st_x_pos'][$i];
				$stdata['st_y_pos'] 	= $_POST['st_y_pos'][$i];
				$stdata['st_width'] 	= $_POST['st_width'][$i];
				$stdata['st_height'] 	= $_POST['st_height'][$i];
				$stdata['st_rotation'] 	= $_POST['st_rotation'][$i];
				$stdata['st_res_id'] 	= $_POST['st_res_id'][$i];
				$stdata['st_res_uri'] 	= $_POST['st_res_uri'][$i];
				$stdata['st_order'] 	= $_POST['st_order'][$i];

				$sticker_data[] = $stdata;
			}
			$i++;
		}

		$j=0;
		$text_data =array();
		foreach($_POST['text_id'] as $v){

			$txtdata['font_family']  	=$_POST['font_family'][$j];
				$txtdata['font_familyiOS']	=$_POST['font_family'][$j];

			$textdata = array();
			$txtdata['text_id'] = $_POST['text_id'][$j];
			$txtdata['text'] 	= $_POST['text'][$j];
			/*$txtdata['font_family'] 	= $_POST['font_family'][$j];
			$txtdata['font_familyiOS'] 	= $_POST['font_familyios'][$j];*/
			$txtdata['txt_color'] 	= $_POST['txt_color'][$j];
			$txtdata['txt_x_pos'] 	= $_POST['txt_x_pos'][$j];
			$txtdata['txt_y_pos'] = $_POST['txt_y_pos'][$j];
			$txtdata['txt_width'] = $_POST['txt_width'][$j];
			$txtdata['txt_height'] = $_POST['txt_height'][$j];
			$txtdata['txt_rotation'] = $_POST['txt_rotation'][$j];
			$txtdata['txt_order'] = $_POST['txt_order'][$j];
			$text_data[]= $txtdata;
			$j++;
		}
		$alldata = array(
			'post_thumb'=>$targetpath,
			'ratio' =>$_POST['ratio'],
			'cat_id'=>$_POST['cat_id'],
			'sticker_info'=>json_encode($sticker_data),
			'text_info' =>json_encode($text_data)
		);

		$this->db->set($alldata)->where('post_id',$_POST['post_id'])->update('poster');
	}
	else 
	{
		$tmp_name = $type = array();
		foreach ($_FILES as $fileVal)
		{
			$tmp_name = $fileVal['tmp_name'];
			$type = $fileVal['type'];
		}

		$i = 0;
		$sticker_data = array();
		foreach($type as $stid)
		{

			if(strtolower($stid) == "image/png" || strtolower($stid)== "image/jpg" || strtolower($stid)== "image/jpeg")
			{

				$imgtype = explode("/", $stid);
				$imgname= rand(1000, 9999) . "_" .date("d-m-Y") . "__" .time() . "." .$imgtype[1];
				$st_target = "upload/sticker/".$imgname; 

				if(move_uploaded_file($tmp_name[$i],$st_target))
				{
					$stdata = array();
					$stdata['st_image'] 	= $st_target;
					$stdata['sticker_id'] 	= $_POST['sticker_id'][$i];
					$stdata['st_x_pos'] 	= $_POST['st_x_pos'][$i];
					$stdata['st_y_pos'] 	= $_POST['st_y_pos'][$i];
					$stdata['st_width'] 	= $_POST['st_width'][$i];
					$stdata['st_height'] 	= $_POST['st_height'][$i];
					$stdata['st_rotation'] 	= $_POST['st_rotation'][$i];
					$stdata['st_res_id'] 	= $_POST['st_res_id'][$i];
					$stdata['st_res_uri'] 	= $_POST['st_res_uri'][$i];
					$stdata['st_order'] 	= $_POST['st_order'][$i];
					$sticker_data[] = $stdata;
				}

			}
			else
			{
				$stdata = array();
				$stdata['st_image'] 	= $_POST['st_imageOLD'][$i];
				$stdata['sticker_id'] 	= $_POST['sticker_id'][$i];
				$stdata['st_x_pos'] 	= $_POST['st_x_pos'][$i];
				$stdata['st_y_pos'] 	= $_POST['st_y_pos'][$i];
				$stdata['st_width'] 	= $_POST['st_width'][$i];
				$stdata['st_height'] 	= $_POST['st_height'][$i];
				$stdata['st_rotation'] 	= $_POST['st_rotation'][$i];
				$stdata['st_res_id'] 	= $_POST['st_res_id'][$i];
				$stdata['st_res_uri'] 	= $_POST['st_res_uri'][$i];
				$stdata['st_order'] 	= $_POST['st_order'][$i];
				$sticker_data[] = $stdata;
			}
			$i++;
		}

		$j=0;
		$text_data =array();
		foreach($_POST['text_id'] as $v)
		{
			$txtdata['font_family']  	=$_POST['font_family'][$j];
				$txtdata['font_familyiOS']	=$_POST['font_family'][$j];
			$textdata = array();
			$txtdata['text_id'] = $_POST['text_id'][$j];
			$txtdata['text'] 	= $_POST['text'][$j];
		//	$txtdata['font_family'] 	= $_POST['font_family'][$j];
		//	$txtdata['font_familyiOS'] 	= $_POST['font_familyios'][$j];
			$txtdata['txt_color'] 	= $_POST['txt_color'][$j];
			$txtdata['txt_x_pos'] 	= $_POST['txt_x_pos'][$j];
			$txtdata['txt_y_pos'] = $_POST['txt_y_pos'][$j];
			$txtdata['txt_width'] = $_POST['txt_width'][$j];
			$txtdata['txt_height'] = $_POST['txt_height'][$j];
			$txtdata['txt_rotation'] = $_POST['txt_rotation'][$j];
			$txtdata['txt_order'] = $_POST['txt_order'][$j];
			$text_data[]= $txtdata;
			$j++;
		}
		

		$alldata = array(
			'ratio' =>$_POST['ratio'],
			'cat_id'=>$_POST['cat_id'],
			'sticker_info'=>json_encode($sticker_data),
			'text_info' =>json_encode($text_data)
		);

		$this->db->set($alldata)->where('post_id',$_POST['post_id'])->update('poster');
	}

	redirect('site/poster');
}


/*public function updateposter()
{

	$sticker_id = explode(",",$_POST['sticker_id']);
	$st_x_pos = explode(",",$_POST['st_x_pos']);
	$st_y_pos = explode(",",$_POST['st_y_pos']);
	$st_width = explode(",",$_POST['st_width']);
	$st_height = explode(",",$_POST['st_height']);
	$st_rotation = explode(",",$_POST['st_rotation']);
	$st_y_rotation = explode(",",$_POST['st_y_rotation']);
	$st_res_id = explode(",",$_POST['st_res_id']);
	$st_res_uri = explode(",",$_POST['st_res_uri']);
	$st_bitmap_url = explode(",",$_POST['st_bitmap_url']);
	$st_type = explode(",",$_POST['st_type']);
	$st_order = explode(",",$_POST['st_order']);

	$text_id= explode(",",$_POST['text_id']);
	$text= explode(",",$_POST['text']);
	$font_family= explode(",",$_POST['font_family']);
	$txt_color= explode(",",$_POST['txt_color']);
	$txt_x_pos= explode(",",$_POST['txt_x_pos']);
	$txt_y_pos= explode(",",$_POST['txt_y_pos']);
	$txt_width= explode(",",$_POST['txt_width']);
	$txt_height= explode(",",$_POST['txt_height']);
	$txt_rotation= explode(",",$_POST['txt_rotation']);
	$txt_type= explode(",",$_POST['txt_type']);
	$txt_order= explode(",",$_POST['txt_order']);

	$post_thumb = 2;
	$bg_image = 2;

	if(isset($_FILES['post_thumb']['name']) && !empty($_FILES['post_thumb']['name']))
	{
		$thumb_image = $_FILES['post_thumb']['name'];
		$ext = pathinfo($thumb_image, PATHINFO_EXTENSION);
		if(strtolower($ext) == 'jpg' || strtolower($ext) =='jpeg' || strtolower($ext) =='png')
		{	
			$imagename = uniqid() . "_" .date("d-m-Y") . "__" .time() . "." . $ext;
			$targetpath = 'upload/thumb_image/' . $imagename;
			if(move_uploaded_file($_FILES['post_thumb']['tmp_name'],$targetpath)){
				$post_thumb = 1;
			}else{
				$post_thumb = 0;
			}
		}
	}
	if(isset($_FILES['back_image']['name']) && !empty($_FILES['back_image']['name']))
	{
		$back_image = $_FILES['back_image']['name'];
		$ext = pathinfo($back_image, PATHINFO_EXTENSION);
		if(strtolower($ext) == 'jpg' || strtolower($ext) =='jpeg' || strtolower($ext) =='png')
		{	
			$background = uniqid() . "_" .date("d-m-Y") . "__" .time() . "." . $ext;
			$bg_target_path = 'upload/background/' . $background;
			if(move_uploaded_file($_FILES['back_image']['tmp_name'],$bg_target_path)){
				$bg_image = 1;
			}else{
				$bg_image = 0;
			}
		}
	}

	if($bg_image ==1 && $post_thumb==1)
	{	


		$i = 0;
		$sticker_data = array();
		$text_data =array();
		foreach($sticker_id as $stid){
			$stdata = array();
			$stdata['sticker_id'] 	= $sticker_id[$i];
			$stdata['st_x_pos'] 	= $st_x_pos[$i];
			$stdata['st_y_pos'] 	= $st_y_pos[$i];
			$stdata['st_width'] 	= $st_width[$i];
			$stdata['st_height'] 	= $st_height[$i];
			$stdata['st_rotation'] 	= $st_rotation[$i];
			$stdata['st_y_rotation'] = $st_y_rotation[$i];
			$stdata['st_res_id'] 	= $st_res_id[$i];
			$stdata['st_res_uri'] 	= $st_res_uri[$i];
			$stdata['st_bitmap_url'] = $st_bitmap_url[$i];
			$stdata['st_type'] 		= $st_type[$i];
			$stdata['st_order'] 	= $st_order[$i];
			$sticker_data[] = $stdata;
			$i++;
		}

		$j=0;
		foreach($text_id as $v){
			$textdata = array();
			$txtdata['text_id'] = $text_id[$j];
			$txtdata['text'] 	= $text[$j];
			$txtdata['font_family'] 	= $font_family[$j];
			$txtdata['txt_color'] 	= $txt_color[$j];
			$txtdata['txt_x_pos'] 	= $txt_x_pos[$j];
			$txtdata['txt_y_pos'] = $txt_y_pos[$j];
			$txtdata['txt_width'] = $txt_width[$j];
			$txtdata['txt_height'] = $txt_height[$j];
			$txtdata['txt_rotation'] = $txt_rotation[$j];
			$txtdata['txt_order'] = $txt_order[$j];
			$txtdata['txt_type'] = $txt_type[$j];
			$text_data[]= $txtdata;
			$j++;
		}

		$alldata = array(
			'post_thumb'=>$targetpath,
			'back_image'=>$bg_target_path,
			'ratio' =>$_POST['ratio'],
			'cat_id'=>$_POST['cat_id'],
			'sticker_info'=>json_encode($sticker_data),
			'text_info' =>json_encode($text_data)
		);

		$this->db->set($alldata)->where('post_id',$_POST['post_id'])->update('poster');

	}
	else if($bg_image == 1){

		$i = 0;
		$sticker_data = array();
		$text_data =array();
		foreach($sticker_id as $stid){
			$stdata = array();
			$stdata['sticker_id'] 	= $sticker_id[$i];
			$stdata['st_x_pos'] 	= $st_x_pos[$i];
			$stdata['st_y_pos'] 	= $st_y_pos[$i];
			$stdata['st_width'] 	= $st_width[$i];
			$stdata['st_height'] 	= $st_height[$i];
			$stdata['st_rotation'] 	= $st_rotation[$i];
			$stdata['st_y_rotation'] = $st_y_rotation[$i];
			$stdata['st_res_id'] 	= $st_res_id[$i];
			$stdata['st_res_uri'] 	= $st_res_uri[$i];
			$stdata['st_bitmap_url'] = $st_bitmap_url[$i];
			$stdata['st_type'] 		= $st_type[$i];
			$stdata['st_order'] 	= $st_order[$i];
			$sticker_data[] = $stdata;
			$i++;
		}

		$j=0;
		foreach($text_id as $v){
			$textdata = array();
			$txtdata['text_id'] = $text_id[$j];
			$txtdata['text'] 	= $text[$j];
			$txtdata['font_family'] 	= $font_family[$j];
			$txtdata['txt_color'] 	= $txt_color[$j];
			$txtdata['txt_x_pos'] 	= $txt_x_pos[$j];
			$txtdata['txt_y_pos'] = $txt_y_pos[$j];
			$txtdata['txt_width'] = $txt_width[$j];
			$txtdata['txt_height'] = $txt_height[$j];
			$txtdata['txt_rotation'] = $txt_rotation[$j];
			$txtdata['txt_order'] = $txt_order[$j];
			$txtdata['txt_type'] = $txt_type[$j];
			$text_data[]= $txtdata;
			$j++;
		}

		$alldata = array(
			'back_image'=>$bg_target_path,
			'ratio' =>$_POST['ratio'],
			'cat_id'=>$_POST['cat_id'],
			'sticker_info'=>json_encode($sticker_data),
			'text_info' =>json_encode($text_data)
		);

		$this->db->set($alldata)->where('post_id',$_POST['post_id'])->update('poster');
	}
	else if($post_thumb ==1){

		$i = 0;
		$sticker_data = array();
		$text_data =array();
		foreach($sticker_id as $stid){
			$stdata = array();
			$stdata['sticker_id'] 	= $sticker_id[$i];
			$stdata['st_x_pos'] 	= $st_x_pos[$i];
			$stdata['st_y_pos'] 	= $st_y_pos[$i];
			$stdata['st_width'] 	= $st_width[$i];
			$stdata['st_height'] 	= $st_height[$i];
			$stdata['st_rotation'] 	= $st_rotation[$i];
			$stdata['st_y_rotation'] = $st_y_rotation[$i];
			$stdata['st_res_id'] 	= $st_res_id[$i];
			$stdata['st_res_uri'] 	= $st_res_uri[$i];
			$stdata['st_bitmap_url'] = $st_bitmap_url[$i];
			$stdata['st_type'] 		= $st_type[$i];
			$stdata['st_order'] 	= $st_order[$i];
			$sticker_data[] = $stdata;
			$i++;
		}

		$j=0;
		foreach($text_id as $v){
			$textdata = array();
			$txtdata['text_id'] = $text_id[$j];
			$txtdata['text'] 	= $text[$j];
			$txtdata['font_family'] 	= $font_family[$j];
			$txtdata['txt_color'] 	= $txt_color[$j];
			$txtdata['txt_x_pos'] 	= $txt_x_pos[$j];
			$txtdata['txt_y_pos'] = $txt_y_pos[$j];
			$txtdata['txt_width'] = $txt_width[$j];
			$txtdata['txt_height'] = $txt_height[$j];
			$txtdata['txt_rotation'] = $txt_rotation[$j];
			$txtdata['txt_order'] = $txt_order[$j];
			$txtdata['txt_type'] = $txt_type[$j];
			$text_data[]= $txtdata;
			$j++;
		}

		$alldata = array(
			'post_thumb'=>$targetpath,
			'ratio' =>$_POST['ratio'],
			'cat_id'=>$_POST['cat_id'],
			'sticker_info'=>json_encode($sticker_data),
			'text_info' =>json_encode($text_data)
		);

		$this->db->set($alldata)->where('post_id',$_POST['post_id'])->update('poster');
	}
	else {

		$i = 0;
		$sticker_data = array();
		$text_data =array();
		foreach($sticker_id as $stid){
			$stdata = array();
			$stdata['sticker_id'] 	= $sticker_id[$i];
			$stdata['st_x_pos'] 	= $st_x_pos[$i];
			$stdata['st_y_pos'] 	= $st_y_pos[$i];
			$stdata['st_width'] 	= $st_width[$i];
			$stdata['st_height'] 	= $st_height[$i];
			$stdata['st_rotation'] 	= $st_rotation[$i];
			$stdata['st_y_rotation'] = $st_y_rotation[$i];
			$stdata['st_res_id'] 	= $st_res_id[$i];
			$stdata['st_res_uri'] 	= $st_res_uri[$i];
			$stdata['st_bitmap_url'] = $st_bitmap_url[$i];
			$stdata['st_type'] 		= $st_type[$i];
			$stdata['st_order'] 	= $st_order[$i];
			$sticker_data[] = $stdata;
			$i++;
		}

		$j=0;
		foreach($text_id as $v){
			$textdata = array();
			$txtdata['text_id'] = $text_id[$j];
			$txtdata['text'] 	= $text[$j];
			$txtdata['font_family'] 	= $font_family[$j];
			$txtdata['txt_color'] 	= $txt_color[$j];
			$txtdata['txt_x_pos'] 	= $txt_x_pos[$j];
			$txtdata['txt_y_pos'] = $txt_y_pos[$j];
			$txtdata['txt_width'] = $txt_width[$j];
			$txtdata['txt_height'] = $txt_height[$j];
			$txtdata['txt_rotation'] = $txt_rotation[$j];
			$txtdata['txt_order'] = $txt_order[$j];
			$txtdata['txt_type'] = $txt_type[$j];
			$text_data[]= $txtdata;
			$j++;
		}

		$alldata = array(
			'ratio' =>$_POST['ratio'],
			'cat_id'=>$_POST['cat_id'],
			'sticker_info'=>json_encode($sticker_data),
			'text_info' =>json_encode($text_data)
		);

		$this->db->set($alldata)->where('post_id',$_POST['post_id'])->update('poster');
	}

	echo json_encode("true");
}*/

public function updatecat(){


	if(isset($_FILES['cat_thumb']['name']) && !empty($_FILES['cat_thumb']['name']))
	{

		$filename = $_FILES['cat_thumb']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if(strtolower($ext) == 'jpg' || strtolower($ext) =='jpeg' || strtolower($ext) =='png')
		{	

			$imagename = uniqid() . "_" .date("d-m-Y") . "__" .time() . "." . $ext;
			$targetpath = 'upload/thumb_image/' . $imagename;
			$data = array(

				'cat_name' => $_POST['cat_name'],
				'sequence_number' => $_POST['sequence_number'],
				'cat_thumb' => $targetpath,
			);

			if(move_uploaded_file($_FILES['cat_thumb']['tmp_name'],$targetpath)){
				$this->Db_function->update('category',$data,$_POST['cat_id'],'cat_id');
			}
			redirect('site/category');
				//echo json_encode("app Updated Successfully");
		}
	}
	else
	{

		$data = array(
			'cat_name' => $_POST['cat_name'],
			'sequence_number' => $_POST['sequence_number'],
		);			

		$this->Db_function->update('category',$data,$_POST['cat_id'],'cat_id');	
		redirect('site/category');

	}

}
public function delete_poster(){

	$this->Db_function->delete('poster',$_POST['post_id'],'post_id');
	echo json_encode("Poster is Successfully Deleted from Table !");
}
public function deletecat(){

	$this->Db_function->delete('category',$_POST['cat_id'],'cat_id');
	echo json_encode("Category is Successfully Deleted from Table !");
}

public function myqry(){

	$total = 18;

	for($i=1;$i<=$total;$i++)
	{
		$folder = 'sports'; $cat = '13';

		$targetpath = 'upload/poster_BG/thumb/'.$folder.'/'.$i.'.jpg';	
		$imgpath = 'upload/poster_BG/bg_images/'.$folder.'/'.$i.'.jpg' ;

		$this->db->insert('poster_BG',array('bg_thumb'=>$targetpath,'cat_id'=>$cat,'background'=>$imgpath));

		//$this->db->set('background',$imgpath)->where('bg_id',$start)->update('poster_BG');
		//echo $this->db->last_query();die;
		// $this->db->set('wall_likes',rand(300,1500))->where('wall_id',$start)->where('wall_likes',3001)->update('wallpaper');
		

	}
}

public function try12(){
	$res = $this->db->select('post_id')->where('cat_id','21')->get('poster')->result_array();
	echo "<pre>";
	print_r($res);

}

public function showfonts(){
	$this->load->view('header');
	$this->load->view('sidebar');

	$data = file_get_contents('http://<varnifonts>/api/v1/fonts');
	$Data = json_decode($data);

	$res = $this->db->select('text_info')->get('poster')->result_array();
	
	foreach($res as $val){
		$fontdata[]= json_decode($val['text_info']);
	}
	foreach ($fontdata as $font) {
		if(!empty($font)){
			foreach ($font as $DD) {
				$Fonts[] = $DD->font_family;		
			}
		}
	}
	
	$finalFont = array_unique($Fonts);
	

	foreach($Data->font as $apiFont){
		$i = 0;
		$j = 0;
		foreach ( $finalFont as $dbfont )
		{  
			if($dbfont==$apiFont->android_font){
				$i=1;                                
			}
		}
		if($i == 1 ){
			$fontPackage[] = $apiFont;
		}
	}
	$appFont_count = count($finalFont);
	$getFont_count = count($fontPackage);
// print_r($appFont_count);
// print_r($getFont_count);
// die;
	$notFound = ($appFont_count - $getFont_count);

	$this->load->view('fonts',array('data'=>$fontPackage,"total_font_in_app"=>$appFont_count,'notFound'=>$notFound));
	$this->load->view('footer');
}

public function download() {
	$this->load->view('header');
	$this->load->view('sidebar');
	$result = $this->db->select('*')
				->from('category')
				->get()
				->result_array();
	$ratio  = $this->db->distinct()->select('ratio')->from('poster')->get()->result_array();
	$this->load->view('download',array('cat'=>$result,'ratio'=>$ratio));
	$this->load->view('footer');
}

	public function fontList()
	{
			$this->load->view('header');
			$this->load->view('sidebar');
			$result = $this->db->select('*')
						->from('poster')
						->get()
						->result_array();

			foreach ($result as $key => $value) {
				
				$fontlist[] = json_decode($value['text_info']); 

			}
			foreach ($fontlist as $font) {					

					foreach ($font as $key => $value) {
						$data[] =   $value->font_family;	
					}
			}
	
			$finalFont = array_unique($data);

			$allfont = $this->db->select('*')
						->from('poster')
						->get()
						->result_array();


			$match = base_url().'assets/font/28 Days Later.ttf';

			echo $match; die;

			$this->load->view('fontList',array('finalFont'=>$finalFont));
			$this->load->view('footer');
	}
	public function trending_festivals() {
		$this->load->view('header');
		$this->load->view('sidebar');
		
		$app_id = $this->input->get('app_id') ? $this->input->get('app_id') : 1;
		
		$result = $this->db->select('t.*, c.cat_name')->from('trending_festivals t')->join('category c', 't.cat_id = c.cat_id', 'left')->where('t.app_id', $app_id)->order_by('CAST(t.sequence_number AS UNSIGNED)', 'ASC')->get()->result_array();
		$categories = $this->db->order_by('CAST(sequence_number AS UNSIGNED)', 'ASC')->get('category')->result_array();
		$this->load->view('trending_festivals', array('data'=>$result, 'categories'=>$categories, 'app_id'=>$app_id));
		$this->load->view('footer');
	}

	public function insert_trending() {
		$app_id = $this->input->post('app_id') ? $this->input->post('app_id') : 1;
		if(isset($_FILES['banner_image']['tmp_name']) && !empty($_FILES['banner_image']['tmp_name'])) {
			$filename = $_FILES['banner_image']['name'];
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			$allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
			if(in_array($ext, $allowed)) {	
				$imagename = uniqid() . "_" . date("d-m-Y") . "__" . time() . "." . $ext;
				$upload_dir = FCPATH . 'upload/thumb_image/';
				if(!is_dir($upload_dir)) { @mkdir($upload_dir, 0777, true); }
				$move_path = $upload_dir . $imagename;
				$db_path = 'upload/thumb_image/' . $imagename;
				if(move_uploaded_file($_FILES['banner_image']['tmp_name'], $move_path)){
					$data = array(
						'app_id' => $app_id,
						'cat_id' => $_POST['cat_id'],
						'sequence_number' => $_POST['sequence_number'],
						'banner_image' => $db_path
					);
					$this->db->insert('trending_festivals', $data);
				}
			}
		}	
		redirect('site/trending_festivals?app_id=' . $app_id);
	}

	public function update_trending() {
		$id = $this->input->post('id');
		$app_id = $this->input->post('app_id') ? $this->input->post('app_id') : 1;
		if(!empty($id)) {
			$data = array(
				'cat_id' => isset($_POST['cat_id']) ? $_POST['cat_id'] : '',
				'sequence_number' => isset($_POST['sequence_number']) ? $_POST['sequence_number'] : 0,
			);
			if(isset($_FILES['banner_image']['tmp_name']) && !empty($_FILES['banner_image']['tmp_name'])) {
				$filename = $_FILES['banner_image']['name'];
				$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
				$allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
				if(in_array($ext, $allowed)) {
					$imagename = uniqid() . "_" . date("d-m-Y") . "__" . time() . "." . $ext;
					$upload_dir = FCPATH . 'upload/thumb_image/';
					if(!is_dir($upload_dir)) { @mkdir($upload_dir, 0777, true); }
					$move_path = $upload_dir . $imagename;
					$db_path = 'upload/thumb_image/' . $imagename;
					if(move_uploaded_file($_FILES['banner_image']['tmp_name'], $move_path)){
						$data['banner_image'] = $db_path;
					}
				}
			}
			$this->db->where('id', $id)->update('trending_festivals', $data);
		}
		redirect('site/trending_festivals?app_id=' . $app_id);
	}

	public function trending_sorting() {
		if(isset($_POST['position']) && is_array($_POST['position'])) {
			$i = 0;
			foreach($_POST['position'] as $id) {
				$this->db->where('id', $id)->set('sequence_number', $i)->update('trending_festivals');
				$i++;
			}
			echo json_encode(array("status" => "success"));
		}
	}

	public function delete_trending($id) {
		$this->db->where('id', $id)->delete('trending_festivals');
		redirect('site/trending_festivals');
	}

	public function hero_banners() {
		$this->load->view('header');
		$this->load->view('sidebar');
		
		$app_id = $this->input->get('app_id') ? $this->input->get('app_id') : 1;
		
		$result = $this->db->where('app_id', $app_id)->order_by('CAST(sequence_number AS UNSIGNED)', 'ASC')->get('hero_banners')->result_array();
		$categories = $this->db->order_by('CAST(sequence_number AS UNSIGNED)', 'ASC')->get('category')->result_array();
		$this->load->view('hero_banners', array('data'=>$result, 'categories'=>$categories, 'app_id'=>$app_id));
		$this->load->view('footer');
	}

	public function insert_hero() {
		$app_id = $this->input->post('app_id') ? $this->input->post('app_id') : 1;
		if(isset($_FILES['banner_image']['tmp_name']) && !empty($_FILES['banner_image']['tmp_name'])) {
			$filename = $_FILES['banner_image']['name'];
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			$allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
			if(in_array($ext, $allowed)) {	
				$imagename = uniqid() . "_hero_" . date("d-m-Y") . "__" . time() . "." . $ext;
				$upload_dir = FCPATH . 'upload/thumb_image/';
				if(!is_dir($upload_dir)) { @mkdir($upload_dir, 0777, true); }
				$move_path = $upload_dir . $imagename;
				$db_path = 'upload/thumb_image/' . $imagename;
				if(move_uploaded_file($_FILES['banner_image']['tmp_name'], $move_path)){
					$action_url = isset($_POST['action_url']) ? $_POST['action_url'] : '';
					$sequence_number = isset($_POST['sequence_number']) ? $_POST['sequence_number'] : 0;
					$data = array(
						'app_id' => $app_id,
						'action_url' => $action_url,
						'sequence_number' => $sequence_number,
						'banner_image' => $db_path
					);
					$this->db->insert('hero_banners', $data);
				}
			}
		}	
		redirect('site/hero_banners?app_id=' . $app_id);
	}

	public function update_hero() {
		$id = $this->input->post('id');
		$app_id = $this->input->post('app_id') ? $this->input->post('app_id') : 1;
		if(!empty($id)) {
			$data = array(
				'action_url' => isset($_POST['action_url']) ? $_POST['action_url'] : '',
				'sequence_number' => isset($_POST['sequence_number']) ? $_POST['sequence_number'] : 0,
			);
			if(isset($_FILES['banner_image']['tmp_name']) && !empty($_FILES['banner_image']['tmp_name'])) {
				$filename = $_FILES['banner_image']['name'];
				$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
				$allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
				if(in_array($ext, $allowed)) {
					$imagename = uniqid() . "_hero_" . date("d-m-Y") . "__" . time() . "." . $ext;
					$upload_dir = FCPATH . 'upload/thumb_image/';
					if(!is_dir($upload_dir)) { @mkdir($upload_dir, 0777, true); }
					$move_path = $upload_dir . $imagename;
					$db_path = 'upload/thumb_image/' . $imagename;
					if(move_uploaded_file($_FILES['banner_image']['tmp_name'], $move_path)){
						$data['banner_image'] = $db_path;
					}
				}
			}
			$this->db->where('id', $id)->update('hero_banners', $data);
		}
		redirect('site/hero_banners?app_id=' . $app_id);
	}

	public function hero_sorting() {
		if(isset($_POST['position']) && is_array($_POST['position'])) {
			$i = 0;
			foreach($_POST['position'] as $id) {
				$this->db->where('id', $id)->set('sequence_number', $i)->update('hero_banners');
				$i++;
			}
			echo json_encode(array("status" => "success"));
		}
	}

	public function delete_hero($id) {
		$this->db->where('id', $id)->delete('hero_banners');
		redirect('site/hero_banners');
	}

	private function _require_admin_session($is_json = false) {
		if (!$this->session->userdata('poster_sess_user_name')) {
			if ($is_json) {
				$this->output->set_status_header(401);
				echo json_encode(array('status' => 'error', 'message' => 'Unauthorized'));
				exit;
			}
			redirect('admin');
			exit;
		}
	}

	public function home_categories() {
		$this->_require_admin_session();
		$this->load->model('Home_category_model');

		$app_id = $this->input->get('app_id') ? (int)$this->input->get('app_id') : 2;
		$result = $this->Home_category_model->get_by_app($app_id);
		$categories = $this->db->order_by('CAST(sequence_number AS UNSIGNED)', 'ASC')->get('category')->result_array();
		$bg_categories = $this->db->order_by('CAST(sequence_number AS UNSIGNED)', 'ASC')->get('background_category')->result_array();
		$action_types = Home_category_model::get_action_types();

		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('home_categories', array(
			'data' => $result,
			'categories' => $categories,
			'bg_categories' => $bg_categories,
			'action_types' => $action_types,
			'app_id' => $app_id
		));
		$this->load->view('footer');
	}

	public function insert_home_category() {
		$this->_require_admin_session();
		$this->load->model('Home_category_model');

		$app_id = $this->input->post('app_id') ? (int)$this->input->post('app_id') : 2;
		$payload = $this->Home_category_model->build_payload_from_post($_POST, false);
		if ($payload['name'] !== '') {
			$this->Home_category_model->insert_item($payload);
			$this->session->set_flashdata('hc_success', 'Home Category added successfully.');
		} else {
			$this->session->set_flashdata('hc_error', 'Category name is required.');
		}
		redirect('site/home_categories?app_id=' . $app_id);
	}

	public function update_home_category() {
		$this->_require_admin_session();
		$this->load->model('Home_category_model');

		$id = (int)$this->input->post('id');
		$app_id = $this->input->post('app_id') ? (int)$this->input->post('app_id') : 2;
		if ($id > 0) {
			$payload = $this->Home_category_model->build_payload_from_post($_POST, true);
			if ($payload['name'] !== '') {
				$this->Home_category_model->update_item($id, $payload);
				$this->session->set_flashdata('hc_success', 'Home Category updated successfully.');
			} else {
				$this->session->set_flashdata('hc_error', 'Category name is required.');
			}
		}
		redirect('site/home_categories?app_id=' . $app_id);
	}

	public function home_category_sorting() {
		$this->_require_admin_session(true);
		$this->load->model('Home_category_model');

		header('Content-Type: application/json');
		if (isset($_POST['position']) && is_array($_POST['position'])) {
			$this->Home_category_model->update_sort_order($_POST['position']);
			echo json_encode(array('status' => 'success'));
			return;
		}
		echo json_encode(array('status' => 'error', 'message' => 'Invalid positions payload'));
	}

	public function toggle_home_category($id = null) {
		$is_ajax = $this->input->is_ajax_request();
		$this->_require_admin_session($is_ajax);
		$this->load->model('Home_category_model');

		$target_id = $id !== null ? (int)$id : (int)$this->input->post('id');
		$next = $this->Home_category_model->toggle_active($target_id);

		if ($is_ajax) {
			header('Content-Type: application/json');
			if ($next === null) {
				echo json_encode(array('status' => 'error', 'message' => 'Category not found'));
			} else {
				echo json_encode(array('status' => 'success', 'is_active' => $next));
			}
			return;
		}

		$app_id = $this->input->get('app_id') ? (int)$this->input->get('app_id') : 2;
		redirect('site/home_categories?app_id=' . $app_id);
	}

	public function delete_home_category($id) {
		$this->_require_admin_session();
		$this->load->model('Home_category_model');

		$app_id = $this->input->get('app_id') ? (int)$this->input->get('app_id') : 2;
		$this->Home_category_model->delete_item((int)$id);
		$this->session->set_flashdata('hc_success', 'Home Category deleted.');
		redirect('site/home_categories?app_id=' . $app_id);
	}

	public function seed_home_categories() {
		$this->_require_admin_session();
		$this->load->model('Home_category_model');

		$app_id = $this->input->get('app_id') ? (int)$this->input->get('app_id') : 2;
		$this->Home_category_model->ensure_table_and_seed();
		$seeded = $this->Home_category_model->seed_defaults($app_id);
		if ($seeded) {
			$this->session->set_flashdata('hc_success', 'Default Home Categories seeded for App ' . $app_id . '.');
		} else {
			$this->session->set_flashdata('hc_error', 'App ' . $app_id . ' already has Home Categories.');
		}
		redirect('site/home_categories?app_id=' . $app_id);
	}

}