function sidebar(){
	var sidebar = $('#sidebar'),
		menu = $('#menu'),
		bg = $('.sidebar-bg');

	sidebar.hide();
	bg.hide();

	menu.on('click', function(event){
		sidebar.show();
		bg.show();
		bg.add(sidebar).on('touchmove', function(event){
			event.preventDefault();
		})
	});

	bg.on('click', function(){
		sidebar.hide();
		bg.hide();
		$('body').css('overflow', '');
	});
};