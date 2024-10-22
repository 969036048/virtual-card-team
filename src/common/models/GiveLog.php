<?php

namespace Yunshop\VirtualCardTeam\common\models;

use app\common\models\BaseModel;
use Yunshop\VirtualCard\model\VirtualType;

class GiveLog extends BaseModel
{

    protected $table = 'yz_virtual_card_team_card_log';
    protected $guarded = [''];

    public function hasVirtualCard()
    {
        return $this->hasOne(VirtualType::class, 'id', 'card_type_id');
    }

    public function member()
    {
        return $this->hasOne(\app\common\models\Member::class, 'uid', 'member_id');
    }

    public function order()
    {
        return $this->hasOne(\app\common\models\Order::class, 'id', 'order_id');
    }
    public function scopeSearch($query, $search)
    {
        $query->with([
            'hasVirtualCard' => function ($query) {
                $query->select('id', 'name');
            },
            'member' => function ($query) {
                $query->select('uid', 'nickname', 'realname', 'mobile', 'avatar');
            },
            'order' => function ($query) {
                $query->select('id', 'order_sn');
            }
        ]);
        if ($search['member']) {
            $query->whereHas('member', function ($query) use ($search) {
                $query->where('nickname', 'like', '%' . $search['member'] . '%')
                    ->orWhere('realname', 'like', '%' . $search['member'] . '%')
                    ->orWhere('mobile', 'like', '%' . $search['member'] . '%')
                    ->orWhere('uid', 'like', '%' . $search['member'] . '%');
            });
        }

        if ($search['time'] && is_numeric($search['time'][0]) && is_numeric($search['time'][1])) {
            $query->whereBetween('created_at', [$search['time'][0]/1000, $search['time'][1]/1000]);
        }
    }
}