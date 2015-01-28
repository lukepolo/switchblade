<?php
class Controller_Subscriptions extends Controller_Template
{
    public function action_index()
    {
       
    }
    
     // TODO - REDO
    public function action_cancel($subscription_id)
    {
        $subscription = new \StripeAPI();
      
        if(!$subscription->remove_subscription($subscription_id))
        {
            \Session::set('error', $subscription->error);
        }
        Response::Redirect_Back(Uri::Create('profile'));
    }
    
    public function action_get()
    {
        return new \Response(
            \StripeAPI::get_subscriptions(), 
            200,
            array(
                'Content-type' => 'application/json'
            )
        );
        exit();
    }
}