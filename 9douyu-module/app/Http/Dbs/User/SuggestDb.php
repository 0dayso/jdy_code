<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/6
 * Time: 14:53
 */
namespace  App\Http\Dbs\User;

use App\Http\Dbs\JdyDb;

class SuggestDb extends JdyDb{

    protected $table = 'suggest';


    const STATUS_UNDONE        = 100,//未处理
          STATUS_DONE          = 200;//已处理

    /**
     * @param $data
     * 添加反馈意见
     */
    public function add($data){

        $this->user_id  = $data['user_id'];
        $this->content  = $data['content'];
        $this->dev_info = $data['dev_info'];

        $this->save();
    }
}