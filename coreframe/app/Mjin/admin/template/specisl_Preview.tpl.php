<?php defined('IN_WZ') or exit('No direct script access allowed');?>

<style type="text/css">
    .dao{
      width: 200px;

    }
    .alt{
    	width: 200px;
    }
    .da{
        background-color: #eaeaea;
      width:300px;
    }
</style>
<body class="body">
<section class="wrapper">
<center>
   <div class="da">
   <table>
   	<tr>
   		<td>
   			 <img src="<?php echo getMImgShow($result['cover'],'original')?>" alt="封面图" style="width:200px;height:200px;;">
   		</td>
   	</tr>
   	<tr>
   		<td>
   		   <div class="dao"> 
   		   <center>
         “<?php echo $result['dao']?> ”
   		   </center>
   		  </div>
   		</td>
   	</tr>

   	<?php 
   	    $aTemp=array();
   	    $atlas=explode('|',$result['atlas']);
   	    $alt=explode('|',$result['alt']);
   	 foreach ($atlas as $key => $value) {
   	 	$aTemp[]=array(
   	 		   'atlas' => $value,
   	 		   'alt'=>$alt[$key]
   	 		);
   	 }
         foreach ($aTemp as $key => $aTemps) {?>
         <tr>
           <td>
           <?
           if ($aTemps['atlas']!="") {?>
   			<img src="<?php echo getMImgShow($aTemps['atlas'],'original')?>" alt="" style="width:200px;height:200px;;">
        <?}?>
   			<p>
   			  <div class="alt">
   			  	<?php echo $aTemps['alt']?>
   			  </div>
   			</p>
   		  </td>
        </tr>
      <?   }
   	?>
   		  
   </table>
   </div>
</center>
</div>

</section>
</script>
</body>
</html>
