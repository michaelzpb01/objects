$(function(){
	sidebar();
	goTop();
	getCaseInfo();

	$("#collect").on('click', function(){
		collectCase();
	});

	$("#go-back").on('click', function(){
		goBack();
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
function getCaseInfo(){
	var request = $.ajax({
		type: "POST",
		url: "index.php?m=wap&f=biz_photo&v=particulars_listing&id="+case_id,
		dataType: "json",
		timeout: 2000,
		success: function(res){
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
				showBigPic();
				sendUrl();
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

function showBigPic(){
	$(".link-a").click(function(){
		var caseDom = $("#case-info"),
			docH = $(document).height(),
			winH = $(window).height(),
			winW = $(window).width();
		var index = parseInt($(this).attr("index"));
		$('.pic-info').height(winH);
		$('.pic-local').width(winW);
		caseDom.height(winH);
		caseDom.css("overflow", "hidden");
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
	        }
	    });
	    //初始化
	    $("#current-number").text(index);
	    //total index position
	    $("#total-number").text(swipe.getLength());
		$('.pic-info').each(function(){
            new RTP.PinchZoom($(this), {});
        });

        $(".go-back").click(function(){
        	var headerH = $("header").height(),
        		btmH = $(".bottom-btn").height();
        	$("#big-pic").hide();
        	caseDom.height(docH-headerH-btmH);
        	caseDom.css("overflow", "");
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

function collectShow(str) {
	var tip_box = '<div id="showBox"><span id="showInfo"></span></div>';
	if($('#showBox'))
	{
		$('#showBox').remove();
	}
	$(tip_box).appendTo("body");
	$('#showInfo').html(str);
	setTimeout(function(){
			$('#showBox').show();
	    setTimeout(function(){
		    $('#showBox').hide();
	    }, 2000);
	},1);
}

function sendUrl(){
	var caseName = $(".case-name").html();
	$(".apply-btn").attr("href","mobile-application.html?id=M站-精品案例详情页-"+caseName);
}