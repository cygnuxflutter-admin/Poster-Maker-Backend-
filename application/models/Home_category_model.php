<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_category_model extends CI_Model {

    const TABLE = 'home_categories';
    const UPLOAD_DIR = 'upload/home_categories/';

    /**
     * Allowed action_type values and their Admin UI display metadata.
     */
    public static function get_action_types() {
        return array(
            'template_category' => array(
                'label' => 'Template Category',
                'badge_bg' => '#eef2ff',
                'badge_color' => '#4338ca',
                'badge_border' => '#c7d2fe',
                'icon' => 'fa-layer-group'
            ),
            'background_category' => array(
                'label' => 'Background Category',
                'badge_bg' => '#ecfeff',
                'badge_color' => '#0e7490',
                'badge_border' => '#a5f3fc',
                'icon' => 'fa-image'
            ),
            'all_templates' => array(
                'label' => 'All Templates',
                'badge_bg' => '#eff6ff',
                'badge_color' => '#1d4ed8',
                'badge_border' => '#bfdbfe',
                'icon' => 'fa-table-cells-large'
            ),
            'all_backgrounds' => array(
                'label' => 'All Backgrounds',
                'badge_bg' => '#f0fdf4',
                'badge_color' => '#15803d',
                'badge_border' => '#bbf7d0',
                'icon' => 'fa-images'
            ),
            'create_custom' => array(
                'label' => 'Create Custom',
                'badge_bg' => '#fffbeb',
                'badge_color' => '#b45309',
                'badge_border' => '#fde68a',
                'icon' => 'fa-wand-magic-sparkles'
            ),
            'my_creations' => array(
                'label' => 'My Creations',
                'badge_bg' => '#f5f3ff',
                'badge_color' => '#6d28d9',
                'badge_border' => '#ddd6fe',
                'icon' => 'fa-folder-open'
            ),
            'url' => array(
                'label' => 'External URL',
                'badge_bg' => '#fdf2f8',
                'badge_color' => '#be185d',
                'badge_border' => '#fbcfe8',
                'icon' => 'fa-arrow-up-right-from-square'
            )
        );
    }

    /**
     * Default seed rows for immediate out-of-the-box API & CMS availability.
     */
    public static function get_default_seeds($app_id = 2) {
        $app_id = (int)$app_id;
        return array(
            array(
                'app_id' => $app_id,
                'name' => 'Festival',
                'icon_url' => null,
                'bg_color' => '#FFFBEB',
                'border_color' => '#FDE68A',
                'action_type' => 'template_category',
                'cat_id' => '55',
                'cat_name' => 'Diwali',
                'action_url' => '',
                'sort_order' => 1,
                'is_active' => 1
            ),
            array(
                'app_id' => $app_id,
                'name' => 'Wedding',
                'icon_url' => null,
                'bg_color' => '#FFF1F2',
                'border_color' => '#FECDD3',
                'action_type' => 'template_category',
                'cat_id' => '18',
                'cat_name' => 'Wedding Anniversary',
                'action_url' => '',
                'sort_order' => 2,
                'is_active' => 1
            ),
            array(
                'app_id' => $app_id,
                'name' => 'Sale',
                'icon_url' => null,
                'bg_color' => '#FEF2F2',
                'border_color' => '#FECACA',
                'action_type' => 'template_category',
                'cat_id' => '10',
                'cat_name' => 'Sales',
                'action_url' => '',
                'sort_order' => 3,
                'is_active' => 1
            ),
            array(
                'app_id' => $app_id,
                'name' => 'Business',
                'icon_url' => null,
                'bg_color' => '#EFF6FF',
                'border_color' => '#BFDBFE',
                'action_type' => 'template_category',
                'cat_id' => '11',
                'cat_name' => 'Business & marketing',
                'action_url' => '',
                'sort_order' => 4,
                'is_active' => 1
            ),
            array(
                'app_id' => $app_id,
                'name' => 'Birthday',
                'icon_url' => null,
                'bg_color' => '#F5F3FF',
                'border_color' => '#DDD6FE',
                'action_type' => 'template_category',
                'cat_id' => '47',
                'cat_name' => 'Birthday',
                'action_url' => '',
                'sort_order' => 5,
                'is_active' => 1
            ),
            array(
                'app_id' => $app_id,
                'name' => 'Food',
                'icon_url' => null,
                'bg_color' => '#ECFDF5',
                'border_color' => '#A7F3D0',
                'action_type' => 'template_category',
                'cat_id' => '19',
                'cat_name' => 'Food',
                'action_url' => '',
                'sort_order' => 6,
                'is_active' => 1
            ),
            array(
                'app_id' => $app_id,
                'name' => 'Education',
                'icon_url' => null,
                'bg_color' => '#ECFEFF',
                'border_color' => '#A5F3FC',
                'action_type' => 'template_category',
                'cat_id' => '27',
                'cat_name' => 'Educationss',
                'action_url' => '',
                'sort_order' => 7,
                'is_active' => 1
            ),
            array(
                'app_id' => $app_id,
                'name' => 'Party',
                'icon_url' => null,
                'bg_color' => '#FDF2F8',
                'border_color' => '#FBCFE8',
                'action_type' => 'template_category',
                'cat_id' => '20',
                'cat_name' => 'Party',
                'action_url' => '',
                'sort_order' => 8,
                'is_active' => 1
            )
        );
    }

    /**
     * Ensures the home_categories table exists and seeds defaults if newly created.
     */
    public function ensure_table_and_seed() {
        $existed = $this->db->table_exists(self::TABLE);
        if (!$existed) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . self::TABLE . "` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `app_id` INT(11) NOT NULL DEFAULT 2,
                `name` VARCHAR(100) NOT NULL,
                `icon_url` VARCHAR(500) DEFAULT NULL,
                `bg_color` VARCHAR(20) DEFAULT NULL,
                `border_color` VARCHAR(20) DEFAULT NULL,
                `action_type` VARCHAR(50) NOT NULL DEFAULT 'template_category',
                `cat_id` VARCHAR(50) DEFAULT NULL,
                `cat_name` VARCHAR(100) DEFAULT NULL,
                `action_url` VARCHAR(500) DEFAULT NULL,
                `sort_order` INT(11) NOT NULL DEFAULT 0,
                `is_active` TINYINT(1) NOT NULL DEFAULT 1,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_home_cat_app_active_sort` (`app_id`, `is_active`, `sort_order`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $this->db->query($sql);
        }

        if ((int)$this->db->count_all(self::TABLE) === 0 && !$existed) {
            $this->seed_defaults(2);
        }
    }

    /**
     * Seeds default rows for a given app_id if that app_id currently has no rows.
     */
    public function seed_defaults($app_id = 2) {
        $app_id = (int)$app_id;
        $existing = $this->db->where('app_id', $app_id)->count_all_results(self::TABLE);
        if ($existing > 0) {
            return false;
        }
        $rows = self::get_default_seeds($app_id);
        return $this->db->insert_batch(self::TABLE, $rows);
    }

    /**
     * Returns active rows for the mobile API ordered by sort_order ASC, id ASC.
     */
    public function get_active_for_api($app_id = 2) {
        $this->ensure_table_and_seed();
        return $this->db
            ->where('is_active', 1)
            ->where('app_id', (int)$app_id)
            ->order_by('sort_order', 'ASC')
            ->order_by('id', 'ASC')
            ->get(self::TABLE)
            ->result_array();
    }

    /**
     * Returns all rows for the Admin CMS for the given app_id.
     */
    public function get_by_app($app_id = 2) {
        $this->ensure_table_and_seed();
        return $this->db
            ->where('app_id', (int)$app_id)
            ->order_by('sort_order', 'ASC')
            ->order_by('id', 'ASC')
            ->get(self::TABLE)
            ->result_array();
    }

    /**
     * Normalizes & sanitizes hex color values (returns null if blank/invalid).
     */
    public function normalize_hex_color($color) {
        $color = trim((string)$color);
        if ($color === '') {
            return null;
        }
        if ($color[0] !== '#') {
            $color = '#' . $color;
        }
        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3}|[A-Fa-f0-9]{8})$/', $color)) {
            return strtoupper($color);
        }
        return null;
    }

    /**
     * Handles icon upload to upload/home_categories/ and returns relative path or null.
     */
    public function handle_icon_upload($field_name = 'icon_file') {
        if (!isset($_FILES[$field_name]['tmp_name']) || empty($_FILES[$field_name]['tmp_name'])) {
            return null;
        }
        $filename = $_FILES[$field_name]['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = array('png', 'webp', 'jpg', 'jpeg', 'gif', 'svg');
        if (!in_array($ext, $allowed, true)) {
            return null;
        }

        $upload_dir = FCPATH . self::UPLOAD_DIR;
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }
        $imagename = uniqid() . '_home_cat_' . date('d-m-Y') . '__' . time() . '.' . $ext;
        $move_path = $upload_dir . $imagename;
        if (move_uploaded_file($_FILES[$field_name]['tmp_name'], $move_path)) {
            return self::UPLOAD_DIR . $imagename;
        }
        return null;
    }

    /**
     * Resolves an icon_url column to an absolute URL or empty string.
     */
    public static function resolve_icon_url($icon_url) {
        $icon_url = trim((string)$icon_url);
        if ($icon_url === '') {
            return '';
        }
        if (preg_match('/^https?:\/\//i', $icon_url)) {
            return $icon_url;
        }
        return base_url(ltrim($icon_url, '/'));
    }

    /**
     * Validates and builds a clean record array from Admin POST input.
     */
    public function build_payload_from_post($post, $is_update = false) {
        $allowed_actions = array_keys(self::get_action_types());
        $action_type = isset($post['action_type']) ? trim($post['action_type']) : 'template_category';
        if (!in_array($action_type, $allowed_actions, true)) {
            $action_type = 'template_category';
        }

        $cat_id = null;
        $cat_name = null;
        $action_url = '';

        if ($action_type === 'template_category' || $action_type === 'background_category') {
            $cat_id = isset($post['cat_id']) && trim($post['cat_id']) !== '' ? trim($post['cat_id']) : null;
            $cat_name = isset($post['cat_name']) && trim($post['cat_name']) !== '' ? trim($post['cat_name']) : null;

            // Server-side fallback to look up cat_name if cat_id is selected and cat_name wasn't sent
            if ($cat_id !== null && $cat_name === null) {
                if ($action_type === 'template_category') {
                    $row = $this->db->select('cat_name')->where('cat_id', $cat_id)->get('category')->row();
                    if ($row && !empty($row->cat_name)) {
                        $cat_name = $row->cat_name;
                    }
                } else {
                    $row = $this->db->select('name')->where('bg_cat_id', $cat_id)->get('background_category')->row();
                    if ($row && !empty($row->name)) {
                        $cat_name = $row->name;
                    }
                }
            }
        } elseif ($action_type === 'url') {
            $action_url = isset($post['action_url']) ? trim($post['action_url']) : '';
        }

        $data = array(
            'app_id' => isset($post['app_id']) && (int)$post['app_id'] > 0 ? (int)$post['app_id'] : 2,
            'name' => isset($post['name']) ? trim($post['name']) : '',
            'bg_color' => $this->normalize_hex_color(isset($post['bg_color']) ? $post['bg_color'] : ''),
            'border_color' => $this->normalize_hex_color(isset($post['border_color']) ? $post['border_color'] : ''),
            'action_type' => $action_type,
            'cat_id' => $cat_id,
            'cat_name' => $cat_name,
            'action_url' => $action_url,
            'sort_order' => isset($post['sort_order']) ? (int)$post['sort_order'] : 0,
            'is_active' => isset($post['is_active']) && (string)$post['is_active'] !== '0' ? 1 : 0
        );

        $uploaded = $this->handle_icon_upload('icon_file');
        if ($uploaded !== null) {
            $data['icon_url'] = $uploaded;
        } elseif (!$is_update && isset($post['icon_url']) && trim($post['icon_url']) !== '') {
            $data['icon_url'] = trim($post['icon_url']);
        }

        return $data;
    }

    public function insert_item($data) {
        $this->ensure_table_and_seed();
        return $this->db->insert(self::TABLE, $data);
    }

    public function update_item($id, $data) {
        $this->ensure_table_and_seed();
        return $this->db->where('id', (int)$id)->update(self::TABLE, $data);
    }

    public function delete_item($id) {
        $this->ensure_table_and_seed();
        $row = $this->db->where('id', (int)$id)->get(self::TABLE)->row_array();
        if ($row && !empty($row['icon_url']) && strpos($row['icon_url'], self::UPLOAD_DIR) === 0) {
            $full_path = FCPATH . $row['icon_url'];
            if (is_file($full_path)) {
                @unlink($full_path);
            }
        }
        return $this->db->where('id', (int)$id)->delete(self::TABLE);
    }

    public function toggle_active($id) {
        $this->ensure_table_and_seed();
        $row = $this->db->select('id, is_active')->where('id', (int)$id)->get(self::TABLE)->row_array();
        if (!$row) {
            return null;
        }
        $next = ((int)$row['is_active'] === 1) ? 0 : 1;
        $this->db->where('id', (int)$id)->update(self::TABLE, array('is_active' => $next));
        return $next;
    }

    public function update_sort_order($positions) {
        $this->ensure_table_and_seed();
        if (!is_array($positions)) {
            return false;
        }
        $order = 1;
        foreach ($positions as $id) {
            $this->db->where('id', (int)$id)->set('sort_order', $order)->update(self::TABLE);
            $order++;
        }
        return true;
    }
}
