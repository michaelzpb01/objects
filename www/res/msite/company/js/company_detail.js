// Company Page

var companys_data;
var company_id = getUrlParameter("company_id");
var url = encodeURIComponent(window.location.href.split("#")[0]);
wxShare(url, "kbgs", company_id);
//Init
$(function(){
	$("#company-tab").dxTab();
	getCompanyData();
})

//Get Data
function getCompanyData(){
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=biz_company&v=company_details&companyid="+company_id,
		dataType: "json",
		timeout: 3000,
		success: function(data){
			console.log(data)
			if(data.code == 1){
				companys_data = data.data;
                //Set UnderScore Templates
				solveTemplate("#company-head", "#company-head-template", companys_data);
				solveTemplate("#designer-list", "#company-designer-template", companys_data);
				solveTemplate("#about-box", "#company-about-template", companys_data);

				setScore();
				getSource();

				$("#favor-btn").bind("click",favored);
				if(companys_data.collectstatus == 1){
					$("#favor-star").removeClass("icon-star_one").addClass("icon-star_two");
				}
				else{
					$("#favor-star").removeClass("icon-star_two").addClass("icon-star_one");
				}

				solveTemplate("#content-now", "#works-data", companys_data);
				$("#my-like").bind("click", function(){
					$(".lazy-img").dxLazyLoad();
					imgLoaded();
				});
			}
		},
		error: function(XMLHttpRequest, textStatus){
			if(textStatus == "timeout"){
				$("#company-detail").remove();
				reLoad("body");
			}
		}
	});
}

//Set Company Score
function setScore(){
	
	var company = companys_data.company;
	
	$("#total-score").html(company.avg_total);
	$("#design-score").html(company.avg_design);
	$("#service-score").html(company.avg_service);
	$("#quality-score").html(company.avg_quality);
	
	setStar("design-star",company.avg_design);
	setStar("service-star",company.avg_service);
	setStar("quality-star",company.avg_quality);
}

//Calculate Stars Style
function setStar(star_id,score){
	
	var star_number = Math.floor(score);
	var star_box = $("#"+star_id).find("li");
	for(var i=0; i<star_box.length; i++){
		if(i>star_number-1) {
			var yellowstar = $(star_box).eq(i).find(".yellow");
			if(i-star_number>0){
				$(yellowstar).width("0");
			}
			else {
				var graywidth = $(yellowstar).width();
			    var yu = score*10%10*0.1*graywidth;
			    $(yellowstar).width(yu+"px");
			}
		}
	}
}

//Click Favorite Button
function favored() {
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=member&v=fav_company&type=company&id="+company_id,
		dataType: "json",
		timeout: 3000,
		success: function(data){
			if(data.code == 0){
				alertConfirm("登录后才能收藏哦，去登录~");
				$("#confirm-layer").on('touchmove', function(event){
					event.preventDefault();
				})
			}else {
				if(data.data.collectstatus == 1)
				{
					$("#favor-star").removeClass("icon-star_one").addClass("icon-star_two");
				}
				else {
					$("#favor-star").removeClass("icon-star_two").addClass("icon-star_one");
				}
				collectShow(data.message);
			}
		},
		error: function(XMLHttpRequest, textStatus){
			if(textStatus == "timeout"){
				console.log(textStatus)
			}
		}
	});
}

function getSource(){
	var company_name = $(".company-name").html();
	if(browser.versions.weixin){
		$("#apply-btn").attr("href", "mobile-sale_price.html?id=微信-口碑公司详情页-"+company_name);
	}else if(browser.versions.weibo){
		$("#apply-btn").attr("href", "mobile-sale_price.html?id=微博-口碑公司详情页-"+company_name);
	}else{
		$("#apply-btn").attr("href", "mobile-sale_price.html?id=M站-口碑公司详情页-"+company_name);
	}
}