<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/7
 * Time: 下午4:01
 */

namespace App\Console\Commands\ImportOldData;

use App\Http\Dbs\Credit\CreditOldDb;

use App\Http\Models\Credit\CreditModel;

use App\Tools\ToolMoney;

use Illuminate\Console\Command;

use Log;

/**
 * todo 老系统与新系统 债权图片对接
 * config/database.php 修改旧系统 mysql_old 配置
 * todo run : php artisan jdy:credit-import
 * 导入老系统债权到新系统
 *
 * Class CreditImport
 * @package App\Console\Commands\ImportOldData
 */
class CreditImport extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jdy:credit-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入老系统债权.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private static $newJSXIds = [];//新九省心的Id
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // 保理数据处理
            $this->creditDataImport(10);
            sleep(1);
            // 信贷数据处理
            $this->creditDataImport(20);
            sleep(1);
            // 房抵数据处理
            $this->creditDataImport(30);
            sleep(1);
            // 项目集数据处理
            $this->creditDataImport(60);
//            sleep(1);
            // （新）九省心数据处理
//            $this->creditDataImport(70);
//            sleep(1);
            // （老）九省心数据处理
            $this->creditDataImportOld(90);

        }catch (\Exception $e){
            Log::info('CreditImport', [$e->getCode(), $e->getMessage(), $e->getLine()]);
        }

        //exit("\n 导入债权完成 退出导入债权");
    }


    protected $pageSize = 30;

    /**
     * @param $page
     * @return mixed
     * @desc 返回skip
     */
    protected function getSkip($page=1)
    {
        return ( max(0, $page -1) ) * $this->pageSize;
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
            90 => 30,//九省心(老)

            40 => null,//第三方
        ];
        return $data[$code];
    }


    /**
     * 获取新系统对应还款方式 code 编码
     *
     * @param $oldCode
     * @return mixed
     */
    protected function getRefundType($oldCode){
        $data = [
            10 => 40,  //等额本息【新系统暂不支持】
            11 => 50,  //等额本金【新系统暂不支持】
            30 => 60,  //循环投资【新系统暂不支持】

            40 => 10,  //到期还本息
            20 => 20,  //按月付息 到期还本
            50 => 30,  //投资当日付息 到期还本
        ];
        return $data[$oldCode];
    }


    /**
     * 获取星级别
     * 5星 （90 - 95，包含90和95）
     * 4星 （85 - 89，包含85和89）
     * 3星 （80 - 84，包含80和84）
     * 2星 （75 - 79，包含75和79）
     * 1星（ 70 - 74，包含70和74）
     *
     * @param int $value
     * @return int
     */
    protected function getStar($value = 0){
        $value = (int)$value;
        if($value>=90){
            return 5;
        }elseif($value>=85 && $value <90){
            return 4;
        }elseif($value>=80 && $value <85){
            return 3;
        }elseif($value>=75 && $value <80){
            return 2;
        }elseif($value>=70 && $value <75){
            return 1;
        }else{
            return 0;
        }

    }

    /**
     * 获取到期日期
     */
    protected function getExpirationDate($refund_type = null, $invest_time = null, $publish_time = null){
        $dateTime = '';
        if($refund_type) {
            if ($refund_type == 40) { //日 到期还本息
                $dateTime = date('Y-m-d H:i:s', strtotime($publish_time .'+'. $invest_time . 'days'));
            } else {//月
                $dateTime = date('Y-m-d H:i:s', strtotime($publish_time .'+'. $invest_time . 'months'));
            }
        }
        return $dateTime;
    }

    /**
     * 批量获取项目
     *
     * @param $projectWay
     * @param array $company_ids
     */
    protected function getOldProject($projectWay, $company_ids = []){
        $projectOldDb             = new CreditOldDb(100);// 100 代表 项目表
        $condition['project_way'] = $projectWay;
        $data                     = $projectOldDb->where($condition)->whereIn('project_company_id', $company_ids)->get()->toArray();
        return $data;
    }


    /**
     * 获取信贷、个人房贷 产品线
     */
    protected function getCreditTag($invest_time){
        $invest_time = (int)$invest_time;
        $invest_time =  100+$invest_time;//九省心 3、6、12【数据表里只有这些】// 100 九省心产品线

        if(!in_array($invest_time, [103, 106, 112])){//九省心 相关产品线
            $invest_time = 101;//九省心 1月期 默认
        }
        return $invest_time;
    }

    /**
     * 债权老数据提取与新数据存储
     *
     * @param int $creditCode
     * @throws \Exception
     */
    protected function creditDataImportOld($creditCode = 90){

        $ProjectOldDb = new CreditOldDb($creditCode);

        //获取总数
        $total       = $ProjectOldDb->where('project_way','30')->count('id');
        //新数据库对应模型
        $class       = CreditModel::getClass(70);
        if($class === null || !class_exists($class)){
            throw new \Exception('我草嘞 新数据模型找不到 无法继续导入', -500);
        }

        $obj            = new $class;
        $newCreditTable = $obj->getTable();
        app('db')->table($newCreditTable)->delete();

        if($total > 0) {
            //获取最大page
            $maxPage = ceil($total / $this->pageSize);
            //分页获取数据
            for ($page = 1; $page <= $maxPage; $page++) {
                $oldProjects = $ProjectOldDb->where('project_way','30')->skip($this->getSkip($page))
                    ->take($this->pageSize)
                    ->get()
                    ->toArray();
                //获取老九省心项目
                $save = [];
                foreach($oldProjects as $k => $record){
                    $creditOldDb   = new CreditOldDb(91);
                    $creditOldData = $creditOldDb->where(['id' => $record['project_company_id']])->get()->toArray();
                    $record         = $this->{'getMapping_' . $creditCode}($creditOldData[0], $record);
                    if(!empty($record))
                        $save[]         = $record;
                }
                // 批量插入数据
                if($save) {
                    $is = $class::insert($save);
                    var_dump($is);
                }
                // 插入后清空数据
                unset($save, $oldCreditData, $creditIds, $projectWay, $oldProjects);
            }

            Log::info('creditDataImport', ['creditDbCode'=> $creditCode, 'total' => $total, 'maxPage' => $maxPage]);
        }
        echo "$creditCode 导入完成 \n";
    }

    /**
     * 债权老数据提取与新数据存储
     *
     * @param int $creditCode
     * @throws \Exception
     */
    protected function creditDataImport($creditCode = 10){
        $creditOldDb = new CreditOldDb($creditCode);
        //获取总数
        $total       = $creditOldDb->count('id');
        //新数据库对应模型
        $class       = CreditModel::getClass($creditCode);
        if($class === null || !class_exists($class)){
            throw new \Exception('我草嘞 新数据模型找不到 无法继续导入', -500);
        }

        $obj            = new $class;
        // todo 清空新系统债权【重新导入】
        $newCreditTable = $obj->getTable();
        app('db')->table($newCreditTable)->delete();

        if($total > 0) {
            //获取最大page
            $maxPage = ceil($total / $this->pageSize);
            //分页获取数据
            for ($page = 1; $page <= $maxPage; $page++) {
                $oldCreditData = $creditOldDb->skip($this->getSkip($page))
                    ->take($this->pageSize)
                    ->get()
                    ->toArray();

                //获取项目关联债权的ID数组
                $creditIds =[];
                foreach($oldCreditData as $k => $record){
                    $creditIds[]= $record['id'];
                }

                //获取债权关联项目数据集
                $projectWay     = $this->getOldProjectWay($creditCode);
                $oldProjects    = $this->getOldProject($projectWay, $creditIds);

                //格式化保存数据
                $save = [];
                foreach($oldCreditData as $k => $record){
                    $record         = $this->{'getMapping_' . $creditCode}($record, $oldProjects);
                    $save[]         = $record;
                }

                // 批量插入数据
                    $is             = $class::insert($save);

                var_dump($is);
                // 插入后清空数据
                unset($save, $oldCreditData, $creditIds, $projectWay, $oldProjects);
            }

            Log::info('creditDataImport', ['creditDbCode'=> $creditCode, 'total' => $total, 'maxPage' => $maxPage]);
        }
        echo "$creditCode 导入完成 \n";
    }

    /**
     * 格式化保理【新老系统映射】
     *
     * @param array $record
     * @param array $oldProject
     * @return array
     */
    protected function getMapping_10($record = [], $oldProject = []){

        $oldProjectsKey = array_search($record['id'], array_column($oldProject, 'project_company_id'));
        $oldProject     = $oldProject[$oldProjectsKey];

        $expiration_date= '';
        if($oldProject) {
            $expiration_date = $this->getExpirationDate($oldProject['refund_type'], $oldProject['invest_time'], $oldProject['publish_time']);
        }
        $record = [
            'id'                    => $record['id'],
            "source"                => "10",                         // 保理
            "type"                  => "50",                         // 保理债权固定
            "credit_tag"            => "200",                        //【保理 产品线：九安心】
            "company_name"          => $record['full_name'],         // 企业名称
            "loan_username"         => $record['loan_username'],     // json 借款人名
            "loan_user_identity"    => $record['loan_user_identity'],// json 借款人证件号
            "factor_summarize"      => $record['factor_summarize'],  // 项目综素
            "repayment_source"      => $record['factor_refund'],     // 还款来源
            "factoring_opinion"     => $record['guarantee_comment'], // 保理公司意见
            "business_background"   => $record['credit_company'],    // 原 债权企业介绍
            "introduce"             => $record['debt_company'],      // 原 债务企业介绍
            "risk_control_measure"  => $record['risk_control'],      // 风控措施
            "transactional_data"    => $record['trade_info'],        // 基础交易材料
            "traffic_data"          => $record['factor_info'],       // 保理业务材料

            "loan_amounts"          => isset($oldProject['total_amount']) ? ToolMoney::formatDbCashAdd($oldProject['total_amount']) : 0,//债权金额 老系统【元】 * 100 =系统【分】
            "interest_rate"         => isset($oldProject['profit_percentage']) ? $oldProject['profit_percentage'] : 0.00,  //利率
            "repayment_method"      => isset($oldProject['refund_type']) ? $this->getRefundType($oldProject['refund_type']) : '',//还款方式编码
            "expiration_date"       => $expiration_date, // 到期日期
            "loan_deadline"         => isset($oldProject['invest_time']) ? $oldProject['invest_time'] : '', //期限
            "contract_no"           => isset($oldProject['contract_no']) ? $oldProject['contract_no'] : '',//合同编号
            //risk
            "riskcalc_level"        => isset($oldProject['riskcalc_level']) ? $oldProject['riskcalc_level'] : 0,
            "company_level_value"   => isset($oldProject['company_level']) ? $oldProject['company_level'] : 0,
            "downstream_level_value"=> isset($oldProject['downstream_level']) ? $oldProject['downstream_level'] : 0,
            "profit_level_value"    => isset($oldProject['profit_level']) ? $oldProject['profit_level'] : 0,
            "downstream_refund_level_value" => isset($oldProject['refund_level']) ? $oldProject['refund_level'] : 0,
            "liability_level_value" => isset($oldProject['liberal_level']) ? $oldProject['liberal_level'] : 0,
            "guarantee_level_value" => isset($oldProject['guarantee_level']) ? $oldProject['guarantee_level'] : 0,

            "company_level"           => isset($oldProject['company_level']) ? $this->getStar($oldProject['company_level']) : 0,
            "downstream_level"        => isset($oldProject['downstream_level']) ? $this->getStar($oldProject['downstream_level']) : 0,
            "profit_level"            => isset($oldProject['profit_level']) ? $this->getStar($oldProject['profit_level']) : 0,
            "downstream_refund_level" => isset($oldProject['downstream_level']) ? $this->getStar($oldProject['downstream_level']) : 0,
            "liability_level"         => isset($oldProject['liberal_level']) ? $this->getStar($oldProject['liberal_level']) : 0,
            "guarantee_level"         => isset($oldProject['guarantee_level']) ? $this->getStar($oldProject['guarantee_level']) : 0,

            "keywords"              => isset($oldProject['keywords']) ? $oldProject['keywords'] : '',
            "credit_desc"           => isset($oldProject['creditor']) ? $oldProject['creditor'] : '',

            'status_code'           => 100,//已经使用的债权
        ];
        return $record;
    }


    /**
     * 格式化信贷【新老系统映射】
     *
     * @param array $record
     * @param array $oldProject
     * @return array
     */
    protected function getMapping_20($record = [], $oldProject = []){

        $oldProjectsKey = array_search($record['id'], array_column($oldProject, 'project_company_id'));
        $oldProject     = $oldProject[$oldProjectsKey];

        $expiration_date= '';
        if($oldProject) {
            $expiration_date = $this->getExpirationDate($oldProject['refund_type'], $oldProject['invest_time'], $oldProject['publish_time']);
        }
        $record = [
            'id'                    => $record['id'],
            "source"                => "20",// 信贷
            "type"                  => "50",// 常规
            "credit_tag"            => isset($oldProject['invest_time']) ? $this->getCreditTag($oldProject['invest_time']) : '', //期限,
            "company_name"          => !empty($record['full_name']) ? $record['full_name'] : $record['name'],
            "loan_amounts"          => isset($oldProject['total_amount']) ? ToolMoney::formatDbCashAdd($oldProject['total_amount']) : 0,//债权金额 老系统【元】 * 100 =系统【分】,
            "interest_rate"         => isset($oldProject['profit_percentage']) ? $oldProject['profit_percentage'] : 0.00,  //利率
            "repayment_method"      => isset($oldProject['refund_type']) ? $this->getRefundType($oldProject['refund_type']) : '',//还款方式编码
            "expiration_date"       => $expiration_date,
            "loan_deadline"         => isset($oldProject['invest_time']) ? $oldProject['invest_time'] : '', //期限
            "contract_no"           => isset($oldProject['contract_no']) ? $oldProject['contract_no'] : '',//合同编号
            "loan_username"         => isset($record['loan_username']) ? $record['loan_username'] : '',     // json 借款人名
            "loan_user_identity"    => isset($record['loan_user_identity']) ? $record['loan_user_identity'] : '',// json 借款人证件号
            //risk
            "riskcalc_level"        => isset($oldProject['riskcalc_level']) ? $oldProject['riskcalc_level'] : 0,

            "company_level_value"   => isset($oldProject['company_level']) ? $oldProject['company_level'] : 0,
            "profit_level_value"    => isset($oldProject['profit_level']) ? $oldProject['profit_level'] : 0,
            "liability_level_value" => isset($oldProject['liberal_level']) ? $oldProject['liberal_level'] : 0,
            "guarantee_level_value" => isset($oldProject['guarantee_level']) ? $oldProject['guarantee_level'] : 0,

            "company_level"          => isset($oldProject['company_level']) ? $this->getStar($oldProject['company_level']) : 0,
            "profit_level"           => isset($oldProject['profit_level']) ? $this->getStar($oldProject['profit_level']) : 0,
            "liability_level"        => isset($oldProject['liberal_level']) ? $this->getStar($oldProject['liberal_level']) : 0,
            "guarantee_level"        => isset($oldProject['guarantee_level']) ? $this->getStar($oldProject['guarantee_level']) : 0,

            "keywords"               => isset($oldProject['keywords']) ? $oldProject['keywords'] : '',
            "credit_desc"            => isset($oldProject['creditor']) ? $oldProject['creditor'] : '',

            "financing_company"      => isset($record['name']) ? $record['name'] : '',
            "founded_time"           => isset($record['regtime']) ? $record['regtime'] : '',
            "program_area_location"  => isset($record['area']) ? $record['area'] : '',
            "registered_capital"     => isset($record['register_money']) ? $record['register_money'] : '',
            "annual_income"          => isset($record['year_income']) ? $record['year_income'] : '',

            "loan_use"               => isset($record['usage']) ? $record['usage'] : '',
            "repayment_source"       => isset($record['refund_from']) ? $record['refund_from'] : '',
            "background"             => isset($record['basic_info']) ? $record['basic_info'] : '',
            "financial"              => isset($record['operate_info']) ? $record['operate_info'] : '',

            "sex"                    => isset($record['sex']) ? $record['sex'] : '',
            "age"                    => isset($record['age']) ? $record['age'] : '',
            "family_register"        => isset($record['address']) ? $record['address'] : '',
            "residence"              => isset($record['place']) ? $record['place'] : '',
            "home_stability"         => isset($record['home_table']) ? $record['home_table'] : '',
            "esteemn"                => isset($record['asset_info']) ? $record['asset_info'] : '',
            "credibility"            => isset($record['credit_info']) ? $record['credit_info'] : '',
            "involved_appeal"        => isset($record['lawsuits_info']) ? $record['lawsuits_info'] : '',

            "submit_data"            => isset($record['auth']) ? $record['auth'] : '',

            "risk_control_message"   => isset($record['risk_info']) ? $record['risk_info'] : '',
            "risk_control_security"  => isset($record['risk_guarantee']) ? $record['risk_guarantee'] : '',

            "contract_agreement"     => isset($record['agreement_images']) ? $record['agreement_images'] : '',
            "company_photo"          => isset($record['industry_images']) ? $record['industry_images'] : '',

            'status_code'            => 200,

        ];
        return $record;
    }

    /**
     * 格式化房抵【新老系统映射】
     *
     * @param array $record
     * @param array $oldProject
     * @return array
     */
    protected function getMapping_30($record = [], $oldProject = []){

        $oldProjectsKey = array_search($record['id'], array_column($oldProject, 'project_company_id'));
        $oldProject     = $oldProject[$oldProjectsKey];

        $expiration_date= '';
        if($oldProject) {
            $expiration_date = $this->getExpirationDate($oldProject['refund_type'], $oldProject['invest_time'], $oldProject['publish_time']);
        }
        $record = [
            'id'                    => $record['id'],
            "source"                => "30",//房产抵押
            "type"                  => "50",//常规

            "credit_tag"            => isset($oldProject['invest_time']) ? $this->getCreditTag($oldProject['invest_time']) : '', //期限,
            "company_name"          => isset($oldProject['name']) ? $oldProject['name'] : '',
            "loan_amounts"          => isset($oldProject['total_amount']) ? ToolMoney::formatDbCashAdd($oldProject['total_amount']) : 0,//债权金额 老系统【元】 * 100 =系统【分】,
            "interest_rate"         => isset($oldProject['profit_percentage']) ? $oldProject['profit_percentage'] : 0.00,  //利率
            "repayment_method"      => isset($oldProject['refund_type']) ? $this->getRefundType($oldProject['refund_type']) : '',//还款方式编码
            "expiration_date"       => $expiration_date,
            "loan_deadline"         => isset($oldProject['invest_time']) ? $oldProject['invest_time'] : '', //期限
            "contract_no"           => isset($oldProject['contract_no']) ? $oldProject['contract_no'] : '',//合同编号
            "loan_username"         => isset($record['loan_username']) ? $record['loan_username'] : '',     // json 借款人名
            "loan_user_identity"    => isset($record['loan_user_identity']) ? $record['loan_user_identity'] : '',// json 借款人证件号
            "credit_desc"           => isset($oldProject['creditor']) ? $oldProject['creditor'] : '',

            "housing_location"      => isset($record['area']) ? $record['area'] : '',
            "housing_area"          => isset($record['home_acreage']) ? $record['home_acreage'] : '',
            "housing_valuation"     => isset($record['home_value']) ? $record['home_value'] : '',

            "sex"                   => isset($record['sex']) ? $record['sex'] : '',
            "age"                   => isset($record['age']) ? $record['age'] : '',
            "family_register"       => isset($record['address']) ? $record['address'] : '',
            "residence"             => isset($record['place']) ? $record['place'] : '',

            "credibility"           => isset($record['credit_info']) ? $record['credit_info'] : '',
            "involved_appeal"       => isset($record['lawsuits_info']) ? $record['lawsuits_info'] : '',

            "risk_control_message"  => isset($record['risk_guarantee']) ? $record['risk_guarantee'] : '',
            "certificates"          => isset($record['identity_images']) ? $record['identity_images'] : '',
            "mortgage"              => isset($record['homeloan_images']) ? $record['homeloan_images'] : '',

            'status_code'           => 100,//已经使用的债权
        ];
        return $record;
    }


    /**
     * 格式化项目集【新老系统映射】
     *
     * @param array $record
     * @param array $oldProject
     * @return array
     */
    protected function getMapping_60($record = [], $oldProject = []){
        $record = [
            'id'                    => $record['id'],
            "source"                => "20",// 信贷
            "type"                  => "60",// 项目集
            "credit_tag"            => isset($record['invest_time']) ? $this->getCreditTag($record['invest_time']) : '', //产品线,
            "company_name"          => isset($record['company_name']) ? $record['company_name'] : '',
            "loan_amounts"          => isset($record['total_amount']) ? ToolMoney::formatDbCashAdd($record['total_amount']) : '',
            "interest_rate"         => isset($record['profit_percentage']) ? $record['profit_percentage'] : '',
            "repayment_method"      => isset($record['refund_type']) ? $this->getRefundType($record['refund_type']) : '',
            "expiration_date"       => isset($record['end_time']) ? $record['end_time'] : '',
            "loan_deadline"         => isset($record['invest_time']) ? $record['invest_time'] : '',
            "contract_no"           => isset($record['contract_num']) ? $record['contract_num'] : '',
            "loan_username"         => isset($record['loan_username']) ? $record['loan_username'] : '',
            "loan_user_identity"    => isset($record['loan_user_identity']) ? $record['loan_user_identity'] : '',
            "financing_company"     => isset($record['name']) ? $record['name'] : '',
            "program_area_location" => isset($record['area']) ? $record['area'] : '',
            "loan_use"              => isset($record['usage']) ? $record['usage'] : '',
            "repayment_source"      => isset($record['refund_from']) ? $record['refund_from'] : '',
            "loan_contract"         => isset($record['contract_file']) ? $record['contract_file']: '',

            'status_code'           => 100,//已经使用的债权
        ];
        return $record;
    }

    /**
     * 格式化新九省心【新老系统映射】
     *
     * @param array $record
     * @param array $oldProject
     * @return array
     */
    protected function getMapping_70($record = [], $oldProject = []){
        $record = [
            'id'                    => $record['id'],
            "source"                => "20",// 信贷
            "type"                  => "70",//九省心
            "credit_tag"            => isset($record['invest_time']) ? $this->getCreditTag($record['invest_time']) : '', //产品线,
            "plan_name"             => isset($record['credit_name']) ? $record['credit_name'] : '',
            "loan_amounts"          => isset($record['total_amount']) ? ToolMoney::formatDbCashAdd($record['total_amount']) : 0,
            "interest_rate"         => isset($record['profit_percentage']) ? $record['profit_percentage'] : '',
            "repayment_method"      => isset($record['refund_type']) ? $this->getRefundType($record['refund_type']) : '',
            "expiration_date"       => isset($record['end_time']) ? $record['end_time'] : '',
            "loan_deadline"         => isset($record['invest_time']) ? $record['invest_time'] : '',
            "contract_no"           => isset($record['contract_num']) ? $record['contract_num'] : '',
            "program_no"            => "",
            "file"                  => "",
            "can_use_amounts"       => $record['total_amount'] - $record['invested_amount'],
            'status_code'           => 100,//已经使用的债权
        ];
        return $record;
    }


    /**
     * 格式化老九省心【新老系统映射】
     *
     * @param array $record
     * @param array $oldProject
     * @return array
     */
    protected function getMapping_90($record = [], $oldProject = []){
        $records = [];

        if(!empty($record) && !empty($oldProject)) {
            $expiration_date = '';
            if ($oldProject) {
                $expiration_date = $this->getExpirationDate($oldProject['refund_type'], $oldProject['invest_time'], $oldProject['publish_time']);
            }

            $records = [
                'id' => $record['id'],
                "source" => "20",// 信贷
                "type" => "70",//九省心
                "credit_tag" => isset($oldProject['invest_time']) ? $this->getCreditTag($oldProject['invest_time']) : '', //期限,
                "plan_name" => isset($oldProject['name']) ? $oldProject['name'] : '',
                "loan_amounts" => isset($oldProject['total_amount']) ? ToolMoney::formatDbCashAdd($oldProject['total_amount']) : 0,//债权金额 老系统【元】 * 100 =系统【分】,
                "interest_rate" => isset($oldProject['profit_percentage']) ? $oldProject['profit_percentage'] : 0.00,  //利率
                "repayment_method" => isset($oldProject['refund_type']) ? $this->getRefundType($oldProject['refund_type']) : '',//还款方式编码
                "expiration_date" => $expiration_date,
                "loan_deadline"     => isset($oldProject['invest_time']) ? $oldProject['invest_time'] : '', //期限
                "contract_no"       => isset($oldProject['contract_no']) ? $oldProject['contract_no'] : '',//合同编号
                "program_no"        => "",
                "file" => "",
                "can_use_amounts" => $oldProject['total_amount'] - $oldProject['invested_amount'],
                'status_code' => 100,//已经使用的债权
                'credit_info' => $record['credit_info'],
            ];

        }
        return $records;
    }

}