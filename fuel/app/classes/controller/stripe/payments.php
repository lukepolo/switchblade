<?php
class Controller_Stripe_Payments extends Controller_Template
{
    public function action_index()
    {
        if(\Input::Method() === "POST")
        {
            
        }
        $data = new stdClass;
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
    
    public function action_process()
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
        $payment->amount = '100';

        if($payment->charge() ===  true)
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
    }
    
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
}