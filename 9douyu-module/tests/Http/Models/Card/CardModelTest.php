<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/21
 * Time: 17:38
 */

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午5:05
 */

namespace Tests\Http\Models\Article;

use App\Http\Models\Common\ServiceApi\BankCardModel;
use App\Tools\ToolTime;

class ArticleModelTest extends \TestCase
{


    /**
     *
     * 连连卡bin接口测试
     */
    public function testFetchCardInfo(){

        $cardNo = '6214830104420491';

        $result = BankCardModel::getCardInfo($cardNo);

        dd($result);
    }




}