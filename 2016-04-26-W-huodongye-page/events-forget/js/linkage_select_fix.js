jQuery.extend({
    "loadProvince": function(id, nextid, lastid) {
        ///<summary>
        /// 加载省份
        ///</summary>
		//28陕西 西安
		//13浙江 杭州
		//3 上海
        var appDomain = "http://www.uzhuang.com/";
        var ar = [5,6,7, 8, 9, 10, 11, 12, 14, 15, 16, 17, 18, 19, 20, 22, 23, 24, 25, 26, 27, 29, 30, 31, 32, 33, 34, 35, 3358];
        var url = appDomain + "index.php";
        var params = {
            'pid': '0',
            'm': 'linkage',
            'f': 'json',
            'v': 'get_json_area',
            'notIdS': ar
        };
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: url,
            data: params,
            cache: false,
            success: function(dataResult) {

                if (dataResult) {
                    var provinceHtml = "<option  value='0'>省/市</option>";
                    for (var key in dataResult) {
                        if (key == 2 || key == 3 || key == 4 || key == 5 || key == 34 || key == 35) {
                            provinceHtml += "<option value='" + loadDC(key) + "'>" + dataResult[key] + "</option>"
                        } 
						// wushi fix 2015-12-01 S
						 else if (key == 21) {
							  provinceHtml += "<option value='" + 328 + "'>" + "深圳市" + "</option>";
							  provinceHtml += "<option value='" + 326 + "'>" + "广州市" + "</option>"; 
                        }
						else if(key == 28){
								provinceHtml += "<option value='" + 435 + "'>" + "西安市" + "</option>";
							}
						else if(key == 13){
								provinceHtml += "<option value='" + 213 + "'>" + "杭州市" + "</option>";
							}
						// wushi fix 2015-12-01 E
						else {
                            provinceHtml += "<option value='" + key + "'>" + dataResult[key] + "</option>";
                        }
                    }
                    $("#" + id).html(provinceHtml);
                    rSelects(id, nextid, lastid, "0");
                }
            },
            error: function(XMLHttpResponse) {}
        });
		
		

        function loadCity(id, nextid, lastid) {
            ///<summary>
            /// 加载城市
            ///</summary>
            var appDomain = "http://www.uzhuang.com/";
            var ohtml = "<option value='0' style='padding:0;display:inline-block;'>市/地区</option>"; //zx 2015-10-21
            var proEl = $("#" + id + " option:selected");
            var provinceId = proEl.val();
            var provinceName = proEl.text();
            var params = {
                'pid': provinceId,
                'm': 'linkage',
                'f': 'json',
                'v': 'get_json_area'
            };
            if (provinceId > 0) {
                var url = appDomain + "index.php";
                jQuery.ajax({
                    type: "POST",
                    dataType: "json",
                    url: url,
                    data: params,
                    cache: false,
                    success: function(dataResult) {
                        var cityHtml;//var cityHtml = ohtml
                        if (dataResult) {
                            for (var key in dataResult) {
                                cityHtml += "<option value='" + key + "'>" + dataResult[key] + "</option>";
                            }
                        }
                        $("#" + nextid).html(cityHtml);
						//$("#" + lastid).html(cityHtml);
                        rSelects(id, nextid, lastid, "next");
                    },
                    error: function(XMLHttpResponse) {}
                });
            } else {
		var play_id = "select_info_" + nextid; //zx 2015-10-21
                $("#" + play_id).html('市/地区');
		rSelects(id, nextid, lastid, "zero");
            }
        }

        function loadDC(id) {
			///<summary>
            /// 判断直辖市
            ///</summary>
            var appDomain = "http://www.uzhuang.com/";
            var url = appDomain + "index.php";
            var keys;
            var params = {
                'pid': id,
                'm': 'linkage',
                'f': 'json',
                'v': 'get_json_area'
            };
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                async: false,
                url: url,
                data: params,
                cache: false,
                success: function(dataResult) {
                    if (dataResult) {
                        for (var key in dataResult) {
                            keys = key;
                        }
                    }
                },
                error: function(XMLHttpResponse) {}
            });
            return keys;
        }

        function rSelects(id, nextid, lastid, s) {
			 ///<summary>
            /// select样式初始化
            ///</summary>
            if (s == "0") {

                $("select[id=" + id + "],select[id=" + nextid + "],select[id=" + lastid + "]").each(function(index) {
                    $(this).css("display", "none");
                    creatdiv($(this));
                    rOptions($(this).attr("name"), id, nextid, lastid);
                    mouseSelects($(this).attr("name"));
					
                });
            } else if (s == "next") {
                var names = $("#" + nextid).attr("name");
                rOptions(names, id, nextid, lastid);
				
            } else if (s == "zero") { //zx 2015-10-21
                var names = "options_" + $("#" + nextid).attr("name");
		$("#"+names).html("<li value='0'>市/地区</li>");
		
	    } else {
                var names = $("#" + id).attr("name");
                rOptions(names, id, nextid, lastid);
			
            }

            function creatdiv(id) {
				///<summary>
              /// 创建div
              ///</summary>
                var select_tag = $('<div></div>'); 
                select_tag.attr('id', 'select_' + id.attr("name")); 
                select_tag.addClass('select_box'); 
                var select_info = $('<div></div>'); 
                select_info.attr('id', 'select_info_' + id.attr("name")); 
                select_info.addClass('tag_select'); 
                select_info.css("cursor", "pointer");
                select_info.appendTo(select_tag); 
                var select_ul = $('<ul></ul>');
                select_ul.attr('id', 'options_' + id.attr("name"));
                select_ul.addClass('tag_options');
                select_ul.css({
                    "position": "absolute",
                    "display": "none",
                    "z-index": "999"
                });
		select_ul.appendTo(select_tag);
               	select_tag.appendTo(id.closest('div'));

            };

            function rOptions(name, id, nextid, lastid) {
				///<summary>
            	/// 获取option
            	///</summary>
			
                var options_ul = 'options_' + name;
                $("#" + options_ul).empty();
                $("select[name=" + name + "] option").each(function(index) {

                    var txt = $(this).text();

                    creatli(name, txt, $(this), id, nextid, lastid);
                });
                var options_ul = 'options_' + name;
                $("#" + options_ul + " li").hover(function(){  //zx 2015-10-21
			$(this).css('background-color', '#f3f3f3');
		}, function(){
			$(this).css('background-color', '');
		}).click(function(e) {

                    var val = $(this).val();
                    clickOptions($(this), name, val, id, nextid, lastid)
                });
            };

            function creatli(name, txt, self, id, nextid, lastid) {
				///<summary>
            	/// 创建li
            	///</summary>
                var options_ul = 'options_' + name;

                var option_li = $('<li value=' + self.val() + '>' + txt + '</li>');
                option_li.css("cursor", "pointer");
                option_li.addClass('open');
                option_li.appendTo($("#" + options_ul));

                var option_selected = self.attr("selected")

                if (option_selected) {

                    option_li.addClass('open_selected');
                    option_li.attr('id', 'selected_' + name);
                    $('#select_info_' + name).html(option_li.html());

                }

                option_li.hover(function() {
                    $(this).removeClass().addClass('open_hover');
                },
                function() {
                    if ($(this).attr("id") == 'selected_' + name) {
                        $(this).removeClass().addClass('open_selected');
                    } else {
                        $(this).removeClass().addClass('open');
                    }
                })

            };

        }

        function mouseSelects(name) {
			 ///<summary>
            /// 鼠标移入select
            ///</summary>
            var sincn = 'select_info_' + name;
            $("#" + sincn).hover(function() {
                if ($(this).attr("class") == 'tag_select') $(this).attr("class", 'tag_select_hover');
            },
            function() {
                if ($(this).attr("class") == 'tag_select_hover') $(this).attr("class", 'tag_select');
            })

            $("#" + sincn).click(function(e) {
                clickSelects(name);
            })

        }

        function clickSelects(name) {
			 ///<summary>
            /// select点击下拉显示
            ///</summary>
            var sincn = 'select_info_' + name;
            var sinul = 'options_' + name;

            if ($("#" + sincn).attr("class") == 'tag_select_hover') {
                $("#" + sincn).attr('class', 'tag_select_open');
                $("#" + sinul).css("display", "block");
            } else if ($("#" + sincn).attr("class") == 'tag_select_open') {
                $("#" + sincn).attr('class', 'tag_select_hover');
                $("#" + sinul).css("display", "none");
            }

        }

        function clickOptions(self, name, val, id, nextid, lastid) {
			 ///<summary>
            /// option点击事件
            ///</summary>
            $('#select_info_' + name).html("");
            $('#selected_' + name).attr("class", "open");
			 $('#selected_' + name).attr("id", "");
			 self.attr("id", "selected_" + name);
			 self.attr("class", "open_hover");

            var select_info = $('<div>' + self.html() + '</div>');
            select_info.attr('id', 'select_info_' + name);
            select_info.addClass('tag_select');
            select_info.css("cursor", "pointer");
            $('#options_' + name).parent().append(select_info);
            $('#select_info_' + name).remove();
            mouseSelects(name);
            var h = self.html();
            $('#options_' + name).css("display", "none")

            $('#select_info_' + name).attr("class", 'tag_select');
			 $("select[name=" + name + "] option").attr("selected", false);
            $("select[name=" + name + "] option[value=" + val + "]").attr("selected", true);

            $('#options_' + name).css("display", "none");
			 $('#select_info_' + name).attr("class", "tag_select");
            $("select[id=" + id + "] option[value=" + val + "]").attr("selected", true);
            if (name == $("#" + id).attr("name")) {
                loadCity(id, nextid, lastid);
            }

        }
		//wushi add 2015-12-18 S
		$(function(){ 
			$(document).click(function (e) { 
				
				var target_id = $(e.target).attr('id'); 
				var sel_id01 = "select_info_"+id;
				var sel_id02 = "select_info_"+nextid;
				var sel_id03 = "select_info_"+lastid;
				var opt_id01 = "options_"+id;
				var opt_id02 = "options_"+nextid;
				var opt_id03 = "options_"+lastid;

				if( target_id !== sel_id01 ){
					
					$("#" + sel_id01).attr('class', 'tag_select');
                	$("#" + opt_id01).css("display", "none");

					}
				 if( target_id !== sel_id02 ){
					 
					$("#" + sel_id02).attr('class', 'tag_select');
                	$("#" + opt_id02).css("display", "none");
					}
				if( target_id !== sel_id03 ){
					 
					$("#" + sel_id03).attr('class', 'tag_select');
                	$("#" + opt_id03).css("display", "none");
					}
				else{ }

			})
		});
		//wushi add 2015-12-18 E
		
		//
    }
});
