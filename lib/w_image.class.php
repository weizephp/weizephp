<?php
/**
 * +----------------------------------------------------------------------
 * | WeizePHP framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013-2113 http://weizephp.75hh.com/ All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 * | Description: 图片处理类库
 * +----------------------------------------------------------------------
 */

class w_image {
    
    /**
     * +------------------------------------------------------
     * | 给图片添加水印[仅支持gif,jpg,png]
     * +------------------------------------------------------
     * | @param string $source 原图片 (包含路径 例子: ./images/pic.jpg )
     * | @param string $water  水印图片  (包含路径 例子: ./images/water.png )
     * | @param string $water  保存图片名  (包含路径 例子: ./images/save.jpg )
     * | @param int $position  水印位置 1顶部居左, 2顶部居中, 3顶部居右, 4居中, 5底部居左, 6底部居中, 7底部居右, 默认为随机, 这里设置为 7底部居右
     * | @param int $alpha     水印的透明度
     * +------------------------------------------------------
     * | @return int
     * |    1 图片成功添加水印
     * |    0 要添加水印的原图片路径错误或者不存在
     * |   -1 水印图片路径错误或者不存在
     * |   -2 图片添加水印操作，仅支持gif,jpg,png格式的文件
     * |   -3 复制图片生成水印图出错
     * |   -4 图片添加水印失败
     * +------------------------------------------------------
     */
    public static function water($source, $water, $savename = '', $position = 7, $alpha = 80) {
        // 检查图片是否存在
        if(!file_exists($source)) {
            return 0; // 要添加水印的原图片路径错误或者不存在
        }
        
        // 检查水印图片是否存在
        if(!file_exists($water)) {
            return -1; // 水印图片路径错误或者不存在
        }
        
        // 图片信息
        $source_info = getimagesize($source);
        $water_info  = getimagesize($water);
        if($source_info[2] > 3) {
            return -2; // 仅支持gif,jpg,png格式的文件
        }
    
        // 如果图片小于水印图片，不生成水印图片
        if($source_info[0] < $water_info[0] || $source_info[1] < $water_info[1]) {
            // 如果没有给出图片保存名,直接不生成
            if(empty($savename)) {
                return 1;
            }
            
            // 如果给出文件名,复制原图
            if(copy($source, $savename)) {
                return 1;
            } else {
                return -3; // 复制图片生成水印图出错
            }
        } else {
            // 建立图像
            switch($source_info[2]) {
                case 1:
                    $source_image = imagecreatefromgif($source);
                    break;
                case 2:
                    $source_image = imagecreatefromjpeg($source);
                    break;
                case 3:
                    $source_image = imagecreatefrompng($source);
                    break;
            }
            switch($water_info[2]) {
                case 1:
                    $water_image = imagecreatefromgif($water);
                    break;
                case 2:
                    $water_image = imagecreatefromjpeg($water);
                    break;
                case 3:
                    $water_image = imagecreatefrompng($water);
                    break;
            }
            
            // 设定图像的混色模式
            imagealphablending($water_image, TRUE);
            
            // 水印图像位置,默认为随机
            switch($position) {
                case 1: // 顶部居左
                    $position_x = 10;
                    $position_y = 10;
                    break;
                case 2: // 顶部居中
                    $position_x = ($source_info[0] - $water_info[0]) / 2;
                    $position_y = 10;
                    break;
                case 3: // 顶部居右
                    $position_x = $source_info[0] - $water_info[0] - 10;
                    $position_y = 10;
                    break;
                case 4: // 居中
                    $position_x = ($source_info[0] - $water_info[0]) / 2;
                    $position_y = ($source_info[1] - $water_info[1]) / 2;
                    break;
                case 5: // 底部居左
                    $position_x = 10;
                    $position_y = $source_info[1] - $water_info[1] - 10;
                    break;
                case 6: // 底部居中
                    $position_x = ($source_info[0] - $water_info[0]) / 2;
                    $position_y = $source_info[1] - $water_info[1] - 10;
                    break;
                case 7: // 底部居右
                    $position_x = $source_info[0] - $water_info[0] - 10;
                    $position_y = $source_info[1] - $water_info[1] - 10;
                    break;
                default: // 随机
                    $position_x = mt_rand(0, $source_info[0] - $water_info[0]);
                    $position_y = mt_rand(0, $source_info[1] - $water_info[1]);
            }
            
            // 透明处理
            //imagealphablending($source_image, FALSE);
            //imagealphablending($water_image, FALSE);
            imagesavealpha($source_image, TRUE);
            imagesavealpha($water_image, TRUE);
            
            // 生成混合图像
            imagecopymerge($source_image, $water_image, $position_x, $position_y, 0, 0, $water_info[0], $water_info[1], $alpha);
            
            // 输出图像函数
            $image_fun = '';
            switch($source_info[2]) {
                case 1:
                    $image_fun = 'imagegif';
                    break;
                case 2:
                    $image_fun = 'imagejpeg';
                    break;
                case 3:
                    $image_fun = 'imagepng';
                    break;
            }
            
            // 如果没有给出保存文件名，默认为原图像名
            if(empty($savename)) {
                $savename = $source;
                //unlink($source);
            }
            
            // 保存图像
            if($image_fun($source_image, $savename)) {
                imagedestroy($source_image);
                imagedestroy($water_image);
                return 1;
            } else {
                return -4; // 图片添加水印失败
            }
        }
    }
    
    /**
     * +------------------------------------------------------
     * | 生成缩略图 [仅支持gif,jpg,png]
     * +------------------------------------------------------
     * | @param string $image      原图 (包含路径 例子: ./images/pic.jpg )
     * | @param string $thumbname  缩略图文件名 (包含路径 例子: ./images/thumb_pic.jpg )
     * | @param string $width      最大宽度
     * | @param string $height     最大高度
     * | @param boolean $interlace 启用隔行扫描
     * +------------------------------------------------------
     * | @return int
     * |    1 缩略图成功生成
     * |    0 要缩略的原图片路径错误或者不存在
     * |   -1 无法获取图像信息，图片缩略操作失败
     * |   -2 图片缩略操作仅支持gif,jpg,png格式的文件
     * |   -3 缩略图生成失败
     * +------------------------------------------------------
     */
    public static function thumb($image, $thumbname, $width = 320, $height = 200, $interlace = TRUE) {
        // 检查图片是否存在
        if (!file_exists($image)) {
            return 0; // 要缩略的原图片路径错误或者不存在
        }
        
        $info = getimagesize($image);
        
        if($info == FALSE) {
            return -1; // 无法获取图像信息，图片缩略操作失败
        }
        
        if($info[2] > 3) {
            return -2; // 图片缩略操作仅支持gif,jpg,png格式的文件
        }
        
        $src_width = $info[0];
        $src_height = $info[1];
        $type = ''; //$type = strtolower(substr(image_type_to_extension($info[2]), 1));
        $interlace = $interlace ? 1 : 0;
        switch ($info[2]) {
            case 1:
                $type = 'gif';
                break;
            case 2:
                $type = 'jpg';
                break;
            case 3:
                $type = 'png';
                break;
            case 4:
                $type = 'swf';
                break;
            case 5:
                $type = 'psd';
                break;
            case 6:
                $type = 'bmp';
                break;
            case 7:
                $type = 'tiff'; // TIFF(intel byte order)
                break;
            case 8:
                $type = 'tiff'; // TIFF(motorola byte order)
                break;
            case 9:
                $type = 'jpc';
                break;
            case 10:
                $type = 'jp2';
                break;
            case 11:
                $type = 'jpx';
                break;
            case 12:
                $type = 'jb2';
                break;
            case 13:
                $type = 'swc';
                break;
            case 14:
                $type = 'iff';
                break;
            case 15:
                $type = 'wbmp';
                break;
            case 16:
                $type = 'xbm';
                break;
            case 17:
                $type = 'ico';
                break;
        }
        
        unset($info);
        
        // 计算缩放比例
        $scale = $src_width / $src_height;
    
        if( $width == 0 ) {
            $width  = $height * $scale;
        }
        if( $height == 0 ) {
            $height = $width / $scale;
        }
        
        if( ($src_width / $width) > ($src_height / $height) ) {
            $thumb_width  = $width;
            $thumb_height = $width / $scale;
        } else {
            $thumb_width  = $height * $scale;
            $thumb_height = $height;
        }
        
        $dst_x = ($width - $thumb_width) / 2;
        $dst_y = ($height - $thumb_height) / 2;
        
        // 载入原图
        $create_func = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
        $src_img     = $create_func($image);
        imagesavealpha($src_img, TRUE); // 这里很重要 意思是不要丢了原图像的透明色
        
        // 创建缩略图
        if($type != 'gif' && function_exists('imagecreatetruecolor')) {
            $thumb_img = imagecreatetruecolor($width, $height);
        } else {
            $thumb_img = imagecreate($width, $height);
        }
        
        // 透明处理,增加背景色
        imagealphablending($thumb_img, FALSE); // 取消默认的混色模式
        imagesavealpha($thumb_img, TRUE); // 设定保存完整的 alpha 通道信息
        $background_color = imagecolorallocate($thumb_img, 255, 255, 255); // 指派一个白色
        if('gif' == $type || 'png' == $type) {
            imagecolortransparent($thumb_img, $background_color); // 设置为透明色，若注释掉该行则输出白色的图
        } else {
            imagefilledrectangle($thumb_img, 0, 0, $width, $height, $background_color); // 画一矩形并填充
        }
        
        // 复制原始图片，进行缩放处理
        if(function_exists("imagecopyresampled")) {
            imagecopyresampled($thumb_img, $src_img, $dst_x, $dst_y, 0, 0, $thumb_width, $thumb_height, $src_width, $src_height);
        } else {
            imagecopyresized($thumb_img, $src_img, $dst_x, $dst_y, 0, 0, $thumb_width, $thumb_height, $src_width, $src_height);
        }
        
        // 对jpeg图形设置隔行扫描
        if('jpg' == $type || 'jpeg' == $type) {
            imageinterlace($thumb_img, $interlace);
        }
        
        // 生成图片
        $image_func = 'image' . ($type == 'jpg' ? 'jpeg' : $type);
        if( $image_func($thumb_img, $thumbname) ) {
            imagedestroy($thumb_img);
            imagedestroy($src_img);
            return 1; // 缩略图成功生成
        } else {
            return -3; // 缩略图生成失败
        }
    }
    
    /**
     * +------------------------------------------------------
     * | 生成缩略图缓存 [仅支持gif,jpg,png]
     * +------------------------------------------------------
     * | @param string $image      原图 (包含路径 例子: ./upload/article/20151124/pic.jpg )
     * | @param string $width      最大宽度
     * | @param string $height     最大高度
     * +------------------------------------------------------
     * | @return string 返回缩略图文件名 (包含路径 例子: ./upload/thumb/article/20151124/pic.jpg.320x200.jpg )
     * +------------------------------------------------------
     */
    public static function thumbcache($image, $width = 320, $height = 200) {
        $pos = strpos($image, 'upload');
        if( $pos === false ) {
            return $image;
        }
        if( !is_file($image) ) {
            return $image;
        }
        
        $pathinfo  = pathinfo($image);
        $extension = isset($pathinfo['extension']) ? strtolower($pathinfo['extension']) : '';
        $thumbname = str_replace('upload', 'upload/thumb', $image) . '.' . strval($width) . 'x' . strval($height) . '.' . $extension;
        
        if( is_file($thumbname) ) {
            return $thumbname;
        }
        
        $thumbdir = dirname($thumbname);
        if( !is_dir($thumbdir) ) {
            mkdir($thumbdir, 0777, true);
        }
        
        $result = self::thumb($image, $thumbname, $width, $height);
        
        if( $result == 1 ) {
            return $thumbname;
        } else {
            return $image;
        }
    }
    
}
