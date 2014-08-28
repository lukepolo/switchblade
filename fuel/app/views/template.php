<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
            
        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <title><?php echo $title?></title>
        
        <!-- #GOOGLE FONT -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
        <?php
            // CSS FILES
            Casset::css('bootstrap.min.css');
            Casset::css('base.css');
            Casset::css('font-awesome.min.css');
            Casset::css('smartadmin-production.min.css');
            Casset::css('smartadmin-skins.min.css');
            echo Casset::render_css();
        ?>
    </head>
	<!--  Add smart styles  smart-style-* -->
    <body class="menu-on-top">
        <?php echo $header; ?>
        <?php echo $navigation; ?>
        <div id="main" role="main">
            <?php echo $ribbon; ?>
            <!--[if IE 8]>
                <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
            <![endif]-->
            <?php echo $content; ?>
        </div>
        <footer>
            <?php echo $footer; ?>
        </footer>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <!--<script data-pace-options='{ "restartOnRequestAfter": true }' src="/js/plugin/pace/pace.min.js"></script>-->
        <?php
            // JS FILES Here
            Casset::js('app.config.js');
	    // JS TOUCH : include this plugin for mobile drag / drop touch events
            Casset::js('plugin/jquery-touch/jquery.ui.touch-punch.min.js');
            Casset::js('bootstrap.min.js');
            // CUSTOM NOTIFICATION
            Casset::js('notification/SmartNotification.min.js');
            // JARVIS WIDGETS
            Casset::js('smartwidgets/jarvis.widget.min.js');
            // EASY PIE CHARTS
            Casset::js('plugin/easy-pie-chart/jquery.easy-pie-chart.min.js');
            // SPARKLINES
            Casset::js('plugin/sparkline/jquery.sparkline.min.js');
            // JQUERY VALIDATE
            Casset::js('plugin/jquery-validate/jquery.validate.min.js');
            // JQUERY MASKED INPUT 
            Casset::js('plugin/masked-input/jquery.maskedinput.min.js');
            // SELECT2
            Casset::js('select2.min.js');
            //JQUERY UI + Bootstrap Slider
            Casset::js('plugin/bootstrap-slider/bootstrap-slider.min.js');
            // browser msie issue fix
            Casset::js('plugin/msie-fix/jquery.mb.browser.min.js');
            // FastClick: For mobile devices: you can disable this in app.js
            Casset::js('plugin/fastclick/fastclick.min.js');
            // Main APP JS
            Casset::js('app.min.js');
            // Voice
            Casset::js('speech/voicecommand.min.js');
            
            echo Casset::render_js();
        ?>
    </body>
</html>