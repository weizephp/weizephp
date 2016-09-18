/**
 * +----------------------------------------------------------------------
 * | WeizePHP framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013-2113 http://weizephp.75hh.com/ All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 */

(function($) {
	$.fn.extend({
		wCaptchaInit: function(options) {
			var d          = new Date();
			var _this      = $(this);
			$.wCKey        = '';
			$.wCExpireTime = 0;
			$.wCidUrl      = 'generals.php?m=captcha&a=getckey';
			$.wSrcUrl      = 'generals.php?m=captcha&a=create';
			$.getJSON($.wCidUrl, function(data) {
				$.wCKey        = data.ckey;
				$.wCExpireTime = d.getTime() + (data.expire * 1000);
				_this.attr('src', $.wSrcUrl +'&ckey='+ $.wCKey +'&t=' + d.getTime());
			});
		},
		wGetCid: function() {
			return $.wCKey;
		},
		wCaptchaReset: function() {
			var d = new Date();
			if(d.getTime() > $.wCExpireTime) {
				$(this).wCaptchaInit();
			} else {
				$(this).attr('src', $.wSrcUrl +'&ckey='+ $.wCKey +'&t=' + d.getTime());
			}
		}
	});
})(jQuery);
