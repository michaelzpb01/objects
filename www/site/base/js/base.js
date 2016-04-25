//Initialization function
var winH, winW, docH;
$(function(){
	winH = $(window).height();
	winW = $(window).width();
	docH = $(document).height();
	sidebar();
});
//Get Browser Version
var browser={
    versions:function(){
        var u = navigator.userAgent, app = navigator.appVersion;
        return {
            trident: u.indexOf('Trident') > -1, //IE内核
            presto: u.indexOf('Presto') > -1, //opera内核
            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,//火狐内核
            mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
            android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
            iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
            iPad: u.indexOf('iPad') > -1, //是否iPad
            webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
            weixin: u.indexOf('MicroMessenger') > -1, //是否微信
            weibo: u.indexOf('weibo') > -1, //是否微博
            qq: u.match(/\sQQ/i) == " qq" //是否QQ
        };
    }(),
    language:(navigator.browserLanguage || navigator.language).toLowerCase()
}
// Get Scrolled Top
function getScrollTop(){
    var scrollTop=0;
    if(document.documentElement&&document.documentElement.scrollTop){
        scrollTop=document.documentElement.scrollTop;
    } else if(document.body){
        scrollTop=document.body.scrollTop;
    }
    return scrollTop;
}
//Return to top of the page
function goTop(){
	var goTop = $('#goTop');
	var apply = $('.apply-btn');
	goTop.hide();
	apply.hide();

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
}

//Slide in right menu
function sidebar(){
	var sidebar = $('#sidebar'),
		menu = $('#menu'),
		bg = $('.sidebar-bg');

	menu.on('click', function(event){
		$("#right-menu").addClass("slideIn");
		bg.show();
		bg.add(sidebar).on('touchmove', function(event){
			event.preventDefault();
		})
	});

	bg.on('click', function(){
		$("#right-menu").removeClass("slideIn");
		$('body').css('overflow', '');
	});
}

//Get url Parameter
function getUrlParameter(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}
//Top tip bar
function showTip(str) {
	var tip_box = '<div id="errorCue"><span id="errorMsg"></span></div>';
	if($('#errorCue'))
	{
		$('#errorCue').remove();
	}
	$(tip_box).appendTo("body");
	$('#errorMsg').html(str);
	$('#errorCue').removeClass().addClass('show');
	setTimeout(function(){
		$('#errorCue').removeClass('show');
	}, 2000);
}
//Alert Layer
function alertOpen(str) {
	var alert_tag = '<section id="alert-layer"><div id="layer-content"><p id="alert-con"></p><p class="auto-close"><span id="close-time">5</span>s后自动关闭</p><span id="layer-close"></span></div></section>';
	if($("#alert-layer"))
	{
		$("#alert-layer").remove();
	}
	$(alert_tag).appendTo("body");
	$("#alert-layer").height(docH);
	$("#alert-con").html(str);
	setTimeout(alertClose,1000);
	function alertClose() {
		var t = $("#close-time").html();
		t--;
		if(t>0)
		{
			$("#close-time").html(t);
			setTimeout(alertClose,1000);
		}
		else {
			$("#alert-layer").remove();
		}
	}
	$("#layer-close").bind("click",function(){
		$("#alert-layer").remove();
	});
}