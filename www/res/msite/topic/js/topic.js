$(function(){
	getTopic();
});

var topic_id = getUrlParameter("topic_id");
var url = encodeURIComponent(window.location.href.split("#")[0]);
wxShare(url, "alzt", topic_id);
function getTopic(){
	$.ajax({
		type: "POST",
		url: "/index.php?m=wap&f=biz_photo&v=special&id="+topic_id,
		dataType: "json",
		timeout: 3000,
		success: function(res){
			console.log(res)
			if(res && res.code == 1){
				var data = res.data;
				solveTemplate("#topic-info", "#topic-data", data);
				$(".lazy-img").dxLazyLoad();
				imgLoaded();
			}
		}
	});
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