<?php

class Controller_Api_Stripe extends \Controller_Rest
{
    public function post_index()
    {
        $input = @file_get_contents("php://input");
        $event_json = json_decode($input);
        switch($event_json->type)
        {
            case 'invoice.payment_succeeded':
                $invoice = $event_json->data->object->lines->data[0];
                $amount = money_format('%.2n', $invoice->plan->amount / 100);
                $product_name = $invoice->plan->name;
                $interval = $invoice->plan->interval_count.' '.$invoice->plan->interval;
                $start_date = \Date::forge($invoice->period->start)->format('us');
                $end_date = \Date::forge($invoice->period->end)->format('us');
                
                $body = array(
                    $amount,
                    $product_name,
                    $interval,
                    $start_date,
                    $end_date
                );
            break;
            case 'invoice.payment_failed':
                // Send notice we will try again in 3 days
                $invoice = $event_json->data->object->lines->data[0];
                $amount = money_format('%.2n', $invoice->plan->amount / 100);
                $product_name = $invoice->plan->name;
                $interval = $invoice->plan->interval_count.' '.$invoice->plan->interval;
                $start_date = \Date::forge($invoice->period->start)->format('us');
                $end_date = \Date::forge($invoice->period->end)->format('us');
                
                $body = array(
                    $amount,
                    $product_name,
                    $interval,
                    $start_date,
                    $end_date
                );
            break;
            case 'charge.dispute.created':
                // Just email me to check the disupte online
            break;
            case 'customer.subscription.trial_will_end':
                // happens 3 days prior
                $stripe = new \StripeAPI();
                $stripe->create_customer($event_json->data->object->customer);
                $email = $stripe->customer->email;
            break;
            case 'plan.created':
            case 'plan.updated':
                $stripe_id = $event_json->data->object->id;
                $name  = $event_json->data->object->name;
                $price = money_format('%.2n', $event_json->data->object->amount / 100);
                $active = $event_json->data->object->livemode;
                $interval = $event_json->data->object->interval;
                $interval_count = $event_json->data->object->interval_count;
                
                $plan = Model_Product_Plan::query()
                    ->where('stripe_id', $stripe_id)
                    ->get_one();
                
                if(empty($plan) === true)
                {
                    $plan = Model_Product_Plan::forge(array(
                        'stripe_id' => $stripe_id,
                        'description' => $name,
                        'price' => $price,
                        'active' => $active,
                        'interval' => $interval,
                        'interval_count' => $interval_count 
                    ));
                }
                else
                {
                    $plan->description = $name;
                    $plan->price = $price;
                    $plan->active = $active;
                    $plan->interval = $interval;
                    $plan->interval_count = $interval_count;
                }
                $plan->save();
            break;
            case 'plan.deleted':
                $stripe_id = $event_json->data->object->id;
                 
                $plan = Model_Product_Plan::query()
                    ->where('stripe_id', $stripe_id)
                    ->get_one();
                
                if(empty($plan) === false)
                {
                    $plan->delete();
                }
            break;
            default:
                $body = 'No Defined Action';
            break;
        }
        
        return $this->response();
    }
}