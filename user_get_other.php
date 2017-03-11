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

//更新查看记录
$result=user_view_add($db, $data);

//获取那个人信息
$user=user_get_other($db, $data);

if($user){	
	ajaxReturn(array(
	"code"=>1,
	"msg"=>"成功",
	"data"=>$user,
	));
}else{
	ajaxReturn(array(
	"code"=>0,
	"msg"=>"失败",
	"data"=>null,
	));
}