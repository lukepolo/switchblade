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
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
        <?php
            // CSS FILES
            Casset::css('bootstrap.css');
            Casset::css('base.css');
            Casset::css('font-awesome.css');
	    Casset::css('smartadmin-production-plugins.css');
            Casset::css('smartadmin-production.css');
	    Casset::css('smartadmin-skins.css');
            echo Casset::render_css();
        ?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.js"></script>
    </head>
    <body class="menu-on-top">
        <?php echo $header; ?>
        <div id="main" role="main">
            <!--[if IE 8]>
                <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
            <![endif]-->
            <div id="content" class="<?php echo $container; ?>">
                <?php 
                    if(isset($content) === true)
                    {
                        echo $content;
                    }
                ?>
            </div>
        </div>
        <footer>
            <?php echo $footer; ?>
        </footer>

    
        <!--<script data-pace-options='{ "restartOnRequestAfter": true }' src="/js/plugin/pace/pace.js"></script>-->
        <?php
            // JS FILES Here
            // HACK
            // http://stackoverflow.com/questions/5670193/how-to-resize-elements-inside-iframe-with-jquery-resizable#answer-6219607
            Casset::js('jquery-ui-modifed.js');
            
	    Casset::js('bootstrap.js');
	    
	    // Main APP JS
            Casset::js('app.config.js');
            Casset::js('app.js');
	    
	    // SELECT2
            Casset::js('select2.min.js');
	    
            // JS TOUCH : include this plugin for mobile drag / drop touch events
            Casset::js('plugin/jquery-touch/jquery.ui.touch-punch.js');
            
            // CUSTOM NOTIFICATION
            Casset::js('notification/SmartNotification.js');
            // JARVIS WIDGETS
            Casset::js('smartwidgets/jarvis.widget.js');
            // EASY PIE CHARTS
            Casset::js('plugin/easy-pie-chart/jquery.easy-pie-chart.js');
            // SPARKLINES
            Casset::js('plugin/sparkline/jquery.sparkline.js');
            // JQUERY VALIDATE
            Casset::js('plugin/jquery-validate/jquery.validate.js');
            // JQUERY MASKED INPUT 
            Casset::js('plugin/masked-input/jquery.maskedinput.js');
            // BOOTSTRAP COLORPICKER
            Casset::js('plugin/colorpicker/bootstrap-colorpicker.js');
            //JQUERY UI + Bootstrap Slider
            Casset::js('plugin/bootstrap-slider/bootstrap-slider.js');
	    // SUMMER NOTE
	    Casset::js('plugin/summernote/summernote.js');
            // FastClick
            Casset::js('plugin/fastclick/fastclick.js');
            // Voice
            Casset::js('speech/voicecommand.js');
            
            echo Casset::render_js();
        ?>
	<script>
	    $(document).ready(function()
	    {
		// Generate all selects as select2
		$('select:not(.no_select2)').each(function()
                {
                    $(this).prepend('<option selected="selected"></option>');
                    $(this).select2();
                });
	    });
	</script>
        <?php echo $php_session_errors; ?>
    </body>
</html>