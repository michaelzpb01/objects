$(function(){
	var designer_id = getUrlParameter("designer_id");
	var company_name = getUrlParameter("company_name");
	var url = encodeURIComponent(window.location.href.split("#")[0]);
	wxShare(url, "sjs", designer_id);
	getDesigner();
	function getDesigner(){
		$.ajax({
			type: "POST",
			url: "/index.php?m=wap&f=biz_design&v=Detail&id="+designer_id,
			dataType: "json",
			timeout: 3000,
			success: function(res){
				console.log(res)
				if(res && res.code == 1){
					var data = res.data;
					if(data.name){
						$("#total-works-num").html(data.name.length);
					}
					solveTemplate("#designer", "#designer-data", data);
					if(data.collectstatus == 1){
						$("#collect i").removeClass("icon-star_one").addClass("icon-star_two");
					}
					else{
						$("#collect i").removeClass("icon-star_two").addClass("icon-star_one");
					}
					general();

					solveTemplate("#content-now", "#works-data", data);
					$(".lazy-img").dxLazyLoad();
					imgLoaded();
					getSource();

					$("#collect").bind("click", collectDesigner);
				}
			},
			error: function(XMLHttpRequest, textStatus){
				if(textStatus == "timeout"){
					$("#works").remove();
					reLoad("body");
				}
			}
		});
	}
	//设计师收藏
	function collectDesigner(){
		$.ajax({
			type: "POST",
			url: "index.php?m=wap&f=member&v=fav_design&type=design&id="+designer_id,
			dataType: "json",
			timeout: 3000,
			success: function(res){
				console.log(res)
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
			error: function(XMLHttpRequest, textStatus){
				console.log(textStatus);
			}
		});
	}
	//按钮居中、全文收起展开
	function general(){
		var marLeftBtn = -($(".designer-btn").width()+parseInt($("#order").css("margin-right")))/2;
		$(".designer-btn").css("margin-left", marLeftBtn+"px");

		var personalInfo = $("#personal-info p");
		var showHide = $("#all-info");
		var text = personalInfo.html(),
			text = htmlDecode(text);
		if(text.length > 76){
			var newText = text.substr(0, 76)+"...";
			personalInfo.html(newText);
			showHide.show().bind("click", function(){
				if($(this).html() == "全文"){
					personalInfo.html(text);
					$(this).html("收起");
				}else{
					personalInfo.html(newText);
					$(this).html("全文");
				}
			});
		}
	}
	function getSource(){
		var designer_name = $(".designer-name").html();
		if(browser.versions.weixin){
			$("#order").attr("href", "mobile-sale_price.html?id=微信-设计师详情页-"+company_name+"-"+designer_name);
		}else if(browser.versions.weibo){
			$("#order").attr("href", "mobile-sale_price.html?id=微博-设计师详情页-"+company_name+"-"+designer_name);
		}else{
			$("#order").attr("href", "mobile-sale_price.html?id=M站-设计师详情页-"+company_name+"-"+designer_name);
		}
	}
});

// &nbsp;替换成空格
function htmlDecode(text){
	return text.replace(/&nbsp;/g, ' ');
}