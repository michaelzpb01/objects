'use strict'
;(function(){
	function zgetByClass(oParent,sClass){
		if(oParent.getElementsByClassName){
			return oParent.getElementsByClassName(sClass);
		}else{
			var aResult = [];
			var re = new RegExp('\\b'+sClass+'\\b');
			var aEle = oParent.getElementsByTagName('*');
			for(var i=0;i<aEle.length;i++){
				if(aEle[i].className.search(re)!=-1){
					aResult.push(aEle[i]);
				}
			}
			return aResult;
		}
	}
	var dataProject =['徜徉集','方庄世纪星','芳城园','国际友谊花园','和义东里','建邦华庭','建邦华庭','金色漫香郡','朗诗未来街区','盛景园','顺义前进花园','顺义石门苑','天宫院','豪迈时代','新世界小区','阳光星苑','阳光星苑','怡和北路一号院','益丰园','永旭嘉园','真武庙','闵行校区','府上嘉园'];
	var dataName =['于先生','乔先生','朱女士','吴女士','刘女士','郭先生','游先生','徐先生','赵女士','刘先生','赵宇童','吴先生','罗先生','郭先生','赵先生','张先生','刘先生','马女士','向女士','单女士','潘先生','宋女士','康先生','刘先生'];
	var skillList = document.getElementById('skill_list');
	var skillMask = zgetByClass(skillList,'skill_list_mask');
	for(var i = 0;i<skillMask.length;i++){
		skillMask[i].innerHTML = '<p>'+dataProject[i]+'</p><p>'+dataName[i]+'</p>';
	}
	
	
	//专注技艺栏移入遮罩层向下滑出
	$('.skill_list li').on('mouseenter',function(){
		$(this).find('.skill_list_mask').stop().animate({'top':$(this).height()});
	});
	//专注技艺栏移入遮罩层向上滑出
	$('.skill_list li').on('mouseleave',function(){
		$(this).find('.skill_list_mask').stop().animate({'top':0});
	});
	//视频移入显示遮罩层
	$('.listen_vedio_demo li').on('mouseenter',function(){
		$(this).find('.listen_mask').show();
	});
	//视频移入隐藏遮罩层
	$('.listen_vedio_demo li').on('mouseleave',function(){
		$(this).find('.listen_mask').hide();
	});
	
	var vedioUrlResault = ['http://player.youku.com/embed/XMTU1MDQ4MDk2OA==','http://player.youku.com/embed/XMTU1MDQ4MTk1Mg==','http://player.youku.com/embed/XMTU1MDQ4MTY0OA==','http://player.youku.com/embed/XMTU1MDQ4MTM0NA=='];
	//播放视频界面显示
	$('.listen_vedio_demo li').on('click',function(){
		$('.uz_vedio iframe').attr({"src":vedioUrlResault[$(this).index()]});
		$('.uz_vedio').show();
	});
	//播放视频界面隐藏
	$('.uz_vedio .close').on('click',function(){
		$('.uz_vedio').hide();
	});
	
	//去除广告start
	$("#s1").click(function(){ 
		  player = new YKU.Player("youkuplayer",{
		  styleid: '6',
		  client_id: '2d3bc241025319e0',
		  vid: 'XMTU1MDQ4MDk2OA==',
		  autoplay: true,
		  show_related: false
    	});
	});
	$("#s2").click(function(){ 
		  player = new YKU.Player("youkuplayer",{
		  styleid: '6',
		  client_id: '2d3bc241025319e0',
		  vid: 'XMTU1MDQ4MTk1Mg==',
		  autoplay: true,
		  show_related: false
    	});
	});
	//
	$("#s3").click(function(){ 
		  player = new YKU.Player("youkuplayer",{
		  styleid: '6',
		  client_id: '2d3bc241025319e0',
		  vid: 'XMTU1MDQ4MTY0OA==',
		  autoplay: true,
		  show_related: false
    	});
	});
	//
	$("#s4").click(function(){ 
		  player = new YKU.Player("youkuplayer",{
		  styleid: '6',
		  client_id: '2d3bc241025319e0',
		  vid: 'XMTU1MDQ4MTM0NA==',
		  autoplay: true,
		  show_related: false
    	});
	});
	//去除广告end
	
	var stewardUrlReaust = ['http://www.uzhuang.com/zhuangxiuguanjia/#md-1','http://www.uzhuang.com/zhuangxiuguanjia/#md-2','http://www.uzhuang.com/zxgj_catch22/#md-3','http://www.uzhuang.com/jialog/','http://www.uzhuang.com/zhuangxiuguanjia/#md-4','http://www.uzhuang.com/zhuangxiuhuanbao/'];
	var stewardUrlA = document.getElementById('steward_cont_list').getElementsByTagName('a');
	for(var i = 0;i<stewardUrlA.length;i++){
		stewardUrlA[i].href = stewardUrlReaust[i];
	}
})();

//$.loadProvince('select-01','select-02');
//choose city
$('#choose-citys').bind('click',function(){
    $('#choose-citys').addClass("slidedown");
    setTimeout(function(){
        $(document).bind('click',function(){
            $('#choose-citys').removeClass("slidedown");
            setTimeout(function(){
                $(document).unbind('click');
            },1);
        }); 
    },1);
});

/*
$(function(){
	//鼠标跟随
	$(' .brandMod > li,.logoMod li ').each( function() { $(this).hoverdir(); } );
	//banner
	$('#top-banner-ul').bxSlider({
		  mode: 'fade',
		  auto: true,
		});
	//flash
	$('#left-slider01').bxSlider();
	$('#left-slider02').bxSlider();
    $.loadProvince('select-01','select-02');
    //choose city
    $('#choose-citys').bind('click',function(){
        $('#choose-citys').addClass("slidedown");
        setTimeout(function(){
            $(document).bind('click',function(){
                $('#choose-citys').removeClass("slidedown");
                setTimeout(function(){
                    $(document).unbind('click');
                },1);
            }); 
        },1);
    })
    // 首屏隐藏右侧发标
	jQuery(document).ready(function($){
		// browser window scroll (in pixels) after which the "back to top" link is shown
		$(".imgList02 li").hover(function(){
				$(this).find(".txt").stop().slideToggle();
			},function(){
				$(this).find(".txt").stop().slideToggle();
				});
		
		//
	});

    $("body").bind("upRightMenu",function(){
        $(".rightMenu").css("bottom","119px");
    });
    $("body").bind("downRightMenu",function(){
        $(".rightMenu").css("bottom","0");
    });
	
})*/















