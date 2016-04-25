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
   .ni{
   }
</style>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class="company"> 
                	<h3>装修公司LOGO： <img src="http://image1.uzhuang.com/image/big_square/<?php echo $company['thumb']?>" alt="LOGO" style="height:60px; width:120px;"></h3>
                    <h3>装修公司名称： <?php echo$company['title'] ?></h3>
                    <form action="?m=Mjin&f=company&v=compile<?php echo $this->su();?>" class="form-inline"  method="post">
                    <font size="5px"><span style="color:red"><!-- * --></span>服务标签:&nbsp;&nbsp;<button style="" onclick="openiframe('?m=Mjin&f=company&v=interveneb&id=<?php echo $company['id'];?><?php echo $this->su();?>','testaa','服务标签修改',350,100);" class="save-bt btn btn-info"type="button"><?php echo $company['tese']?></button>&nbsp;</font>
                    <input name="teseid" type="hidden" value="<?php echo $company['id']?>">
                	</form>
                	<font size="5px">公司简介：</font><div class="content"> <?php echo $company_data['content'];?></div>
                	 <h3>公司地址：&nbsp;&nbsp;<?php echo $company['address']?></h3><br/>
                	 <h4>服务水平：<?php if($companyo['avg_design']=="0.0"){echo '5.0'; }else{ echo $companyo['avg_service'];}?>分&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;施工质量：<?php if($companyo['avg_quality']=="0.0"){echo '5.0'; }else{ echo $companyo['avg_quality'];}?>分&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;服务态度：<?php if($companyo['avg_service']=="0.0"){echo '5.0'; }else{ echo $companyo['avg_service'];}?>分&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;综合评分：<?php if($companyo['avg_total']=="0.0"){echo '5.0'; }else{ echo $companyo['avg_total'];}?>分</h4><br/>
                     <h4>设计师数（精品）：<?php echo $company['designnum']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;案例数（精品）:<?php echo $company['photonum']?></h4><br/>
                     <form action="?m=Mjin&f=company&v=compile<?php echo $this->su();?>" class="form-inline"  method="post">
                        <input name="teseid" type="hidden" value="<?php echo $company['id']?>">
                     <h5>浏览数：<?php echo $company['com_browsenum']?>&nbsp;&nbsp;&nbsp;&nbsp;<input name="browsenum" value="<?php echo $company['browsenums']?>"style="width:30px;"/></h5><br/>
                     <h5>收藏数：<?php echo $company['com_collectnum']?>&nbsp;&nbsp;&nbsp;&nbsp;<input name="collectnum" value="<?php echo $company['collectnums']?>"style="width:30px;"/></h5><br/>
                     <input name="submit" type="submit" class="save-bt btn btn-info" id="baoG" value="保存"><br/>
                     <br/>
                     </form>
                </div>
            </section>
         </div>
     </div>：
 </section>
 <script>
    $("#tese").keyup(function(){
  var $this = $(this);
  var title = $this.val();
  var titleLen = title.length;
  if (titleLen>=10) {
    title = title.substr(0,10);
    $this.val(title);
    alert('服务标签最多输入10个字符')
    return false;
  }
 })
 </script>
