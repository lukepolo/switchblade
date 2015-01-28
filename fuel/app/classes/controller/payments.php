<?php
class Controller_Payments extends Controller_Template
{
    public function action_index()
    {
        $payment = new \StripeAPI();

        $payment->card_number = '4242424242424242';
        $payment->exp_month = '11';
        $payment->exp_year = '17';
        $payment->country = 'US';
        $payment->address1 = '6602 N. Park Ave';
        $payment->city = 'Indianapolis';
        $payment->state = 'Indiana';
        $payment->zip = '46200';
        
        $subscription  = Model_Product_Subscription::query()
            ->where('id', 2)
            ->get_one();
        
        $payment->description = $subscription->description;
        
        if($payment->add_subscription($subscription) ===  true)
        {
            // TODO - Redirect to proper products internal dashboard
            echo 'Success!';
            die;
        }
        else
        {
            echo $payment->error;
            die;
            Response::Redirect_Back(Uri::Create('payments'));
        }
        die;
        
        if(\Input::Method() === "POST")
        {
            // TODO - Remove Test Data
            $payment = new \StripeAPI();
            
            $payment->card_number = '4242424242424242';
            $payment->exp_month = '11';
            $payment->exp_year = '17';
            $payment->country = 'US';
            $payment->address1 = '6602 N. Park Ave';
            $payment->city = 'Indianapolis';
            $payment->state = 'Indiana';
            $payment->zip = '46200';
            $payment->amount = '100';
            
            \Debug::dump($payment->charge());
            die;
            
        }
        $data = new stdClass;
    }
    
    
    // TODO - REDO
    public function action_refund($charge_id)
    {
        $refund = new \StripeAPI($charge_id);
        // TODO - we will need a process to process refunds
        if(!$refund->process_refund())
        {
            \Session::set('error', $refund->error);
        }
        Response::Redirect_Back(Uri::Create('profile'));
    }
    
    public function action_get()
    {
        return new \Response(
            \StripeAPI::get_payments(), 
            200,
            array(
                'Content-type' => 'application/json'
            )
        );
        exit();
    }
}