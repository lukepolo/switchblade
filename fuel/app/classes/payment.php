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
    public $country;
    public $amount;
    public $currency = 'usd';
    
    public function __construct()
    {
        // TODO  SETUP Dev / Prod Keys
        // SEND API KEY
        \Stripe::setApiKey('sk_test_saciivOBVDmsbJptRSdoAyZC');
      
        if(empty($currency) === false)
        {
            $this->currency = $currency;
        }
        
    }
    
    public function charge()
    {
        // if in dev we ALWAYS TEST
        if($this->validate())
        {
            return Stripe_Charge::create(
                array(
                    'name' => $this->name,
                    'card' => $this->card_id,
                    'customer' => $this->customer->id,
                    'amount' => $this->amount, 
                    'currency' => $this->currency,
                    'description' => 'testing',
                )
            );
        }
        else
        {
            return $this->validate();
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
                    'userid' => \Auth::get_user_id()[1],
                )
            ));
            
            Auth::update_user(
                array(
                    'stripe_user' => $customer->id
                )
            );
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
}
