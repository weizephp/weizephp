/**
 * +----------------------------------------------------------------------
 * | WeizePHP [ This is a freeware ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013 - 2113 http://weizephp.75hh.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 韦泽 <e-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 * | 文件功能：jquery文件上传插件
 * +----------------------------------------------------------------------
 * | File: jquery.ajax_file_upload.js
 */

/*

//+------------------------------
//| 使用方法
//+------------------------------
//| <span class="w_upload_image_add">[上传]</span>
//| 点击添加上传HTML标签
//+------------------------------

// 1、不带post参数的情况
$('.w_upload_image_add').click(function(e) {
	$.ajax_file_upload({url:'upload.php'}, function(data) {
		alert(data);
	});
});

// 2、带post参数的情况
$('.w_upload_image_add').click(function(e) {
	$.ajax_file_upload({url:'upload.php', input:{'name键':'value值'}}, function(data) {
		alert(data);
	});
});

*/

jQuery.extend({
	ajax_file_upload: function(options, callback) {
		
		// 如果没设置网址，返回false
		if(!options.url) {
			return false;
		}
		
		// input 数据
		if(!options.input) {
			options.input = {};
		}
		
		// 生成 CSS
		var css = '<style id="w_css" type="text/css">' +
		'#w_upload { display:none; }' +
		'#w_upload_bg { position:absolute; top:0px; left:0px; width:100%; background:#666666; filter:alpha(opacity=50); opacity:0.5; height:100%; z-index:9999; }' +
		
		'#w_upload_box { position:absolute; top:200px; left:40%; width:260px; height:auto; background:#FFFFFF; border-width:1px; border-style:solid; border-color:#009900 #006600 #009900 #006600; z-index:10000; box-shadow:3px 3px 3px #666666; }' +
		'#w_upload_box h3, #w_upload_box h3 span { height:32px; line-height:32px; font-size:12px; }' +
		'#w_upload_box h3 { margin:0px; padding-left:8px; border-bottom:1px solid #CBE6BD; color:#006600; }' +
		'#w_upload_box h3 span { float:right; width:32px; color:#C00; text-align:center; cursor:pointer; }' +
		
		'#w_form { margin:0px; padding:0px; }' +
		'#w_form_wrap { padding:13px 10px; }' +
		
		'#w_file_warp { padding-bottom:13px; }' +
		'#w_file { width:220px; }' +
		
		'.w_form_button { padding:2px 5px; *padding:4px 5px 1px; border:1px solid; border-color:#DDD #666 #666 #DDD; background:#DDD; color:#006600; cursor:pointer; }' +
		'</style>';
		
		$('head').append(css);
		css = '';
		
		// 生成input
		var input = '';
		for(var i in options.input) {
			input += '<input type="hidden" name="' + i + '" value="' + options.input[i] + '"/>';
		}
		
		// 生成HTML
		var html = '<div id="w_upload">' +
			'<div id="w_upload_bg"></div>' +
			'<div id="w_upload_box">' +
				'<h3><span class="w_close">×</span>上 传</h3>' +
				'<div id="w_form_wrap">' +
					'<form id="w_form" action="'+ options.url +'" method="post" enctype="multipart/form-data" target="w_iframe">' +
						'<div id="w_file_warp"><input id="w_file" type="file" name="file" /></div>' +
						'<div>' +
							input +
							' <input class="w_form_button" type="button" value="上 传" /> ' +
							' <input class="w_form_button w_close" type="button" value="取 消" /> ' +
						'</div>' +
					'</form>' +
					'<iframe id="w_iframe" name="w_iframe" style="display:none;"></iframe>' +
				'</div>' +
			'</div>' +
		'</div>';
		
		$('body').append(html);
		input = '';
		html = '';
		
		// 让背景宽度自适应
		$('#w_upload_bg').width( $(document).outerWidth() );
		
		// 让背景高度自适应
		$('#w_upload_bg').height( $(document).outerHeight() );
		
		// 让上传框居中显示
		$('#w_upload').show('fast', function() {
			// 计算距离左边的宽度
			var _left = ($(window).width() - $('#w_upload_box').outerWidth()) / 2;
			$('#w_upload_box').css('left', _left);
			// 计算距离头部的高度
			var _top = Math.max(document.documentElement.scrollTop, document.body.scrollTop) + ( ($(window).height() - $('#w_upload_box').outerHeight()) / 2 );
			if(_top >= 250) {
				_top = _top - 50;
			}
			// 设定居中(样式中必须定义 position:absolute; 才能居中)
			$('#w_upload_box').css('top', _top);
		});
		
		// 点击关闭
		$('.w_close').click(function(e) {
			$('#w_css').remove(); // 移除CSS
			$('#w_upload').remove(); // 移除HTML
        });
		
		// 选中文件自动提交
		$('#w_file').change(function(e) {
			$('.w_form_button').eq(0).val('上传中...');
			$('#w_form').submit();
			var i = 0;
			var intId = 0;
			intId = window.setInterval(function() {
				var data = $.trim( $('#w_iframe').contents().find('body').html() );
				// 有数据，停掉循环，然后返回数据给回调函数
				if(data != '') {
					window.clearInterval(intId);
					$('#w_css').remove(); // 移除CSS
					$('#w_upload').remove(); // 移除HTML
					callback(data);
				}
				// 循环获取数据次数，大于等于62次的话(大约31秒)，停止获取
				if(i >= 62) {
					window.clearInterval(intId);
				}
				i++;
			}, 500);
		});
		
	} // ajax_file_upload end
});