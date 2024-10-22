<?php

namespace Yunshop\VirtualCardTeam\admin;

use app\common\components\BaseController;
use app\common\facades\Setting;
use app\common\models\Goods;
use Yunshop\TeamDividend\models\TeamDividendLevelUpgrade;
use Yunshop\VirtualCard\model\VirtualType;

class BasicController extends BaseController
{
    public function index()
    {
        return view('Yunshop\VirtualCardTeam::basic.index')->render();
    }

    public function getData()
    {
        $set = Setting::get('plugin.virtual-card-team');
        $virtual_type = VirtualType::select('id','field_name')->uniacid()->whereHas('hasManyData')->get()->makeHidden('hasManyData')->toArray();
        return $this->successJson('ok', [
            'set' =>$set,
            'virtual_type' => $virtual_type
        ]);
    }

    public function set()
    {
        $save_set = request()->form_data;
        try {
            Setting::set('plugin.virtual-card-team', $save_set);
            return $this->successJson('保存成功', $save_set);
        } catch (\Exception $e) {
            return $this->errorJson($e->getMessage());
        }

    }

    public function getGoodsList()
    {
        $query = Goods::uniacid()->select('id', 'title', 'thumb');

        if (request()->keyword) {
            $query->where('title', 'like', '%' . request()->keyword . '%');
        }
        $list = $query->paginate();

        $list->each(function ($item) {
            $item->thumb = yz_tomedia($item->thumb);
        });
        return $this->successJson('ok', $list);
    }


}