<?php include 'header.html.php';?>
            
            <div class="setup">
                <h2>配置系统</h2>
            </div>
            
            <div class="main">
                <form method="post" action="setting.php">
                    
                    <!-- 设置 -->
                    <div class="tb_box tb_box_w400">
                        <!-- 填写数据库信息 -->
                        <table class="tb" width="100%" border="0">
                            <tr>
                                <th colspan="2">填写数据库信息</th>
                            </tr>
                            <tr>
                                <td class="td_w150">数据库主机：</td>
                                <td><input type="text" name="dbhost" value="localhost"/></td>
                            </tr>
                            <tr>
                                <td class="td_w150">数据库端口：</td>
                                <td><input type="text" name="dbport" value="3306"/></td>
                            </tr>
                            <tr>
                                <td class="td_w150">数据库用户名：</td>
                                <td><input type="text" name="dbuser" value="root"/></td>
                            </tr>
                            <tr>
                                <td class="td_w150">数据库密码：</td>
                                <td><input type="text" name="dbpw" value=""/></td>
                            </tr>
                            <tr>
                                <td class="td_w150">数据库名：</td>
                                <td><input type="text" name="dbname"></td>
                            </tr>
                            <tr>
                                <td class="td_w150">数据表前缀：</td>
                                <td><input type="text" name="tablepre" value="w_"/></td>
                            </tr>
                        </table>
                        <!-- 填写管理员信息 -->
                        <table class="tb" width="100%" border="0">
                            <tr>
                                <th colspan="2">填写管理员信息</th>
                            </tr>
                            <tr>
                                <td class="td_w150">管理员账号：</td>
                                <td><input type="text" name="username" value="admin"/></td>
                            </tr>
                            <tr>
                                <td class="td_w150">管理员账号确认：</td>
                                <td><input type="text" name="username_again" value="admin"/></td>
                            </tr>
                            <tr>
                                <td class="td_w150">管理员密码：</td>
                                <td><input type="password" name="password"/></td>
                            </tr>
                            <tr>
                                <td class="td_w150">管理员密码确认：</td>
                                <td><input type="password" name="password_again"/></td>
                            </tr>
                            <tr>
                                <td class="td_w150">管理员 Email：</td>
                                <td><input type="text" name="email" value="admin@admin.com"/></td>
                            </tr>
                            <tr>
                                <td class="td_w150">管理员 Email 确认：</td>
                                <td><input type="text" name="email_again" value="admin@admin.com"/></td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- 操作表单 -->
                    <div class="form_box">
                        <input type="button" value="上一步" onClick="javascript:history.back();"/>
                        - <input type="submit" value="立即安装"/>
                    </div>
                    
                </form>
            </div>
            
<?php include 'footer.html.php';?>