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
                <el-tooltip content="可视化创建新表" placement="top">
                    <el-button
                        v-blur
                        @click="crud"
                        type="primary"
                        class="table-header-operate"
                    >
                        <Icon name="fa fa-code"/>

                        <span class="table-header-operate-text">创建新表</span>
                    </el-button>
                </el-tooltip>
            </template>
        </TableHeader>

        <!-- 表格 -->
        <!-- 表格列有多种自定义渲染方式，比如自定义组件、具名插槽等，参见文档 -->
        <!-- 要使用 el-table 组件原有的属性，直接加在 Table 标签上即可 -->
        <Table ref="tableRef"></Table>

        <Info />
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
import {routePush} from "/@/utils/router";
import Router from "/@/router";
import {useAdminInfo} from "/@/stores/adminInfo";
import {cloneDeep} from "lodash-es";
import {buildJsonToElTreeData} from "/@/utils/common";
import Info from "./info.vue";

const adminInfo = useAdminInfo();

const terminal = useTerminal()

defineOptions({
    name: 'data/table/table',
})

const {t} = useI18n()
const tableRef = ref()
let optButtons: OptButton[] = [
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

    {
        render: 'tipButton',
        name: 'info',
        title: 'Info',
        text: '',
        type: 'primary',
        icon: 'fa fa-search-plus',
        class: 'table-row-info',
        disabledTip: false,
        click: (row: TableRow) => {
            infoButtonClick(row)
        },
    }
]
optButtons = optButtons.concat(defaultOptButtons(['edit']))

/**
 * baTable 内包含了表格的所有数据且数据具备响应性，然后通过 provide 注入给了后代组件
 */
const baTable = new baTableClass(
    new baTableApi('/admin/data.Table/'),
    {
        // dblClickNotEditColumn: ['all'],
        pk: 'id',
        filter: {
            limit: 15
        },
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
                width: 220,
                prop: 'name',
                align: 'left',
                operatorPlaceholder: t('Fuzzy query'),
                operator: 'LIKE',
                sortable: true,
            },
            {
                label: t('data.table.table_type'),
                prop: 'table_type',
                align: 'center',
                render: 'tag',
                custom: {2: 'primary', 1: 'warning'},
                operator: 'eq',
                sortable: false,
                replaceValue: {'1': t('data.table.table_type 1'), '2': t('data.table.table_type 2')},
            },
            {
                label: t('data.table.comment'),
                prop: 'comment',
                width: 240,
                align: 'left',
                operator: 'LIKE',
                formatter: (row) => row.comment || t('None'),
            },
            {
                label: t('data.table.record_count'),
                width: 100,
                prop: 'record_count',
                align: 'center',
                operator: 'RANGE',
                sortable: true
            },
            {
                label: t('data.table.total_size'),
                width: 120,
                prop: 'total_size',
                align: 'left',
                operator: 'RANGE',
                sortable: true
            },
            {
                label: t('data.table.data_size'),
                width: 120,
                prop: 'data_size',
                align: 'left',
                operator: 'RANGE',
                sortable: true
            },
            {
                label: t('data.table.index_size'),
                width: 120,
                prop: 'index_size',
                align: 'left',
                operator: 'RANGE',
                sortable: true
            },
            {
                label: t('data.table.charset'),
                prop: 'charset',
                align: 'center',
                operatorPlaceholder: t('Fuzzy query'),
                operator: 'LIKE',

            },


            {
                label: t('data.table.engine'),
                prop: 'engine',
                align: 'center',
                render: 'tag',
                operator: 'eq',
            },
            {
                label: t('data.table.update_time'),
                prop: 'update_time',
                align: 'center',
                render: 'datetime',
                operator: 'RANGE',
                sortable: 'custom',
                width: 160,
                timeFormat: 'yyyy-mm-dd hh:MM:ss',
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
            {
                label: t('Operate'),
                align: 'center',
                width: 120,
                render: 'buttons',
                buttons: optButtons,
                operator: false,
                fixed: 'right',
            },
        ],
        dblClickNotEditColumn: [undefined, 'engine'],
    },
    {
        defaultItems: {engine: '1'},
    },{
        onTableDblclick: ({ row }) => {
            infoButtonClick(row)
            return false
        },
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
    routePush({name: 'data/backup'})
}
const crud = () => {
    routePush({name: 'crud/crud'})
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

    if (selection?.length == 0) {
        ElMessageBox.confirm(t('您没有选中任何表,继续执行将备份所有表'), "提示!", {
            confirmButtonText: t('Confirm'),
            cancelButtonText: t('Cancel'),
            type: 'warning',
        }).then(() => {
            backup(tables)
        })
    }
    if (selection?.length != 0) {
        ElMessageBox.confirm(t('您选中了' + selection?.length + '个表,确认执行备份吗?'), "提示!", {
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
    const ext: string = tables.length > 0 ? tables.join(',') : 'all';
    const extend: string = ext + "~~" + adminInfo.id;

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
                routePush({name: 'data/backup'})
            })
        })
    })
}

const infoButtonClick = (row: TableRow) => {
    if (!row) return
    // 数据来自表格数据,未重新请求api,深克隆,不然可能会影响表格
    let rowClone = cloneDeep(row)
    rowClone.columns = JSON.parse(rowClone.columns)
    baTable.form.extend!['info'] = rowClone
    baTable.form.operate = 'Info'
}

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    sync()

})
</script>

<style scoped lang="scss"></style>
