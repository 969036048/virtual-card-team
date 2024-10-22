<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYzVirtualCardTeamRewardRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('yz_virtual_card_team_reward_record')) {
            Schema::create('yz_virtual_card_team_reward_record', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('uniacid')->default(0)->comment('公众号ID')->index('idx_uniacid');
                $table->integer('goods_id')->nullable()->comment('商品ID');
                $table->integer('order_id')->nullable()->comment('订单ID');
                $table->integer('card_type_id')->nullable()->comment('卡密类型');
                $table->integer('card_num')->nullable()->comment('预计卡密数量');
                $table->integer('card_num_give')->nullable()->comment('已发放卡密数量');
                $table->integer('member_id')->nullable()->comment('用户ID');
                $table->integer('created_at')->nullable();
                $table->integer('updated_at')->nullable();
            });
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE " . app('db')->getTablePrefix() . "yz_virtual_card_team_reward_record comment '经销商虚拟卡密--获取虚拟卡密列表表'");//表注释
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yz_virtual_card_team_reward_record');
    }
}
