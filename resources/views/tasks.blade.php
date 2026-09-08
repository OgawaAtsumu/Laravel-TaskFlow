<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>TaskFlow - タスク管理システム</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white border-b border-gray-200 px-6 py-3 flex justify-between items-center text-sm shadow-sm">
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                <span>←</span> ダッシュボードへ戻る
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('memos.index') }}" class="text-gray-600 hover:text-gray-900">メモ管理</a>
            <a href="{{ route('vue.memos') }}" class="text-gray-600 hover:text-gray-900">Vue メモ</a>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-gray-600 font-medium">{{ Auth::user()->name }} さん</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-red-600 text-xs px-2.5 py-1 rounded border border-gray-300 hover:border-red-400 transition">
                    ログアウト
                </button>
            </form>
        </div>
    </nav>

    <div id="vue-task-app"></div>
</body>
</html>