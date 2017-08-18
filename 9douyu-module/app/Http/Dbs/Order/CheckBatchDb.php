<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/30
 * Time: 下午4:33
 */

namespace App\Http\Dbs\Order;


use App\Http\Dbs\JdyDb;

class CheckBatchDb extends JdyDb
{
    protected $table    =   'order_check_batch';
    


    const
        STATUS_PENDING      =   100,    //待审核状态
        STATUS_WAIT_CHECK   =   200,    //待对账的状态
        STATUS_SUCCESS      =   300,    //对账成功
        STATUS_ERROR        =   400,    //失败

        //快捷对账的通道标示
        RECHARGE_CBPAY_TYPE         = 1000, //网银在线充值标记
        RECHARGE_QDBPAY_AUTH_TYPE   = 1201, //钱袋宝代扣充值标记
        RECHARGE_YEEPAY_AUTH_TYPE   = 1102, //易宝认证充值标记
        RECHARGE_LLPAY_AUTH_TYPE    = 1101, //连连认证充值标记
        RECHARGE_BFPAY_AUTH_TYPE    = 1103,//宝付认证充值标记
        RECHARGE_UMP_AUTH_TYPE      = 1202, //联动优势充值标记
        RECHARGE_REAPAY_AUTH_TYPE   = 1204, //融宝支付充值标记
        RECHARGE_BEST_AUTH_TYPE     = 1203  //翼支付充值标记

    ;

    /**
     * @param $id
     * @return array
     * @desc 查询数据
     */
    public function getById( $id )
    {
        $result     =   self::find( $id );

        return $this->dbToArray($result);
    }

    /**
     * @param $id
     * @return array
     * @desc 可以对账的数据
     */
    public function getAdoptBatch( $id )
    {
        $result     =   $this->where("status",self::STATUS_WAIT_CHECK)->find($id);

        return  $this->dbToArray($result);
    }
    /**
     * @param $page
     * @param $size
     * @return array
     * @desc  返回列表
     */
    public function getList( $page , $size )
    {
        $start  = $this->getLimitStart($page, $size);

        $total  = $this->count('id');

        $list   = $this->orderBy('id', 'desc')
            ->skip($start)
            ->take($size)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];
    }

    /**
     * @param $data
     * @desc 增加记录
     */
    public function doAdd( $data )
    {
        $this->pay_channel=   $data['pay_channel'];

        $this->admin_id =   $data['admin_id'];

        $this->file_path=   $data['file_path'];

        $this->name     =   $data['name'];

        $this->note     =   $data['note'];

        return $this->save();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 更新数据
     */
    public function doEdit( $id , $data )
    {

        return $this->where('id',$id)->update($data);
    }
    /**
     * @param $id
     * @return mixed
     * @desc 删除
     */
    public function doDelete($id)
    {

        return $this->where('id', $id)
                ->where('status', self::STATUS_PENDING)
                ->delete();

    }
    /**
     * @return array
     * @desc 对账的通道
     */
    public function setRechargeType()
    {
        return [

            self::RECHARGE_CBPAY_TYPE         => "网银在线",
            self::RECHARGE_QDBPAY_AUTH_TYPE   => "钱袋宝",
            self::RECHARGE_YEEPAY_AUTH_TYPE   => "易宝",
            self::RECHARGE_LLPAY_AUTH_TYPE    => "连连认证",
            self::RECHARGE_UMP_AUTH_TYPE      => "联动优势",
            self::RECHARGE_REAPAY_AUTH_TYPE   => "融宝",
            self::RECHARGE_BEST_AUTH_TYPE     => "翼支付",
            self::RECHARGE_BFPAY_AUTH_TYPE    => "宝付",
        ];
    }

    /**
     * @return array
     * @desc 对账的状态
     */
    public function serReviewStatus()
    {
        return [
            self::STATUS_PENDING      => "待审核",
            self::STATUS_WAIT_CHECK   => "对账中",
            self::STATUS_SUCCESS      => "对账成功",
            self::STATUS_ERROR        => "对账失败"
        ];
    }
}