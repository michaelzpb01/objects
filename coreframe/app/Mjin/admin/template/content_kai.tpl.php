<center>
	<form name="myform" class="form-horizontal tasi-form" id="form6" action="?m=Mjin&f=content&v=kais&id=<?php echo $result['id']?><?php echo $this->su();?>" method="post">
      <input name="kai" id="zhi" value=""  placeholder="输入开通城市（例如北京市）"style='width:200px;height:30px;'>
     <input name="submit" type="submit" class="save-bt btn btn-info" id="bao" value="确认"> &nbsp;&nbsp;&nbsp;
</form>
<h4>已开通城市</h4>
    </center>  

          <?foreach($kai as $k){?>
       		<?echo $k['name'];?>
       	   <?}?>
   
  


