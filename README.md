# TaskFlow - タスク管理システム

Laravel 12 (API) と Vue.js 3 を連携したSPAライクなタスク管理ポートフォリオアプリケーションです。  
プロジェクトごとのタスク管理、ステータス遷移、リアルタイムな検索・絞り込み、進捗率を可視化するダッシュボード機能を備えています。

---

## 主な機能一覧

### 1. 認証機能（Laravel Breeze）
- ユーザー新規登録、ログイン、ログアウト
- パスワードリセット・プロフィール編集
- ユーザーごとのデータ完全分離

### 2. タスク管理（Vue.js × Laravel REST API）
- **CRUD機能**: タスクの新規登録、一覧表示、編集、削除（削除前確認付き）
- **ステータス管理**: 「未着手」「進行中」「完了」の3段階ステータス
- **優先度設定**: 「高」「中」「低」の3段階優先度
- **期限・期限切れ警告**: 期限日設定、期限超過時の視覚的アラート表示
- **完了日時記録**: ステータス完了時に自動で完了日時をタイムスタンプ記録

### 3. プロジェクト管理（カテゴリ機能）
- タスクをプロジェクト（仕事・学習・プライベート等）ごとに分類・整理

### 4. リアルタイム検索・絞り込み（Vue.js リアクティブ機能）
- **キーワード検索**: タイトル・詳細テキストのインクリメンタル検索
- **ステータス絞り込み**: 未着手／進行中／完了のフィルタリング
- **プロジェクト絞り込み**: プロジェクトごとのフィルタリング
- **優先度絞り込み**: 高／中／低のフィルタリング
- **条件クリア**: 検索条件の一括リセット
- **件数表示**: 「全○件中、○件を表示」

### 5. ダッシュボード統計（リアルタイム集計）
- 総タスク数（Total Tasks）
- 完了タスク数（Completed Tasks）
- 未完了タスク数（Incomplete Tasks）
- 進捗率・完了率（Completion Rate: パーセンテージ ＆ プログレスバー表示）

---

## 使用技術・環境

### バックエンド
- PHP 8.2+
- Laravel 12
- Laravel Breeze（認証）
- Laravel Sanctum（API認証）
- Eloquent ORM / FormRequest / API Resource
- MySQL

### フロントエンド
- Vue.js 3（Composition API / `<script setup>`）
- Axios（非同期通信）
- Tailwind CSS 3 / 4
- Vite 6

### 開発・インフラ環境
- Windows 11 / XAMPP
- Git / GitHub

---

## 画面構成・ルーティング

- `/`: トップページ（ログイン・登録・アプリへの導線）
- `/dashboard`: ダッシュボード（アプリ概要・各機能へのクイックリンク）
- `/tasks`: **TaskFlow メイン画面（Vue.js SPA）**
- `/memos`: クラシックメモ一覧（Blade + Server-side CRUD）
- `/vue-memos`: Vue メモ管理（API連携サンプル）
- `/profile`: ユーザープロフィール編集

---

## セットアップ手順

```bash
# リポジトリのクローン
git clone <repository-url>
cd Sample

# 依存パッケージのインストール
composer install
npm install

# 環境変数の設定
cp .env.example .env
php artisan key:generate

# データベースマイグレーション
php artisan migrate

# アセットのビルド
npm run build

# ローカルサーバー起動
php artisan serve
```