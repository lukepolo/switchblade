<style>
    .iframe-edit {
        width:100%;
        height:100%;
    }
    #jumpsplit-editor .tab-content .tab-pane {
        height:100%;
    }
</style>
<script>
    var iframe_doc;
    var iframe_element;
    
    // ------------ WIDGETS ------------ //
    // Shows the menu 
    function jumpsplit_menu(element)
    {
        // Store element globally
        iframe_element = element;
        jumpsplit_widget_positions();
        
        // HACK -- we dont want the default menu classes
        $('#jumpsplit-element-menu .ui-menu-title').removeClass('ui-widget-content ui-menu-divider');
        
        var element_text = '<'+$(iframe_element).prop('tagName').toLowerCase() + get_class_list('class', ' ')+'>';
        
        // Set the menu title
        $('#jumpsplit-element-menu .ui-menu-title').text(element_text).attr('title', element_text);
        
        // Hide rest of widgets
        $('.widget-templates').hide();
        $('#jumpsplit-element-menu').menu().show();
    }
    
    // Shows the HTML editor
    function jumpsplit_html_editor()
    {
        $('.widget-templates').hide();
        $('#jumpsplit-html-edit').show();
        
        $('#jumpsplit-html-edit .note-editable').html(iframe_element.outerHTML.replace('jumpsplit-border',''));
        
        // Reset positions
        jumpsplit_widget_positions();
    }
    
    // Shows the Classes editor
    function jumpsplit_classes_editor()
    {
        $('.widget-templates').hide();
        $('#jumpsplit-classes').show();
        
        default_classes = get_class_list(null, ',');
        
        $("#class_selector").val(default_classes.split(',')).trigger("change");
        $("#class_selector").select2({placeholder: 'Select or Enter a Class', tags:default_classes.split(',')});
        
        // Reset positions
        jumpsplit_widget_positions();
    }
    
    // Shows the CSS editor
    function jumpsplit_css_editor()
    {
        $('.widget-templates').hide();
        $('#jumpsplit-css').show();
        
        // Reset positions
        jumpsplit_widget_positions();
    }
    
    // ------------ END OF WIDGETS ------------ //
    
    // ------------ WIDGET INTERACTIONS ------------ //
    // Resets all menu / widget positions next to element
    function jumpsplit_widget_positions()
    {
        // TODO
        // make dragable
        // detect if off page
        $('.widget-templates').css('top', $('#site-editor').offset().top + $(iframe_element).offset().top - $(iframe_doc).scrollTop()+'px').css('left', 10 + $('#site-editor').offset().left + $(iframe_element).offset().left + $(iframe_element).width()+'px')
    }
    
    $(document).ready(function()
    {
        $('#jumpsplit-close').on('click', function()
        {
            $('#jumpsplit-element-menu').hide();
            iframe_element = null;
        });
        
        $(window).on('resize scroll', function()
        {
            if (iframe_element)
            {
                jumpsplit_widget_positions();
            }
        });
         
        // Pressing the close button on the widgets will close current element
        $('.jarviswidget-delete-btn').on('click', function()
        {
            $('#jumpsplit-element-menu').show();
            $(this).closest('.jarviswidget').hide()
        });
        
        // This can go to the template
        $('.summernote').summernote({
            height: 400,   //set editable area's height
        });
        
        // Getting the iframe_document
        $('#site-editor').load(function()
        {
            // Check to see if we can determine elements by elementFromPoint
            if (!document.elementFromPoint)
            {
                alert('Your browser is incompatiable, features may not work as inteneded');
            }
            iframe_doc = $('#site-editor').contents()[0];
        });
    });
        
    // ------------ END OF WIDGET INTERACTIONS ------------ //
     
    // Gets the class list of the current element
    function get_class_list(wrap, seperator)
    {
        var class_list = '';
        if ($(iframe_element).attr('class') != 'jumpsplit-border')
        {
            var classes = $(iframe_element).attr('class').split(/\s+/);
            $.each(classes, function(index, item)
            {
                if (item != 'jumpsplit-border')
                {
                    class_list = class_list + item + seperator; 
                }
            });
            
            // trim the seperator
            class_list = class_list.replace(new RegExp('('+seperator+'$)',"g"), "");
            if (wrap)
            {
                class_list = " " + wrap + '="' + class_list + '"';
            }
        }
        else
        {
            class_list = "";
        }
        return class_list;
    }
    
    // Get an element respect to the iframe based on current x / y coords
    function jumpsplit_get_element(mouse_x, mouse_y)
    {
        if($(iframe_doc).scrollTop() > 0)
        {
            // TOP relative
            mouse_y = mouse_y - $(iframe_doc).scrollTop();
        }
        else if($(iframe_doc).scrollLeft() > 0 )
        {
            // TODO
            // MAKE SURE IT WORKS
            mouse_x = mouse_x - $(iframe_doc).scrollTop();
        }
        
        return iframe_doc.elementFromPoint(mouse_x, mouse_y);
    }
</script>

<div class="jarviswidget jarviswidget-sortable" id="jumpsplit-editor" role="widget" style="">
    <header role="heading">
        <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
        <h2> </h2>
        <ul class="nav nav-tabs pull-right in">
            <li class="active">
                <a data-toggle="tab" href="#editor"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">Editor</span></a>
            </li>
            <li>
                <a data-toggle="tab" href="#tab_2"><i class="fa fa-facebook"></i> <span class="hidden-mobile hidden-tablet">Tab2</span></a>
            </li>
            <li>
               <a data-toggle="tab" href="#tab_3"><i class="fa fa-dollar"></i> <span class="hidden-mobile hidden-tablet">Tab3</span></a>
            </li>
        </ul>
        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span>
    </header>
    <!-- widget div-->
    <div class="no-padding" role="content">
        <div class="widget-body">
            <!-- content -->
            <div class="tab-content">
                <div class="tab-pane fade active in padding-10 no-padding-bottom" id="editor">
                    <?php
//                        $url = "https://www.discountfilters.com/refrigerator-water-filters/lg-lt700p-3-pack/p176272/";
                        $url = "http://lukepolo.com";
                    ?>
                    <iframe id="site-editor" class="iframe-edit" src="<?php echo Uri::Create('jumpsplit/editor/url/').rawurlencode($url); ?>"></iframe>
                </div>
                <!-- end s1 tab pane -->
                <div class="tab-pane fade" id="tab_2">
                    2
                </div>
                <!-- end s2 tab pane -->
                <div class="tab-pane fade" id="tab_3">
                    3
                </div>
                <!-- end s3 tab pane -->
            </div>
            <!-- end content -->
        </div>
    </div>
    <!-- end widget div -->
</div>
<?php echo \View::Forge('widgets/menu');?>
<?php echo \View::Forge('widgets/html_editor');?>
<?php echo \View::Forge('widgets/classes_editor');?>
<?php echo \View::Forge('widgets/css_editor');?>