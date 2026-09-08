<script setup>
defineProps({
    totalTasks: {
        type: Number,
        default: 0,
    },
    completedTasks: {
        type: Number,
        default: 0,
    },
    incompleteTasks: {
        type: Number,
        default: 0,
    },
    completionRate: {
        type: Number,
        default: 0,
    },
});
</script>

<template>
    <section class="stats-section">
        <div class="stats-grid">
            <!-- 総タスク数 -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-icon total-icon">📋</span>
                    <span class="stat-title">総タスク数</span>
                </div>
                <div class="stat-value">{{ totalTasks }} <span class="stat-unit">件</span></div>
            </div>

            <!-- 未完了数 -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-icon incomplete-icon">⏳</span>
                    <span class="stat-title">未完了タスク</span>
                </div>
                <div class="stat-value incomplete-value">{{ incompleteTasks }} <span class="stat-unit">件</span></div>
            </div>

            <!-- 完了数 -->
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-icon completed-icon">✅</span>
                    <span class="stat-title">完了タスク</span>
                </div>
                <div class="stat-value completed-value">{{ completedTasks }} <span class="stat-unit">件</span></div>
            </div>

            <!-- 完了率 -->
            <div class="stat-card rate-card">
                <div class="stat-header">
                    <span class="stat-icon rate-icon">📊</span>
                    <span class="stat-title">進捗率（完了率）</span>
                </div>
                <div class="stat-value rate-value">{{ completionRate }}<span class="stat-unit">%</span></div>
                <div class="progress-bar-bg">
                    <div
                        class="progress-bar-fill"
                        :style="{ width: `${completionRate}%` }"
                    ></div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.stats-section {
    margin-bottom: 24px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.stat-card {
    padding: 18px 20px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background-color: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.stat-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}

.stat-icon {
    font-size: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background-color: #f1f5f9;
}

.total-icon {
    background-color: #eff6ff;
}

.incomplete-icon {
    background-color: #fef3c7;
}

.completed-icon {
    background-color: #ecfdf5;
}

.rate-icon {
    background-color: #f3e8ff;
}

.stat-title {
    color: #64748b;
    font-size: 13px;
    font-weight: 600;
}

.stat-value {
    color: #0f172a;
    font-size: 28px;
    font-weight: 700;
    line-height: 1.1;
}

.stat-unit {
    font-size: 15px;
    font-weight: 500;
    color: #64748b;
    margin-left: 2px;
}

.incomplete-value {
    color: #d97706;
}

.completed-value {
    color: #059669;
}

.rate-value {
    color: #7c3aed;
}

.progress-bar-bg {
    margin-top: 10px;
    height: 7px;
    background-color: #f1f5f9;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #8b5cf6, #10b981);
    border-radius: 4px;
    transition: width 0.4s ease;
}
</style>
