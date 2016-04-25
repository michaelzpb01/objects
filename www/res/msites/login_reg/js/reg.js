// Reg 2015-12-1 Xushu
var btnTimer; //Send message button timer
//Init
$(function(){
	changePic();
	$("#reg-btn").bind("click",userReg);
	$("#change-code").bind("click",changePic);
	$("#sent-message").bind("click",sendCode);
	$("#eyes").bind("click",showPwd);
	$("#reg input").bind("blur",checkInputs);
})
//Check inputs to change button state
function checkInputs() {
	if($("#phone-number").val() == "")
	{
		return;
	}
	if($("#phone-code").val() == "")
	{
		return;
	}
	if($("#set-pwd").val() == "")
	{
		return;
	}
	$("#reg-btn").removeClass("disabled");
}
//Click submit button
function userReg() {
	var user_phone = $("#phone-number").val();
	var restet_pwd = $("#restet-pwd").val();
	var ck_pwd = checkPwd(restet_pwd);
	var ck_phone = checkPhone(user_phone);
	var phone_code = $("#phone-code").val();
	if($("#phone-number").val() == "")
	{
		showTip("请输入手机号");
		return;
	}
	if($("#phone-code").val() == "")
	{
		showTip("请输入手机验证码");
		return;
	}
	if($("#set-pwd").val() == "")
	{
		showTip("请输入密码");
		return;
	}
	console.log("user_phone:"+ck_phone+"    restet_pwd:"+ck_pwd);
	if(ck_pwd==true && ck_phone==true) {
		$.ajax({
			type: "POST",
			url:"index.php?m=wap&f=password&v=register",
			data:'mobile='+user_phone+'&password='+restet_pwd+'&smscode='+phone_code,
			dataType: "json",
			success: function(data){
				console.log(data);
				console.log("data.code:"+data.code+"     data.message:"+data.message+"     data.process_time:"+data.process_time);
				alertOpen(data.message);
				if(data.code == 0)
				{
                    stopBtntimer();
					return false;
				}
				setTimeout(function(){
					window.location.href = "mobile-cases.html";	
				},2000);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			    console.log(XMLHttpRequest.status);
                console.log(XMLHttpRequest.readyState);
                console.log(textStatus);
			}
	   })
	}
}
//Change code picture
function changePic() {
	var imgsrc = "http://www.uzhuang.com/index.php?m=core&f=identifying_code&w=112&h=32&rd"+Math.random();
	$("#code-img").attr("src",imgsrc);
}
//Send code message to phone
function sendCode() {
	var user_phone = $("#phone-number").val();
	if(user_phone == "")
	{
		showTip("请输入手机号");
		return false;
	}
	var ck_phone = checkPhone(user_phone);
	if(ck_phone == false)
	{
		return false;
	}
	
	$.ajax({
		type: "POST",
		url:"index.php?m=wap&f=password&v=mb",
		data:'mobile='+user_phone,
		dataType: "json",
		success: function(data){
			console.log(data);
			console.log("data.code:"+data.code+"     data.message:"+data.message+"     data.process_time:"+data.process_time);
			//alertOpen(data.message);
			if(data.code == 1)
			{
				postMessage();
			}
			else {
				alertOpen('用户名已存在');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.status);
			console.log(XMLHttpRequest.readyState);
			console.log(textStatus);
		}
   })
	
}
function postMessage() {
	var user_phone = $("#phone-number").val();
	$.ajax({
		type: "get",
		url:"index.php?m=sms&f=sms&v=sendsms",
		data:'mobile='+user_phone,
		dataType: "json",
		success: function(data){
			console.log("data:"+data);
			if(data == '0') {
				setTimer();
			} else if(data=='202') {
				alertOpen('图片验证码错误！');
				$("#checkcode").focus();
			} else if(data=='203') {
				alertOpen('发送失败！今天发送数量太多！');
			} else if(data=='204') {
				alertOpen('发送过快，请1分钟后重试！');
			} else if(data=='205') {
				alertOpen('发送失败！今天发送数量太多！');
			} else {
				alertOpen('接口异常，短信发送失败！');
			}
		}
	})
}
//Button timer
function setTimer() {
	var send_btn = $("#sent-message");
	send_btn.addClass("waiting");
	var t = 60;
	var changeTime = function() {
		send_btn.val(t+"s秒后重新获取");
		t--;
		if(t<0)
		{
			send_btn.removeClass("waiting");
			send_btn.val("发送短信验证码");
			return;
		}
		btnTimer = setTimeout(changeTime,1000);
	};
	changeTime();
}
//Stop button timer 
function stopBtntimer() {
	clearTimeout(btnTimer);
	var send_btn = $("#sent-message");
	send_btn.removeClass("waiting");
	send_btn.val("发送短信验证码");
}
//show password
function showPwd() {
	if($("#restet-pwd").attr("type") == "password") 
	{
		$("#eyes").addClass("open");
		$("#restet-pwd").attr("type","text");
	}
	else {
		$("#eyes").removeClass("open");
		$("#restet-pwd").attr("type","password");
	}
}
