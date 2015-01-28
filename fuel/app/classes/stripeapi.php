<?php
class StripeAPI
{
    protected $card_id;
    
    public $customer;
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
    
    public $subscription;
    
    public $description;
    
    public $time_period = 31;
    
    public $charge;
    public $refund;
    
    public $error;
    public $metadata = array();
    
    public $coupon;
    
    public function __construct($charge_id = null)
    {
        // SEND API KEY
        \Stripe::setApiKey(Config::get('stripe.'.\Fuel::$env.'.key'));
        
        $this->metadata['user_id'] = \Auth::get_user_id()[1];
        
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
                        $this->metadata
                    )
                )
            );
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
        $this->create_card();
        // TODO - make a return false
        return true;
    }
    
    public function refund()
    {
        // TODO - Need user profile page to continue
    }
    
    public function create_customer($customer_id = null)
    {
        if(empty($this->customer) === true)
        {
            // See if the user has a customer id 
            if(\Auth::get('stripe_user'))
            {
                $customer = Stripe_Customer::retrieve(\Auth::get('stripe_user'));
            }
            elseif(empty($customer_id) === false)
            {
                $customer = Stripe_Customer::retrieve($customer_id);
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
        }
    }
    
    public function create_card()
    {
        if(empty($this->card_id) === true)
        {
            foreach($this->customer->cards->data as $card)
            {
                // we already have their cc info just set it as their card
                if(substr($this->card_number, -4) == $card->last4)
                {
                    $this->card_id = $card->id;
                    return;
                }
            }
        
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
    
    public static function get_payments($limit = 12)
    {
        try
        {
            $payemnts = new \StripeAPI();
            $payments = Stripe_Charge::all(array(
                'customer' => \Auth::get('stripe_user'),
                'limit' => $limit
            ));
            return $payments->__toJSON();
        }
        catch (\Exception $e) 
        {
            return $e->getMessage();
        }
    }
    
    public static function get_subscriptions()
    {
        try
        {
            $payment = new \StripeAPI();
            $subscriptions = Stripe_Customer::retrieve(\Auth::get('stripe_user'))->subscriptions->all();
            return $subscriptions->__toJSON();
        }
        catch (\Exception $e) 
        {
            return $e->getMessage();
        }
    }
    
    public function process_refund($reason = 'requested_by_customer', $amount = null)
    {
        try
        {
            if($this->metadata['user_id'] == \Auth::get_user_id()[1])
            {
                $this->refund = $this->charge->refunds->create(array(
                    'amount' => $amount,
                    'reason' => $reason
                ));

                Session::set('success', 'You have been refunded please wait 1 to 3 days before the refund is completed.');
                return true;
            }
            else
            {
                $this->error = 'Please contact customer support with error #1 <br> '.\Settings::get('helpdesk');
                return false;
            }
        } 
        catch (\Exception $e) 
        {
            $this->error = $e->getMessage();
            return false;
        }
    }
    
    public function remove_subscription($subscription_id)
    {
        try
        {
            $this->customer = Stripe_Customer::retrieve(\Auth::get('stripe_user'));
            $this->subscription = $this->customer->subscriptions->retrieve($subscription_id);
            
            if($this->metadata['user_id'] == \Auth::get_user_id()[1])
            {
                try
                {
                    $this->subscription->cancel();
                    Session::set('success', 'Your subscription has been canceled.');
                    return;
                }
                catch(Exception $e)
                {
                    $this->error = $e->getMessage();
                    return false;
                }
            }
            else
            {
                $this->error = 'Please contact customer support with error #2 <br> '.\Settings::get('helpdesk');
                return false;
            }
        }
        catch(\Exception $e)
        {
            $this->error = $e->getMessage();
            return false;
        }
    }
    
    public function add_subscription(Model_Product_Subscription $subscription)
    {
        try
        {
            $this->validate();
            $this->metadata['plan_id'] = (int) $subscription->id;
            
            // subscribe them to a subscription
            $this->customer->subscriptions->create(array(
                'plan' => $subscription->stripe_id,
                'coupon' => $this->coupon,
                'metadata' => $this->metadata
            ));
            
            return true;
        }
        catch(\Exception $e)
        {
            $this->error = $e->getMessage();
            return false;
        }
    }
}
