// Index data
var index_data;
var banners_data;
var located;
var current_cityid = 3360;  //Default City id

$(function(){
	if(winW == 940){        //If Weibo PC
		weiboImage();
	}
	$("#user-name").unbind("focus");

	//Post application
	getIndex();

	//Bind button Events
	$("#local").bind("click",setLocation);
	$("#close-citys").bind("click",function(){
		$("#location").removeClass("slideIn");
	});

	//Go to user home
	$('#my-home').bind('click', function() {
		window.location.href = 'mobile-user_home.html';
	});

	getIndexSource();

	getSource("#specail-service");
});

//Show a Picture in Weibo Page
function weiboImage(){
	var weibo_img = $("#weibo-pc");
	var child = $("body").children();
	$(child).hide();
	$(weibo_img).show();
}

//Get Activity Data
function getIndex(){
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=index&v=index",
		dataType: "json",
		timeout: 3000,
		success: function(data){
			if(data.code == 1)
			{
				index_data = data.data;
				located = data.data.init.ktcity;
				
				setCurrentCity();
				
				solveTemplate("#active-list", "#index-active-template", index_data);
				solveTemplate("#choose-city", "#index-located-template", located);
				
				cityBindEvent();

			}
		},
		error: function(XMLHttpRequest, textStatus){
			if(textStatus == "timeout"){
				reLoad("body");
			}
		}
	});
}

//Set city_id
function setCurrentCity(){
	var city_id = getCookie("cityid");
	if(city_id){
		city_id = city_id;
	}
	else{
		city_id = 3360;
	}
    getBanners(city_id);
    var cityname = getCityName(city_id);
    $("#current-city").html(cityname);
	
	selectCurrentCity(cityname);
}

//Get Banners Data
function getBanners(city_id) {
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=index&v=shuffl_photo&shuffl="+city_id,
		dataType: "json",
		timeout: 3000,
		success: function(data){
				banners_data = data.data;
				solveTemplate("#banner-box", "#index-banner-template", banners_data);
				//initialize swiper when document ready  
				var swiper = new Swiper('#index-banner', {
					pagination: '.swiper-pagination',
					loop : true,
					autoplay: 3000,
					paginationClickable: false,
					autoplayDisableOnInteraction : false
				});
		},
		error: function(XMLHttpRequest, textStatus){
			if(textStatus == "timeout"){
				reLoad("body");
			}
		}
	});
}

//Set located by GPS
function setLocation(){
	
	$("#location").addClass("slideIn");
	
	var city_id = getCookie("cityid");
	if(city_id){
		var cityname = getCityName(city_id);
		//$("#local-city").html(cityname);
		selectCurrentCity(cityname);	
	}
		
	$("#local-state").html("定位中...");
 
	var options={
	   enableHighAccuracy:true, 
	   maximumAge:1000
	}
	if(navigator.geolocation){
	   //Support geolocation
	   //alert("打开GPS");
	   navigator.geolocation.getCurrentPosition(locatedSuccess,locatedError);
	   
	}else{
	   //Not Support geolocationn
	   $("#local-state").html("定位失败，请手动选择城市");
	}
	

}

//Get Location Success
function locatedSuccess(position){
	//alert("位置获取成功");
	var longitude =position.coords.longitude;
	var latitude = position.coords.latitude;
	//alert("x:"+longitude+" y:"+latitude);
   
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=index&v=locationByGPS&lng="+longitude+"&lat="+latitude,
		dataType: "json",
		timeout: 3000,
		success: function(gps){
			if(gps.data.cityid){
				$("#local-city").html(gps.data.city);
				$("#local-state").html("");
				selectCurrentCity(gps.data.city);
				postCity(gps.data.cityid,gps.data.city);	
			}
			else{
				$("#local-city").html(gps.data.city);
				$("#local-state").html("暂未开通服务");
			}
		},
		error: function(XMLHttpRequest, textStatus){
			$("#local-state").html("定位失败，请手动选择城市");
		}
	});
}


//Get Location Error
function locatedError(error){
    $("#local-state").html("定位失败，请手动选择城市");
//   switch(error.code){
//	   case 1:
//	   alert("位置服务被拒绝");
//	   setDefultCity();
//	   break;
//
//	   case 2:
//	   alert("暂时获取不到位置信息");
//	   setDefultCity();
//	   break;
//
//	   case 3:
//	   alert("获取信息超时");
//	   setDefultCity();
//	   break;
//
//	   case 4:
//	   alert("未知错误");
//	   setDefultCity();
//	   break;
//   }

}

//Selected Current City
function selectCurrentCity(cityname){
	var cityli = $("#choose-city li");
	$(cityli).removeClass("current");
	for(var i=0; i<cityli.length; i++){
		var cityitem = $(cityli).eq(i);
		if($(cityitem).html() == cityname){
			$(cityitem).addClass("current");
			return;
		}
	}
	$(cityli).eq(0).addClass("current");
}

//SetDefultCity
function setDefultCity(){
	current_cityid = 3360;
	getBanners(3360);
	$("#current-city").html("北京");
}

//Citys buttons Bind Click
function cityBindEvent(){
	$("#choose-city li").bind("click",function(){
		var choosed = $(this).attr("city-id");
		var choosed_name = $(this).html();
		postCity(choosed,choosed_name,true);	
	})
}

//Save Located to Cookie
function postCity(cityid,cityname,reloadpage){
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=index&v=qhcspi&cityid="+cityid+"&cityname="+cityname,
				dataType: "json",
		timeout: 3000,
		success: function(data){
			console.log("success");
			if(reloadpage){
				window.location.reload();
			}
		},
		error: function(XMLHttpRequest, textStatus){
			console.log("false");
		}
	});
}

//Id Transform to CityName
function getCityName(cityid){
	var citys = index_data.init.ktcity;
	for(var key in citys){
		var city = {
		  name: citys[key].city,
		    id: citys[key].cityid
		};
		if(city.id == cityid){
			return city.name;
		}
	}
	return "北京";
}

// get source
function getSource(el){
	var source = $(el).prev().html();
	var href = $(el).attr("href");
	if(browser.versions.weixin){
		$(el).attr("href", href + "&id=M微信-首页" + source);
	}else if(browser.versions.weibo){
		$(el).attr("href", href + "&id=M微博-首页" + source);
	}else{
		$(el).attr("href", href + "&id=M-首页" + source);
	}
}

function getIndexSource(){
	if(browser.versions.weixin){
		$("#source").val("微信-首页-报名");
	}else if(browser.versions.weibo){
		$("#source").val("微博-首页-报名");
	}else{
		$("#source").val("M站-首页-报名");
	}
}