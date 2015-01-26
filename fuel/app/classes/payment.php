<?php
class Payment
{
    protected $card_id;
    protected $customer;
    
    public $card_number;
    public $exp_month;
    public $exp_year;
    public $cvc;
    public $name;
    public $address1;
    public $city;
    public $state;
    public $zip;
    public $country = 'US';
    public $amount;
    public $currency = 'usd';
    
    public $product_id;
    public $subscription = false;
    
    public $time_period = 31;
    
    public $charge;
    public $refund;
    
    public function __construct($charge_id = null)
    {
        // SEND API KEY
        \Stripe::setApiKey(Config::get('stripe.'.\Fuel::$env.'.key'));
        
        if(empty($charge_id) === false)
        {
            $this->charge = Stripe_Charge::retrieve($charge_id);
        }
    }
    
    public function charge()
    {
        // Attempt to charge the card, if not return the error
        try
        {
            $this->validate();
            
            $this->charge = Stripe_Charge::create(
                array(
                    'name' => $this->name,
                    'card' => $this->card_id,
                    'customer' => $this->customer->id,
                    'amount' => $this->amount, 
                    'currency' => $this->currency,
                    'description' => $this->description,
                    'metadata' => array(
                        'user_id' => \Auth::get_user_id()[1],
                    )
                )
            );
            
            $payment = \Model_Payment::forge(array(
                'user_id' => \Auth::get_user_id()[1],
                'charge_id' => $this->charge->id,
                'amount' => $this->charge->amount,
                'product_id' => $this->product_id,
                'refunded' => false
            ));
            
            $payment->save();
            
            return true;
        }
        catch(\Exception $e)
        {
            $this->error = $e->getMessage();
            return false;
        }
    }
    
    public function validate()
    {
        // TODO - Validate CARD INFO before we pass it to the customer 
        $this->create_customer();
        
        // TODO - make a return false
        return true;
    }
    
    public function refund()
    {
        // TODO - Need user profile page to continue
    }
    
    public function create_customer()
    {
        // See if the user has a customer id 
        if(\Auth::get('stripe_user'))
        {
            $customer = Stripe_Customer::retrieve(\Auth::get('stripe_user'));
        }
        else 
        {
            $customer = Stripe_Customer::create(array(
                'email' => \Auth::get('email'),
                'metadata' => array(
                    'user_id' => \Auth::get_user_id()[1],
                )
            ));
            
            Auth::update_user(array(
                    'stripe_user' => $customer->id
            ));
        }
        
        $this->customer = $customer;
        
        foreach($customer->cards->data as $card)
        {
            // we already have their cc info just set it as their card
            if(substr($this->card_number, -4) == $card->last4)
            {
                $this->card_id = $card->id;
                return true;
            }
        }
        $this->card_id = $this->create_card();
    }
    
    public function create_card()
    {
        $card = $this->customer->cards->create(array(
                'card' => array(
                    'number' => $this->card_number, 
                    'exp_month' => $this->exp_month,
                    'exp_year' => $this->exp_year,
                    'cvc' => $this->cvc,
                    'address_line1' => $this->address1,
                    'address_city' => $this->city,
                    'address_zip' => $this->zip,
                    'address_state' => $this->state,
                    'address_country' => $this->country
                ),
            )
        );
        
        $this->card_id = $card->id;
    }
    
    public static function get_payments()
    {
        return \Model_Payment::query()
            ->Related('product')
            ->where('user_id', \Auth::get_user_id()[1])
            ->get();
    }
    
    public function process_refund($payment, $amount, $reason = 'requested_by_customer')
    {
        try
        {
            $this->refund = $this->charge->refunds->create(array(
                'amount' => $amount,
                'reason' => $reason
            ));
            
            $payment->refund = $this->refund->id;
            $payment->save();
            
            Session::set('success', 'You have been refunded please wait 1 to 3 days before the refund is completed.');
            return true;
        } 
        catch (\Exception $e) 
        {
            Session::set('error', $e->getMessage());
            return false;
        }
    }
}
