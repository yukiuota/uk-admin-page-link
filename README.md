# 管理画面ページリンク追加（uk-admin-page-link） v1.0.1

WordPress管理画面のメインメニューに、任意の固定ページ編集画面へのリンクを自由に追加できるプラグインです。

## 機能概要
- 管理画面メニューに、好きな名前・アイコン・順序で固定ページ編集画面へのリンクを追加
- 複数のリンクを自由に設定・削除可能
- Dashiconsアイコンをセレクト＋プレビューで直感的に選択
- 表示位置やアイコンも個別に指定可能
- WordPress Plugin Handbook準拠のセキュリティ対策・国際化対応

## インストール方法
1. このリポジトリをダウンロードし、`uk-admin-page-link`フォルダごと`wp-content/plugins/`にアップロード
2. WordPress管理画面「プラグイン」から「UK Admin Page Link」を有効化

## 使い方
1. 管理画面「設定」→「メニューリンク設定」へアクセス
2. 「表示名」「固定ページ」「アイコン」「表示位置」を入力し、必要なだけ行を追加
3. 「保存」ボタンで反映
4. 管理画面メニューに設定したリンクが追加され、クリックで該当ページの編集画面へ遷移
5. 不要な行は「削除」ボタンで消去可能

### アイコンについて
- Dashiconsの代表的なアイコンをセレクトボックスから選択可能
- 「その他（手入力）」を選ぶと、任意のDashiconsクラス名を直接入力できます
- [Dashicons一覧はこちら](https://developer.wordpress.org/resource/dashicons/)

### 表示位置について
- 数値が小さいほど上に表示されます（例: 60, 65, 70 など）
- 他プラグインと重複しない値を推奨

## ディレクトリ構成
```
uk-admin-page-link/
├── uk-admin-page-link.php         # メインプラグインファイル
├── uninstall.php                  # アンインストール時のクリーンアップ
├── includes/
│   ├── settings-page.php         # 設定画面・保存・UI
│   ├── menu-links.php            # メニュー追加ロジック
│   └── assets/
│       ├── admin-style.css       # 管理画面用CSS
│       └── admin-script.js       # 管理画面用JavaScript
├── languages/                     # 多言語対応ファイル（将来用）
└── README.md
```

## セキュリティ機能
- CSRFプロテクション（nonceフィールド）
- データサニタイゼーション・エスケープ処理
- 権限チェック（`edit_pages`権限が必要）
- 直接ファイルアクセス防止

## 技術仕様
- **WordPress要件**: 5.0以上
- **PHP要件**: 7.4以上
- **テスト済み**: WordPress 6.6
- **Text Domain**: uk-admin-page-link
- **ライセンス**: GPL v2 or later

## 更新履歴
### v1.0.1 (2025年7月30日)
- WordPress Plugin Handbook準拠の改善
- セキュリティ強化（nonceによるCSRF対策）
- 国際化対応（翻訳可能テキスト）
- CSS/JavaScript外部ファイル化
- データ検証強化
- アンインストール時のクリーンアップ処理追加

### v1.0.0
- 初回リリース
- 基本機能実装

## カスタマイズ・拡張
- `includes/assets/`配下のCSS/JSをカスタマイズ可能
- 固定ページ以外の投稿タイプや外部URLへのリンク追加も拡張可能
- 多言語化ファイル（.po/.mo）を`languages/`ディレクトリに配置可能

## ライセンス
GPL v2 or later - WordPress公式ライセンスと互換