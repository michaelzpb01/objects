<?php defined('IN_WZ') or exit('No direct script access allowed');?>
<?php
include $this->template('header','core');
?>
<body class="body pxgridsbody">
<style type="text/css">
    .tablewarnings{display: none;}
    #materialtotal{
    }
    .special{

    }
</style>
<link href="<?php echo R;?>js/colorpicker/style.css" rel="stylesheet">
<link href="<?php echo R;?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo R;?>js/colorpicker/color.js"></script>
<style type="text/css">
  #position {
  position: absolute;
  top: 40%;
  left: 35%;
  width: 300px;
  height: 300px;
  margin: -20px 0 0 -75px;
  padding: 0 10px;
  line-height: 2.4;
  float: left;
  margin:0px;
  padding: 0px;
}
.special{
   margin-left: 50px;
}
body, html {
  overflow: auto !important;
}
</style>
<section class="wrapper">
    <div id="position" style="display:none;">
      <img id="close" src="" alt="">
    </div>
    <div class="row">
     <div class="col-lg-12">
        <section class="panel">
           <div class="special">

            <form action="?m=Mjin&f=special&v=edit&id=<?php echo $special['id']?><?php echo $this->su();?>" class="form-inline"  method="post">
            <input type="hidden" name="haha" id="haha">
             <font size="5px">
             专题名称：</font><input name="title" id="title" value="<?=$special['title']?>"  placeholder="输入20以内的字符"  style="width:200px;height:30px; " ><br/><br/>
              <font size="5px"> <span style="float:left">封面图：&nbsp;</span></font><input type="text" value="<?php echo $special['cover']?>" ondblclick="img_view(this.value);" class="form-control" id="attachment_test" name="attachment_test" size="100" style="width:450px;margin-right:5px;position:relative;">
            <span class="input-group-btn">
            <button type="button" style="margin-left:550px;position:relative; margin-top:-30px" class="btn btn-white" onclick="openiframe('/index.php?m=attachment&amp;f=index&amp;v=upload_dialog&amp;callback=callback_thumb_dialog&amp;htmlid=attachment_test&amp;limit=1&amp;htmlname=setting%5Battachment_test%5D&amp;ext=png%7Cjpg%7Cgif%7Cdoc%7Cdocx&amp;token=3d9292f4c141b7f9c4f3a37f8af2e607&amp;_menuid=29&amp;_submenuid=67','attachment_test','loading...',810,400,1)">上传文件</button><br/><br/>
            </span>
                  <font size="5px"><span>导语</span>:&nbsp;&nbsp;&nbsp;</font>
             <span> <textarea name="form[dao]" style="width:500px; height:100px; margin-top:30px;"><?=$special['dao']?></textarea></span>
              <table  class = "table table-striped table-advance table-hover"  id="myTable1"  width="500px;"   cellpadding="4"  cellspacing="1"  >  
            <tr>
                   <td><h4><span style="margin-left:45px">图片</span><span style="margin-left:405px">图片描述</span><span style="margin-left:405px">图片链接</span></h4></td>
            </tr>
             <?
              $aRowid = array();
              $case = explode('|', $special['atlas']);
              $urls = explode('|', $special['url']);
              $alt = explode('|', $special['alt']);
              foreach($case as $key => $value){
                  $aRowid[] = array(
                    'url' => $value,
                    'alt' => $alt[$key],
                    'urls' => $urls[$key],
                
                  );
              }
            foreach ($aRowid as $key => $v) {
              if($v['url']!=""){?>
            <tr><td>
           <span  style="margin-left:40px"><img src="<?php echo getMImgShow($v['url'],'big_square')?>"  onclick="img_viewss(this.src);" style="width:80px; margin-top:-10px; margin-left: -20px"/></span> 
           <span style="margin-left:165px"><textarea name="form[alt][]" style="width:500px;  margin-top:10px;"><?php echo $v['alt']?></textarea></span>  
            <span><input type="hidden" name='url[]' value="<?echo $v['url']?>" >
                 <input type="text" name='urls[]' value="<?echo $v['urls']?>" style="margin-left:95px;width:300px; margin-top:-30px; height:40px;">
            </span>  <td> <a class="btn btn-danger btn-xs removeTr"  style="margin-left:-165px;">移除</a></td></tr>
                <?} }?>
            <tr>
                  <td> 
                  <div class="attaclist"><div id="case"><ul id="case_ul"></ul></div><span class="input-group-btn">
           <button type="button" class="btn btn-white" onclick="openiframe('/index.php?m=attachment&amp;f=index&amp;v=upload_dialog&amp;callback=callback_images5&amp;htmlid=case&amp;limit=20&amp;width=1&amp;htmlname=form%5Bcase%5D&amp;ext=jpg%7Cpng%7Cgif%7Cbmp&amp;token=34f98e4c74f75c6701341b703f09917b&amp;_menuid=5002','case','loading...',810,400,20)"> 案例图：上传文件</button></span><span style="position: relative;top: -30px;left:150px;color:red;">（案例图尺寸：宽度640px，高度≤960px）</span></div></td>
              </tr>   
         </table >
             <input name="submit" type="submit" class="save-bt btn btn-info" id="baoG" value="保存" style="margin-left:1000px;"><br/>
            </form>
            </div>
        </section>
     </div>
    </div>
</section>
<script src="<?php echo R;?>js/bootstrap.min.js"></script>
<script src="<?php echo R;?>js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/pxgrids-scripts.js"></script>
<script src="<?php echo R;?>js/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<link href="<?php echo R;?>css/style.css" rel="stylesheet">
<script src="<?php echo R; ?>js/html5upload/extension.js"></script>
<link href="{R}css/validform.css" rel="stylesheet" />
<script src="{R}js/validform.min.js"></script>
<script type="text/javascript" src="{R}js/validform.min.js"></script>
<script type="text/javascript">
  function  get_Element(the_ele,the_tag){  
    the_tag  =  the_tag.toLowerCase();  
    if(the_ele.tagName.toLowerCase()==the_tag)  
      return  the_ele;  
    while(the_ele=the_ele.offsetParent){  
      if(the_ele.tagName.toLowerCase()==the_tag)  
        return  the_ele;  
    }  
    return(null);  
  }  

  $("#myTable1>tbody>tr:eq(1)").attr("id","par");
  var insber=$("#insber");
  $("#addLine").click(function(){
    var count = $("#myTable1 tr").length;
    count--;
    var newLine = $("#par").clone();
    newLine.attr('id','par'+count)
    newLine.find("td:eq(0)").html('<div align="center">'+count+'</div>');
    newLine.find("td:eq(1) img").attr("id","attachment_thumb_test"+(count-1)+"_thumb");
    newLine.find("td:eq(1)>input").attr("id","attachment_thumb_test"+(count-1));
    newLine.find('td:eq(1)>img').attr("src","http://m.uzhuang.com/res/images/upload-thumb.png");
    newLine.find('td:eq(1)>:text').val('');
    newLine.find('td:eq(2) textarea').val('');
    var ofirm = "openiframe('/index.php?m=attachment&f=index&v=upload_dialog&htmlid=attachment_thumb_test"+(count-1)+"  limit=1&is_thumb=1&htmlname=setting[recphoto][]&ext=png%7Cjpg%7Cgif%7Cdoc%7Cdocx&token=3d9292f4c141b7f9c4f3a37f8af2e607&_menuid=5032','attachment_thumb_test"+(count-1)+"' ,'loading...',810,400,1)";
    newLine.find("td:eq(1)>span :button").attr("onclick",ofirm);
    insber.before(newLine);
  })
 $("#baoG,#baoF").click(function(){
  var $this = $("#title");
  var title = $this.val();
  var titleLen = title.length;
  if (titleLen>20) {
    alert('活动名称最多输入20个字符')
    return false;
  }
 })
  $(document).on('click','.removeTr',function(){
    $this = $(this);
    var len = $("#myTable1>tbody tr").length;
    if (len<=2) {
      return false;
    }
    $this.closest('tr').remove();
  })

</script>
