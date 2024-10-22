<?php

namespace Yunshop\EventRegistration\common\services;

use app\common\services\member\center\BaseMemberCenterService;

class MemberCenterService extends BaseMemberCenterService
{

    public function getData(): array
    {
        $data = [];
        $set = \Setting::get('plugin.check_in_rebate');


        if (app('plugins')->isEnabled('check-in-rebate') && $set['is_open']) {
            $data[] = [
                'name' => 'check_in_rebate',
                'title' => CHECK_IN_REBATE_PLUGIN_NAME,
                'class' => 'icon-fontclass-mendianxaiofeika1',
                'url' => 'gameUser',
                'image' => 'check-in-rebate.png',
                'mini_url' => '/mircoApp/competitionApply/gameUser/gameUser',
                'type_1' => 'interactive',
                'type_2' => 'market',
                'weight_1' => 12300,
                'weight_2' => 2900,
            ];

        }
        return $data;
    }

}
