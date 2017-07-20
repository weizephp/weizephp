<?php
/**
 * +----------------------------------------------------------------------
 * | WeizePHP framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013-2113 http://weizephp.75hh.com/ All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 * | Description: MySQL连接类库
 * +----------------------------------------------------------------------
 */

/**
使用示例:
---------------------------------
// 连接数据库
$wdb = new w_mysqli('localhost', 'root', '', 'weizephp');
if( $wdb->connect_error ) {
    die( 'Connect Error (' . $wdb->connect_errno . ') ' . $wdb->connect_error );
}
if( !$wdb->set_charset('utf8') ) {
    die( "Error loading character set utf8: {$wdb->error}" );
}
---------------------------------
// 获取一条数据
$sql = "SELECT * FROM `w_user`";
$row = $wdb->get_row($sql);
---------------------------------
// 获取多条数据
$sql = "SELECT * FROM `w_user`";
$lists = $wdb->get_all($sql);
---------------------------------
// 分页
$sql    = "SELECT * FROM `w_user`";
$data   = $wdb->pagination($sql, 1);
$output = $wdb->pagination_output();
foreach($data as $val) {} // 遍历分页数据
echo $output; // 输出分页HTML
---------------------------------
*/

class w_mysqli extends mysqli {
    
    /**
     * SQL查询
     * @param string $query, SQL query.
     * @param int $resultmode
     * @return TRUE/FALSE/Object
     */
    public function query($query, $resultmode = MYSQLI_STORE_RESULT) {
        $result = parent::query($query, $resultmode);
        if($result === FALSE) {
            $date    = date('Y-m-d H:i:s');
            $message = "Invalid query: {$this->errno} {$this->error}. Date: {$date}. SQL: {$query}";
            if(defined('W_DISPLAY_DEBUG')) {
                $message = "<?php exit; ?> {$message}\n";
                error_log($message, 3, W_ROOT_PATH . '/data/log/' . date('Ymd') . '_mysql_error.php');
            } else {
                die($message);
            }
        }
        return $result;
    }
    
    /**
     * 获取一条数据，如果没有数据，就返回空数组
     */
    public function get_row($sql) {
        $result = $this->query($sql);
        if( $result === false ) {
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
    public function get_all($sql) {
        $result = $this->query($sql);
        if( $result === false ) {
            return array();
        }
        $arr = array();
        while( $row = $result->fetch_assoc() ) {
            $arr[] = $row;
        }
        return $arr;
    }
    
    /** ------------------------------------------------- */
    
    /**
     * 分页
     * 使用实例：
     *   $wdb = new w_mysqli();
     *   $sql    = "SELECT * FROM `{$wconfig['db']['tablepre']}user`";
     *   $data   = $wdb->pagination($sql);
     *   $output = $wdb->pagination_output();
     *   echo $output; // 输出分页HTML . 格式: [上一页] [1..] [52] [52] 53 [54] [55] [..666] [下一页]
     */
    public $pagination_num_rows = 0; // 记录总数
    public $pagination_total    = 0; // 分页总数
    public $pagination_cur_page = 1; // 当前页
    public $pagination_prev     = '上一页';
    public $pagination_next     = '下一页';
    public $pagination_range    = 2; // 当前页两头显示分页数字的范围
    
    // 获取分页记录
    public function pagination($sql, $page_size = 10) {
        // 总记录数
        $result = $this->query($sql);
        $this->pagination_num_rows = $result === false ? 0 : $result->num_rows;
        // 获取数据
        if( $this->pagination_num_rows > 0 ) {
            $this->pagination_total    = ceil($this->pagination_num_rows / $page_size); // 分页总数
            $this->pagination_cur_page = isset($_GET['page']) && ($_GET['page'] > 0) ? $_GET['page'] : 1; // 当前页
            if( $this->pagination_cur_page > $this->pagination_total ) {
                $this->pagination_cur_page = $this->pagination_total;
            }
            $step_size = ($this->pagination_cur_page - 1) * $page_size;
            $sql = $sql . " LIMIT {$step_size}, {$page_size}";
            return $this->get_all($sql);
        } else {
            return array();
        }
    }
    
    // 获取URL参数。$unset 用于去掉不需要传值的参数，多个用,隔开。
    public function get_url($unset = '') {
        $list = array();
        $keys = explode(',', $unset);
        foreach ($_GET as $key => $val) {
            if( (preg_match('/^[a-zA-Z0-9_]+$/', $key) == 1) && !in_array($key, $keys) ) {
                $list[] = $key . '=' . urlencode($val);
            }
        }
        return implode('&amp;', $list);
    }
    
    // 分页HTML输出
    public function pagination_output($simple = false) {
        // 如果只有一页，返回空
        if($this->pagination_total < 2) {
            return '';
        }
        // 定义URL变量
        $url = $this->get_url('page');
        $url = empty($url) ? '?page=' : '?' . $url . '&amp;page=';
        // 上一页
        $prev = '';
        if($this->pagination_cur_page > 1) {
            //$prev = '<a href="' . $url . ($this->pagination_cur_page - 1) . '">' . $this->pagination_prev . '</a>';
            $prev = '<li><a href="' . $url . ($this->pagination_cur_page - 1) . '">' . $this->pagination_prev . '</a></li>';
        }
        // 第一页
        $first = '';
        if( $this->pagination_cur_page > ($this->pagination_range + 1) ) {
            //$first = '<a href="' . $url . '1">1...</a>';
            $first = '<li><a href="' . $url . '1">1...</a></li>';
        }
        // 中间页码数字
        $links = '';
        if($simple == false) {
            for($i = $this->pagination_range; $i >= 1; $i--) {
                $page = $this->pagination_cur_page - $i;
                if($page < 1) {
                    continue;
                }
                //$links .= '<a href="' . $url . $page . '">' . $page . '</a>';
                $links .= '<li><a href="' . $url . $page . '">' . $page . '</a></li>';
            }
            //$links .= '<span class="cur">' . $this->pagination_cur_page . '</span>';
            $links .= '<li class="active"><a href="javascript:void(0);">' . $this->pagination_cur_page . '</a></li>';
            for($i = 1; $i <= $this->pagination_range; $i++) {
                $page = $this->pagination_cur_page + $i;
                if($page > $this->pagination_total) {
                    break;
                }
                //$links .= '<a href="' . $url . $page . '">' . $page . '</a>';
                $links .= '<li><a href="' . $url . $page . '">' . $page . '</a></li>';
            }
        }
        // 最后一页
        $last = '';
        if( ($this->pagination_total - $this->pagination_cur_page) > $this->pagination_range ) {
            //$last = '<a href="' . $url . $this->pagination_total . '">...' . $this->pagination_total . '</a>';
            $last = '<li><a href="' . $url . $this->pagination_total . '">...' . $this->pagination_total . '</a></li>';
        }
        // 下一页
        $next = '';
        if($this->pagination_cur_page < $this->pagination_total) {
            //$next = '<a href="' . $url . ($this->pagination_cur_page + 1) . '">' . $this->pagination_next . '</a>';
            $next = '<li><a href="' . $url . ($this->pagination_cur_page + 1) . '">' . $this->pagination_next . '</a></li>';
        }
        // 返回HTML
        if($simple == false) {
            //return $prev . $first . $links . $last . $next;
            return '<ul class="pagination">' . $prev . $first . $links . $last . $next . '</ul>';
        } else {
            //return $prev . $next;
            return '<ul class="pagination">' . $prev . $next . '</ul>';
        }
    }
    
}
