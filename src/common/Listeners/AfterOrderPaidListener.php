<?php

namespace Yunshop\VirtualCardTeam\common\Listeners;

use app\common\events\order\AfterOrderPaidEvent;
use app\common\models\member\MemberParent;
use app\common\models\Order;
use app\common\models\OrderGoods;
use Illuminate\Contracts\Events\Dispatcher;
use Yunshop\TeamDividend\models\TeamDividendAgencyModel;
use Yunshop\VirtualCard\model\VirtualData;
use Yunshop\VirtualCard\model\VirtualOrder;
use Yunshop\VirtualCardTeam\common\models\Activity;
use Yunshop\VirtualCardTeam\common\models\ExpectCard;
use Yunshop\VirtualCardTeam\common\models\GiveLog;
use Yunshop\VirtualCardTeam\common\models\ParticipateIn;

class AfterOrderPaidListener
{
    public function subscribe(Dispatcher $events)
    {

        $events->listen(AfterOrderPaidEvent::class, static::class . "@handle");
    }

    public function handle(AfterOrderPaidEvent $event)
    {
        $order = Order::uniacid()->find($event->getOrderModel()->id);

        $set = \Setting::get('plugin.virtual-card-team');
        $goods_ids = [];
        //获取member_goods_list里的ID
        foreach ($set['member_goods_list'] as $goods) {
            $goods_ids[] = $goods['goods_id'];
        }
        if (!$order) {
            return;
        }
        $order_goods = OrderGoods::uniacid()->where('order_id', $order->id)->get()->toArray();
        $give_num = 0;
        foreach ($order_goods as $goods) {
            if (in_array($goods['goods_id'], $goods_ids)) {
                $give_num += $goods['total'];
            }
        }
        if (!$give_num) {
            \Log::debug('虚拟卡未赠送商品' . $order->id);
            return;
        }

        $parent_member = MemberParent::uniacid()->where('member_id', $order->uid)
            ->orderBy('level', 'asc')
            ->get()
            ->toArray();
        $agent = null;
        foreach ($parent_member as $key => $value) {
            $agent = TeamDividendAgencyModel::uniacid()->where('uid', $value['parent_id'])
                ->where('activity_id', $set['activity_id'])
                ->first();
            if ($agent) {
                break;
            }
        }
        if (!$agent) {
            \Log::debug('经销商虚拟卡密没有赠送上级' . $order->id);
        }
        $expect_card = ExpectCard::uniacid()->where('member_id', $order->uid)->first();
        if (!$expect_card || $expect_card->card_num <= $expect_card->card_num_give) {
            \Log::debug('经销商虚拟卡密没有可赠送预计卡密' . $order->id);
            return;
        }

        if ($expect_card->card_num > $expect_card->card_num_give + $give_num) {
            $give_num = $expect_card->card_num - $expect_card->card_num_give;
        }

        $expect_card->card_num_give += $give_num;
        $virtual_order = VirtualOrder::uniacid()->first();
        if (!$virtual_order) {
            $virtual_order = new VirtualOrder();
        }

        $agent_order = Order::uniacid()->find($expect_card->order_id);
        if (!$agent_order) {
            \Log::debug('经销商虚拟卡密没有赠送订单' . $order->id);
            return;
        }
        $virtual_order_data = [
            'uniacid' => \YunShop::app()->uniacid,
            'order_id' => $agent_order->id,
            'member_id' => $agent_order->uid,
            'order_sn' => $agent_order->order_sn,
            'order_price' => $agent_order->price,
            'status' => 1,
        ];
        $virtual_order->fill($virtual_order_data);

        $virtual_data = VirtualData::uniacid()->where('type_id', $set['virtual_card_type_id'])
            ->whereNull('order_id')
            ->whereNull('owner_id')
            ->where('status', 0)
            ->get()
            ->toArray();
        $virtual_data_num = count($virtual_data);
        if (!$virtual_data_num) {
            \Log::debug('虚拟卡密没有可赠送卡密' . $order->id);
            return;
        }
        if ($give_num > $virtual_data_num) {
            $give_num = $virtual_data_num;
        }
        $datas = VirtualData::getSongData($set['virtual_card_type_id'], $give_num)->get();

        //该类型没有设置卡密数据
        if ($datas->isEmpty()) {
            \Log::debug('虚拟卡密没有可赠送卡密' . $order->id);
            return;
        }
        $save_data = [
            'order_id' => $$agent_order->id,
            'owner_id' => $agent_order->uid,
            'status' => 1,
        ];


        try {
            \DB::beginTransaction();
            $log = [];
            foreach ($datas as $card_data) {
                $save_data['usetime'] = time();

                $card_data->fill($save_data);

                $card_data->save();
                $log[] = [
                    'uniacid' => \YunShop::app()->uniacid,
                    'order_id' => $agent_order->id,
                    'member_id' => $agent_order->uid,
                    'virtual_data_id' => $card_data->id,
                    'type' => 1,
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
            }
            $virtual_order->save();
            $expect_card->save();
            GiveLog::insert($log);
            \DB::commit();
        } catch (\Exception $e) {
            \Log::debug('虚拟卡密赠送失败' . $e->getMessage());
            \DB::rollBack();
        }
    }


}
