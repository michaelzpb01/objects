// login page 2015-12-1 Xushu
function userLogin() {
    var user_id = $("#user-name").val();
	var user_pwd = $("#pwd").val();
    var ck_ph = checkPhone(user_id);
	var ck_pwd = checkPwd(user_pwd);
    if(ck_ph == true && ck_pwd == true)
    {
		$.ajax({
			type: "POST",
			url:"http://m.uzhuang.com/index.php?m=member&f=index&v=login",
			data:'username='+user_id+'&password='+user_pwd,
			dataType: "json",
			success: function(data){
				console.log("data.message:"+data.message+"     process_time:"+data.process_time+"     data.username:"+data.username);
			}
	   })
	}
}
$(function(){
    $("#login-btn").bind("click",userLogin);
})