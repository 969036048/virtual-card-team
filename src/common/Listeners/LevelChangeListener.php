<?php


namespace Yunshop\VirtualCardTeam\common\Listeners;
use app\common\models\Order;
use app\common\models\OrderGoods;
use Illuminate\Contracts\Events\Dispatcher;

use Yunshop\TeamDividend\events\LevelChangeEvent;
use Yunshop\TeamDividend\models\TeamDividendAgencyModel;
use Yunshop\TeamDividend\models\TeamDividendLevelModel;
use Yunshop\TeamDividend\models\TeamDividendLevelUpgrade;
use Yunshop\TeamDividend\models\Uplog;
use Yunshop\VirtualCardTeam\common\models\ExpectCard;

class LevelChangeListener
{
    private $agencyModel;

    public function subscribe(Dispatcher $events)
    {
        $events->listen(LevelChangeEvent::class,self::class . '@handle');
    }

    public function handle($events)
    {
        if (!app('plugins')->isEnabled('team-dividend')) {
            return;
        }
        $set  = \Setting::get('plugin.virtual-card-team');

        if (!$set['is_open'] || empty($set['goods_list']) || empty($set['member_goods_list'])) {
            return;
        }
        $this->agencyModel = $events->getDealerModel();

        $agent = TeamDividendAgencyModel::uniacid()
            ->find($this->agencyModel->id);
        //判断是否为成为经销商，而不是升级,不是则退出
        $is_create = Uplog::uniacid()
            ->where('uid', $agent->uid)
            ->first();
        if ($is_create) {
            \Log::debug('经销商虚拟卡密，不是成为经销商，不赠送虚拟卡密' . $agent->uid);
            return;
        }

        $level = TeamDividendLevelModel::uniacid()
            ->orderBy('level_weight', 'asc')
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        $level_upgrade = TeamDividendLevelUpgrade::uniacid()
            ->where('dividend_id', $level[0]['id'])
            ->first();
        if (!$level_upgrade) {
            \Log::debug('虚拟卡升级未设置升级条件' . $agent->uid);
            return;
        }
        $level_upgrade = $level_upgrade->toArray();
        $level_set = unserialize($level_upgrade['parase']);
        $goods_ids = array_merge([$level_set[0]['goods']], $level_set[1]['many_good']);
        $is_give = false;
        $member_goods_list = $set['member_goods_list'];
        $order_id = 0;
        $goods_id = 0;
        foreach ($member_goods_list as $key => $value) {
            if (in_array($value['goods_id'], $goods_ids)) {

                $order_goods = OrderGoods::uniacid()->where('goods_id', $value['goods_id'])
                            ->where('uid', $agent->uid)
                            ->first();
                $order_id = $order_goods->order_id;
                $order = Order::uniacid()
                    ->find($order_id);
                if ($order_goods && in_array($order->status, [1, 2, 3])) {
                    $is_give = true;
                    $goods_id = $value['goods_id'];

                }
                break;
            }
        }
        if (!$is_give) {
            \Log::debug('虚拟卡升级未赠送商品' . $agent->uid);
            return;
        }

        $save_data = [
            'uniacid' => \YunShop::app()->uniacid,
            'goods_id' => $goods_id,
            'order_id' => $order_id,
            'card_type_id' => $set['card_type_id'],
            'card_num' => $set['card_num'],
            'card_num_give' => 0,
            'member_id' => $agent->uid,
        ];
        try {
            ExpectCard::create($save_data);
            \Log::debug('经销商虚拟卡赠送预计卡密成功' . $agent->uid);
        } catch (\Exception $e) {
            \Log::debug('经销商虚拟卡赠送预计卡密失败' . $agent->uid, $e->getMessage());
        }
    }
}