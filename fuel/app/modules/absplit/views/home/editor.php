<?php
//    $url = "https://www.discountfilters.com/refrigerator-water-filters/lg-lt700p-3-pack/p176272/";
    $url = "http://lukepolo.com";
    $base_url = $url;
    echo Asset::css('loading.css'); 
?>
<style>   
    /*    TODO - Put into proper CSS files need to ask Janice how she wants it done     */
    #redo-undo .widget-icon {
        padding-right: 10px;
        cursor: pointer;
    }
    
    .fa  {
        cursor: pointer;
    }
    
    #site-editor {
        display: none;
    }
    
    .ui-menu-item {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .iframe-edit {
        width:100%;
    }
    #absplit-editor .tab-content .tab-pane {
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
    
    #absplit-element-menu .ui-menu-title {
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
    .form-horizontal {
        margin-left:-50px;
    }
    
    // Needs to be GLOBAL
    div[role="content"]:not(.no-padding) .widget-footer {
        margin-left: -12px;
        margin-right: -12px;
    }
</style>
<script type="text/javascript">
    var iframe_doc;
    var iframe_element;
    var element_tree = [];
    var pending_changes = {};
    var pending_changes_history = new Array();
    var pending_changes_history_index = null;
    
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
    function absplit_menu(element)
    {
        // Store element globally
        iframe_element = element;
        absplit_widget_menu_position();
        
        // TODO
        // HACK -- we dont want the default menu classes
        $('#absplit-element-menu .ui-menu-title').removeClass('ui-widget-content ui-menu-divider');
        
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
        element_tree = new Array();
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
        $('#absplit-element-menu .ui-menu-title').text(element_text).attr('title', element_text);
        
        // Hide rest of widgets
        $('.widget-templates').hide();
        $('#absplit-element-menu').menu().show();
        
        // TODO - check bottom - top
        // Checks to see if the menu is outside of the view
        var left_pos = $(window).width() - $('#absplit-element-menu').position().left;
        if(left_pos < 250)
        {
            $('#absplit-element-menu').css('left', $('#absplit-editor').width() - 350);
        }
        
        // Checks to see if the menu is outside of the view
        var right_pos = $('#absplit-element-menu').position().left;
        if(right_pos < 250)
        {
            $('#absplit-element-menu').css('left', 350);
        }
    }
    
    function close_menu()
    {
        $('#absplit-close').click();
    }
    
    // Shows the HTML editor
    function absplit_html_editor()
    {
        // Add the base to our template!
        $('head').append('<base href="<?php echo $base_url; ?>">');
        $('.widget-templates').hide();
        $('#absplit-html-edit').show();
        
        $('#absplit-html-edit .note-editable').html(iframe_element.outerHTML.replace('absplit-border',''));
        
        // Reset positions
        absplit_widget_positions();
    }
    
    function absplit_goal_creator()
    {
        $('.widget-templates').hide();
        $('#absplit-goal-creator').show();
        
        // Reset positions
        absplit_widget_positions();
    }
    
    function absplit_img_editor()
    {
        $('.widget-templates').hide();
        $('#absplit-img-editor').show();
      
        $('#absplit-img-editor input').val('');
        $('#absplit-img-editor #img_preview').attr('src', '');
        
        // Reset positions
        absplit_widget_positions();
    }
    
    function absplit_link_editor()
    {
        $('.widget-templates').hide();
        $('#absplit-link-editor').show();
        
        if(iframe_element.src != null)
        {
            var link = iframe_element.src;
        }
        else
        {
            var link = iframe_element.href;
        }
        
        $('#absplit-link-editor .link').val(link);

        $('#absplit-link-editor .alt').val(iframe_element.alt);
        
        // Reset positions
        absplit_widget_positions();
    }
    
    // Shows the Classes editor
    function absplit_classes_editor()
    {
        $('.widget-templates').hide();
        $('#absplit-classes').show();
        
        default_classes = get_class_list(null, ',');
        
        $("#class_selector").val(default_classes.split(',')).trigger("change");
        $("#class_selector").select2({placeholder: 'Select or Enter a Class', tags:default_classes.split(',')});
        
        // Reset positions
        absplit_widget_positions();
    }
    
    // Shows the CSS editor
    function absplit_css_editor()
    {
        $('.widget-templates').hide();
        $('#absplit-css').show();
        
        // Build the CSS Values
        $('#css_widget input').each(function()
        {
            $(this).val($(iframe_element).css($(this).data('get')));
        });
        
        // Reset positions
        absplit_widget_positions();
    }
    
    // ------------ END OF WIDGETS ------------ //
    
    // ------------ WIDGET INTERACTIONS ------------ //
    // 
    // 
    // Resets all menu / widget positions next to element
    function absplit_widget_positions()
    {
        // TODO
        // detect if off page - dont allow
        $('.widget-templates:not(#absplit-element-menu, #code_holder)').addClass('screen_center').draggable(
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
    
    function absplit_widget_menu_position()
    {
        // TODO
        // detect if off page - dont allow
        var menu_height = 120;
        $('#absplit-element-menu').css('top', $('#site-editor').offset().top - menu_height + $(iframe_element).offset().top - $(iframe_doc).scrollTop()+'px').css('left', 10 + $('#site-editor').offset().left + $(iframe_element).offset().left + $(iframe_element).width()+'px');       
    }
    
    $(document).ready(function()
    {
        $('.iframe-edit').height($(window).height() - $('header').height() - $('.page-footer').height() - 215);
        
        $('#absplit-close').on('click', function()
        {
            $('#absplit-element-menu').hide();
            iframe_element = null;
        });
        
        $(window).on('resize scroll', function()
        {
            $('.iframe-edit').parent().height($(window).height() - $('header').height() - $('.page-footer').height() - 215);
            $('.iframe-edit').height($(window).height() - $('header').height() - $('.page-footer').height() - 215);
            if (iframe_element)
            {
                absplit_widget_positions();
            }
        });
         
        // Pressing the close button on the widgets will close current element
        $('.jarviswidget-delete-btn').on('click', function()
        {
            console.log($(this).closest('.jarviswidget').attr('id'));
            if($(this).closest('.jarviswidget').attr('id') !=  'code_holder')
            {
                $('#absplit-element-menu').show();
            }
            $(this).closest('.jarviswidget').hide();
            
        });
        
        // This can go to the template
        $('.summernote').summernote({
            height: 400,   //set editable area's height
        });
        
        // Getting the iframe_document
        $('#site-editor').load(function()
        {
            $(this).show();
            $('.tetrominos').hide();
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
            var path = $(iframe_element).getPath();
            
            add_changes(path, 'visibility', "$('" + path + "').css('visibility', 'hidden');", "$('" + path + "').css('visibility', 'visible');");
            
            // Close menu
            close_menu();
        });
        
        $(document).on('mouseenter', '#select_container li a', function()
        {
            iframe_window.add_absplit_border(element_tree[$(this).data('id')]);
        });
        
        $(document).on('click', '#select_container li a', function()
        {
            iframe_window.add_absplit_border(element_tree[$(this).data('id')])
            absplit_menu(element_tree[$(this).data('id')]); 
        });
        
        $(document).on('click', '#resize_move', function()
        {
            // Resizing is a bitch.....so is draggging.......
            // TODO - create own custom draggable function
            $(iframe_element, iframe_doc).resizable().draggable();
            close_menu();
        });

        $('.cancel').click(function()
        {
            // Remove all pending 
            $(pending_changes).each(function(index, path_object)
            {
                $.each(path_object, function(path, type_object)
                {
                    $.each(type_object, function(type, data)
                    {
                        if(data.pending == true)
                        {
                            // find oldest pending history
                            $(pending_changes_history).each(function(index, data_history)
                            {
                                if(data_history.path == path && data_history.type == type && data_history.pending == true)
                                {
                                    iframe_window.eval(data_history.revert_function);
                                    return false;
                                }
                            });
                            delete pending_changes[path][type];
                        }
                    });
                });
            });
            
            // Set all histroy pending to false
            $(pending_changes_history).each(function(index, data_history)
            {
                if(data_history.pending == true)
                {
                    data_history.pending = false;
                }
            });
                            
            apply_changes();
            $(this).closest('.jarviswidget').find('.jarviswidget-delete-btn').click();
        });

        // TODO - these need to be "OK" buttons rather than save
        $('.save').click(function()
        {
            // Remove all pending 
            $(pending_changes).each(function(index, path_object)
            {
                $.each(path_object, function(path, type_object)
                {
                    $.each(type_object, function(type, data)
                    {
                        data.pending = false;
                    });
                });
            });
            $(this).closest('.jarviswidget').find('.jarviswidget-delete-btn').click();
        });
        
        // Live Preview Changes
        $(document).on('keyup change', '.jarviswidget input:visible, #absplit-html-edit .note-editor', function(e)
        {
            var path = $(iframe_element).getPath();
            switch($(this).closest('.jarviswidget').attr('id'))
            {
                case 'absplit-classes':
                    // We dont want keyup for this , we want on change!
                    if(e.type != 'keyup')
                    {
                        add_changes(path, 'classes', "$('" + path + "').attr('class', '" + $(this).val().replace(/,/g, ' ') + "');", "$('" + path + "').attr('class', '" + get_class_list(null, ' ') + "');");
                    }
                break;
                case 'absplit-css':
                    add_changes(path, 'css:'+$(this).data('get'), "$('" + path + "').css('" + $(this).data('get') + "','" + $(this).val() +"');", "$('" + path + "').css('" + $(this).data('get') + "','" + $(path, iframe_doc).css($(this).data('get')) +"');");
                break;
                case 'absplit-html-edit':
                    add_changes(path, 'html', "$('" + path + "').html('" + $(this).prev().code() +"');", "$('" + path + "').html('" + $(iframe_element).html() +"');");
                break;
                case 'absplit-link-editor':
                    //TODO
                    // need to say if its  a link or a src
                    add_changes(path, 'src', "$('" + path + "').attr('src', '" + $(this).val() +"');", "$('" + path + "').attr('src', '" + $(iframe_element).attr('src') +"');");
                break;
                case 'absplit-img-editor':
                    // wait for the upload to complete
                    $.each(event.target.files, function(index, file) 
                    {
                        var reader = new FileReader();

                        reader.onload = function(event)
                        {  
                            data = event.target.result;
                            $('#img_preview').attr('src', data);
                            file_data = data;
                            add_changes(path, 'src', "$('" + path + "').attr('src', '" + file_data +"');", "$('" + path + "').attr('src', '" + $(iframe_element).attr('src') +"');")
                        };  

                        reader.readAsDataURL(file);
                    });
                    
                break;
                default:
                    console.log('NO EVENT - '+ $(this).closest('.jarviswidget').attr('id'));
                break;
            }
        });
    });
    
        
    // ------------ END OF WIDGET INTERACTIONS ------------ //
    
    // Adds Changes to our list of changes 
    function add_changes(path, type, apply_function, revert_function)
    {
        // It can happen if they move quickly and the JS cannot keep up with the user
        if(apply_function == revert_function)
        {
            return;
        }
        // We need to modify our code if we have a diff history index
        if(pending_changes_history_index)
        {
            $(pending_changes_history).slice(pending_changes_history_index).each(function(index, data)
            {
                // remove from pending_changes array
                delete pending_changes[data.path][data.type];
            });
            
            // remove all history beyond the index
            pending_changes_history = pending_changes_history.splice(0, pending_changes_history_index);
            pending_changes_history_index = null;
        }
        
        if (!pending_changes[path])
        {
            pending_changes[path] = {};
        }
        
        pending_changes[path][type] = {
            path : path,
            apply_function: apply_function,
            revert_function: revert_function,
            temp_removed: false,
            pending : true
        }
        
        pending_changes_history.push({
            path : path,
            type: type,
            apply_function: apply_function,
            revert_function: revert_function,
            pending: true
        });
        
       apply_changes();
    }
    
    function apply_changes()
    {
        // Re-append all changes
        $('#code_holder .note-editable').html('<generated_code></genderated_code>');
        $(pending_changes).each(function(index, path_object)
        {
            $.each(path_object, function(path, type_object)
            {
                $.each(type_object, function(type, data)
                {
                    if(data.temp_removed == false)
                    {
                        $('#code_holder .note-editable generated_code').text($('#code_holder .note-editable').text().trim()+'\n'+data.apply_function);
                        iframe_window.eval(data.apply_function);
                    }
                });
            });
        });
    }
    // Gets the class list of the current element
    function get_class_list(wrap, seperator)
    {
        var class_list = '';
        var classes = $(iframe_element).attr('class').split(/\s+/);
        $.each(classes, function(index, item)
        {
            if (!item.match(/absplit-/))
            {
                class_list = class_list + item + seperator; 
            }
        });
            
        if (class_list)
        {
            // trim the seperator
            class_list = class_list.replace(new RegExp('('+seperator+'$)',"g"), "");
            if (wrap)
            {
                class_list = " " + wrap + '="' + class_list + '"';
            }
        }
       
        return class_list;
    }
    
    // Get an element respect to the iframe based on current x / y coords
    function absplit_get_element(mouse_x, mouse_y)
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
    
    function set_history_index()
    {
        if(pending_changes_history_index == null)
        {
            pending_changes_history_index = pending_changes_history.length;
        }
    }
    
    $(document).on('click', '#redo-change', function()
    {
        set_history_index();
        if(pending_changes_history_index != pending_changes_history.length)
        {
            var history = $(pending_changes_history)[pending_changes_history_index++];
            pending_changes[history.path][history.type].temp_removed = false;
            iframe_window.eval(history.apply_function);
            apply_changes();
        }
        
        // TODO
        // Need to reload their current WINDOWS content
    });
    
    $(document).on('click', '#undo-change', function()
    {
        set_history_index();
        if(pending_changes_history_index != 0)
        {
            var history = $(pending_changes_history)[--pending_changes_history_index]
            pending_changes[history.path][history.type].temp_removed = true;
            iframe_window.eval(history.revert_function);
            apply_changes();
        }
        
        // TODO
        // Need to reload their current WINDOWS content
    });
    
    $(document).on('click', '#trigger_states a', function()
    {
        $(iframe_element, iframe_doc).addClass('absplit-'+ $(this).data('type'));
        $(iframe_element, iframe_doc).toggleClass('absplit-locked');
    });
    
    
    $(document).on('keydown blur', '.variation-title', function(e)
    {
        if(e.which == 13 || e.type == 'focusout')
        {
            e.preventDefault();
            $(this).attr('contenteditable', false);
            window.getSelection().removeAllRanges();
        }
    });
    
    $(document).on('click', '.variation-title-edit', function()
    {
        $(this).prev().attr('contenteditable', true).focus();
        $(this).prev().selectText();
    });
    
        
    $(document).on('click', '#add_variation', function()
    {
        variation_count = $('#variation_list li').length - 1;

        // TODO - Generate a new variation ID
        variation_id = variation_count; // TEMP
        
        $('#variation_list .active').removeClass('active');
        
        $('#variation_list li:eq(1)').before(
            '<li class="active">\
                <a data-toggle="tab" href="#">\
                    <i class="fa fa-desktop"></i> \
                    <span class="variation-title">Variation '+ variation_count +'</span> \
                    <i class="variation-title-edit fa fa-pencil" style="font-size:12px"></i>\
                </a>\
            </li>'
        );
    });
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
            <li class="active">
                <a data-toggle="tab" href="site-viewer">
                    <i class="fa fa-desktop"></i> 
                    <span class="variation-title">Variation 1</span> 
                    <i class="variation-title-edit fa fa-pencil" style="font-size:12px"></i>
                </a>
            </li>
            <li>
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
    $('.iframe-edit').parent().height($(window).height() - $('header').height() - $('.page-footer').height() - 250);
</script>
<?php 
    // Including all the widgets
    echo \View::Forge('widgets/menu');
    echo \View::Forge('widgets/html_editor');
    echo \View::Forge('widgets/link_editor');
    echo \View::Forge('widgets/img_editor');
    echo \View::Forge('widgets/class_editor');
    echo \View::Forge('widgets/css_editor');
    echo \View::Forge('widgets/goal_creator');
    echo Asset::js('css_path.js');
?>

<script>
    jQuery.fn.selectText = function(){
        var doc = document;
        var element = this[0];
        console.log(this, element);
        if (doc.body.createTextRange) {
            var range = document.body.createTextRange();
            range.moveToElementText(element);
            range.select();
        } else if (window.getSelection) {
            var selection = window.getSelection();        
            var range = document.createRange();
            range.selectNodeContents(element);
            selection.removeAllRanges();
            selection.addRange(range);
        }
     };
</script>