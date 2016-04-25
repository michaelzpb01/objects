// login page 2015-12-1 Xushu
$(function(){
    $("#login-btn").bind("click",userLogin);
	var check = setTimeout(checkLogin,1000);
	getWeiboLink();
	lgoinByQQ();

	// Login in by weixin
	$("#wx-passport").bind('click', function() {
		$(this).attr("href", "/index.php?m=third_login&f=weixin&v=weixin_login_url");
	});
})
var user_home = getUrlParameter("user_home");
function userLogin() {
    var user_id = $("#user-name").val();
	var user_pwd = $("#pwd").val();
    var ck_ph = checkPhone(user_id);
	var ck_pwd = checkPwd(user_pwd);
    if(ck_ph == true && ck_pwd == true)
    {
		var save = '';
		if(free_login.checked  == true)
		{
			var save = "&savecookie=savecookie";
		}
		$.ajax({
			type: "POST",
			url:"index.php?m=wap&f=password&v=login",
			data:'username='+user_id+'&password='+user_pwd+save,
			dataType: "json",
			success: function(data){
				if(data.code == 1)
				{
					//login Success
					if(user_home && user_home == 1)
					{
						window.location.href="mobile-user_home.html";
					}
					else{
						var favored = getUrlParameter("favored");
						if(favored == 1){
							window.location.href = document.referrer;
						}
						else{
							goBack();
						}
					}
				}
				else {
					alertOpen(data.message);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			    console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
			}
	   })
	}
}
//show Tips
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
//Check login
function checkLogin() {
	var username=getCookie('NDb__uid');
	if (username!=null && username!=""){
	  collectShow("您已登录");
	  setTimeout(function(){
		goBack();
	  },1000);
	}
}
//Get login address by weibo
function getWeiboLink(){
	$.ajax({
		type: "POST",
		url: "index.php?m=third_login&f=sina&v=sina_login",
		dataType: "json",
		timeout: 3000,
		success: function(data){
				if(data.code == 1){
					var weibolink = data.data;
					$("#weibo-passport").attr("href",weibolink);
				}
				else{
					setTimeout(getWeiboLink,1000);
				}
		},
		error: function(XMLHttpRequest, textStatus){
					setTimeout(getWeiboLink,1000);
		}
	});
}

// Login in by QQ
function lgoinByQQ() {
	$.ajax({
		type: "POST",
		url: "/index.php?m=third_login&f=QQ&v=qq_login_url",
		dataType: "json",
		timeout: 3000,
		success: function(res) {
			console.log(res)
			if(res.code == 1) {
				var QQLink = res.data;
				$("#qq-passport").attr("href", QQLink);
			} else {
				setTimeout(lgoinByQQ, 1000);
			}
		},
		error: function(XMLHttpRequest, textStatus) {
			setTimeout(lgoinByQQ, 1000);
		}
	});
}

// Login in by weixin
// function lgoinByWx() {
// 	$.ajax({
// 		type: "POST",
// 		url: "/index.php?m=third_login&f=weixin&v=callback",
// 		dataType: "json",
// 		timeout: 3000,
// 		success: function(res) {
// 			console.log(res)
// 			if(res.code == 1) {
// 				var wxLink = res.data;
// 				$("#wx-passport").attr("href", wxLink);
// 			} else {
// 				setTimeout(lgoinByWx, 1000);
// 			}
// 		},
// 		error: function(XMLHttpRequest, textStatus) {
// 			setTimeout(lgoinByWx, 1000);
// 		}
// 	});
// }