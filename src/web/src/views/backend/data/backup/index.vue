<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info"
                  show-icon/>

        <!-- 表格顶部菜单 -->
        <!-- 自定义按钮请使用插槽，甚至公共搜索也可以使用具名插槽渲染，参见文档 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('Quick search placeholder', { fields: t('data.backup.quick Search Fields') })"
        ></TableHeader>

        <!-- 表格 -->
        <!-- 表格列有多种自定义渲染方式，比如自定义组件、具名插槽等，参见文档 -->
        <!-- 要使用 el-table 组件原有的属性，直接加在 Table 标签上即可 -->
        <Table ref="tableRef"></Table>

        <!-- 表单 -->
        <PopupForm/>
    </div>
</template>

<script setup lang="ts">
import {nextTick, onMounted, provide, ref} from 'vue'
import {useI18n} from 'vue-i18n'
import PopupForm from './popupForm.vue'
import {baTableApi} from '/@/api/common'
import {defaultOptButtons} from '/@/components/table'
import TableHeader from '/@/components/table/header/index.vue'
import Table from '/@/components/table/index.vue'
import baTableClass from '/@/utils/baTable'
import {useTerminal} from '/@/stores/terminal'
import {backupRun, download} from '/@/api/backend/data/backup'

const terminal = useTerminal()
defineOptions({
    name: 'data/backup',
})

const {t} = useI18n()
const tableRef = ref()

let optButtons: OptButton[] = [
    // {
    //     render: 'confirmButton',
    //     name: 'run',
    //     title: '开始备份',
    //     text: '备份',
    //     type: 'warning',
    //     icon: 'fa fa-play',
    //     class: 'table-row-edit',
    //     popconfirm: {
    //         confirmButtonText: '开始',
    //         cancelButtonText: '取消',
    //         confirmButtonType: 'warning',
    //         title: '确认开始任务吗',
    //     },
    //     disabledTip: false,
    //     click: (row: TableRow) => {
    //         run(row.id)
    //     },
    // },

    {
        render: 'confirmButton',
        name: 'restore',
        title: '还原备份',
        text: '还原',
        type: 'warning',
        icon: 'el-icon-RefreshRight',
        class: 'table-row-edit',
        popconfirm: {
            confirmButtonText: t('security.dataRecycleLog.restore'),
            cancelButtonText: t('Cancel'),
            confirmButtonType: 'success',
            title: t('security.dataRecycleLog.Are you sure to restore the selected records?'),
        },
        disabledTip: false,
        // click: (row: TableRow) => {
        //     onRestore([row[baTable.table.pk!]])
        // },
    },
    {
        render: 'tipButton',
        name: 'info',
        title: '下载',
        text: '',
        type: 'success',
        icon: 'fa fa-download',
        class: 'table-row-info',
        disabledTip: false,
        click: (row: TableRow) => {
            downloadFile(row.id)
        },
    },
    {
        render: 'tipButton',
        name: 'info',
        title: 'Info',
        text: '',
        type: 'primary',
        icon: 'fa fa-search-plus',
        class: 'table-row-info',
        disabledTip: false,
        // click: (row: TableRow) => {
        //     infoButtonClick(row[baTable.table.pk!])
        // },
    }
]
optButtons = optButtons.concat(defaultOptButtons(['delete']))

/**
 * baTable 内包含了表格的所有数据且数据具备响应性，然后通过 provide 注入给了后代组件
 */
const baTable = new baTableClass(
    new baTableApi('/admin/data.Backup/'),
    {
        pk: 'id',
        column: [
            {type: 'selection', align: 'center', operator: false},
            {label: t('data.backup.id'), prop: 'id', align: 'center', width: 70, operator: 'RANGE', sortable: 'custom'},
            {
                label: t('data.backup.data_table__name'),
                prop: 'table_name',
                align: 'left',
                operatorPlaceholder: t('Fuzzy query'),
                operator: 'LIKE',
            },
            {
                label: t('data.backup.version'),
                prop: 'version',
                align: 'center',
                operatorPlaceholder: t('Fuzzy query'),
                operator: 'LIKE',
            },
            {
                label: t('data.backup.file'),
                width: 420,
                prop: 'file',
                align: 'left',
                render: 'urlBackup',
                operator: false
            },

            {
                label: t('data.backup.create_time'),
                prop: 'create_time',
                align: 'center',
                render: 'datetime',
                operator: 'RANGE',
                sortable: 'custom',
                width: 160,
                timeFormat: 'yyyy-mm-dd hh:MM:ss',
            },
            {
                label: t('Operate'),
                fixed: 'right',
                align: 'center',
                width: 240,
                render: 'buttons',
                buttons: optButtons,
                operator: false
            },
        ],
        dblClickNotEditColumn: [undefined, 'status', 'runnow'],
    },
    {
        defaultItems: {runnow: '1'},
    }
)

provide('baTable', baTable)

const run = (id: number) => {

    // 不显示设置栏
    terminal.configShow(false)
    // 自动打开窗口
    terminal.toggle(true)
    terminal.messageShow(true)
    terminal.addTask('data.backup', true, 'webViewsDir', () => {
        // terminal.toggle(false)
        // terminal.toggleDot(true)

        nextTick(() => {
            // 要求 Vite 服务端重启
            // if (import.meta.hot) {
            //     reloadServer('crud')
            // } else {
            //     ElNotification({
            //         type: 'error',
            //         message: t('crud.crud.Vite hot warning'),
            //     })
            // }
        })
    })
}

const downloadFile = (id: number) => {
    download(id)
}

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
