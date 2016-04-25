// myorders
$(function(){
	var orderid = getUrlParameter("bianhao");
	getOrder(orderid);
	$("#steps").bind("click",function(e){
		if($(e.target).hasClass("node-detail"))
		{
			var imgsrc = $(e.target).attr("imgsrc");
			var node_name = $(e.target).attr("node_name");
			showPic(node_name,imgsrc);
		}
		if($(e.target).hasClass("step-name"))
		{
			var li = $(e.target).parents("li");
			if(li.hasClass("completed"))
			{
				li.toggleClass("slideDown");
			}
			else {
				//Alert node image
				var img_link = li.find(".node-detail");
				var node_name = img_link.attr("node_name");
				if(node_name)
				{
					var imgsrc = img_link.attr("imgsrc");
					showPic(node_name,imgsrc);		
				}
			}
		}
	});
})
//Send application
function getOrder(number) {
	$.ajax({
		type: "POST",
		url:"index.php?m=wap&f=member&v=listing_details&order_no="+number,
		dataType: "json",
		success: function(data){
			console.log(data);
			var order_data = data.data.dingdanxinxi;
			var order_nodes =  data.data.dingdanjiedian;
			var now_step = data.data.dangqianjindu;
			if(order_data){
				setData(order_data);
			}
			setNodes(order_nodes);
			var box = $("#wanjie");
			if(now_step.orderstatus == "完结")
			{
				box.addClass("completed");	
			}
			box.appendTo("#steps");
			setTimeout(setLasted,30);
			//setLasted();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log(XMLHttpRequest.status);
			console.log(XMLHttpRequest.readyState);
			console.log(textStatus);
		}
	})
}
//Set last node color
function setLasted() {
	var completed = $(".completed");
	var nodes_length = completed.length;
	completed.eq(nodes_length-1).addClass("lastChild").addClass("slideDown");
	nodeTop(completed);
}
//Set scroll top
function nodeTop(el) {
	var el = el;
	var header_h = $("header").height();
	var node_top = (el.length-1)*$(".completed").height()+$("#order-steps").find("h2").height()+$("#order-head").height()+header_h;
	docH = $(document).height();
	var bottom_h = docH - winH + header_h;
	if(bottom_h<0) {
		node_top = 0;
	}
	else if(node_top > bottom_h) {
		node_top = bottom_h-header_h;
	}
	else {
		node_top = node_top-header_h;
	}
	document.body.scrollTop = node_top;
	console.log("node_top:"+node_top);	
}
//Set order datas
function setData(data) {
	var order = data;
	if(order.address)
	{
		$("#order-head h2").html(order.address);
	}
	if(order.way)
	{
		$('<li>'+order.way+'</li>').appendTo("#tags");
	}
	if(order.homestyle)
	{
		$('<li>'+order.homestyle+'</li>').appendTo("#tags");	
	}
	if(order.housetype)
	{
		$('<li>'+order.housetype+'</li>').appendTo("#tags");	
	}
	$('<li>'+order.area+'㎡</li>').appendTo("#tags");
	for(var i=0; i<order.style.length; i++)
	{
		$('<li>'+order.style[i]+'</li>').appendTo("#tags");
	}
}
//Set node datas
function setNodes(order_nodes) {
	var nodes = order_nodes;
	for(var i=0; i<nodes.length; i++) {
		var node = nodes[i];
		switch(node.nodename) {
			case "复测1":
			  fuCe_1(node);
			  break;
			case "装修订单审核": 
			  dingDan(node);
			  break;
			case "为您精选3家装修公司": 
			  jingXuan(node);
			  break;
			case "为您指定管家":
			  guanJia(node);
			  break;
			case "上门量房":
			  liangFang(node);
			  break;
			case "确定装修公司":
			  gongSi(node);
			  break;
			case "意向定金":
			  dingJin(node);
			  break;
			case "签订设计协议":
			  qianDing(node);
			  break;
			case "签订设计协议/意向定金":
			  DingjinNull(node);
			  break;
			case "方案确定预交底":
			  jiaoDi(node);
			  break;
			case "签施工协议": 
			  xieYi(node);
			  break;
			case "工程开工":
			  kaiGong(node);
			  break;
			case "拆改":
			  chaiGai(node);
			  break;
			case "水电材料验收":
			  shuiDian_1(node);
			  break;
			case "泥木材料验收":
			  niMu_1(node);
			  break;
			case "油漆材料验收":
			  youQi_1(node);
			  break;
			case "水电验收":
			  shuiDian_2(node);
			  break;
		    case "泥木验收":
			  niMu_2(node);
			  break;
			case "油漆验收":
			  youQi_2(node);
			  break;
			case "竣工验收":
			  junGong(node);
			  break;
			case "竣工污染检测":
			  wuRan(node);
			  break;
			case "污染治理":
			  zhiLi(node);
			  break;
			case "复测2":
			  fuCe_2(node);
			  break;
			case "尾款质保期":
			  weiKuan(node);
			  break;
			case "入住空气检测":
			  ruZhu(node);
			  break;
			case "空气治理": 
			  kongQi(node);
			  break;  
		} 
	}
    var orderid = getUrlParameter("bianhao");
    
    $(".live-photo").attr("href","mobile-live_detail.html?myself=1&live_id="+orderid.substr(orderid.length-4));
}
//订单审核
function dingDan(node) {
	var box = $("#shenhe");
	if(node.nodestatus == 1)
	{
	  box.find(".detail").find("p").html(node.message);
	}
	appendTo_list(box,node);
}
//精选家装公司
function jingXuan(node) {
	var box = $("#jingxuan");
	if(node.nodestatus == 1)
	{
		for(var key in node.threecompany)
		{
			var company = '<li><span class="company-name">'+node.threecompany[key].companyname +'</span><span class="score">'+node.threecompany[key].pingfen+'</span></li>';
			console.log(company);
			$(company).appendTo("#hot-company");
		}
	}
	appendTo_list(box,node);
}
//为您指定管家
function guanJia(node) {
	var box = $("#zhiding");
	if(node.nodestatus == 1)
	{
		$("#manager-name").html(node.guanjia.gjname);
		$("#manager-face").attr("src",node.guanjia.personalphoto);
		$("#manager-phone").html(node.guanjia.mobile);
		$("#take-phone").attr("href","tel:"+node.guanjia.mobile);
	}
	appendTo_list(box,node);
}
//上门量房
function liangFang(node) {
	var box = $("#liangfang");
	if(node.nodestatus == 1)
	{
		var states = node.message;
		for(var i=0; i<states.length; i++) {
			var state = '<li><div class="company-name">'+states[i].company+'</div><div class="state">'+states[i].beizhu+'</div></li>';
			$(state).appendTo("#state-list");
		}
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//选定装修公司
function gongSi(node) {
	var box = $("#xuangongsi");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.company);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
    appendTo_list(box,node);
}
//意向定金
function dingJin(node) {
	var box = $("#qianxieyi");
	if(node.nodestatus == 1)
	{
		$("#qxy-qd").html(node.beizhu);
		$("#xybh").html(node.jine);
		$("#yfk").hide();
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//签订设计协议
function qianDing(node) {
	var box = $("#qianxieyi");
	if(node.nodestatus == 1)
	{
		$("#qxy-qd").html(node.beizhu);
		$("#xybh").html(node.jine);
		$("#yfk").html(node.bianhao);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//无意向定金和装修协议
function DingjinNull(node) {
	var box = $("#qianxieyi");
	appendTo_list(box,node);
    if(node.srcstatus == 0){
        box.find(".live-photo").remove();
    }
}
//方案确定预交底
function jiaoDi(node) {
	var box = $("#quedingfangan");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.beizhu);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//签施工协议
function xieYi(node) {
	var box = $("#shigongxieyi");
	if(node.nodestatus == 1)
	{
		$("#sgxy-id").html(node.bianhao);
		$("#sgxy-pay").html(node.gongchengkuan);
		$("#sgxy-detail").html(node.shijifukuan);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//工程开工
function kaiGong(node) {
	var box = $("#kaigong");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.beizhu);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//拆改
function chaiGai(node) {
	var box = $("#chaigai");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.beizhu);
	}
	appendTo_list(box,node);
}
//水电材料验收
function shuiDian_1(node) {
	var box = $("#shuidian-1");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.beizhu);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//泥木材料验收
function niMu_1(node) {
	var box = $("#nimu-1");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.beizhu);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//油漆材料验收
function youQi_1(node) {
	var box = $("#youqi-1");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.beizhu);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//水电验收
function shuiDian_2(node) {
	var box = $("#shuidian-2");
	if(node.nodestatus == 1)
	{
		$("#sd2-beizhu").html(node.beizhu);
		$("#sd2-jine").html(node.jine);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//泥木验收
function niMu_2(node) {
	var box = $("#nimu-2");
	if(node.nodestatus == 1)
	{
		$("#nm2-beizhu").html(node.beizhu);
		$("#nm2-jine").html(node.jine);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//油漆验收
function youQi_2(node) {
	var box = $("#youqi-2");
	if(node.nodestatus == 1)
	{
		$("#yq2-beizhu").html(node.beizhu);
		$("#yq2-jine").html(node.jine);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//竣工验收
function junGong(node) {
	var box = $("#jungong");
	if(node.nodestatus == 1)
	{
		var money;
		if(!node.zengxiang)
		{
			money = "";
		}
		else {
			money = node.zengxiang;
		}
		$("#jg-beizhu").html(node.beizhu);
		$("#jg-jine").html(money);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//竣工污染检测
function wuRan(node) {
	var box = $("#wuran_1");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.beizhu);
	}
	appendTo_list(box,node);
    if(node.srcstatus == 0){
        box.find(".live-photo").remove();
    }
}
//污染治理
function zhiLi(node) {
	var box = $("#wuran_2");
	if(node.nodestatus == 1)
	{
		$("#wr-cp").html(node.company);
		$("#wr-bz").html(node.beizhu);
		if(node.bianhao)
		{
			$("#wr-id").html("协议编号："+node.bianhao);
		}
		if(node.jine) 
		{
			$("#wr-mn").html(node.jine);	
		}
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//复测_1
function fuCe_1(node) {
	var box = $("#fuce1");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.beizhu);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//复测 2
function fuCe_2(node) {
	var box = $("#fuce2");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.beizhu);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//尾款质保期
function weiKuan(node) {
	var box = $("#weikuan");
	if(node.nodestatus == 1)
	{
		box.find(".detail").find("p").html(node.beizhu);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//入住空气检测
function ruZhu(node) {
	var box = $("#kongqi");
	if(node.nodestatus == 1)
	{
		$("#kq-bz").html(node.beizhu);
		if(node.zengsong) {
			$("#kq-zs").html(node.zengsong);
		}
		else {
			$("#kq-zs").hide();
		}
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
//空气治理
function kongQi(node) {
	var box = $("#zhili");
	if(node.nodestatus == 1)
	{
		$("#zl-cp").html(node.company);
		$("#zl-id").html(node.bianhao);
		$("#zl-mn").html(node.jine);
		$("#zl-bz").html(node.beizhu);
        if(node.srcstatus == 0){
            box.find(".live-photo").remove();
        }
	}
	appendTo_list(box,node);
}
function appendTo_list(box,node,time) {
	if(node.nodestatus == 1)
	{
		box.find(".time").html(node.shijian);
		box.addClass("completed");
	}
	if(node.src)
	{
		box.find(".node-detail").attr("imgsrc",node.src).attr("node_name",node.nodename);
	}
	box.appendTo("#steps");
}
//Show node picture
function showPic(nodeName,imgsrc) {
	var picbox = '<div id="blacklayer"><div id="pic-container" class="set-center"><h2>'+nodeName+'</h2><div id="img-box"><img id="node-img" src="'+imgsrc+'"></div></div></div>';
	$(picbox).appendTo("body");
	$("#pic-container").bind("click",function(e){
		e.stopPropagation();
	});
	$("#blacklayer").bind("click",function(){
		$("#blacklayer").remove();
	});
}