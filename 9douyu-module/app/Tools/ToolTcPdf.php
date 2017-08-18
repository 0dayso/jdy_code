<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/10/18
 * Time: 上午11:57
 */

namespace App\Tools;

use TCPDF;

class ToolTcPdf
{

    /**
     * @param $title
     * @param $data
     * @param array $imgArr
     * @param bool $isShow
     * @return string
     * @desc 生成pdf,包含显示和下载
     */
    public static function createPdfFile($title, $data, $imgArr=[], $isShow=true)
    {
        
        //实例化
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $page = $pdf->getPage();

        // 设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        // 设置间距
        $pdf->SetMargins(15, 15, 15);

        //距底部
        $pdf->setFooterMargin(10);

        // 设置分页
        $pdf->SetAutoPageBreak(TRUE, 20);

        // set image scale factor
        $pdf->setImageScale(1.25);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        //设置字体
        $pdf->SetFont('stsongstdlight', '', 14);

        $pdf->AddPage();

        if( count($imgArr) > 1 && count(explode('分割线', $data)) > 1 ){

            $data = explode('分割线', $data);

            $pdf->writeHTML($data[0]);

            $page = $pdf->getPage();

            $pdf->setPage($page);

            if(!empty($imgArr[0]['p']))
            {
                $pdf->setPage($imgArr[0]['p']);
            }

            $pdf->SetXY($imgArr[0]['x'], $imgArr[0]['y']);
            $imageUrl1 = assetUrlByCdn('/static/img/'.$imgArr[0]['img']);
            $image1 = file_get_contents(str_replace('https://','http://',$imageUrl1));
            $pdf->Image('@'.$image1, '', '', 0, 0, '', '', '', false, 300, '', false, false, 1, false, false, false);

            $pdf->setPage($page);

            if(!empty($imgArr[0]['p']))
            {
                $pdf->setPage($imgArr[0]['p']);
            }

            $pdf->writeHTML($data[1]);
            $imageUrl2 = assetUrlByCdn('/static/img/'.$imgArr[1]['img']);
            $image2 = file_get_contents(str_replace('https://','http://',$imageUrl2));
            $pdf->Image('@'.$image2, '', '', 0, 0, '', '', '', false, 300, '', false, false, 1, false, false, false);


            //$pdf->Image(public_path().'/static/img/'.$imgArr[1]['img'], $imgArr[1]['x'], $imgArr[1]['y'], '', '', '', '',true);

        }else{

            //写入
            $pdf->writeHTML($data);

            $page = $pdf->getPage();
            //增加图片
            foreach ( $imgArr as $info ){

                $pdf->setPage($page);

                if(!empty($info['p']))
                {
                    $pdf->setPage($info['p']);
                }

                $pdf->SetXY($info['x'], $info['y']);
                $imageUrl = assetUrlByCdn('/static/img/'.$info['img']);
                $image = file_get_contents(str_replace('https://','http://',$imageUrl));
                $pdf->Image('@'.$image, '', '', 0, 0, '', '', '', false, 300, '', false, false, 1, false, false, false);

                //$pdf->Image(public_path().'/static/img/'.$info['img'], $info['x'], $info['y'], '', '', '', '',true);

            }
        }

        $pdf->SetTitle($title);

        //输出PDF
        if( $isShow ){

            return $pdf->Output($title.'.pdf','S');

        }else{//下载

            $pdf->Output($title.'.pdf', 'D');

        }
    }

    /**
     * @return bool
     * @desc 这里是tcpdf参数示例
     */
    private function paramHelp()
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // 设置文档信息
        $pdf->SetCreator('Hello');
        $pdf->SetAuthor('demo');
        $pdf->SetTitle('Welcome to 9douyu.com!');
        $pdf->SetSubject('TCPDF');
        $pdf->SetKeywords('TCPDF, PDF, PHP');

        // 设置页眉和页脚信息
        $pdf->SetHeaderData('', 30, '9douyu.com', '测试',
            array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('stsongstdlight', '', '10'));
        $pdf->setFooterFont(Array('helvetica', '', '8'));

        // 设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        // 设置间距
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // 设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);

        // set image scale factor
        $pdf->setImageScale(1.25);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        //设置字体
        $pdf->SetFont('stsongstdlight', '', 14);

        $pdf->AddPage();

        $str1 = '欢迎来到9douyu.com';

        $pdf->Write(0,$str1,'', 0, 'L', true, 0, false, false, 0);

        return false;

        //输出PDF
        $pdf->Output('demo.pdf', 'D');

    }

    /**
     * @param string $filePath
     * @return int
     * @desc 获取PDF文件的页数
     */
    public static function getPdfPage( $filePath = '' )
    {
        if( empty($filePath) ){
            return 0;
        }
        $maxPage    =   0;
        // 打开文件
        if ( ! $fileInfo = @fopen($filePath,"r") ) {

            return $maxPage;
        }
        while( !feof($fileInfo) ) {

            $line = fgets($fileInfo,255);

            if ( preg_match('/\/Count [0-9]+/', $line, $matches ) ) {

                preg_match('/[0-9]+/',$matches[0], $matches2);

                if ( $maxPage<$matches2[0] ) $maxPage=$matches2[0];
            }
        }
        fclose($fileInfo);
        // 返回页数
        return $maxPage;
    }

}