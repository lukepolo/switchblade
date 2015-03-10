<?php
namespace App\Extensions;

class FormBuilder extends \Illuminate\Html\FormBuilder
{
    /**
    * Create a form input field.
    *
    * @param  string  $type
    * @param  string  $name
    * @param  string  $value
    * @param  array   $options
    * @return string
    */
    public function input($type, $name, $value = null, $options = array())
    {
        if ( ! isset($options['name'])) $options['name'] = $name;

        // We will get the appropriate value for the given field. We will look for the
        // value in the session for the value in the old input data then we'll look
        // in the model instance if one is set. Otherwise we will just use empty.
        $id = $this->getIdAttribute($name, $options);

        if ( ! in_array($type, $this->skipValueTypes))
        {
            $value = $this->getValueAttribute($name, $value);
        }

        // Once we have the type, value, and ID we can merge them into the rest of the
        // attributes array so we can convert them into their HTML attribute format
        // when creating the HTML element. Then, we will return the entire input.
        $merge = compact('type', 'value', 'id');

        // DIFFERS FROM ORGINAL
        if($type != 'checkbox' && $type != 'radio' && isset($options['class']) === false)
        {
            $options['class'] = 'form-control';
        }

        if(isset($options['placeholder']) === false)
        {
            $options['placeholder'] = ucwords(str_replace('_', ' ', $options['name']));
        }

        $options = array_merge($options, $merge);

        return '<input'.$this->html->attributes($options).'>';
    }

    /**
     * Create a textarea input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function textarea($name, $value = null, $options = array())
    {
        if ( ! isset($options['name'])) $options['name'] = $name;

        // Next we will look for the rows and cols attributes, as each of these are put
        // on the textarea element definition. If they are not present, we will just
        // assume some sane default values for these attributes for the developer.
        $options = $this->setTextAreaSize($options);

        $options['id'] = $this->getIdAttribute($name, $options);

        $value = (string) $this->getValueAttribute($name, $value);

        unset($options['size']);

        // DIFFERS FROM ORGINAL
        if(isset($options['class']) === false)
        {
            $options['class'] = 'form-control';
        }

        // Next we will convert the attributes into a string form. Also we have removed
        // the size attribute, as it was merely a short-cut for the rows and cols on
        // the element. Then we'll create the final textarea elements HTML for us.
        $options = $this->html->attributes($options);

        return '<textarea'.$options.'>'.e($value).'</textarea>';
    }
}
