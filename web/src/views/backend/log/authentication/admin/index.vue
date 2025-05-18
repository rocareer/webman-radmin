<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <!-- 自定义按钮请使用插槽，甚至公共搜索也可以使用具名插槽渲染，参见文档 -->
        <TableHeader
            :buttons="['refresh', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            quick-search-placeholder="通过用户名模糊搜索"
        ></TableHeader>

        <!-- 表格 -->
        <!-- 表格列有多种自定义渲染方式，比如自定义组件、具名插槽等，参见文档 -->
        <!-- 要使用 el-table 组件原有的属性，直接加在 Table 标签上即可 -->
        <Table ref="tableRef"></Table>

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { onMounted, provide, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import { baTableApi } from '/@/api/common'
import { defaultOptButtons } from '/@/components/table'
import TableHeader from '/@/components/table/header/index.vue'
import Table from '/@/components/table/index.vue'
import baTableClass from '/@/utils/baTable'

defineOptions({
    name: 'log/authentication/admin',
})

const { t } = useI18n()
const tableRef = ref()
const optButtons: OptButton[] = defaultOptButtons(['delete'])

/**
 * baTable 内包含了表格的所有数据且数据具备响应性，然后通过 provide 注入给了后代组件
 */
const baTable = new baTableClass(
    new baTableApi('/admin/log.authentication.Admin/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('log.authentication.admin.id'), prop: 'id', align: 'center', width: 70, operator: 'RANGE', sortable: 'custom' },
            {
                label: t('log.authentication.admin.name'),
                prop: 'name',
                align: 'left',
                operatorPlaceholder: t('Fuzzy query'),
                render: 'tag',
                operator: 'LIKE',
            },
            {
                label: t('log.authentication.admin.action'),
                prop: 'action',
                align: 'center',
                render: 'tag',
                custom: { 1: 'primary', 2: 'warning' },
                operator: 'eq',
                sortable: false,
                replaceValue: { '1': t('log.authentication.admin.action 1'), '2': t('log.authentication.admin.action 2') },
            },
            {
                label: t('log.authentication.admin.status'),
                prop: 'status',
                align: 'center',
                render: 'tag',
                custom: { 0: 'danger', 1: 'success' },
                operator: 'eq',
                sortable: false,
                replaceValue: { '1': t('log.authentication.admin.status 1'), '0': t('log.authentication.admin.status 0') },
            },
            {
                label: t('log.authentication.admin.ip'),
                prop: 'ip',
                align: 'left',
                operator: 'eq',
                sortable: false,
            },
            {
                label: t('log.authentication.admin.create_time'),
                prop: 'create_time',
                align: 'center',
                render: 'datetime',
                operator: 'RANGE',
                sortable: 'custom',
                width: 160,
                timeFormat: 'yyyy-mm-dd hh:MM:ss',
            },
            { label: t('Operate'), align: 'center', width: 100, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined, 'action'],
    },
    {
        defaultItems: { action: '1' },
    }
)

provide('baTable', baTable)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})
</script>

<style scoped lang="scss"></style>
