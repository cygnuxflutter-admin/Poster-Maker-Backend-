<?php
/**
 * RTO Notification Bank Inspector (CLI Tool)
 * Run in terminal: php check_bank_diversity.php
 */
require 'index.php';
$ci = get_instance();
$rows = $ci->db->select('id, category_name, target_screen, status, title, body')
               ->limit(20)
               ->get('rto_push_notification_bank')
               ->result_array();

echo "\n=======================================================\n";
echo "   FIRST 20 NOTIFICATIONS IN LIVE DATABASE\n";
echo "=======================================================\n\n";
foreach ($rows as $r) {
    echo "#{$r['id']} [{$r['category_name']} | {$r['target_screen']}] ({$r['status']})\n";
    echo "  Title: {$r['title']}\n";
    echo "  Body:  {$r['body']}\n\n";
}
