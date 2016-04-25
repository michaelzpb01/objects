<form name="myform" class="form-horizontal tasi-form" id="form6" action="?m=Mjin&f=content&v=intervenes&id=<?php echo $result['id']?><?php echo $this->su();?>" method="post">
<input name="intervene" id="zhi" value=" <?php echo $result['intervene']?>">
<input name="submit" type="submit" class="save-bt btn btn-info" id="bao" value="确认"> &nbsp;&nbsp;&nbsp;
<!-- <a href="?m=Mjin&f=content&v=intervenes&id=<?php echo $result['id']?><?php echo $this->su();?>">ccccc</a>
</form> -->
</form>
<script type="text/javascript" src="<?php echo R; ?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
  $("#bao").click(function(){
    $("div",parent.document).trigger('close');
    console.log($("div",parent.document));
  })
</script>

