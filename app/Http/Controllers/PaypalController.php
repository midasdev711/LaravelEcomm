<?php

    namespace App\Http\Controllers;

    use App\Models\Cart;
    use App\Models\Product;
    use Exception;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Srmklive\PayPal\Services\ExpressCheckout;

    class PaypalController extends Controller
    {
        /**
         * @throws Exception
         */
        public function payment()
        {
            $cart = Cart::whereUserId(auth()->user()->id)->where('order_id', null)->get()->toArray();

            $data = [];

            $data['items'] = array_map(function ($item) use ($cart) {
                $name = Product::where('id', $item['product_id'])->pluck('title');
                return [
                    'name'  => $name,
                    'price' => $item['price'],
                    'desc'  => 'Thank you for using paypal',
                    'qty'   => $item['quantity'],
                ];
            }, $cart);

            $data['invoice_id'] = 'ORD-'.strtoupper(uniqid());
            $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
            $data['return_url'] = route('payment.success');
            $data['cancel_url'] = route('payment.cancel');

            $data['total'] = session()->get('total');

            Cart::where('user_id', auth()->user()->id)->where('order_id',
                null)->update(['order_id' => session()->get('id')]);

            $provider = new ExpressCheckout;

            $response = $provider->setExpressCheckout($data, true);
            return redirect($response['paypal_link']);
        }

        /**
         * Responds with a welcome message with instructions
         *
         * @return Response
         */
        public function cancel(): Response
        {
            dd('Your payment is canceled. You can create cancel page here.');
        }

        /**
         * Responds with a welcome message with instructions
         *
         * @param  Request  $request
         * @return RedirectResponse
         * @throws Exception
         */
        public function success(Request $request): RedirectResponse
        {
            $provider = new ExpressCheckout;
            $response = $provider->getExpressCheckoutDetails($request->token);

            if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
                request()->session()->flash('success', 'You successfully pay from Paypal! Thank You');
                session()->forget('cart');
                session()->forget('coupon');
                return redirect()->route('home');
            }

            request()->session()->flash('error', 'Something went wrong please try again!!!');
            return redirect()->back();
        }
    }
