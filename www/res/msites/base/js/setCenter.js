;(function($){
    $.fn.setCenter = function(){
		var center = this,
      		winW  = $(window).width(),
      		winH  = $(window).height(),
			eleW  = center.width(),
			eleH  = center.height(),
			leftL = (winW - eleW)/2,
			topH  = (winH - eleH)/2;
			
		center.css({
			position: 'absolute',
			left: leftL,
			top: topH
		});
    };
})(Zepto);