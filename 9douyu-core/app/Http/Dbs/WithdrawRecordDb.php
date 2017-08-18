<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/11/2
 * Time: 11:34
 * Desc: 提现记录
 */


namespace App\Http\Dbs;


class WithdrawRecordDb extends JdyDb
{

    protected $table = "withdraw_record";


    CONST STATUS_DEALING   = 0,   //未处理
          STATUS_FINISHED  = 1; //已处理


    /**
     * @param $data
     * @return mixed
     * 添加记录
     */
    public function addRecord($data){
        
        return self::insert($data);
    }


    /**
     * @param $page
     * @param $size
     * @return mixed
     * 分页获取数据
     */
    public function getList($page,$size){

        $offset = max(($page - 1),0) * $size;

        $res = self::orderBy('id','desc')
            ->skip($offset)
            ->take($size)
            ->get()
            ->toArray();

        return $res;
    }


    public function getTotal(){

        return self::count();
    }
    /**
     * @param $id
     * @return mixed
     * 处理提现为成功
     */
    public function doDeal($id){

        return self::where('id', $id)
            ->update(['status'=>self::STATUS_FINISHED]);
    }


    /**
     * @param $startTime
     * @param $endTime
     * @return mixed
     * 按提现时间段获取数据
     */
    public function getByTime($startTime,$endTime){

        return self::where('start_time',$startTime)
            ->where('end_time',$endTime)
            ->first();
    }

    /**
     * @param $id
     * @return array
     * 获取未处理提现
     */
    public function getRecord($id){

        $obj =  self::where('id',$id)
            ->first();

        return $this->dbToArray($obj);
    }


    /**
     * @param $createdAt
     * @param $withdrawCash
     * @return bool
     * @desc 取消提现
     */
    public function cancelWithdraw($createdAt, $withdrawCash)
    {

        $data = $this->getIdByCreatedAt($createdAt);

        $result = true;

        $maxCash = WithdrawOrderDb::WITHDRAW_SPLIT_LIMIT;;

        if (!empty($data)) {

            $cash = $data['cash'] - $withdrawCash;
            $num  = $data['num'] - ceil($withdrawCash/$maxCash);
            $id   = $data['id'];

            $result = self::where('id', $id)
                ->update(
                    [
                        'cash' => $cash,
                        'num' => $num,
                    ]
                );

        }
        return $result;
    }

    /**
     * @param $createdAt
     * @return mixed
     * 通过创建时间获取数据
     */
    public function getIdByCreatedAt( $createdAt )
    {

        $obj = self::where('start_time', '<=', $createdAt)
            ->where('end_time', '>=', $createdAt)
            ->first();

        return $this->dbToArray($obj);

    }
}