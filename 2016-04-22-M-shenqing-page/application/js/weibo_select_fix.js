var wbbm_province = 0; //获取省份 0为未选择
var wbbm_citys = 0; //获取城市 0为未选择
(function($) {
	$.fn.loadProvince = function(nextid) {

		var select_1 = this;
		var select_2 = $(nextid);
		var id = this.attr("id");
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "http://www.uzhuang.com/api/mwap.php?action=fbcity&select=0",
            cache: false,
            success: function(dataResult) {
				//console.log(dataResult);
                if (dataResult) {
					//console.log(dataResult.data[0].lid);
                    var provinceHtml = "<option  value='0'>省/市</option>";
					var province = dataResult.data;
                    for (var key in province) {
                            provinceHtml += '<option value='+province[key].lid +'>' + province[key].name + '</option>';
                    }
                    $("#" + id).html(provinceHtml);
					select_1.bind("change",function(e){
						var selected_1 = select_1.find('option').not(function(){ return !this.selected });
						wbbm_province = selected_1.val();
						if(wbbm_province == 0)
						{
							select_2.html("<option  value='0'>市/地区</option>");
							return false;
						}
						wbbm_citys = 0;
						getCitys(wbbm_province, select_2);
					})
                }
            },
            error: function(XMLHttpResponse) {
				console.log(XMLHttpRequest.status);
				console.log(XMLHttpRequest.readyState);
				console.log(textStatus);
				
			}
        });
       
    }
})(Zepto);

function getCitys(selected_id, city_box) {
	var select_2 = city_box;
	$.ajax({
	  type: "POST",
	  dataType: "json",
	  url: "http://www.uzhuang.com/api/mwap.php?action=fbcity&select="+selected_id,
	  cache: false,
	  success: function(dataResult) {
		console.log(dataResult);
		if (dataResult) {
			//console.log(dataResult.data[0].lid);
			var citysHtml = "<option  value='0'>市/地区</option>";
			var citys = dataResult.data;
			for (var key in citys) {
					citysHtml += '<option value='+citys[key].lid +'>' + citys[key].name + '</option>';
					//console.log(provinceHtml);
			}
			console.log(citysHtml);
			city_box.html(citysHtml);
			select_2.bind("change",function(e){
				//var a = select_1.find("option:selected").text();
				var selected_2 = select_2.find('option').not(function(){ return !this.selected });
				wbbm_citys = selected_2.val();
			})
		}
	  },
	  error: function(XMLHttpResponse) {
		console.log(XMLHttpRequest.status);
		console.log(XMLHttpRequest.readyState);
		console.log(textStatus);
		
	  }
	});
}