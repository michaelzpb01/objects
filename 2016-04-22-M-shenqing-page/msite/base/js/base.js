//Initialization function
var winH, winW, docH;
var dataStatus = 0; //ajax post number
$(function(){
	winH = $(window).height();
	winW = $(window).width();
	docH = $(document).height();
	sidebar();
	goTop();
	$("#go-back").bind("click",goBack);
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
//show or hide the collect case
function goTop(){
	var goTop = $('#goTop');
	var apply = $('.bottom-btn');
	goTop.hide();
	apply.hide();

	$(window).on('scroll', function() {
		var scroll_top = getScrollTop();
		if(scroll_top > 50){
			apply.show();
		} else{
			apply.hide();
		}
		if(scroll_top >= winH*2){
			goTop.show();
		}else{
			goTop.hide();
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
//solve Template
function solveTemplate(contentId, tplId, data){
    	var content = $(contentId),
			tpl = $(tplId).html(),
			render = _.template(tpl),
			html = render(data);

		content.html(html);
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
	setTimeout(function(){
			$('#errorCue').removeClass().addClass('show');
	    setTimeout(function(){
		    $('#errorCue').removeClass('show');
	    }, 2000);
	},1);
}
//Alert Layer
function alertOpen(str) {
	var alert_tag = '<section id="alert-layer"><div id="layer-content"><p id="alert-con"></p><p class="auto-close"><span id="close-time">5</span>s后自动关闭</p><span id="layer-close" class="iconfont icon-close"></span></div></section>';
	if($("#alert-layer"))
	{
		$("#alert-layer").remove();
	}
	$(alert_tag).appendTo("body");
	$("#alert-layer").height(docH);
	$("#alert-con").html(str);
	var timer = setTimeout(alertClose,1000);
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
			clearTimeout(timer);
		}
	}
	$("#layer-close").bind("click",function(){
		$("#alert-layer").remove();
	});
}
//Alert (Confirm button)
function alertConfirm(str) {
	var alert_tag = '<section id="confirm-layer"><div id="layer-content"><p id="alert-con"></p><div id="layerbtns"><a id="layer-cancel" href="javascript:">取消</a><a id="layer-ok" href="javascript:">确定</a></div></div></section>';
	if($("#confirm-layer"))
	{
		$("#confirm-layer").remove();
	}
	$(alert_tag).appendTo("body");
	$("#alert-layer").height(docH);
	$("#alert-con").html(str);
	$("#layer-ok").bind("click",function(){
		var e = "ok";
		$("#confirm-layer").trigger(e);
		$("#confirm-layer").remove();
		window.location.href = "mobile-login.html";
	});
	$("#layer-cancel").bind("click",function(){
		var e = "cancel";
		$("#confirm-layer").trigger(e);
		$("#confirm-layer").remove();
	});
	
}
//Go back page
function goBack()  
{  
	window.history.back();
	return false;
}   

//No data message
function noAll(str, id) {
	var tip_box = '<div class="no-order"><span class="no-Info"></span></div>';
	$(id).css('position', 'relative');
	$(tip_box).appendTo(id);
	$(id).find(".no-Info").html(str);
}

//reload page
function reLoad(id){
	var tip_box = '<div class="no-order"><span class="no-Info">找不到数据辣，请重新<a id="F5">刷新</a>~</span></div>';
	$(id).css('position', 'relative');
	$(tip_box).appendTo(id);
	$("#F5").click(function(){
		window.location.reload();
	});
}