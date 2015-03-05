<?php

namespace App\Http\Controllers;

class PaymentController extends Controller
{
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function postSubscribe()
    {
        $user = \Auth::user();
        
        $test_subscription = 'Ketch-Basic';
        $test_subscription_year = 'Ketch-Basic-Year';
        if($user->subscribed() === false)
        {
            $user->subscription($test_subscription)->create(\Input::get('stripeToken'));
        }
        else
        {
            $user->subscription($test_subscription)->swap();
        }
        
        return redirect()->back();
    }
    
    public function getInvoices()
    {
        $invoices = null;
        foreach(\Auth::user()->invoices() as $invoice)
        {
            $invoices[$invoice->id]['dollars'] = $invoice->total();
            $invoices[$invoice->id]['date'] = $invoice->dateString();
            
            foreach($invoice->subscriptions() as $line_item)
            {
                $invoices[$invoice->id]['items'][$line_item->id]['plan'] = $line_item->__get('plan')->statement_descriptor;
                $invoices[$invoice->id]['items'][$line_item->id]['name'] = $line_item->getStripeLine();
                $invoices[$invoice->id]['items'][$line_item->id]['start_date'] = $line_item->startDateString();
                $invoices[$invoice->id]['items'][$line_item->id]['end_date'] = $line_item->endDateString();
                $invoices[$invoice->id]['items'][$line_item->id]['dollars'] = $line_item->total();
            }
            
            $invoices[$invoice->id]['discount'] = $invoice->discount();
            $invoices[$invoice->id]['coupon'] = $invoice->coupon();
            $invoices[$invoice->id]['discountDollars'] = $invoice->discountDollars();
        }
        return response()->json($invoices);
    }
}
