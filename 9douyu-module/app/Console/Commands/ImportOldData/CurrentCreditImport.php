<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/6
 * Time: 13:54
 */

namespace App\Console\Commands\ImportOldData;


use App\Http\Dbs\Current\CreditDetailDb;
use App\Http\Dbs\Current\CreditOldDb;
use App\Http\Dbs\Current\FreeProjectOldDb;
use App\Tools\ToolArray;
use Log;

use Illuminate\Console\Command;

/**
 * todo 老系统与新系统
 * config/database.php 修改旧系统 mysql_old 配置
 * todo run : php artisan jdy:current-credit-import
 * 导入老系统零钱计划债权到新系统
 *
 * Class CurrentCreditImport
 * @package App\Console\Commands\ImportOldData
 */

class CurrentCreditImport extends Command{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jdy:current-credit-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入老系统零钱计划债权.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
           
            // （老）九省心数据处理
//            $this->creditDataImportOld(90);
            
            $this->currentCreditDataImport();

        }catch (\Exception $e){
            dd($e->getMessage());
            Log::info('CreditImport', [$e->getCode(), $e->getMessage()]);
        }

        //exit("\n 导入债权完成 退出导入债权");
    }


    /**
     * 零钱计划债权明细导入
     */
    private function currentCreditDataImport(){
        
        $db = new CreditOldDb();

        $list = $db->select('id','project_company_id')
            ->get()
            ->toArray();

        if($list){
            
            $freeDb = new FreeProjectOldDb();

            $arr = ToolArray::arrayToKey($list,'project_company_id');

            $companyIds = array_keys($arr);

            $creditList = $freeDb->whereIn('id',$companyIds)
                ->get()
                ->toArray();

            $creditList = ToolArray::arrayToKey($creditList,'id');

            $detailDb = new CreditDetailDb();

            foreach($arr as $id => $val){

                $params = [];

                if(isset($creditList[$id])){

                    $data = json_decode($creditList[$id]['credit_info'],true);

                    foreach($data as $credit){

                        $params[] = [
                            'credit_id' => $val['id'],
                            'name' => $credit['realname'],
                            'id_card'   => $credit['identity_card'],
                            'amount' => $credit['amount'],
                            'time' => $credit['time'],
                            'usable_amount' => $credit['amount'],
                            'address' => $credit['address']
                        ];
                    }

                    $detailDb->addRecord($params);

                }else{
                    continue;
                }

            }

        }

       

    }

}