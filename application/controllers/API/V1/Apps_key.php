<?php 
class Apps_key extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Db_function');
		header('Content-Type:application/json');
	}

	// public function index(){
	// 	if(isset($_POST['device']) && !empty($_POST['device']))
	// 	{
	// 		if($_POST['device']==1){
	// 			$key = $this->db->select('android_key')->where("key_id",'1')->get('tbl_key')->row()->android_key;
	// 			$response['key'] = $key;
	// 		}
			
	// 	}
	// 	else
	// 	{
	// 		$response['key'] ="";
	// 	}
		
	// 	$new_data = base64_encode(json_encode($response));

	// 	echo base64_encode($new_data);
	// }
}
?>