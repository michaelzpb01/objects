var wbbm_province = 0; //获取省份 0为未选择
(function($) {
	$.fn.loadProvince = function(nextid) {

		var select_1 = this;
		var id = this.attr("id");
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/api/mmwap.php?action=fbcity",
            cache: false,
            success: function(dataResult) {
                if (dataResult) {
                    var provinceHtml = "<option  value='0'>城市</option>";
					var province = dataResult.data;
                    for (var key in province) {
                            provinceHtml += '<option value='+province[key].lib +'>' + province[key].name + '</option>';
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
						console.log("selected_1:"+selected_1.html());
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