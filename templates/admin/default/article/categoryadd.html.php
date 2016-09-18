<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">分类添加</h4>
    <form class="w-form">
        <div class="form-group">
            <label for="pid">上级</label>
            <select class="form-control" id="pid" name="pid">
                <option value="0">≡ 作为一级 ≡</option>
                <?php
                foreach($categories as $v) {
                    echo '<option value="'. $v['categoryid'] .'">'. $v['categoryname_fmt'] .'</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="categoryname">分类名称</label>
            <input type="text" class="form-control" id="categoryname" name="categoryname"/>
        </div>
        <div class="form-group">
            <label for="status">是否显示</label>
            <select class="form-control" id="status" name="status">
                <option value="0">否</option>
                <option value="1" selected="selected">是</option>
            </select>
        </div>
        <div class="form-group">
            <label for="displayorder">排序</label>
            <input type="text" class="form-control" id="displayorder" name="displayorder" value="10"/>
        </div>
        <div class="form-group">
            <label for="seotitle">SEO标题</label>
            <input type="text" class="form-control" id="seotitle" name="seotitle"/>
            <span class="help-block">限60个字符</span>
        </div>
        <div class="form-group">
            <label for="seokeywords">SEO关键字</label>
            <input type="text" class="form-control" id="seokeywords" name="seokeywords"/>
            <span class="help-block">限100个字符</span>
        </div>
        <div class="form-group">
            <label for="seodescription">SEO简介</label>
            <textarea class="form-control" rows="3" id="seodescription" name="seodescription"></textarea>
        </div>
        <input type="hidden" id="formtoken" name="formtoken" value="<?php echo $formtoken;?>"/>
        <button type="submit" class="btn btn-success">提 交</button>
    </form>
    
    <?php include W_ROOT_PATH . '/templates/w_modals.html.php';?>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.w-form').submit(function() {
            var formData = $(this).serialize();
            $.post("?m=article&a=categoryadd", formData, function(result) {
                if(result.status == 1) {
                	w_b_modal_success('操作成功', function() {
                    	window.location.href = '?m=article&a=categorylist';
                    });
                } else {
                	w_b_modal_error(result.msg);
                }
            }, 'json');
            return false;
        });
    });
    </script>
    
<?php include $_W['template_path'].'/footer.html.php';?>
