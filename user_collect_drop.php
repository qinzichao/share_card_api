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

$result=user_collect_drop($db, $data);

if($result){	
	ajaxReturn(array(
	"code"=>1,
	"msg"=>"保存成功",
	"data"=>$result,
	));
}else{
	ajaxReturn(array(
	"code"=>0,
	"msg"=>"保存失败",
	"data"=>array(),
	));
}