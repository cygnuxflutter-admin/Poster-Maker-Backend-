<?php 
class MakeItYoursAPI extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Db_function');
		header('Content-Type:application/json');
	}

    private function verify_key()
    {
        if(isset($_POST['device']) && !empty($_POST['device']) && $_POST['device']=='1')
		{
			$key = $this->db->select('android_key')->where("key_id",'1')->get('tbl_key')->row()->android_key;
			if(isset($_POST['key']) && $_POST['key']==$key)
			{
                return true;
            }
        }
        return false;
    }

	public function categories()
	{
		if($this->verify_key())
		{
            $result = $this->db->where('is_active', 1)->order_by('sort_order', 'ASC')->get('make_it_yours_categories')->result_array();
            $rows = array();
            foreach ($result as $rwData) {
                $rows[] = array(
                    'id' => $rwData['id'],
                    'name' =>$rwData['name'],
                    'slug' =>$rwData['slug'],
                    'image' => !empty($rwData['image']) ? base_url($rwData['image']) : ''
                );
            }			

            $response['error'] = '1';
            $response['message'] = 'Loading Success!';
            $response['data'] = $rows;
		}
		else
		{
			$response['error'] = '0';
			$response['message'] = 'Loading Failed(key is not passed or invalid)!';
		}
		
		echo json_encode($response);
	}

    public function posters()
	{
		if($this->verify_key())
		{
            if(!isset($_POST['category_id']) || empty($_POST['category_id'])) {
                $response['error'] = '0';
                $response['message'] = 'Category ID is required!';
                echo json_encode($response);
                return;
            }

            $cat_id = $_POST['category_id'];
            
            $result = $this->db->where('category_id', $cat_id)
                               ->where('is_published', 1)
                               ->order_by('id', 'DESC')
                               ->get('make_it_yours_posters')
                               ->result_array();
                               
            $rows = array();
            foreach ($result as $rwData) {
                // Parse JSON slots safely
                $slotsData = !empty($rwData['slots']) ? json_decode($rwData['slots'], true) : array();
                
                // Remove extra fields if they exist
                if(is_array($slotsData)) {
                    unset($slotsData['email']);
                    unset($slotsData['website']);
                } else {
                    $slotsData = new stdClass();
                }
                
                $rows[] = array(
                    'id' => $rwData['id'],
                    'title' =>$rwData['title'],
                    'ratio' => isset($rwData['ratio']) ? $rwData['ratio'] : '1:1',
                    'design_id' => isset($rwData['design_id']) ? $rwData['design_id'] : '',
                    'poster_image' => !empty($rwData['poster_image']) ? base_url($rwData['poster_image']) : '',
                    'thumbnail_image' => !empty($rwData['thumbnail_image']) ? base_url($rwData['thumbnail_image']) : '',
                    'personalization' => array(
                        'enabled' => $rwData['personalization_enabled'] == 1,
                        'theme' => !empty($rwData['theme']) ? $rwData['theme'] : '',
                        'slots' => $slotsData
                    )
                );
            }			

            $response['error'] = '1';
            $response['message'] = 'Loading Success!';
            $response['data'] = $rows;
		}
		else
		{
			$response['error'] = '0';
			$response['message'] = 'Loading Failed(key is not passed or invalid)!';
		}
		
		echo json_encode($response);
	}

    // ==========================================
    // BUSINESS PROFILE
    // ==========================================
    
    public function get_business_profile()
    {
        if($this->verify_key())
        {
            if(!isset($_POST['user_uid']) || empty($_POST['user_uid'])) {
                $response['error'] = '0';
                $response['message'] = 'User UID is required!';
                echo json_encode($response);
                return;
            }

            $user_uid = $_POST['user_uid'];
            
            $profile = $this->db->where('user_uid', $user_uid)->get('make_it_yours_business_profiles')->row_array();
                               
            if($profile) {
                if(!empty($profile['logo_url']) && !filter_var($profile['logo_url'], FILTER_VALIDATE_URL)) {
                    $profile['logo_url'] = base_url($profile['logo_url']);
                }
                $response['error'] = '1';
                $response['message'] = 'Profile found';
                $response['data'] = $profile;
            } else {
                $response['error'] = '2'; // Not found but not an error
                $response['message'] = 'No business profile found for this user';
                $response['data'] = null;
            }
        }
        else
        {
            $response['error'] = '0';
            $response['message'] = 'Loading Failed(key is not passed or invalid)!';
        }
        
        echo json_encode($response);
    }

    public function save_business_profile()
    {
        if($this->verify_key())
        {
            if(!isset($_POST['user_uid']) || empty($_POST['user_uid'])) {
                $response['error'] = '0';
                $response['message'] = 'User UID is required!';
                echo json_encode($response);
                return;
            }

            $user_uid = $_POST['user_uid'];
            
            $data = array(
                'business_name' => $this->input->post('business_name'),
                'tagline' => $this->input->post('tagline'),
                'phone' => $this->input->post('phone'),
                'whatsapp' => $this->input->post('whatsapp'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'website' => $this->input->post('website'),
                'instagram' => $this->input->post('instagram'),
                'facebook' => $this->input->post('facebook'),
                'custom_text' => $this->input->post('custom_text')
            );

            // Filter out null values so we don't overwrite with empty if they didn't send a field
            foreach($data as $key => $val) {
                if($val === null) {
                    unset($data[$key]);
                }
            }

            // Handle logo upload
            if(isset($_FILES['logo']['name']) && !empty($_FILES['logo']['name']))
            {
                $filename = $_FILES['logo']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if(in_array(strtolower($ext), array('jpg','jpeg','png')))
                {	
                    $imagename = uniqid() . "_logo_" . time() . "." . $ext;
                    $targetpath = 'upload/logo/' . $imagename;
                    
                    // Create dir if not exists
                    if (!is_dir('upload/logo/')) {
                        mkdir('upload/logo/', 0777, true);
                    }

                    if(move_uploaded_file($_FILES['logo']['tmp_name'], $targetpath)){
                        $data['logo_url'] = $targetpath;
                    }
                }
            }

            $existing = $this->db->where('user_uid', $user_uid)->get('make_it_yours_business_profiles')->row_array();

            if($existing) {
                $this->db->where('user_uid', $user_uid)->update('make_it_yours_business_profiles', $data);
                $response['message'] = 'Profile updated successfully!';
            } else {
                $data['user_uid'] = $user_uid;
                $this->db->insert('make_it_yours_business_profiles', $data);
                $response['message'] = 'Profile created successfully!';
            }

            $response['error'] = '1';
        }
        else
        {
            $response['error'] = '0';
            $response['message'] = 'Loading Failed(key is not passed or invalid)!';
        }
        
        echo json_encode($response);
    }
}
?>
