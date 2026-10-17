<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model','us');
	}
	public function index(){
		
		$this->load->view('header');
		$this->load->view('sidebar');
		$result = $this->us->loadData('login_user');
		$this->load->view('users/index',array('data'=>$result));
		$this->load->view('footer');
	}
	public function adduser(){
		
		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('users/add');
		$this->load->view('footer');
	}
	public function insert()
	{
		$menu = implode(',',$_POST['ms_example']);
		$role = implode(',',$_POST['userRole']);
		$data = array(
			'username' => $_POST['user_name'],
			'email'=> $_POST['user_email'],
			'password'=> md5($_POST['user_password']),
			'menu_id'=>$menu,
			'user_role' =>$role,
			
		);
		
		$this->db->insert('login_user',$data);
		redirect('user');
	}
	public function updateUser(){
		$id = $_GET['edit'];
		$result['data'] = $this->db->Where('user_id',$id)->get("login_user")->result_array();
		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('users/edit',$result);
		$this->load->view('footer');
	}
	public function edit()
	{
		$menu = implode(',',$_POST['ms_example']);
		$role = implode(',',$_POST['userRole']);
		$data = array(
			'username' => $_POST['user_name'],
			'email'=> $_POST['user_email'],
			'password'=> md5($_POST['user_password']),
			'menu_id'=>$menu,
			'user_role' =>$role,
		);

		$this->db->set($data)->where('user_id',$_POST['user_id'])->update('login_user');
		redirect('user');
	}

	public function deleteUser(){
		$id = $_POST['user_id'];
		$this->db->where('user_id',$id)->delete('login_user');
		echo json_encode("User Deleted !");
	}
}
?>