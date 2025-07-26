<?php
/*
Plugin Name: UK Admin Page Link
Description: 固定ページ編集画面へのリンクを管理画面メニューに追加できるプラグイン
Version: 1.0.0
Author: Y.U.
*/

if (!defined('ABSPATH')) exit;

define('UKAPL_PLUGIN_DIR', plugin_dir_path(__FILE__));

define('UKAPL_PLUGIN_URL', plugin_dir_url(__FILE__));

// 設定画面・保存・UI
require_once UKAPL_PLUGIN_DIR . 'includes/settings-page.php';
// メインメニューへのリンク追加
require_once UKAPL_PLUGIN_DIR . 'includes/menu-links.php';