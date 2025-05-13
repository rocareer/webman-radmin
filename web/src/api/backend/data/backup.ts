import createAxios from '/@/utils/axios'
import {useAdminInfo} from "/@/stores/adminInfo";
const adminInfo = useAdminInfo()



export function backup(id:number) {
    return createAxios({
            url: '/admin/data/backup/run',
            method: 'get',
            params: {
                id:id
            },
        },
        {
            showSuccessMessage: true,
        })
}


export async function  download (id: number) {
    const host: string = import.meta.env.VITE_AXIOS_BASE_URL as string
    try {
        // 直接使用window.open触发下载
        window.open(host+`/admin/data/backup/download?id=${id}&batoken=`+adminInfo.getToken(), '_blank');
    } catch (error) {
        console.error('下载失败:', error);
        // 这里可以添加错误提示
    }
}
