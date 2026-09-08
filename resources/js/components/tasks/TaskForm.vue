<script setup>
const props = defineProps({
    categories: {
        type: Array,
        required: true,
    },

    form: {
        type: Object,
        required: true,
    },

    formErrors: {
        type: Object,
        required: true,
    },

    isSubmitting: {
        type: Boolean,
        default: false,
    },

    successMessage: {
        type: String,
        default: '',
    },

    isEditing: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits([
    'update:form',
    'submit',
    'cancel',
]);

const updateField = (field, value) => {
    emit('update:form', {
        ...props.form,
        [field]: value,
    });
};
</script>

<template>
    <section class="task-form-section">
        <h2>
            {{ isEditing ? 'タスク編集' : 'タスク新規登録' }}
        </h2>

        <p
            v-if="successMessage"
            class="success-message"
        >
            {{ successMessage }}
        </p>

        <form @submit.prevent="emit('submit')">
            <div class="form-group">
                <label for="task-category">
                    プロジェクト
                </label>

                <select
                    id="task-category"
                    :value="form.category_id"
                    @change="
                        updateField(
                            'category_id',
                            $event.target.value
                        )
                    "
                >
                    <option value="">
                        プロジェクトを選択してください
                    </option>

                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>

                <p
                    v-if="formErrors.category_id"
                    class="field-error"
                >
                    {{ formErrors.category_id[0] }}
                </p>
            </div>

            <div class="form-group">
                <label for="task-title">
                    タイトル
                </label>

                <input
                    id="task-title"
                    :value="form.title"
                    type="text"
                    placeholder="タスクのタイトル"
                    @input="
                        updateField(
                            'title',
                            $event.target.value
                        )
                    "
                >

                <p
                    v-if="formErrors.title"
                    class="field-error"
                >
                    {{ formErrors.title[0] }}
                </p>
            </div>

            <div class="form-group">
                <label for="task-description">
                    詳細
                </label>

                <textarea
                    id="task-description"
                    :value="form.description"
                    placeholder="タスクの詳細を入力してください"
                    @input="
                        updateField(
                            'description',
                            $event.target.value
                        )
                    "
                ></textarea>

                <p
                    v-if="formErrors.description"
                    class="field-error"
                >
                    {{ formErrors.description[0] }}
                </p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="task-status">
                        ステータス
                    </label>

                    <select
                        id="task-status"
                        :value="form.status"
                        @change="
                            updateField(
                                'status',
                                $event.target.value
                            )
                        "
                    >
                        <option value="not_started">
                            未着手
                        </option>

                        <option value="in_progress">
                            進行中
                        </option>

                        <option value="completed">
                            完了
                        </option>
                    </select>

                    <p
                        v-if="formErrors.status"
                        class="field-error"
                    >
                        {{ formErrors.status[0] }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="task-priority">
                        優先度
                    </label>

                    <select
                        id="task-priority"
                        :value="form.priority"
                        @change="
                            updateField(
                                'priority',
                                $event.target.value
                            )
                        "
                    >
                        <option value="low">
                            低
                        </option>

                        <option value="medium">
                            中
                        </option>

                        <option value="high">
                            高
                        </option>
                    </select>

                    <p
                        v-if="formErrors.priority"
                        class="field-error"
                    >
                        {{ formErrors.priority[0] }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="task-due-date">
                        期限
                    </label>

                    <input
                        id="task-due-date"
                        :value="form.due_date"
                        type="date"
                        @input="
                            updateField(
                                'due_date',
                                $event.target.value
                            )
                        "
                    >

                    <p
                        v-if="formErrors.due_date"
                        class="field-error"
                    >
                        {{ formErrors.due_date[0] }}
                    </p>
                </div>
            </div>
            <div class="form-actions">
                <button
                    type="submit"
                    class="submit-button"
                    :disabled="isSubmitting"
                >
                    {{
                        isSubmitting
                            ? (isEditing ? '更新中...' : '登録中...')
                            : (isEditing ? '更新する' : '登録する')
                    }}
                </button>
                <button
                    v-if="isEditing"
                    type="button"
                    class="cancel-button"
                    :disabled="isSubmitting"
                    @click="emit('cancel')"
                >
                    編集をキャンセル
                </button>
            </div>
        </form>
    </section>
</template>

<style scoped>
.task-form-section {
    max-width: 1000px;
    margin: 30px auto;
    padding: 24px;
    border: 1px solid #dbe3e8;
    border-radius: 10px;
    background-color: white;
    color: #263238;
    box-sizing: border-box;
}

.task-form-section h2 {
    margin-top: 0;
}

.form-group {
    flex: 1;
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    border: 1px solid #aeb6bf;
    border-radius: 7px;
    background-color: white;
    font-size: 16px;
}

.form-group textarea {
    min-height: 120px;
    resize: vertical;
}

.form-row {
    display: flex;
    gap: 16px;
}

.submit-button {
    padding: 10px 20px;
    border: none;
    border-radius: 7px;
    background-color: #2563eb;
    color: white;
    font-size: 15px;
    cursor: pointer;
}

.submit-button:hover:not(:disabled) {
    background-color: #1d4ed8;
}

.submit-button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

.success-message {
    padding: 12px;
    border-radius: 7px;
    background-color: #eafaf1;
    color: #27864f;
}

.field-error {
    margin: 6px 0 0;
    color: #c0392b;
    font-size: 14px;
}

@media (max-width: 720px) {
    .form-row {
        display: block;
    }
}

.form-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cancel-button {
    padding: 10px 20px;
    border: 1px solid #94a3b8;
    border-radius: 7px;
    background-color: white;
    color: #475569;
    font-size: 15px;
    cursor: pointer;
}

.cancel-button:hover:not(:disabled) {
    background-color: #f1f5f9;
}

.cancel-button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}
</style>