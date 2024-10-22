<?php

namespace Yunshop\VirtualCardTeam\admin;

use app\common\components\BaseController;
use app\common\models\Coupon;
use app\common\models\Goods;
use Yunshop\VirtualCardTeam\common\models\Activity;
use Yunshop\VirtualCardTeam\common\models\ExpectCard;


class CardController extends BaseController
{
    public function index()
    {
        return view('Yunshop\VirtualCardTeam::card.list')->render();
    }

    public function getData()
    {
        $list = ExpectCard::uniacid()->search(request()->search)
            ->orderBy('id', 'desc')
            ->paginate();
        return $this->successJson('ok', $list);
    }

    public function detail()
    {
        return view('Yunshop\VirtualCardTeam::activity.detail')->render();
    }

    public function save()
    {
        $data = request()->all();
        $save_data = [
            'uniacid' => \YunShop::app()->uniacid,
            'title' => $data['title'],
            'count_day' => $data['count_day'],
            'is_continuous' => $data['is_continuous'],
            'goods_id' => $data['goods_id'],
            'start_time' => $data['start_time'] ? strtotime($data['start_time']) : '',
            'end_time' => $data['start_time'] ? strtotime($data['end_time']) : '',
            'supplement_goods_id' => $data['supplement_goods_id'],
            'supplement_goods_price_level' => json_encode($data['supplement_goods_price_level']),
            'reward_ratio' => $data['reward_ratio'],
            'coupon_list' => json_encode($data['coupon_list']),
            'check_in_limit' => $data['check_in_limit'],
            'detail' => $data['detail'],
        ];
        if ($data['id']) {
            $activity = Activity::find($data['id']);
        } else {
            $activity = new Activity();
        }
        $activity->fill($save_data);
        $validator = $activity->validator($save_data);
        if ($validator->fails()) {
            return $this->errorJson($validator->messages()->first());
        }
        if ($activity->save()) {
            return $this->successJson('保存成功', []);
        } else {
            return $this->errorJson('保存失败', []);
        }
    }

    public function getDetail()
    {
        $id = request()->id;
        $activity = Activity::uniacid()
            ->with([
                'goods' => function ($query) {
                    $query->select('id', 'title', 'thumb');
                },
                'supplementGoods' => function ($query) {
                    $query->select('id', 'title', 'thumb');
                },
            ])
            ->find($id);
        $activity->start_time = $activity->start_time ? date('Y-m-d H:i:s', $activity->start_time) : '';
        $activity->end_time = $activity->end_time ? date('Y-m-d H:i:s', $activity->end_time) : '';
        $activity->supplement_goods_price_level = json_decode($activity->supplement_goods_price_level, true);
        $activity->coupon_list = json_decode($activity->coupon_list, true);
        if ($activity) {
            return $this->successJson('ok', $activity);
        } else {
            return $this->errorJson('活动不存在');
        }

    }
    public function getGoodsList()
    {
        $query = Goods::uniacid()
            ->where('plugin_id', 0);

        // 排除已添加的商品防止数据混乱
        $goods_ids = Activity::uniacid()->pluck('goods_id');
        $supplement_goods_ids = Activity::uniacid()->pluck('supplement_goods_id');
        $query->whereNotIn('id', $goods_ids)->whereNotIn('id', $supplement_goods_ids);
        if (request()->keyword) {
            $query->where('title', 'like', '%' . request()->keyword . '%');
        }
        $list = $query->paginate();

        $list->each(function ($item) {
            $item->thumb = yz_tomedia($item->thumb);
        });
        return $this->successJson('ok', $list);
    }

    public function getCoupon()
    {
        $data = Coupon::uniacid()->select('id', 'uniacid', 'name');
        if (request()->keyword) {
            $data = $data->where('name', 'like', '%' . request()->keyword . '%')
                ->orWhere('id', 'like', '%' . request()->keyword . '%');
        }
        return $this->successJson('ok', $data->paginate());
    }

}