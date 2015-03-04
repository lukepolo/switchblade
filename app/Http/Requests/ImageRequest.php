<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exception\HttpResponseException;

class ImageRequest extends FormRequest
{
    public function rules()
    {
        return [
        ];  
    }
    
    // We dont really care about the rules its just required
    protected function passesAuthorization()
    {
        $file = \Request::file('file');
        if ($file->isValid())
        {
            if(strpos($file->getMimeType(), 'image/') > -1)
            {
                try {
                    $file->move(public_path().'/img/profile_images');
                    return true;
                }
                catch(\Exception $e)
                {
                    // LOG TO FILE
                    // TODO
                    if(config('app.debug'))
                    {
                        throw new HttpResponseException($this->response(
                            array('Error: '.$e->getMessage())
                        ));
                    }
                    else
                    {
                        throw new HttpResponseException($this->response(
                            array('Error' => 'The image could not be uploaded, please contact support.')
                        ));
                    }
                }
            }
            else
            {
                throw new HttpResponseException($this->response(
                    array('Error' => 'Your image is not valid, cannot upload type: '. $file->getMimeType())
                ));
            }
        }
        else
        {
            throw new HttpResponseException($this->response(
                array('Error' => 'Your image could not be uploaded : Unknown Reason.')
            ));
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