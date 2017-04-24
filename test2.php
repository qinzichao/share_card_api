<?php 

@require_once dirname(__FILE__)."/includes/init.php";



//==========发送通知开始=========
$form_id="c032926406ac1e57e0b69f8ff9b9fa5e";
$openid="oL60Z0XXgqIYrStaVFoy8OUECHWM";
$openid2="oL60Z0ZFOHHsG80r9vuR_in-IIJc";//测试发送给自已先

$my=user_get($db, array("openid"=>$openid));
//exit(print_r($my));

if($my&&$form_id&&$form_id!="the formId is a mock one"){

	//获取访问令牌
	require_once dirname(__FILE__)."/Service/Common/Cache.php";
	require_once dirname(__FILE__)."/Service/WeixinPush/BaseService.php";
	require_once dirname(__FILE__)."/Service/WeixinPush/CardCollect.php";
	$service=new \Service\WeixinPush\CardCollect();
	$service->setData($my["nickname"], $my["company"], $my["job"], $my["more_info"])->push($openid,$openid2, $form_id);

}
//==========发送通知结束=========

