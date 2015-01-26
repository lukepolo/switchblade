<?php
class Controller_Payments extends Controller_Template
{
    public function action_index()
    {
        if(\Input::Method() === "POST")
        {
            // TODO - Remove Test Data
            $payment = new \Payment();
            
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
}