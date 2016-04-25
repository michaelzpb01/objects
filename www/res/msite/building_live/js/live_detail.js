// Live Detail  
var score_star;

//type 为0为非必须节点（未完成不显示）, 1为必须节点
//s 为0 为普通节点，1 为特殊节点
//开启评价  打开205行注释 getEva(order_id);
var nodes = {
    
		11: {nodename:"上门量房", tagid:"smlf", type:1, s:0},
		13: {nodename:"选定装修公司", tagid:"xdzxgs", type:1, s:0},
		15: {nodename:"签订设计协议/意向定金", tagid:"qdsjxy", type:1, s:0},
		17: {nodename:"方案确定预交底", tagid:"faydyjd", type:1, s:0},
		19: {nodename:"签施工协议", tagid:"qsgxy", type:1, s:0},
		21: {nodename:"工程开工", tagid:"gckg", type:1, s:0},
		23: {nodename:"拆改", tagid:"cg", type:0, s:0},
		
		25: {nodename:"水电材料验收", tagid:"sdclys", type:1, s:1},
		27: {nodename:"水电验收", tagid:"sdys", type:1, s:1},
		29: {nodename:"泥木材料验收", tagid:"nmclys", type:1, s:1},
		31: {nodename:"泥木验收", tagid:"nmys", type:1, s:1},
		33: {nodename:"油漆材料验收", tagid:"yqclys", type:1, s:1},
		35: {nodename:"油漆验收", tagid:"yqys", type:1, s:1},
		37: {nodename:"竣工验收", tagid:"jgys", type:1, s:1},
		
		39: {nodename:"竣工污染检测", tagid:"jgwrjc", type:1, s:0},
		41: {nodename:"污染治理", tagid:"wrzl", type:0, s:0},
		43: {nodename:"复测", tagid:"fc1", type:0, s:0},
		45: {nodename:"尾款质保期", tagid:"wkzbq", type:1, s:0}, 
		47: {nodename:"入住空气检测", tagid:"rzkqjc", type:1, s:0},
		49: {nodename:"空气治理", tagid:"kqzl", type:0, s:0},
		51: {nodename:"复测", tagid:"fc2", type:0, s:0},
		52: {nodename:"完结", tagid:"wj", type:1, s:0}
		
}

var lastnode = {
        FW: {title:"细木制品工程", id:0},
        CO: {title:"清油工程",    id:1},
        WE: {title:"水电气工程",  id:2},
        MO: {title:"混油工程",    id:3},
        LP: {title:"乳胶漆工程",  id:4},
        FB: {title:"饰面砖工程",  id:5},
        C:  {title:"检查项目",    id:6}
}

var num_ch = ["一、","二、","三、","四、","五、","六、","七、"]

var order_id = 1790;	//Test id 1898 or 1790 myself=1

var my_live;
var scroll_dis; //定位位置变量

$(function(){
	
    my_live = getUrlParameter("myself");
    if(my_live == 1){
        $("#favor-btn").hide();
        $(".contact-us").remove();
        $("#user-box").hide();
        $('<a id="gj-phone" href=""><i class="iconfont icon-dianhua"></i></a>').appendTo("#gj");
    }

    var live_id = getUrlParameter("live_id");
    var url = encodeURIComponent(window.location.href.split("#")[0]);
    wxShare(url, "gdzb", live_id);

    if(live_id){
        order_id = live_id;
    }
    
	getDetail();
	
	function getDetail() {
		$.ajax({
			type: "POST",
			url: "index.php?m=wap&f=biz_log&v=log&orderid="+order_id,
			dataType: "json",
			timeout: 3000,
			success: function(data){
                console.log(data)
                setFavorBtn(data.data.collectstatus);
				setOrderData(data.data.node, order_id);
				setInit(data.data.resu);
                
                if(data.data.col3 == "意向定金协议"){
                    $("#qdsjxy .step-name").text("意向定金");
                }
                if(data.data.col2 == "否"){
                    $("#cg").hide();
                }
                // if(data.data.orderstatus == "完结"){
                //     $("#wj").addClass("completed");
                //     $(".completed").removeClass("lastChild slideDown").last().addClass("lastChild slideDown");
                // }
			},
			error: function(XMLHttpRequest, textStatus){
				console.log("getDetail error");
			}
		});
	}
	
    
    $("#favor-btn").bind("click",setFavorite);
	showBigPic();

    $("#gj-contact").bind('click', function(e) {
        var that = $(this);
        sendUrl(that, "#gj-name");
    });
    $("#gs-contact").bind('click', function(e) {
        var that = $(this);
        sendUrl(that, "#company-name");
    });

    //确定来源
    function sendUrl(id, name){
        var liveName = $("#order-name").html();
        var thisName = $(name).html();
        if(browser.versions.weixin){
                id.attr("href","mobile-sale_price.html?id=微信-工地直播-"+liveName+"-"+thisName);
            }else if(browser.versions.weibo){
                id.attr("href","mobile-sale_price.html?id=微博-工地直播-"+liveName+"-"+thisName);
            }else{
                id.attr("href","mobile-sale_price.html?id=M站-工地直播-"+liveName+"-"+thisName);
            }
    }
})

//设置收藏按钮状态
function setFavorBtn(state){
    if(state == 1){
        $("#favor-btn .iconfont").removeClass("icon-star_one").addClass("icon-star_two");
    }
}

//请求收藏
function setFavorite(){
    $.ajax({
        type: "POST",
        url: "index.php?m=wap&f=member&v=fav_day_log&type=day_log&orderid="+order_id,
        dataType: "json",
        timeout: 3000,
        success: function(data){
            console.log("获取收藏状态");
            console.log(data);
        if(data.code == 0){
            alertConfirm("登录后才能收藏哦，去登录~");
            $("#confirm-layer").on('touchmove', function(event){
                event.preventDefault();
            })
        }else {
            if(data.data.collectstatus == 1)
            {
                $("#favor-btn .iconfont").removeClass("icon-star_one").addClass("icon-star_two");
            }
            else {
                $("#favor-btn .iconfont").removeClass("icon-star_two").addClass("icon-star_one");
            }
            collectShow(data.message);
        }
            
        },
        error: function(XMLHttpRequest, textStatus){
            console.log("getNodeData error");
        }
    });
}

//设置订单头部数据
function setOrderData(data) {
	
	console.log(data);
	if(my_live == 1){
        $("#gj-phone").attr("href","tel:"+data.mobile);
    }
	if(data.logname){
		$("#order-name").text(data.logname);	
	}
	if(data.homestyle[0]){
		for(var i=0; i<data.homestyle.length; i++){
			var style_tag = "<li>"+data.homestyle[i]+"</li>";
			$(style_tag).appendTo("#order-tags");
		}
	}
	if(data.area){
		var home_area = "<li>"+data.area+"㎡</li>";
		$(home_area).appendTo("#order-tags");
	}
    for(var i=0; i<data.style.length; i++){
        if(data.style[i]){
            var style_tag = "<li>"+data.style[i]+"</li>";
            $(style_tag).appendTo("#order-tags");
        }
    }
    if(data.way[0]){
		var home_way = "<li>"+data.way[0]+"</li>";
		$(home_way).appendTo("#order-tags");
	}
	$("#user-name").text(data.name);
	$("#user-face").attr("src",data.avatar);
	
	$("#gj-name").text(data.gjname);
	$("#gj-face").attr("src",data.personalphotos);
    
    if(data.company){
        $("#company-name").html(data.company);
    }
    else {
        $("#company-name").html("待确定");
        $("#gs-contact").hide();
    }
	$("#company-logo").attr("src",data.company_photo);
	
	getEva(order_id);
	
}
//Tag_id 转换为  Node_id
function getNodeId(tag_id) {
    for(var key in nodes){
        if(nodes[key].tagid == tag_id){
            var temp = {id:key, style:nodes[key].s}
            return temp;
        }
    }
}
//Node_id 转换为 Tag_id
function getTagId(node_id){
    for(var key in nodes){
        if(key == node_id){
             return nodes[key].tagid;
        }
    }
}

//初始化列表标签
function setInit(node_array){
    
	for(var i=0; i<node_array.length; i++){
        var pass_node = node_array[i];
        var pass_id = pass_node[0];
        var pass_li = $("#"+nodes[pass_id].tagid);
        $(pass_li).addClass("completed");
        if(nodes[pass_id].type == 0){
            $(pass_li).addClass("kexuan");   
        }
        $(pass_li).find(".title .time").html(pass_node[1][1]+"-"+pass_node[1][2]);
        // switch(pass_id){
        //     case "11":
        //         $("#step-A .step-title .time").html("("+pass_node[1][0]+"-"+pass_node[1][1]+"-"+pass_node[1][2]+")");
        //         break;
        //     case "21":
        //         $("#step-B .step-title .time").html("("+pass_node[1][0]+"-"+pass_node[1][1]+"-"+pass_node[1][2]+")");
        //         break;
        //     case "39":
        //         $("#step-C .step-title .time").html("("+pass_node[1][0]+"-"+pass_node[1][1]+"-"+pass_node[1][2]+")");
        //         break;    
        // }
	}
    
    $("#step-B .completed").prependTo("#step-B .living-list");
    
    //绑定下拉事件
    $(".completed .title").bind("click",function(){
        var li_box = $(this).parents("li");
        if(!$(li_box).hasClass("rendered")){
            var now = getNodeId($(li_box).attr("id"));
            getNodeData(now.id);
        }
        if($(li_box).hasClass("slideDown")){
            $(li_box).find(".check-item").removeClass("open-state");
            if($(li_box).attr("id") == "fc1" || $(li_box).attr("id") == "fc2"){
                $(li_box).find(".check-list").removeClass("close-detail");
            }
            else{
                $(li_box).find(".check-list").addClass("close-detail");  
            }
            $(li_box).removeClass("slideDown");
        }
        else{
            $(li_box).addClass("slideDown");
        }
    });
    
    //展开最新的节点
    var lastedNoade = $(".completed").last();
    var last_index = $(lastedNoade).index();
    var container_id = $(lastedNoade).parents("div").attr("id");
    var li_h = $(".living-li").height();
    var header_h = $("header").height();
    var title_h = $("#order-title").height();
    var eva_h = $("#gj-list").height();
    var step_h = $(".step-title").height();
    var win_h = $(window).height();
    if(container_id == "step-A"){
        scroll_dis = last_index*li_h+step_h+title_h+eva_h;
    }
    else if(container_id == "step-B"){
        var a_h = $("#step-A").height();
        scroll_dis = last_index*li_h+a_h+step_h+title_h+eva_h;
    }
    else {
        var a_h = $("#step-A").height();
        var b_h = $("#step-B").height();
        scroll_dis = last_index*li_h+a_h+b_h+step_h+title_h+eva_h;
    }
    if(scroll_dis > win_h) {
        console.log("scrolltop: "+scroll_dis);
        setTimeout(function(){document.body.scrollTop = scroll_dis;},30);
    }
    
    $(lastedNoade).find(".title").trigger("click");
    
    //绑定展开事件
    $("#slide-btn").bind("click",function(){
        var btn_icon = $(this).find("i");
        if(btn_icon.hasClass("icon-openbtn")){
            if(!$(this).hasClass("already")){
                $(this).addClass("already");   
                getAll();
            }
            $(".living-li.completed").addClass("slideDown");
            btn_icon.removeClass("icon-openbtn").addClass("icon-closebtn");
        }
        else{
            closeAll();
            btn_icon.removeClass("icon-closebtn").addClass("icon-openbtn");
        }
    })
    
    //设置最后一个完成节点颜色
    $(".completed").last().addClass("lastChild");
    
    //隐藏未完成的非必须节点
    for(var key in nodes){
        if(nodes[key].type == 0){
            var temp_tag = $("#"+nodes[key].tagid);
            if(!$(temp_tag).hasClass("completed")){
                $(temp_tag).hide();
            };
        };
    }
    
    $("#live-detail").bind("click",function(e){
        if($(e.target).hasClass("check-item")){
            var check_parent = $(e.target);
        }
        else{
            var check_parent = $(e.target).parents(".check-item");
        }
        if(check_parent.length > 0){
            $(check_parent).next(".check-list").toggleClass("close-detail"); 
            $(check_parent).toggleClass("open-state");
        }
    })
    
}

//获取单个节点信息
function getNodeData(node_id) {
    $.ajax({
        type: "POST",
        url: "index.php?m=wap&f=biz_log&v=index&orderid="+order_id+"&nodeid="+node_id,
        dataType: "json",
        timeout: 3000,
        success: function(data){
            console.log(data);
            var boxid = getTagId(node_id);
            renderData(node_id, boxid, data.data[node_id]);
            scaleImg();
        },
        error: function(XMLHttpRequest, textStatus){
            console.log("getNodeData error");
        }
    });
}

//获取所有节点信息
function getAll() {
    $.ajax({
        type: "POST",
        url: "index.php?m=wap&f=biz_log&v=index&orderid="+order_id,
        dataType: "json",
        timeout: 3000,
        success: function(data){
            console.log(data);
            for(var key in data.data){
                var temp_box = nodes[key].tagid;
                var temp_data = data.data[key];
                var li_box = $("#"+temp_box);
                if(!$(li_box).hasClass("rendered")){
                    renderData(key, temp_box, temp_data);
                    scaleImg();
                }   
            }
        },
        error: function(XMLHttpRequest, textStatus){
            console.log("getAll error");
        }
    });
}

//关闭所有节点
function closeAll() {
    $(".check-item").removeClass("open-state");
    $(".check-list").addClass("close-detail");
    $("#fc2").find(".check-list").removeClass("close-detail");
    $(".living-li").removeClass("slideDown");
}

//获取评价信息
function getEva(id) {
	$.ajax({
		type: "POST",
		url: "index.php?m=wap&f=comment&v=biz_comment&orderid="+id,
		dataType: "json",
		timeout: 3000,
		success: function(data){
			console.log(data);
			if(data.data.guanjia.data || data.data.company.data){
				$("#get-eva").show();
			}
			$("#eva-rank").text(data.data.guanjia.satisfaction);
            if(data.data.guanjia.data){
                $("#eva-con").html(data.data.guanjia.data);
            }
			else{
                $("#gj-eva").hide();
            }
            if(data.data.company.data){
                $("#company-eva").html(data.data.company.data);
            }
			else{
                $("#gs-eva").hide();
            }
            if(data.data.company.replydata){
                $("#company-reply").html(data.data.company.replydata); 
            }
			else{
                $("#reply-box").hide();
            }
            if(data.data.company.appdata){
                $("#user-reply").html(data.data.company.appdata);  
            }
            else{
                $(".company-reply").eq(1).hide();
            }
			
			(data.data.company.total)?score_star = data.data.company.total:score_star = 0;
            
            $("#get-eva").bind("click",function(){
                $("#user-box").toggleClass("slideDown");
                $("#eva-content").toggle();
                if($("#user-box").hasClass("slideDown")){
                    setStar("stars",score_star);
                    $("#score").html(score_star);
                    //缩放字体
                    var reply_h = $("#reply-box").height();
                    $("#reply-box").height(reply_h/2);
                }
                else {
                    $("#reply-box").height("auto");
                }
            });
            
		},
		error: function(XMLHttpRequest, textStatus){
			console.log("getEva error");
		}
	});
}

//计算评价星星的样式
function setStar(star_id,score){
	
	var star_number = Math.floor(score);
	var star_box = $("#"+star_id).find("li");
	for(var i=0; i<star_box.length; i++){
		if(i>star_number-1) {
			var yellowstar = $(star_box).eq(i).find(".yellow");
			if(i-star_number>0){
				$(yellowstar).width("0");
			}
			else {
				var graywidth = $(".icon-star_two.gray").width();
			    var yu = score*10%10*0.1*graywidth;
			    $(yellowstar).width(yu+"px");
			}
		}
	}
}

//判断节点类型
function renderData(node_id, boxid, data){
    
    $("#"+boxid).addClass("rendered");
    if(data.type==0){
        
        //普通节点
        var pic_box = $("#"+boxid).find(".imgs-list");
        pic_box.addClass('show-pic');
        $("#"+boxid).find(".txt").html("").html(data.content);
        pic_box.html("");
        for(var i=0; i<data.recphoto.length; i++){
            $('<li><span><img src="'+data.recphoto[i]+'"  bigsrc="'+data.recphotos[i]+'"></span></li>').appendTo(pic_box);
        }
        if(data.recphoto.length == 4){
            pic_box.addClass("four-pic");
        }
    }
    else{
        //特殊节点
        switch(node_id)
        {
            case "25":
                console.log("Node 25");
                renderMaterial(node_id, boxid, data);
                break;
            case "27":
                console.log("Node 27");
                renderProject(node_id, boxid, data);
                break;
            case "29":
                console.log("Node 29");
                renderMaterial(node_id, boxid, data);
                break;
            case "31":
                console.log("Node 31");
                renderProject(node_id, boxid, data);
                break;
            case "33":
                console.log("Node 33");
                renderMaterial(node_id, boxid, data);
                break;
            case "35":
                console.log("Node 35");
                renderProject(node_id, boxid, data);
                break;
            case "37":
                console.log("Node 37");
                renderLast(node_id, boxid, data);
                break;
            case "43":
                console.log("Node 43");
                renderRecheck(node_id, boxid, data);
                break;
            case "51":
                console.log("Node 51");
                renderRecheck(node_id, boxid, data);
                break;
            default:
                console.log("undefine Node");      
        }
    }

}

//特殊材料节点
function renderMaterial(node_id, boxid, data){
    console.log("renderMaterial");
    console.log(data);
    var temp_box = $("#"+boxid);
    var temp_container = $(temp_box).find(".works");
    if(data.nodeinfo){
        $(temp_box).find(".describe").html(data.nodeinfo[0].content);
        var temp_array = data.nodeinfo;
        var temp_ok = [];
        var temp_no = [];
        for(var i=0; i<temp_array.length; i++){
            if(temp_array[i].accQua == "合格"){
                temp_ok.push(temp_array[i]);
            }
            else{
                temp_no.push(temp_array[i]);
            }
        }
        console.log(temp_ok);
        console.log(temp_no);
    }
    if(temp_ok.length>0){
        $(ok_box).appendTo(temp_container);
        for(var i=0; i<temp_ok.length; i++){ 
            var title = (i+1)+" :"+temp_ok[i].proName+" 品牌："+temp_ok[i].brand+" ("+temp_ok[i].accDate+")";
            var temp_li = $(tag_ok);
            var temp_ul = $(temp_box).find(".check ul");
            $(temp_li).appendTo(temp_ul);
            $(temp_li).find("h4").html(title);
            if(temp_ok[i].accImgs == 0){
                $(temp_li).find(".images-2").hide(); 
            }
            else{
                $(temp_li).find("img").attr("src",temp_ok[i].accImg).attr("bigsrc",temp_ok[i].accImgs);
            }
            
        }
        $(temp_ul).prev(".check-item").find(".number").html(temp_ok.length);
    }
    if(temp_no.length>0){
        $(no_box).appendTo(temp_container);
        for(var i=0; i<temp_no.length; i++){  
            var title = (i+1)+" :"+temp_no[i].proName+" 品牌："+temp_no[i].brand;
            var temp_li = $(tag_complete);
            var temp_ul = $(temp_box).find(".restore ul");
            $(temp_li).appendTo(temp_ul);
            $(temp_li).find("h4").html(title);
            $(temp_li).find(".poor").html(temp_no[i].unpuaRea);
            if(temp_no[i].accImg == 0){
                $(temp_li).find(".images-2").hide();  
            }
            $(temp_li).find(".before img").attr("src",temp_no[i].accImg).attr("bigsrc",temp_no[i].accImgs);
            $(temp_li).find(".pre-time").html(temp_no[i].accDate);
            if(temp_no[i].overImg == 0){
                $(temp_li).find(".after").remove();
                $(temp_li).find(".images-2").append(tag_building);
                $(temp_li).find(".after .predict").html(temp_no[i].overDate);
            }
            $(temp_li).find(".after img").attr("src",temp_no[i].overImg).attr("bigsrc",temp_no[i].overImgs);;
            $(temp_li).find(".after-time").html(temp_no[i].overDate);
            
        }
        $(temp_ul).prev(".check-item").find(".number").html(temp_no.length);
    }
}

//特殊工程节点
function renderProject(node_id, boxid, data){
    console.log("renderProject");
    console.log(data);
    var temp_box = $("#"+boxid);
    var temp_container = $(temp_box).find(".works");
    if(data.nodeinfo){
        $(temp_box).find(".describe").html(data.nodeinfo[0].content);
        var temp_array = data.nodeinfo;
        var temp_ok = [];
        var temp_no = [];
        for(var i=0; i<temp_array.length; i++){
            if(temp_array[i].accQua == "合格"){
                temp_ok.push(temp_array[i]);
            }
            else{
                temp_no.push(temp_array[i]);
            }
        }
        console.log(temp_ok);
        console.log(temp_no);
    }
    if(temp_ok.length>0){
        $(ok_box).appendTo(temp_container);
        for(var i=0; i<temp_ok.length; i++){ 
            var title = (i+1)+" :"+temp_ok[i].proName+temp_ok[i].brand+" ("+temp_ok[i].accDate+")";
            var temp_li = $(project_ok);
            var temp_ul = $(temp_box).find(".check ul");
            $(temp_li).appendTo(temp_ul);
            $(temp_li).find("h4").html(title);
            $(temp_li).find(".live-pic").attr("src",temp_ok[i].accImg).attr("bigsrc",temp_ok[i].accImgs);
        }
        $(temp_ul).prev(".check-item").find(".number").html(temp_ok.length);
    }
    if(temp_no.length>0){
        $(no_box).appendTo(temp_container);
        for(var i=0; i<temp_no.length; i++){
            var title = (i+1)+" :"+temp_no[i].proName+temp_no[i].brand;
            var temp_li = $(project_no);
            var temp_ul = $(temp_box).find(".restore ul");
            $(temp_li).appendTo(temp_ul);
            
            $(temp_li).find("h4").html(title);
            $(temp_li).find(".poor").html(temp_no[i].unpuaRea);
            $(temp_li).find(".before-img").attr("src",temp_no[i].accImg).attr("bigsrc",temp_no[i].accImgs);
            
            $(temp_li).find(".pre-time").html(temp_no[i].accDate);
            if(temp_no[i].overImg == 0){
                var temp_line = $(temp_li).find(".line-box");
                $(temp_line).remove();
                $(temp_li).find(".after").remove();
                $(temp_li).find(".images-2").append(tag_building);
                $(temp_li).find(".images-2").append(temp_line);
                $(temp_li).find(".after .predict").html(temp_no[i].overDate);
            }
            $(temp_li).find(".after-img").attr("src",temp_no[i].overImg).attr("bigsrc",temp_no[i].overImgs);
            $(temp_li).find(".after-time").html(temp_no[i].overDate); 
        }
        $(temp_ul).prev(".check-item").find(".number").html(temp_no.length);
    }
}

//竣工节点
function renderLast(node_id, boxid, data){
    
    $("#jgys").find(".describe").html(data.content);
    
    var temp_nodes = [];
    // var temp_ok = [];
    // var temp_no = [];
    for(var i=0; i<data.res.length; i++){
        var temp_item = data.res[i];
        var temp_index = temp_item.node;
        var temp_id = lastnode[temp_index].id;
        
        if(!temp_nodes[temp_id]){
            temp_nodes[temp_id] = [];
        }
        temp_nodes[temp_id].push(temp_item);
    }
    
    console.log(temp_nodes);
    for(var key in temp_nodes){
        var n_name = getPojectName(key);
        var temp_final = $(final_check);
        $("#final-check").append(temp_final);
        $(temp_final).find(".pro-name").html(num_ch[key]+n_name);
        for(var i=0; i<temp_nodes[key].length; i++){
            var tempProject = temp_nodes[key][i];
            console.log(tempProject.proName);

            if(tempProject.accQua == "合格"){
                // temp_ok.push(tempProject);
                var check_box = $(temp_final).find(".check").length;
                if(check_box == 0){
                    $(ok_box).appendTo(temp_final);  
                }
                var title = (i+1)+" :"+tempProject.proName+" ("+tempProject.accDate+")";
                var temp_li = $(project_ok);
                var temp_ul = $(temp_final).find(".check ul");
                $(temp_li).appendTo(temp_ul);
                $(temp_li).find("h4").html(title);
                $(temp_li).find(".live-pic").attr("src",tempProject.accImg).attr("bigsrc",tempProject.accImgs);
            }
            else{
                // temp_no.push(tempProject);
                var check_box = $(temp_final).find(".restore").length;
                if(check_box == 0){
                    $(no_box).appendTo(temp_final);
                }
                
                var title = (i+1)+" :"+tempProject.proName;
                var temp_li = $(project_no);
                var temp_ul = $(temp_final).find(".restore ul");
                $(temp_li).appendTo(temp_ul);
                
                $(temp_li).find("h4").html(title);
                $(temp_li).find(".poor").html(tempProject.unpuaRea);
                $(temp_li).find(".before-img").attr("src",tempProject.accImg).attr("bigsrc",tempProject.accImgs);
                
                $(temp_li).find(".pre-time").html(tempProject.accDate);
                if(tempProject.overImg == 0){
                    var temp_line = $(temp_li).find(".line-box");
                    $(temp_line).remove();
                    $(temp_li).find(".after").remove();
                    $(temp_li).find(".images-2").append(tag_building);
                    $(temp_li).find(".images-2").append(temp_line);
                    $(temp_li).find(".after .predict").html(tempProject.overDate);
                }
                $(temp_li).find(".after-img").attr("src",tempProject.overImg).attr("bigsrc",tempProject.overImgs);
                $(temp_li).find(".after-time").html(tempProject.overDate);  
            }
        }

        var temp_ok_number = $(temp_final).find(".check ul li").length;
        var temp_no_number = $(temp_final).find(".restore ul li").length;
        console.log("temp_ok_number:"+temp_ok_number);
        console.log("temp_no_number:"+temp_no_number);
        if(temp_ok_number > 0){
            $(temp_final).find(".check .number").html(temp_ok_number);
        }
        if(temp_no_number > 0){
            $(temp_final).find(".restore .number").html(temp_no_number);
        } 
    }
    var firt_title = $("#jgys .pro-name")[0];
    $(firt_title).addClass("m0");
    
}

//复测节点
function renderRecheck(node_id, boxid, data){
    console.log("复测节点");
    console.log(data);
    
    var temp_data = data.nodeinfo[0];
    var temp_box = $("#"+boxid);
    var temp_container = $(temp_box).find(".works");
    
    if(temp_data.col == "否"){
        $(temp_box).find(".state").text("复测结果：未通过");
        $(temp_box).find(".txt").html(temp_data.content);

        $(no_box).appendTo(temp_container);
        var temp_ul = $(temp_box).find(".restore ul");
        $(temp_ul).removeClass("close-detail");
        
        for(var i=0; i<data.nodeinfo.length; i++){
            var temp_data = data.nodeinfo[i];
            var temp_li = $(tag_complete);
            $(temp_li).appendTo(temp_ul);
            
            $(temp_li).find(".before img").attr("src",temp_data.accImg).attr("bigsrc",temp_data.accImgs);
            $(temp_li).find(".pre-time").html(temp_data.addtimes);
            if(temp_data.overImg == 0){
                $(temp_li).find(".after").remove();
                $(temp_li).find(".images-2").append(tag_building);
                $(temp_li).find(".after .predict").html(temp_data.accDate);
            }
            $(temp_li).find(".after img").attr("src",temp_data.overImg).attr("bigsrc",temp_data.overImgs);
            $(temp_li).find(".after-time").html(temp_data.accDate);
        }
    }
    else{
        $(temp_box).find(".state").text("复测结果：通过");
        $(temp_box).find(".txt").html(temp_data.content);
    }
}

function getPojectName(i) {
    for(var key in lastnode){
        if(i == lastnode[key].id){
            return lastnode[key].title;
        }   
    }
}

var ok_box = '<div class="check"><div class="check-item"><i class="iconfont icon-right"></i><span class="check-name">验收合格</span><button><span class="number"></span>项目<i class="iconfont icon-slidearrow"></i></button></div><ul class="check-list clearfix close-detail"></ul></div>';

var no_box = '<div class="restore"><div class="check-item"><i class="iconfont icon-error"></i><span class="check-name">整改项</span><button><span class="number"></span>项目<i class="iconfont icon-slidearrow"></i></button></div><ul class="check-list clearfix close-detail"></ul></div>';

var tag_ok = '<li class="show-pic clearfix"><h4></h4><div class="images-2"><div class="before"><div class="image"><span><img src=""></span></div></div></div></li>';

var tag_complete = '<li class="show-pic clearfix"><h4></h4><p class="poor"></p><div class="images-2"><div class="before"><div class="image"><span><img src=""></span></div><p>整改前</p><p class="pre-time"></p></div><div class="after"><div class="image"><span><img src=""></span></div><p>整改后</p><p class="after-time"></p></div></div></li>';

var tag_building = '<div class="after"><div class="wait"><div class="content"><p>要求</p><p class="predict"></p><p>整改完成</p></div></div></div>';

var project_ok = '<li class="show-pic clearfix"><h4></h4><div class="images-2"><div class="before"><div class="image"><span><img class="live-pic" src=""></span></div><p>现场图片</p></div><div class="after" style="display:none"><div class="image"><span><!--<img class="line-pic" src="image">--></span></div><p>国家标准</p></div></div></li>';

var project_no = '<li class="show-pic clearfix"><h4></h4><p class="poor"><div class="images-2"><div class="before"><div class="image"><span><img class="before-img" src=""></span></div><p>整改前</p><p class="pre-time"></p></div><div class="after"><div class="image"><span><img class="after-img" src=""></span></div><p>整改后</p><p class="after-time"></p></div><div class="line-box" style="display:none"><div class="image"><!--<img  class="line-pic" src="">--></div><p>国家标准</p></div></div></li>';

var final_check = '<section class="works"><h3 class="pro-name"></h3></section>';


function showBigPic(){
    $("#live-detail").bind("click", function(event){
        $("#big-pic-content").remove();
        var bigPicContent = '<ul id="big-pic-content"></ul>';
        $("#slider").append(bigPicContent);
        var index;
        if($(event.target).attr("bigsrc")) {
            $(".show-pic").removeClass('nowNode');
            $(event.target).parents('.show-pic').addClass('nowNode');
            var imgs = $(event.target).parents(".show-pic").find("img");
            imgs.each(function(item, el) {
                if($(el).attr("src") === $(event.target).attr("src")) {
                    index = item + 1;
                }
                var bigSrc = $(this).attr("bigsrc");
                $('<li class="single-pic"><div class="pic-info"><img class="lazy-bimg" data-original="'+bigSrc+'"><div class="loader"></div></div></li>').appendTo("#big-pic-content");
            });
            var liveDetail = $("#live-detail"),
                docH = $(document).height(),
                winH = $(window).height(),
                winW = $(window).width();

            $('.pic-info').height(winH);
            $('.pic-local').width(winW);

            liveDetail.css({
                height: winH,
                overflow: "hidden"
            });
            $("#big-pic").show().css({
                width: winW,
                height: winH,
                overflow: "auto"
            });
            preLoadImg(index);
            var swipe = new Swipe(document.getElementById('slider'), {
                speed: 400,
                startSlide: index-1,
                callback: function() {
                    //current index position
                    var index=this.getPos()+1;
                    $("#current-number").text(index);
                    preLoadImg(index);
                }
            });
            //初始化
            $("#current-number").text(swipe.getPos()+1);
            //total index position
            $("#total-number").text(swipe.getLength());
            $('.pic-info').each(function(){
                new RTP.PinchZoom($(this), {});
            });

            $(".go-back").click(function(){
                var headerH = $("header").height(),
                    btmH = $(".bottom-btn").height();
                $("#big-pic").hide();

                liveDetail.css({
                    height: "auto",
                    overflow: ""
                });
                var nTop = $(".nowNode").offset().top - headerH*3;
                document.body.scrollTop = nTop;
            });
        }
    });
}

function preLoadImg(index){
    var imgs = $(".lazy-bimg");
    var now_img = $(imgs).eq(index-1);
    var pre_img = $(imgs).eq(index-2);
    var next_img = $(imgs).eq(index);
    setSrc(pre_img);
    setSrc(now_img);
    setSrc(next_img);
    for(var i=0; i< imgs.length; i++){
        imgs[i].onload = function(){
            $(this).addClass("opacity1");
            $(this).siblings(".loader").hide();
        };
    }
}

function setSrc(img){
    var img_src = $(img).attr("data-original");
    $(img).attr("src",img_src);
}

function scaleImg() {
    var imgs = $(".living-list img");
    var boxW = imgs.parent().width(); //区域宽度
    var boxH = imgs.parent().height(); //区域高度
    var ratio = boxW/boxH;
    for(var i=0; i<imgs.length; i++) {
        imgs[i].onload = function(){
            var img = new Image();
            img.src = this.src;
            var imgW = img.width;
            var imgH = img.height;
            var imgRatio = imgW/imgH;
            if(ratio > imgRatio){
                var height_t=-((boxW/imgW)*imgH - boxH)/2;
                this.style.height = "auto";
                this.style.marginTop = height_t + "px";
            } else {
                var width_w=-((boxH/imgH)*imgW - boxW)/2;
                this.style.width = "auto";
                this.style.marginLeft = width_w + "px";
            }
        };
    }
}