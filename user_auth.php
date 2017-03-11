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

if(!isset($data["code"])||!$data["code"]){
	ajaxReturn(array(
	"code"=>0,
	"msg"=>"code不能为空",
	"data"=>array(),
	));
}

$auth_info=user_auth($db, $data);

if($auth_info){	
	ajaxReturn(array(
	"code"=>1,
	"msg"=>"成功",
	"data"=>$auth_info,
	));
}else{
	ajaxReturn(array(
	"code"=>0,
	"msg"=>"失败",
	"data"=>array(),
	));
}