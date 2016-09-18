<?php
/**
 * +----------------------------------------------------------------------
 * | WeizePHP framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013-2113 http://weizephp.75hh.com/ All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 */

class w_upload {
    
    // 上传文件最大值，默认为2M
    public $max_size = 2;
    
    // 允许上传的文件文件后缀，留空不做检查
    public $allow_extension = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
    
    // 允许上传的文件类型&MIME限定(如果浏览器提供此信息的话)，留空不做检查。例子 array('image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/bmp')
    public $allow_type = array();
    
    // 上传文件保存目录，注意路径 ./
    public $save_path = './data/attachments';
    
    // 上传文件保存名（不加后缀，默认为空时，表示时间命名）
    public $save_name = '';
    
    // 按照日期自动创建存储文件夹. TRUE 为是 FALSE 为否
    public $date_folder = TRUE;
    
    // 存在同名是否覆盖. TRUE 为是 FALSE 为否
    public $overwrite = FALSE;
    
    /**
     * 上传文件
     */
    public function upload($field) {
        // 获取上传error代码信息
        switch($field['error']) {
            //case 0:
            //    return 0; // UPLOAD_ERR_OK 没有错误发生，文件上传成功
            //    break;
            case 1:
                return 1; // UPLOAD_ERR_INI_SIZE 上传的文件大小超过了系统配置的限制
                break;
                
            case 2:
                return 2; // UPLOAD_ERR_FORM_SIZE 上传文件的大小超过了表单规定的最大值
                break;
                
            case 3:
                return 3; // UPLOAD_ERR_PARTIAL 文件只有部分被上传
                break;
                
            case 4:
                return 4; // UPLOAD_ERR_NO_FILE 没有文件被上传
                break;
                
            case 6:
                return 6; // UPLOAD_ERR_NO_TMP_DIR 找不到临时文件夹
                break;
                
            case 7:
                return 7; // UPLOAD_ERR_CANT_WRITE 文件写入失败
                break;
        }
        
        //--------------------
        
        // 检查文件大小（2M = 2*1024*1024 Byte）
        if($field['size'] > ($this->max_size * 1024 * 1024)) {
            return 8; // 上传文件大小不符
        }
        
        // 如果Mime类型不为空，检查文件Mime类型
        if(!empty($this->allow_type) && !in_array(strtolower($field['type']), $this->allow_type)) {
            return 9; // 上传文件MIME类型不允许
        }
        
        // 检查文件后缀类型
        $pathinfo = pathinfo($field['name']);
        $extension = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
        if(!empty($this->allow_extension) && !in_array(strtolower($extension), $this->allow_extension, TRUE)) {
            return 10; // 上传的文件后缀类型不允许
        }
        
        // 检查是否合法上传
        if(!is_uploaded_file($field['tmp_name'])) {
            return 11; // 非法上传文件
        }
        
        // 如果是图像文件 检测文件格式，并获取宽高
        $imagesize = getimagesize($field['tmp_name']);
        if( in_array(strtolower($extension), array('gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf')) && FALSE === $imagesize ) {
            return 12; // 非法图像文件
        }
        $width  = 0;
        $height = 0;
        if(FALSE !== $imagesize) {
            $width  = $imagesize[0];
            $height = $imagesize[1];
        }
        
        //--------------------
        
        // 如果保存路径末尾有 / ，则去掉 /
        if(substr($this->save_path, -1) == '/') {
            $this->save_path = substr($this->save_path, 0, -1);
        }
        
        // 如果保存文件夹不可写，返回错误码
        if( !is_writable($this->save_path) ) {
            return 13; // 文件夹不可写
        }
        
        // 返回当前的 Unix 时间戳
        $time = time();
        
        // 如果文件保存名为空，那么以时间加随机数创建文件名
        if(empty($this->save_name)) {
            $this->save_name = $time . '_' . mt_rand(1000, 9999);
        }
        
        // 定义“时间路径”临时变量
        $tmp_path_date = '';
        
        // 是否按照日期自动创建存储文件夹
        if($this->date_folder == TRUE) {
            $tmp_path_date = date('Ymd', $time);
            $this->save_name = $tmp_path_date . '/' . $this->save_name; // 改变文件名目录
        }
        
        // “保存路径”临时变量
        $tmp_save_path = !empty($tmp_path_date) ? $this->save_path . '/' . $tmp_path_date : $this->save_path;
        
        // 构建文件的保存名（增加后缀）
        $this->save_name .= '.' . $extension;
        
        // 完整的保存名
        $filename = $this->save_path .'/'. $this->save_name;
        
        // 同名文件是否覆盖
        if( ($this->overwrite == FALSE) && file_exists($filename) ) {
            return 14; // 同名文件已经存在
        }
        
        // 如果存储目录不存在，则创建
        if(!is_dir($tmp_save_path)) {
            $result = mkdir($tmp_save_path, 0777, TRUE); // 递归创建目录 (PHP 5 >= 5.0.0)
            if($result == FALSE) {
                return 15; // 保存文件夹创建失败
            }
        }
        
        //--------------------
        
        // 计算指定文件的 MD5 散列值
        $md5 = md5_file($field['tmp_name']);
        
        // 保存文件
        if(!move_uploaded_file($field['tmp_name'], $filename)) {
            return 16; // 将上传的文件移动到新位置出错
        }
        
        // 改变文件模式
        chmod($filename, 0777);
        
        // 删除变量
        unset($field['tmp_name'], $pathinfo, $time, $tmp_path_date, $tmp_save_path);
        
        // 成功，返回数组
        return array(
            'name'      => $field['name'],
            'save_name' => $this->save_name,
            'save_path' => $this->save_path,
            'filename'  => $filename,
            'extension' => $extension,
            'md5'       => $md5,
            'size'      => $field['size'],
            'type'      => $field['type'],
            'width'     => $width,  // 图片宽度，其他文件显示0
            'height'    => $height, // 图片高度，其他文件显示0
        );
    }
    
}
