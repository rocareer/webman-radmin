import createAxios from '/@/utils/axios'

export interface TableInfo {
    name: string
    charset: string
    record_count: number
    engine: string
    comment: string
}

export function syncTable() {
    return createAxios<TableInfo[]>({
            url: '/admin/data/table/sync',
            method: 'get',
        },
        {
            showSuccessMessage: true,
        })
}
