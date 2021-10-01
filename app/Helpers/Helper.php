<?php

    namespace App\Helpers;

    use App\Models\Cart;
    use App\Models\Category;
    use App\Models\Message;
    use App\Models\Order;
    use App\Models\Shipping;
    use App\Models\Wishlist;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\Auth;

    class Helper
    {
        /**
         * @return Collection
         */
        public static function messageList(): Collection
        {
            return Message::whereNull('read_at')->orderBy('created_at', 'desc')->get();
        }
        /**
         * @param  string  $user_id
         * @return int|mixed
         */
        // Cart Count
        public static function cartCount(string $user_id = '')
        {
            if (Auth::check()) {
                if ($user_id == "") {
                    $user_id = auth()->user()->id;
                }
                return Cart::whereUserId($user_id)->whereOrderId(null)->sum('quantity');
            } else {
                return 0;
            }
        }

        /**
         * @param  string  $user_id
         * @return Builder[]|\Illuminate\Database\Eloquent\Collection|int
         */
        public static function getAllProductFromCart(string $user_id = '')
        {
            if (Auth::check()) {
                if ($user_id == "") {
                    $user_id = auth()->user()->id;
                }
                return Cart::with('product')->where('user_id', $user_id)->where('order_id', null)->get();
            } else {
                return 0;
            }
        }
        /**
         * @param  string  $user_id
         * @return int|mixed
         */
        // Total amount cart
        public static function totalCartPrice(string $user_id = '')
        {
            if (Auth::check()) {
                if ($user_id == "") {
                    $user_id = auth()->user()->id;
                }
                return Cart::whereUserId($user_id)->where('order_id', null)->sum('amount');
            } else {
                return 0;
            }
        }
        /**
         * @param  string  $user_id
         * @return int|mixed
         */
        // Wishlist Count
        public static function wishlistCount(string $user_id = '')
        {
            if (Auth::check()) {
                if ($user_id == "") {
                    $user_id = auth()->user()->id;
                }
                return Wishlist::whereUserId($user_id)->where('cart_id', null)->sum('quantity');
            } else {
                return 0;
            }
        }

        /**
         * @param  string  $user_id
         * @return int|mixed
         */
        public static function totalWishlistPrice(string $user_id = '')
        {
            if (Auth::check()) {
                if ($user_id == "") {
                    $user_id = auth()->user()->id;
                }
                return Wishlist::whereUserId($user_id)->where('cart_id', null)->sum('amount');
            } else {
                return 0;
            }
        }
        /**
         * @param $id
         * @param $user_id
         * @return int|string
         */
        // Total price with shipping and coupon
        public static function grandPrice($id, $user_id)
        {
            $order = Order::find($id);
            if ($order) {
                $shipping_price = (float)$order->shipping->price;
                $order_price = self::orderPrice($id, $user_id);
                return number_format((float)($order_price + $shipping_price), 2, '.', '');
            } else {
                return 0;
            }
        }

        /**
         * @return Collection
         */
        public static function shipping(): Collection
        {
            return Shipping::orderBy('id', 'DESC')->get();
        }

        public static function productCategoryList($option = 'all')
        {
            if ($option = 'all') {
                return Category::orderBy('id', 'DESC')->get();
            }
            return Category::has('products')->orderBy('id', 'DESC')->get();
        }

        public static function postCategoryList($option = "all")
        {
            if ($option = 'all') {
                return Category::orderBy('id', 'DESC')->get();
            }
            return Category::has('posts')->orderBy('id', 'DESC')->get();
        }
    }
