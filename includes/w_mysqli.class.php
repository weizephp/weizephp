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

class w_mysqli extends mysqli {
    public $result = NULL; // 结果集
    
    /**
     * 错误报告
     */
    public function w_mysql_error_log($message) {
        global $_W;
        if($_W['error_reporting'] === true) {
            error_log($message, 3, ROOT_PATH.'/data/log/'.date('Ymd').'_mysql_error.php');
        } else {
            die($message);
        }
    }
    
    /**
     * SQL询问
     */
    public function query( $sql, $resultmode = MYSQLI_STORE_RESULT ) {
        $result = parent::query($sql);
        if( $result === FALSE ) {
            $this->w_mysql_error_log( 'Invalid query: ' . $this->errno . ' '. $this->error. '. SQL: ' . $sql );
        }
        return $result;
    }
    
    /**
     * 获取一条数据，如果没有数据，就返回空数组
     */
    public function get_row( $sql ) {
        $result = $this->query($sql);
        if( $result == FALSE ) {
            return array();
        }
        $row = $result->fetch_assoc();
        if( empty($row) ) {
            return array();
        }
        return $row;
    }
    
    /**
     * 获取所有数据，如果没有数据，就返回空数组
     */
    public function get_all( $sql ) {
        $result = $this->query($sql);
        if( $result == FALSE ) {
            return array();
        }
        $arr = array();
        while( $row = $result->fetch_assoc() ) {
            $arr[] = $row;
        }
        return $arr;
    }
    
    /**
     * 分页
     * +----------------------------------
     * [!]采用了 Bootstrap 的html分页结构
     * [!]此分页不适合大数据，日后需要改进
     */
    public function paged($sql, $page_size = 10, $simple = false) {
        $paged_data  = array();
        $paged_html  = '';
        
        $total_pages = 0;
        $paged       = 0;
        
        $result = $this->query($sql);
        $count  = $result->num_rows;
        
        //--------------------------
        
        if($count > 0) {
            $total_pages = ceil($count / $page_size);
            $paged       = isset($_GET['paged']) ? intval($_GET['paged']) : 0;
            $paged       = $paged > 0 ? $paged : 1;
        
            if($paged > $total_pages) {
                $paged = $total_pages;
            }
        
            $step_size = ($paged - 1) * $page_size;
            $sql = $sql ." LIMIT ". $step_size .",". $page_size;// LIMIT 0,10
        
            $paged_data = $this->get_all($sql);
        } else {
            $paged_data = array();
        }
        
        //--------------------------
        
        $list = array();
        foreach($_GET as $key => $value) {
            if($key != 'paged') {
                $list[] = $key .'='. urlencode($value);
            }
        }
        $page_url = !empty($list) ? '?'. htmlspecialchars(implode('&', $list)) .'&amp;paged=' : '?paged=';
        
        //--------------------------
        
        if($total_pages > 1) {
            if($paged > 1) {
                $prev = '<li><a href="'. $page_url . ($paged - 1) .'">上一页</a></li>';
            } else {
                $prev = '<li class="disabled"><a href="javascript:void(0);" aria-label="Previous"><span aria-hidden="true">上一页</span></a></li>';
            }
            
            $num_html = '';
            
            if($simple == false) {
                $arr = array();
                if($total_pages < 6) {
                    $arr = range(1, $total_pages);
                } else {
                    if($paged > 3) {
                        $arr = range(1, 5);
                    } else if(($paged <= $total_pages) && ($paged > ($total_pages - 3))) {
                        $arr = range($total_pages - 4, $total_pages);
                    } else {
                        $arr = range($paged - 2, $paged + 2);
                    }
                }
                foreach($arr as $value) {
                    if($value == $paged) {
                        $num_html .= '<li class="active"><a href="javascript:void(0);">'. $value .' <span class="sr-only">(current)</span></a></li>';
                    } else {
                        $num_html .= '<li><a href="'. $page_url . $value .'">'. $value .'</a></li>';
                    }
                }
            }
        
            if($paged < $total_pages) {
                $next = '<li><a href="'. $page_url . ($paged + 1) .'">下一页</a></li>';
            } else {
                $next = '<li class="disabled"><a href="javascript:void(0);" aria-label="Next"><span aria-hidden="true">下一页</span></a></li>';
            }
            
            $paged_html = '<ul class="pagination">' . $prev . $num_html . $next . '</ul>';
        } else {
            $paged_html = '';
        }
        
        //--------------------------
        
        return array('paged_data' => $paged_data, 'paged_html' => $paged_html);
    }
}
