<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 上午11:18
 * Desc: 零钱计划项目
 */

namespace App\Http\Dbs\CurrentNew;
use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class ProjectNewDb extends JdyDb{


    protected $table = 'current_new_project';
    public $timestamps = false;

    CONST
        STATUS_UN_PUBLISH = 100, //待发布
        STATUS_PUBLISH    = 200, //已发布
    END=1;

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

        $result = self::where('id',$id)->first();

        return $this->dbToArray($result);

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

        $result = self::where('status', self::STATUS_PUBLISH)
                ->where('publish_at', '<=', ToolTime::dbNow())
                ->where('total_amount', '>', 'invested_amount')
                ->orderBy('invested_amount')
                ->orderBy('id','desc')
                ->first();

        return $this->dbToArray($result);
    }

    /**
     * @param $projectName
     * @param $cash
     * @param $admin
     * @param $publishAt
     * @param $status
     * @return bool
     * 添加零钱计划项目
     */
    public function add($projectName,$cash,$publishAt,$admin,$status){

        $this->name         = $projectName;
        $this->total_amount = $cash;
        $this->create_by    = $admin;
        $this->publish_at   = $publishAt;
        $this->status       = $status;

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

    /**
     * @desc 获取零钱计划项目列表
     * @param $page
     * @param $pageSize
     * @return mixed
     */
    public function getProjectList($page, $pageSize){

        $start = $this->getLimitStart($page, $pageSize);

        $rateList = self::orderBy('id', 'desc')
            ->skip($start)
            ->paginate($pageSize)
            ->toArray();
        return $rateList;
    }

    /**
     * @param $id
     * @param $date
     * @param $rate
     * @param $profit
     * @return mixed
     * 编辑零钱计划项目
     */
    public function edit($id, $name, $totalAmount, $publishAt='', $status ){

        $data = [
            'name'              => $name,
            'total_amount'      => $totalAmount,
            'status'            => $status,
            'publish_at'        => $publishAt,
        ];

        return self::where('id',$id)
            ->update($data);
    }
}