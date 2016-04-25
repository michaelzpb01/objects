// login page 2015-12-1 Xushu
$(function(){
    $("#login-btn").bind("click",userLogin);
	var check = setTimeout(checkLogin,1000);
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
					}else{
						goBack();
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
//return cookie value
function getCookie(c_name)
{
  if (document.cookie.length>0)
  {
    c_start=document.cookie.indexOf(c_name + "=")
    if (c_start!=-1)
    { 
    c_start=c_start + c_name.length+1 
    c_end=document.cookie.indexOf(";",c_start)
    if (c_end==-1) c_end=document.cookie.length
    return unescape(document.cookie.substring(c_start,c_end))
    } 
  }
  return ""
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

