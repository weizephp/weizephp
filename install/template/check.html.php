<?php include 'header.html.php';?>
            
            <div class="setup">
                <h2>检查安装环境</h2>
            </div>
            
            <div class="main">
                
                <div>
                    <!-- 环境检查 -->
                    <table class="tb" width="100%" border="0">
                        <tr>
                            <th colspan="2">环境检查</th>
                        </tr>
                        <tr>
                            <td>操作系统</td>
                            <td class="td_w200"><?php echo $system_info['php_os']; ?></td>
                        </tr>
                        <tr>
                            <td>PHP 版本</td>
                            <td class="td_w200"><?php echo $system_info['php_version']; ?></td>
                        </tr>
                        <tr>
                            <td>附件上传大小</td>
                            <td class="td_w200"><?php echo $system_info['file_upload']; ?></td>
                        </tr>
                        <tr>
                            <td>GD 库</td>
                            <td class="td_w200"><?php echo $system_info['gd']; ?></td>
                        </tr>
                    </table>
                    
                    <!-- 目录、文件读写权限检查 -->
                    <table class="tb" width="100%" border="0">
                        <tr>
                            <th colspan="2">目录、文件读写权限检查</th>
                        </tr>
                        <?php
                        $result = array();
                        foreach($checking_dirs as $v) {
                            $path = '../'.$v;
                            if(file_exists($path) && is_writable($path)) {
                                echo '<tr><td class="td_w200"> ./'. $v .' </td><td> √ 可写 </td></tr>';
                            } else {
                                $result[] = false;
                                echo '<tr><td class="td_w200 color_red"> ./'. $v .' </td><td class="color_red"> × 不可写 </td></tr>';
                            }
                        }
                        ?>
                    </table>
                </div>
                
                <!-- 操作表单 -->
                <div class="form_box">
                    <form method="get" autocomplete="off" action="setting.php">
                        <input type="button" value="上一步" onClick="javascript:history.back();"/>
                        <?php
                        if(empty($result)) {
                        	echo ' - <input type="submit" value="下一步"/>';
                        } else {
                        	echo ' - <input type="submit" disabled="disabled" value="下一步"/>';
                        }
                        ?>
                    </form>
                </div>
                
            </div>
            
<?php include 'footer.html.php';?>