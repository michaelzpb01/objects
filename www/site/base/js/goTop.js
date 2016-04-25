function goTop(){
	var winH = $(window).height();
	var goTop = $('#goTop');
	var apply = $('.apply-btn');
	goTop.hide();
	apply.hide();
	function getScrollTop()
	{
	    var scrollTop=0;
	    if(document.documentElement&&document.documentElement.scrollTop)
	    {
	        scrollTop=document.documentElement.scrollTop;
	    }
	    else if(document.body)
	    {
	        scrollTop=document.body.scrollTop;
	    }
	    return scrollTop;
	}

	$(window).on('scroll', function() {
		var scroll_top = getScrollTop();
		if(scroll_top < winH){
			goTop.hide();
			apply.hide();
		} else{
			goTop.show();
			apply.show();
		}
	});
	goTop.on('click', function(){
		document.body.scrollTop = 0;
	});
};

