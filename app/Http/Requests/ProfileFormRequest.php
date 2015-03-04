<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exception\HttpResponseException;

class ProfileFormRequest extends FormRequest
{
    public function rules()
    {
        if(\Auth::user()->email != \Input::get('email'))
        {
            $unquie = '|unique:users';
        }
        else
        {
            $unquie = '';
        }
       
        if(\Input::get('current_password') != '' || \Input::get('new_password') != '')
        {
            // We must checked if the password is the same
            if(\Hash::check(\Input::get('current_password'), \Auth::user()->getAuthPassword()))
            {
                return [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255'.$unquie,
                    'current_password' => 'required',
                    'new_password' => 'required|min:5',
                ];
            }
            else
            {
                throw new HttpResponseException($this->response(
                    array('Error' => 'Your current password does not match what we have on our records!')
		));
            }
        }
        else
        {
            return [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255'.$unquie,
            ];  
        }
    }

    public function authorize()
    {
        if (\Auth::check())
        {
            return true;
        }
        else
        {
            return false;
        }
        
    }
}