<?php


/**
 * Http方法
 *
 */
function http($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$output = curl_exec($ch);//输出内容
	curl_close($ch);
	return array($output);
}

/**
 * Http方法
 *
 */
function vpost($url,$data){ // 模拟提交数据函数
	$curl = curl_init(); // 启动一个CURL会话
	curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
	// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	// curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	if(data){
		curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包x
	}
	curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
	$tmpInfo = curl_exec($curl); // 执行操作
	if (curl_errno($curl)) {
		echo 'Errno'.curl_error($curl);//捕抓异常
	}
	curl_close($curl); // 关闭CURL会话
	return $tmpInfo; // 返回数据
}

/**
 * Ajax方式返回数据到客户端
 * @access protected
 * @param mixed $data 要返回的数据
 * @param String $type AJAX返回数据格式
 * @param int $json_option 传递给json_encode的option参数
 * @return void
 */
 function ajaxReturn($data) {
	exit(json_encode($data));
}

/**
 * 保存用户数据
 * 
 * @param unknown $db
 * @param unknown $data
 */
function user_save(&$db,$data){
	$openid=$data["openid"];
	
	$sql="select * from ".DB_PREFIX."user where openid='{$openid}' ";
	$user=$db->getRow($sql);
	
	if($user){
		$db->autoExecute(DB_PREFIX."user",$data,"UPDATE"," openid='{$openid}'");
	}else{
		$data["add_time"]=time();
		$db->autoExecute(DB_PREFIX."user",$data);
	}
	
	$user=$db->getRow($sql);
	
	return $user;
}

/**
 * 获取用户数据
 *
 * @param unknown $db
 * @param unknown $data
 */
function user_get(&$db,$data){
	$openid=$data["openid"];

	$sql="select * from ".DB_PREFIX."user where openid='{$openid}' ";
	$user=$db->getRow($sql);

	return $user;
}

/**
 * 获取用户名片夹数据
 *
 * @param unknown $db
 * @param unknown $data
 */
function user_collect_list(&$db,$data){
	
	$openid=$data["openid"];

	$sql="select u.* from ".DB_PREFIX."user_collect u_c
	left join ".DB_PREFIX."user u on u_c.openid2=u.openid where u_c.openid='{$openid}' ";
	$user_list=$db->getAll($sql);

	return $user_list;
}

/**
 * 获取微信openid
 *
 * @param unknown $db
 * @param unknown $data
 */
function user_auth(&$db,$data){
	$code=$data["code"];
	$url="https://api.weixin.qq.com/sns/jscode2session?appid=".WEIXIN_AppID."&secret=".WEIXIN_AppSecret."&js_code=".$code."&grant_type=authorization_code";
	$output=file_get_contents($url);

	$openid="";
	if($output){
		$output=json_decode($output,true);
		if(isset($output["openid"])){
			$openid=$output["openid"];
		}
	}
	return array("openid"=>$openid);
}

/**
 * 添加喜欢
 *
 * @param unknown $db
 * @param unknown $data
 */
function user_love_add(&$db,$data){
	
	$openid=$data["openid"];
	$openid2=$data["openid2"];
	
	$sql="select * from ".DB_PREFIX."user_love where openid='{$openid}' and openid2='{$openid2}' ";
	$row=$db->getRow($sql);
	
	if($row){
		return false;
	}else{
		$data["add_time"]=time();
		
		$db->autoExecute(DB_PREFIX."user_love",$data);
		
		//更新的数据
		$sql="select count(id) from ".DB_PREFIX."user_love where openid2='{$openid2}'";
		$count=$db->getOne($sql);

		$sql="select * from ".DB_PREFIX."user where openid='{$openid2}' ";
		$user=$db->getRow($sql);
		if($user){
			$db->autoExecute(DB_PREFIX."user",array("loves"=>$count),"UPDATE"," openid='{$openid2}'");
		}
		return true;
	}
}

/**
 * 删除喜欢
 *
 * @param unknown $db
 * @param unknown $data
 */
function user_love_drop(&$db,$data){

	$openid=$data["openid"];
	$openid2=$data["openid2"];
	
	$sql="select * from ".DB_PREFIX."user_love where openid='{$openid}' and openid2='{$openid2}' ";
	$row=$db->getRow($sql);
	
	if($row){
		
		$sql="delete from ".DB_PREFIX."user_love where openid='{$openid}' and openid2='{$openid2}' ";
		$db->query($sql);
		
		$affected_rows=$db->affected_rows();
		if($affected_rows>0){
			
			//更新的数据
			$sql="select count(id) from ".DB_PREFIX."user_love where openid2='{$openid2}'";
			$count=$db->getOne($sql);
	
			$sql="select * from ".DB_PREFIX."user where openid='{$openid2}' ";
			$user=$db->getRow($sql);
			if($user){
				$db->autoExecute(DB_PREFIX."user",array("loves"=>$count),"UPDATE"," openid='{$openid2}'");
			}
		}
		return true;
	}else{
		return false;
	}
}



/**
 * 添加收藏
 *
 * @param unknown $db
 * @param unknown $data
 */
function user_collect_add(&$db,$data){

	$openid=$data["openid"];
	$openid2=$data["openid2"];

	$sql="select * from ".DB_PREFIX."user_collect where openid='{$openid}' and openid2='{$openid2}' ";
	$row=$db->getRow($sql);

	if($row){
		return false;
	}else{

		$data["add_time"]=time();
		
		$db->autoExecute(DB_PREFIX."user_collect",$data);

		//更新数据
		$sql="select count(id) from ".DB_PREFIX."user_collect where openid2='{$openid2}'";
		$count=$db->getOne($sql);
		
		$sql="select * from ".DB_PREFIX."user where openid='{$openid2}' ";
		$user=$db->getRow($sql);
		if($user){
			$db->autoExecute(DB_PREFIX."user",array("collects"=>$count),"UPDATE"," openid='{$openid2}'");
		}
		
		return true;
	}
}

/**
 * 删除喜欢
 *
 * @param unknown $db
 * @param unknown $data
 */
function user_collect_drop(&$db,$data){

	$openid=$data["openid"];
	$openid2=$data["openid2"];

	$sql="select * from ".DB_PREFIX."user_collect where openid='{$openid}' and openid2='{$openid2}' ";
	$row=$db->getRow($sql);
	
	if($row){

		$sql="delete from ".DB_PREFIX."user_collect where openid='{$openid}' and openid2='{$openid2}' ";
		$db->query($sql);

		$affected_rows=$db->affected_rows();
		if($affected_rows>0){
				
			//更新数据
			$sql="select count(id) from ".DB_PREFIX."user_collect where openid2='{$openid2}'";
			$count=$db->getOne($sql);

			$sql="select * from ".DB_PREFIX."user where openid='{$openid2}' ";
			$user=$db->getRow($sql);
			if($user){
				$db->autoExecute(DB_PREFIX."user",array("collects"=>$count),"UPDATE"," openid='{$openid2}'");
			}
		}
		return true;
	}else{
		return false;
	}
}

/**
 * 添加查看
 *
 * @param unknown $db
 * @param unknown $data
 */
function user_view_add(&$db,$data){

	$openid=$data["openid"];
	$openid2=$data["openid2"];

	$sql="select * from ".DB_PREFIX."user_view where openid='{$openid}' and openid2='{$openid2}' ";
	$row=$db->getRow($sql);

	if($row){
		return false;
	}else{
		$data["add_time"]=time();

		$db->autoExecute(DB_PREFIX."user_view",$data);

		//更新数据
		$sql="select count(id) from ".DB_PREFIX."user_view where openid2='{$openid2}'";
		$count=$db->getOne($sql);
		
		$sql="select * from ".DB_PREFIX."user where openid='{$openid2}' ";
		$user=$db->getRow($sql);
		if($user){
			$db->autoExecute(DB_PREFIX."user",array("views"=>$count),"UPDATE"," openid='{$openid2}'");
		}
		return true;
	}
}

/**
 * 获取其他用户数据
 *
 * @param unknown $db
 * @param unknown $data
 */
function user_get_other(&$db,$data){
	
	$openid=$data["openid"];
	
	$openid2=$data["openid2"];
	
	$sql="select * from ".DB_PREFIX."user where openid='{$openid2}' ";
	$user=$db->getRow($sql);
	
	$data=$user;
	$data["can_collect"]=0;
	$data["can_love"]=0;
	
	if($user){
		$sql="select * from ".DB_PREFIX."user_collect where openid='{$openid}' and openid2='{$openid2}' ";
		$row=$db->getRow($sql);
		$data["can_collect"]=$row?0:1;
		
		$sql="select * from ".DB_PREFIX."user_love where openid='{$openid}' and openid2='{$openid2}' ";
		$row=$db->getRow($sql);
		$data["can_love"]=$row?0:1;
	}

	return $data;
}

?>