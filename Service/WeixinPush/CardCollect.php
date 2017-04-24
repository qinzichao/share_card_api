<?php

namespace Service\WeixinPush;


/**
 * 推送签到
 * @author qinzichao
 *
 */
class CardCollect extends BaseService
{

    /**
     * 模版ID
     * @var unknown
     */
    protected $_template_id = "-IrBcYoVy9Kf1MCtXdAZjYtnOm3CM24AobZjIT2ELKs";

    protected $data;

    /**
     * 发送自定义的模板消息
     * 
     * @param unknown $openid 关注者的openid
     * @param unknown $openid2 接收者的openid2
     * @param unknown $form_id 表单ID
     * @return boolean
     */
    public function push($openid,$openid2, $form_id)
    {
    	$template = [
            'touser'      => $openid2,
            'template_id' => $this->_template_id,
            'data'        => $this->getData(),
        	'page'=>'/pages/other/other?openid='.$openid,
        	'form_id'=>$form_id,
//         	'color'=>'#ccc',
			//'emphasis_keyword'=>'keyword1.DATA',
        ];

        $json_template = json_encode($template, JSON_UNESCAPED_UNICODE);
		//exit($json_template);
        $url = $this->wx_push_url."access_token=".$this->accessToken;
		
        $dataRes = $this->request_post($url, $json_template);
        //exit($dataRes);
        $dataRes = json_decode($dataRes, true);
        if ($dataRes ['errcode'] == 0) {
            return true;
        }

        return false;

    }

    /**
     * 获取数据
     * @return multitype:multitype:string unknown
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 设置数据
     * 
	    收藏人 {{keyword1.DATA}}
	    对方公司 {{keyword2.DATA}}
	    对方职位  {{keyword3.DATA}}
	    备注 {{keyword4.DATA}}
     */
    public function setData($keyword1, $keyword2, $keyword3, $keyword4)
    {
        $this->data = [
            'keyword1' => [ "value" => $keyword1, "color" => "#173177" ],
            'keyword2' => [ "value" => $keyword2, "color" => "#173177" ],
            'keyword3' => [ "value" => $keyword3, "color" => "#173177" ],
            'keyword4' => [ "value" => $keyword4, "color" => "#173177" ],
        ];

        return $this;
    }
}