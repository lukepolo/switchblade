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

// Shows the goal creator
function absplit_goal_creator()
{
    $('.widget-templates').hide();
    $('#absplit-goal-creator').show();

    // Reset positions
    absplit_widget_positions();
}

// Shows the img editor 
function absplit_img_editor()
{
    $('.widget-templates').hide();
    $('#absplit-img-editor').show();

    $('#absplit-img-editor input').val('');
    $('#absplit-img-editor #img_preview').attr('src', '');

    // Reset positions
    absplit_widget_positions();
}

// Shows the link editor
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

// Trigger states allows the user to view a state that they usually cannnot do other wise
// ex. hovering without actually hovering over the element
$(document).on('click', '#trigger_states a', function()
{
    // TODO a better way than just doing the parent?
    // TODO - allow for JS hovers? 
//        iframe_window.$(iframe_element).trigger('mouseover').off();
    $(iframe_element, iframe_doc).addClass('absplit-'+ $(this).data('type'));
    $(iframe_element, iframe_doc).parent().addClass('absplit-'+ $(this).data('type'));
    $(iframe_element, iframe_doc).toggleClass('absplit-locked');
    $(iframe_element, iframe_doc).parent().toggleClass('absplit-locked');
});

// Removes the element from the dom
$(document).on('click', '#remove_element', function()
{
    var path = $(iframe_element).getPath();

    add_changes(path, 'visibility', "$('" + path + "').css('visibility', 'hidden');", "$('" + path + "').css('visibility', 'visible');");

    // Close menu
    close_menu();
    
    // TODO - save imediatleys
});

// Drag and Resize
$(document).on('click', '#resize_move', function()
{
    $('#absplit-resize-editor').show();
    
    $(iframe_element, iframe_doc).resizable({
        handles: "n, e, s, w, ne, se, sw, nw"
    }).draggable({
        start: function() {
            $('#absplit-resize-editor').hide();
        },   
        stop: function() {
            $('#absplit-resize-editor').show();
        }
    });
    
    $('.ui-resizable', iframe_doc).append('<div class="ui-resieable-overlay"></div>');
    close_menu();
});