<?php
@require_once dirname ( __FILE__ ) . "/includes/init.php";

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

$user_collect_list = user_collect_list ( $db, $data );

ajaxReturn ( array (
		"code" => 1,
		"msg" => "成功",
		"data" => $user_collect_list 
) );