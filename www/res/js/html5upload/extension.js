//插入附件的回调函数
function callback_thumb_dialog(filename,htmlid) {
    var dialog = top.dialog.get(window);
	var htmlid = htmlid ? htmlid : 'testid';
    dialog.close(filename).remove();
    return false;
}

//插入附件的回调函数
function insert_file_callback(filename) {
	//alert(filename);
	document.getElementById('thumb').innerHTML += "<img src='"+filename+"' width='60' height='60'>";
}


//后台上传附件时的回调方法
function callback_image_dialog (filename,htmlid,is_thumb,htmlname) {
	var file_array = filename.split('|');
	var str = '';
	$.each( file_array, function(i, n) {
		var temp = n.split(',');
		var file_url = temp[0];
		var file_alt = temp[1];
		var file_id = temp[2];
		str += '<li id="file_node_'+file_id+'"><div class="img_box"><input type="hidden" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'"> <img src="http://www.uzhuang.com/image/small_square/'+file_url+'" alt="'+file_alt+'" onclick="img_view(this.src);"> <textarea name="'+htmlname+'['+file_id+'][alt]" onfocus="if(this.value == this.defaultValue) this.value = \'\'" onblur="if(this.value.replace(\' \',\'\') == \'\') this.value = this.defaultValue;">'+file_alt+'</textarea><a class="btn btn-danger btn-xs" href="javascript:remove_file('+file_id+');">移除</a></div><div class="link_box"><div class="btn-group border" role="group" aria-label="..." style="margin-top:10px"><button type="button" class="btn active btn-default">original</button><button type="button" class="btn btn-default">big</button><button type="button" class="btn btn-default">middle</button><button type="button" class="btn btn-default">small</button><button type="button" class="btn btn-default">thumb</button><button type="button" class="btn btn-default">small_thumb</button><button type="button" class="btn btn-default">original</button><button type="button" class="btn btn-default">middle_square</button><button type="button" class="btn btn-default">small_square</button></div><div class="img_link"><a href="javascript:void(0)" target="_blank" class="btn btn-success" style="margin-top:10px">查看图片</a><div class="img_src">图片地址：www.uzhuang.com/image/<span class="img-type">original</span>/'+file_url+'</div></div></div></li>';
	});
	var dialog = top.dialog.get(window);
	dialog.close(str).remove();
	return false;
}

/**
 * 多文件上传回调方法
 *
 * @author tuzwu
 * @createtime
 * @modifytime
 * @param	
 * @return
 */
function callback_more_dialog(filename,htmlid,is_thumb,htmlname)
{
	var file_array = filename.split('|');
	var str = '';
	$.each( file_array, function(i, n) {
		var temp = n.split(',');
		var file_url = temp[0];
		var file_alt = temp[1];
		var file_id = temp[2];
		str += '<li id="file_node_'+file_id+'"><input type="hidden" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'"> <img src="http://m.uzhuang.com/image/small_square/'+file_url+'" alt="'+file_alt+'" onclick="img_view(this.src);"> <textarea name="'+htmlname+'['+file_id+'][alt]" onfocus="if(this.value == this.defaultValue) this.value = \'\'" onblur="if(this.value.replace(\' \',\'\') == \'\') this.value = this.defaultValue;">'+file_alt+'</textarea> <a class="btn btn-danger btn-xs" href="javascript:remove_file('+file_id+');">移除</a></li>';
	});
	var dialog = top.dialog.get(window);
	dialog.close(str).remove();
	return false;
}

/**
 *移除文件
 *
 * @author tuzwu
 * @createtime
 * @modifytime
 * @param	
 * @return
 */
function remove_file(file_id)
{
	$('#file_node_'+file_id).remove();
}

function img_view(src)
{
	var ext = src.substring(src.lastIndexOf("."));
	ext = ext.toLowerCase();
	if(!/\.(gif|jpg|jpeg|png|bmp)$/.test(ext)) 
	{
		return false;
	}
    if(src.lastIndexOf("?")==0) {
        openiframe(src,'img_view','图片预览','800','560');
    } else {
        top.dialog({
            title: '图片预览',
            quickClose: true,
            content: '<img src="http://mdev.uz.com/image/original/'+src+'" style="max-width:1000px;max-height:1000px;">'
        }).show();
    }
}
function img_viewss(src)
{
	var ext = src.substring(src.lastIndexOf("."));
	ext = ext.toLowerCase();
	if(!/\.(gif|jpg|jpeg|png|bmp)$/.test(ext)) 
	{
		return false;
	}
    if(src.lastIndexOf("?")==0) {
        openiframe(src,'img_view','图片预览','800','560');
    } else {
        top.dialog({
            title: '图片预览',
            quickClose: true,
            content: '<img src="'+src+'" style="max-width:1000px;max-height:1000px;">'
        }).show();
    }
}

function img_views(src)
{
	var ext = src.substring(src.lastIndexOf("."));
	ext = ext.toLowerCase();
	if(!/\.(gif|jpg|jpeg|png|bmp)$/.test(ext)) 
	{
		return false;
	}
    if(src.lastIndexOf("?")==0) {
        openiframe(src,'img_view','图片预览','800','560');
    } else {
        top.dialog({
            title: '图片预览',
            quickClose: true,
            content: '<img src="http://mdev.uz.com/image/original/'+src+'" style="max-width:700px;max-height:500px;">'
        }).show();
    }
}
function img_wei(src)
{
    var ext = src.substring(src.lastIndexOf("."));
    ext = ext.toLowerCase();
    if(!/\.(gif|jpg|jpeg|png|bmp)$/.test(ext)) 
    {
        return false;
    }
    if(src.lastIndexOf("?")==0) {
        openiframe(src,'img_view','图片预览','800','560');
    } else {
        top.dialog({
            title: '二维码扫描',
            quickClose: true,
            content: '<img src="'+src+'" style="max-width:1000px;max-height:1000px;">'
        }).show();
    }
}


/**
 * 点评
 *
 * @author tuzwu
 * @createtime
 * @modifytime
 * @param
 * @return
 */
function callback_dianping(filename,htmlid,is_thumb,htmlname)
{
	var file_array = filename.split('|');
	var str = '';
	$.each( file_array, function(i, n) {
		var temp = n.split(',');
		var file_url = temp[0];
		var file_alt = temp[1];
		var file_id = temp[2];
		str += '<li id="file_node_'+file_id+'"><input type="hidden" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'"> <img src="'+file_url+'" alt="'+file_alt+'" onclick="img_view(this.src);"></li>';
	});

	var dialog = top.dialog.get(window);
	dialog.close(str).remove();
	return false;
}

function callback_images2(filename,htmlid,is_thumb,htmlname)
{
    var file_array = filename.split('|');
    var str = '';
    $.each( file_array, function(i, n) {
        var temp = n.split(',');
        var file_url = temp[0];
        var file_alt = temp[1];
        var file_id = temp[2];
        var lastIndex = temp[1].lastIndexOf('.');
        var newStr = temp[1].slice(0,lastIndex);
        var space = Array();
        space = newStr.split('-');
        
        // console.log(temp[3]);
        // str += '<li id="file_node_'+file_id+'"><table class="table table-striped table-advance table-hover"><tr><td><input type="hidden" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'"> <img src="http://www.uzhuang.com/image/small_square/'+file_url+'" alt="'+file_alt+'" onclick="img_view(this.src);"></td><td> <select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" selected="">客厅</option><option value="2">卧室</option><option value="3">餐厅</option><option value="4">厨房</option><option value="5">卫生间</option><option value="6">阳台</option><option value="7">书房</option><option value="8">玄关</option><option value="9">儿童房</option><option value="10">衣帽间</option><option value="11">花园</option></select> </td><td> <textarea style="height:50px;" name="'+htmlname+'['+file_id+'][alt]" onfocus="if(this.value == this.defaultValue) this.value = \'\'" onblur="if(this.value.replace(\' \',\'\') == \'\') this.value = this.defaultValue;">'+file_alt+'</textarea></td><td><a class="btn btn-danger btn-xs" href="javascript:remove_file('+file_id+');">移除</a></td></tr></table></li>';
        var optiones;
        if(space[3]=='玄关'){
            optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" selected>玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8">餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='玄关特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2" selected>玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='客厅'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3" selected>客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8">餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22" >厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='客厅特写'){
            optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4" selected>客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24" >卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='过道'){
            optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5" selected>过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26" >阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='过道特写'){
            optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6" selected>过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14" >书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='隔断'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7" selected>隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29" >平面图</option></select>';
        }else if(space[3]=='餐厅'){
            optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1">玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" selected>餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16" >儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='餐厅特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9" selected>餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='楼梯'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10" selected>楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25" >卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='楼梯特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11" selected>楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='卧室'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12" selected>卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='卧室特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13" selected>卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='书房'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14" selected>书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='书房特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15" selected>书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='儿童房'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16" selected>儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='儿童房特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17" selected>儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='老人房'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18" selected>老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='老人房特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19" selected>老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='平面图'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29" selected>平面图</option></select>';
        }else if(space[3]=='衣帽间'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20" selected>衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='衣帽间特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21" selected>衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='厨房'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22" selected>厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='厨房特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23" selected>厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='卫生间'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24" selected>卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='卫生间特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25" selected>卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='阳台'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26" selected>阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='阳台特写'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1">玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27" selected>阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }else if(space[3]=='其他'){
             optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" >玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28" selected>其他</option><option value="29">平面图</option></select>';
        }else{
            optiones='<select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1">玄关</option><option value="2">玄关特写</option><option value="3">客厅</option><option value="4">客厅特写</option><option value="5">过道</option><option value="6">过道特写</option><option value="7">隔断</option><option value="8" >餐厅</option><option value="9">餐厅特写</option><option value="10">楼梯</option><option value="11">楼梯特写</option><option value="12">卧室</option><option value="13">卧室特写</option><option value="14">书房</option><option value="15">书房特写</option><option value="16">儿童房</option><option value="17">儿童房特写</option><option value="18">老人房</option><option value="19">老人房特写</option><option value="20">衣帽间</option><option value="21">衣帽间特写</option><option value="22">厨房</option><option value="23">厨房特写</option><option value="24">卫生间</option><option value="25">卫生间特写</option><option value="26">阳台</option><option value="27">阳台特写</option><option value="28">其他</option><option value="29">平面图</option></select>';
        }

        str += '<li id="file_node_'+file_id+'"><table class="table table-striped table-advance table-hover"><tr><td style="width:100px;margin-right:18px"><input type="hidden" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'" id="xiao"> <img src="http://mdev.uz.com/image/original/'+file_url+'" alt="'+file_alt+'" onclick="img_viewss(this.src);"></td><td style="width:100px;margin-right:20px"> '+optiones+' </td><td style="width:100px"></td><td style="width:120px"> <textarea placeholder="描述（建议150字以内）"style="height:50px;" name="'+htmlname+'['+file_id+'][alt]" onfocus="if(this.value == this.defaultValue) this.value = \'\'" onblur="if(this.value.replace(\' \',\'\') == \'\') this.value = this.defaultValue;"></textarea></td><td style="width:70px"><a class="btn btn-danger btn-xs" href="javascript:remove_file('+file_id+');">移除</a></td></tr></table></li>';
    });

    var dialog = top.dialog.get(window);
    dialog.close(str).remove();
    return false;
}
function callback_images3(filename,htmlid,is_thumb,htmlname)
{
    var file_array = filename.split('|');
    var str = '';
    $.each( file_array, function(i, n) {
        var temp = n.split(',');
        var file_url = temp[0];
        // alert(file_url);
        var file_alt = temp[1];
        var file_id = temp[2];
        // alert(file_id);
        str += '<li id="file_node_'+file_id+'"><table class="table table-striped table-advance table-hover"><tr><td style="width:100px;margin-right:18px"><input type="hidden" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'"> <img src="http://www.uzhuang.com/image/small_square/'+file_url+'" alt="'+file_alt+'" onclick="img_view(this.src);"></td><td style="width:100px;margin-right:20px"> <select name="'+htmlname+'['+file_id+'][space]" class="form-control" style="width:auto;" id="space"><option value="1" selected="">客厅</option><option value="2">卧室</option><option value="3">餐厅</option><option value="4">厨房</option><option value="5">卫生间</option><option value="6">阳台</option><option value="7">书房</option><option value="8">玄关</option><option value="9">儿童房</option><option value="10">衣帽间</option><option value="11">花园</option></select> </td><td style="width:100px"><select name="'+htmlname+'['+file_id+'][project]" class="form-control" style="width:auto;" id="project"><option value="1" selected="">橱柜</option><option value="2">客厅电视背景墙</option><option value="3">窗帘</option><option value="4">地砖</option><option value="5">地板</option><option value="6">隔断</option><option value="7">客厅吊顶</option><option value="8">书柜</option><option value="9">床头背景墙</option><option value="10">过道吊顶</option><option value="11">其他</option></select></td><td style="width:120px"> <textarea  style="height:50px;" name="'+htmlname+'['+file_id+'][alt]" onfocus="if(this.value == this.defaultValue) this.value = \'\'" onblur="if(this.value.replace(\' \',\'\') == \'\') this.value = this.defaultValue;"></textarea></td><td style="width:70px"><a class="btn btn-danger btn-xs" href="javascript:remove_file('+file_id+');">删除</a></td></tr></table></li>';
    });
    var dialog = top.dialog.get(window);
    dialog.close(str).remove();
    return false;
}
function callback_images4(filename,htmlid,is_thumb,htmlname)
{
    var file_array = filename.split('|');
    var str = '';
    $.each( file_array, function(i, n) {
        var temp = n.split(',');
        var file_url = temp[0];
        var file_alt = temp[1];
        var file_id = temp[2];
       str += '<li id="file_node_'+file_id+'"><table class="table table-striped table-advance table-hover"><tr><td style="width:100px;margin-right:18px"><input type="hidden" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'"> <img src="http://www.uzhuang.com/image/small_square/'+file_url+'" alt="'+file_alt+'" onclick="img_view(this.src);"></td><td><a class="btn btn-danger btn-xs" href="javascript:remove_file('+file_id+');">移除</a></td></tr></table></li>';
        /* str += '<li id="file_node_'+file_id+'"><table class="table table-striped table-advance table-hover"><tr><td><input type="hidden" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'"> <img src="http://www.uzhuang.com/image/small_square/'+file_url+'" alt="'+file_alt+'" onclick="img_view(this.src);"></td><td><a class="btn btn-danger btn-xs" href="javascript:remove_file('+file_id+');">移除</a></td></tr></table></li>';*/
    });
    var dialog = top.dialog.get(window);
    dialog.close(str).remove();
    return false;
}

function callback_images5(filename,htmlid,is_thumb,htmlname)
{
    var file_array = filename.split('|');
    var str = '';
    $.each( file_array, function(i, n) {
        var temp = n.split(',');
        var file_url = temp[0];
        // alert(file_url);
        var file_alt = temp[1];
        var file_id = temp[2];
        // alert(file_id);
        str += '<li id="file_node_'+file_id+'"><table class="table table-striped table-advance table-hover"><tr><td style="width:100px;margin-right:18px"> <img src="http://mdev.uz.com/image/original/'+file_url+'" alt="'+file_alt+'" onclick="img_viewss(this.src); "></td><td style="width:120px"> <textarea  style="height:50px;" name="'+htmlname+'['+file_id+'][alt]" onfocus="if(this.value == this.defaultValue) this.value = \'\'" onblur="if(this.value.replace(\' \',\'\') == \'\') this.value = this.defaultValue;"></textarea><input style="width:300px;height:30px; margin-left:100px;" type="hidden" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'"><input style="width:300px;height:30px; margin-left:100px;" type="text" name="urls[]" value=""></td><td style="width:70px"><a class="btn btn-danger btn-xs" href="javascript:remove_file('+file_id+');">删除</a></td></tr></table></li>';
    });
    var dialog = top.dialog.get(window);
    dialog.close(str).remove();
    return false;
}
function callback_images6(filename,htmlid,is_thumb,htmlname)
{
    var file_array = filename.split('|');
    var str = '';
    $.each( file_array, function(i, n) {
        var temp = n.split(',');
        var file_url = temp[0];
        // alert(file_url);
        var file_alt = temp[1];
        var file_id = temp[2];

        // alert(file_id);
        str += '<li id="file_node_'+file_id+'"><table class="table table-striped table-advance table-hover"><tr><td style="width:100px;margin-right:18px"> <img src="http://m.uzhuang.com/image/original/'+file_url+'" alt="'+file_alt+'" onclick="img_viewss(this.src);" ></td><td style="width:120px"> <input style="width:300px;height:30px;margin-left: -90px; " type="text" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'"> <input type="text" name="urlsx[]"" value="" style="width:270px;height: 30px;margin-left: 90px;" placeholder="图片url连接(如:http://m.uzhuang.com)"><input type="text" name="bian[]"" value="" style="width:70px;height: 30px;margin-left: 90px;" placeholder="图片位置ID"></td><td style="width:70px"><a class="btn btn-danger btn-xs" href="javascript:remove_file('+file_id+');" style="margin-left:-150px;">删除</a></td></tr></table></li>';
    });
    var dialog = top.dialog.get(window);
    dialog.close(str).remove();
    return false;
}
function callback_images7(filename,htmlid,is_thumb,htmlname)
{
    var file_array = filename.split('|');
    var str = '';
    $.each( file_array, function(i, n) {
        var temp = n.split(',');
        var file_url = temp[0];
        // alert(file_url);
        var file_alt = temp[1];
        var file_id = temp[2];
   
        str += '<li id="file_node_'+file_id+'"><table class="table table-striped table-advance table-hover"><tr><td style="width:100px;margin-right:18px"> <img src="http://m.uzhuang.com/image/original/'+file_url+'" alt="'+file_alt+'" onclick="img_viewss(this.src);"style="    width:80px;margin-left: -35px;"></td><td style="width:120px"> <input style="width:300px;height:30px; margin-left:-90px;" type="text" name="'+htmlname+'['+file_id+'][url]" value="'+file_url+'"><input type="text" name="urlsh[]"" value="" style="width:270px;height: 30px;margin-left: 90px;" placeholder="图片url连接(如:http://m.uzhuang.com)"><input type="text" name="bians[]"" value="" style="width:70px;height: 30px;margin-left: 90px;" placeholder="图片位置ID"></td><td style="width:70px"><a class="btn btn-danger btn-xs" href="javascript:remove_file('+file_id+');" style="margin-left:-150px;">删除</a></td></tr></table></li>';
    });
    var dialog = top.dialog.get(window);
    dialog.close(str).remove();
    return false;
}
