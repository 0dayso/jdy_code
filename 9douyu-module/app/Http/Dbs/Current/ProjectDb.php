<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 上午11:18
 * Desc: 零钱计划项目
 */

namespace App\Http\Dbs\Current;
use App\Http\Dbs\JdyDb;

class ProjectDb extends JdyDb{


    const
        INTEREST_RATE_NOTE = '借款利率',
        END=true;

    protected $table = 'current_project';
    public $timestamps = false;

    public function getObj($id)
    {

        return self::find($id);

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

    /**
     * @return mixed
     * 获取最后一个零钱计划项目
     */
    public function getShowProject(){

        $result = self::orderBy('id','desc')
                ->first();

        return $this->dbToArray($result);
    }

    /**
     * @param $projectName
     * @param $cash
     * @param $admin
     * @return bool
     * 添加零钱计划项目
     */
    public function add($projectName,$cash,$publishAt,$admin){

        $this->name         = $projectName;
        $this->total_amount = $cash;
        $this->create_by     = $admin;
        $this->publish_at   = $publishAt;

        return $this->save();
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @desc 根据项目时间获取零钱计划的项目
     */
    public function getCurrentProjectByDate($startTime,$endTime)
    {

        $timesArr   = [$startTime, $endTime];

        return  $this->whereBetween('created_at', $timesArr)
                ->get()
                ->toArray();

    }
}