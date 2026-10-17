<?php
class Download extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Db_function');
		$this->load->library('zip');
		$this->load->helper('download');
	//	header('Content-Type:application/json');
	}

	public function swiperCat()
	{
		if(isset($_POST['cat_id']) && $_POST['cat_id']=='0' && isset($_POST['ratio']) && $_POST['ratio']=='0')
		{

			$result = $this->db->select('cat_id,cat_name,cat_thumb')->order_by("FIELD(cat_id,'7','41','45') DESC")->get('category')->result_array();

			$rows = array();

			foreach ($result as $rwData)
			{	
				$ar = array();
				$ct = $this->db->select('post_id,post_thumb,ratio')
				->where('cat_id',$rwData['cat_id'])
				->get('poster')
				->result_array();

				foreach($ct as $c){ 

					$c['post_thumb'] = base_url($c['post_thumb']);
					$ar [] = array(
						'post_id' => $c['post_id'],
						'post_thumb' =>$c['post_thumb'],
						'ratio' => $c['ratio']
					);
				}

				$rows[] = array(
					'cat_id' => $rwData['cat_id'],
					'cat_name' =>$rwData['cat_name'],
					'thumb_img' =>base_url($rwData['cat_thumb']),
					'poster_list'=>$ar
				);

			}


		}
		else if($_POST['cat_id'] > 0 && $_POST['ratio'] !=0)
		{
			$result = $this->db->where('cat_id',$_POST['cat_id'])->get('category')->result_array();

			$rows = array();

			foreach ($result as $rwData)
			{	
				$ar = array();
				$ct = $this->db->select('post_id,post_thumb,ratio')->where('cat_id',$rwData['cat_id'])->where('ratio',$_POST['ratio'])->get('poster')->result_array();

				foreach($ct as $c){ 

					$c['post_thumb'] = base_url($c['post_thumb']);
					$ar [] = array(
						'post_id' => $c['post_id'],
						'post_thumb' =>$c['post_thumb'],
						'ratio' => $c['ratio']
					);
				}

				$rows[] = array(
					'cat_id' => $rwData['cat_id'],
					'cat_name' =>$rwData['cat_name'],
					'thumb_img' =>base_url($rwData['cat_thumb']),
					'poster_list'=>$ar
				);

			}


		}
		else if($_POST['cat_id'] > 0 && $_POST['ratio'] ==0)
		{

			$result = $this->db->where('cat_id',$_POST['cat_id'])->get('category')->result_array();

			$rows = array();

			foreach ($result as $rwData)
			{	
				$ar = array();
				$ct = $this->db->select('post_id,post_thumb,ratio')->where('cat_id',$rwData['cat_id'])->get('poster')->result_array();

				foreach($ct as $c){ 

					$c['post_thumb'] = base_url($c['post_thumb']);
					$ar [] = array(
						'post_id' => $c['post_id'],
						'post_thumb' =>$c['post_thumb'],
						'ratio' => $c['ratio']
					);
				}

				$rows[] = array(
					'cat_id' => $rwData['cat_id'],
					'cat_name' =>$rwData['cat_name'],
					'thumb_img' =>base_url($rwData['cat_thumb']),
					'poster_list'=>$ar
				);

			}

		}
		else if($_POST['cat_id'] ==0 && $_POST['ratio'] !=0)
		{

			$result = $this->Db_function->loaddata('category','nor');
			$rows = array();

			foreach ($result as $rwData)
			{	
				$ar = array();
				$ct = $this->db->select('post_id,post_thumb,ratio')->where('cat_id',$rwData['cat_id'])->where('ratio',$_POST['ratio'])->get('poster')->result_array();

				foreach($ct as $c){ 

					$c['post_thumb'] = base_url($c['post_thumb']);
					$ar [] = array(
						'post_id' => $c['post_id'],
						'post_thumb' =>$c['post_thumb'],
						'ratio' => $c['ratio']
					);
				}

				$rows[] = array(
					'cat_id' => $rwData['cat_id'],
					'cat_name' =>$rwData['cat_name'],
					'thumb_img' =>base_url($rwData['cat_thumb']),
					'poster_list'=>$ar
				);

			}

		}

		$response['error'] = '1';
		$response['message'] = 'Loading Success!';
		$response['data'] = $rows;


		
		echo json_encode($response);

		
	}

	public function swiperCatDownload()
	{
		$paths = '';
 		$this->zip->add_dir('assets');


		if(isset($_POST['cat_id']) && $_POST['cat_id']=='0' && isset($_POST['ratiodata']) && $_POST['ratiodata']=='0')
		{

			$result = $this->db->select('cat_id,cat_name,cat_thumb')->order_by("FIELD(cat_id,'7','41','45') DESC")->get('category')->result_array();

			$rows = array();

			foreach ($result as $rwData)
			{	
				$ar = array();
				$ct = $this->db->select('post_id,post_thumb,ratio')
				->where('cat_id',$rwData['cat_id'])
				->get('poster')
				->result_array();

				foreach($ct as $c){ 

					$c['post_thumb'] = base_url($c['post_thumb']);
					
					$paths = $c['post_thumb'];
					$link_array = explode('/',$paths);
    				
            		$this->zip->add_data('assets/'.end($link_array),file_get_contents($paths));


					$ar [] = array(
						'post_id' => $c['post_id'],
						'post_thumb' =>$c['post_thumb'],
						'ratio' => $c['ratio']
					);
				}

				$rows[] = array(
					'cat_id' => $rwData['cat_id'],
					'cat_name' =>$rwData['cat_name'],
					'thumb_img' =>base_url($rwData['cat_thumb']),
					'poster_list'=>$ar
				);

			}


		}
		else if($_POST['cat_id'] > 0 && $_POST['ratiodata'] != 0)
		{
			$result = $this->db->where('cat_id',$_POST['cat_id'])->get('category')->result_array();

			$rows = array();

			foreach ($result as $rwData)
			{	
				$ar = array();
				$ct = $this->db->select('post_id,post_thumb,ratio')->where('cat_id',$rwData['cat_id'])->where('ratio',$_POST['ratiodata'])->get('poster')->result_array();

				foreach($ct as $c){ 

					$c['post_thumb'] = base_url($c['post_thumb']);
					
					$paths = $c['post_thumb'];
					 $link_array = explode('/',$paths);
    				
            		$this->zip->add_data('assets/'.end($link_array),file_get_contents($paths));

					$ar [] = array(
						'post_id' => $c['post_id'],
						'post_thumb' =>$c['post_thumb'],
						'ratio' => $c['ratio']
					);
				}

				$rows[] = array(
					'cat_id' => $rwData['cat_id'],
					'cat_name' =>$rwData['cat_name'],
					'thumb_img' =>base_url($rwData['cat_thumb']),
					'poster_list'=>$ar
				);

			}


		}
		else if($_POST['cat_id'] > 0 && $_POST['ratiodata'] == 0)
		{

			$result = $this->db->where('cat_id',$_POST['cat_id'])->get('category')->result_array();

			$rows = array();

			foreach ($result as $rwData)
			{	
				$ar = array();
				$ct = $this->db->select('post_id,post_thumb,ratio')->where('cat_id',$rwData['cat_id'])->get('poster')->result_array();

				foreach($ct as $c){ 

					$c['post_thumb'] = base_url($c['post_thumb']);
					
					$paths = $c['post_thumb'];
					$link_array = explode('/',$paths);
    				
            		$this->zip->add_data('assets/'.end($link_array),file_get_contents($paths));

					$ar [] = array(
						'post_id' => $c['post_id'],
						'post_thumb' =>$c['post_thumb'],
						'ratio' => $c['ratio']
					);
				}

				$rows[] = array(
					'cat_id' => $rwData['cat_id'],
					'cat_name' =>$rwData['cat_name'],
					'thumb_img' =>base_url($rwData['cat_thumb']),
					'poster_list'=>$ar
				);

			}

		}
		else if($_POST['cat_id'] == 0 && $_POST['ratiodata'] !=0)
		{

			$result = $this->Db_function->loaddata('category','nor');
			$rows = array();

			foreach ($result as $rwData)
			{	
				$ar = array();
				$ct = $this->db->select('post_id,post_thumb,ratio')->where('cat_id',$rwData['cat_id'])->where('ratio',$_POST['ratiodata'])->get('poster')->result_array();

				foreach($ct as $c){ 

					$c['post_thumb'] = base_url($c['post_thumb']);
					
					$paths = $c['post_thumb'];
					$link_array = explode('/',$paths);
    				
            		$this->zip->add_data('assets/'.end($link_array),file_get_contents($paths));

					
					$ar [] = array(
						'post_id' => $c['post_id'],
						'post_thumb' =>$c['post_thumb'],
						'ratio' => $c['ratio']
					);
				}

				$rows[] = array(
					'cat_id' => $rwData['cat_id'],
					'cat_name' =>$rwData['cat_name'],
					'thumb_img' =>base_url($rwData['cat_thumb']),
					'poster_list'=>$ar
				);

			}

		}

		$response['error'] = '1';
		$response['message'] = 'Loading Success!';
		$response['data'] = $rows;

		
		$fileName = 'data.json';

        $fileData = json_encode($response);

		$this->zip->add_data($fileName, $fileData);

		$this->zip->download('data.zip');	

    
	}
}

?>