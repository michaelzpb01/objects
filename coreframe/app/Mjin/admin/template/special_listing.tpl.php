<?php defined('IN_WZ') or exit('No direct script access allowed');?>
<?php
include $this->template('header','core');
?>
<body class="body">
<style type="text/css">
    .table>tbody>tr>td, .table>thead>tr>th {
        padding: 5px 10px;
    }
    .table>thead>tr>th.tablehead {
        padding: 10px 10px;
    }
    body {
        min-height: 400px;

    }
    .collect{
      float: left;
      margin: 0;
    }
    .shang{
      display: block;
      margin-bottom: -5px;
      width: 16px;
      height: 10px;
    }
     .xia{
      height: 10px;
    }
</style>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                 <span class="dropdown addcontent">

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
             <!--        <form action="?m=Mjin&f=content&v=listing<?php echo $this->su();?>" class="form-inline"  method="post">
                             <div class="input-append dropdown">
                          <select name="keytypes" class="form-control" style="float:left; height:35px;">
                            <option value="1" <?php echo $keytypes=='1'?'selected':'' ?> >案例名称</option>
                                   <option value="3" <?php echo $keytypes=='3'?'selected':'' ?> >装修公司</option>
                            <option value="2" <?php echo $keytypes=='2'?'selected':'' ?> >设计师</option>
                     
                          </select>
                      
                            <input type="text" name="title" placeholder="搜索标题" class="input" style="height:35px; width:200px; " value="<?php echo $GLOBALS['title']?>">
                            <button type="submit" class="btn adsr-btn"><i class="icon-search"></i></button>
                     </div>
                     </form> -->
                </header>
                  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;<a href="?m=Mjin&f=special&v=add<?php echo $this->su();?>"class="btn btn-default btn-sm" >新建专题</a>
               <!--    <a href="javascript:makedo('?m=Mjin&f=content&v=Sync<?php echo $this->su();?>', '确定要进行装修公司与设计同步么')" class="btn btn-default btn-sm">同步</a><br/> -->
                   <font size="2px" style="margin-left:30px;">共<span style="color:red"><?php echo $total?></span>条记录.</font>
                <div class="panel-body" id="panel-bodys">
                <form action="?m=Mjin&f=special&v=Deletes<?php echo $this->su();?>" class="form-inline"  id="form6" method="post">
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                            <th class="textName"style="padding-left:20px;"><h4>序号</h4></th>
                                <th class="textName"style="padding-left:20px;"><h4>专题名称</h4></th>
                                <th class="textName"style="padding-left:50px;"><h4>更新时间</h4>
                                <a href="?m=Mjin&f=special&v=listing&cid=39&pai=tshang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=special&v=listing&cid=39&pai=txia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                <th class="textName"style="padding-left:150px;"><h4>链接</h4></th>
                                <th class="textName"style="padding-left:20px;"><h4>干预值</h4>
                                  <a href="?m=Mjin&f=special&v=listing&cid=39&pai=gshang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=special&v=listing&cid=39&pai=gxia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                <th class="textName"style="padding-left:25px;">
                                  <select name="key" class="form-control" style="float:left; height:35px;">
                                    <option value="" >状态</option>
                                    <option value="1" <?php echo $keytypess=='1'?'selected':'' ?> >上架</option>
                                    <option value="2" <?php echo $keytypess=='2'?'selected':'' ?> >下架</option>
                                    <option value="3" <?php echo $keytypess=='3'?'selected':'' ?> >未发布</option>
                                </select></th>
                                <th class="textName"style="padding-left:20px;"><h4>操作</h4></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($special AS $key =>$Mjin) { ?>
                                <tr title="<?php echo $models[$r['modelid']]['name'];?>">
                                  <tr id="u_<?php echo $Mjin['id'];?>">
                                <td style="padding-left:40px;padding-right:20px;">
                              <input type="checkbox" name="ids[]" class="zids"value="<?php echo $Mjin['id']?>" echo 'disabled'?>
                               &nbsp;<?php 
                               $k=$pagess*10;
                               if($k==0){
                                  echo $key+1;
                               }else{
                                  echo $k+$key+1-10;
                               }
                              ?>
                               </td>
                               <td style="padding-left:30px;">
                               <a href="?m=Mjin&f=special&v=Preview&id=<?php echo $Mjin['id']?><?php echo $this->su();?>" target="_blank">
                                      <?php echo $Mjin['title']?>
                               </a>
                               </td>
                               <td style="padding-left:30px;"><?php echo $Mjin['updatetime'];?></td>
                                <td style="padding-left:30px;"><?php 
                               $str=R;$newstr = substr($str,0,strlen($str)-5);
                               echo $newstr.'/mobile-topic.html?topic_id='.$Mjin['id'];?></td>
                               <td style="padding-left:30px;"> <button style="" onclick="openiframe('?m=Mjin&f=special&v=intervene&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>','testaa','修改干预值',350,100);" type="button"><?echo $Mjin['intervene']?></button></td>
                               <td style="padding-left:30px;"><?php 
                               if($Mjin['status']==1){echo "上架";}elseif($Mjin['status']==2){echo "下架";
                               }elseif($Mjin['status']==3){echo "未发布";}?></td>
                              <td> 
                              <a href="?m=Mjin&f=special&v=edit&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>" class="btn btn-primary btn-xs">编辑</a>
                              <?php if($Mjin['status']==1){?><a href="javascript:makedo('?m=Mjin&f=special&v=UnShelve&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>', '确认下架该记录？')" class="btn btn-default btn-xs">下架</a><?}elseif($Mjin['status']==2){?><a href="javascript:makedo(' ?m=Mjin&f=special&v=Putaway&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>', '确认上架该记录？')" class="btn btn-default btn-xs">上架</a><?}elseif($Mjin['status']==3){?><a href="javascript:makedo('?m=Mjin&f=special&v=Publish&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>', '确认发布该记录？')" class="btn btn-default btn-xs">发布</a><?}?>
                              <a href="javascript:makedo('?m=Mjin&f=special&v=delete&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>', '确认删除该记录？')" class="btn btn-danger btn-xs">删除</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>


                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="pull-left">
                                        <button type="button" onClick="checkall()" id="selectsAll" name="submit2" class="btn btn-default btn-sm">全选/反选</button>
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
    // alert(where)
    location.href="?m=Mjin&f=special&v=listing&shang="+where+"<?php echo $this->su();?>";
  })
   $("#selectsAll").click(function(){
    $(":checkbox[class=zids]").each(function(){
      if (!$(this).attr('checked')) {
        $(this).attr('checked',true);
      }else{
        $(this).removeAttr('checked');
      }
    })
  })

  $(":checkbox").click(function(){
    var isch = $(this).attr("checked");
    if (!isch) {
      $(this).attr('checked',true);
    }else{
      $(this).removeAttr('checked');
    }
  })

  var isCheck = 0;
  $("#deletes").click(function(){
    $(":checkbox").each(function(){
      var b=$(this).attr("checked");
      if (b) {
        isCheck=1;
      }
    });
    if (isCheck==0) {
      alert('请选择要删除的数据');
      return false;
    };
    if (!confirm('确定要删除选中记录？')) {
      return false;
    };
  })
    $("#bao1").click(function(){
    $("#form6").attr("action","?m=Mjin&f=special&v=listing<?php echo $this->su();?>")
  })
</script>
</body>
</html>