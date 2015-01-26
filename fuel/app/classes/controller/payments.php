<?php
class Controller_Payments extends Controller_Template
{
    public function action_index()
    {
        $payment = new \Payment();
        
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
        $payment->description = '1 Month of Service for KetchURL.';
        $payment->product_id = 1;
            
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
            Response::Redirect_Back(Uri::Create('payments'));
        }
        die;
        
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
    
    public function action_refund($payment_id)
    {
        $payment = \Model_Payment::query()
            ->where('id', $payment_id)
            ->where('user_id', \Auth::get_user_id()[1])
            ->get_one();
        
        if(empty($payment) === false)
        {
            // TODO - we will need a process to process refunds
            // try to process refund 
            $refund = new \Payment($payment->charge_id);
            
            if(!$refund->process_refund($payment, $payment->amount))
            {
                \Session::set('error', $this->error);
            }
        }
        else
        {
            \Session::set('error', 'Please contact customer support with error #1 <br> '.\Settings::get('helpdesk'));
        }
        Response::Redirect_Back(Uri::Create('profile'));
    }
}