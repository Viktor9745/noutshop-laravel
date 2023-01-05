<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Stripe;

class StripePaymentController extends Controller
{
    // public function buy(){
    //     $ids = Auth::user()->itemsCart()->where('in_cart', 'in_cart')->allRelatedIds();
    //     foreach ($ids as $id){
    //         Auth::user()->itemsCart('in_cart', 'in_cart')->updateExistingPivot($id, ['in_cart'=>'ordered']);
    //     }
    //     return back();
    // }

    public function paymentStripe(Request $request, Item $item){
        $itemCart=null;
        if(Auth::check()){
            $itemCart = Auth::user()->itemsCart()->where('in_cart', 'in_cart')->get();
        }
        $amount=0;
        for($i=0;$i<count($itemCart);$i++){
            $amount+=($itemCart[$i]->pivot->quantity)*($itemCart[$i]->price);
        }
        return view('stripe',['amount'=>$amount]);
    }

    public function postPaymentStripe(Request $request, Item $item){
        $validator  = Validator::make($request->all(), [
            'card_no'=>'required',
            'ccExpiryMonth'=>'required',
            'ccExpiryYear'=>'required',
            'cvvNumber'=>'required',
            // 'amount'=>'required',

        ]);
        $input = $request->except('_token');

        if($validator->passes()){
            // config('stripe.sk')
            $stripe = Stripe::setApiKey("sk_test_51MCDhOGEHUvPBvkBuz4N12KSxZ8A0aKvLgAvsinPrDFQjy6qSEBpQn5kvxT7It1tftYduLTrESXuxz8zKk1Xzwjm00Gw3BwpzK");
            // dd($stripe);
            try{
                $token = $stripe->tokens()->create([
                    'card'=>[
                        'number'=>$request->get('card_no'),
                        'exp_month'=>$request->get('ccExpiryMonth'),
                        'exp_year'=>$request->get('ccExpiryYear'),
                        'cvc'=>$request->get('cvvNumber'),
                    ],
                ]);

                if(!isset($token['id'])){
                    return redirect()->route('stripe.add.money');
                }
                $itemCart=null;
                if(Auth::check()){
                    $itemCart = Auth::user()->itemsCart()->where('in_cart', 'in_cart')->get();
                }
                // dd($itemCart);
                $amount=0;
                for($i=0;$i<count($itemCart);$i++){
                    $amount+=($itemCart[$i]->pivot->quantity)*($itemCart[$i]->price);
                }
                $amount = number_format($amount);
                // dd($amount);
                $charge = $stripe->charges()->create([
                    'card'=>$token['id'],
                    'currency'=>'KZT',
                    'amount'=>$amount,
                    'description'=>'wallet',
                ]);

                if($charge['status']=='succeeded'){
                    // dd($charge);
                    $ids = Auth::user()->itemsCart()->where('in_cart', 'in_cart')->allRelatedIds();
                    foreach ($ids as $id){
                        Auth::user()->itemsCart('in_cart', 'in_cart')->updateExistingPivot($id, ['in_cart'=>'ordered']);
                    }
                    return redirect()->route('items.index')->with('message', __('messages.order_confirm'));
                }else{
                    return redirect()->route('addmoney.paymentstripe')->with('error', __('messages.money_no'));
                }


            }catch(Exception $e){
                return redirect()->route('addmoney.paymentstripe')->with('error',$e->getMessage());
            }catch(\Cartalyst\Stripe\Exception\CardErrorException $e){
                return redirect()->route('addmoney.paymentstripe')->with('error', $e->getMessage());
            }catch(\Cartalyst\Stripe\Exception\MissingParameterException $e){
                return redirect()->route('addmoney.paymentstripe')->with('error', $e->getMessage());
            }
        }
    }

}
