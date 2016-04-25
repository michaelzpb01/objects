(function($) {
	$.fn.dxTab = function(options) {
		var defaults = {
			mode: "click",
			clsName: "current",
			start: "0",
		};
		var opt = $.extend(defaults,options);
		var m = opt.mode;
		var clsName = opt.clsName;
		var tabs = $(this);
		var tab = tabs.find('li');
		var targets = [];
		tab.removeClass(clsName);
		$(tab).eq(opt.start).addClass(clsName);
		tab.each(function(index, element) {
            var tab_id = $(this).attr('target');
			targets[tab_id] = $('#'+tab_id);
			if(index == opt.start)
			{
				targets[tab_id].show();
			}
			else {
				targets[tab_id].hide();
			}
        });
		tab.bind(m,function(){
			if($(this).hasClass('disabled'))
			{
				return;
			}
			tab.removeClass(clsName);
			$(this).addClass(clsName);
			var target = $(this).attr('target');
			for(var key in targets)
			{
				if(key == target)
				{
					targets[key].show();
				}
				else {
					targets[key].hide();
				}
			}
		});
	}
})(Zepto);

