//获取公司列表信息
var companys_data;

$(function(){
	var city_id = getCookie("cityid");
	if(city_id){
		city_id = city_id;
	}
	else{
		city_id="";
	}
	getCompany(city_id);
});

function getCompany(city_id){
	var cityid = city_id;
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=biz_company&v=listing&cityid="+cityid,
		dataType: "json",
		timeout: 3000,
		success: function(data){
			console.log(data);
			if(data.code == 1)
			{
				companys_data = data.data;
				solveTemplate("#company-list", "#company-list-template", data.data);
				var footer = $("#footer");
				var footer_h = $(footer).height()+parseInt($(footer).css("padding-top"))+parseInt($(footer).css("padding-bottom"))+1;
				var header_h = $("header").height()+1;
				var content_h = $("#company-list-page").height();
				winH = $(window).height();
				if(header_h+content_h>winH-footer_h){
					$(footer).addClass("setstatic")
				}
				if(content_h < 58){
					var empty_tip = '<p id="empty-tip">数据正在维护中~</p>';
					$(empty_tip).appendTo("body");
				}
			}
		},
		error: function(XMLHttpRequest, textStatus){
			if(textStatus == "timeout"){
				reLoad("body");
			}
		}
	});
}