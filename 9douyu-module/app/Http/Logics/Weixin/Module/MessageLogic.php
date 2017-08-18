<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/9/7
 * Time: 下午3:44
 */

namespace App\Http\Logics\Weixin\Module;

use EasyWeChat\Message\News;

use EasyWeChat\Message\Text;

use Log;
/**
 * 消息
 *
 * Class MessageLogic
 * @package App\Http\Logics\Weixin\Module
 */
class MessageLogic
{


    const
        TEMPLATE_MESSAGE_DEFAULT = 100, //模板消息类型


    END = true;


    /**
     * 单图文消息
     *
     * @param array $record
     * @return News
     */
    public static function imageText($record = []){
        $news = new News($record);
        return $news;
    }


    /**
     * 文本消息
     *
     * @param array $record
     * @return Text
     */
    public static function text($record = []){
        $text = new Text($record);
        return $text;
    }


    /**
     * 获取模板消息id
     *
     * @param int $code
     * @return mixed
     * @throws \Exception
     */
    private static function getTemplate($code = 100){
        $data = [
            self::TEMPLATE_MESSAGE_DEFAULT => 'PGx0Q7QCs_wcT6mSGOc4Js2ziQ2yXujsGmQn2MiNhq0',//对应的模板id
        ];

        if(empty($data[$code])){
            throw new \Exception('找不到对应的微信模板');
        }

        return $data[$code];
    }

    /**
     * 发送模板消息
     *
     * @param array $param
     * @return bool
     */
    public static function sendTemplateMessage($param = []){
        try {
            $apiData = [
                'touser'        => $param['openId'],
                'template_id'   => self::getTemplate($param['type']),
                'url'           => isset($param['url']) ? $param['url'] : '',
                'topcolor'      => '#f7f7f7',
                'data'          => isset($param['data']) ? $param['data'] : [],
            ];

            $wechat        = app('wechat');
            $notice       = $wechat->notice;

            \Log::info(__METHOD__ .'api数据：', $apiData);

            $messageId = $notice->send($apiData);

            \Log::info(__METHOD__ . '发送结果', [$messageId]);

            return $messageId;

        }catch (\Exception $e){

            \Log::info(__METHOD__  . '发送异常：', [$e->getLine(), $e->getMessage()]);
        }
        return false;
    }
}