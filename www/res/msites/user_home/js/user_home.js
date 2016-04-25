// JavaScript Document
$(function(){
	personalInfo();
	$("#user-tab").dxTab();
	tabScroll();
})
//Tab fixe in top
function tabScroll() {
	var el = $("#user-tab");
	var pre = el.prev();
	var h_line = $("header").height() + $("#right-menu").height() + $("#user-inform").height();
	$(window).bind('scroll', function() {
		var d_h = $(document).height();
		var w_h = $(window).height(); 
		if(d_h < w_h*1.3)
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
			if(res && res.code == 1){
				solveTemplate("#content-now", "#content-now-data", res);
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
	$("#show-hide").on("click", function(event){
		$(this).toggleClass("slide-down");
		$("#content-now").toggle();
	});
}