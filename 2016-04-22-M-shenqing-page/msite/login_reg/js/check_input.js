// 2015-12-01
function checkPhone(number) {
	var user_id = number;
	if(user_id == "")
	{
		showTip("请输入用户名");
		return false;
	}
    if(!isNaN(user_id)){
		//Number length is 11 ，First number is 1
        var tel = /^1[3|4|5|7|8|9][0-9]\d{8}$/;
        if(!tel.test(user_id)) {
			showTip("您输入的手机号格式不正确");
            return false;
        }
		else {
			return true;
		}
    }
    else {
		if(escape(user_id).indexOf( "%u" )<0) 
		{
			if(user_id.length>2 && user_id.length<21)
			{
			    return true ;	
			}
			else {
				showTip("您输入的用户名格式不正确");
			}
	    }
	    else
	    {
            showTip("您输入的用户名格式不正确");
            return false ;
	    } 
    }
}
function checkPwd(pwd) {
	var user_pwd = pwd;
	if(user_pwd == "")
	{
		showTip("请输入密码");
		return false;
	}
	if(escape(user_pwd).indexOf( "%u" )<0) 
	{
		if(user_pwd.length>5 && user_pwd.length<21)
		{
			return true ;	
		}
		else {
			showTip("您输入的密码格式不正确");
		}
	}
}
