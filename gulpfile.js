process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');

stylesPath = 'public/assets/styles/';
scriptsPath = 'public/assets/scripts/';
imagesPath = 'public/assets/images/';
skinsPath = 'public/assets/scripts/skins/';
fontsPath = 'public/assets/fonts/';
lessPath = 'public/assets/styles/less';

elixir.config.css.outputFolder = stylesPath;
elixir.config.js.outputFolder = scriptsPath;
elixir.config.sourcemaps = false;

elixir(function (mix) {
    mix.copy('resources/assets/images/*.*', imagesPath)
        .copy('resources/dist/img/*', imagesPath)
        .copy('resources/assets/css/*.*', stylesPath)
        .copy('resources/assets/css/*.min.css', stylesPath)
        .copy('resources/assets/js/*.*', scriptsPath)
        .copy('resources/assets/js/*.min.js', scriptsPath)
        .copy('resources/assets/skins/*', skinsPath)
        .copy('resources/assets/fonts/*.*', fontsPath)
        .copy('resources/assets/build/bootstrap-less/*.*', lessPath)
        .copy('resources/assets/build/bootstrap-less/mixins/*.*', lessPath)
        .copy('resources/assets/build/less/*.*', lessPath)
        .copy('resources/assets/build/less/skins/*.*', lessPath)

        .styles(['bootstrap.min.css'], stylesPath + 'bootstrap.default.min.css')
        .styles(['bootstrap.min.css', 'bootstrap-theme.min.css'], stylesPath + 'bootstrap.legacy.min.css')

        .scripts([
            'cms-timeago.js',
            'cms-restfulizer.js',
            'cms-carousel.js',
            'cms-alerts.js'
        ], scriptsPath + 'cms-main.js')
        .scripts([
            'cms-comment-core.js',
            'cms-comment-edit.js',
            'cms-comment-delete.js',
            'cms-comment-create.js',
            'cms-comment-fetch.js',
            'cms-comment-main.js'
        ], scriptsPath + 'cms-comment.js')

        .scripts([
            'jquery-3.1.1.min.js',
            'jquery.ui.widget.js',
            'jquery.fileupload.js',
            'jquery.timeago.min.js',
            'bootstrap.min.js',
            'jquery.iframe-transport.js',
            'select2.full.min.js',
            'fileupload-script.js',
            'moment.js',
            'daterangepicker.js',
            'bootstrap-datepicker.js',
            'select-script.js',
            'cms-timeago.js',
            'cms-restfulizer.js',
            'cms-carousel.js',
            'cms-alerts.js',
            'script.js'
        ], scriptsPath + 'all.js')

        .scripts([
            'jquery-ui.min.js',
            'morris.min.js',
            'raphael.min.js',
            'respond.min.js',
            'html5shiv.min.js',
            'jquery.sparkline.min.js',
            'jquery-jvectormap-1.2.2.min.js',
            'jquery-jvectormap-world-mill-en.js',
            'jquery.knob.js',
            'moment.js',
            'daterangepicker.js',
            'bootstrap-datepicker.js',
            'bootstrap3-wysihtml5.all.min.js',
            'jquery.slimscroll.min.js',
            'fastclick.min.js',
            'app.min.js'
        ], scriptsPath + 'admin.js')

        .styles([
            'font-awesome.min.css',
            'animate.min.css',
            'select2.min.css',
            'datepicker3.css',
            'daterangepicker.css',
            'cms-main.css',
            'style.css'
        ], stylesPath + 'all.css')

        .styles([
            'AdminLTE.min.css',
            '_all-skins.min.css',
            'blue.css',
            'morris.css',
            'jquery-jvectormap-1.2.2.css',
            'datepicker3.css',
            'daterangepicker.css',
            'bootstrap3-wysihtml5.min.css',
            'admin-style.css'
        ], stylesPath + 'admin.css')
        .version([stylesPath + 'all.css', scriptsPath + 'all.js']);
});
