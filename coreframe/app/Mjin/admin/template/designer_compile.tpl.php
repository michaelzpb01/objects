<?php defined('IN_WZ') or exit('No direct script access allowed');?>
<?php
include $this->template('header','core');
?>      
<style type="text/css">
   .company{
    margin-left:40px;
   }
   .content{
    margin-left:140px; 
    margin-top:-30px; 
    width: 700px;
   }
   #baoG{
      margin-left:800px; 
   }
   .xiaoz{
    position:relative;
    left:300px;
    margin-top:-50px; 
   }
</style>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class="company">
      <!--    <img class="bigImg" src="" alt="效果图" style="width:320px;height:200px"> -->
                 <form action="?m=Mjin&f=designer&v=compile<?php echo $this->su();?>" class="form-inline"  method="post">
                   <input name="derid" type="hidden" value="<?php echo $designer['id']?>">
                   <?php if($designer['thumb1']==""){?>
                  <h3>设计师头像:   <img width="120" height="120" onclick="img_view(this.src); " class="attachment_thumb" src="http://image1.uzhuang.com/image/big_square/<?php echo $designer['thumb']?>" id="attachment_thumb_test0_thumb">
                  <?}else{?>
                  <h3>设计师头像:  <img width="120" height="60" onclick="img_view(this.src); " class="attachment_thumb" src="
                  <?php echo getMImgShow($designer['thumb1'],'big_square')?>
                  " id="attachment_thumb_test0_thumb">
                 <? }?>
                  <input type="hidden" size="100" name="setting[recphoto]" id="attachment_thumb_test0" class="form-control" onclick="img_view('?m=core&amp;f=image_privew&amp;imgurl='+this.value);" value=""><span class="input-group-btn" ><button onclick="openiframe('/index.php?m=attachment&amp;f=index&amp;v=upload_dialog&amp;htmlid=attachment_thumb_test0  limit=1&amp;is_thumb=1&amp;htmlname=setting[recphoto][]&amp;ext=png%7Cjpg%7Cgif%7Cdoc%7Cdocx&amp;token=3d9292f4c141b7f9c4f3a37f8af2e607&amp;_menuid=5032','attachment_thumb_test0' ,'loading...',810,400,1)" class="btn xiaoz btn-white" type="button">上传</button></span></h3>
                    <h3>设计师姓名 ：<?php echo $designer['title'] ?></h3>
                    <h3>所属装修公司 ：<?php echo $companyid['title'] ?></h3>
                  <font size="5px">设计师等级：</font><div class="content">
                            <select name="keytypes" class="form-control" style="float:left; height:35px; width:150px;">
                           <?foreach ($der as $key => $dero) {?>
                           <option value=<?=$dero['id']?> <?php if($dero['name']==$designer['ranks'])echo 'selected';?>><?=$dero['name']?></option>
                          <?}?>
                            </select></div><br/><br/>
                   <h3>设计师简介：&nbsp;&nbsp;</h3><div class="content"><textarea name="dercontent" cols="45" rows="5"><?php echo strip_tags($designer_data['content'])?></textarea><font size="4px"><div><span style="color:red; margin-left:185px;"><font size="2px">*建议200字以内</font></span></div></font></div><br/>
                   <h4>作品数(精品)：<?php echo $designer['productionnum']?></h4><br/>
                     <h5>浏览数：<?php echo $designer['des_browsenum']?>&nbsp;&nbsp;&nbsp;&nbsp;<input name="browsenum" value="<?php echo $designer['browsenums']?>"style="width:30px;"/></h5><br/>
                     <h5>收藏数：<?php echo $designer['design_collectnum']?>&nbsp;&nbsp;&nbsp;&nbsp;<input name="collectnum" value="<?php echo $designer['collectnums']?>"style="width:30px;"/></h5><br/>
                     <input name="submit" type="submit" class="save-bt btn btn-info" id="baoG" value="保存"><br/>
                     <br/>
                     </form>
                </div>
            </section>
         </div>
     </div>
 </section>
 <script type="text/javascript">
 $(".bigImg").attr("src",$(".attachment_thumb").attr("src"))
 </script>