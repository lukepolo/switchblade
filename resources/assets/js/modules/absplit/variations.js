// Adds a new variation
$(document).on('click', '#add_variation', function()
{
    // TODO - PHP needs to generate a variation_id!
    if(!variation_count)
    {
        variation_count = $('#variation_list li').length - 1;
    }
    else
    {
        variation_count++;
    }

    $('#variation_list .active').removeClass('active');

    $('#variation_list li:eq(1)').before(
        '<li data-variation-id="' + variation_count + '" class="active">\
            <a data-toggle="tab" href="#">\
                <i class="variation-type fa fa-desktop"></i> \
                <i class="fa fa-close"></i>\
                <span class="variation-title">Variation '+ variation_count +'</span> \
                <i class="variation-title-edit fa fa-pencil" style="font-size:12px"></i>\
            </a>\
        </li>'
    );

    $('#variation_list li.active').click();
});

// Chnages to a differnt variation and applys its changes in the pending changes
$(document).on('click', '#variation_list li', function()
{
    $('.cancel').first().click();
    close_menu();
    // UNDO ALL Changes first
    undo_changes();
    
    variation_id = $('#variation_list li.active').data('variation-id');
    $('.absplit-border', iframe_doc).removeClass('absplit-border');
    
    apply_changes();
});

// TODO - make it functional 
// Changes the document to a mobile document 
$(document).on('click', '#variation_list .active .variation-type', function()
{
    $(this).toggleClass('fa-desktop fa-mobile');
});

// Removes a variation
$(document).on('click', '#variation_list .fa.fa-close', function()
{
    $(this).closest('li').remove();
    if(!$('#variation_list .active'))
    {
        $('#variation_list li:eq(1) a').click();
    }
});

// Starts ending the title of the variation
$(document).on('click', '.variation-title-edit', function()
{       
    $(this).prev().attr('contenteditable', true).focus();
    $(this).prev().selectText();
});

// Stops editing the title of the variation
$(document).on('keydown blur', '.variation-title', function(e)
{
    if(e.which == 27 || e.which == 13 || e.type == 'focusout')
    {
        e.preventDefault();
        $(this).attr('contenteditable', false);
        window.getSelection().removeAllRanges();
    }
});