<!--备份下载用url列渲染-->
<template>
    <div>
        <el-input :model-value="cellValue" :placeholder="$t('Link address')">
            <template #append>
                <el-tooltip content="下载备份文件到本地" placement="top">
                    <el-button @click="openUrl(downloadUrl(props.row.id), field)">
                        <Icon color="#606266" name="fa fa-download"/>
                    </el-button>
                </el-tooltip>
            </template>
        </el-input>
    </div>
</template>

<script setup lang="ts">
import {TableColumnCtx} from 'element-plus'
import {getCellValue} from '/@/components/table/index'
import {useAdminInfo} from "/@/stores/adminInfo";
import {uuid} from "/@/utils/random";

const admininfo = useAdminInfo();

interface Props {
    row: TableRow
    field: TableColumn
    column: TableColumnCtx<TableRow>
    index: number
}

const props = defineProps<Props>()

if (props.field.click) {
    console.warn('baTable.table.column.click 即将废弃，请使用 el-table 的 @cell-click 或单元格自定义渲染代替')
}
const host: string = import.meta.env.VITE_AXIOS_BASE_URL as string


const cellValue = getCellValue(props.row, props.field, props.column, props.index)

/**
 * 组装下载url
 * @param id
 */
const downloadUrl = (id: number) => {
    return host + `/admin/data/backup/download?id=${id}&api-token=` + admininfo.getToken() + "&uuid=" + uuid()
}

const openUrl = async (url: string, field: TableColumn) => {
    if (field.target == '_blank') {
        window.open(url)
    } else {
        window.location.href = url
    }
}
</script>
