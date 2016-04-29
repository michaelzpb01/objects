;(function(){
	//解析url后面数据
	function url2json(url) {
		var json = {};
		var arr = url.split('&');
		for (var i = 0; i < arr.length; i++) {
			var arr2 = arr[i].split('=');
			json[arr2[0]] = arr2[1];
		}
		return json;
	}
	function getMessage(URL,fn,TYPE){
		TYPE = TYPE||'json';
		$.ajax({
			type: "post",
			url: URL,
			dataType: TYPE,
			success: function(number){
				if(number){
					fn&&fn(number);
				}
			},
			error: function(){
	        	alert('您当前网络异常');
	        }
		});
	}
	/*getMessage();*/

	//获取url
	var windowHref = window.location.href;
	//获取url后面数据
	var oUrl2string = windowHref.substring(windowHref.indexOf('#')+1,windowHref.length);
	//解析url#后数据
	var oUrl2resault = url2json(oUrl2string);
	
	//设置内容
	function setmassage(data){
		$('#userTotal').html(data.fbcount);
		$('header .header-title').html(data.titie);
		$('#applyBtn').val('预约');
	}
	//boutique精品案例
	//works预约公司
	//steward预约管家
	//designer预约设计师
	switch(oUrl2resault.type){
		case 'boutique':
			getMessage("http://mdev.uz.com/api/subscribe.php?action=subscribe&temp=302&type=Boutique",function(number){
				var data = number.data;
				setmassage(data);
				$('#applyBtn').val(data.titie);
				$('#apply-img').attr('src',data.cover);
				$('.think-banner').attr({'id':'think-banner'});
			});
		break;
		case 'works':
			getMessage("http://mdev.uz.com/api/subscribe.php?action=subscribe&temp=94&type=works",function(number){
				var data = number.data;
				setmassage(data);
				$('.target_username').html(data.name);
				$('#apply-img').attr('src','applied/images/firm_banner_bg.jpg');
				$('.target_logo img').attr('src',data.thumb);
				$('.think-banner').attr({'id':'firm-banner'});
			});
		break;
		case 'steward':
			getMessage("http://mdev.uz.com/api/subscribe.php?action=subscribe&temp=2213&type=steward",function(number){
				var data = number.data;
				setmassage(data);
				$('.target_username').html(data.name);
				$('.steward_level').html(data.level);
				$('#apply-img').attr('src','applied/images/butler_banner_bg.jpg');
				$('.target_logo img').attr('src',data.personalphoto);
				$('.think-banner').attr({'id':'butler-banner'});
				$('.steward_city').html(data[2]['name']);
			});
		break;
		case 'designer':
			getMessage("http://mdev.uz.com/api/subscribe.php?action=subscribe&temp=3&type=designer",function(number){
				var data = number.data;
				setmassage(data);
				$('.target_username').html(data.name);
				$('.target_message_design_lv').html(data.ranks);
				$('#apply-img').attr('src','applied/images/design_banner_bg.jpg');
				$('.think-banner').attr({'id':'designer-banner'});
				$('.target_logo img').attr('src',data.thumb);
			});
		break;
		
	}
	
})();