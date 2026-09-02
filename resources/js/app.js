import './bootstrap';

import { createApp } from 'vue';
import HelloVue from './components/HelloVue.vue';
import MemoList from './components/MemoList.vue';
import TaskApp from './components/tasks/TaskApp.vue';

const vueElement = document.getElementById('vue-app');

if (vueElement) {
    createApp(HelloVue).mount(vueElement);
}

const memoListElement =
    document.getElementById('vue-memo-list');

if (memoListElement) {
    createApp(MemoList).mount(memoListElement);
}

const taskAppElement =
    document.getElementById('vue-task-app');

if (taskAppElement) {
    createApp(TaskApp).mount(taskAppElement);
}