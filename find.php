<?php
@require_once dirname ( __FILE__ ) . "/includes/init.php";

/**
 * 查找列表
 * 
 * @param unknown $db
 * @param unknown $data
 */
function findList($db,$data)
{
	$openid=$data["openid"];
	$sql="select * from ".DB_PREFIX."user where openid!='{$openid}' and mobile!='' and mobile!='undefined' and more_info!='' order by last_find_time asc limit 1 ";
	$row=$db->getRow($sql);
	if($row){
		
		$now=time();
		$db->autoExecute(DB_PREFIX."user",array("last_find_time"=>$now),"UPDATE"," id ={$row["id"]} ");
		
		//是否已喜欢过
		$sql="select count(id) from ".DB_PREFIX."user_love where openid='{$openid}' and openid2='{$row["openid"]}'";
		$love_count=$db->getOne($sql);
		$row["is_love"]=$love_count?"1":"0";
		
		//是否已收藏过
		$sql="select count(id) from ".DB_PREFIX."user_collect where openid='{$openid}' and openid2='{$row["openid"]}'";
		$collect_count=$db->getOne($sql);
		$row["is_collect"]=$collect_count?"1":"0";
	}

	return $row?$row:array();
}

$data = $_REQUEST;

if (! $data) {
	ajaxReturn ( array (
			"code" => 0,
			"msg" => "数据不能为空",
			"data" => array () 
	) );
}

if (! isset ( $data ["openid"] ) || ! $data ["openid"]) {
	ajaxReturn ( array (
			"code" => 0,
			"msg" => "openid不能为空",
			"data" => array () 
	) );
}

$list = findList( $db, $data );

ajaxReturn ( array (
		"code" => 1,
		"msg" => "获取成功",
		"data" => $list 
) );