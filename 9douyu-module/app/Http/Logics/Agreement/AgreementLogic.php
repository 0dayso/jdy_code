<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/10/19
 * Time: 下午3:40
 * Desc: 合同/协议
 */

namespace App\Http\Logics\Agreement;

use App\Http\Logics\Logic;
use App\Tools\ToolTcPdf;

class AgreementLogic extends Logic{

    //模板
    const BLADE_ARR = [
        '10'        =>  'credit',           //信贷
        '20'        =>  'factor',           //保理
        '30'        =>  'free',             //九省心
        '40'        =>  'credit_assign',    //债权转让
        '50'        =>  'home_loan',        //房贷
        '60'        =>  'group_credit',     //项目集
        'current'   =>  'current',          //零钱计划
        'argument'  =>  'argument',         //投资协议
        'pre'       =>  'pre_interest',      //闪电付息
        'third'     =>  'third_credit'       //第三方债权信息
    ];

    //标题
    const TITLE_ARR = [
        //'10'        =>  '九斗鱼-应收账款转让及回购协议',    //信贷
        '10'        =>  '九斗鱼-债权转让协议',               //信贷
        '20'        =>  '九斗鱼-应收账款转让及回购合同',       //保理
        '30'        =>  '九斗鱼-九省心投资协议',               //九省心
        '40'        =>  '九斗鱼-站内债权转让协议',             //债权转让
        '50'        =>  '九斗鱼-债权转让协议',                //房贷
        '60'        =>  '九斗鱼-应收账款转让及回购协议',       //项目集
        'current'   =>  '九斗鱼-零钱计划投资协议',            //零钱计划
        'argument'  =>  '九斗鱼-投资咨询与管理服务协议',       //投资协议
        'pre'       =>  '九斗鱼-闪电付息投资协议'      //闪电付息
    ];

    //位置
    const SEAL_POSITION = [
        '10'    => [
            [
                'img'   => 'seal_xingguo.png',
                'x'     => 40,
                'y'     => 95
            ],
            [
                'img'   => 'seal_xiaodai.png',
                'x'     => 90,
                'y'     => 95,
            ],
        ],
        '20'    => [
            [
                'img'   => 'seal_xingguo.png',
                'x'     => 40,
                'y'     => 160,
                'p'     => 3,
            ],
            [
                'img'   => 'seal_baoli.png',
                'x'     => 30,
                'y'     => 40,
            ],
        ],
        '30'    => [
            [
                'img'   => 'seal_xingguo.png',
                'x'     => 40,
                'y'     => 230
            ]
        ],
        '40'    => [
            [
                'img'   => 'seal_xingguo.png',
                'x'     => 40,
                'y'     => 210
            ]
        ],
        '50'    => [
            [
                'img'   => 'seal_xingguo.png',
                'x'     => 80,
                'y'     => 80
            ],
            [
                'img'   => 'seal_xiaodai.png',
                'x'     => 30,
                'y'     => 80
            ]
        ],
        '60'    => [
            [
                'img'   => 'seal_xingguo.png',
                'x'     => 45,
                'y'     => 45
            ]
        ],
        'current'   => [
            [
                'img'   => 'seal_xingguo.png',
                'x'     => 40,
                'y'     => 30
            ]
        ],
        'pre'   => [
            [
                'img'   => 'seal_xingguo.png',
                'x'     => 40,
                'y'     => 160
            ]
        ],
        'default'   => [
            [
                'img'   => 'seal_xingguo.png',
                'x'     => 30,
                'y'     => 30
            ]
        ]
    ];

    /**
     * 君子签-个人签章的位置定义
     */
    const CHAPTER_OFFSET = [
        '10'    => [
            [
                'offsetX' => 0.28, 'offsetY'=> 0.18
            ],
        ],
        '20'    => [
            [
                'offsetX' => 0.18, 'offsetY' => 0.28,
            ],
        ],
        '30'    => [
            [
                'offsetX' => 0.26, 'offsetY' => 0.53
            ]
        ],
        '40'    => [
            [
                'offsetX' => 0.18,'offsetY' => 0.28
            ]
        ],
        '50'    => [
            [
                'offsetX' => 0.31,'offsetY' => 0.19
            ],
        ],
        '60'    => [
            [
                'offsetX' => 0.18, 'offsetY' => 0.25
            ]
        ],
        'current'   => [
            [
                'offsetX' => 0.18, 'offsetY' => 0.28
            ]
        ],
        'pre'   => [
            [
                'offsetX' => 0.18, 'offsetY' => 0.28
            ]
        ],

        'third'   => [
            [
                'offsetX' => 0.31, 'offsetY' => 0.175
            ]
        ],
        'default'   => [
            [
                'offsetX' => 0.18, 'offsetY' => 0.28
            ]
        ]
    ];

    /**
     * @param $type
     * @return mixed
     * @desc 获取章的位置
     */
    public static function getSealPositionByType($type){

        $positionArr = self::SEAL_POSITION;

        return isset($positionArr[$type]) ? $positionArr[$type] : $positionArr['default'];

    }

    /**
     * @param $type
     * @return mixed
     * @desc 获取标题
     */
    public static function getTitleByType($type){

        $titleArr = self::TITLE_ARR;

        return isset($titleArr[$type]) ? $titleArr[$type] : $titleArr['argument'];

    }

    /**
     * @param $type
     * @return mixed
     * @desc 通过type获取模板名称
     */
    public static function getBladeByType($type){

        $bladeArr = self::BLADE_ARR;

        return isset($bladeArr[$type]) ? $bladeArr[$type] : $bladeArr['argument'];

    }

    /**
     * @param $type
     * @param $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 返回协议或者合同内容的html
     */
    public static function getAgreementHtmlByType($type, $data){

        $bladeName = self::getBladeByType($type);

        return view("common.agreement.{$bladeName}", $data)->render();

    }

    /**
     * @param $type
     * @param $data
     * @param bool $isShow
     * @desc pdf生成
     */
    public static function getPdfAgreementByType($type, $data, $isShow=true){

        $renderResult = self::getAgreementHtmlByType($type, $data);

        $title = self::getTitleByType($type);

        $imgArr = self::getSealPositionByType($type);

        if(!self::creditInfo($data) && !empty($imgArr[1])){
            unset($imgArr[1]);
        }

        return ToolTcPdf::createPdfFile($title, $renderResult, $imgArr, $isShow);

    }

    /**
     * @param $pdfPath
     * 合同预览
     */
    public static function showPdfAgreement($pdfPath)
    {

        $pdfPath = base_path().'/'.$pdfPath;

        header("Content-type: application/pdf");

        readfile($pdfPath);

    }

    public static function creditInfo($data){

        if(empty($data['data']['credit'][0]['creditor_info'])){
            return true;
        }

        $creditInfo = $data['data']['credit'][0]['creditor_info'];

        $creditArr = explode(',',$creditInfo);

        if($creditArr[0] == '池洪英'){

            return false;

        }

        return true;
    }

    /**
     * @param $type
     * @return mixed
     * @desc  君子签个人签章的定位
     */
    public static function setChapterOffset( $type )
    {
        $chapter    =   self::CHAPTER_OFFSET;

        return  isset($chapter[$type]) ? $chapter[$type] : $chapter['default'] ;
    }
}
