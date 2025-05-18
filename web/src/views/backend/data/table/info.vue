<template>
    <!-- 查看详情 -->
    <el-dialog class="ba-operate-dialog info"
               :model-value="['Info'].includes(baTable.form.operate!)"
               @close="baTable.toggleForm"
               draggable width="60%"
               destroy-on-close>
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
               表: {{ baTable.form.extend!.info.name }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div class="ba-operate-form" :class="'ba-' + baTable.form.operate + '-form'">
                <el-collapse v-model="activeName" accordion>
                    <el-collapse-item name="1">
                        <template #title=" isActive ">
                            <div :class="['title-wrapper', { 'is-active': isActive }]">
                                基本详情
                                <el-icon class="header-icon">
                                    <info-filled />
                                </el-icon>
                            </div>
                        </template>
                        <el-descriptions :column="4" border>
                            <el-descriptions-item :width="120" :span="2" :label="t('data.table.name')">
                                {{ baTable.form.extend!.info.name }}
                            </el-descriptions-item>
                            <el-descriptions-item :width="120" :span="2" :label="t('data.table.table_type')">
                                {{ baTable.form.extend!.info.table_type_name }}
                            </el-descriptions-item>
                            <el-descriptions-item :width="120" :span="2" :label="t('data.table.engine')">
                                {{ baTable.form.extend!.info.engine }}
                            </el-descriptions-item>
                            <el-descriptions-item :width="120" :span="2" :label="t('data.table.charset')">
                                {{ baTable.form.extend!.info.charset }}
                            </el-descriptions-item>
                            <el-descriptions-item :width="120" :span="2" :label="t('data.table.record_count')">
                                {{ baTable.form.extend!.info.record_count }}
                            </el-descriptions-item>
                            <el-descriptions-item :width="120" :span="2" :label="t('data.table.total_size')">
                                {{ baTable.form.extend!.info.total_size }}
                            </el-descriptions-item>
                            <el-descriptions-item :width="120" :span="2" :label="t('data.table.data_size')">
                                {{ baTable.form.extend!.info.data_size }}
                            </el-descriptions-item>
                            <el-descriptions-item :width="120" :span="2" :label="t('data.table.index_size')">
                                {{ baTable.form.extend!.info.index_size }}
                            </el-descriptions-item>
                            <el-descriptions-item :width="120" :span="2" :label="t('data.table.create_time')">
                                {{ baTable.form.extend!.info.create_time }}
                            </el-descriptions-item>
                            <el-descriptions-item :width="120" :span="2" :label="t('data.table.update_time')">
                                {{ baTable.form.extend!.info.update_time }}
                            </el-descriptions-item>
                            <el-descriptions-item :width="120" :span="4" :label="t('data.table.comment')">
                                {{ baTable.form.extend!.info.comment }}
                            </el-descriptions-item>
                        </el-descriptions>
                    </el-collapse-item>
                    <el-collapse-item title="数据结构" active name="2">
                        <el-table height="100%" :data="baTable.form.extend!.info.columns" border stripe
                                  v-if="baTable.form.extend!.info.columns.length > 0">
                            <el-table-column prop="field" label="字段名" width="120"/>
                            <el-table-column prop="type" label="类型" width="100"/>
                            <el-table-column prop="length" label="长度" width="80" align="center">
                                <template #default="scope">
                                    {{ scope.row.length !== null ? scope.row.length : '0' }}
                                </template>
                            </el-table-column>
                            <el-table-column prop="precision" label="小数点" width="80" align="center">
                                <template #default="scope">
                                    {{ scope.row.precision !== null ? scope.row.precision : '0' }}
                                </template>
                            </el-table-column>
                            <el-table-column prop="nullable" label="允许NULL" width="90" align="center">
                                <template #default="scope">
                                    <el-tag :type="scope.row.nullable ? 'info' : 'success'" size="small">
                                        {{ scope.row.nullable ? '是' : '否' }}
                                    </el-tag>
                                </template>
                            </el-table-column>
                            <el-table-column prop="key" label="键" width="80" align="center">
                                <template #default="scope">
                                    <el-tag v-if="scope.row.key === 'PRI'" type="danger" size="small">主键</el-tag>
                                    <el-tag v-else-if="scope.row.key === 'UNI'" type="warning" size="small">唯一
                                    </el-tag>
                                    <el-tag v-else-if="scope.row.key === 'MUL'" type="success" size="small">索引
                                    </el-tag>
                                    <span v-else>-</span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="default" label="默认值" width="100">
                                <template #default="scope">
                                    {{ scope.row.default !== null ? scope.row.default : '-' }}
                                </template>
                            </el-table-column>
                            <el-table-column prop="extra" label="额外" width="120">
                                <template #default="scope">
                                    <el-tag v-if="scope.row.extra === 'auto_increment'" type="info" size="small">自增
                                    </el-tag>
                                    <span v-else>{{ scope.row.extra || '-' }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="comment" label="注释" min-width="150" show-overflow-tooltip/>
                        </el-table>
                        <el-empty v-else description="暂无字段信息"/>
                    </el-collapse-item>
                </el-collapse>
            </div>
        </el-scrollbar>
    </el-dialog>
</template>

<script setup lang="ts">
import {inject, ref} from 'vue'
import {useI18n} from 'vue-i18n'
import type BaTable from '/@/utils/baTable'
import {timeFormat} from '/@/utils/common'
import {InfoFilled} from "@element-plus/icons-vue";
const activeName = ref('2')
const dialogVisible = ref(false)
const baTable = inject('baTable') as BaTable

const {t} = useI18n()
</script>

<style scoped lang="scss">
.table-el-tree {
    :deep(.el-tree-node) {
        white-space: unset;
    }

    :deep(.el-tree-node__content) {
        display: block;
        align-items: unset;
        height: unset;
    }
}
.title-wrapper {
    display: flex;
    align-items: center;
    gap: 4px;
}

.title-wrapper.is-active {
    color: var(--el-color-primary);
}
.info{
    padding: 10vh;
}

</style>
