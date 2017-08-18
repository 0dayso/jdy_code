<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/5
 * Time: 下午5:04
 */

namespace App\Http\Controllers\App\Ad;


use App\Http\Controllers\App\AppController;
use App\Http\Dbs\Ad\AdPositionDb;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\RequestSourceLogic;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Source;

class AdController extends AppController
{

    /**
     * @SWG\Post(
     *   path="/ads_show",
     *   tags={"APP-Ad:广告相关接口"},
     *   summary="广告 [Ad\AdController@index]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="2.2.3",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="广告位描述",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="start",
     *      enum={"start","lead","register","registerBtn","tradPass","reservation","boutique","banner","invest","investBelow","productList","productDetail","myAssets1","myAssets2","envelope","login","realName","realNameOk","realNameNo","productDetailInfo","indexPlay","indexDown","assignedSuccess","indexPop","userCenter","current","verify_message"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="广告信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="广告信息获取失败。",
     *   )
     * )
     */
    /**
     * @param Request $request
     * @return array
     * @desc 启动页、引导页等广告
     */
    function index(Request $request){

        $logic      = new AdLogic();
        $type       = $request->input('type');
        $limit      = 1;    //显示一个

        //获取广告基本条件
        $version = $this -> version;

        $position = '';
        switch($type){
            case 'start':
                $position = 3;
                //$position = AdPositionDb::P_START_PAGE;
                break;
            case 'lead':
                $position = 4;
                //$position = AdPositionDb::P_LEAD_PAGE;
                $limit = 5;//显示5张
                break;
            case 'register':
                $position = AdPositionDb::P_REGISTERED;
                break;
            case 'registerBtn':
                $position = AdPositionDb::P_REGISTERED_BTN;
                break;
            case 'tradPass':
                $position = AdPositionDb::P_TRAD_PASS_SET;
                break;
            case 'reservation':
                $position = AdPositionDb::P_REG_RESERVATION;
                break;
            case 'boutique':
                $position = AdPositionDb::P_BOUTIQUE;
                break;
            case 'banner':
                $limit = 10;//显示10张
                $position = AdPositionDb::P_BOUTIQUE_BANNER;
                break;
            case 'invest':
                $position = AdPositionDb::P_INVEST_SUCCESS;
                break;
            case 'investBelow':
                $position = AdPositionDb::P_INVEST_BELOW_SUCCESS;
                break;
            case 'productList':
                $position = AdPositionDb::P_PRODUCT_LIST;
                break;
            case 'productDetail':
                $position = AdPositionDb::P_PRODUCT_DETAIL;
                break;
            case 'myAssets1':
                $position = AdPositionDb::P_MYASSETS1;
                break;
            case 'myAssets2':
                $position = AdPositionDb::P_MYASSETS2;
                break;
            case 'envelope':
                $position = AdPositionDb::P_ENVELOPE;
                break;
            case 'login':
                $position = 9;
                break;
            case 'realName':
                $position = AdPositionDb::P_REAL_NAME;
                break;
            case 'realNameOk':
                $position = 12;
                break;
            case 'realNameNo':
                $position = AdPositionDb::P_REAL_NAME_NO;
                break;
            case 'productDetailInfo':
                $limit = 10;//显示10张
                $position = AdPositionDb::P_PRODUCT_DETAIL_INFO;
                break;
            case 'indexPlay':
                $limit = 10;//显示10张
                $position = AdPositionDb::P_INDEX_PLAY;
                break;
            case 'indexDown':
                $limit = 10;//显示10张
                $position = AdPositionDb::P_INDEX_DOWN;
                break;
            case 'assignedSuccess':
                $limit = 1; //显示1张
                $position = AdPositionDb::P_ASSIGNED_SUCCESS;
                break;
            case 'indexPop':
                $limit = 1; //显示20
                $position = 21;
                break;
            case 'userCenter':
                $position = AdPositionDb::P_USER_CENTER_ADS;
                break;
            case 'current':
                $position = AdPositionDb::P_CURRENT;
                break;
            case 'verify_message':
                $position = AdPositionDb::P_REAL_NAME;
                break;
        }

        $result = $logic -> getAppAdsByPositionId($position, $limit);

        if($position == AdPositionDb::P_REAL_NAME_OK && empty($request['data']) && $this->client == RequestSourceLogic::SOURCE_ANDROID){

            $result['data'][0] = [
                'name' => '立即投资',
            ];

        }

        return self::appReturnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/get_max_adsid",
     *   tags={"APP-Ad:广告相关接口"},
     *   summary="请求活动是否弹出显示 [Ad\AdController@getMaxAdSid]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="2.2.3",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="广告信息获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="广告信息获取失败。",
     *   )
     * )
     */
    /**
     * @return array
     * @desc 请求活动是否弹出显示
     */
    public function getMaxAdSid(){

        $logic = new AdLogic();

        $result = $logic -> getMaxAdSid();

        return self::appReturnJson($result);

    }

}