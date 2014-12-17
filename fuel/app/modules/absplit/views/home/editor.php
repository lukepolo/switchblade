<?php
//    $url = "https://www.discountfilters.com/refrigerator-water-filters/lg-lt700p-3-pack/p176272/";
$url = 'http://ejjpeopleskills.com';
    echo Asset::css('loading.css'); 
    echo Asset::css('modules/absplit/main.css'); 
?>
<script type="text/javascript">
    // TODO - this will be set in PHP
    var variation_id = 1;
</script>
<div class="jarviswidget jarviswidget-sortable" id="absplit-editor" role="widget" style="">
    <header role="heading">
        <span id="redo-undo">
            <span id="undo-change" class="widget-icon"> <i class="fa fa-mail-reply"></i> </span>
            <span id="redo-change" class="widget-icon"> <i class="fa fa-mail-forward"></i> </span>
        </span>
        <h2></h2>
        <ul id="variation_list" class="nav nav-tabs pull-right in">
            <li>
                <a id="add_variation" href="javascript:void(0);">
                    <i class="fa fa-plus"></i> Add Variation
                </a>
            </li>
            <li data-variation-id="1" class="active">
                <a data-toggle="tab" href="site-viewer">
                    <i class="variation-type fa fa-desktop"></i> 
                    <i class="fa fa-close"></i>
                    <span class="variation-title">Variation 1</span> 
                    <i class="variation-title-edit fa fa-pencil" style="font-size:12px"></i>
                </a>
            </li>
            <li id="original">
                <a data-toggle="tab" href="#">
                   <i class="fa fa-ellipsis-h"></i> 
                   Original
               </a>
            </li>
        </ul>
        <span class="jarviswidget-loader">
            <i class="fa fa-refresh fa-spin"></i>
        </span>
    </header>
    <!-- widget div-->
    <div class="no-padding" role="content">
        <div class="widget-body">
            <!-- content -->
            <div class="tab-content">
                <span class='tetrominos'>
                    <div class='tetromino box1'></div>
                    <div class='tetromino box2'></div>
                    <div class='tetromino box3'></div>
                    <div class='tetromino box4'></div>
                </span>
                <div class="tab-pane active" id="site-viewer">
                    <iframe id="site-editor" class="iframe-edit" src="<?php echo Uri::Create('absplit/editor/url/').rawurlencode($url); ?>"></iframe>
                </div>
            </div>
            <!-- end content -->
            <div class="code_holder_open btn bg-color-blueDark txt-color-white">
                Edit Code
            </div>
        </div>
    </div>
    <!-- end widget div -->
</div>
<div id="code_holder" class="jarviswidget jarviswidget-color-blue widget-templates">
    <header role="heading">
        <div class="jarviswidget-ctrls" role="menu">
            <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Close">
                <i class="fa fa-times"></i>
            </a>
        </div>
        <span class="widget-icon">
            <i class="fa fa-pencil"></i>
        </span>
        <h2>Edit Javascript / JQuery</h2>
    </header>
    <!-- widget div-->
    <div role="content">
        <!-- end widget edit box -->
        <!-- widget content -->
        <div class="widget-body no-padding">
            <div class="summernote-no-tools"></div>
        </div>
    </div>
  <!-- end widget content -->
</div>
<!-- end widget div -->
<?php 
    // Including all the widgets
    echo \View::Forge('widgets/menu');
    echo \View::Forge('widgets/html_editor');
    echo \View::Forge('widgets/link_editor');
    echo \View::Forge('widgets/img_editor');
    echo \View::Forge('widgets/class_editor');
    echo \View::Forge('widgets/css_editor');
    echo \View::Forge('widgets/goal_creator');
    echo \View::Forge('widgets/resize_editor');
    echo \View::Forge('widgets/swap_editor');
    echo \View::Forge('widgets/moveto_editor');
    
    Casset::js('modules/absplit/*');
    
?>
<script type="text/javascript">
    $(document).ready(function()
    {
         $('.summernote-no-tools').summernote({
            toolbar: [
            ],
            height: 400,
            minHeight: 200,
            maxHeight: 500
        });
        
        // This can go to the template
        $('.summernote').summernote({
            height: 400   //set editable area's height
        });
    });
</script>