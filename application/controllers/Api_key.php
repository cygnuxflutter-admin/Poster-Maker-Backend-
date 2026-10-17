<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_key extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Db_function');
	}

	public function index()
	{
		$data['apiKey'] = $this->db->order_by("key_id","desc")->limit(1)->get('tbl_key')->row_array();

		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('api_key',$data);		
		$this->load->view('footer');
	}

	public function saveApi()
	{
		if( (isset($_POST['androidKey']) && isset($_POST['iosKey'])) || (empty($_POST['androidKey']) && empty($_POST['iosKey'])) ) {
			$arrayData = array( 'android_key' => $_POST['androidKey'] , 'iOS_key' => $_POST['iosKey'] );
			$result = $this->db->where('key_id','1')->update('tbl_key',$arrayData);
			if($result){
				echo "true";
			}else{
				echo "false";
			}
		}else{
			echo "false";
		}
	}
	
}
