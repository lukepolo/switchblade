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
            add_changes(path, 'html', "$('" + path + "').html(" + JSON.stringify($(this).prev().code()) +");", "$('" + path + "').html(" + JSON.stringify($(iframe_element)[0].outerHTML) +");");
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
                    add_changes(path, 'src', "$('" + path + "').attr('src', '" + file_data +"');", "$('" + path + "').attr('src', '" + $(iframe_element).attr('src') +"');");
                };  

                reader.readAsDataURL(file);
            });

        break;
        default:
            console.log('NO EVENT - '+ $(this).closest('.jarviswidget').attr('id'));
        break;
    }
});