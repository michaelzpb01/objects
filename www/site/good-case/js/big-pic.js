require.config({
	baseUrl: '../base/js',
    paths: {
        zepto:'zepto.min',
        center: 'setCenter'
    },
    shim: {
　　　　'zepto':{
　　　　　　exports: '$'
　　　　}
	}
});

require(['zepto', 'center'], function($){
	$('#pic-list').setCenter();
});