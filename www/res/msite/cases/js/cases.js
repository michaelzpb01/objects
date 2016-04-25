$(function(){
	goTop();
	getCases(null, null);
	$(window).bind("scroll", scrollPages);
	getSx();
	sxSlider();
});
var city_id = getCookie("cityid") ? getCookie("cityid") : "3360";
//筛选页面滑入滑出
var styletype = housetype = null;

//滑动状态, 当前页数
var isLoading = true,
	page = 1,
	totalPage = 0;
	docH = 0;

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
		$(window).bind("scroll", scrollPages);
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
			page = 1;
			$("#case-container ul").remove();
			$(".down-page").show();
			getCases(styletype, housetype);
			$(window).bind("scroll", scrollPages);
		}
	});
}

//获取精品案例数据
function getCases(style, house){
	isLoading = false;
	var caseContent = '<ul id=page'+page+'></ul>';
	$("#case-container").append(caseContent);
	var request = $.ajax({
		type: "POST",
		url: "index.php?m=wap&f=biz_photo&v=listing&cityid="+city_id+"&page="+page+"&style="+style+"&house="+house,
		dataType: "json",
		timeout: 2000,
		success: function(res){
			console.log(res)
			if(res && res.code == 1){
				var data = res.data;
				totalPage = data.finalpage;
				solveTemplate("#page"+page, "#case-data", data);
				docH = $(document).height();
				if(totalPage <= 1){
					$(".down-page").remove();
				}
				$(".lazy-img").dxLazyLoad();
				imgLoaded();
				if(data.tishi){
					noAll(data.tishi, "#content-now");
				}
				isLoading = true;
				page++;
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

function scrollPages(){
	var pageH = $(".down-page").height();
	var sTop = document.body.scrollTop + 100;
	if(pageH + sTop >= docH - winH && isLoading){
		if(page <= totalPage){
			getCases(styletype, housetype);
		}else{
			$(".down-page").hide();
		}
	}
}