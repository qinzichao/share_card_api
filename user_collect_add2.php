<?php 

@require_once dirname(__FILE__)."/includes/init.php";


$data=$_REQUEST;

if(!$data){
	ajaxReturn(array(
	"code"=>0,
	"msg"=>"数据不能为空",
	"data"=>array(),
	));
}

if(!isset($data["openid"])||!$data["openid"]){
	ajaxReturn(array(
	"code"=>0,
	"msg"=>"openid不能为空",
	"data"=>array(),
	));
}

if(!isset($data["openid2"])||!$data["openid2"]){
	ajaxReturn(array(
	"code"=>0,
	"msg"=>"openid2不能为空",
	"data"=>array(),
	));
}

if($data["openid"]==$data["openid2"]){
	ajaxReturn(array(
	"code"=>0,
	"msg"=>"自已不能收藏自已",
	"data"=>array(),
	));
}

$result=user_collect_add($db, $data);

//==========发送通知开始=========
$form_id=isset($_REQUEST["form_id"])&&$_REQUEST["form_id"]?$_REQUEST["form_id"]:"c032926406ac1e57e0b69f8ff9b9fa5e";
$openid=$data["openid"];
$openid2=$data["openid2"];
// $openid2="oL60Z0ZFOHHsG80r9vuR_in-IIJc";//测试发送给自已先

$my=user_get($db, array("openid"=>$openid));

if($my&&$form_id){

	//获取访问令牌
	require_once dirname(__FILE__)."/Service/Common/Cache.php";
	require_once dirname(__FILE__)."/Service/WeixinPush/BaseService.php";
	require_once dirname(__FILE__)."/Service/WeixinPush/CardCollect.php";
	$service=new \Service\WeixinPush\CardCollect();
	$service->setData($my["nickname"], $my["company"], $my["job"], $my["more_info"])->push($openid,$openid2, $form_id);

}
//==========发送通知结束=========


if($result){	
	

	ajaxReturn(array(
	"code"=>1,
	"msg"=>"保存成功",
	"data"=>array(),
	));
}else{
	ajaxReturn(array(
	"code"=>0,
	"msg"=>"保存失败",
	"data"=>array(),
	));
}