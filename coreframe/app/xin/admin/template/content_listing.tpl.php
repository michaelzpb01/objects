<?php defined('IN_WZ') or exit('No direct script access allowed');?>
<?php
include $this->template('header','core');
?>
<body class="body">
<style type="text/css">
</style>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                 <span class="dropdown addcontent">
                  <script src="<?php echo R; ?>js/html5upload/extension.js"></script>
                 </span>
                 <span class="dropdown examine">
                 </span>
                    <form class="pull-right position" action="" method="get">
                        <input name="m" value="content" type="hidden">
                        <input name="f" value="content" type="hidden">
                        <input name="v" value="listing" type="hidden">
                        <input name="type" value="<?php echo $type;?>" type="hidden">
                        <input name="_su" value="<?php echo $GLOBALS['_su'];?>" type="hidden">
                        <input name="status" value="<?php echo $status;?>" type="hidden">
                        <input name="cid" value="<?php echo $cid;?>" type="hidden">
                    </form>
                    <form action="?m=xin&f=content&v=listing<?php echo $this->su();?>" class="form-inline"  method="post">
                             <div class="input-append dropdown">
                      
                            <font size="4px">活动名称:</font> <input type="text" name="title" placeholder="搜索标题" class="input" style="height:35px; width:200px; " value="<?php echo $GLOBALS['title']?>">
                            <button type="submit" class="btn adsr-btn"><i class="icon-search"></i></button>
                     </div>
                     </form>
                </header>
                  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;<a href="?m=xin&f=content&v=adds&cid=39<?php echo $this->su();?>"class="btn btn-default btn-sm" >添加活动</a>
                  <br/>

                   <font size="2px" style="margin-left:30px;">共<span style="color:red"><?php echo $total?></span>条记录.</font>
                <div class="panel-body" id="panel-bodys">
                <form action="?m=xin&f=content&v=Deletes<?php echo $this->su();?>" class="form-inline"  id="form6" method="post">
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                            <th class="textName"style="padding-left:20px;"><h4>序号</h4></th>
                                <th class="textName"style="padding-left:20px;"><h4>活动名称</h4></th>
                                <th class="textName"style="padding-left:70px;"><h4>时间</h4></th>
                                <th class="textName"style="padding-left:140px;"><h4>链接</h4></th>
                                <th class="textName"style="padding-left:20px;"><h4>状态</h4></h4>
                                <th class="textName"style="padding-left:50px;"><h4>操作</h4></th>
                            </tr>
                          
                           
                            </thead>
                            <tbody>
                            <?php foreach($result AS $key =>$Mjin) { ?>
                                <tr title="<?php echo $models[$r['modelid']]['name'];?>">
                                  <tr id="u_<?php echo $Mjin['id'];?>">
                                <td style="padding-left:40px;padding-right:20px;">
                              <input type="checkbox" name="ids[]" value="<?php echo $Mjin['id']?>" echo 'disabled'?>
                               &nbsp;<?php 
                               $k=$pagess*10;
                               if($k==0){
                                  echo $key+1;
                               }else{
                                  echo $k+$key+1-10;
                               }
                                 echo '&nbsp;&nbsp;&nbsp;';
                                 if($Mjin['type']=='2'){echo '<span style="color:red">H5</span>';}
                              ?>
                               </td>
                               <td style="padding-left:30px;">
        <!--<a href="<?php if($Mjin['status']!=4) {echo $Mjin['url'];}else{ echo '?m=xin&f=content&v=Previews&id=<?php echo $Mjin["id"];?>'.$this->su();};?>" target="_blank"> -->
                             <? $str=R;$newstr = substr($str,0,strlen($str)-5);?>
                               <a href="mobile-template.html?temp=<?echo $Mjin['id']?>" target="_blank">
                                      <?php echo $Mjin['title'];
                                      
                                      ?>
                               </a>
                               </td>
                               <td style="padding-left:30px;"><?php echo $Mjin['addtime']?></td>
                               <td style="padding-left:30px;"><?php 
                               $str=R;$newstr = substr($str,0,strlen($str)-5);$url='uploadfile/wei/'.$Mjin['id'].".png";
                               echo $newstr.'/mobile-template.html?temp='.$Mjin['id'];?><img class="xiaotu" src="<?echo $url?>" style="width:30px;" onClick="img_wei(this.src);"><a href="?m=xin&f=content&v=doDownload&filename=<?echo $url?><?php echo $this->su();?>">下载二维码</a></td>
                               <td style="padding-left:30px;"><?php 
                               if($Mjin['status']==1){echo "上线";}elseif($Mjin['status']==2){echo "下线";
                               }?></td>
                              <td> 
                              <a href="?m=xin&f=content&v=edit&id=<?php echo $Mjin['id'];?>&type=<?php echo $GLOBALS['type'];?>&cid=<?php echo $Mjin['cid'].$this->su();?>" class="btn btn-primary btn-xs">编辑</a>
                              <?php if($Mjin['status']==1){?><a href="javascript:makedo('?m=xin&f=content&v=UnShelve&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>', '确认下线该记录？')" class="btn btn-default btn-xs">下线</a><?}elseif($Mjin['status']==2){?><a href="javascript:makedo(' ?m=xin&f=content&v=Putaway&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>', '确认上线该记录？')" class="btn btn-default btn-xs">上线</a><?}elseif($Mjin['status']==3){?><a href="javascript:makedo('?m=xin&f=content&v=Publish&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>', '确认发布该记录？')" class="btn btn-default btn-xs">发布</a><?}?>
                              <a href="javascript:makedo('?m=xin&f=content&v=delete&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>', '确认删除该记录？')" class="btn btn-danger btn-xs">删除</a>
                                    </td>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="pull-left">
                                        <button type="button" onClick="checkall()" name="submit2" class="btn btn-default btn-sm">全选/反选</button>
                                        <input type="submit" class="btn btn-default btn-sm" id="deletes"value="批量删除">
                                    </div>
                                    <div class="pull-right">
                                        <ul class="pagination pagination-sm mr0">
                                            <?php echo $pages;?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </section>
        </div>
</section>
<script src="<?php echo R;?>js/bootstrap.min.js"></script>
<script src="<?php echo R;?>js/hover-dropdown.js"></script>
<script src="<?php echo R;?>js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/pxgrids-scripts.js"></script>
<script type="text/javascript">
  $("select[name=key]").change(function(){
    var where = $(this).val();
    location.href="?m=xin&f=content&v=listing&shang="+where+"<?php echo $this->su();?>";
  })

  $("#deletes").click(function(){
    if (!confirm('确定要删除选中记录？')) {
      return false;
    };
  })
    $("#bao1").click(function(){
    $("#form6").attr("action","?m=xin&f=content&v=listing<?php echo $this->su();?>")
  });
</script>
</body>
</html>