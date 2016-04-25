<?php defined('IN_WZ') or exit('No direct script access allowed');?>
<?php
include $this->template('header','core');
?>
<body class="body pxgridsbody">
<style type="text/css">
    .tablewarnings{display: none;}
    #materialtotal{
    }
    body, html {
  overflow: auto !important;
}
.zinl{
  display: inline-block;
}
/*  #companyname{
    display: none;
  }
*/
   #cover{
    width: 350px;
   }
   .ha_bor{border:1px solid;}
</style>
<link href="<?php echo R;?>js/colorpicker/style.css" rel="stylesheet">
<link href="<?php echo R;?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo R;?>js/colorpicker/color.js"></script>
<section class="wrapper">
    <div class="row">
     <div class="col-lg-12">
     <section class="panel">
        <div class="panel-body" id="panel-bodys">
        <form name="myform" class="form-horizontal tasi-form" id="form6" action="" method="post">
        <table class="table table-striped table-advance table-hover" id="contenttable">
        <tbody>
      <div id="myTabContent" class="tab-content">
      <div role="tabpanel" class="tab-pane fade active in" id="tabs1" aria-labelledby="1tab">
      <table class="table table-striped table-advance table-hover" id="contenttable">
            <?php
            if(is_array($formdata['0'])) {
                foreach($formdata['0'] as $field=>$info) {
                    if($info['powerful_field']) continue;
                    if($info['formtype']=='powerful_field') {
                        foreach($formdata['0'] as $_fm=>$_fm_value) {
                            if($_fm_value['powerful_field']) {
                                $info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
                            }
                        }
                    }
                    ?>
                    <tr>
                        <td style="width: 120px;">
                            <?php if($info['star']){ ?> <font color="red">*</font><?php } ?>
                            <strong><?php echo $info['name']?></strong>
                        </td>
                        <td class="hidden-phone" >
                            <div class="col-sm-12 input-group" style="width: 300px;">
                                <?php echo $info['form']?>
                                <span class="tablewarnings"><?php echo $info['remark']?></span>
                            </div>
                        </td>
                    </tr>
                <?php
                } }?>
       <button style=""class="ha_bor" id="HONG" onclick="openiframe('?m=Mjin&f=content&v=company&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>','testaa','装修公司选择',500,500);" type="button">装修公司选择......</button>
       <input type="hidden" name="comName" id="comName" >
        </table>
        <div class="contentsubmit text-center" style="margin-top:-50px;">
          <input type="submit" style="visibility:hidden;" value=""> &nbsp;&nbsp;&nbsp;
        <input name="submit" type="submit" class="save-bt btn btn-info" id="bao" value="保存">&nbsp;&nbsp;&nbsp;

         <button style=""class="save-bt btn btn-info" onclick="openiframe('?m=Mjin&f=content&v=Previews&'+$('form').serialize()+'<?php echo $this->su();?>','','查看详情',1100,500);" type="button">预览</button> &nbsp;&nbsp;&nbsp;
            <input name="submit" type="submit" class="save-bt btn btn-info" id="fabu" value="发布"> &nbsp;&nbsp;&nbsp;
      </div>
    <!--   <h8> 描述（建议80字以内）</h8> -->
    </div>
        </td>
        </tr>
        <tr>
        <td>

     </section>
     </div>
    </div>
</section>
<script src="<?php echo R;?>js/bootstrap.min.js"></script>
<script src="<?php echo R;?>js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/pxgrids-scripts.js"></script>
<script src="<?php echo R;?>js/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<link href="<?php echo R;?>css/style.css" rel="stylesheet">
<script type="text/javascript">


  $("#companyname").replaceWith($("#HONG"));


var issubmit = true;
var caseExit = false;
var isvalidateCase = true;
var resion1;
  $(document).on('click','#cover',function(){
    var v=$(this).val();
    img_view(v);
    return false;
  })
  $("td:contains(所属公司ID)").closest("tr").css("display","none");
  $("tr").eq(10).insertBefore($("tr").eq(8));
  var areas = $('<lable style="display: block;height: 30px;line-height: 30px;position: relative;left: 10px;">㎡</lable>');
  $("#area").after(areas)
  var money = $('<lable style="height: 25px;position: relative;">万元</lable>');
  $("#total").after(money)
    var money1 = $('<lable style="height: 25px;position: relative;">万元</lable>');
  $("#materialtotal").after(money1)
    var money3 = $('<lable style="height: 25px;position: relative;">万元</lable>');
  $("#crafttotal").after(money3)
  $("#browsenum").before('<lable style="height: 25px;position: relative;margin-right:5px;">0</lable>')
  $("#likes").before('<lable style="height: 25px;position: relative;margin-right:5px;">0</lable>')
  $("#sharenum").before('<lable style="height: 25px;position: relative;margin-right:5px;">0</lable>')
  $("#collectnum").before('<lable style="height: 25px;position: relative;margin-right:5px;">0</lable>')
  $("#companyid").attr("disabled",true);
  $("#cover").attr('placeholder','封面图尺寸：宽度640px，高度≥480px（最好等于480px））');
  $("strong:contains(案例图)").closest('td').next('td').append('<span style="position: relative;top: -23px;left: 90px;color:red;">（案例图尺寸：宽度640px，高度≤960px）</span>');
 $("#bao").click(function(){
    var v1 = $("#name").val();
    var v2 = $("#area").val();
    if (!v1) {
      alert("请输入案例名称");
      return false;
    };
    var reg = /^[0-9]*$/;
    var res = reg.test(v2);
    if (!res) {
      alert("面积只能位数字");
      return false;
    };

    if (!v2) {
      alert("请输入面积");
      return false;
    };
  var name = $("#name").val();
  var flag=true;
    $.ajax({
      url:"?m=Mjin&f=content&v=checkNameExit<?php echo $this->su();?>",
      data:{'name':name},
      type:"POST",
      dataType:"json",
      success:function(data){
        if (data) {
          flag=false;
          alert("案例已经存在");
        }else{
          flag=true;
        }
      },
      async:false
    })
   if (!flag) {
    return false;
   };
  })

 

  $("#yu").click(function(){
    if (!confirm('确定预览该信息？')) {
      return false;
    };
  })
    $(".save-bt").click(function(){
        t=setTimeout("hide_formtips()",5000);
    });
    $(function(){
        $(".form-horizontal").Validform({
            tiptype:1
            //$.Hidemsg()
        });
    })

    $(document).on('keyup','#crafttotal',function(){
      var v1 = $(this).val();
      if (v1) {
        v1 = parseInt(v1);
      }else{
        v1 = 0;
      }
      var v2 = $("#materialtotal").val();
      if (v2) {
        v2 = parseInt(v2);
      }else{
        v2=0;
      }
      var total = v1+v2;
      $("#total").val(total);
    })

  
    function change_route(name,value) {
        $("#def_type").html(name);
        $("#route_type").val(value);
    }
    function hide_formtips() {
        $.Hidemsg();
        clearInterval(t);
    }
    function check_title() {
     
        var title = $("#title").val();
        if(title=='') {
            alert('请填写标题');
            $("#title").focus();
        } else {
            $.post("?m=content&f=content&v=checktitle<?php echo $this->su();?>", { title: title,cid:<?php echo $cid;?>,id:0},
                function(data){
                    if(data=='ok') {
                        alert('没有重复标题');
                    } else if(data=='1') {
                        alert('有完全相同的标题存在');
                    } else if(data=='2') {
                        alert('有相似度很高的标题存在');
                    }
                });
        }
    }
<?php
if($cate_config['workflowid'] && $_SESSION['role']!=1) {
?>
    $("input[name='form[status]'][value='9']").attr("disabled",true);
    $("input[name='form[status]'][value='8']").attr("disabled",true);
    $("input[name='form[status]'][value='1']").attr("checked",true);

<?php }?>
$(function(){
    $(".form-horizontal").Validform({
      tiptype:3,
      callback:function(form){
        $("#submit").click();
      }
    });
});
  $("#bao1").click(function(){
    $("#form6").attr("action","?m=Mjin&f=content&v=Previews<?php echo $this->su();?>")
  })


$("#fabu,#bao").click(function(){
  var v1 = $("#name").val();
  var v2 = $("#area").val();
  var v3 = $("#comName").val();
  var v4 = $("#designer").val();
  var v5 = $("#cover").val();
  var v6 = $("#xiao").val();
  if (!v1) {
    alert("请输入案例名称");
    return false;
  };
  if (!v2) {
    alert("请输入面积");
    return false;
  };
  if (!v3) {
    alert("请选择装修公司");
    return false;
  };
  if (!v4) {
    alert("请选择设计师");
    return false;
  };
   if (!v5) {
    alert("请选择封面图");
    return false;
  };
   if (!v6) {
    alert("请选择案例图");
    return false;
  };
    var name = $("#name").val();
  var flag=true;
 
    $.ajax({
      url:"?m=Mjin&f=content&v=checkNameExit<?php echo $this->su();?>",
      data:{'name':name},
      type:"POST",
      dataType:"json",
      success:function(data){
        if (data) {
          flag=false;
          alert("案例已经存在");
        }else{
          flag=true;
        }
      },
      async:false
    })
   if (!flag) {
    return false;
   };

}) 

// window.onbeforeunload = function() { 
//   if($("#name").val() == '' || $("#area").val() == ''){
// 　　       return "确定离开页面吗？"; 
//   } 
// }
</script>

<link href="{R}css/validform.css" rel="stylesheet" />
<script src="{R}js/validform.min.js"></script>
<script type="text/javascript" src="{R}js/validform.min.js"></script>
