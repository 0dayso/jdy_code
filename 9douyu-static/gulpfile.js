process.env.DISABLE_NOTIFIER = true;
var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix){
    //pc2 css
    mix.styles([
        'pc2/main.css',
        'pc2/new-style.css',
        'pc2/nyroModal.css',
        'pc2/public-css.css',
        'pc2/reset.css',
        'pc2/web.css',
        'pc2/jquery.fancybox-1.3.4.css',
        'pc2/progressbar.css',
        'pc2/progressbar-small.css',
        'pc2/hexing.css',
        'pc2/pc2-m.css',
        'pc2/pc2-t.css',
        'pc2/pc2-t1.css',
        'pc2/pc2-t2.css',
        'pc2/pc2-x.css',
        'pc2/m-cardinformation.css',
        'pc2/tip-blue.css',
        'pc2/tip-yellow.css',
    ],'static/css/pc2.css','assets/css');
    //pc2 js
    mix.scripts([
        'pc2/jquery-1.9.1.js',
        'pc2/jquery.plugin.js',
        'pc2/t-common.js',
        'pc2/jquery.fancybox-1.3.1.pack.js',
        'pc2/jquery.slides.js',
        'pc2/time.js',
        'pc2/jquery.poshytip.min.js',
        //弹窗的插件
        'pc2/jquery.nyroModal.custom.js',
        //投资零钱计划流程所需js?
        //计算器,所有投资页面的计算器要用到
        'pc2/calculate-new.js',
        //与收益计算器相关的页面
        'pc2/principalInterest.js',
        'pc2/dateDiff.js',
        'pc2/jquery.easing-1.3.js',
        'pc2/jquery.jcarousellite.js',
        'pc2/jquery.mousewheel-3.1.12.js',
        'pc2/navsetfixed.js',
        'pc2/invest.js',
    ],'static/js/pc2.js','assets/js');
    //新旧暂存
    // mix.copy('assets/css/pc2/web.css','static/css/web.css');
    // mix.copy('assets/css/activity/chirstmas2015.css','static/css/chirstmas2015.css');
    // mix.copy('assets/css/activity/monkey.css','static/css/monkey.css');
    // mix.copy('assets/css/pc2/public-css.css','static/css/public-css.css');


    //stats css
    /*mix.styles([
        'stats/bootstrap-responsive.min.css',
        'stats/bootstrap.min.css',
        'stats/datepicker.css',
        'stats/chosen.css',
        'stats/elfinder.min.css',
        'stats/elfinder.theme.css',
        'stats/font-awesome-ie7.min.css',
        'stats/font-awesome.min.css',
        'stats/fullcalendar.css',
        'stats/glyphicons.css',
        'stats/halflings.css',
        'stats/ie.css',
        'stats/ie9.css',
        'stats/jquery-ui-1.8.21.custom.css',
        'stats/jquery.cleditor.css',
        'stats/jquery.gritter.css',
        'stats/jquery.iphone.toggle.css',
        'stats/jquery.noty.css',
        'stats/noty_theme_default.css',
        'stats/style-forms.css',
        'stats/uniform.default.css',
        'stats/uploadify.css',
        'stats/style.css',
        'stats/style-responsive.css',
        'stats/matrix-style.css',
        'stats/matrix-media.css',
    ],'public/static/css/stats.css');*/

    //stats js
    /*mix.scripts([
        'stats/jquery-1.9.1.min.js',
        'stats/jquery-migrate-1.0.0.min.js',
        'stats/jquery-ui-1.10.0.custom.min.js',
        'stats/jquery.ui.touch-punch.js',
        'stats/modernizr.js',
        'stats/bootstrap.min.js',
        'stats/jquery.cookie.js',
        'stats/fullcalendar.min.js',
        'stats/jquery.dataTables.min.js',
        'stats/excanvas.js',
        'stats/jquery.flot.js',
        'stats/jquery.flot.pie.js',
        'stats/jquery.flot.stack.js',
        'stats/jquery.flot.resize.min.js',
        'stats/jquery.chosen.min.js',
        'stats/jquery.uniform.min.js',
        'stats/jquery.cleditor.min.js',
        'stats/jquery.noty.js',
        'stats/jquery.elfinder.min.js',
        'stats/jquery.raty.min.js',
        'stats/jquery.iphone.toggle.js',
        'stats/jquery.uploadify-3.1.min.js',
        'stats/jquery.gritter.min.js',
        'stats/jquery.imagesloaded.js',
        'stats/jquery.masonry.min.js',
        'stats/jquery.knob.modified.js',
        'stats/jquery.sparkline.min.js',
        'stats/counter.js',
        'stats/custom.js',
        'stats/bootstrap-datepicker.js',
        // 'stats/matrix.charts.js',
        // 'stats/matrix.chat.js',
        'stats/matrix.js',
        'stats/jquery.peity.min.js',
        'stats/matrix.dashboard.js',
        'stats/statsmain.js',

    ],'public/static/js/stats.js');*/
    //mix.copy('assets/css/recharge.css','Public/static/css/recharge.css');


    //statsmain css
    /*mix.styles([
        'stats/bootstrap.min.css',
        'stats/bootstrap-responsive.min.css',
        'statsmain/colorpicker.css',
        'stats/datepicker.css',
        'statsmain/uniform.css',
        'statsmain/select2.css',
        'statsmain/matrix-style.css',
        'stats/matrix-media.css',
        'statsmain/bootstrap-wysihtml5.css',
        'stats/font-awesome.min.css',
        'stats/jquery.gritter.css',
        'statsmain/fullcalendar.css',
        'statsmain/self.css',
    ],'public/static/css/statsmain.css');
    mix.copy('resources/assets/css/recharge.css','public/static/css/recharge.css');*/
});
