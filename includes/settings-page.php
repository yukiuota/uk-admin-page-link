<?php
if (!defined('ABSPATH')) exit;

// 管理画面用CSS/JSの読み込み
add_action('admin_enqueue_scripts', 'ukapl_admin_enqueue_scripts');
function ukapl_admin_enqueue_scripts($hook) {
    if ($hook !== 'settings_page_ukapl-settings') {
        return;
    }
    
    wp_enqueue_style(
        'ukapl-admin-style',
        plugin_dir_url(__FILE__) . 'assets/admin-style.css',
        array(),
        '1.0.1'
    );
    
    wp_enqueue_script(
        'ukapl-admin-script',
        plugin_dir_url(__FILE__) . 'assets/admin-script.js',
        array('jquery'),
        '1.0.1',
        true
    );
}

// 設定画面の追加
add_action('admin_menu', 'ukapl_add_settings_page');
function ukapl_add_settings_page() {
    add_options_page(
        __('メニューリンク設定', 'uk-admin-page-link'),
        __('メニューリンク設定', 'uk-admin-page-link'),
        'manage_options',
        'ukapl-settings',
        'ukapl_render_settings_page'
    );
}

// 設定画面の表示
function ukapl_render_settings_page() {
    $dashicons = [
        'dashicons-admin-site', 'dashicons-admin-media', 'dashicons-admin-page', 'dashicons-admin-comments',
        'dashicons-admin-appearance', 'dashicons-admin-plugins', 'dashicons-admin-users', 'dashicons-admin-tools',
        'dashicons-admin-settings', 'dashicons-admin-network', 'dashicons-admin-home', 'dashicons-admin-generic',
        'dashicons-admin-collapse', 'dashicons-welcome-write-blog', 'dashicons-welcome-add-page',
        'dashicons-welcome-view-site', 'dashicons-welcome-widgets-menus', 'dashicons-format-image',
        'dashicons-format-gallery', 'dashicons-format-video', 'dashicons-format-audio',
    ];
    // 保存処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('manage_options')) {
        // nonceチェック
        if (!isset($_POST['ukapl_nonce']) || !wp_verify_nonce($_POST['ukapl_nonce'], 'ukapl_save_settings')) {
            wp_die(__('セキュリティチェックに失敗しました。', 'uk-admin-page-link'));
        }
        
        $labels = isset($_POST['ukapl_label']) ? array_map('sanitize_text_field', $_POST['ukapl_label']) : [];
        $page_ids = isset($_POST['ukapl_page_id']) ? array_map('intval', $_POST['ukapl_page_id']) : [];
        $icons = isset($_POST['ukapl_icon']) ? array_map('sanitize_text_field', $_POST['ukapl_icon']) : [];
        $positions = isset($_POST['ukapl_position']) ? array_map('floatval', $_POST['ukapl_position']) : [];
        $links = [];
        $count = max(count($labels), count($page_ids), count($icons), count($positions));
        for ($i = 0; $i < $count; $i++) {
            if (!empty($labels[$i]) && !empty($page_ids[$i])) {
                $links[] = [
                    'label' => $labels[$i],
                    'page_id' => $page_ids[$i],
                    'icon' => !empty($icons[$i]) ? $icons[$i] : '',
                    'position' => isset($positions[$i]) ? $positions[$i] : '',
                ];
            }
        }
        update_option('ukapl_links', $links);
        echo '<div class="updated notice"><p>' . __('保存しました。', 'uk-admin-page-link') . '</p></div>';
    }
    $saved_links = get_option('ukapl_links', []);
    $pages = get_pages();
    echo '<div class="wrap"><h1>' . __('メニューリンク設定', 'uk-admin-page-link') . '</h1>';
    echo '<form method="post" action="" id="ukapl-form">';
    wp_nonce_field('ukapl_save_settings', 'ukapl_nonce');
    echo '<div id="ukapl-rows">';
    if (!empty($saved_links)) {
        foreach ($saved_links as $link) {
            $icon_val = isset($link['icon']) ? $link['icon'] : '';
            $is_custom_icon = $icon_val && !in_array($icon_val, $dashicons);
            $preview_icon = $is_custom_icon ? $icon_val : ($icon_val ?: 'dashicons-admin-page');
            echo '<div class="ukapl-row">';
            echo '<label>' . __('表示名', 'uk-admin-page-link') . '<br><input type="text" name="ukapl_label[]" value="' . esc_attr($link['label']) . '" /></label>';
            echo '<label>' . __('固定ページ', 'uk-admin-page-link') . '<br><select name="ukapl_page_id[]">';
            foreach ($pages as $page) {
                $selected = ($page->ID == $link['page_id']) ? 'selected' : '';
                echo '<option value="' . esc_attr($page->ID) . '" ' . $selected . '>' . esc_html($page->post_title) . '</option>';
            }
            echo '</select></label>';
            echo '<label>' . __('アイコン', 'uk-admin-page-link') . '<br>';
            echo '<span style="display:flex;align-items:center;gap:8px;">';
            echo '<select class="ukapl-icon-select" name="ukapl_icon[]">';
            foreach ($dashicons as $dicon) {
                $selected = ($icon_val === $dicon) ? 'selected' : '';
                echo '<option value="' . esc_attr($dicon) . '" data-icon="' . esc_attr($dicon) . '" ' . $selected . '>' . $dicon . '</option>';
            }
            $selected = $is_custom_icon ? 'selected' : '';
            echo '<option value="custom" ' . $selected . '>' . __('その他（手入力）', 'uk-admin-page-link') . '</option>';
            echo '</select>';
            $custom_style = $is_custom_icon ? '' : 'style="display:none"';
            echo '<input type="text" class="ukapl-icon-custom" name="ukapl_icon_custom[]" value="' . ($is_custom_icon ? esc_attr($icon_val) : '') . '" placeholder="dashicons-xxx" ' . $custom_style . ' />';
            echo '<span class="dashicons ' . esc_attr($preview_icon) . ' ukapl-icon-preview"></span>';
            echo '</span>';
            echo '</label>';
            echo '<label>' . __('表示位置', 'uk-admin-page-link') . '<br><input type="number" step="0.1" name="ukapl_position[]" value="' . esc_attr(isset($link['position']) ? $link['position'] : '') . '" placeholder="' . __('例: 60', 'uk-admin-page-link') . '" /></label>';
            echo '<button type="button" class="ukapl-remove-row button">' . __('削除', 'uk-admin-page-link') . '</button>';
            echo '</div><hr class="ukapl-hr">';
        }
    } else {
        echo '<div class="ukapl-row">';
        echo '<label>' . __('表示名', 'uk-admin-page-link') . '<br><input type="text" name="ukapl_label[]" /></label>';
        echo '<label>' . __('固定ページ', 'uk-admin-page-link') . '<br><select name="ukapl_page_id[]">';
        foreach ($pages as $page) {
            echo '<option value="' . esc_attr($page->ID) . '">' . esc_html($page->post_title) . '</option>';
        }
        echo '</select></label>';
        echo '<label>' . __('アイコン', 'uk-admin-page-link') . '<br>';
        echo '<span style="display:flex;align-items:center;gap:8px;">';
        echo '<select class="ukapl-icon-select" name="ukapl_icon[]">';
        foreach ($dashicons as $dicon) {
            echo '<option value="' . esc_attr($dicon) . '" data-icon="' . esc_attr($dicon) . '">' . $dicon . '</option>';
        }
        echo '<option value="custom">' . __('その他（手入力）', 'uk-admin-page-link') . '</option>';
        echo '</select>';
        echo '<input type="text" class="ukapl-icon-custom" name="ukapl_icon_custom[]" placeholder="dashicons-xxx" style="display:none" />';
        echo '<span class="dashicons dashicons-admin-page ukapl-icon-preview"></span>';
        echo '</span>';
        echo '</label>';
        echo '<label>' . __('表示位置', 'uk-admin-page-link') . '<br><input type="number" step="0.1" name="ukapl_position[]" placeholder="' . __('例: 60', 'uk-admin-page-link') . '" /></label>';
        echo '<button type="button" class="ukapl-remove-row button">' . __('削除', 'uk-admin-page-link') . '</button>';
        echo '</div><hr class="ukapl-hr">';
    }
    echo '</div>';
    echo '<p><button type="button" id="ukapl-add-row" class="button">' . __('＋行を追加', 'uk-admin-page-link') . '</button></p>';
    echo '<p><input type="submit" class="button-primary" value="' . __('保存', 'uk-admin-page-link') . '"></p>';
    echo '</form>';
    echo '</div>';
}

// 保存時、セレクトがcustomなら手入力値を保存
add_filter('pre_update_option_ukapl_links', function($value, $old_value) {
    if (!isset($_POST['ukapl_icon']) || !isset($_POST['ukapl_icon_custom'])) return $value;
    foreach ($value as $i => &$row) {
        if (isset($_POST['ukapl_icon'][$i]) && $_POST['ukapl_icon'][$i] === 'custom') {
            $row['icon'] = sanitize_text_field($_POST['ukapl_icon_custom'][$i]);
        }
    }
    return $value;
}, 10, 2);
