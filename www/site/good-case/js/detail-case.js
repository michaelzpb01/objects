require.config({
	baseUrl: '../base/js',
    paths: {
        zepto:'zepto.min',
        gotop:'goTop',
        sidebar: 'sidebar'
    },
    shim: {
　　　　'zepto':{
　　　　　　exports: '$'
　　　　}
	}
});

require(['zepto', 'gotop', 'sidebar'], function($){
});