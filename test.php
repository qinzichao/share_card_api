<?php 

@require_once dirname(__FILE__)."/includes/init.php";


//获取访问令牌
require_once dirname(__FILE__)."/Service/Common/Cache.php";
require_once dirname(__FILE__)."/Service/WeixinPush/BaseService.php";
require_once dirname(__FILE__)."/Service/WeixinPush/CardCollect.php";
$service=new \Service\WeixinPush\CardCollect();
// $service->setData("测试", "公司", "职位", "描述")->push("oL60Z0ZFOHHsG80r9vuR_in-IIJc", "c032926406ac1e57e0b69f8ff9b9fa5e");

$form_id=isset($_REQUEST["form_id"])&&$_REQUEST["form_id"]?$_REQUEST["form_id"]:"c032926406ac1e57e0b69f8ff9b9fa5e";

$service->setData("测试", "公司", "职位", "描述")->push("oL60Z0ZFOHHsG80r9vuR_in-IIJc", $form_id);


