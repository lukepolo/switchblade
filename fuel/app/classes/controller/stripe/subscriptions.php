<?php
class Controller_Stripe_Subscriptions extends Controller_Template
{
    public function action_index()
    {
        
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
    
    public function action_create()
    {
        $subscription = new \StripeAPI();

        $subscription->card_number = '4242424242424242';
        $subscription->exp_month = '11';
        $subscription->exp_year = '17';
        $subscription->country = 'US';
        $subscription->address1 = '6602 N. Park Ave';
        $subscription->city = 'Indianapolis';
        $subscription->state = 'Indiana';
        $subscription->zip = '46200';
        
        $plan = Model_Product_Plan::query()
            ->where('id', 1)
            ->get_one();
        
        $subscription->description = $plan->description;
        
        if($subscription->add_subscription($plan) ===  true)
        {
            // TODO - Redirect to proper products internal dashboard
            echo 'Success!';
            die;
        }
        else
        {
            echo $payment->error;
            die;
            Response::Redirect_Back(Uri::Create('subscriptions'));
        }
        die;
    }
    
    public function action_cancel($subscription_id)
    {
        $subscription = new \StripeAPI();
      
        if(!$subscription->remove_subscription($subscription_id))
        {
            \Session::set('error', $subscription->error);
        }
        Response::Redirect_Back(Uri::Create('profile'));
    }
    
    public function action_update($subscription_id, $plan_id)
    {
        $subscription = new \StripeAPI();
        if(!$subscription->update_subscription($subscription_id, $plan_id))
        {
            \Session::set('error', $subscription->error);
        }
        Response::Redirect_Back(Uri::Create('profile'));
        
    }
}