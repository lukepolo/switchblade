// Adds Changes to our list of changes 
function add_changes(path, type, apply_function, revert_function)
{
    console.log('adding changes!');
    // It can happen if they move quickly and the JS cannot keep up with the user
    // We also don't need to repeat any of the same apply functions if they type to fast
    if(apply_function == revert_function || ($(pending_changes_history[variation_id]).last()[0] && $(pending_changes_history[variation_id]).last()[0].apply_function == apply_function))
    {
        console.log(apply_function);
        console.log('bad changes');
        return;
    }
    else
    {
        // Remove any classes that being with absplit-
        if($.isArray(apply_function) == false && $.isArray(revert_function) == false)
        {
            absplit_classes = 'absplit\-.+?\b';
            apply_function = apply_function.replace(new RegExp('('+absplit_classes+'$)',"gi"), "");
            revert_function = revert_function.replace(new RegExp('('+absplit_classes+'$)',"gi"), "");
        }
        
        // We need to modify our code if we have a diff history index
        if(pending_changes_history_index[variation_id])
        {
            $(pending_changes_history[variation_id]).slice(pending_changes_history_index[variation_id]).each(function(index, data)
            {
                // remove from pending_changes array
                delete pending_changes[variation_id][data.path][data.type];
            });

            // remove all history beyond the index
            pending_changes_history[variation_id] = pending_changes_history[variation_id].splice(0, pending_changes_history_index[variation_id]);
            pending_changes_history_index[variation_id] = null;
        }

        // name space created? 
        if (!pending_changes[variation_id])
        {
            pending_changes[variation_id] = {};
        }

        if (!pending_changes[variation_id][path])
        {
            pending_changes[variation_id][path] = {};
        }

        pending_changes[variation_id][path][type] = {
            path : path,
            apply_function: apply_function,
            revert_function: revert_function,
            temp_removed: false,
            pending : true,
            applied: false
        };

        // TODO - by variation_id
        if(!pending_changes_history[variation_id])
        {
            pending_changes_history[variation_id] = new Array();
        }
        pending_changes_history[variation_id].push({
            path : path,
            type: type,
            apply_function: apply_function,
            revert_function: revert_function,
            pending: true
        });

       apply_changes();
    }
}

function apply_changes()
{
    // Re-append all changes
    $('#code_holder .note-editable').html('<generated_code></genderated_code>');
    if(pending_changes[variation_id])
    {
        $(pending_changes[variation_id]).each(function(index, path_object)
        {
            $.each(path_object, function(path, type_object)
            {
                $.each(type_object, function(type, data)
                {
                    if(data.temp_removed == false && data.applied == false)
                    {
                        data.applied = true;
                        $('#code_holder .note-editable generated_code').text($('#code_holder .note-editable').text().trim()+'\n'+data.apply_function);
                        if($.isArray(data.apply_function) == false)
                        {
                            iframe_window.eval(data.apply_function);
                        }
                        else
                        {
                            $.each(data.apply_function, function(index, apply_function)
                            {
                                iframe_window.eval(apply_function);
                            });
                        }
                    }
                });
            });
        });
    }
}

function undo_changes()
{
    // undo all changes 
    $(pending_changes).each(function(index, variation_details)
    {
        $.each(variation_details, function(index, path_object)
        {
            $.each(path_object, function(path, type_object)
            {
                $.each(type_object, function(type, data)
                {
                    if(data.applied == true)
                    {
                        data.applied = false;
                        $('#code_holder .note-editable generated_code').text($('#code_holder .note-editable').text().trim()+'\n'+data.apply_function);
                        if($.isArray(data.revert_function) == false)
                        {
                            iframe_window.eval(data.revert_function);
                        }
                        else
                        {
                            $.each(data.revert_function, function(index, revert_function)
                            {
                                iframe_window.eval(revert_function);
                            });
                        }
                    }
                });
            });
        });
    });
}

function set_history_index()
{
    if(pending_changes_history_index[variation_id] == null)
    {
        pending_changes_history_index[variation_id] = pending_changes_history[variation_id].length;
    }
}

// Move forward in thehistory list
$(document).on('click', '#redo-change', function()
{
    set_history_index();
    if(pending_changes_history_index[variation_id] != pending_changes_history[variation_id].length)
    {
        var history = $(pending_changes_history[variation_id])[pending_changes_history_index[variation_id]++];
        pending_changes[variation_id][history.path][history.type].temp_removed = false;
        if($.isArray(history.apply_function) == false)
        {
            iframe_window.eval(history.apply_function);
        }
        else
        {
            $.each(history.apply_function, function(index, apply_function)
            {
                iframe_window.eval(apply_function);
            });
        }
    }
});

// Move backward in thehistory list
$(document).on('click', '#undo-change', function()
{
    set_history_index();
    if((pending_changes_history_index[variation_id] -1) != -1)
    {
        var history = $(pending_changes_history[variation_id])[--pending_changes_history_index[variation_id]];
        pending_changes[variation_id][history.path][history.type].temp_removed = true;
        if($.isArray(history.revert_function) == false)
        {
            iframe_window.eval(history.revert_function);
        }
        else
        {
            $.each(history.revert_function, function(index, revert_function)
            {
                iframe_window.eval(revert_function);
            });
        }
    }
});

// When pressing cancel we need to remove all pending changes!
$(document).on('click', '.cancel', function()
{
    // Remove all pending 
    $(pending_changes[variation_id]).each(function(index, path_object)
    {
        $.each(path_object, function(path, type_object)
        {
            $.each(type_object, function(type, data)
            {
                if(data.pending == true)
                {
                    // find oldest pending history
                    $(pending_changes_history[variation_id]).each(function(index, data_history)
                    {
                        if(data_history.path == path && data_history.type == type && data_history.pending == true)
                        {
                            if($.isArray(data_history.revert_function) == false)
                            {
                                iframe_window.eval(data_history.revert_function);
                            }
                            else
                            {
                                $.each(data_history.revert_function, function(index, revert_function)
                                {
                                    iframe_window.eval(revert_function);
                                });
                            }
                            return false;
                        }
                    });
                    delete pending_changes[$('#variation_list .active').data('variation-id')][path][type];
                }
            });
        });
    });

    // Set all histroy pending to false
    $(pending_changes_history[variation_id]).each(function(index, data_history)
    {
        if(data_history.pending == true)
        {
            data_history.pending = false;
        }
    });

    destroy_move_drag();
    apply_changes();
    close_menu();
    
    $('body', iframe_doc).removeClass('absplit_moveto absplit_swap');
    $('.absplit_secondary_border', iframe_doc).removeClass('absplit_secondary_border');
    
    if($(this).closest('.jarviswidget').attr('id') !=  'code_holder')
    {
        $('#absplit-element-menu').show();
    }
    $(this).closest('.jarviswidget').hide();
});

// Save all changes to the variation
$(document).on('click', '.save', function()
{
    // Remove all pending 
    $(pending_changes[$('#variation_list .active').data('variation-id')]).each(function(index, path_object)
    {
        $.each(path_object, function(path, type_object)
        {
            $.each(type_object, function(type, data)
            {
                data.pending = false;
            });
        });
    });
    destroy_move_drag();
    apply_changes();
    $(this).closest('.jarviswidget').find('.jarviswidget-delete-btn').click();
});