<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model
{
	public function loadData($table)
	{
		$res = $this->db->select('*')->where('is_admin','0')->get($table)->result_array();
		return $res;
	}

	public function insert($data,$table){
		$this->db->insert($data,$table);
	}
}
?>