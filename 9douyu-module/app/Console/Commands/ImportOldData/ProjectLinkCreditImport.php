<?php

namespace App\Console\Commands\ImportOldData;

use App\Http\Dbs\Credit\CreditDb;
use App\Http\Dbs\Credit\CreditOldDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Dbs\Project\ProjectLinkCreditDb;
use App\Tools\ToolTime;
use Illuminate\Console\Command;
use Log;

class ProjectLinkCreditImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     *
     * todo run:artisan jdy:project-link-credit-import
     *
     * 导入完成后可删除
     */
    protected $signature = 'jdy:project-link-credit-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入老系统项目关联债权表.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try {
            // 保理数据处理
            $this->projectDataImport(10);
            sleep(1);
            // 信贷数据处理
            $this->projectDataImport(20);
            sleep(1);
            // 房抵数据处理
            $this->projectDataImport(30);
            sleep(1);
            // 项目集数据处理
            $this->projectDataImport(60);
            sleep(1);
            // （新）九省心数据处理
            $this->projectDataImport(70);

            echo "ending \n";

        }catch (\Exception $e){
            Log::info('ProjectLinkCreditImport', [$e->getCode(), $e->getMessage()]);
        }

    }

    /**
     * 获取老系统的projectWay【与新系统对应】
     *
     * @param $code
     */
    protected function getOldProjectWay($code = 10){
        $data = [
            10 => 20,//保理
            20 => 10,//信贷
            30 => 50,//房抵
            60 => 60,//项目集
            70 => 30,//九省心

            40 => null,//第三方
        ];
        return $data[$code];
    }

    /**
     * 债权老数据提取与新数据存储
     *
     * @param int $projectWay
     * @throws \Exception
     */
    protected function projectDataImport($projectWay = 10){

        $projectLinkCreditDb = new ProjectLinkCreditDb();

        $oldProjectWay = $this->getOldProjectWay($projectWay);

        /*start*/
        $projectInfo = $this -> getOldProject($oldProjectWay);

        foreach($projectInfo as $key => $value){

            if($projectWay == 60){
                $jsonCredit = $this->getGroupCredit($projectWay, $value['project_company_id']);
            }else{
                $creditInfo['credit_id']           = $value['project_company_id'];
                $creditInfo['type']                = $projectWay;
                $creditInfo['credit_cash']         = $value['total_amount'];
                $jsonCredit = json_encode([$creditInfo]);
            }

            $data['project_id']                = $value['id'];
            $data['product_line']              = $this -> getProductLine($oldProjectWay, $value['refund_type'], $value['invest_time']);
            $data['credit_info']               = $jsonCredit;
            $data['created_at']                = ToolTime::dbNow();
            $save[]                            = $data;

        }

        $projectLinkCreditDb::insert($save);

        /*end*/

        echo "$projectWay 导入完成 \n";
    }

    /**
     * 批量获取项目
     *
     * @param $projectWay
     * @return mixed
     */
    protected function getOldProject( $projectWay ){
        $projectOldDb             = new CreditOldDb(100);// 100 代表 项目表
        $condition['project_way'] = $projectWay;
        $data                     = $projectOldDb->select('id','project_way','total_amount','project_company_id','refund_type','invest_time')->where($condition)->get()->toArray();
        return $data;
    }

    /**
     * @param $projectWay
     * @param $companyId
     * @return array|string
     * @desc 债权关联
     */
    protected function getGroupCredit( $projectWay, $companyId ){

        $freeProjectDb            = new CreditOldDb(91);// 91 代表 free_project 债权
        $condition['id'] = $companyId;
        $data                     = $freeProjectDb->select('credit_info')->where($condition)->get()->toArray();
        $creditInfo = '';
        if(!empty($data)){
            $creditArr = json_decode($data[0]['credit_info']);
            foreach($creditArr as $key=>$value){
                $creditInfo[] = [
                    'credit_id'   => (int)$key,
                    'type'        => $projectWay,
                    'credit_cash' => number_format($value,2,'.',''),
                ];
            }
        }

        return json_encode($creditInfo);

    }

    /**
     * 获取产品线
     *
     * @param $oldProjectWay
     * @param $refund_type
     * @return int
     */
    protected function getProductLine($oldProjectWay, $refundType, $investTime){

        if($refundType == 50){
            return ProjectDb::PROJECT_PRODUCT_LINE_SDF + $investTime;
        }
        if($refundType == 40 && $oldProjectWay == 20){
            return ProjectDb::PROJECT_PRODUCT_LINE_JAX;
        }

        if($refundType == 40){
            return ProjectDb::PROJECT_PRODUCT_LINE_JSX + 1;
        }

        return ProjectDb::PROJECT_PRODUCT_LINE_JSX + $investTime;

    }
}
