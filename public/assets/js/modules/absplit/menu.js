// MAIN SYSTEM MENU
// TODO - FIX CLOSING MENUS TO USE ONE FUNCTION TO MAKE IT ALOT EASIER
function absplit_menu(element)
{
    // Store element globally
    iframe_element = element;
    absplit_widget_menu_position();

    // TODO
    // HACK -- we dont want the default menu classes
    $('#absplit-element-menu .ui-menu-title').removeClass('ui-widget-content ui-menu-divider');

    // Gets the element tag to display a name for the user
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

    // Build and add selecting trees to navigate easily 
    build_tree('parent');
    build_tree('child');

    // Set the menu title
    $('#absplit-element-menu .ui-menu-title').text(element_text).attr('title', element_text);

    // Hide rest of widgets
    $('.widget-templates').hide();
    
    // Show the main menu
    $('#absplit-element-menu').menu().show();

    // TODO - check bottom - top
    // wrote a function todo this , need to determine which way to adjust though , probalby write another function todo it for me
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

// Builds child / parent tree for the type given
function build_tree(type)
{
    // This emptys and places a default placeholder for showing all connected elements
    $('#select_'+type).empty().html('\
        <li id="no_'+type+'" class="ui-menu-item" role="presentation">\
            <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">No '+ ucwords(type) +' Elements</a>\
        </li>\
    ');

    // Two differnt types of selectors, need to get the eval of the function
    if(type == 'parent')
    {
        var map_func = "$(iframe_element).parents()";
    }
    else
    {
        var map_func = "$(iframe_element).children()";
    }

    // Builds the element tree 
    var count = 0;
    
    element_tree[type] = new Array();
    // Find all the childre of parents
    eval(map_func).map(function() 
    {
        // We dont want them to select HTML or BODY
        if(this.tagName != 'HTML' && this.tagName != 'BODY')
        {
            // TODO - change this to a class instead
            // remove the no connections
            $('#no_'+type).remove();
            
            // Push the element into the tree array
            element_tree[type].push(this);

            // If the element has an ID show it
            if(this.id)
            {
                var element_id = ' #'+this.id;
            }
            else
            {
                element_id = '';
            }
            
            // Append to the menu visually
            $('#select_'+type).append('\
                <li class="ui-menu-item" role="presentation">\
                    <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem" data-type="' + type + '" data-id="' + count + '">'+ element_tags[this.tagName.toLowerCase()]+ " &lt;" + this.tagName.toLowerCase() + element_id + "&gt;" +'</a>\
                </li>\
            ');
            count++;
        }
    });
}

// Quickly close all menus
function close_menu()
{
    $('#absplit-close').click();
}

// Resets all menu / widget positions next to element
function absplit_widget_positions()
{
    $('.widget-templates:not(#absplit-element-menu, #code_holder):visible').addClass('screen_center').draggable(
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

// Moves the widgets to the center 
function absplit_widget_menu_position()
{
    // TODO
    // detect if off page - dont allow
    $('#absplit-element-menu').css('top', $('#site-editor').offset().top - menu_height + $(iframe_element).offset().top - $(iframe_doc).scrollTop()+'px').css('left', 10 + $('#site-editor').offset().left + $(iframe_element).offset().left + $(iframe_element).width()+'px');       
}

$('#absplit-close').on('click', function()
{
    $('#absplit-element-menu').hide();
});

$(document).on('mouseenter', '#select_parent li a, #select_child li a', function()
{
    iframe_window.add_absplit_border(element_tree[$(this).data('type')][$(this).data('id')]);
});

$(document).on('click', '#select_parent li a, #select_child li a', function()
{
    iframe_window.add_absplit_border(element_tree[$(this).data('type')][$(this).data('id')])
    absplit_menu(element_tree[$(this).data('type')][$(this).data('id')]); 
});

// Pressing the close button on the widgets will close current element
$('.jarviswidget-delete-btn').on('click', function()
{
    close_menu();
    if($(this).closest('.jarviswidget').attr('id') !=  'code_holder')
    {
        $('.cancel').first().click();
        $('#absplit-element-menu').show();
    }
    $(this).closest('.jarviswidget').hide();
});

function destroy_move_drag()
{
    $('.ui-resizeable-overlay', iframe_doc).remove();
    $('.ui-resizable', iframe_doc).draggable("destroy");
    $('.ui-resizable', iframe_doc).resizable("destroy");
}