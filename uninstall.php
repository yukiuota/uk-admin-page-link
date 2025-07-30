<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// 保存されているオプションを削除
delete_option('ukapl_links');

// マルチサイトの場合も対応
if (is_multisite()) {
    global $wpdb;
    $sites = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}");
    foreach ($sites as $site) {
        switch_to_blog($site->blog_id);
        delete_option('ukapl_links');
        restore_current_blog();
    }
}
