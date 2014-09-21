<?php
class Form extends Fuel\Core\Form
{
    public static function label($label, $id = null, array $attributes = array())
    {
        // Differs from orginal
        if(isset($attributes['class']) === true)
        {
            // append to the class
            $attributes['class'] = $attributes['class'].' '.'label';
        }
        else
        {
            $attributes['class'] = 'label';
        }
        
        return static::$instance->label($label, $id, $attributes);
    }
    
    public static function open($attributes = array(), array $hidden = array())
    {
        $hidden['csrf'] = \Form::csrf(); // Differs from orginal
        return static::$instance->open($attributes, $hidden);
    }
    
    public static function input($field, $value = null, array $attributes = array())
    {
        // Differs from orginal
        if(isset($attributes['placeholder']) === false)
        {
            $attributes['placeholder'] = ucwords(str_replace('_', ' ', $field));
        }
        return static::$instance->input($field, $value, $attributes);
    }
    
    public static function password($field, $value = null, array $attributes = array())
    {
        // Differs from orginal
        if(isset($attributes['placeholder']) === false)
        {
            $attributes['placeholder'] = ucwords(str_replace('_', ' ', $field));
        }
        return static::$instance->password($field, $value, $attributes);
    }
    
    public static function checkbox($field, $value = null, $checked = null, array $attributes = array())
    {
        // Adding <i></i> for smartadmin checkbox
        return static::$instance->checkbox($field, $value, $checked, $attributes). '<i></i>';
    }
    
    public static function select($field, $values = null, array $options = array(), array $attributes = array())
    {
        // Differs from orginal
        if(isset($attributes['data-placeholder']) === false)
        {
            $attributes['data-placeholder'] = ucwords(str_replace('_', ' ', $field));
        }
        
        return static::$instance->select($field, $values, $options, $attributes);
    }

}