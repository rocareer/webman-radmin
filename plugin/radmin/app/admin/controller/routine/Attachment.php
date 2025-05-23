<?php
/** @noinspection DuplicatedCode */

namespace plugin\radmin\app\admin\controller\routine;

use plugin\radmin\app\common\controller\Backend;
use plugin\radmin\app\common\model\Attachment as AttachmentModel;
use Throwable;

class Attachment extends Backend
{
    /**
     * @var object
     * @phpstan-var AttachmentModel
     */
    protected object $model;

    protected string|array $quickSearchField = 'name';

    protected array $withJoinTable = ['admin', 'user'];

    protected string|array $defaultSortField = 'last_upload_time,desc';

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new AttachmentModel();
    }

    /**
     * 删除
     * @throws Throwable
     */
    public function del(): \Radmin\Response
    {
        $where             = [];
        $dataLimitAdminIds = $this->getDataLimitAdminIds();
        if ($dataLimitAdminIds) {
            $where[] = [$this->dataLimitField, 'in', $dataLimitAdminIds];
        }

        $ids     = $this->request->input('ids', []);
        $where[] = [$this->model->getPk(), 'in', $ids];
        $data    = $this->model->where($where)->select();

        $count = 0;
        try {
            foreach ($data as $v) {
                $count += $v->delete();
            }
        } catch (Throwable $e) {
            return $this->error(__('%d records and files have been deleted', [$count]) . $e->getMessage());
        }
        if ($count) {
            return $this->success(__('%d records and files have been deleted', [$count]));
        } else {
            return $this->error(__('No rows were deleted'));
        }
    }
}