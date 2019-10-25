<?php
namespace Sms;

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

// 加载区域结点配置
Config::load();

/**
 * Class SmsDemo
 *
 * 这是短信服务API产品的DEMO程序，直接执行此文件即可体验短信服务产品API功能
 * (只需要将AK替换成开通了云通信-短信服务产品功能的AK即可)
 * 备注:Demo工程编码采用UTF-8
 */
Class AliSms
{

    protected $acsClient = null;

    public function __construct($access_key, $access_secret, $sign_name, $code_template_id)
    {
        $this->access_key = $access_key;
        $this->access_secret = $access_secret;
        $this->sign_name = $sign_name;
        $this->code_template_id = $code_template_id;
    }

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public function getAcsClient() 
    {
        //产品名称:云通信短信服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        // $accessKeyId = config('alisms.access_key'); // AccessKeyId
        $accessKeyId = $this->access_key; // AccessKeyId
        // $accessKeySecret = config('alisms.access_secret'); // AccessKeySecret
        $accessKeySecret = $this->access_secret;

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if($this->acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            $this->acsClient = new DefaultAcsClient($profile);
        }
        return $this->acsClient;
    }

    /**
     * 发送短信
     * @return stdClass
     */
    public  function sendSms($phone, $code) 
    {
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phone);
        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        // $request->setSignName(config('alisms.sign_name'));
        $request->setSignName($this->sign_name);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        // $request->setTemplateCode(config('alisms.code_template_id'));
        $request->setTemplateCode($this->code_template_id);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
            "code" => $code,
        ), JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
//        $request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
//        $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);
        return json_encode($acsResponse,true);
    }

    public function sendNotice($phone, $TemplateParam) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phone);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        // $request->setSignName(config('alisms.sign_name'));
        $request->setSignName($this->sign_name);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        // $request->setTemplateCode(config('alisms.code_template_id'));
        $request->setTemplateCode($this->code_template_id);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode($TemplateParam
        ), JSON_UNESCAPED_UNICODE);

        // // 可选，设置流水号
        // $request->setOutId("yourOutId");

        // // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        // $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);

        return json_encode($acsResponse,true);
    }
}
