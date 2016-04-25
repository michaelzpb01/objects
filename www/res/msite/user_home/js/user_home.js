// User home
var user_data;
var favor_designer;

$(function(){
	personalInfo();
	getLiveList();
	$("#user-tab").dxTab();
	tabScroll();
	$("#order-box").add("#building-list").add("#my-favor").css("min-height", winH);

	getCompany();
	getDesigner();
	getLive();

	$("#logout").bind("click",logout);
})
//Tab fixe in top
function tabScroll() {
	var el = $("#user-tab");
	var pre = el.prev();
	var h_line = $("header").height() + $("#right-menu").height() + $("#user-inform").height();
	$(window).bind('scroll', function() {
		var d_h = $(document).height();
		var w_h = $(window).height(); 
		if(d_h < w_h)
		{
			return;
		}
		var scroll_top = document.body.scrollTop;
		if(scroll_top < h_line){
			el.removeClass("fixedTab");
		} else{
			el.addClass("fixedTab");
		}
	});
}
function personalInfo(){
	var request = $.ajax({
		type: "POST",
		url: "index.php?m=wap&f=member&v=index",
		dataType: "json",
		timeout: 2000,
		success: function(res){
			if(res && res.code == 1){
				console.log(res)
				var data = res.data;
				solveTemplate("#user-inform", "#user-inform-data", data);
				bindData();
				personalCollectCases();
			}else{
				window.location.href = "mobile-login.html?user_home=1";
			}
		},
		error: function(XMLHttpRequest, textStatus){
			dataStatus = dataStatus+1;
			if(textStatus == "timeout"){
				personalInfo();
			}
		}
	});
	if(request && dataStatus == 2){
		request.abort();
		$("#user-tab").remove();
		reLoad("#user-inform");
	}
}
function bindData() {
	var request = $.ajax({
		type: "POST",
		url: "index.php?m=wap&f=member&v=listing",
		dataType: "json",
		timeout: 2000,
		success: function(res){
			if(res && res.code == 1){
				var data = res.data;
				if(data.tishiyu){
					noAll(data.tishiyu, "#order-box");
				}else{
					solveTemplate("#order-list", "#order-data", res);
					$(".order-days").each(function(){
						if($(this).children("b").text() == "未开工"){
							$(this).css("color", "#999");
						}
					});
					$(".local-name h3").each(function(){
						var str = $(this).text();
						if(str.length > 11){
							var newStr = str.substr(0, 11)+"...";
							$(this).text(newStr);
						}
					});
				}
			}
		},
		error: function(XMLHttpRequest, textStatus){
			dataStatus = dataStatus+1;
			if(textStatus == "timeout"){
				bindData();
			}
		}
	});
	if(request && dataStatus == 3){
		request.abort();
		reLoad("#order-box");
	}
}

function personalCollectCases(){
	var request = $.ajax({
		type: "POST",
		url: "index.php?m=wap&f=member&v=photos",
		dataType: "json",
		timeout: 2000,
		success: function(res){
			console.log(res)
			if(res && res.code == 1){
				solveTemplate("#content-now", "#content-now-data", res);
				$("#works-list").bind("click", function(){
					$(".lazy-img").dxLazyLoad();
					imgLoaded();
				});
				showTab();
			}
		},
		error: function(XMLHttpRequest, textStatus){
			dataStatus = dataStatus+1;
			if(textStatus == "timeout"){
				personalCollectCases();
			}
		}
	});
	if(request && dataStatus == 5){
		request.abort();
		reLoad("#content-now");
	}
}

function showTab(){
	var number = $("#content-now .content-li").length;
	$("#total-number").text(number);
	$(".slide-tag").on("click", function(event){
		var number = $(this).find(".number").html()*1;
		if(number == 0){
			return;
		}
		$(this).toggleClass("slide-down");
		$(this).next(".slide_content").toggle();
	});
}

function getCompany(){
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=member&v=company",
		dataType: "json",
		timeout: 2000,
		success: function(data){
			if(data.code == 1)
			{
				user_data = data.data;
				solveTemplate("#company-list", "#company-list-template", data.data);
			}
		},
		error: function(XMLHttpRequest, textStatus){
			dataStatus = dataStatus+1;
			if(textStatus == "timeout"){
				//getCases(styletype, housetype);
			}
		}
	});
}


function getDesigner(){
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=member&v=design",
		dataType: "json",
		timeout: 2000,
		success: function(data){
			console.log(data)
			if(data.code == 1)
			{
				favor_designer = data.data;
				solveTemplate("#designer-list", "#company-designer-template", favor_designer);
			}
		},
		error: function(XMLHttpRequest, textStatus){
			dataStatus = dataStatus+1;
			if(textStatus == "timeout"){
				//getCases(styletype, housetype);
			}
		}
	});
}

function logout() {
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=password&v=logout",
		dataType: "json",
		timeout: 2000,
		success: function(data){
			if(data.code == 1)
			{
				window.location.href = "mobile-index.html"
			}
		},
		error: function(XMLHttpRequest, textStatus){
			dataStatus = dataStatus+1;
			if(textStatus == "timeout"){
				//getCases(styletype, housetype);
			}
		}
	});
}

function getLive() {
	$.ajax({
		type: "POST",
		url: "/index.php?m=wap&f=member&v=day_log",
		dataType: "json",
		timeout: 2000,
		success: function(res){
			console.log(res)
			if(res && res.code == 1) {
				var data = res.data;
				solveTemplate("#live-list", "#live-list-data", res);
				var number = $("#live-list .list-li").length;
				$("#live-number").text(number);
			}
		},
		error: function(XMLHttpRequest, textStatus){
			console.log(textStatus)
		}
	});
}


//获取我的工地信息
function getLiveList() {
	$.ajax({
		type: "POST",
		url: "/index.php?m=wap&f=member&v=log",
		dataType: "json",
		success: function(res) {
			console.log(res)
			if(res.code == 0) {
				noAll(res.message, "#list-content");
			}
			if(res.code == 1) {
				solveTemplate("#list-content", "#list-content-data", res);
				$(".lazy-img").dxLazyLoad();
			}
		}
	});
}