var device;
var app;
if(browser.versions.iPhone || browser.versions.iPad) {
	device = "apple";
}
if(browser.versions.android) {
	device = "android";
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

var temp = getUrlParameter("temp"); //活动编号
var url = encodeURIComponent(window.location.href.split("#")[0]);
wxShare(url, "hdmb", temp);

var city_status = 0;
var choose_province = 0; //获取省份 0为未选择
var choose_city = 0; //获取城市 0为未选择
var fromPage; //来源变量
var wxCue = '<p id="tip"><i class="tip-icon"></i><span id="tip-con"></span></p>';
$(function(){
	getTopic();
	getPerson();

});
function dataLoaded(){
		//底部提交按钮显示隐藏
		$("#free-btn").hide("fast");
		var w_h = $(window).height();
		var w_w = $(window).width();
		var d_h = content_h = 0;
		var d_h = $(document).height();
		var contentTopH = $("#content-top").height()+$("#apply-form").height()?$("#content-top").height()+$("#apply-form").height():$("#apply-form").height();
		var headerH = $("header").height()?$("header").height():0;
		var content_h = contentTopH + headerH;
		var tip_h =  $("#apply-total").height();

		var line = content_h - tip_h;
		var footer_top =  d_h - w_h - $("#footer").height();
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
			var input_top = $("#content-top").height() - 56 - headerH;
			if(device == "apple")
			{
				setTimeout(function(){managerPage.scrollTop  = input_top+10;},10);
			}
			else {
				managerPage.scrollTop  = input_top;
			}

			$("#user-name").focus();
		}
};
//顶部红色提示条
function showInfo(msg){
	$('#errorMsg').html(msg);
    $('#errorCue').removeClass().addClass('show');
	if(device == "apple"){
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
//获取报名人数
function getPerson(){
	$.ajax({
		type: "GET",
		url: "api/wap.php?action=fbcount&temp="+temp,
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

//获取专题详情
function getTopic(){
	$.ajax({
		type: "POST",
		url: "api/hd.php?action=fbform&temp="+temp+"&idurl="+url,
		dataType: "json",
		timeout: 3000,
		success: function(res){
			console.log(res)
			if(res && res.code == 1){
				var data = res.data;
				if(data.status == 1){
					if(data.type == 2){
						$(".container").css({"padding-top": 0});
						$("#swiper-container").css('height', winH);
						$(".normal-form").add("#free-btn").add("#footer-bottom-tel").add("#footer").remove();
						solveTemplate(".swiper-wrapper", "#swiper-box-data", data);

						var swiper = new Swiper('#swiper-container', {
						    pagination: '.swiper-pagination',
						    // loop : true,
						    paginationClickable: true,
						    direction: 'vertical'
						});

						if(data.background){
							$("#apply-form").parent(".swiper-slide").css({
								"background-image": "url("+data.background+")",
								"background-repeat": " no-repeat",
								"background-size": "cover"
							});
						}

						// var go_top_div = '<div class="go-top"><i class="iconfont icon-evaarrow"></i></div>';
						var swiperLen = $(".swiper-slide").length - 1;

						$(".swiper-slide").last().find(".go-top").remove();
					} else {
						$("#swiper-container").remove();
						solveTemplate("#content-top", "#top-activity-data", data);
    					solveTemplate("#content-bottom", "#bottom-activity-data", data);

    					if(data.background){
							$("#apply-form").css({
								"background": "url("+data.background+") no-repeat"
							});
	    				}
					}

    				var imgNum = 0;
    				var load_img = $(".lazy-img");
    				for(var i=0; i<load_img.length; i++){
    					load_img[i].onload = function(){
    						imgNum++;
    						if(imgNum==load_img.length){
    							dataLoaded();
    						}
    					}
    				}
    				if(data.title){
    					$(".header-title").html(data.title);
    				}
    				if(data.button){
    					$("#applyBtn").val(data.button);
    					$("#free-btn").html(data.button);
    				}
    				if(data.status_1 == 2){
    					$("#footer-bottom-tel").remove();
    				}
    				if(data.status_2 == 2){
    					$("#free-btn").remove();
    				}
    				if(data.status_3 == 2){
    					$("#footer").remove();
    				}
    				if(data.status_4 == 2){
    					$("header").remove();
    					$(".container").css({"padding-top": 0});
    				}
    				if(data.color){
    					$("#applyBtn").add("#free-btn").css({
    						"background-color": "#"+data.color,
    						"border-color": "#"+data.color
    					});
    				}
    				if(data.color1){
    					$("#applyBtn").add("#free-btn").css({"color": "#"+data.color1});
    				}
    				if(data.color2){
    					$("#userTotal").css({"color": "#"+data.color2});
    				}
    				if(data.color3){
    					$("#apply-total").css({"color": "#"+data.color3});
    				}
    				if(data.city == 1){
    					city_status = 1;
    					$("#city").remove();
    					$("#province").removeClass("fl");
    					$("#select-01").removeClass("apply-select").addClass("open-city");
    					solveTemplate("#select-01", "#province-data", data);
    					$("#select-01").bind("change", function(){
    						var selected_1 = $(this).find('option').not(function(){ return !this.selected });
    						choose_province = selected_1.val();
    					});
    				}else if(data.city == 2){
    					city_status = 2;
    					solveTemplate("#select-01", "#province-data", data);
    					$("#select-01").bind("change", function(){
    						var selected_1 = $(this).find('option').not(function(){ return !this.selected });
    						choose_province = selected_1.val();
    						getCitys(choose_province);
    					});
    				}

    				$("#user-pwd").on('focus', function(){
    					checkName();
    				});
    				$("#myform").on("submit", function() {
    					if(city_status == 1){
    						return checkForms();
    					}else if(city_status == 2){
    						return allCitysCheckForms();
    					}
    				});
				}else{
					$("#apply-form").add("#footer-bottom-tel").add("#free-btn").add("#footer").remove();
					alert("活动过期啦~来首页看看更多活动吧");
					window.location.href = "mobile-index.html";
				}
			}
		},
		error: function(XMLHttpRequest, textStatus){
			if(textStatus == "timeout"){
				$("#apply-form").add("#footer-bottom-tel").add("#footer").remove();
				reLoad("body");
			}
		}
	});
}
function getCitys(select_id){
	$.ajax({
		type: "POST",
		url: "api/hd.php?action=fbform&temp="+temp+"&select="+select_id,
		dataType: "json",
		timeout: 3000,
		success: function(res){
			if(res && res.code == 1){
    			var data = res.data;
    			solveTemplate("#select-02", "#city-data", data);
    			$("#select-02").bind("change", function(){
					var selected_2 = $(this).find('option').not(function(){ return !this.selected });
					choose_city = selected_2.val();
				});
			}
		},error: function(XMLHttpRequest, textStatus){
			console.log(textStatus);
		}
	});
}
//发送开通城市请求
function checkForms() {
	if(getUrlParameter("id")){
		var source = getUrlParameter("id");
		if(isWeiXin()){
			fromPage = "Mweixin-" + source;
		}else if(isWeiBo()){
			fromPage = "Mweibo-" + source;
		}else{
			fromPage = "M-" + source;
		}
	}else{
		var source = $("#source").val();
		if(isWeiXin()){
			fromPage = "Mweixin-" + source;
		}else if(isWeiBo()){
			fromPage = "Mweibo-" + source;
		}else{
			fromPage = "M-" + source;
		}
	}
	console.log(fromPage)
	if(checkName() == false) {
		return false;
	}
	if(checkPhone() == false){
		return false;
	}
	if(choose_province==0)
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
    	url: "api/hd.php?action=fbcount&city="+city_status+"&title="+$("#user-name").val()+"&telephone="+$("#user-pwd").val()+"&areaid="+choose_province+"&source="+fromPage+"&time="+new Date().getTime(),
    	dataType: "json",
    	beforeSend: function(){
			$('#applyBtn').attr('disabled', 'disabled');
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
        	$('#applyBtn').removeAttr('disabled');
			$("#myform").removeClass("disabled");
        },
		error: function(){
			$('#applyBtn').removeAttr('disabled');
        	alert('您当前网络异常');
        }
    });
    return false;
}
//发送全国城市请求
function allCitysCheckForms() {
	if(getUrlParameter("id")){
		var source = getUrlParameter("id");
		if(isWeiXin()){
			fromPage = "Mweixin-" + source;
		}else if(isWeiBo()){
			fromPage = "Mweibo-" + source;
		}else{
			fromPage = "M-" + source;
		}
	}else{
		var source = $("#source").val();
		if(isWeiXin()){
			fromPage = "Mweixin-" + source;
		}else if(isWeiBo()){
			fromPage = "Mweibo-" + source;
		}else{
			fromPage = "M-" + source;
		}
	}
	console.log(fromPage+2);
	if(checkName() == false) {
		return false;
	}
	if(checkPhone() == false){
		return false;
	}
	if(choose_province==0)
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
    if(choose_city==0)
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
    	type: "POST",
		url: 'api/hd.php?action=fbcount&city='+city_status+'&title='+$("#user-name").val()+'&telephone='+$("#user-pwd").val()+'&select='+choose_province+'&select_1='+choose_city+'&source='+fromPage,
    	dataType: "json",
    	beforeSend: function(){
			$('#applyBtn').attr('disabled', 'disabled');
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
        	$('#applyBtn').removeAttr('disabled');
			$("#myform").removeClass("disabled");
        },
		error: function(){
			$('#applyBtn').removeAttr('disabled');
        	alert('您当前网络异常');
        }
    });
    return false;
}
//弹出层方法
function setLayerTop() {
	var layer_top = (w_h- $("#layer-content").height())/2+managerPage.scrollTop;
	$("#layer-content").css("top",layer_top);
}
