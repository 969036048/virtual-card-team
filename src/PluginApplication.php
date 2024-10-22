<?php


namespace Yunshop\VirtualCardTeam;

use Yunshop\VirtualCardTeam\common\Listeners\AfterOrderPaidListener;
use Yunshop\VirtualCardTeam\common\Listeners\LevelChangeListener;

class PluginApplication extends \app\common\services\PluginApplication
{
    protected function setMenuConfig()
    {
        \app\backend\modules\menu\Menu::current()->setPluginMenu('virtual-card-team',[
            'name' => '经销商虚拟卡密赠送',
            'type' => 'marketing',
            'url' => 'plugin.virtual-card-team.admin.basic.index',
            'urlParams' => '',
            'permit' => 1,
            'menu' => 1,
            'top_show'          => 0,
            'left_first_show'   => 0,
            'left_second_show'  => 1,
            'icon' => 'fa-credit-card',//菜单图标
            'list_icon' => 'virtual-card-team',
            'parents'=>[],
            'child' => [
                'plugin.virtual-card-team.admin.basic' => [
                    'name'      => '基础设置',
                    'permit'    => 1,
                    'menu'      => 1,
                    'icon'      => '',
                    'url'       => 'plugin.virtual-card-team.admin.basic.index',
                    'urlParams' => [],
                    'parents'   =>['virtual-card-team'],
                    'child'     => [
                        'plugin.virtual-card-team.admin.basic.set' => [
                            'name'      => '基础设置',
                            'permit'    => 1,
                            'menu'      => 1,
                            'icon'      => '',
                            'url'       => 'plugin.virtual-card-team.admin.basic.set',
                            'urlParams' => [],
                            'parents'   =>['virtual-card-team', 'plugin.virtual-card-team.admin.basic'],
                            'child'     => []
                        ],
                        'plugin.virtual-card-team.admin.basic.get-data' => [
                            'name'      => '设置数据',
                            'permit'    => 1,
                            'menu'      => 0,
                            'icon'      => '',
                            'url'       => 'plugin.virtual-card-team.admin.basic.get-data',
                            'urlParams' => [],
                            'parents'   =>['virtual-card-team', 'plugin.virtual-card-team.admin.basic'],
                            'child'     => []
                        ]
                    ]
                ],
                'plugin.virtual-card-team.admin.card' => [
                    'name'      => '预计虚拟卡密',
                    'permit'    => 1,
                    'menu'      => 1,
                    'icon'      => '',
                    'url'       => 'plugin.virtual-card-team.admin.card.index',
                    'urlParams' => [],
                    'parents'   =>['virtual-card-team'],
                    'child'     => [
                        'plugin.virtual-card-team.admin.card.get-data' => [
                            'name'      => '预计虚拟卡密列表数据',
                            'permit'    => 1,
                            'menu'      => 0,
                            'icon'      => '',
                            'url'       => 'plugin.virtual-card-team.admin.card.get-data',
                            'urlParams' => [],
                            'parents'   =>['virtual-card-team', 'plugin.virtual-card-team.admin.card'],
                            'child'     => []
                        ],
                        'plugin.virtual-card-team.admin.card.give-card' => [
                            'name'      => '手动发放虚拟卡密',
                            'permit'    => 1,
                            'menu'      => 0,
                            'icon'      => '',
                            'url'       => 'plugin.virtual-card-team.admin.card.give-card',
                            'urlParams' => [],
                            'parents'   =>['virtual-card-team', 'plugin.virtual-card-team.admin.card'],
                        ]
                    ]
                ],
            ]
        ]);
    }


    public function toPublishes()
    {
        $this->publishes(
            [
                //入口图标
                plugin_assets_path('virtual-card-team', 'image/virtual-card-team') => plugin_icon_path('event-registration.png'),
                //前端图标
                plugin_assets_path('virtual-card-team', 'image/event-registration-member.png') => entry_icon_path('event-registration-member.png'),
            ]
        );
    }

    public function register()
    {
        $set = \Setting::get('plugin.check_in_rebate');
        define('CHECK_IN_REBATE_PLUGIN_NAME', $set['plugin_name'] ?: "消费商品打卡返还");
    }
    public function boot()
    {

        $events = app('events');

        //订单付款
        $events->subscribe(AfterOrderPaidListener::class);

        //经销商等级监听
        $events->subscribe(LevelChangeListener::class);

    }


}
