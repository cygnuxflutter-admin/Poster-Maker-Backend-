<?php 
class Poster2 extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Db_function');
		header('Content-Type:application/json');
	}

	public function category()
	{
		if(isset($_POST['device']) && !empty($_POST['device']) && $_POST['device']=='1')
		{
			$key = $this->db->select('android_key')->where("key_id",'1')->get('tbl_key')->row()->android_key;
			if(isset($_POST['key']) && $_POST['key']==$key)
			{

				$result = $this->Db_function->loaddata('category','nor');
				$rows = array();
				foreach ($result as $rwData) {
					$rows[] = array(
						'cat_id' => $rwData['cat_id'],
						'cat_name' =>$rwData['cat_name'],
						'thumb_img' =>base_url($rwData['cat_thumb']),
					);
				}

				$response['error'] = '1';
				$response['message'] = 'Loading Success!';
				$response['data'] = $rows;

				
			}
			else
			{
				$response['error'] = '0';
				$response['message'] = 'Loading Failed(key is not passed)!';
				
			}
		}
		else{

			$response['error'] = '404';
			$response['message'] = 'Device Parameter missing';
		}
		echo json_encode($response);
	}

	public function swiperCat()
	{
		if(isset($_POST['device']) && !empty($_POST['device']) && $_POST['device']=='1')
		{
			$key = $this->db->select('android_key')->where("key_id",'1')->get('tbl_key')->row()->android_key;
			if(isset($_POST['key']) && $_POST['key']==$key)
			{

				if(isset($_POST['cat_id']) && $_POST['cat_id']=='0' && isset($_POST['ratio']) && $_POST['ratio']=='0')
				{

					$result = $this->db->order_by('CAST(sequence_number AS UNSIGNED)', 'ASC')->get('category')->result_array();
					$rows = array();

					foreach ($result as $rwData)
					{	
						$ar = array();
						$ct = $this->db->select('post_id,post_thumb,ratio')->where('cat_id',$rwData['cat_id'])->order_by('rand()')->get('poster')->result_array();

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
					$result = $this->db->where('cat_id',$_POST['cat_id'])->order_by('rand()')->get('category')->result_array();

					$rows = array();

					foreach ($result as $rwData)
					{	
						$ar = array();
						$ct = $this->db->select('post_id,post_thumb,ratio')->where('cat_id',$rwData['cat_id'])->where('ratio',$_POST['ratio'])->order_by('rand()')->get('poster')->result_array();

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

					$result = $this->db->where('cat_id',$_POST['cat_id'])->order_by('rand()')->get('category')->result_array();

					$rows = array();

					foreach ($result as $rwData)
					{	
						$ar = array();
						$ct = $this->db->select('post_id,post_thumb,ratio')->where('cat_id',$rwData['cat_id'])->order_by('rand()')->get('poster')->result_array();

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
						$ct = $this->db->select('post_id,post_thumb,ratio')->where('cat_id',$rwData['cat_id'])->where('ratio',$_POST['ratio'])->order_by('rand()')->get('poster')->result_array();

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
				else
				{
					$rows = "Invalid Parameter set!";
				}

				$response['error'] = '1';
				$response['message'] = 'Loading Success!';
				$response['data'] = $rows;

			}
			else
			{
				$response['error'] = '0';
				$response['message'] = 'Loading Failed(key is not passed)!';
			}

		}
		else{

			$response['error'] = '404';
			$response['message'] = 'Device Parameter missing';
		}
		echo json_encode($response);

		
	}


	public function swiperBG()
	{
		if(isset($_POST['device']) && !empty($_POST['device']) && $_POST['device']=='1')
		{
			$key = $this->db->select('android_key')->where("key_id",'1')->get('tbl_key')->row()->android_key;
			if(isset($_POST['key']) && $_POST['key']==$key)
			{

				/*if(isset($_POST['cat_id']) && !empty($_POST['cat_id']))
				{*/

					$result = $this->db->order_by('rand()')->get('background_category')->result_array();
					$rows = array();

					foreach ($result as $rwData)
					{	
						$ar = array();
						$ct = $this->db->select('bg_id,bg_thumb,background')->where('cat_id',$rwData['bg_cat_id'])->order_by('rand()')->get('poster_BG')->result_array();

						foreach($ct as $c){ 

							$c['bg_thumb'] = base_url($c['bg_thumb']);
							$c['background'] = base_url($c['background']);
							$ar [] = array(
								'id' => $c['bg_id'],
								'thumb_url' =>$c['bg_thumb'],
								'image_url' =>$c['background'],
							);
						}

						$rows[] = array(
							'category_id' => $rwData['bg_cat_id'],
							'category_name' =>$rwData['name'],
							'category_list'=>$ar
						);

					}
					$response['thumbnail_bg'] = $rows;
				}
				else
				{
					$response['error'] = '0';
					$response['message'] = 'Loading Failed(key is not passed)!';
				}

			}
			else{

				$response['error'] = '404';
				$response['message'] = 'Device Parameter missing';
			}
			echo json_encode($response);


		}



		public function poster()
		{
			if(isset($_POST['device']) && !empty($_POST['device']) && $_POST['device']=='1')
			{
				$key = $this->db->select('android_key')->where("key_id",'1')->get('tbl_key')->row()->android_key;
				if(isset($_POST['key']) && $_POST['key']==$key)
				{
					if(isset($_POST['post_id']) && !empty($_POST['post_id']))
					{
						$result = $this->db->where('cat_id',$_POST['cat_id'])->where('post_id',$_POST['post_id'])->get('poster')->result_array();

					// For popular ...
						$catView = $this->db->select('views')->where('cat_id',$_POST['cat_id'])->get('category')->row()->views;

						$catView = $catView + 1;
						$catData = array(
							'views'=>$catView,
							'updated_date'=>date("Y-m-d")
						);
						$this->db->set($catData)->where('cat_id',$_POST['cat_id'])->update('category');

						$view = $this->db->select('views')->where('post_id',$_POST['post_id'])->get('poster')->row()->views;
						$view = $view + 1;
						$data = array(
							'views'=>$view,
							'updated_date'=>date("Y-m-d")
						);
						$this->db->set($data)->where('post_id',$_POST['post_id'])->update('poster');

					// End of Popular Programming...

						$rows = array();
						foreach($result as $rwData) 
						{
							$strwdata = array();
							$txtrwdata= array();
							$stinfo = json_decode($rwData['sticker_info']);
							$txtinfo = json_decode($rwData['text_info']);
							foreach($stinfo as $stdata)
							{
								$strwdata[]=$stdata;
								unset($rwData['sticker_info']);
							}
							foreach($txtinfo as $txtdata)
							{
								$txtrwdata[]=$txtdata;
								unset($rwData['text_info']);
							}
							$rows[] = array(
								'post_id' => $rwData['post_id'],
								'post_thumb' =>base_url($rwData['post_thumb']),
								'back_image' =>base_url($rwData['back_image']),
								'ratio'		 =>$rwData['ratio'],
								'cat_id'	 =>$rwData['cat_id'],
								'sticker_info'=>$strwdata,
								'text_info'=>$txtrwdata,
							);
						}

						$response['error'] = '1';
						$response['message'] = 'Loading Success!';
						$response['data'] = $rows;
					}
					else
					{
						$result = $this->db->where('cat_id',$_POST['cat_id'])->get('poster')->result_array();
						$rows = array();
						foreach($result as $rwData) 
						{
							$strwdata = array();
							$txtrwdata= array();
							$stinfo = json_decode($rwData['sticker_info']);
							$txtinfo = json_decode($rwData['text_info']);
							foreach($stinfo as $stdata){
								$strwdata[]=$stdata;
								unset($rwData['sticker_info']);
							}
							foreach($txtinfo as $txtdata){
								$txtrwdata[]=$txtdata;
								unset($rwData['text_info']);
							}

							$rows[] = array(
								'post_id' => $rwData['post_id'],
								'post_thumb' =>base_url($rwData['post_thumb']),
								'back_image' =>base_url($rwData['back_image']),
								'ratio'		 =>$rwData['ratio'],
								'cat_id'	 =>$rwData['cat_id'],
								'sticker_info'=>$strwdata,
								'text_info'=>$txtrwdata,
							);
						}

						$response['error'] = '1';
						$response['message'] = 'Loading Success!';
						$response['data'] = $rows;
					}
				}
				else
				{
					$response['error'] = '0';
					$response['message'] = 'Access denied without Key!';
				}
			}
			elseif(isset($_POST['device']) && !empty($_POST['device']) && $_POST['device']=='2')
			{
					$response['msg'] = 'iOS';
			}
			else
			{
				$response['error'] = '404';
				$response['message'] = 'Device Parameter missing';
			}
			echo json_encode($response);
		}
	}
	?>