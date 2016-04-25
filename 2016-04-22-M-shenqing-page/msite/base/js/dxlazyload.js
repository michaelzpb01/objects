// DX lazyload 2015-12-24 
(function($) {
  $.fn.dxLazyLoad = function(options) {
	  
	//Global variable
    var defaults = {
      mode: "fade",
	  pre_dis: 100,
      next_dis: 200,
	  attr_name: "data-original",
	  touch_delay: 100
    };
	
    var opt = $.extend(defaults,options);
    var pics = $(this);
    var win_h = $(window).height();
	var need_show = [];
	var need_load = [];
	var timer;
	
	//Init
	this.init = function() {
		$(pics).addClass("load-hide");
		needShow();
	    loadPic();
		$(document).bind("scroll",function(e){
		  	clearTimeout(timer);
			timer = setTimeout(preLoad,opt.touch_delay);
		});
	}
	
	//Calculate some pictures need to load
    var needShow = function(){
      var pic_bottom = 0;
	  var scroll_top = document.body.scrollTop-opt.pre_dis;
      for(var i=0; i<pics.length; i++) {
		var pic = pics[i];
		pic_top = pic.offsetTop;
		pic_bottom = pic_top + $(pic).height();
		if(pic_bottom>scroll_top && pic_top<scroll_top+win_h+opt.next_dis) {
			need_load.push(pic);
		}
		if(pic_bottom>scroll_top && pic_top<document.body.scrollTop+win_h) {
			$(pic).attr("need-show",1);
			need_show.push(pic);
			showPic();
		}
	  }
    }
	
	//Load function
	var loadPic = function() {
      for(var i=0; i<need_load.length; i++){
	    var load_el = need_load[i];
		var load_src = $(load_el).attr(opt.attr_name);
		if($(load_el).attr("load-state") != 1){
		  $(need_load[i]).attr("src",load_src);
		  need_load[i].onload=function(){
			console.log("loaded");
			var el = this;
	        $(el).attr("load-state",1);
			showPic();
	      }
		}
	  }
	}
	
	//Picture fade in
	function showPic() {
	  for(var i=0; i<need_show.length; i++){
	      var load_el = need_show[i];
		  var load_src = $(load_el).attr(opt.attr_name);
		  if($(load_el).attr("need-show") == 1 && $(load_el).attr("load-state") == 1){
			var el = need_load[i];
	        $(load_el).addClass("fadeIn"); 
		  }
	  }
	}
	
	//Preload function
	var preLoad = function() {
	  needShow();
	  loadPic();
	  showPic();
	}
	
	this.init();
  }
})(Zepto);