<?php
/**
 * Created by PhpStorm.
 * Name: 芸众商城系统
 * Author: 广州市芸众信息科技有限公司
 * Profile: 广州市芸众信息科技有限公司位于国际商贸中心的广州，专注于移动电子商务生态系统打造，拥有芸众社交电商系统、区块链数字资产管理系统、供应链管理系统、电子合同等产品/服务。官网 ：www.yunzmall.com  www.yunzshop.com
 * Date: 2022/12/6
 * Time: 19:07
 */

namespace Yunshop\LeaseToy\common\order\type;

use app\common\models\refund\RefundApply;
use app\common\modules\refund\product\RefundOrderTypeBase;
use Yunshop\LeaseToy\models\LeaseOrderModel;

class VirtualCardTeamRefundOrderType extends RefundOrderTypeBase
{
    public function isBelongTo()
    {
        return $this->order->plugin_id == 40;
    }



    public function handleAfterSales(RefundApply $refundApply)
    {
       $refundApply->price = $this->order->price;
       $refundApply->freight_price = $this->getOrderFreightPrice();
       $refundApply->other_price = $this->getOrderOtherPrice();
    }


    //订单其他费用退款
    public function getOrderOtherPrice()
    {
        $leaseOrder = $this->getLeaseOrder();
        $deposit_total = $leaseOrder->deposit_total?:0;
        return $this->order->fee_amount + $this->order->service_fee_amount + $deposit_total;
    }



    public function getLeaseOrder()
    {
        $leaseOrder = LeaseOrderModel::uniacid()->where('order_id', $this->order->id)->first();


        return $leaseOrder;
    }


    public function multipleRefund()
    {
        return false;
    }

    public function applyNumberLimit()
    {
        return false;
    }

    public function applyBeforeValidate()
    {

    }
}