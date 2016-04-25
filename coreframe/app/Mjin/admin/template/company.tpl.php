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
                    <form action="?m=Mjin&f=company&v=listing<?php echo $this->su();?>" class="form-inline"  method="post" id="form7">
                             <div class="input-append dropdown">
                          <select name="com" class="form-control" style="float:left; height:35px;">
                            <option value="1" <?php echo $coms=='1'?'selected':'' ?> >装修公司</option>
                            <option value="2" <?php echo $coms=='2'?'selected':'' ?> >城市</option>
                          </select> 
                            <input type="text" name="title" placeholder="请输入........" class="input" style="height:35px; width:200px; " value="<?php echo $GLOBALS['title']?>">
                            <button type="submit" class="btn adsr-btn"><i class="icon-search"></i></button>
                     </div>
                     </form>
                </header>
            <!--       &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;<a href="?m=Mjin&f=content&v=add&cid=39<?php echo $this->su();?>"class="btn btn-default btn-sm" >创建案例</a><br/> -->
                   <font size="2px" style="margin-left:30px;">共<span style="color:red"><?php echo $total?></span>条记录.</font>
                <div class="panel-body" id="panel-bodys">
                <form action="?m=Mjin&f=content&v=Deletes<?php echo $this->su();?>" class="form-inline"  id="form6" method="post">
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                            <th class="textName"style="padding-left:20px;"><h4>序号</h4></th>
                                <th class="textName"style="padding-left:80px;"><h4>公司名称</h4></th>
                                <th class="textName"style="padding-left:35px;"><h4>城市</h4></th>
                                <th class="textName"style="padding-left:20px;"><h4>设计师数<br/>(精品)</h4>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=dshang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=dxia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                <th class="textName"style="padding-left:20px;"><h4>案例数<br/>(精品)</h4>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=ashang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=axia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                <th class="textName"style="padding-left:45px;"><h4><p class="collect">浏览数</p></h4><br/> 
                                  <a href="?m=Mjin&f=company&v=listing&cid=39&pai=lshang<?php echo $this->su();?>"class="shang1" >升</a>
                                  <a href="?m=Mjin&f=company&v=listing&cid=39&pai=lxia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                <th class="textName"style="padding-left:30px;"><h4><p class="collect">收藏数</p></h4><br/>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=sshang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=sxia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                <th class="textName"style="padding-left:20px;"><h4><p class="collect">服务水平</p></h4><br/>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=fshang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=fxia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                 <th class="textName"style="padding-left:20px;"><h4><p class="collect">施工质量</p></h4><br/>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=sishang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=sixia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                 <th class="textName"style="padding-left:20px;"><h4><p class="collect">服务态度</p></h4><br/>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=tshang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=txia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                 <th class="textName"style="padding-left:20px;"><h4><p class="collect">综合评分</p></h4><br/>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=zshang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=zxia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                               
                                <th class="textName"style="padding-left:20px;"><h4>操作</h4></th>
                                      </th>
                                <th class="textName"style="padding-left:30px;"><h4><p class="collect">干预值</p></h4><br/>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=gyshang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=company&v=listing&cid=39&pai=gyxia<?php echo $this->su();?>"class="xia1" >降</a> 
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            
                               <?php 
                                   if(empty($company)){
                                    die;
                                   }     

                               foreach($company AS $key =>$Com) { ?>
                                <tr title="<?php echo $models[$r['modelid']]['name'];?>">
                                  <tr id="u_<?php echo $Com['id'];?>">
                                <td style="padding-left:40px;padding-right:20px;">
                               &nbsp;<?php 
                               $k=$pagess*10;
                               if($k==0){
                                  echo $key+1;
                               }else{
                                  echo $k+$key+1-10;
                               }
                              ?>
                               </td>
                               <td style="padding-left:30px;"><h5><?php echo $Com['title']?></php></h5></td>
                               <td style="padding-left:30px;"><h5><?php echo $Com['com']?></php></h5></td>
                                <td style="padding-left:30px;"><h5><?php echo $Com['designnum']?></php></h5></td>
                                 <td style="padding-left:30px;"><h5><?php echo $Com['photonum']?></php></h5></td>
                                 <td style="padding-left:50px;"><h5><?php echo $Com['com_browsenum']?></php></h5></td>
                                 <td style="padding-left:50px;"><h5><?php echo $Com['com_collectnum']?></php></h5></td>
                                 <td style="padding-left:30px;"><h5><?php echo $Com['avg_designs']?></php></h5></td>
                                 <td style="padding-left:30px;"><h5><?php echo $Com['avg_qualitys']?></php></h5></td>
                                 <td style="padding-left:30px;"><h5><?php echo $Com['avg_services']?></php></h5></td>
                                 <td style="padding-left:30px;"><h5><?php echo $Com['avg_totals']?></php></h5></td>
                                <td> 
                              <a href="?m=Mjin&f=company&v=compile&id=<?php echo $Com['id'];?><?php echo $this->su();?>" class="btn btn-primary btn-xs">编辑</a></td>
                               <td style="padding-left:30px;"> <button style="" onclick="openiframe('?m=Mjin&f=company&v=intervene&id=<?php echo $Com['id'];?><?php echo $this->su();?>','testaa','修改干预值',350,100);" type="button"><?echo $Com['intervene']?></button></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
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
    location.href="?m=Mjin&f=content&v=listing&shang="+where+"<?php echo $this->su();?>";
  })

  $("#deletes").click(function(){
    if (!confirm('确定要删除选中记录？')) {
      return false;
    };
  })
    $("#bao1").click(function(){
    $("#form6").attr("action","?m=Mjin&f=content&v=listing<?php echo $this->su();?>")
  })
  $("#form7").find("button").click(function(e){
      var form = $(e.target).parents("form");
      form.attr("action","?m=Mjin&f=company&v=listing<?php echo $this->su();?>&"+form.serialize());
      form.submit();
      return false;
  })
    
</script>
</body>
</html>