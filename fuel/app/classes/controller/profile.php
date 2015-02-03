<?php
class Controller_Profile extends Controller_Template
{
    public function action_index()
    {
        $data = new stdClass;
        if(Input::Method() === "POST")
        {
            $saved = Auth::update_user(
                array(
                    'email' => Input::Post('email'),
                    'first_name' => Input::Post('first_name'),
                    'last_name' => Input::Post('last_name'),
                    'gender' => Input::Post('gender'),
                )
            );
            
            if($saved)
            {
                Session::set('success', 'Successfully updated your profile');
            }
            
            if(Input::Post('current_password') !== null && Input::Post('new_password') != null)
            {
                if(Auth::change_password(Input::Post('current_password'), Input::Post('new_password')))
                {
                    Session::set('success', 'Successfully updated your password');
                }
                else
                {
                    Session::set('error', 'Your current password is incorrect, please try again');
                }
            }
        }      
        $this->template->content = View::Forge('auth/profile', $data);
    }
    
    public function action_update_img()
    {
        
        if(Input::Method() === "POST")
        {
            $config = array(
                'path' => MAINPATH.'assets/img/users',
                'randomize' => true,
                'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
            );
            
            // process the uploaded files in $_FILES
            Upload::process($config);
            
            // if there are any valid files
            if (Upload::is_valid())
            {
                // save them according to the config
                Upload::save();

                $files = Upload::get_files();
                
                // call a model method to update the database
                Auth::update_user(array(
                    'user_image' => $files[0]['saved_as']
                ));
                
                echo true;
            }

            // and process any errors
            foreach (Upload::get_errors() as $file)
            {
                // need to test this?
                echo json_encode($file['errors'][0]);
                die;
            }
        }
        die;
    }        
}