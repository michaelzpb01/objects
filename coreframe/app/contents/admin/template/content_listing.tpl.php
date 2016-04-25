<?php defined('IN_WZ') or exit('No direct script access allowed');?>
<?php
include $this->template('header','core');
?>
<body class="body">
<style type="text/css">
.content{
    width: 500px;
    height: 60px;
    overflow: hidden;
}
#ai{
    width: 500px;
    height: 50px;
    overflow: hidden;
}
</style>
    <script src="<?php echo R; ?>js/html5upload/extension.js"></script>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
          
                 <div>
                   <span style="font-size:30px;">轮播图</span><br/>
                   <a href="?m=contents&f=contents&v=imgs<?php echo $this->su();?>" class="btn btn-primary btn-xs">轮播图维护</a>
                   <div style="">
                   <?
                      $imgs = explode('|',$imgs['imgs']);
                      foreach ($imgs as $key => $value) {?>
                     <img src="<?php echo getMImgShow($value,'original')?>" alt="" style="width:100px; height:50px;"  onclick="img_viewss(this.src)">
                      <?}
                   ?>
                   </div>
                    <span style="font-size:30px;">今日观点</span><br/>
                    <div id="ai">
                      <?echo $result[0]['content']?>
                    </div>
                    <span style="font-size:30px;">最新发布</span><br/>
                     <a href="?m=contents&f=contents&v=add<?php echo $this->su();?>" class="btn btn-primary btn-xs">内容发布</a>
                        <?foreach ($result as $key => $va) {?>
                    <div>
                      <table>
                        <tr>
                          <td>
                          <img src="<?php echo getMImgShow($va['img'],'original')?>" alt="" style="width:150px; height:100px;">
                          </td>
                          <td>
                           <h3><?=$va['title']?></h3><h4 class="content"><?=$va['content']?>
                          </td>
                          <td>
                            <a href="?m=contents&f=contents&v=edit&id=<?=$va['id']?><?php echo $this->su();?>"  class="btn btn-primary btn-xs">编辑</a>
                            <a href="javascript:makedo('?m=contents&f=contents&v=de&id=<?php echo $va['id'];?><?php echo $this->su();?>', '确认删除该记录？')" class="btn btn-danger btn-xs">删除</a>
                          </td>
                        </tr>

                      </table>
                    </div><br/>
                       <?    }?>
               </div>
 
                  
            
            </section>
        </div>
</section>
<script src="<?php echo R;?>js/bootstrap.min.js"></script>
<script src="<?php echo R;?>js/hover-dropdown.js"></script>
<script src="<?php echo R;?>js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/pxgrids-scripts.js"></script>
<script type="text/javascript">
 
</script>
</body>
</html>