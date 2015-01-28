<?php

class Controller_Api extends \Controller_Rest
{
    public function get_mods()
    {
        // Based on their activated mods , grab the JS code to execute
        $mods[] = \ABSplit\Controller_Api::get_code();
        $mods[] = \HeatMap\Controller_Api::get_code();
        
        return $this->response($mods);
    }
    
    public function post_stripe()
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
                // get customer
                $stripe = new \StripeAPI();
                $stripe->create_customer("cus_5awt1H3f8yQHsM");
                $email = $stripe->customer->email;
            break;
            default:
                $body = 'No Defined Action';
            break;
        }
        
        return $this->response();
    }
}