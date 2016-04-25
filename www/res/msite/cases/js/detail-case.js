$(function(){
	sidebar();
	goTop();
	getCaseInfo();

	$("#collect").on('click', function(){
		collectCase();
	});
    
});
// function imgSize(){
// 	var imgSize = $('.pic-info img'),
// 		winH = $(window).height();
// 		// winW = $(window).width();


// 	imgSize.each(function(){
// 		var imgH = $(this).height();
// 			// imgW = $(this).width();

// 		$(this).css({
// 			position: "absolute",
// 			top: (winH-imgH)/2,
// 		});
// 	});
// }

var case_id = getUrlParameter("id");
var url = encodeURIComponent(window.location.href.split("#")[0]);
wxShare(url, "jpal", case_id);
function getCaseInfo(){
	var request = $.ajax({
		type: "POST",
		url: "index.php?m=wap&f=biz_photo&v=particulars_listing&id="+case_id,
		dataType: "json",
		timeout: 2000,
		success: function(res){
			console.log(res)
			if(res && res.code == 1){
				var data = res.data;
				if(data.collectstatus == 1){
					$("#collect i").removeClass("icon-star_one").addClass("icon-star_two");
				}
				else{
					$("#collect i").removeClass("icon-star_two").addClass("icon-star_one");
				}
				solveTemplate("#case-info", "#detail-data", data);
				solveTemplate("#big-pic-content", "#big-pic-data", data);
				$(".lazy-img").dxLazyLoad();
				imgLoaded();
				showBigPic();
				sendUrl();

				preLoadImg(1);
			}
		},
		error: function(XMLHttpRequest, textStatus){
			dataStatus = dataStatus+1;
			if(textStatus == "timeout"){
				getCaseInfo();
			}
		}
	});
	if(request && dataStatus == 2){
		request.abort();
		$(".bottom-choose").remove();
		reLoad("#case-info");
	}
}

//preload slider image
function preLoadImg(index){
	var imgs = $(".lazy-bimg");
	var now_img = $(imgs).eq(index-1);
	var pre_img = $(imgs).eq(index-2);
	var next_img = $(imgs).eq(index);
	setSrc(pre_img);
	setSrc(now_img);
	setSrc(next_img);
	for(var i=0; i< imgs.length; i++){
		imgs[i].onload = function(){
			$(this).addClass("opacity1");
			$(this).siblings(".loader").hide();
		};
	}
}

function setSrc(img){
	var img_src = $(img).attr("data-original");
	$(img).attr("src",img_src);
}

function showBigPic(){
	$(".link-a").click(function(){
		//$(".lazy-bimg").dxLazyLoad();
		var caseDom = $("#case-info"),
			docH = $(document).height(),
			winH = $(window).height(),
			winW = $(window).width();
		var index = parseInt($(this).attr("index"));
		$('.pic-info').height(winH);
		$('.pic-local').width(winW);
		// caseDom.height(winH);
		caseDom.css({
			height: winH,
			overflow: "hidden"
		});
		$("#big-pic").show().css({
			width: winW,
			height: winH,
			overflow: "auto"
		});
		var swipe = new Swipe(document.getElementById('slider'), {
	        speed: 400,
	        startSlide: index-1,
	        callback: function() {
	        	//current index position
	        	var index=this.getPos()+1;
	        	$("#current-number").text(index);
				preLoadImg(index);
	        }
	    });
	    //初始化
	    $("#current-number").text(swipe.getPos()+1);
	    //total index position
	    $("#total-number").text(swipe.getLength());
		$('.pic-info').each(function(){
            new RTP.PinchZoom($(this), {});
        });

        $(".go-back").click(function(){
        	var headerH = $("header").height(),
        		btmH = $(".bottom-btn").height();
        	$("#big-pic").hide();
        	// caseDom.height(docH-headerH-btmH);
        	caseDom.css({
				height: "auto",
				overflow: ""
			});
        });
	});
}

function collectCase(){
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=member&v=fav&id="+case_id+"&type=picture",
		dataType: "json",
		success: function(res){
			if(res){
				if(res.code == 0){
					alertConfirm("登录后才能收藏哦，去登录~");
					$("#confirm-layer").on('touchmove', function(event){
						event.preventDefault();
					})
				}else {
					var data = res.data;
					if(data.collectstatus == 1){
						$("#collect i").removeClass("icon-star_one").addClass("icon-star_two");
						collectShow(res.message);
					}
					else{
						$("#collect i").removeClass("icon-star_two").addClass("icon-star_one");
						collectShow(res.message);
					}
				}
			}
		},
		error: function(){
			alert("找不到数据辣，请稍后再试~");
		}
	});
}

function sendUrl(){
	var caseName = $(".case-name").html();
	if(browser.versions.weixin){
			$(".apply-btn").attr("href","mobile-sale_price.html?id=微信-精品案例详情页-"+caseName);
		}else if(browser.versions.weibo){
			$(".apply-btn").attr("href","mobile-sale_price.html?id=微博-精品案例详情页-"+caseName);
		}else{
			$(".apply-btn").attr("href","mobile-sale_price.html?id=M站-精品案例详情页-"+caseName);
		}
}

function imgLoaded(){
	var cover_img = document.getElementById("cover-img");
	cover_img.onload = function(){
		var that = $(this);
		var divH = $(".case-div").height();
		var divW = $(".case-div").width();
		var imgW = that.width();
		var imgH = that.height();
		if(imgW/imgH < 4/3){
			var _imgH=(divW/imgW)*imgH;
			var height_t = -(_imgH-divH)/2;
			that.css({
				position: "relative",
				top: height_t,
			});
		}
	};
}