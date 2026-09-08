<script setup>
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import TaskCard from './TaskCard.vue';
import TaskForm from './TaskForm.vue';
import TaskFilter from './TaskFilter.vue';
import TaskStats from './TaskStats.vue';

const tasks = ref([]);
const isLoading = ref(false);
const errorMessage = ref('');

const categories = ref([]);

// フィルター用 state
const searchKeyword = ref('');
const selectedStatus = ref('');
const selectedCategoryId = ref('');
const selectedPriority = ref('');

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
const editingTaskId = ref(null);

// 統計情報（ダッシュボード）
const totalTasks = computed(() => tasks.value.length);
const completedTasks = computed(
    () => tasks.value.filter((task) => task.status === 'completed').length
);
const incompleteTasks = computed(
    () => totalTasks.value - completedTasks.value
);
const completionRate = computed(() =>
    totalTasks.value > 0
        ? Math.round((completedTasks.value / totalTasks.value) * 100)
        : 0
);

// 絞り込みフィルター
const filteredTasks = computed(() => {
    const keyword = searchKeyword.value.trim().toLowerCase();

    return tasks.value.filter((task) => {
        // キーワード検索（タイトル・詳細）
        const matchesKeyword =
            !keyword ||
            task.title.toLowerCase().includes(keyword) ||
            (task.description && task.description.toLowerCase().includes(keyword));

        // ステータス絞り込み
        const matchesStatus =
            !selectedStatus.value || task.status === selectedStatus.value;

        // プロジェクト（カテゴリ）絞り込み
        const matchesCategory =
            !selectedCategoryId.value ||
            String(task.category?.id) === String(selectedCategoryId.value);

        // 優先度絞り込み
        const matchesPriority =
            !selectedPriority.value || task.priority === selectedPriority.value;

        return matchesKeyword && matchesStatus && matchesCategory && matchesPriority;
    });
});

const clearFilters = () => {
    searchKeyword.value = '';
    selectedStatus.value = '';
    selectedCategoryId.value = '';
    selectedPriority.value = '';
};

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

const updateTask = async () => {
    isSubmitting.value = true;
    formErrors.value = {};
    successMessage.value = '';
    errorMessage.value = '';

    try {
        await axios.put(
            `/api/tasks/${editingTaskId.value}`,
            {
                category_id: form.value.category_id,
                title: form.value.title,
                description:
                    form.value.description || null,
                status: form.value.status,
                priority: form.value.priority,
                due_date:
                    form.value.due_date || null,
            }
        );

        successMessage.value =
            'タスクを更新しました。';

        editingTaskId.value = null;
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
        } else if (error.response?.status === 404) {
            errorMessage.value =
                '更新するタスクが見つかりません。';
        } else {
            errorMessage.value =
                'タスクの更新に失敗しました。';
        }
    } finally {
        isSubmitting.value = false;
    }
};

const startEdit = (task) => {
    editingTaskId.value = task.id;

    form.value = {
        category_id: task.category?.id ?? '',
        title: task.title,
        description: task.description ?? '',
        status: task.status,
        priority: task.priority,
        due_date: task.due_date ?? '',
    };

    formErrors.value = {};
    successMessage.value = '';
    errorMessage.value = '';

    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

const cancelEdit = () => {
    editingTaskId.value = null;
    successMessage.value = '';
    errorMessage.value = '';

    resetForm();
};

onMounted(() => {
    fetchTasks();
    fetchCategories();
});
</script>

<template>
    <main class="task-app">
        <header class="task-header">
            <div>
                <h1 class="app-title">TaskFlow</h1>

                <p>
                    プロジェクトとタスクを一元管理するタスクフローシステム
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

        <!-- ダッシュボード統計 -->
        <TaskStats
            :total-tasks="totalTasks"
            :completed-tasks="completedTasks"
            :incomplete-tasks="incompleteTasks"
            :completion-rate="completionRate"
        />

        <!-- タスク作成・編集フォーム -->
        <TaskForm
            :categories="categories"
            :form="form"
            :form-errors="formErrors"
            :is-submitting="isSubmitting"
            :success-message="successMessage"
            :is-editing="editingTaskId !== null"
            @update:form="updateForm"
            @submit="editingTaskId !== null ? updateTask() : createTask()"
            @cancel="cancelEdit"
        />

        <!-- タスク一覧セクション -->
        <section class="task-list-section">
            <div class="list-section-header">
                <h2>タスク一覧</h2>
            </div>

            <!-- 検索・絞り込みフィルター -->
            <TaskFilter
                v-model:search-keyword="searchKeyword"
                v-model:selected-status="selectedStatus"
                v-model:selected-category-id="selectedCategoryId"
                v-model:selected-priority="selectedPriority"
                :categories="categories"
                :total-count="tasks.length"
                :filtered-count="filteredTasks.length"
                @clear="clearFilters"
            />

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
                タスクはまだ登録されていません。上のフォームから最初のタスクを登録しましょう！
            </p>

            <p
                v-else-if="filteredTasks.length === 0"
                class="empty-message"
            >
                検索条件に一致するタスクは見つかりませんでした。
            </p>

            <div
                v-else
                class="task-grid"
            >
                <TaskCard
                    v-for="task in filteredTasks"
                    :key="task.id"
                    :task="task"
                    @edit="startEdit"
                    @delete="deleteTask"
                />
            </div>
        </section>
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

.task-header h1.app-title {
    margin: 0 0 6px;
    font-size: 30px;
    font-weight: 800;
    color: #1e3a8a;
    letter-spacing: -0.5px;
}

.task-header p {
    margin: 0;
    color: #64748b;
    font-size: 15px;
}

.task-list-section {
    margin-top: 36px;
}

.list-section-header {
    margin-bottom: 16px;
}

.list-section-header h2 {
    margin: 0;
    font-size: 22px;
    color: #1e293b;
    font-weight: 700;
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