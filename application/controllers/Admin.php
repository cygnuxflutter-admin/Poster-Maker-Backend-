<?php

class Admin extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model("Db_function");
	}

	public function index()
	{
		if($this->session->userdata('poster_sess_user_name')){
			redirect('admin/showAdmin');
		} else {
			$this->load->view('login');
		}
	}

	public function authenticate()
	{
		$result = false;
		if(isset($_POST['submit'])){
			$result= $this->Db_function->login_verify('login_user',array('username'=>$_POST['username'],'password'=>md5($_POST['password'])));	
		}
		if(!empty($result)){	
			$data = array('poster_sess_user_name'=>$result[0]['username'], 'poster_sess_user_id'=>$result[0]['user_id']);
			$this->session->set_userdata($data);
			redirect('admin/showAdmin');
		}
		else{
			$this->session->set_flashdata('error','Wrong Username, password combination.');
			redirect('admin');
		}
	}
	public function showAdmin(){
		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('admin');
		$this->load->view('footer');
	}
	public function logout(){
		$this->session->unset_userdata('poster_sess_user_name');
		redirect('admin');
	}

}

?>