<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info"
                  show-icon/>

        <!-- 表格顶部菜单 -->
        <!-- 自定义按钮请使用插槽，甚至公共搜索也可以使用具名插槽渲染，参见文档 -->
        <TableHeader
            :buttons="['refresh', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('Quick search placeholder', { fields: t('data.table.quick Search Fields') })"
        >
            <template #refreshAppend>
                <el-tooltip content="与数据库同步" placement="top">
                    <el-button
                        v-blur
                        @click="sync"
                        type="danger"
                        class="table-header-operate"
                    >
                        <el-icon>
                            <Refresh/>
                        </el-icon>
                        <span class="table-header-operate-text">与数据库同步</span>
                    </el-button>
                </el-tooltip>

                <el-tooltip content="备份数据" placement="top">
                    <el-button
                        v-blur
                        @click="backupSelectiontables"
                        type="success"
                        class="table-header-operate"
                    >
                        <Icon name="el-icon-CopyDocument"/>

                        <span class="table-header-operate-text">备份表</span>
                    </el-button>
                </el-tooltip>
                <el-tooltip content="还原数据" placement="top">
                    <el-button
                        v-blur
                        @click="restore"
                        type="warning"
                        class="table-header-operate"
                    >
                        <Icon name="el-icon-RefreshRight"/>

                        <span class="table-header-operate-text">还原表</span>
                    </el-button>
                </el-tooltip>
            </template>
        </TableHeader>

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
import {syncTable} from "/@/api/backend/data/table";
import {Refresh} from "@element-plus/icons-vue";
import {useTerminal} from "/@/stores/terminal";
import {ElMessageBox} from "element-plus";
import {changeListenDirtyFileSwitch} from "/@/utils/vite";
import {routePush} from "/@/utils/router";
import Router from "/@/router";

const terminal = useTerminal()

defineOptions({
    name: 'data/table/table',
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
        name: 'info',
        title: '备份数据',
        text: '',
        type: 'success',
        icon: 'el-icon-CopyDocument',
        class: 'table-row-info',
        disabledTip: false,
        click: (row: TableRow) => {
            backup([row.name])
        },
    },
    // {
    //     render: 'confirmButton',
    //     name: 'restore',
    //     title: '还原备份',
    //     text: '',
    //     type: 'warning',
    //     icon: 'el-icon-RefreshRight',
    //     class: 'table-row-edit',
    //     popconfirm: {
    //         confirmButtonText: t('security.dataRecycleLog.restore'),
    //         cancelButtonText: t('Cancel'),
    //         confirmButtonType: 'success',
    //         title: t('security.dataRecycleLog.Are you sure to restore the selected records?'),
    //     },
    //     disabledTip: false,
    //     // click: (row: TableRow) => {
    //     //     onRestore([row[baTable.table.pk!]])
    //     // },
    // },

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
optButtons = optButtons.concat(defaultOptButtons(['edit']))

/**
 * baTable 内包含了表格的所有数据且数据具备响应性，然后通过 provide 注入给了后代组件
 */
const baTable = new baTableClass(
    new baTableApi('/admin/data.Table/'),
    {
        pk: 'id',
        column: [
            {type: 'selection', align: 'center', operator: false},
            {
                label: t('data.table.id'),
                prop: 'id',
                align: 'center',
                width: 70,
                operator: 'RANGE',
                sortable: 'custom'
            },
            {
                label: t('data.table.name'),
                prop: 'name',
                align: 'left',
                operatorPlaceholder: t('Fuzzy query'),
                operator: 'LIKE',
                sortable: true,
            },
            {
                label: t('data.table.comment'),
                prop: 'comment',
                align: 'left',
                operator: 'LIKE',
                formatter: (row) => row.comment || t('None'),
            },
            {
                label: t('data.table.record_count'),
                prop: 'record_count',
                align: 'center',
                operator: 'RANGE',
                sortable: true
            },
            {
                label: t('data.table.charset'),
                prop: 'charset',
                align: 'center',
                operatorPlaceholder: t('Fuzzy query'),
                operator: 'LIKE',
                sortable: false,
            },


            {
                label: t('data.table.engine'),
                prop: 'engine',
                align: 'center',
                render: 'tag',
                operator: 'eq',
                sortable: true,
            },

            {
                label: t('data.table.create_time'),
                prop: 'create_time',
                align: 'center',
                render: 'datetime',
                operator: 'RANGE',
                sortable: 'custom',
                width: 160,
                timeFormat: 'yyyy-mm-dd hh:MM:ss',
            },
            {label: t('Operate'), align: 'center', width: 200, render: 'buttons', buttons: optButtons, operator: false},
        ],
        dblClickNotEditColumn: [undefined, 'engine'],
    },
    {
        defaultItems: {engine: '1'},
    }
)

provide('baTable', baTable)


/**
 * 数据同步
 */
const sync = () => {
    baTable.getIndex()
    syncTable().then((res) => {
        baTable.getIndex()?.then(() => {
            baTable.initSort()
            baTable.dragSort()
        })
    })
}
const restore = () => {
    routePush({ name: 'data/backup' })
}


/**
 * 获取表格选择项的id数组
 */
const backupSelectiontables = () => {
    const tables: string[] = []
    const selection = baTable.table.selection;
    selection?.forEach((item) => {
        tables.push(item.name!)
    })

    if (selection?.length==0){
        ElMessageBox.confirm(t('您没有选中任何表,继续执行将备份所有表'), "提示!", {
            confirmButtonText: t('Confirm'),
            cancelButtonText: t('Cancel'),
            type: 'warning',
        }).then(() => {
            backup(tables)
        })
    }
    if (selection?.length!=0){
        ElMessageBox.confirm(t('您选中了'+selection?.length+'个表,确认执行备份吗?'), "提示!", {
            confirmButtonText: t('Confirm'),
            cancelButtonText: t('Cancel'),
            type: 'warning',
        }).then(() => {
            backup(tables)
        })
    }




}

/**
 * 备份单表
 * @param tables
 */
const backup = (tables: any) => {

    // 安全转换，处理空数组情况
    const extend: string = tables.length > 0 ? tables.join(',') : '-a';

    // 不显示设置栏
    terminal.configShow(false)
    terminal.toggle(true) // 自动打开窗口
    // terminal.messageShow(true)
    terminal.addTask('data.backup', true, extend, () => {
        terminal.toggle(false)
        terminal.toggleDot(true)
        terminal.configShow(true)
        nextTick(() => {
            ElMessageBox.confirm(t('您可以到备份记录页面查看备份记录'), "备份完成!", {
                confirmButtonText: "前往",
                cancelButtonText: t('Cancel'),
                type: 'success',
            }).then(() => {
                routePush({ name: 'data/backup' })
            })
        })
    })
}


onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    sync()

})
</script>

<style scoped lang="scss"></style>
