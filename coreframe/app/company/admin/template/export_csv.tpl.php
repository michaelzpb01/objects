<?php defined('IN_WZ') or exit('No direct script access allowed');?>
<?php
include $this->template('header','core');
?>
<body class="body pxgridsbody">
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<?php echo $this->menu($GLOBALS['_menuid']);?>

				<div class="panel-body" id="formid">
					<form class="form-horizontal tasi-form" method="post" action="">
						<div class="form-group">
							<label class="col-sm-2 control-label">开始时间</label>
							<div class="col-sm-4">
								<?php echo WUZHI_form::calendar('starttime',$starttime,1);?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">截至时间</label>
							<div class="col-sm-4">
								<?php echo WUZHI_form::calendar('endtime',$endtime,1);?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-10">
								<input class="btn btn-info" id="submit" type="submit" name="submit" value="导出">
							</div>
						</div>
					</form>
				</div>

			</section>
		</div>

	</div>
</section>
<script type="text/javascript">
	$(function(){
		$(".form-horizontal").Validform({
			tiptype:1,
			postonce:true,
			beforeSubmit:function(curform){

			}
		});
	})

</script>
<script src="<?php echo R;?>js/bootstrap.min.js"></script>
<script src="<?php echo R;?>js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/pxgrids-scripts.js"></script>

