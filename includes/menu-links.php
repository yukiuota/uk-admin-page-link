<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'ukapl_add_custom_menu_links');
function ukapl_add_custom_menu_links() {
    if (!current_user_can('edit_pages')) return;
    $links = get_option('ukapl_links', []);
    foreach ($links as $link) {
        if (!empty($link['label']) && !empty($link['page_id'])) {
            $edit_url = admin_url('post.php?post=' . intval($link['page_id']) . '&action=edit');
            $icon = !empty($link['icon']) ? $link['icon'] : 'dashicons-admin-page';
            $position = isset($link['position']) && $link['position'] !== '' ? floatval($link['position']) : null;
            add_menu_page(
                esc_html($link['label']),
                esc_html($link['label']),
                'edit_pages',
                $edit_url,
                '',
                $icon,
                $position
            );
        }
    }
}
