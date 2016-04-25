// FindPwd 2015-12-03 Xushu
$(function(){
	gotoStep1();
})
//Go to Step 1 
function gotoStep1() {
	$("#findpwd-1").show();
	$("#findpwd-2").hide();
	$("#findpwd-3").hide();
	changePic();
	$("#change-code").add("#code-img").bind("click",changePic);
	$("#step1_btn").bind("click",checkImgcode);
	$("#go-back").bind("click",goBack);
}
//Go to Step2
function gotoStep2() {
	$("#findpwd-2").show();
	$("#findpwd-1").hide();
	$("#findpwd-3").hide();
	var user_phone = $("#user-phone").val();
	var new_phone = user_phone.substr(0,3)+"****"+user_phone.substr(7,11);
	$("#user-telnumber").html(new_phone);
	$("#sent-message").bind("click",sendCode);
	$("#step2_btn").bind("click",checkMsgcode);
	$("#go-back").unbind("click");
	$("#go-back").bind("click",gotoStep1);
	sendCode();
}
//Go to Step3 
function gotoStep3() {
	$("#findpwd-3").show();
	$("#findpwd-1").hide()
	$("#findpwd-2").hide();
	$("#ok-btn").bind("click",resetPwd);
	$("#go-back").unbind("click");
	$("#go-back").bind("click",gotoStep2);
}
//Change code picture
function changePic() {
	var imgsrc = "index.php?m=core&f=identifying_code&w=112&h=32&rd"+Math.random();
	$("#code-img").attr("src",imgsrc);
}
//Send code message to phone
function sendCode() {
	var user_phone = $("#user-phone").val();
	var check_code = $("#write-code").val();
	if(check_code == "" && user_phone == "")
	{
		return;
	}
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
//Check image code
function checkImgcode() {
	var user_phone = $("#user-phone").val();
	var check_code = $("#write-code").val();
	if(user_phone == "")
	{
		showTip("请输入注册时的手机号");
		return;
	}
	if(check_code == "")
	{
		showTip("请输入验证码");
		return;
	}
	if(checkPhone(user_phone) == true) {
		$.ajax({
			type: "post",
			url:"index.php?m=wap&f=password&v=edit_mobile",
			data:'mobile='+user_phone+"&checkcode="+check_code,
			dataType: "json",
			success: function(data){
				console.log("data:"+data);
				if(data.code == '1') {
					gotoStep2();
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
//Check message code
function checkMsgcode() {
	var phone_number = $("#user-phone").val();
	var msg_code = $("#phone-code").val();
	if(msg_code == "")
	{
		showTip("请输入手机验证码");
		return;
	}
	$.ajax({
		type: "POST",
		url:"index.php?m=wap&f=password&v=edit_code",
		data:'smscode='+msg_code+'&mobile='+phone_number,
		dataType: "json",
		success: function(data){
			console.log("data.code:"+data.code+"     data.message:"+data.message+"     data.process_time:"+data.process_time);
			if(data.code == "1")
			{
				gotoStep3();
			}
			else {
				var send_btn = $("#sent-message");
				send_btn.removeClass("waiting");
				send_btn.val("发送短信验证码");
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
//reset password
function resetPwd() {
	var newpwd = $("#new-pwd").val();
	var confirmpwd = $("#confirm-pwd").val();
	var user_phone = $("#user-phone").val();
	if(newpwd == "")
	{
		showTip("请输入新密码");
		return;
	}
	if(confirmpwd == "")
	{
		showTip("请确认新密码");
		return;
	}
	if(newpwd != confirmpwd)
	{
		showTip("两次密码输入不一致！");
		return;
	}
	$.ajax({
		type: "post",
		url:"index.php?m=wap&f=password&v=edit_password",
		data:'mobile='+user_phone+"&password="+newpwd+"&password2="+confirmpwd,
		dataType: "json",
		success: function(data){
			console.log("data:"+data);
			if(data.code == '1') {
				alertOpen("恭喜您，密码重置成功！");
				setTimeout(function(){
					window.location.href = 'mobile-login.html';
				},2000);
			}
			else {
				alertOpen(data.message);
			}
		}
	})
}
//Send message button change to unable
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
		setTimeout(changeTime,1000);
	};
	changeTime();
}
