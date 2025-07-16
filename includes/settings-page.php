<?php
if (!defined('ABSPATH')) exit;

// 設定画面の追加
add_action('admin_menu', 'ukapl_add_settings_page');
function ukapl_add_settings_page() {
    add_options_page(
        'メニューリンク設定',
        'メニューリンク設定',
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
        echo '<div class="updated notice"><p>保存しました。</p></div>';
    }
    $saved_links = get_option('ukapl_links', []);
    $pages = get_pages();
    echo '<div class="wrap"><h1>メニューリンク設定</h1>';
    echo '<form method="post" action="" id="ukapl-form">';
    echo '<div id="ukapl-rows">';
    if (!empty($saved_links)) {
        foreach ($saved_links as $link) {
            $icon_val = isset($link['icon']) ? $link['icon'] : '';
            $is_custom_icon = $icon_val && !in_array($icon_val, $dashicons);
            $preview_icon = $is_custom_icon ? $icon_val : ($icon_val ?: 'dashicons-admin-page');
            echo '<div class="ukapl-row">';
            echo '<label>表示名<br><input type="text" name="ukapl_label[]" value="' . esc_attr($link['label']) . '" /></label>';
            echo '<label>固定ページ<br><select name="ukapl_page_id[]">';
            foreach ($pages as $page) {
                $selected = ($page->ID == $link['page_id']) ? 'selected' : '';
                echo '<option value="' . esc_attr($page->ID) . '" ' . $selected . '>' . esc_html($page->post_title) . '</option>';
            }
            echo '</select></label>';
            echo '<label>アイコン<br>';
            echo '<span style="display:flex;align-items:center;gap:8px;">';
            echo '<select class="ukapl-icon-select" name="ukapl_icon[]">';
            foreach ($dashicons as $dicon) {
                $selected = ($icon_val === $dicon) ? 'selected' : '';
                echo '<option value="' . esc_attr($dicon) . '" data-icon="' . esc_attr($dicon) . '" ' . $selected . '>' . $dicon . '</option>';
            }
            $selected = $is_custom_icon ? 'selected' : '';
            echo '<option value="custom" ' . $selected . '>その他（手入力）</option>';
            echo '</select>';
            $custom_style = $is_custom_icon ? '' : 'style="display:none"';
            echo '<input type="text" class="ukapl-icon-custom" name="ukapl_icon_custom[]" value="' . ($is_custom_icon ? esc_attr($icon_val) : '') . '" placeholder="dashicons-xxx" ' . $custom_style . ' />';
            echo '<span class="dashicons ' . esc_attr($preview_icon) . ' ukapl-icon-preview" style="font-size:24px;vertical-align:middle;"></span>';
            echo '</span>';
            echo '</label>';
            echo '<label>表示位置<br><input type="number" step="0.1" name="ukapl_position[]" value="' . esc_attr(isset($link['position']) ? $link['position'] : '') . '" placeholder="例: 60" /></label>';
            echo '<button type="button" class="ukapl-remove-row button">削除</button>';
            echo '</div><hr class="ukapl-hr">';
        }
    } else {
        echo '<div class="ukapl-row">';
        echo '<label>表示名<br><input type="text" name="ukapl_label[]" /></label>';
        echo '<label>固定ページ<br><select name="ukapl_page_id[]">';
        foreach ($pages as $page) {
            echo '<option value="' . esc_attr($page->ID) . '">' . esc_html($page->post_title) . '</option>';
        }
        echo '</select></label>';
        echo '<label>アイコン<br>';
        echo '<span style="display:flex;align-items:center;gap:8px;">';
        echo '<select class="ukapl-icon-select" name="ukapl_icon[]">';
        foreach ($dashicons as $dicon) {
            echo '<option value="' . esc_attr($dicon) . '" data-icon="' . esc_attr($dicon) . '">' . $dicon . '</option>';
        }
        echo '<option value="custom">その他（手入力）</option>';
        echo '</select>';
        echo '<input type="text" class="ukapl-icon-custom" name="ukapl_icon_custom[]" placeholder="dashicons-xxx" style="display:none" />';
        echo '<span class="dashicons dashicons-admin-page ukapl-icon-preview" style="font-size:24px;vertical-align:middle;"></span>';
        echo '</span>';
        echo '</label>';
        echo '<label>表示位置<br><input type="number" step="0.1" name="ukapl_position[]" placeholder="例: 60" /></label>';
        echo '<button type="button" class="ukapl-remove-row button">削除</button>';
        echo '</div><hr class="ukapl-hr">';
    }
    echo '</div>';
    echo '<p><button type="button" id="ukapl-add-row" class="button">＋行を追加</button></p>';
    echo '<p><input type="submit" class="button-primary" value="保存"></p>';
    echo '</form>';
    echo '</div>';
    // CSSとJS
    echo '<style>
    .ukapl-row { display: flex; gap: 16px; align-items: flex-end; margin-bottom: 8px; }
    .ukapl-row label { display: flex; flex-direction: column; min-width: 140px; }
    .ukapl-hr { border: none; border-top: 1px solid #ccc; margin: 8px 0; }
    .ukapl-icon-select option { padding-left:28px; position:relative; }
    .ukapl-remove-row { margin-left: 8px; height: 32px; }
    </style>';
    echo '<script>
    function updateIconPreview(select) {
        var row = select.closest(".ukapl-row");
        var customInput = row.querySelector(".ukapl-icon-custom");
        var preview = row.querySelector(".ukapl-icon-preview");
        var val = select.value;
        if(val === "custom") {
            customInput.style.display = "inline-block";
            preview.className = "dashicons " + (customInput.value || "dashicons-admin-page") + " ukapl-icon-preview";
        } else {
            customInput.style.display = "none";
            preview.className = "dashicons " + val + " ukapl-icon-preview";
        }
    }
    document.querySelectorAll(".ukapl-icon-select").forEach(function(select){
        select.addEventListener("change", function(){ updateIconPreview(this); });
        updateIconPreview(select);
    });
    document.querySelectorAll(".ukapl-icon-custom").forEach(function(input){
        input.addEventListener("input", function(){
            var row = input.closest(".ukapl-row");
            var preview = row.querySelector(".ukapl-icon-preview");
            preview.className = "dashicons " + (input.value || "dashicons-admin-page") + " ukapl-icon-preview";
        });
    });
    document.getElementById("ukapl-add-row").onclick = function() {
        var rows = document.querySelectorAll("#ukapl-rows .ukapl-row");
        var lastRow = rows[rows.length-1];
        var newRow = lastRow.cloneNode(true);
        // input/selectの値をリセット
        newRow.querySelectorAll("input, select").forEach(function(el){
            if(el.tagName === "SELECT") el.selectedIndex = 0;
            else el.value = "";
        });
        newRow.querySelector(".ukapl-icon-custom").style.display = "none";
        newRow.querySelector(".ukapl-icon-preview").className = "dashicons dashicons-admin-page ukapl-icon-preview";
        // 新しいselect/inputにもイベント付与
        newRow.querySelector(".ukapl-icon-select").addEventListener("change", function(){ updateIconPreview(this); });
        newRow.querySelector(".ukapl-icon-custom").addEventListener("input", function(){
            var row = this.closest(".ukapl-row");
            var preview = row.querySelector(".ukapl-icon-preview");
            preview.className = "dashicons " + (this.value || "dashicons-admin-page") + " ukapl-icon-preview";
        });
        // 区切り線も追加
        var hr = document.createElement("hr");
        hr.className = "ukapl-hr";
        document.getElementById("ukapl-rows").appendChild(newRow);
        document.getElementById("ukapl-rows").appendChild(hr);
    };
    // 削除ボタン
    document.addEventListener("click", function(e) {
        if(e.target.classList.contains("ukapl-remove-row")) {
            var row = e.target.closest(".ukapl-row");
            var hr = row.nextElementSibling;
            if(document.querySelectorAll("#ukapl-rows .ukapl-row").length > 1) {
                row.remove();
                if(hr && hr.classList.contains("ukapl-hr")) hr.remove();
            } else {
                // 最後の1行はクリアのみ
                row.querySelectorAll("input, select").forEach(function(el){
                    if(el.tagName === "SELECT") el.selectedIndex = 0;
                    else el.value = "";
                });
                row.querySelector(".ukapl-icon-custom").style.display = "none";
                row.querySelector(".ukapl-icon-preview").className = "dashicons dashicons-admin-page ukapl-icon-preview";
            }
        }
    });
    </script>';
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
