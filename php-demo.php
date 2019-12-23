<?php
/*
 *1. 以下是有关支付的方法，签名方式等可以进行参考，具体的业务逻辑实现还需要参考文档，有不懂的可以和收钱吧技术人员确认。
 *2. 请求支付后，订单的状态信息通过轮询的方式获取。
 * */
//激活接口
function activate($vendor_sn, $vendor_key)
{
    $api_domain = 'https://api.shouqianba.com';
    $url = $api_domain .'/terminal/activate';

    $params['app_id'] = '';    //app id，从服务商平台获取
    $params['code'] = '';              //激活码内容
    $params['device_id'] = '';                 //设备唯一身份ID

    $ret = pre_do_execute($params, $url, $vendor_sn, $vendor_key);

    return $ret;

}



//签到接口
function checkin($terminal_sn, $terminal_key)
{
    $api_domain = 'https://api.shouqianba.com';
    $url = $api_domain . '/terminal/checkin';

    $params['terminal_sn'] = $terminal_sn;       //终端号
    $params['device_id'] = '';                //设备唯一身份ID


    $ret = pre_do_execute($params, $url, $terminal_sn, $terminal_key);
    return $ret;
}



//预下单接口
function precreate($terminal_sn, $terminal_key)
{
    $api_domain = 'https://api.shouqianba.com';
    $url = $api_domain . '/upay/v2/precreate';

    $params['terminal_sn'] = $terminal_sn;      //收钱吧终端ID
    //$params['sn']='';         //收钱吧系统内部唯一订单号
    $params['client_sn'] = '';  //商户系统订单号,必须在商户系统内唯一；且长度不超过64字节
    $params['total_amount'] = '';             //交易总金额
    $params['payway']='';
    $params['subject'] = '';              //本次交易的概述
    $params['operator'] = '';             //发起本次交易的操作员
    $params['sub_payway']='';           //内容为数字的字符串，如果要使用WAP支付，则必须传 "3", 使用小程序支付请传"4"
    //$params['payer_uid']='';          //消费者在支付通道的唯一id,微信WAP支付必须传open_id,支付宝WAP支付必传用户授权的userId
    //$params['description']='';           //对商品或本次交易的描述
    //$params['longitude']='';             //经纬度必须同时出现
    //$params['latitude']='';              //经纬度必须同时出现
    //$params['extended']='';              //收钱吧与特定第三方单独约定的参数集合,json格式，最多支持24个字段，每个字段key长度不超过64字节，value长度不超过256字节
    //$params['goods_details']='';         //商品详情
    //$params['reflect']='';               //任何调用者希望原样返回的信息
    //$params['notify_url']='';     //支付回调的地址
    $ret = pre_do_execute($params, $url, $terminal_sn, $terminal_key);
    return $ret ;
}



//支付接口
function  pay($terminal_sn, $terminal_key)
{
    $api_domain = 'https://api.shouqianba.com';  //收钱吧服务器域名
    $url = $api_domain . "/upay/v2/pay";

    $params['terminal_sn'] = $terminal_sn;              //终端号
    $params['client_sn'] = getClient_Sn(16); //商户系统订单号,必须在商户系统内唯一；且长度不超过64字节
    $params['total_amount'] = '';                      //交易总金额,以分为单位
    //$params['payway'] = '1';                          //支付方式,1:支付宝 3:微信 4:百付宝 5:京东钱包
    $params['dynamic_id'] = '';       //条码内容（支付包或微信条码号）
    $params['subject'] = '';                        //交易简介
    $params['operator'] = '';                        //门店操作员

    //$params['description']='';                        //对商品或本次交易的描述
    //$params['longitude']='';                          //经度(经纬度必须同时出现)
    //$params['latitude']='';                           //纬度(经纬度必须同时出现)
    //$params['device_id']='';                          //设备指纹
    //$params['extended']='';                           //扩展参数集合  { "goods_tag": "beijing"，"goods_id":"1"}
    //$params['goods_details']='';                      //商品详情 goods_details": [{"goods_id": "wx001","goods_name": "苹果笔记本电脑","quantity": 1,"price": 2,"promotion_type": 0}]
    //$params['reflect']='';                            //反射参数
    //$params['notify_url']='';                         //支付回调地址(如果支付成功通知时间间隔为1s,5s,30s,600s)

    $ret = pre_do_execute($params, $url, $terminal_sn, $terminal_key);
    return $ret;
}



//退款接口
function refund($terminal_sn, $terminal_key)
{
    $api_domain = 'https://api.shouqianba.com';
    $url = $api_domain . '/upay/v2/refund';

    $params['terminal_sn'] = $terminal_sn;       //收钱吧终端ID
    $params['sn'] = '';        //收钱吧系统内部唯一订单号（N）
    //$params['client_sn']='';   //商户系统订单号,必须在商户系统内唯一；且长度不超过64字节
    //$params['client_tsn']='';  //商户退款流水号一笔订单多次退款，需要传入不同的退款流水号来区分退款，如果退款请求超时，需要发起查询，并根据查询结果的client_tsn判断本次退款请求是否成功
    $params['refund_amount'] = '';              //退款金额
    $params['refund_request_no'] = '';        //商户退款所需序列号,表明是第几次退款(正常情况不可重复，意外状况爆出不变)
    $params['operator'] = '';                 //门店操作员
    //$params['extended'] = '';                    //扩展参数集合
    //$params['goods_details'] = '';               //商品详情

    $ret = pre_do_execute($params, $url, $terminal_sn, $terminal_key);
    return $ret;
}


//查询接口
function query($terminal_sn, $terminal_key)
{
    $api_domain = 'https://api.shouqianba.com';
    $url = $api_domain . '/upay/v2/query';

    $params['terminal_sn'] = $terminal_sn;      //收钱吧终端ID
    $params['sn']='';         //收钱吧系统内部唯一订单号
    //$params['client_sn'] = '';    //商户系统订单号,必须在商户系统内唯一；且长度不超过64字节

    $ret = pre_do_execute($params, $url, $terminal_sn, $terminal_key);
    return $ret;
}


//wap api pro 接口
function wap_api_pro($terminal_sn, $terminal_key)
{
    $params['terminal_sn'] = $terminal_sn;     //收钱吧终端ID
    $params['client_sn'] = '';    //商户系统订单号,必须在商户系统内唯一；且长度不超过64字节
    $params['total_amount'] = '';             //以分为单位,不超过10位纯数字字符串,超过1亿元的收款请使用银行转账
    $params['subject'] = '';              //本次交易的概述
    //$params['payway']='1';
    $params['notify_url'] = '';   //支付回调的地址
    $params['operator'] = '';                    //发起本次交易的操作员
    $params['return_url'] = "";  //处理完请求后，当前页面自动跳转到商户网站里指定页面的http路径

    ksort($params);  //进行升序排序


    $param_str = "";
    foreach ($params as $k => $v) {
        $param_str .= $k .'='.$v.'&';
    }

    $sign = strtoupper(md5($param_str . 'key=' . $terminal_key));

    $paramsStr = $param_str . "sign=" . $sign;
    $res = "https://qr.shouqianba.com/gateway?" . $paramsStr;

    //将这个url生成二维码扫码或在微信链接中打开可以完成测试
    file_put_contents('logs/wap_api_pro_' . date('Y-m-d') . '.txt', $res, FILE_APPEND);
}



function pre_do_execute($params, $url, $terminal_sn, $terminal_key)
{
    $j_params = json_encode($params);
    $sign = getSign($j_params . $terminal_key);
    $result = httpPost($url, $j_params, $sign, $terminal_sn);
    return $result;
}

function getClient_Sn($codeLenth)  //获得订单号
{
    $str_sn = '';
    for ($i = 0; $i < $codeLenth; $i++)
    {
        if ($i == 0)
            $str_sn .= rand(1, 9); // first field will not start with 0.
        else
            $str_sn .= rand(0, 9);
    }
    return $str_sn;
}

function getSign($signStr)   //签名
{
    $md5 = Md5($signStr);
    return $md5;
}
function httpPost($url, $body, $sign, $sn)   //头部请求规则
{
    $header = array(
        "Format:json",
        "Content-Type: application/json",
        "Authorization:$sn" . ' ' . $sign
    );
    $result = do_execute($url, $body, $header);
}

function do_execute($url, $postfield, $header)
{
    //    var_dump($url);echo '<br>';
    //    var_dump($postfield);echo '<br>';
    //    var_dump($header);echo '<br>';exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    $response = curl_exec($ch);
    //var_dump(curl_error($ch));  //查看报错信息
    file_put_contents('logs/web_api_' . date('Y-m-d') . '.txt', date("Y-m-d H:i:s", time()) . "===" . "返回：" . $response . "\n" . "请求应用参数：" . $postfield . "\n" . "\n" . "请求url：" . $url . "\n", FILE_APPEND);
    var_dump($url);
    echo '<br>';
    var_dump($response);
    exit;
    curl_close($ch);
    return $response;
}




