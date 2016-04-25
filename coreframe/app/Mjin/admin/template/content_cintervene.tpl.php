<?php defined('IN_WZ') or exit('No direct script access allowed');?>
<?php
include $this->template('header','core');
?>
<style>
	.seek{
		width: 300px;
		height: 50px; 
	}
    .input{
    	position: relative;
    	top: 15px;
    }
    .adsr-btn{
    	position: relative;
    	top: 15px;
    }
    .list{
    margin-left: 100px;
    }
    .HONG{
        color:  #9D9D9D ; 
        size:15px;
    }
</style>
<center>
<div class="seek">
   <form action="" class="form-inline"  method="post">
	 <input type="text" name="title" placeholder="输入装修公司名字或关键词" class="input" style="height:35px; width:240px; " value="<?php echo $GLOBALS['title']?>">
     <button type="submit" class="btn adsr-btn"><i class="icon-search"></i></button><br>
   </form>
</div>
</center>

<div class="list">
	<?php 
	   foreach($company as $com){?>
	 <strong><h4> <a href="?m=Mjin&f=content&v=add&idc=<?php echo $com['id'];?>&com=<?php echo $com['title'];?><?php echo $this->su();?>"class="HONG" ><?php  echo $com['title']."<br/>"; ?></a></h4>
</strong> 
	  <? }
	?></div>

