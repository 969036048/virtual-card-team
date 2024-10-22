<?php

namespace Yunshop\VirtualCardTeam\services;


class SmsServices
{
    public static function sendSms($mobile)
    {
        $set = \Setting::get('plugin.check_in_rebate');
        $sms = \Setting::get('shop.sms');
        if (!$sms['aly_appkey'] || !$sms['aly_secret'] || !$sms['aly_signname']) {
            \Log::debug('---短信配置错误', $sms);
        }
        $aly_sms = new \app\common\services\aliyun\AliyunSMS(trim($sms['aly_appkey']), trim($sms['aly_secret']));

        $response = $aly_sms->sendSms(
            $sms['aly_signname'], // 短信签名
            $set['check_in_rebate_aliyun_template'], // 短信模板编号
            $mobile, // 短信接收者
        );
        if ($response->Code != 'OK' || $response->Message != 'OK') {
            \Log::debug( '---短信发送失败', $response->Message);
        }
    }
}