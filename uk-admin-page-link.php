<?php
/**
 * Plugin Name: UK Admin Page Link
 * Description: 固定ページ編集画面へのリンクを管理画面メニューに追加できるプラグイン
 * Version: 1.0.1
 * Author: Y.U.
 * Text Domain: uk-admin-page-link
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.0
 * Tested up to: 6.6
 * Requires PHP: 7.4
 * Network: false
 */

if (!defined('ABSPATH')) exit;

define('UKAPL_PLUGIN_DIR', plugin_dir_path(__FILE__));

define('UKAPL_PLUGIN_URL', plugin_dir_url(__FILE__));

// プラグインの有効化時
register_activation_hook(__FILE__, 'ukapl_activate');
function ukapl_activate() {
    // デフォルト設定は特になし
    // 必要に応じて初期データを設定
}

// プラグインの無効化時
register_deactivation_hook(__FILE__, 'ukapl_deactivate');
function ukapl_deactivate() {
    // 設定は残しておく（アンインストール時に削除）
}

// 国際化のセットアップ
add_action('plugins_loaded', 'ukapl_load_textdomain');
function ukapl_load_textdomain() {
    load_plugin_textdomain('uk-admin-page-link', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

// 設定画面・保存・UI
require_once UKAPL_PLUGIN_DIR . 'includes/settings-page.php';
// メインメニューへのリンク追加
require_once UKAPL_PLUGIN_DIR . 'includes/menu-links.php';