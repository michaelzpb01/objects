// Xushu Edit 2015-11-03
//判断设备
var browser={
    versions:function(){
            var u = navigator.userAgent, app = navigator.appVersion;
            return {         //移动终端浏览器版本信息
                trident: u.indexOf('Trident') > -1, //IE内核
                presto: u.indexOf('Presto') > -1, //opera内核
                webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
                mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或uc浏览器
                iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
                iPad: u.indexOf('iPad') > -1, //是否iPad
                webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
            };
         }(),
         language:(navigator.browserLanguage || navigator.language).toLowerCase()
}
var device;
var app;
if(browser.versions.iPhone || browser.versions.iPad) {
	device = "apple";
}
if(browser.versions.android) {
	device = "android";
}
//获取滚动高度
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
//测试是否为微信
function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
//测试是否为微博
function isWeiBo() {
	var ua = window.navigator.userAgent.toLowerCase();
	if(ua.indexOf('weibo') > -1) {
		return true;
	}
}
$(function(){
	getPerson();
	$("#go-back").on('click', function(){
		goBack();
	});
	//底部提交按钮显示隐藏
	$("#free-btn").hide("fast");
	var w_h = $(window).height();
	var w_w = $(window).width();
	var d_h = $(document).height();
	var content_h = $("#manager-banner").height()+$("#quality").height();
	var tip_h =  $("#apply-total").height() + $("#apply-tip").height();
	var tip_margin = parseInt($("#apply-tip").css("margin-bottom")) + parseInt($("#apply-total").css("margin-bottom")); 
	var line = content_h - tip_h -tip_margin;
	var wxCue = '<p id="tip"><i class="tip-icon"></i><span id="tip-con"></span></p>';
	var footer_top =  d_h - w_h - $("#footer").height() - $("#bottom-tel").height();
	$(document).scroll(function() {
		var scroll_top = getScrollTop();
		if(scroll_top>line)
		{
			$("#free-btn").show("fast");
			if(scroll_top>footer_top) {
				$("#free-btn").addClass("document-bottom");
			}
			else {
				$("#free-btn").removeClass();
			}
		}
		else {
			$("#free-btn").hide("fast");
		}
	});
	$("#free-btn").bind("click",gotoReg);
	//输入框获取焦点后，屏幕滚动
	$("#user-name").bind("focus",gotoReg);
	function gotoReg() {
		var input_top = content_h - $("#apply-form").height() - parseInt($("#features-1").css("margin-bottom"));
		if(device == "apple")
		{
			setTimeout(function(){managerPage.scrollTop  = input_top+10;},10);
		}
		else {
			managerPage.scrollTop  = input_top;
		}

		$("#user-name").focus();
	}
	//顶部红色提示条
	function showInfo(msg){
		$('#errorMsg').html(msg);
	    $('#errorCue').removeClass().addClass('show');
		if(device == "apple")
		{
			$('#errorCue').addClass("ios");
			$('#errorCue').css({top: managerPage.scrollTop});
			$(window).scroll(function(){
				$('#errorCue').css({top: managerPage.scrollTop});
			});
		}
	    setTimeout(function(){
	        $('#errorCue').removeClass('show');
	    }, 2000);
	}
	//输入框上部提示语
	function nameTip(str) {
		$("#tip").remove();
		$(wxCue).appendTo("#name-box");
		$("#tip-con").html(str);
		setTimeout(function(){
			$("#tip").remove();
		},2000);
	}
	//输入框上部提示语
	function phoneTip(str) {
		$("#tip").remove();
		$(wxCue).appendTo("#phone-box");
		$("#tip-con").html(str);
		setTimeout(function(){
			$("#tip").remove();
		},2000);
	}
	//选择省提示语
	function provinceTip(str) {
		$("#tip").remove();
		$(wxCue).appendTo("#province");
		$("#tip-con").html(str);
		setTimeout(function(){
			$("#tip").remove();
		},2000);
	}
    //选择城市提示语
	function cityTip(str) {
		$("#tip").remove();
		$(wxCue).appendTo("#city");
		$("#tip-con").html(str);
		setTimeout(function(){
			$("#tip").remove();
		},2000);
	}
	//输入框检测
	function checkName(){
		if($("#user-name").val()=='') {
			if(isWeiXin() || isWeiBo()){
				nameTip('您的姓名不能为空');
			}
			else {
				showInfo('您的姓名不能为空');
			}
            $("#user-name").focus();
            return false;
        }
        var reg = /^[\u4E00-\u9FA5]+$/;
	    var userName = $("#user-name").val();
	    if (!reg.test(userName)) {
			if(isWeiXin() || isWeiBo()){
				nameTip('请填写中文姓名');
			}
			else {
				showInfo('请填写中文姓名');
			}
	        $("#user-name").focus();
	        return false;
	    }
		if($("#user-name").val().replace(/[^x00-xff]/g,"**").length>20) {
			if(isWeiXin() || isWeiBo()){
				nameTip('您的称呼保持在10个字以内');
			}
			else {
				showInfo('您的称呼保持在10个字以内');
			}
			$("#user-name").focus();
			return false;
		}
	}
	function checkPhone(){
		if($("#user-pwd").val()=='') {
			if(isWeiXin() || isWeiBo()){
				phoneTip('请填写电话');
			}
			else {
				showInfo('请填写电话');
			}
            $("#user-pwd").focus();
            return false;
        } 
		else {
            var tel = /^1[3|4|5|7|8|9][0-9]\d{8}$/;
            if(!tel.test($("#user-pwd").val())) {
				if(isWeiXin() || isWeiBo()){
					phoneTip('您的电话输入有误，请重新填写');
				}
				else {
					showInfo('您的电话输入有误，请重新填写');
				}
                $("#user-pwd").focus();
                return false;
            }
        }
	}

	//获取URL的用户id
	function getUrlParameter(name) {
	    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
	    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
	}
	//发送提交请求
    function checkForms() {
    	var fromPage; //来源变量
    	if(getUrlParameter("id")){
    		fromPage = getUrlParameter("id");
    	}else{
    		fromPage = $("#source").val();
    	}
    	if(checkName() == false) {
			return false;
		}
		if(checkPhone() == false){
			return false;
		}
		if(wbbm_province==0)
		{
			if(isWeiXin() || isWeiBo()){
				provinceTip('请选择城市');
				return false;
			}
			else {
				showInfo('请选择您所在的城市');
				return false;
			}
		}
        $.ajax({
        	type: "POST",
        	url: "api/mmwap.php?action=fbform&title="+$("#user-name").val()+"&telephone="+$("#user-pwd").val()+"&areaid="+wbbm_province+"&source="+fromPage+"&time="+new Date().getTime(),
        	dataType: "json",
        	beforeSend: function(){
				$('#applyBtn').attr('disabled', 'disabled').val("提交中...");
				$("#myform").addClass("disabled");
        	},
            success: function(res){
            	if(res['flag']){
            		alertOpen(res['msg']);
            		getPerson();
					res="";
					$(".apply-input").val("");
					$("#select-01").val("0");
            	}else if(!res['flag']){
					alertOpen(res['msg']);
					$(".apply-input").val("");
					$("#select-01").val("0");
				}
            },
            complete: function(){
            	$('#applyBtn').removeAttr('disabled').val("免费申请");
				$("#myform").removeClass("disabled");
            },
			error: function(){
				$('#applyBtn').removeAttr('disabled').val("免费申请");
            	alert('您当前网络异常');
            }
        });
        return false;
    }
	//微博报名
	function weiboCheckForms() {
    	if(checkName() == false) {
			return false;
		}
		if(checkPhone() == false){
			return false;
		}
		var source = getUrlParameter("id");
		if(wbbm_province==0)
		{
			if(isWeiXin() || isWeiBo()){
				provinceTip('请选择城市');
				return false;
			}
			else {
				showInfo('请选择您所在的城市');
				return false;
			}
		}
	    if(wbbm_citys==0)
		{
			if(isWeiXin() || isWeiBo()){
				cityTip('请选择城市');
				return false;
			}
			else {
				showInfo('请选择您所在的城市');
				return false;
			}
		}
        $.ajax({
        	type: "GET",
			url: 'api/mwap.php?action=fbform&title='+$("#user-name").val()+'&telephone='+$("#user-pwd").val()+'&select='+wbbm_province+'&select_1='+wbbm_citys+'&source='+source,
        	dataType: "json",
        	beforeSend: function(){
				$('#applyBtn').attr('disabled', 'disabled').val("提交中...");
				$("#myform").addClass("disabled");
        	},
            success: function(res){
            	if(res['flag']){
            		alertOpen(res['msg']);
            		getPerson();
					res="";
					$(".apply-input").val("");
					$(".apply-select").val("0");
            	}else if(!res['flag']){
					alertOpen(res['msg']);
					$(".apply-input").val("");
					$(".apply-select").val("0");
				}
            },
            complete: function(){
            	$('#applyBtn').removeAttr('disabled').val("一秒报名");
				$("#myform").removeClass("disabled");
            },
			error: function(){
				$('#applyBtn').removeAttr('disabled').val("一秒报名");
            	alert('您当前网络异常');
            }
        });
        return false;
    }
	//获取报名人数
    function getPerson(){
    	$.ajax({
    		type: "GET",
    		url: "api/wap.php?action=fbcount",
    		dataType: "text",
    		success: function(number){
    			if(number){
    				$('#userTotal').text(number);
    			}
    		},
			error: function(){
            	alert('您当前网络异常');
            }
    	});
    }
	//弹出层方法
	function setLayerTop() {
		var layer_top = (w_h- $("#layer-content").height())/2+managerPage.scrollTop;
		$("#layer-content").css("top",layer_top);
	}
	function alertOpen(str) {
		var alert_tag = '<section id="alert-layer"><div id="layer-content"><p id="alert-con"></p><p class="auto-close"><span id="close-time">5</span>s后自动关闭</p><span id="layer-close"></span></div></section>';
		$(alert_tag).appendTo("body");
		$("#alert-layer").height(d_h);
		$("#alert-con").html(str);
		setLayerTop();
		$(window).scroll(setLayerTop);
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
	$("#myform").on("submit", function() {
		return checkForms();
	});
	$("#user-pwd").on('focus', function(){
		checkName();
	});
    $("#weibo-myform").on("submit", function() {
		return weiboCheckForms();
	});
	$("#user-pwd").on('focus', function(){
		checkName();
	});
})
