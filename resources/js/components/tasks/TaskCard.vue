<script setup>
defineProps({
    task: {
        type: Object,
        required: true,
    },
});

defineEmits([
    'edit',
    'delete',
]);
</script>

<template>
    <article
        class="task-card"
        :class="{
            overdue: task.is_overdue,
            completed: task.status === 'completed',
        }"
    >
        <div class="task-labels">
            <span
                class="priority-label"
                :class="`priority-${task.priority}`"
            >
                {{ task.priority_label }}
            </span>

            <span
                class="status-label"
                :class="`status-${task.status}`"
            >
                {{ task.status_label }}
            </span>

            <span class="category-label">
                {{ task.category?.name ?? '未設定' }}
            </span>
        </div>

        <h2>{{ task.title }}</h2>

        <p class="task-description">
            {{ task.description || '詳細はありません。' }}
        </p>

        <div class="task-dates">
            <p>
                期限：
                {{ task.due_date ?? '設定なし' }}
            </p>

            <p v-if="task.completed_at">
                完了日時：{{ task.completed_at }}
            </p>
        </div>

        <p
            v-if="task.is_overdue"
            class="overdue-message"
        >
            期限を過ぎています。
        </p>

        <div class="task-actions">
            <button
                type="button"
                class="edit-button"
                @click="$emit('edit', task)"
            >
                編集
            </button>

            <button
                type="button"
                class="delete-button"
                @click="$emit('delete', task)"
            >
                削除
            </button>
        </div>
    </article>
</template>

<style scoped>
.task-card {
    padding: 20px;
    border: 1px solid #dbe3e8;
    border-radius: 10px;
    background-color: white;
    box-shadow: 0 3px 12px rgb(0 0 0 / 6%);
}

.task-card.overdue {
    border-color: #e74c3c;
    background-color: #fff8f7;
}

.task-card.completed {
    border-color: #a7d7b7;
    background-color: #f6fff8;
}

.task-card.completed h2,
.task-card.completed .task-description {
    color: #7f8c8d;
}

.task-card h2 {
    margin: 15px 0 10px;
    font-size: 20px;
}

.task-labels {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.priority-label,
.status-label,
.category-label {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: bold;
}

.priority-low {
    background-color: #eafaf1;
    color: #27864f;
}

.priority-medium {
    background-color: #fff3cd;
    color: #856404;
}

.priority-high {
    background-color: #fdecea;
    color: #c0392b;
}

.status-not_started {
    background-color: #edf2f7;
    color: #4a5568;
}

.status-in_progress {
    background-color: #eaf2fb;
    color: #2471a3;
}

.status-completed {
    background-color: #eafaf1;
    color: #27864f;
}

.category-label {
    background-color: #f3e8ff;
    color: #7e22ce;
}

.task-description {
    min-height: 48px;
    color: #475467;
    white-space: pre-wrap;
}

.task-dates {
    margin-top: 16px;
    color: #667085;
    font-size: 14px;
}

.task-dates p {
    margin: 5px 0;
}

.overdue-message {
    margin: 15px 0 0;
    padding: 8px 10px;
    border-radius: 6px;
    background-color: #fdecea;
    color: #c0392b;
    font-weight: bold;
}

.task-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

.edit-button,
.delete-button {
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    color: white;
    font-size: 14px;
    cursor: pointer;
}

.edit-button {
    background-color: #2563eb;
}

.edit-button:hover {
    background-color: #1d4ed8;
}

.delete-button {
    background-color: #dc2626;
}

.delete-button:hover {
    background-color: #b91c1c;
}

</style>