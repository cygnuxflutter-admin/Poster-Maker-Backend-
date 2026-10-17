<?php

class Db_function extends CI_Model{

	public function login_verify($table,$data){
		$this->db->select();
		$this->db->from($table);
		$this->db->where($data);

		$query= $this->db->get();

		if($query->num_rows()==1){
			return $query->result_array();
		}
		else{
			return false;
		}
	}	

	public function checkUser($table, $data = "null")
	{
		$this->db->select();
		$this->db->from($table);
		$this->db->where($data);

		$query= $this->db->get();

		if($query->num_rows()==1){
			return true;
		}
		else{
			return false;
		}
	}
	public function loadDataForUpdate($table,$field,$id,$type)
	{
		if($type=='join')
		{

			$this->db->select();
			$this->db->from($table);
			$this->db->join('category as ct','ct.cat_id = '.$table.'.cat_id');
			$this->db->where($field,$id);
			$query = $this->db->get();
			if($query->num_rows()>0){
				return $query->result_array();
			}
		}
		else
		{
			$this->db->select();
			$this->db->from($table);
			$this->db->where($field,$id);
			$query = $this->db->get();

			if($query->num_rows()>0){
				return $query->result_array();
			}

		}
		
	}
	public function getImage($table,$field,$where,$id){
		$this->db->select($field);
		$this->db->from($table);
		$this->db->where($where,$id);
		$qry = $this->db->get();

		if($qry->num_rows() > 0){
			return $qry->result_array();
		}
	}

	public function loaddatamulti($table, $is_delete , $field , $id,$where){
		

		if($where =='y')
		{

			$this->db->select('mod_id');
			$this->db->from($table[0]);
			$this->db->where($field,$id);
			$this->db->where($table[0].'.is_delete',$is_delete);
			$rawData = $this->db->get();
			$row = $rawData->result_array();

			$mIds = explode(',', $row[0]['mod_id']);
			
			$ids = array();
			foreach($mIds as $org)
			{    
				$ids[]= $org;
			}

			
			//$this->db->where('cat.cat_id','1');


			$this->db->select();
			$this->db->from($table[0]);
			$this->db->join($table[1],$table[1].'.cat_id='.$table[0].'.cat_id');
			$this->db->join($table[2],$table[2].'.user_id='.$table[0].'.user_id');
			$this->db->where_in($table[2].'.model_id', $ids);
			$this->db->join($table[3],$table[3].'.oc_id='.$table[0].'.oc_id');
			$this->db->where($field,$id);
			$this->db->where($table[0].'.is_delete',$is_delete);

			$qry = $this->db->get();
			
			if($qry->num_rows() > 0){
				return $qry->result_array();
			}

		}
		else 
		{	

			$this->db->select();
			$this->db->from($table[0]);
			$this->db->join($table[1],$table[1].'.cat_id='.$table[0].'.cat_id');
			$this->db->join($table[2], $table[2].'.model_id='.$table[0].'.mod_id');
			$this->db->join($table[3],$table[3].'.oc_id='.$table[0].'.oc_id');
			$this->db->where($table[0].'.is_delete',$is_delete);
			$qry = $this->db->get();
			if($qry->num_rows() > 0){
				return $qry->result_array();
			}
		}
		
	}

	public function loadDataLike($table,$field, $value, $is_delete){
		$this->db->select();
		$this->db->from($table);
		$this->db->like($field, $value,'after');
		$this->db->where($table.'.is_delete',$is_delete);
		$qry = $this->db->get();
		if($qry->num_rows() > 0){
			return $qry->result_array();
		}
	}

	public function loadposter($table)
	{
		$this->db->select('post_id,cat_id,ratio,back_image,post_thumb');
		$this->db->from($table);

		$query = $this->db->get();

		if($query->num_rows() > 0){
			return $query->result_array();
		}
		else{
			return false;
		}	
	}

	public function loadData($table,$type,$orderby='null'){
		if($type=='join')
		{

			$this->db->select();
			$this->db->from($table);
			$this->db->join('catagory','catagory.cat_id = '.$table.'.cat_id');
			$query = $this->db->get();

			if($query->num_rows() > 0){
				return $query->result_array();
			}
			else{
				return false;
			}	
		}
		else if($orderby=='null'){
			
			$this->db->select();
			$this->db->from($table);
			if($table == 'category'){
				$this->db->order_by('CAST(sequence_number AS UNSIGNED) ASC, cat_id ASC');
			}

			$query = $this->db->get();

			if($query->num_rows() > 0){
				return $query->result_array();
			}
			else{
				return false;
			}	
		}
		else
		{	
			$this->db->select();
			$this->db->from($table);
			$this->db->order_by($orderby,'asc');
			$this->db->where('is_delete',$is_delete);
			$query = $this->db->get();

			if($query->num_rows() > 0){
				return $query->result_array();
			}
			else{
				return false;
			}
		}


	}

	public function insertdata($table, $field, $data){
		$this->db->select($field);
		$this->db->from($table);
		$this->db->where($field, $data[$field]);
		$query = $this->db->get();
		if($query->num_rows() > 0){

			return false;
		}
		else{
			
			$this->db->insert($table, $data);
		}

	}

	public function delete($table,$id,$field){
		$this->db->where($field,$id);
		$this->db->delete($table);

	}

	public function update($table, $data ,$id, $field){

		
		$this->db->where($field, $id);
		$this->db->update($table, $data);

		$this->db->select();
		$this->db->from($table);
		$this->db->where($field,$id);
		$qry = $this->db->get();
		if($qry->num_rows() > 0){
			return $qry->result_array();
		}

	}

	public function popularity(){
		$this->db->select('wall_id, wall_likes, wall_download, wall_share');
		$this->db->from('wallpaper');
		$qry = $this->db->get()->result_array();

		foreach($qry as $val){
			$popularity=($val['wall_likes'] + $val['wall_download'] + $val['wall_share']) /3; 
			$this->db->set('popularity',$popularity)->where('wall_id',$val['wall_id'])->update('wallpaper');
		}

	}

	// Ajax Data Fetching Function set :

	public function allData_count($table)
	{
		$query = $this
		->db
		->get($table);
		return $query->num_rows(); 
	}
	public function allData($limit,$start,$col,$dir,$tbl)
	{
		$query = $this
		->db
		->limit($limit,$start)
		->order_by($col,$dir)
		->get($tbl);
		if($query->num_rows()>0)
		{
			return $query->result_array(); 
		}
		else
		{
			return null;
		}
	}
	public function Data_search($limit,$start,$search,$col,$dir,$tbl,$like_field,$like2,$sel_field,$tbl2)
	{
		
		foreach($like_field as $key => $value){
			if($key == 0) {
				$this->db->like($value,$search);
			} else {
				$this->db->or_like($value,$search);
			}
		}
		$query = $this
		->db
		->limit($limit,$start)
		->order_by($col,$dir)
		->get($tbl);

		//echo $this->db->last_query();die;

		if($query->num_rows()>0)
		{
			return $query->result_array();  
		}
		else
		{
			$res = $this->db->like($like2,$search)->select($sel_field)->get($tbl2)->result_array();
			//	echo $this->db->last_query();die;
			
			foreach ($res as $value) {
				$arr[] = $value['cat_id'];
			}
			$IDS = implode(',',$arr);
			
			if($res){
				$qry = $this
				->db
				->limit($limit,$start)
				->order_by($col,$dir)
				->where('cat_id IN ('.$IDS.')')
				->get($tbl);
				//echo $this->db->last_query();die;
				if($qry->num_rows()>0){
					return $qry->result_array();
				}
				else{
					return null;
				}
			}
		}
	}
	public function Data_search_count($search,$tbl,$like_field,$like2,$sel_field,$tbl2)
	{
		foreach($like_field as $key => $value){
			if($key == 0) {
				$this->db->like($value,$search);
			} else {
				$this->db->or_like($value,$search);
			}
		}
		$query = $this
		->db
		->get($tbl);


		if($query->num_rows()>0)
		{
			return $query->num_rows();  
		}
		else
		{
			$res = $this->db->like($like2,$search)->select($sel_field)->get($tbl2)->result_array();
			foreach ($res as $value) {
				$arr[] = $value['cat_id'];
			}
			$IDS = implode(',',$arr);
			
			if($res){
				$qry = $this
				->db
				->where('cat_id IN ('.$IDS.')')
				->get($tbl);

				if($qry->num_rows()>0){
					return $qry->num_rows();
				}
				else{
					return null;
				}
			}
		}
	}
	public function sortUpdate()
	{
		if(isset($_POST['position']) && is_array($_POST['position']))
		{
			$position = $_POST['position'];
			$i = 0;
			foreach($position as $k => $v){
				$this->db->where('cat_id', $v);
				$this->db->set('sequence_number', $i);
				$this->db->update("category");
				$i++;
			}
			echo json_encode(array("status" => "success", "message" => "Sequence updated successfully"));
		}
	}
}
?>