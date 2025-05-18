<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info"
                  show-icon/>

        <!-- 表格顶部菜单 -->
        <!-- 自定义按钮请使用插槽，甚至公共搜索也可以使用具名插槽渲染，参见文档 -->
        <TableHeader
            :buttons="['refresh', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            quick-search-placeholder="通过表名和版本号模糊搜索"
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
import {download} from '/@/api/backend/data/backup'
import {ElMessageBox, ElNotification} from "element-plus";
import {routePush} from "/@/utils/router";
import {taskStatus} from "/@/stores/constant/terminalTaskStatus";
import {useAdminInfo} from "/@/stores/adminInfo";
const adminInfo=useAdminInfo();

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
        render: 'tipButton',
        name: 'restore',
        title: '还原备份',
        text: '还原',
        type: 'warning',
        icon: 'el-icon-RefreshRight',
        class: 'table-row-edit',
        click(row, field) {
            clickRestore(row)
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
                width: 200,
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

const clickRestore = (row: any) => {
    ElMessageBox.confirm('操作不可逆!还原数据前最好先备份!', "谨慎操作!", {
        confirmButtonText: "前去备份",
        cancelButtonText:"继续操作",
        type: 'warning',
    }).then(() => {
        routePush({ name: 'data/table' })
    }).catch(() => {
        restore(row)
    })
}

const restore = (row: any) => {

    // 安全转换，处理空数组情况
    const extend: string = row.table_name+'~~'+row.version+'~~'+adminInfo.id;

    // 不显示设置栏
    terminal.configShow(false)
    terminal.toggle(true) // 自动打开窗口
    // terminal.messageShow(true)
    terminal.addTask('data.restore', true, extend, (status: number) => {

        terminal.toggleDot(true)
        terminal.configShow(true)

        nextTick(() => {
            if (status === taskStatus.Success) {
                ElNotification({
                    message: t('还原成功'),
                    type: 'success'
                })
            } else {
                ElNotification({
                    message: t('还原失败'),
                    type: 'error'
                })
            }
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
