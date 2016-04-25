var userId = getUrlParameter("uid");
var questionId = getUrlParameter("qid");

var isLoading = true, //ajax请求状态
	page = 1,		  //页数
	totalPage = 0,	  //总页数
	docH = 0;

$(function(){
	getInfo();
	$(window).bind("scroll", scrollPages);
});
function getInfo(){
	isLoading = false;
	var answerUl = '<ul id=page'+page+' class=answer-content></ul>';
	$("#answer-box").append(answerUl);
	$.ajax({
		type: "POST",
		url: "http://bang.uzhuang.com/index.php?m=bang&f=question&v=details&uid="+userId+"&qid="+questionId+"&page="+page,
		dataType: "json",
		timeout: 3000,
		success: function(res) {
			console.log(res)
			if(res.data1.pages){
				totalPage = res.data1.pages;
				if(totalPage <= 1){
					$(".down-page").remove();
				}
			}
			solveTemplate('#question-box', '#question-data', res);
			solveTemplate('#page'+page, '#answer-data', res);
			docH = $(document).height();
			isLoading = true;
			page++;
		},
		error: function(XMLHttpRequest, textStatus){
			console.log("data error");
		}
	});
}

function scrollPages() {
	var pageH = $('.down-page').height();
	var sTop = document.body.scrollTop + 200;
	if(pageH + sTop >= docH - winH && isLoading) {
		if(page <= totalPage) {
			getInfo();
		} else {
			$(".down-page").hide();
		}
	}
}