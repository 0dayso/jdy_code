<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 上午11:18
 * Desc: 零钱计划项目
 */

namespace App\Http\Dbs;

class CurrentProjectDb extends JdyDb{


    protected $table = 'current_project';
    public $timestamps = false;

    public function getObj($id)
    {

        return $this->find($id);

    }

    /**
     * @param $id
     * @return mixed
     */
    public function getInfoById($id)
    {

        return self::where('id',$id)->get()->toArray();

    }

    public function invest($id, $cash)
    {

        return self::where('id',$id)
                //->where('publish_at', '<=', ToolTime::dbNow())
                ->where('total_amount', '>=', \DB::raw(sprintf('`invested_amount`+%d', $cash)))
                ->update(array(
                    'invested_amount' => \DB::raw(sprintf('`invested_amount`+%d', $cash))
                ));



    }

}