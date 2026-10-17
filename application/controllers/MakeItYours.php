<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MakeItYours extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Db_function');
		date_default_timezone_set('Asia/Kolkata');
        
        // Simple session check
        if(!$this->session->userdata('poster_sess_user_name')){
			redirect('admin');
		}

        // Auto Create Tables if they don't exist
        $this->auto_create_tables();
	}

    private function auto_create_tables()
    {
        // Create Categories Table
        $this->db->query("CREATE TABLE IF NOT EXISTS make_it_yours_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            sort_order INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");

        // Create Posters Table
        $this->db->query("CREATE TABLE IF NOT EXISTS make_it_yours_posters (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            poster_image VARCHAR(255) NOT NULL,
            thumbnail_image VARCHAR(255) DEFAULT NULL,
            is_published TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES make_it_yours_categories(id) ON DELETE CASCADE
        )");

        // Add Personalization Columns to Posters Table if they don't exist
        $fields = $this->db->list_fields('make_it_yours_posters');
        if (!in_array('personalization_enabled', $fields)) {
            $this->db->query("ALTER TABLE make_it_yours_posters ADD COLUMN personalization_enabled TINYINT(1) DEFAULT 0");
        }
        if (!in_array('slots', $fields)) {
            $this->db->query("ALTER TABLE make_it_yours_posters ADD COLUMN slots TEXT DEFAULT NULL");
        }
        if (!in_array('theme', $fields)) {
            $this->db->query("ALTER TABLE make_it_yours_posters ADD COLUMN theme TEXT DEFAULT NULL");
        }
        if (!in_array('ratio', $fields)) {
            $this->db->query("ALTER TABLE make_it_yours_posters ADD COLUMN ratio VARCHAR(50) DEFAULT '1:1'");
        }
        if (!in_array('design_id', $fields)) {
            $this->db->query("ALTER TABLE make_it_yours_posters ADD COLUMN design_id VARCHAR(100) DEFAULT NULL");
        }

        // Create Business Profiles Table
        $this->db->query("CREATE TABLE IF NOT EXISTS make_it_yours_business_profiles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_uid VARCHAR(255) NOT NULL UNIQUE,
            business_name VARCHAR(255) DEFAULT NULL,
            tagline VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(50) DEFAULT NULL,
            whatsapp VARCHAR(50) DEFAULT NULL,
            email VARCHAR(255) DEFAULT NULL,
            address TEXT DEFAULT NULL,
            website VARCHAR(255) DEFAULT NULL,
            instagram VARCHAR(255) DEFAULT NULL,
            facebook VARCHAR(255) DEFAULT NULL,
            logo_url VARCHAR(255) DEFAULT NULL,
            custom_text TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
    }

    // ==========================================
    // CATEGORIES
    // ==========================================
	public function categories()
	{
		$this->load->view('header');
		$this->load->view('sidebar');
		$data['categories'] = $this->db->order_by('sort_order', 'ASC')->get('make_it_yours_categories')->result_array();
		$this->load->view('make_it_yours/categories', $data);
		$this->load->view('footer');
	}

    public function add_category()
    {
        $this->load->view('header');
		$this->load->view('sidebar');
        
        $data = array();
        if($this->input->get('edit')){
            $data['edit_cat'] = $this->db->where('id', $this->input->get('edit'))->get('make_it_yours_categories')->row_array();
        }
        
		$this->load->view('make_it_yours/add_category', $data);
		$this->load->view('footer');
    }

    public function save_category()
    {
        $id = $this->input->post('id');
        $raw_name = $this->input->post('name');
        
        $sort_order = $this->input->post('sort_order') ? $this->input->post('sort_order') : 0;
        $is_active = $this->input->post('is_active') !== null ? 1 : 0;

        $targetpath = null;
        if(isset($_FILES['image']['name']) && !empty($_FILES['image']['name']))
        {
            $filename = $_FILES['image']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(in_array(strtolower($ext), array('jpg','jpeg','png')))
            {	
                $imagename = uniqid() . "_" . date("d-m-Y") . "__" . time() . "." . $ext;
                $targetpath = 'upload/thumb_image/' . $imagename;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetpath);
            }
        }

        if($id) {
            // Edit Single Category
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $raw_name)));
            $data = array(
                'name' => trim($raw_name),
                'slug' => $slug,
                'sort_order' => $sort_order,
                'is_active' => $is_active
            );
            if($targetpath) {
                $data['image'] = $targetpath;
            }
            $this->db->where('id', $id)->update('make_it_yours_categories', $data);
        } else {
            // Add Multiple Categories if comma-separated
            $names = explode(',', $raw_name);
            $current_order = $sort_order;
            foreach($names as $n) {
                $clean_name = trim($n);
                if(empty($clean_name)) continue;
                
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $clean_name)));
                
                $data = array(
                    'name' => $clean_name,
                    'slug' => $slug,
                    'sort_order' => $current_order,
                    'is_active' => $is_active
                );

                if($targetpath) {
                    $data['image'] = $targetpath;
                }

                $this->db->insert('make_it_yours_categories', $data);
                $current_order++; // Increment for the next category
            }
        }

        redirect('MakeItYours/categories');
    }

    public function delete_category($id)
    {
        // Posters will cascade delete due to foreign key
        $this->db->where('id', $id)->delete('make_it_yours_categories');
        redirect('MakeItYours/categories');
    }

    // ==========================================
    // POSTERS
    // ==========================================
    public function posters()
	{
		$this->load->view('header');
		$this->load->view('sidebar');
		
        $this->db->select('make_it_yours_posters.*, make_it_yours_categories.name as category_name');
        $this->db->join('make_it_yours_categories', 'make_it_yours_categories.id = make_it_yours_posters.category_id');
        $this->db->order_by('make_it_yours_posters.id', 'DESC');
        $data['posters'] = $this->db->get('make_it_yours_posters')->result_array();
        
		$this->load->view('make_it_yours/posters', $data);
		$this->load->view('footer');
	}

    public function add_poster()
    {
        $this->load->view('header');
		$this->load->view('sidebar');
        
        $data = array();
        $data['categories'] = $this->db->where('is_active', 1)->order_by('sort_order', 'ASC')->get('make_it_yours_categories')->result_array();
        
        if($this->input->get('edit')){
            $data['edit_poster'] = $this->db->where('id', $this->input->get('edit'))->get('make_it_yours_posters')->row_array();
        }
        
		$this->load->view('make_it_yours/add_poster', $data);
		$this->load->view('footer');
    }

    public function save_poster()
    {
        $id = $this->input->post('id');
        $category_input = $this->input->post('category_id');
        $title = $this->input->post('title');
        
        $submit_type = $this->input->post('submit_type');
        
        if($submit_type == 'publish' || $submit_type == 'update_publish') {
            $is_published = 1;
        } else if($submit_type == 'draft' || $submit_type == 'update_draft') {
            $is_published = 0;
        } else {
            // Fallback
            $is_published = 1;
        }
        $ratio = $this->input->post('ratio') ? $this->input->post('ratio') : '1:1';
        $design_id = $this->input->post('design_id');
        
        $personalization_enabled = $this->input->post('personalization_enabled') !== null ? 1 : 0;
        $slots = $this->input->post('slots');
        $theme = $this->input->post('theme');
        
        $personalization_mode = $this->input->post('personalization_mode');
        $bulk_slots_raw = $this->input->post('bulk_slots');
        $bulk_slots_array = array();
        if($personalization_mode == 'bulk' && !empty($bulk_slots_raw)) {
            $parsed = json_decode($bulk_slots_raw, true);
            if(is_array($parsed)) {
                $bulk_slots_array = $parsed;
            }
        }

        $thumbnail_image = null;
        if(isset($_FILES['thumbnail_image']['name']) && !empty($_FILES['thumbnail_image']['name']))
        {
            $filename = $_FILES['thumbnail_image']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(in_array(strtolower($ext), array('jpg','jpeg','png')))
            {	
                $imagename = uniqid() . "_thumb_" . date("d-m-Y") . "__" . time() . "." . $ext;
                $targetpath = 'upload/thumb_image/' . $imagename;
                if(move_uploaded_file($_FILES['thumbnail_image']['tmp_name'], $targetpath)){
                    $thumbnail_image = $targetpath;
                }
            }
        }

        if ($id) {
            // Edit Single Poster
            $poster_image = null;
            if(isset($_FILES['poster_image']['name'][0]) && !empty($_FILES['poster_image']['name'][0])) {
                $filename = $_FILES['poster_image']['name'][0];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if(in_array(strtolower($ext), array('jpg','jpeg','png')))
                {	
                    $imagename = uniqid() . "_poster_" . date("d-m-Y") . "__" . time() . "." . $ext;
                    $targetpath = 'upload/background/' . $imagename;
                    if(move_uploaded_file($_FILES['poster_image']['tmp_name'][0], $targetpath)){
                        $poster_image = $targetpath;
                    }
                }
            }

            $data = array(
                'category_id' => $category_input,
                'title' => $title,
                'is_published' => $is_published,
                'ratio' => $ratio,
                'design_id' => $design_id,
                'personalization_enabled' => $personalization_enabled,
                'slots' => $slots,
                'theme' => $theme
            );

            if($poster_image) {
                $data['poster_image'] = $poster_image;
            }
            if($thumbnail_image) {
                $data['thumbnail_image'] = $thumbnail_image;
            } else if($poster_image) {
                $data['thumbnail_image'] = $poster_image;
            }

            $this->db->where('id', $id)->update('make_it_yours_posters', $data);

        } else {
            // Add Multiple Posters
            $category_ids = is_array($category_input) ? $category_input : array($category_input);
            
            if(isset($_FILES['poster_image']['name']) && is_array($_FILES['poster_image']['name'])) {
                $count = count($_FILES['poster_image']['name']);
                for($i = 0; $i < $count; $i++) {
                    if(!empty($_FILES['poster_image']['name'][$i])) {
                        $filename = $_FILES['poster_image']['name'][$i];
                        $ext = pathinfo($filename, PATHINFO_EXTENSION);
                        if(in_array(strtolower($ext), array('jpg','jpeg','png')))
                        {	
                            $imagename = uniqid() . "_poster_" . date("d-m-Y") . "__" . time() . "." . $ext;
                            $targetpath = 'upload/background/' . $imagename;
                            if(move_uploaded_file($_FILES['poster_image']['tmp_name'][$i], $targetpath)){
                                
                                foreach($category_ids as $cat_id) {
                                    if(empty($cat_id)) continue;
                                    
                                    $current_slots = $slots;
                                    if($personalization_mode == 'bulk' && isset($bulk_slots_array[$i])) {
                                        $current_slots = json_encode($bulk_slots_array[$i]);
                                    }
                                    
                                    $data = array(
                                        'category_id' => $cat_id,
                                        'title' => $count > 1 ? $title . ' - ' . ($i + 1) : $title,
                                        'is_published' => $is_published,
                                        'poster_image' => $targetpath,
                                        'ratio' => $ratio,
                                        'design_id' => $design_id,
                                        'personalization_enabled' => $personalization_enabled,
                                        'slots' => $current_slots,
                                        'theme' => $theme
                                    );

                                    if($thumbnail_image) {
                                        $data['thumbnail_image'] = $thumbnail_image;
                                    } else {
                                        $data['thumbnail_image'] = $targetpath;
                                    }

                                    $this->db->insert('make_it_yours_posters', $data);
                                }
                            }
                        }
                    }
                }
            }
        }

        redirect('MakeItYours/posters');
    }
    
    public function delete_poster($id)
    {
        $this->db->where('id', $id)->delete('make_it_yours_posters');
        redirect('MakeItYours/posters');
    }
}
