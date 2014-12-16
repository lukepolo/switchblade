// GLOBAL Variables
var iframe_doc;
var iframe_element;
var element_tree = {
    parent: null,
    child: null
};
var pending_changes = {};
var pending_changes_history = {};
var pending_changes_history_index = {};
var variation_count;
var menu_height = 120;
var orginal_style;

var swap_item;

// Holds all the types of elements  
// TODO - need a function to check if it exists because it will become undefined if we dont 
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

var min_height = 215;
// When we resize the window we need to make sure the iframe element is correct
$(window).on('resize scroll', function()
{
    $('.iframe-edit').parent().height($(window).height() - $('header').height() - $('.page-footer').height() - min_height);
    $('.iframe-edit').height($(window).height() - $('header').height() - $('.page-footer').height() - min_height);
    if (iframe_element)
    {
        absplit_widget_positions();
    }
});

// Getting the iframe_document / and Show loading screen till we are ready
$('#site-editor').load(function()
{
    $('.iframe-edit').height($(window).height() - $('header').height() - $('.page-footer').height() - min_height);
    $(this).show();

    $('.tetrominos').hide();
    // Check to see if we can determine elements by elementFromPoint
    if (!document.elementFromPoint)
    {
        // Bummer
        alert('Your browser is incompatiable, features may not work as inteneded');
    }
    iframe_window = $('#site-editor')[0].contentWindow;
    iframe_doc = $('#site-editor').contents()[0];
    
    $(iframe_doc).on('click', '.absplit_swap_border', function()
    {
        var swap_item_path = $(iframe_element).getPath();
        
        swap_with = $('.absplit_swap_border', iframe_doc);
        
        var swap_with_path = $(swap_with).getPath();
        
        // the apply and revert function are going to be the same cause they deal with paths
        
        apply_revert = new Array(
            'var clone_1 = $("'+ swap_item_path +'").clone().attr("data-absplit-swap", "1a");',
            'var clone_2 = $("'+ swap_with_path +'").clone().attr("data-absplit-swap", "1b");',
            '$("'+ swap_with_path +'").after(clone_1);',
            '$("'+ swap_item_path +'").replaceWith(clone_2);',
            '$("'+ swap_with_path +'").remove();'
        );

        revert_function = new Array(
            'var clone_1 = $(\'[data-absplit-swap="1a"]\').clone().attr("data-absplit-swap", "");',
            'var clone_2 = $(\'[data-absplit-swap="1b"]\').clone().attr("data-absplit-swap", "");',
            '$(\'[data-absplit-swap="1a"]\').after(clone_2);',
            '$(\'[data-absplit-swap="1b"]\').replaceWith(clone_1);',
            '$(\'[data-absplit-swap="1a"]\').remove()'
        );

        // Added // to the end of the "revert" function is they must be diff to register
        add_changes(swap_item_path, 'swap', apply_revert, revert_function);
        
        $('body', iframe_doc).removeClass('absplit_swap');
        $('.absplit_swap_border', iframe_doc).removeClass('absplit_swap_border');
    });
});

// Shows the code editor
$(document).on('click', '.code_holder_open', function()
{
    $('#code_holder').show();
});

// We need this for the parent right away
$('.iframe-edit').parent().height($(window).height() - $('header').height() - $('.page-footer').height() - min_height);

// HACK - to remove all default hidden html tags
// TODO 
$('#code_holder .note-editable').html('');