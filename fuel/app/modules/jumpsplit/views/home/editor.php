<?php
//    $url = "https://www.discountfilters.com/refrigerator-water-filters/lg-lt700p-3-pack/p176272/";
    $url = "http://lukepolo.com";
    $base_url = $url;
?>
<style>
/*    TODO*/
    .iframe-edit {
        width:100%;
    }
    #jumpsplit-editor .tab-content .tab-pane {
        height:100%;
    }
    .note-editable * {
        max-width: 80%;
    }
    
    #code_holder .note-editable {
        white-space: pre-wrap;
    }
    
    .jarviswidget .drag i {
        margin-left: 10px;
        vertical-align: text-top;
    }
    
    #jumpsplit-element-menu .ui-menu-title {
        cursor: inherit;
    }
    
    #code_holder {
        display: none;
    }
    #code_holder {
        position: fixed;
        bottom: -55px;
        right: 60px;
        left: 60px;
        z-index : 1000000;
    }
    
    .code_holder_open .active,
    .code_holder_open {
        position: absolute !important;
        top: inherit !important;
        left: inherit !important;
        bottom: -1px !important;
        right:60px !important;
        height:25px;
        width:100px;
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;
        /*        YAH FIND WHAT THIS SHOULD BE*/
        z-index : 1000000;
    }
</style>
<script type="text/javascript">
    var iframe_doc;
    var iframe_element;
    var element_tree = [];
    var pending_changes = {};
    var pending_changes_history = new Array();
    
    var test;
    
    var element_tags = {
        p: "Paragraph",
        div: "Division",
        h1: "Heading",
        h2: "Heading",
        h3: "Heading",
        h4: "Heading",
        h5: "Heading",
        h6: "Heading",
        a: "Link",
        ul: "Unordered List",
        ol: "Ordered List",
        li: "List Item",
        blockquote: "Blockquote",
        hr: "Horizontal Rule",
        img: "Image",
        i: "Italics",
        b: "Bold",
        title: "Title",
        form: "Form",
        input: "Input",
        select: "Selector",
        option: "Select Option",
        font: "Font",
        table: "Table",
        tr: "Table Row",
        td: "Table Division",
        th: "Table Heading",
        thead: "Table Header",
        tbody: "Table Body",
        tfooter: "Table Footer",
        header: "Header",
        footer: "Footer",
        body: "Body",
        small: "Small Text",
        pre: "Preformated Text",
        strike: "Striked Text",
        sub: "Subscript",
        frame: "Frame",
        iframe: "IFrame",
        textarea: "Textarea"
    };
    
    // ------------ WIDGETS ------------ //
    // Shows the menu 
    function jumpsplit_menu(element)
    {
        // Store element globally
        iframe_element = element;
        jumpsplit_widget_menu_position();
        
        // TODO
        // HACK -- we dont want the default menu classes
        $('#jumpsplit-element-menu .ui-menu-title').removeClass('ui-widget-content ui-menu-divider');
        
        var element_tag = $(iframe_element).prop('tagName').toLowerCase();
        
        var element_text = element_tags[element_tag] + ' <'+ element_tag + get_class_list('class', ' ') + '>';
        
        // Shows or hides the replace link in the menu
        if(element_tag == 'img')
        {
            $('#replace_img').show();
        }
        else
        {
            $('#replace_img').hide();
        }
        
        // Shows or hides the "link" link in the menu
        if(element_tag == 'a' || element_tag == 'img')
        {
            $('#link').show();
        }
        else
        {
            $('#link').hide();
        }
        
        // This emptys and places a default placeholder for showing all connected elements
        $('#select_container').empty().html('\
            <li id="no_connections" class="ui-menu-item" role="presentation">\
                <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">No Connecting Elements</a>\
            </li>\
        ');
        
        // Builds the element tree 
        // TODO - PUT INTO ITS OWN FUNCTION
        element_tree = [];
        var count = 0;
        $(iframe_element).parents().map(function() 
        {
            if(this.tagName != 'HTML' && this.tagName != 'BODY')
            {
                // remove the no connections
                $('#no_connections').remove();
                element_tree.push(this);
                
                if(this.id)
                {
                    var element_id = ' #'+this.id;
                }
                else
                {
                    element_id = '';
                }
                $('#select_container').append('\
                    <li class="ui-menu-item" role="presentation">\
                        <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem" data-id="' + count + '">'+ element_tags[this.tagName.toLowerCase()]+ " &lt;" + this.tagName.toLowerCase() + element_id + "&gt;" +'</a>\
                    </li>\
                ');
                count++;
            }
        });
        
        // Set the menu title
        $('#jumpsplit-element-menu .ui-menu-title').text(element_text).attr('title', element_text);
        
        // Hide rest of widgets
        $('.widget-templates').hide();
        $('#jumpsplit-element-menu').menu().show();
        
        // TODO - check bottom - top
        // Checks to see if the menu is outside of the view
        var left_pos = $(window).width() - $('#jumpsplit-element-menu').position().left;
        if(left_pos < 250)
        {
            $('#jumpsplit-element-menu').css('left', $('#jumpsplit-editor').width() - 350);
        }
        
        // Checks to see if the menu is outside of the view
        var right_pos = $('#jumpsplit-element-menu').position().left;
        if(right_pos < 250)
        {
            $('#jumpsplit-element-menu').css('left', 350);
        }
    }
    
    // Shows the HTML editor
    function jumpsplit_html_editor()
    {
        // Add the base to our template!
        $('head').append('<base href="<?php echo $base_url; ?>">');
        $('.widget-templates').hide();
        $('#jumpsplit-html-edit').show();
        
        $('#jumpsplit-html-edit .note-editable').html(iframe_element.outerHTML.replace('jumpsplit-border',''));
        
        // Reset positions
        jumpsplit_widget_positions();
    }
    
    function jumpsplit_link_editor()
    {
        
        $('.widget-templates').hide();
        $('#jumpsplit-link-editor').show();
        
        $('#jumpsplit-link-editor .href').val();
        $('#jumpsplit-link-editor .alt').val();
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
        
        // Build the CSS Values
        $('#css_widget input').each(function()
        {
            $(this).val($(iframe_element).css($(this).data('get')));
        });
        
        // Reset positions
        jumpsplit_widget_positions();
    }
    
    // ------------ END OF WIDGETS ------------ //
    
    // ------------ WIDGET INTERACTIONS ------------ //
    // 
    // 
    // Resets all menu / widget positions next to element
    function jumpsplit_widget_positions()
    {
        // TODO
        // detect if off page - dont allow
        $('.widget-templates:not(#jumpsplit-element-menu, #code_holder)').addClass('screen_center').draggable(
        {
            handle: '.drag', 
            cursor: "move",
            iframeFix: true,
            containment: "#content",
            start: function()
            {
                $('.widget-templates').removeClass('screen_center');
            }
        });
    }
    
     function jumpsplit_widget_menu_position()
    {
        // TODO
        // detect if off page - dont allow
        var menu_height = 120;
        $('#jumpsplit-element-menu').css('top', $('#site-editor').offset().top - menu_height + $(iframe_element).offset().top - $(iframe_doc).scrollTop()+'px').css('left', 10 + $('#site-editor').offset().left + $(iframe_element).offset().left + $(iframe_element).width()+'px');       
    }
    
    $(document).ready(function()
    {
        $('.iframe-edit').height($(window).height() - $('header').height() - $('.page-footer').height() - 215);
        
        $('#jumpsplit-close').on('click', function()
        {
            $('#jumpsplit-element-menu').hide();
            iframe_element = null;
        });
        
        $(window).on('resize scroll', function()
        {
            $('.iframe-edit').height($(window).height() - $('header').height() - $('.page-footer').height() - 215);
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
            iframe_window = $('#site-editor')[0].contentWindow;
            iframe_doc = $('#site-editor').contents()[0];
        });
        
        $(document).on('click', '#remove_element', function()
        {
            // Remove Element
            // TODO - Add to THEIR JS file
            $(iframe_element).remove();
            // Close menu
            $('#jumpsplit-close').click();
        });
        
        $(document).on('mouseover', '#select_container li a', function()
        {
            iframe_window.add_jumpsplit_border(element_tree[$(this).data('id')]);
        });
        
        $(document).on('click', '#select_container li a', function()
        {
           jumpsplit_menu(element_tree[$(this).data('id')]); 
        });
        
        $(document).on('click', '#resize_move', function()
        {
//            alert('really complicated ><');
            // TODO - create own custom draggable function
            $(iframe_element, iframe_window.document).resizable().draggable();
            $('#jumpsplit-close').click();
        });

        $('.cancel').click(function()
        {
            $(this).closest('.jarviswidget').find('.jarviswidget-delete-btn').click();
        });

        // TODO - these need to be "OK" buttons rather than save
        $('.save').click(function()
        {
            $(this).closest('.jarviswidget').find('.jarviswidget-delete-btn').click();
        });
        
        // Live Preview Changes
        $(document).on('keyup change', '.jarviswidget input:visible, #jumpsplit-html-edit .note-editor', function(e)
        {
            var path = $(iframe_element).getPath();
            switch($(this).closest('.jarviswidget').attr('id'))
            {
                case 'jumpsplit-classes':
                    // We dont want keyup for this , we want on change!
                    if(e.type != 'keyup')
                    {
                        // removing jumpsplit-border
                        $(path, iframe_doc).removeClass('jumpsplit-border');
                        add_changes(path, 'classes', "$('" + path + "').attr('class', '" + $(this).val().replace(/,/g, ' ') + "');", "$('" + path + "').attr('class', '" + $(path, iframe_doc).attr('class') + "');");
                      
                        // re-apply the jumpsplit-border
                        $(path, iframe_doc).addClass('jumpsplit-border');
                    }
                break;
                case 'jumpsplit-css':
                    add_changes(path, 'css:'+$(this).data('get'), "$('" + path + "').css('" + $(this).data('get') + "','" + $(this).val() +"');", "$('" + path + "').css('" + $(this).data('get') + "','" + $(path, iframe_doc).css($(this).data('get')) +"');");
                break;
                case 'jumpsplit-html-edit':
                    add_changes(path, 'html', "$('" + path + "').html('" + $(this).prev().code() +"');", null);
                break;
                default:
                    console.log('NO EVENT - '+ $(this).closest('.jarviswidget').attr('id'));
                break;
            }
            
            // Re-append all changes
            $('#code_holder .note-editable').html('');
            $(pending_changes).each(function(index, path_object)
            {
                $.each(path_object, function(path, type_object)
                {
                    $.each(type_object, function(index, data)
                    {
                        $('#code_holder .note-editable').text($('#code_holder .note-editable').text().trim()+'\n'+data.apply_function);
                        iframe_window.eval(data.apply_function);
                    });
                });
            });
        });
    });
    
        
    // ------------ END OF WIDGET INTERACTIONS ------------ //
    
    // Adds Changes to our list of changes 
    function add_changes(path, type, apply_function, revert_function)
    {
        if (!pending_changes[path])
        {
            pending_changes[path] = {};
        }
        
        pending_changes[path][type] = {
            path : path,
            apply_function: apply_function,
            revert_function: revert_function 
        }
        
        pending_changes_history.push({
            path : path,
            apply_function: apply_function,
            revert_function: revert_function,
        });
    }
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
        <span class="widget-icon"> <i class="fa fa-mail-reply"></i> </span>

        <span class="widget-icon"> <i class="fa fa-mail-forward"></i> </span>
        <h2></h2>
        <ul class="nav nav-tabs pull-right in">
            <li>
                <a href="javascript:void(0);">
                    <i class="fa fa-plus"></i> Add Variation
                </a>
            </li>
            <li class="active">
                <a data-toggle="tab" href="#variant-1"><i class="fa fa-desktop"></i> <span class="hidden-mobile hidden-tablet">Variation 1</span></a>
            </li>
            <li>
               <a data-toggle="tab" href="#original"><i class="fa fa-ellipsis-h"></i> <span class="hidden-mobile hidden-tablet">Original</span></a>
            </li>
        </ul>
        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span>
    </header>
    <!-- widget div-->
    <div class="no-padding" role="content">
        <div class="widget-body">
            <!-- content -->
            <div class="tab-content">
                <div class="tab-pane fade active in padding-10 no-padding-bottom" id="variant-1">
                    <iframe id="site-editor" class="iframe-edit" src="<?php echo Uri::Create('jumpsplit/editor/url/').rawurlencode($url); ?>"></iframe>
                </div>
                <!-- end s2 tab pane -->
                <div class="tab-pane fade" id="original">
                    <iframe src="<?php echo Uri::Create('jumpsplit/editor/url/').rawurlencode($url); ?>"></iframe>
                </div>
                <!-- end s3 tab pane -->
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

<script>
    $(document).ready(function()
    {
        $('.summernote-no-tools').summernote({
            toolbar: [
            ],
            height: 400,
            minHeight: 200,
            maxHeight: 500,
        });
        
        // HACK - to remove all default hidden html tags
        // TODO 
        $('#code_holder .note-editable').html('');
        
        $(document).on('click', '.code_holder_open', function()
        {
            $('#code_holder').show();
        });
    });
</script>
<?php echo \View::Forge('widgets/menu');?>
<?php echo \View::Forge('widgets/html_editor');?>
<?php echo \View::Forge('widgets/link_editor');?>
<?php echo \View::Forge('widgets/class_editor');?>
<?php echo \View::Forge('widgets/css_editor');?>

<?php

    echo Asset::js('css_path.js')
?>