<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import TaskCard from './TaskCard.vue';
import TaskForm from './TaskForm.vue';

const tasks = ref([]);
const isLoading = ref(false);
const errorMessage = ref('');

const categories = ref([]);

const form = ref({
    category_id: '',
    title: '',
    description: '',
    status: 'not_started',
    priority: 'medium',
    due_date: '',
});

const formErrors = ref({});
const isSubmitting = ref(false);
const successMessage = ref('');

const fetchTasks = async () => {
    isLoading.value = true;
    errorMessage.value = '';

    try {
        const response = await axios.get('/api/tasks');

        tasks.value = response.data.data;
    } catch (error) {
        console.error(error);

        if (error.response?.status === 401) {
            errorMessage.value = 'ログインが必要です。';
        } else {
            errorMessage.value = 'タスクの取得に失敗しました。';
        }
    } finally {
        isLoading.value = false;
    }
};

const fetchCategories = async () => {
    try {
        const response = await axios.get('/api/categories');

        categories.value = response.data.data;
    } catch (error) {
        console.error(error);

        errorMessage.value =
            'カテゴリの取得に失敗しました。';
    }
};

const updateForm = (updatedForm) => {
    form.value = updatedForm;
};

const resetForm = () => {
    form.value = {
        category_id: '',
        title: '',
        description: '',
        status: 'not_started',
        priority: 'medium',
        due_date: '',
    };

    formErrors.value = {};
};

const createTask = async () => {
    isSubmitting.value = true;
    formErrors.value = {};
    successMessage.value = '';
    errorMessage.value = '';

    try {
        await axios.post('/api/tasks', {
            category_id: form.value.category_id,
            title: form.value.title,
            description:
                form.value.description || null,
            status: form.value.status,
            priority: form.value.priority,
            due_date:
                form.value.due_date || null,
        });

        successMessage.value =
            'タスクを登録しました。';

        resetForm();

        await fetchTasks();
    } catch (error) {
        console.error(error);

        if (error.response?.status === 422) {
            formErrors.value =
                error.response.data.errors;
        } else if (error.response?.status === 401) {
            errorMessage.value =
                'ログインが必要です。';
        } else {
            errorMessage.value =
                'タスクの登録に失敗しました。';
        }
    } finally {
        isSubmitting.value = false;
    }
};

const deleteTask = async (task) => {
    const confirmed = window.confirm(
        `「${task.title}」を削除しますか？`
    );

    if (!confirmed) {
        return;
    }

    errorMessage.value = '';
    successMessage.value = '';

    try {
        await axios.delete(`/api/tasks/${task.id}`);

        successMessage.value =
            'タスクを削除しました。';

        await fetchTasks();
    } catch (error) {
        console.error(error);

        if (error.response?.status === 401) {
            errorMessage.value =
                'ログインが必要です。';
        } else if (error.response?.status === 404) {
            errorMessage.value =
                '削除するタスクが見つかりません。';
        } else {
            errorMessage.value =
                'タスクの削除に失敗しました。';
        }
    }
};

onMounted(() => {
    fetchTasks();
    fetchCategories();
});
</script>

<template>
    <main class="task-app">
        <TaskForm
            :categories="categories"
            :form="form"
            :form-errors="formErrors"
            :is-submitting="isSubmitting"
            :success-message="successMessage"
            @update:form="updateForm"
            @submit="createTask"
        />
        <header class="task-header">
            <div>
                <h1>タスク管理</h1>

                <p>
                    Laravel APIから取得したタスクです。
                </p>
            </div>

            <button
                type="button"
                class="reload-button"
                :disabled="isLoading"
                @click="fetchTasks"
            >
                {{ isLoading ? '読み込み中...' : '再読み込み' }}
            </button>
        </header>

        <p
            v-if="isLoading"
            class="loading-message"
        >
            タスクを読み込んでいます...
        </p>

        <p
            v-else-if="errorMessage"
            class="error-message"
        >
            {{ errorMessage }}
        </p>

        <p
            v-else-if="tasks.length === 0"
            class="empty-message"
        >
            タスクはまだ登録されていません。
        </p>

        <div
            v-else
            class="task-grid"
        >
            <TaskCard
                v-for="task in tasks"
                :key="task.id"
                :task="task"
                @delete="deleteTask"
            />
        </div>
    </main>
    
</template>

<style scoped>
.task-app {
    max-width: 1000px;
    margin: 30px auto;
    padding: 24px;
    color: #263238;
}

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 28px;
}

.task-header h1 {
    margin: 0 0 8px;
}

.task-header p {
    margin: 0;
    color: #667085;
}

.reload-button {
    padding: 10px 16px;
    border: none;
    border-radius: 7px;
    background-color: #2563eb;
    color: white;
    font-size: 15px;
    cursor: pointer;
}

.reload-button:hover:not(:disabled) {
    background-color: #1d4ed8;
}

.reload-button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

.task-grid {
    display: grid;
    grid-template-columns:
        repeat(auto-fit, minmax(280px, 1fr));
    gap: 18px;
}

.loading-message {
    color: #7e22ce;
    font-weight: bold;
}

.error-message {
    padding: 12px;
    border-radius: 7px;
    background-color: #fdecea;
    color: #c0392b;
}

.empty-message {
    padding: 24px;
    border: 1px dashed #b8c2cc;
    border-radius: 8px;
    color: #667085;
    text-align: center;
}

</style>