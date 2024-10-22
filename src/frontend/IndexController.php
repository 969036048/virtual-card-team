<?php

namespace Yunshop\VirtualCardTeam\frontend;

use app\common\components\BaseController;
use Yunshop\VirtualCardTeam\common\models\Activity;
use Yunshop\VirtualCardTeam\common\models\ParticipateIn;

class IndexController extends BaseController
{
    public function getBaseInfo()
    {
        $search = request()->search;
        $list = Activity::uniacid()
            ->select('id', 'title', 'start_time', 'end_time')
            ->search($search)
            ->orderBy('start_time', 'desc')
            ->paginate();
        $list->each(function ($item) {
            $item->start_time = date('Y-m-d H:i:s', $item->start_time);
            $item->end_time = date('Y-m-d H:i:s', $item->end_time);
        });
        $set = \Setting::get('plugin.check_in_rebate');
        $info_set = [
            'top_thumb_url' => $set['top_thumb_url'] ?: yz_tomedia($set['top_thumb']),
            'plugin_name' => CHECK_IN_REBATE_PLUGIN_NAME,
        ];


        return $this->successJson('ok', [
                'activity' => $list,
                'set' => $info_set
            ]
        );
    }

    public function getDetail()
    {
        $id = request()->id;
        $activity = Activity::uniacid()
            ->select('id', 'title', 'start_time', 'end_time', 'detail', 'goods_id')
            ->find($id);

        $activity->start_time = $activity->start_time ? date('Y-m-d H:i:s', $activity->start_time) : '';
        $activity->end_time = $activity->end_time ? date('Y-m-d H:i:s', $activity->end_time) : '';
        $activity->count_member = ParticipateIn::uniacid()
            ->where('activity_id', $id)
            ->groupBy('member_id')
            ->count();
        if ($activity) {
            return $this->successJson('ok', $activity);
        } else {
            return $this->errorJson('活动不存在');
        }
    }
}