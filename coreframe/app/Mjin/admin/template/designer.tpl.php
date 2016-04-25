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
                    <form action="?m=Mjin&f=designer&v=listing<?php echo $this->su();?>" class="form-inline"  method="post" id="form7">
                             <div class="input-append dropdown">
                          <select name="keytypes" class="form-control" style="float:left; height:35px;">
                            <option value="2" <?php echo $keytypes=='2'?'selected':'' ?> >设计师</option>
                            <option value="3" <?php echo $keytypes=='3'?'selected':'' ?> >装修公司</option>
                            <option value="4" <?php echo $keytypes=='4'?'selected':'' ?> >城市</option>
                          </select>
                            <input type="text" name="designers" placeholder="搜索标题" class="input" style="height:35px; width:200px; " value="<?php echo $GLOBALS['designers']?>">
                            <button type="submit" class="btn adsr-btn"><i class="icon-search"></i></button>
                     </div>
                     </form>
                </header>
                   <font size="2px" style="margin-left:30px;">共<span style="color:red"><?php echo $total?></span>条记录.</font>
                <div class="panel-body" id="panel-bodys">
                <form action="?m=Mjin&f=content&v=Deletes<?php echo $this->su();?>" class="form-inline"  id="form6" method="post">
                      <center><table class="table table-striped table-advance table-hover" style='width:1500px;'>
                            <thead>
                            <tr>
                            <th class="textName"style="padding-left:20px;"><h4>序号</h4></th>
                                <th class="textName"style="padding-left:20px;"><h4>设计师</h4></th>
                                <th class="textName"style="padding-left:45px;"><h4>等级</h4>
                                <th class="textName"style="padding-left:80px;"><h4>装修公司</h4>
                                <th class="textName"style="padding-left:30px;"><h4>城市</h4>
                             <!--    <a href="?m=Mjin&f=designer&v=listing&cid=39&pai=dshang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=designer&v=listing&cid=39&pai=dxia<?php echo $this->su();?>"class="xia1" >降</a> -->
                                </th>
                                <th class="textName"style="padding-left:20px;"><h4>作品数<br/>(精品)</h4>
                                <a href="?m=Mjin&f=designer&v=listing&cid=39&pai=ashang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=designer&v=listing&cid=39&pai=axia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                <th class="textName"style="padding-left:45px;"><h4><p class="collect">浏览数</p></h4><br/> 
                                  <a href="?m=Mjin&f=designer&v=listing&cid=39&pai=lshang<?php echo $this->su();?>"class="shang1" >升</a>
                                  <a href="?m=Mjin&f=designer&v=listing&cid=39&pai=lxia<?php echo $this->su();?>"class="xia1" >降</a>
                                </th>
                                <th class="textName"style="padding-left:20px;"><h4><p class="collect">收藏数</p></h4><br/>
                                 <a href="?m=Mjin&f=designer&v=listing&cid=39&pai=sshang<?php echo $this->su();?>"class="shang1" >升</a>
                                <a href="?m=Mjin&f=designer&v=listing&cid=39&pai=sxia<?php echo $this->su();?>"class="xia1" >降</a>
                             </th>
                                <th class="textName"style="padding-left:20px;"><h4>操作</h4></th>
                             </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                              if(empty($designer)){
                                    die;
                                   }     
                             foreach($designer AS $key =>$Com) { ?>
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
                                <td style="padding-left:30px;"><h5><?php echo $Com['ranks']?></php></h5></td>
                                <td style="padding-left:30px;"><h5><?php echo $Com['companynames']?></php></h5></td>
                                <td style="padding-left:30px;"><h5><?=$Com['com']?></php></h5></td>
                                 <td style="padding-left:30px;"><h5><?php echo $Com['productionnum']?></php></h5></td>
                                 <td style="padding-left:50px;"><h5><?php echo $Com['des_browsenum']?></php></h5></td>
                                 <td style="padding-left:50px;"><h5><?php echo $Com['design_collectnum']?></php></h5></td>
                                <td> 
                              <a href="?m=Mjin&f=designer&v=compile&id=<?php echo $Com['id'];?><?php echo $this->su();?>" class="btn btn-primary btn-xs">编辑</a></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table></center>  
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
      form.attr("action","?m=Mjin&f=designer&v=listing<?php echo $this->su();?>&"+form.serialize());
      form.submit();
      return false;
  })
    
</script>
</body>
</html>