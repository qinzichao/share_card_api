<?php

namespace Service\WeixinPush;


/**
 * 微信服务类：基类
 * 
 * @author qinzichao
 *
 */
class BaseService {
	
	protected $appid;
	protected $secrect;
	public $accessToken;
	
	//protected $wx_push_url="https://api.weixin.qq.com/cgi-bin/message/template/send?";
	protected $wx_push_url="https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?";
	
	function __construct() {
		
		$this->appid=WEIXIN_AppID;
		$this->secrect=WEIXIN_AppSecret;
		
		$this->accessToken = $this->getToken ( $this->appid, $this->secrect );
		//exit($this->accessToken);
		
	}
	
	/**
	 * 发送post请求
	 * 
	 * @param string $url        	
	 * @param string $param        	
	 * @return bool mixed
	 */
	protected function request_post($url = '', $param = '') {
		if (empty ( $url ) || empty ( $param )) {
			return false;
		}
		$postUrl = $url;
		$curlPost = $param;
		$ch = curl_init (); // 初始化curl
		curl_setopt ( $ch, CURLOPT_URL, $postUrl ); // 抓取指定网页
		curl_setopt ( $ch, CURLOPT_HEADER, 0 ); // 设置header
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // 要求结果为字符串且输出到屏幕上
		curl_setopt ( $ch, CURLOPT_POST, 1 ); // post提交方式
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $curlPost );
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$data = curl_exec ( $ch ); // 运行curl
		curl_close ( $ch );
		return $data;
	}
	
	/**
	 * 发送get请求
	 * 
	 * @param string $url        	
	 * @return bool mixed
	 */
	protected function request_get($url = '') {
		if (empty ( $url )) {
			return false;
		}
		$ch = curl_init (); // 初始化curl
		
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_HEADER,0); // 不要http header 加快效率
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$data = curl_exec ( $ch );
		curl_close ( $ch );
		return $data;
	}
	
	/**
	 *
	 * @param
	 *        	$appid
	 * @param
	 *        	$appsecret
	 * @return mixed 获取token
	 */
	protected function getToken($appid, $appsecret) {
		
		$key="WX_".$appid;//缓存的key

		$cache = new \Service\Common\Cache();
		$value = $cache->get($key);
		if ($value) {
			$access_token = $value;
		} else {
			
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $appsecret;
			$json = $this->request_get ( $url );
			$arr = json_decode ( $json, true );
			$access_token = $arr ['access_token'];

			$cache->put($key, $access_token, 120);
		}
		//exit($access_token);
		return $access_token;
	}
	
	
}