$(function(){
	goTop();
	getCases(null, null);
	getSx();
	sxSlider();

});
//筛选页面滑入滑出
var styletype = housetype = null;
function sxSlider(){
	var sxLink = $("#shaixuan-link"),
		sxSlide = $("#sx-slide"),
		sxClose = $("#sx-close"),
		items = $("#shaixuan-content"),
		all = $("#all-choose");

	sxLink.on("click", function(){
		sxSlide.addClass("slideIn");
		stopScroll("#sx-slide");
	});

	sxClose.on("click", function(){
		sxSlide.removeClass("slideIn");
		allowScroll();
	});

	all.on("click", function(){
		$("#shaixuan-content dd").removeClass();
		$(this).addClass("active-style");
		sxSlide.removeClass("slideIn");
		allowScroll();
		getCases(null, null);
	});

	items.on("click", function(event){
		if(event.target.nodeName == "DD"){
			styletype = $(event.target).attr("styletype") ? $(event.target).attr("styletype"):null;
			housetype = $(event.target).attr("housetype") ? $(event.target).attr("housetype"):null;

			all.removeClass("active-style");
			$(this).children().children().removeClass();
			$(event.target).addClass("active-style");
			sxSlide.removeClass("slideIn");
			allowScroll();
			getCases(styletype, housetype);
		}
	});
}

//获取精品案例数据
function getCases(style, house){
	var request = $.ajax({
		type: "POST",
		url: "index.php?m=wap&f=biz_photo&v=listing&style="+style+"&house="+house,
		dataType: "json",
		timeout: 2000,
		success: function(res){
			if(res && res.code == 1){
				var data = res.data;
				solveTemplate("#content-now", "#case-data", data);
				$(".lazy-img").dxLazyLoad();
				if(data.tishi){
					noAll(data.tishi, "#content-now");
				}
			}
		},
		error: function(XMLHttpRequest, textStatus){
			dataStatus = dataStatus+1;
			if(textStatus == "timeout"){
				getCases(styletype, housetype);
			}
		}
	});
	if(request && dataStatus == 3){
		request.abort();
		$(".content-li").remove();
		reLoad("#content-now");
	}
}

//获取筛选数据
function getSx(){
	var request = $.ajax({
		type: "POST",
		url: "index.php?m=wap&f=biz_photo&v=screen",
		dataType: "json",
		timeout: 2000,
		success: function(res){
			if(res && res.code == 1){
				var data = res.data;
				solveTemplate("#shaixuan-content", "#sx-data", data);
			}
		},
		error: function(XMLHttpRequest, textStatus){
			dataStatus = dataStatus+1;
			if(textStatus == "timeout"){
				getSx();
			}
		}
	});
	if(request && dataStatus == 5){
		request.abort();
		$("#all-choose").remove();
		reLoad(".shaixuan-box");
	}
}

function stopScroll(id){
	$(id).on('touchmove', function(event){
		event.preventDefault();
	})
}
function allowScroll(){
	$('body').css('overflow', '');
}