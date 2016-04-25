$(function() {
	goTop();
	getList();
	getSx();
	getSort();
	$(window).bind("scroll", scrollPages);
});
var sortStatus = nodeId = 0;
if(getUrlParameter("status")){
	nodeId = getUrlParameter("status");
}

var isLoading = true, //ajax请求状态
	page = 1,		  //列表页数
	totalPage = 0,	  //总页数
	docH = 0;

function getSx() {
	var sxBtn     = $('#shaixuan-link'),
		sxBox     = $('#sx-box'),
		// sxContent = $('#sx-content'),
		sxBg      = $('#sx-bg');

	sxBtn.bind('click', function() {
		sxBox.toggleClass('slideIn');
		sxBg.toggle();
		sxBg.add(sxContent).bind('touchmove', function(event) {
			event.preventDefault();
		});
	});

	sxBg.bind('click', function() {
		sxBox.removeClass('slideIn');
		sxBg.hide();
	});
	if(getUrlParameter("status") == 0){
		$("#get_all").addClass("active-style");
	}

	$(".sx-li").bind('click', function(){
		$(".sx-li").removeClass("active-style");
		$(this).addClass("active-style");
		sxBox.removeClass('slideIn');
		sxBg.hide();
	});
	// sxContent.bind('click', function(event) {
	// 	if(event.target.nodeName == 'LI') {
	// 		nodeId = $(event.target).attr('status');
	// 		$(this).find('li').removeClass('active-style');
	// 		$(event.target).addClass('active-style');
	// 		sxBox.removeClass('slideIn');
	// 		sxBg.hide();
	// 		page = 1;
	// 		$("#list-container .list-ul").remove();
	// 		$(".down-page").show();
	// 		getList();
	// 		$(window).bind("scroll", scrollPages);
	// 	}
	// });
}
function getSort() {
	var newSort = $('#new-sort'),
		maxSort = $('#max-sort');

	newSort.bind('click', function() {
		sortStatus = $(this).attr('status');
		$(this).addClass('active').siblings().removeClass('active');
		page = 1;
		$("#list-container .list-ul").remove();
		$(".down-page").show();
		getList();
		$(window).bind("scroll", scrollPages);
	});

	maxSort.bind('click', function() {
		sortStatus = $(this).attr('status');
		$(this).addClass('active').siblings().removeClass('active');
		page = 1;
		$("#list-container .list-ul").remove();
		$(".down-page").show();
		getList();
		$(window).bind("scroll", scrollPages);
	});
}

function getList() {
	isLoading = false;
	var liveContent = '<ul id=page'+page+' class=list-ul></ul>';
	var reqUrl;
	if(nodeId == 5) {
		reqUrl = "/index.php?m=wap&f=biz_log&v=listl&acquiesce="+sortStatus+"&ends="+nodeId+"&page="+page;
	} else {
		reqUrl = "/index.php?m=wap&f=biz_log&v=listl&acquiesce="+sortStatus+"&nodeid="+nodeId+"&page="+page;
	}
	$('#list-container').append(liveContent);
	$.ajax({
		type: "POST",
		url: reqUrl,
		dataType: "json",
		timeout: 2000,
		success: function(res) {
			console.log(res)
			if(res && res.code == 1) {
				var data = res.data;
				if(data.page_max) {
					totalPage = data.page_max;
				}
				console.log(totalPage)
				if(totalPage <= 1){
					$(".down-page").remove();
				}
				solveTemplate('#page'+page, '#list-data', data);
				$(".lazy-img").dxLazyLoad();
				liveImgLoaded();
				docH = $(document).height();
				isLoading = true;
				page++;


			}
		},
		error: function(XMLHttpRequest, textStatus) {
			console.log(textStatus)
		}
	});
}

function scrollPages() {
	var pageH = $('.down-page').height();
	var sTop = document.body.scrollTop + 200;
	if(pageH + sTop >= docH - winH && isLoading) {
		if(page <= totalPage) {
			getList();
		} else {
			$(".down-page").hide();
		}
	}
}


function liveImgLoaded(){
	$(".lazy-img").bind("imgloaded",function(){
		var that = $(this);
		that.css({
			height: "auto",
			"min-height": "10.1875rem"
		});
		var boxH = $(".building-box").height();
		var boxW = $(".building-box").width();
		var imgW = that.width();
		var imgH = that.height();
		var _imgH=(boxW/imgW)*imgH;

		var height_t = -(_imgH-boxH);
		that.css({
			position: "relative",
			top: height_t/2
		});
		that.next().css("margin-top", height_t);
	});
}