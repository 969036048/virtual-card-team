<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYzVirtualCardTeamCardLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('yz_virtual_card_team_card_log')) {
            Schema::create('yz_virtual_card_team_card_log', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('uniacid')->default(0)->comment('公众号ID')->index('idx_uniacid');
                $table->integer('member_id')->nullable()->comment('会员ID');
                $table->integer('virtual_data_id')->nullable()->comment('虚拟卡密ID');
                $table->tinyInteger('type')->nullable()->comment('发放类型 1:购买商品 2:后台发放');
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
        Schema::dropIfExists('yz_virtual_card_team_card_log');
    }
}
