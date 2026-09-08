<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-md text-white p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <span class="inline-block px-3 py-1 bg-white/20 text-xs font-semibold rounded-full uppercase tracking-wider mb-2">Portfolio Project</span>
                        <h3 class="text-2xl font-bold mb-2">TaskFlow - タスク管理システム</h3>
                        <p class="text-blue-100 text-sm max-w-xl">
                            Laravel 12 API と Vue.js 3 を連携したSPAライクなタスク管理システムです。ステータス管理、プロジェクト別分類、リアルタイム検索・絞り込み、進捗率ダッシュボード機能を備えています。
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-blue-700 font-bold rounded-lg shadow hover:bg-blue-50 transition transform hover:-translate-y-0.5">
                            TaskFlow を開く →
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 text-lg mb-2">📝 メモ管理</h4>
                    <p class="text-gray-600 text-sm mb-4">Bladeテンプレートを用いたクラシックなCRUDとカテゴリ別メモ一覧です。</p>
                    <a href="{{ route('memos.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">メモ一覧へ移動 →</a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 text-lg mb-2">⚡ Vue メモ管理 (API連携)</h4>
                    <p class="text-gray-600 text-sm mb-4">Vue 3 と Axios で非同期通信を行うメモ管理コンポーネントです。</p>
                    <a href="{{ route('vue.memos') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Vueメモへ移動 →</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
