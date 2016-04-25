<?php defined('IN_WZ') or exit('No direct script access allowed');?>

<style type="text/css">
 
    .Lright{
      float: right;
      margin-right:10px; 
    }
    .particulars{

      background-color: #eaeaea;
      width:400px;
      margin-top:50px; 
      
    }
    .h{
       width:300px;
       margin-top:50px;
    }
    .case-div{
  position: relative;
}
.case-name{
  position: absolute;
  width: 100%;
  top: 50%;
  left: 0;
  margin-top: -1rem;
  font-size: 1rem;
  color: #fff;
  text-align: center;
  z-index: 10;
  margin-left: 10px;
}
    .div-ceng{
      position: absolute;
      width: 80%;
      height: 100%;
      margin-left:40px; 
      top: 0;
      background-color: #000;
      opacity: 0.3;
    }
.container{
  padding-top: 2.6875rem;
  margin: 0 auto;
  max-width: 20rem;
  margin-left: 500px;
}
.detail-info{
  margin: 0.875rem 0 2.1875rem;
  text-align: center;
  font-size: 0.75rem;

}
.detail-info1{
  margin: 0.875rem 0 2.1875rem;
  text-align: center;
  font-size: 0.75rem;
  color: #fff;

}
.case-name1{
  position: absolute;
  width: 320;
  top: 50%;
  left: 0;
  margin-top: -1rem;
  font-size: 1rem;
  color: #fff;
  text-align: center;
  z-index: 10;
  margin-left: 10px;
}
.Rrghit{
 margin-top:-200px; 
}
.hong{


}

</style>
<body class="body">
<section class="wrapper">
<center>
   <div class="particulars">
     <div id="detail-works" class="container">
   <div class="case-div">
    <div style="width:320px;height:200px;background:#000;position:absolute;opacity:.3;">
    </div>
      <img src="<?php echo getMImgShow($result['cover'],'original')?>" alt="效果图" style="width:320px;height:200px">
        <h3 class="case-name1"><?php echo $result['name']?>
               <div class="detail-info1"><span style="">#<?php
                 foreach($style as $key=> $st){
                  if($result['style']==$key){
                    echo $st; 
                    }
                 
                   }
                  ?></span >&nbsp;·&nbsp;<span><?php 
                    foreach($house as $ke=> $st){
                      if($result['housetype']==$ke){
                        echo $st;
                        }
               }?></span></div>
             </h3>
      <div class="div-ceng"></div>
    </div> 
    </div>
            <div class="Rrghit">
            <div class="case-div">
      <img src="<?php echo getMImgShow($result['cover'],'original')?>" alt="效果图" style="width: 320px;">
      <h3 class="case-name"><?php echo $result['name']?>
      </h3>
      <div class="div-ceng"></div>
    </div>
    <div class="detail-info"><span style="">#<?php
        foreach($house as $ke=> $st){
          if($result['housetype']==$ke){
            echo $st;
            }
       }
     ?></span>&nbsp;&nbsp;&nbsp;<span style="">#<?php
       echo $result['area']."㎡";
     ?></span>&nbsp;&nbsp;&nbsp;<span>#<?php 
       foreach($style as $key=> $st){
          if($result['style']==$key){
            echo $st; 
            }
       }?></span></div>
            <?
              $aRowid = $aTemp = array();
              $case = explode('|',$result['case']);
              $space = explode('|', $result['space']);
              $alt = explode('|', $result['alt']);
              foreach($case as $key => $value){
                  $aTemp[$space[$key]][] = array(
                    'url' => $value,
                    'alt' => $alt[$key],
                    'space' => $space[$key],
                  );
              }
              ksort($aTemp,SORT_NUMERIC);
              foreach($aTemp as $item){
                  foreach($item as $citem){
                      $aRowid[] = $citem;
                  }
              }
                foreach ($aRowid as $key => $v) {
              ?>   <div class="h">
              <?php 
             if($v['url']<>""){?>
             <img src="<?php echo getMImgShow($v['url'],'original')?>" style="width: 320px;" />
                     <h6 style="margin:0;"><span><?if($key==0){
                      $a=1;
                      echo $a;
                      }else if(($v['space'])!=29){
                               echo $key+$a;
                        
                        }?></span>
                <?foreach($spac as $k => $spa){
                  if($v['space']==$k){
                      echo $spa;
                   }
                }?>
                &nbsp;<?php echo $v['alt']?><div></h6>
               <? } ?>
               <??>
           </div>
          <?}?>
          </div>
          <div><?php $str=R;$newstr = substr($str,0,strlen($str)-5); 
          echo $newstr.'/mobile-detail_case.html?id='.$result['id']?></div>
     </center>
   </div>

</section>
</script>
</body>
</html>
