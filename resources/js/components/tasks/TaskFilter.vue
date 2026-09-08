<script setup>
defineProps({
    searchKeyword: {
        type: String,
        required: true,
    },
    selectedStatus: {
        type: String,
        required: true,
    },
    selectedCategoryId: {
        type: [String, Number],
        required: true,
    },
    selectedPriority: {
        type: String,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
    totalCount: {
        type: Number,
        required: true,
    },
    filteredCount: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits([
    'update:searchKeyword',
    'update:selectedStatus',
    'update:selectedCategoryId',
    'update:selectedPriority',
    'clear',
]);
</script>

<template>
    <div class="filter-container">
        <div class="filter-area">
            <!-- キーワード検索 -->
            <div class="filter-group keyword-filter">
                <label for="search-keyword">
                    キーワード
                </label>
                <input
                    id="search-keyword"
                    :value="searchKeyword"
                    type="search"
                    placeholder="タイトル・詳細を検索"
                    @input="emit('update:searchKeyword', $event.target.value)"
                >
            </div>

            <!-- ステータス絞り込み -->
            <div class="filter-group">
                <label for="search-status">
                    ステータス
                </label>
                <select
                    id="search-status"
                    :value="selectedStatus"
                    @change="emit('update:selectedStatus', $event.target.value)"
                >
                    <option value="">すべてのステータス</option>
                    <option value="not_started">未着手</option>
                    <option value="in_progress">進行中</option>
                    <option value="completed">完了</option>
                </select>
            </div>

            <!-- プロジェクト（カテゴリ）絞り込み -->
            <div class="filter-group">
                <label for="search-category">
                    プロジェクト
                </label>
                <select
                    id="search-category"
                    :value="selectedCategoryId"
                    @change="emit('update:selectedCategoryId', $event.target.value)"
                >
                    <option value="">すべてのプロジェクト</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!-- 優先度絞り込み -->
            <div class="filter-group">
                <label for="search-priority">
                    優先度
                </label>
                <select
                    id="search-priority"
                    :value="selectedPriority"
                    @change="emit('update:selectedPriority', $event.target.value)"
                >
                    <option value="">すべての優先度</option>
                    <option value="high">高</option>
                    <option value="medium">中</option>
                    <option value="low">低</option>
                </select>
            </div>

            <!-- リセットボタン -->
            <div class="filter-action">
                <button
                    type="button"
                    class="clear-filter-button"
                    :disabled="!searchKeyword && !selectedStatus && !selectedCategoryId && !selectedPriority"
                    @click="emit('clear')"
                >
                    検索条件をクリア
                </button>
            </div>
        </div>

        <div class="filter-summary">
            <p class="filter-count">
                全 <strong>{{ totalCount }}</strong> 件中、
                <strong>{{ filteredCount }}</strong> 件を表示
            </p>
        </div>
    </div>
</template>

<style scoped>
.filter-container {
    margin-bottom: 24px;
}

.filter-area {
    display: flex;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 14px;
    padding: 18px;
    border: 1px solid #dbe3e8;
    border-radius: 10px;
    background-color: #f8fafc;
}

.filter-group {
    flex: 1;
    min-width: 160px;
}

.keyword-filter {
    flex: 2;
    min-width: 220px;
}

.filter-group label {
    display: block;
    margin-bottom: 6px;
    color: #334155;
    font-size: 14px;
    font-weight: 600;
}

.filter-group input,
.filter-group select {
    width: 100%;
    padding: 9px 12px;
    box-sizing: border-box;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    background-color: white;
    color: #1e293b;
    font-size: 14px;
    transition: border-color 0.2s;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
}

.filter-action {
    display: flex;
    align-items: flex-end;
}

.clear-filter-button {
    padding: 10px 16px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    background-color: #f1f5f9;
    color: #475569;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
}

.clear-filter-button:hover:not(:disabled) {
    background-color: #e2e8f0;
    color: #1e293b;
}

.clear-filter-button:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}

.filter-summary {
    margin-top: 8px;
    padding-left: 4px;
}

.filter-count {
    margin: 0;
    color: #64748b;
    font-size: 14px;
}

.filter-count strong {
    color: #0f172a;
}
</style>
