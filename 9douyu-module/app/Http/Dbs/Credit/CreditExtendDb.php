<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/5/16
 * Time: Pm 06:20
 * Desc: 九斗鱼债权拓展表DB层
 */

namespace App\Http\Dbs\Credit;

class CreditExtendDb extends CreditDb
{

    protected $table = 'credit_extend';

    /**
     * @desc 创建债权扩展记录
     * @param $attributes
     * @reurn mixed
     */
    public static function add($attributes = []){

        $model = new static($attributes, array_keys($attributes));
        $model->save();
        return $model->id;
    }

    /**
     * @desc 更新扩展信息
     * @param $creditId int
     * @param $data array
     * @return array
     */
    public static function doUpdateExtra( $creditId, $data )
    {
        return static::where('credit_id', $creditId)->Update([ 'extra'=> $data ] );
    }

    /**
     * @desc 获取债权拓展信息
     * @param $creditId int
     * @return array
     */
    public static function getExtraByCreditId( $creditId )
    {
        return self::select('extra')
            ->where( 'credit_id', $creditId )
            ->first();

        if(is_object($result)){
            return $result -> toArray();
        }else{
            return [];
        }
    }

    /**
     * @param array $creditIds
     * @return mixed
     * 债权详情
     */
    public static function getCreditDetailByCreditIds( $creditIds ){

        $result = self::whereIn('credit_id', $creditIds)
                    ->get()
                    ->toArray();

        return $result;
    }


    /**
     * @desc  获取债券的扩展信息
     */
    public function getCreditExtendByCreditId( $creditId = 0 )
    {
        return $this->dbToArray(
            $this->where('credit_id', $creditId )->first()
        );
    }
}
