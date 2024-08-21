<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\Variation;
use App\Models\Review;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Exchange;
use App\Models\ExchangeItem;
use App\Models\Notification;
use App\Models\Release;
use App\Models\ShipFee;
use Carbon\Carbon;
use Session;
use Hash;

class HomeController extends Controller
{
    public function home()
    {
        $products = Product::where('prod_status', '!=', 'DELETED')->where('prod_status', '!=', 'DISABLED')->orderBy('prod_id', 'desc')->take(3)->get();
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('home',compact('notifications','products'))->with(['loggedIn' => true]);
        }
        else
            return view('home',compact('products'))->with(['loggedIn' => false]);
    }

    public function dashboard($status = 'All')
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $defaultaddress = Address::where('user_id',$userdata->user_id)->where('default',true)->first();
            if($status === 'All')
                $orders = Order::with('seller','orderItems')->where('buyer_id',$userdata->user_id)->orderBy('order_id','desc')->paginate(10);
            if($status === 'UNPAID')
                $orders = Order::with('seller','orderItems')->where('buyer_id',$userdata->user_id)->where('status','UNPAID')->orderBy('order_id','desc')->paginate(10);
            if($status === 'ON THE WAY')
                $orders = Order::with('seller','orderItems')->where('buyer_id',$userdata->user_id)->where('status','ON THE WAY')->orderBy('order_id','desc')->paginate(10);
            if($status === 'COMPLETED')
                $orders = Order::with('seller','orderItems')->where('buyer_id',$userdata->user_id)->where('status','COMPLETED')->orderBy('order_id','desc')->paginate(10);
            if($status === 'CANCELED')
                $orders = Order::with('seller','orderItems')->where('buyer_id',$userdata->user_id)->where('status','CANCELED')->orderBy('order_id','desc')->paginate(10);
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            $warnCount = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->count();
            return view('dashboard',compact('userdata','defaultaddress','orders','notifications','warnCount'));
        }
        else
            return redirect('/login');
    }

    public function profile()
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $warnCount = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->count();
        if($warnCount >= 3)
            return redirect('/dashboard/main/')->with('fail','This account has been disabled!');
        $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
        return view('profile',compact('userdata','notifications'));
    }

    public function addressBook()
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $addresses = Address::where('user_id','=',$userdata->user_id)->get();
        $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
        $warnCount = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->count();
        return view('address',compact('userdata','addresses','notifications','warnCount'));
    }

    public function newAddress()
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/dashboard/addressbook')->with('fail','This account has been disabled!');
        $provinces = ["Negros Oriental","Negros Occidental"];
        $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
        return view('newaddress',compact('userdata','provinces','notifications'));
    }

    public function editAddress($add_id)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $warnCount = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->count();
        if($warnCount >= 3)
                return redirect('/dashboard/addressbook')->with('fail','This account has been disabled!');
        $provinces = ["Negros Oriental","Negros Occidental"];
        $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
        $address = Address::find($add_id);
        if (!$address)
            return redirect('/dashboard/addressbook')->with('fail','Address not found!');
        return view('editaddress',compact('userdata','provinces','address','notifications'));
    }

    public function productCatalog(Request $request)
    {
        $productlist = Product::where('prod_status','ENABLED')->where('is_approved',1)->whereHas('user', function ($query) {$query->where('is_disabled',0);});
        $organizations = User::distinct()->whereNotNull('org_name')->pluck('org_name');			// THIS LINE IS MODIFIED
        $categories = Category::all();

        if($request->has('category') && $request->input('category') != 'all')
            $productlist->where('category', $request->input('category'));
        if($request->has('organization') && $request->input('organization') != 'all')
        {
            $productlist->whereHas('user', function ($query) use ($request) {
                $query->where('org_name', $request->input('organization'));
            });
        };

        $products = $productlist->paginate(20);

        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('productcatalog',compact('products','organizations','categories','notifications'))->with(['loggedIn' => true]);
        }
        else
            return view('productcatalog',compact('products','organizations','categories'))->with(['loggedIn' => false]);
    }

    public function search(Request $request)
    {
        $search = $request->input('q');

        $productlist = Product::where('prod_name', 'LIKE', '%' . $search . '%')->where('prod_status', 'ENABLED')->where('is_approved', 1)->whereHas('user', function ($query) {$query->where('is_disabled', 0);});
        $organizations = User::distinct()->whereNotNull('org_name')->pluck('org_name');					// THIS LINE IS MODIFIED
        $categories = Category::all();

        if($request->has('category') && $request->input('category') != 'all')
            $productlist->where('category', $request->input('category'));
        if($request->has('organization') && $request->input('organization') != 'all')
        {
            $productlist->whereHas('user', function ($query) use ($request) {
                $query->where('org_name', $request->input('organization'));
            });
        };

        $products = $productlist->paginate(20);

        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('searchcatalog',compact('products','organizations','search','categories','notifications'))->with(['loggedIn' => true]);
        }
        else
            return view('searchcatalog',compact('products','organizations','search','categories'))->with(['loggedIn' => false]);
    }

    // public function productFullDetails($prod_id)
    // {
    //     $product = Product::find($prod_id);
    //     $userdata = User::where('user_id','=',$product->user_id)->first();
    //     $reviewlist = Review::where('prod_id',$product->prod_id);
    //     $averageRating = $reviewlist->avg('rate');

    //     if(!$product)
    //         return redirect('/catalog')->with('fail','Product does not exist!');

    //     $colorVariations = Variation::where('prod_id', $prod_id)->whereNotNull('color')->distinct()->pluck('color')->toArray();
    //     $sizeVariations = Variation::where('prod_id', $prod_id)->whereNotNull('size')->distinct()->pluck('size')->toArray();
    //     $variationsData = Variation::where('prod_id', $prod_id)->get(['color','size','quantity']);
    //     $variationsDataJson = json_encode($variationsData);
    //     $reviews = $reviewlist->simplePaginate(5);

    //     if(Session::has('loginId'))
    //         return view('productfulldetails',compact('userdata','product','colorVariations','sizeVariations','reviews','averageRating','variationsDataJson'))->with(['loggedIn' => true]);
    //     else
    //         return view('productfulldetails',compact('userdata','product','colorVariations','sizeVariations','reviews','averageRating','variationsDataJson'))->with(['loggedIn' => false]);
    // }

    public function productFullDetails($prod_id)
    {
        $product = Product::find($prod_id);
        $userdata = User::where('user_id','=',$product->user_id)->first();
        $reviewlist = Review::where('prod_id',$product->prod_id);
        $variations = Variation::where('prod_id',$prod_id)->get();
        $averageRating = $reviewlist->avg('rate');

        if(!$product || $product->prod_status === 'DELETED')
            return redirect('/catalog')->with('fail','Product does not exist!');

        $reviews = $reviewlist->simplePaginate(5);

        if(Session::has('loginId'))
        {
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('productfulldetails',compact('userdata','product','reviews','averageRating','variations','notifications'))->with(['loggedIn' => true]);
        }
        else
            return view('productfulldetails',compact('userdata','product','reviews','averageRating','variations'))->with(['loggedIn' => false]);
    }

    public function addCart(Request $request)
    {
        if(!Session::has('loginId'))
            return redirect('/login');
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/catalog')->with('fail','This account has been disabled!');

        $user_id = Session::get('loginId');
        $product = Product::find($request->prod_id);
        $variationslist = Variation::where('prod_id', $product->prod_id)->get();
        $variation = $request->input('variations');
        $quantity = $request->input('quantity');

        if(!empty($variation))
        {
            if (strpos($variation, ' - ') !== false)
            {
                list($color, $size) = explode(' - ', $variation);
                $existingCartItem = CartItem::where('cart_id', $this->getCartId($user_id))->where('prod_id', $product->prod_id)->where('color', $color)->where('size', $size)->first();
            }
            else
            {
                if($variationslist->contains('color', $variation))
                    $existingCartItem = CartItem::where('cart_id', $this->getCartId($user_id))->where('prod_id', $product->prod_id)->where('color', $variation)->first();
                else if($variationslist->contains('size', $variation))
                    $existingCartItem = CartItem::where('cart_id', $this->getCartId($user_id))->where('prod_id', $product->prod_id)->where('size', $variation)->first();
            }
        }
        else
            $existingCartItem = CartItem::where('cart_id', $this->getCartId($user_id))->where('prod_id', $product->prod_id)->first();

        if($existingCartItem)
        {
            $existingCartItem->quantity += $quantity;
            $existingCartItem->subtotal = $product->price * $existingCartItem->quantity;
            $existingCartItem->save();
        }
        else
        {
            $cartItem = new CartItem();
            $cartItem->cart_id = $this->getCartId($user_id);
            $cartItem->prod_id = $product->prod_id;
            $cartItem->seller_id = $product->user_id;
            $cartItem->prod_name = $product->prod_name;
            $cartItem->prod_image = $product->image1;
            $cartItem->quantity = $quantity;

            if (!empty($variation))
            {
                if(strpos($variation, ' - ') !== false)
                {
                    list($color, $size) = explode(' - ', $variation);
                    $cartItem->color = $color;
                    $cartItem->size = $size;
                }
                else
                {
                    if($variation !== null)
                    {
                        if ($variationslist->contains('color', $variation))
                            $cartItem->color = $variation;
                        else if ($variationslist->contains('size', $variation))
                            $cartItem->size = $variation;
                    }
                    else
                    {
                        $cartItem->color = null;
                        $cartItem->size = null;
                    }
                }
            }
            else
            {
                $cartItem->color = null;
                $cartItem->size = null;
            }

            $subtotal = $product->price * $quantity;
            $cartItem->subtotal = $subtotal;
            $cartItem->save();
        }

        return redirect('/cart');
    }

    private function getCartId($user_id)        //helper function to get the buyers cart_id
    {
        $cart = Cart::where('user_id',$user_id)->first();
        return $cart->cart_id;
    }

    public function reviewProduct($prod_id)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/catalog')->with('fail','This account has been disabled!');
        $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
        $product = Product::find($prod_id);
        if(!$product)
            return redirect('/catalog')->with('fail','Product does not exist!');
        else
            return view('reviewproduct',compact('userdata','product','notifications'));
    }

    public function saveReview(Request $request)
    {
        $request->validate([
            'rate' => 'required|integer|between:1,5',
            'content' => 'required|string',
        ]);
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/catalog')->with('fail','This account has been disabled!');
        $product = Product::find($request->prod_id);
        if(!$product)
            return redirect('/catalog')->with('fail','Product does not exist!');

        $review = new Review();
        $review->user_id = $request->user_id;
        $review->prod_id = $request->prod_id;
        $review->rate = $request->rate;
        $review->content = $request->content;
        $review->save();

        return redirect('/catalog/fulldetails/'.$request->prod_id)->with('success','Review submitted successfully!');
    }

    public function cart()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            if($userdata->is_disabled === 1)
                return redirect('/catalog')->with('fail','This account has been disabled!');
            $cart = Cart::where('user_id',$userdata->user_id)->first();
            $cartitems = CartItem::where('cart_id',$cart->cart_id)->get();
            $total = $cartitems->sum('subtotal');
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('cart',compact('userdata','cart','cartitems','total','notifications'));
        }
        else
            return redirect('/login');
    }

    public function cartUpdate(Request $request, $cartitem_id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/catalog')->with('fail','This account has been disabled!');

        $cartitem = CartItem::find($cartitem_id);

        if (!$cartitem) {
            return redirect()->back()->with('error', 'Cart item not found.');
        }

        if ($request->input('quantity_change') === 'increment') {
            $cartitem->quantity += 1;
        } elseif ($request->input('quantity_change') === 'decrement' && $cartitem->quantity > 1) {
            $cartitem->quantity -= 1;
        }

        $cartitem->subtotal = $cartitem->quantity * $cartitem->product->price;
        $cartitem->save();

        return back();
    }

    public function cartDelete($cartitem_id)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/catalog')->with('fail','This account has been disabled!');
        $cartitem = CartItem::find($cartitem_id);
        if(!$cartitem)
            return redirect('/seller/sellerprofile/addressbook')->with('fail','Item not found!');
        else
        {
            $cartitem->delete();
            return back();
        }
    }

    public function checkout()
    {
        $user = User::where('user_id', Session::get('loginId'))->first();
        if($user->is_disabled === 1)
                return redirect('/catalog')->with('fail','This account has been disabled!');
        $buyerAddress = $user->addresses()->where('default', 1)->first();
        $cartItems = CartItem::where('cart_id', $user->cart->cart_id)->with('product.user')->get();     //load the product and its associated seller user

        $cartItemsBySeller = $cartItems->groupBy(function ($item) {
            return $item->product->user->user_id;
        });

        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->prod_id);

            if($cartItem->color || $cartItem->size)
            {
                $variation = Variation::where('prod_id', $cartItem->prod_id)->where('color', $cartItem->color)->where('size', $cartItem->size)->first();
                if(!$variation)
                    return redirect('/cart')->with('fail','There are product variations that no longer exist. Remove them before checking out!');
                if($variation->quantity === 0)
                    return redirect('/cart')->with('fail','There are product variations that are out of stock. Remove them before checking out!');
                if($cartItem->quantity > $variation->quantity)
                    return redirect('/cart')->with('fail','There are product variations whose stock are less than the amount you want to purchase. Lessen the amount or remove them before checking out!');
            }
            else
            {
                if($product->prod_status === 'DELETED')
                    return redirect('/cart')->with('fail','There are products that no longer exist. Remove them before checking out!');
                if($product->quantity === 0)
                    return redirect('/cart')->with('fail','There are products that are out of stock. Remove them before checking out!');
                if($cartItem->quantity > $product->quantity)
                    return redirect('/cart')->with('fail','There are products whose current stock are less than the amount you want to purchase. Lessen the amount or remove them before checking out!');
            }
        }

        $total = 0;
        $totalShippingFee = 0;
        $fee = ShipFee::where('fee_id',1)->first();

        foreach ($cartItemsBySeller as $sellerId => $items) {
            $seller = $items[0]->product->user;
            $sellerShippingFee = $fee->fee;
            $totalQuantity = $items->sum('quantity');

            //add 30 to shipping fee for every 5th item
            $additionalFee = (int)($totalQuantity / 5) * 30;
            $sellerShippingFee += $additionalFee;
            $totalShippingFee += $sellerShippingFee;

            $sellerTotal = $items->sum('subtotal');
            $total += $sellerTotal;
        }

        $total += $totalShippingFee;
        $notifications = Notification::where('receiver_id',$user->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();

        return view('checkout',compact('buyerAddress','cartItemsBySeller','fee','total','totalShippingFee','notifications'));
    }

    public function finalCheckout(Request $request)
    {
        $request->validate([
            'province' => 'required',
            'city' => 'required',
            'street_add' => 'required',
            'postal' => 'required|digits:4',
            'phone' => 'required|digits:11'
        ]);

        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $defaultaddress = Address::where('user_id',$userdata->user_id)->where('default',true)->first();
        $cartItems = CartItem::where('cart_id', $userdata->cart->cart_id)->with('product.user')->get();     //load the product and its associated seller user

        $cartItemsBySeller = $cartItems->groupBy(function ($item) {
            return $item->product->user->user_id;
        });

        foreach ($cartItems as $cartItem) {
            $product = Product::where('prod_id',$cartItem->prod_id)->lockForUpdate()->first();

            if($cartItem->color || $cartItem->size)
            {
                $variation = Variation::where('prod_id', $cartItem->prod_id)->where('color', $cartItem->color)->where('size', $cartItem->size)->lockForUpdate()->first();
                if(!$variation)
                    return redirect('/cart')->with('fail','There are product variations that no longer exist. Remove them before checking out!');
                if($variation->quantity === 0)
                    return redirect('/cart')->with('fail','There are product variations that are out of stock. Remove them before checking out!');
                if($cartItem->quantity > $variation->quantity)
                    return redirect('/cart')->with('fail','There are product variations whose stock are less than the amount you want to purchase. Lessen the amount or remove them before checking out!');
            }
            else
            {
                if($product->prod_status === 'DELETED')
                    return redirect('/cart')->with('fail','There are products that no longer exist. Remove them before checking out!');
                if($product->quantity === 0)
                    return redirect('/cart')->with('fail','There are products that are out of stock. Remove them before checking out!');
                if($cartItem->quantity > $product->quantity)
                    return redirect('/cart')->with('fail','There are products whose current stock are less than the amount you want to purchase. Lessen the amount or remove them before checking out!');
            }
        }

        DB::beginTransaction();
        try
        {
            $fee = ShipFee::where('fee_id',1)->first();

            foreach ($cartItemsBySeller as $sellerId => $items) {
                $seller = $items[0]->product->user;
                $sellerShippingFee = $fee->fee;
                $totalQuantity = $items->sum('quantity');

                $additionalFee = (int)($totalQuantity / 5) * 30;
                $sellerShippingFee += $additionalFee;
                
                $order = new Order();
                $order->buyer_id = $userdata->user_id;
                $order->seller_id = $seller->user_id;
                $order->phone = $request->phone;
                $order->province = $request->province;
                $order->city = $request->city;
                $order->street_add = $request->street_add;
                $order->postal = $request->postal;
                $order->date = now();
                $order->ship_fee = $sellerShippingFee;
                $order->status = 'UNPAID';
                $order->total = $sellerShippingFee;
                $order->save();

                $notify = new Notification();
                $notify->sender_id = $userdata->user_id;
                $notify->receiver_id = $seller->user_id;
                $notify->content = "You have a new incoming order: Order " . $order->order_id;
                $notify->quick_link = url('/seller/orderdetails/'.$order->order_id);
                $notify->save();

                foreach ($items as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->order_id;
                    $orderItem->prod_id = $item->prod_id;
                    $orderItem->prod_name = $item->prod_name;
                    $orderItem->prod_image = $item->prod_image;
                    $orderItem->color = $item->color;
                    $orderItem->size = $item->size;
                    $orderItem->quantity = $item->quantity;
                    $orderItem->subtotal = $item->subtotal;
                    $orderItem->save();

                    $order->total += $orderItem->subtotal;
                    $order->save();

                    $item->delete();

                    if($orderItem->color || $orderItem->size)
                    {
                        $variation = Variation::where('prod_id',$orderItem->prod_id)->where('color',$orderItem->color)->where('size',$orderItem->size)->lockForUpdate()->first();
                        if($variation)
                        {
                            $variation->quantity -= $orderItem->quantity;
                            $variation->save();
    
                            $product = Product::find($orderItem->prod_id);
                            $product->quantity -= $orderItem->quantity;
                            $product->save();
                        }
                    }
                    else
                    {
                        $product = Product::find($orderItem->prod_id);
                        $product->quantity -= $orderItem->quantity;
                        $product->save();
                    }
                }
            }
            DB::commit();
            return redirect('/dashboard/main/');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return redirect('/cart')->with('fail','An error occurred while processing your order. Please try again.');
        }
    }

    public function orderDetails($order_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $order = Order::where('order_id',$order_id)->first();
            $seller = User::where('user_id',$order->seller_id)->first();
            $orderitems = OrderItem::where('order_id',$order->order_id)->get();
            $merchtotal = $order->total - $order->ship_fee;
            $releasedate = Release::whereIn('orderitem_id',$orderitems->pluck('orderitem_id'))->distinct()->value('date_sent');
            $receiveDate = Carbon::parse($order->receive_date);
            $currentDate = Carbon::now();
            $dateDifference = $receiveDate->diffInDays($currentDate);
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('orderfulldetails',compact('userdata','order','seller','orderitems','merchtotal','releasedate','dateDifference','notifications'));
        }
        else
            return redirect('/login');
    }

    public function orderUnpaid(Request $request)
    {
        $request->validate(['ref_no' => 'required|string|min:5|max:20',]);
        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $order = Order::where('order_id',$request->order_id)->first();
        if($order)
        {
            DB::beginTransaction();

            try
            {
                if($order->status === 'CANCELED')
                    return back()->with('fail','This order has been canceled.');
                if($order->status === 'ON THE WAY')
                    return back()->with('fail','This order is being shipped and is on the way.');
                if($order->status === 'COMPLETED')
                    return back()->with('fail','This order has been completed and delivered.');
                if($order->ref_no === null && $request->ref_no !== null)
                {
                    $notify = new Notification();
                    $notify->sender_id = $userdata->user_id;
                    $notify->receiver_id = $order->seller_id;
                    $notify->content = "Proof of payment (GCash reference number) has been received for Order " . $order->order_id;
                    $notify->quick_link = url('/seller/orderdetails/'.$order->order_id);
                    $notify->save();
                }
                $order->ref_no = $request->ref_no;
                $order->save();

                DB::commit();
                return back()->with('success','Proof of payment has been updated. Please wait for the seller to verify the reference number.');
            }
            catch(\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while trying to post your reference number. Please try again.');
            }
        }
        else
            return redirect('/dashboard/main/');
    }

    public function orderCancelPage($order_id)
    {
        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $order = Order::where('order_id',$order_id)->first();
        if($order)
        {
            if($order->status !== 'UNPAID')
                return redirect('/dashboard/orderdetails/'.$order->order_id)->with('fail','This order can no longer be canceled.');
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('ordercancel',compact('order','notifications'));
        }
        else
            return redirect('/dashboard/main/');
    }

    public function orderCancel(Request $request)
    {
        $request->validate(['cancel_reason' => 'required|string',]);
        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $order = Order::where('order_id',$request->order_id)->first();
        if($order)
        {
            if($order->status !== 'UNPAID')
                return redirect('/dashboard/orderdetails/'.$order->order_id)->with('fail','This order can no longer be canceled.');

            DB::beginTransaction();

            try
            {
                $order->status = "CANCELED";
                $order->cancel_reason = $request->cancel_reason;
                $order->save();

                foreach ($order->orderItems as $orderItem) {
                    if($orderItem->color || $orderItem->size)
                    {
                        $variation = Variation::where('prod_id',$orderItem->prod_id)->where('color',$orderItem->color)->where('size',$orderItem->size)->first();
                        if($variation)
                        {
                            $variation->quantity += $orderItem->quantity;
                            $variation->save();

                            $product = Product::find($orderItem->prod_id);
                            $product->quantity += $orderItem->quantity;
                            $product->save();
                        }
                    }
                    else
                    {
                        $product = Product::find($orderItem->prod_id);
                        $product->quantity += $orderItem->quantity;
                        $product->save();
                    }
                }

                $notify = new Notification();
                $notify->sender_id = $userdata->user_id;
                $notify->receiver_id = $order->seller_id;
                $notify->content = "Order " . $order->order_id . " has been canceled. Check order for additional details.";
                $notify->quick_link = url('/seller/orderdetails/'.$order->order_id);
                $notify->save();

                DB::commit();
                return redirect('/dashboard/orderdetails/'.$order->order_id)->with('success','Order ID ' . $order->order_id . ' has been canceled.');
            }
            catch(\Exception $e)
            {
                DB::rollback();
                return redirect('/dashboard/orderdetails/'.$order->order_id)->with('fail','An error occurred canceling the order. Please try again.');
            }
        }
        else
            return redirect('/dashboard/main/');
    }

    public function orderComplete($order_id)
    {
        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $order = Order::where('order_id',$order_id)->first();
        if($order)
        {
            DB::beginTransaction();

            try
            {
                if($order->status === 'COMPLETED')
                    return back()->with('fail','This order has been completed and delivered.');
                $order->status = "COMPLETED";
                $order->receive_date = now();
                $order->save();

                $notify = new Notification();
                $notify->sender_id = $userdata->user_id;
                $notify->receiver_id = $order->seller_id;
                $notify->content = "Order " . $order->order_id . " has been received by buyer.";
                $notify->quick_link = url('/seller/orderdetails/'.$order->order_id);
                $notify->save();

                DB::commit();
                return back();
            }
            catch(\Exception $e)
            {
                DB::rollback();
                return redirect('/dashboard/orderdetails/'.$order->order_id)->with('fail','An error occurred completing the order. Please try again.');
            }
        }
        else
            return redirect('/dashboard/main/');
    }

    public function requestExchangePage($order_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            if($userdata->is_disabled === 1)
                return back()->with('fail','This account has been disabled!');
            $order = Order::where('order_id',$order_id)->first();
            $orderitems = OrderItem::where('order_id',$order_id)->get();
            $seller = User::where('user_id',$order->seller_id)->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            if($order)
            {
                $orderDate = Carbon::parse($order->date);
                $currentDate = Carbon::now();
                $dateDifference = $orderDate->diffInDays($currentDate);
                if($dateDifference >= 2)
                    return redirect('/dashboard/orderdetails/'.$order->order_id)->with('fail','You can no longer request for an exchange.');
                if($order->has_exchange === 1)
                    return redirect('/dashboard/orderdetails/'.$order->order_id)->with('fail','You can only request for a return once per order.');
                return view('exchange',compact('userdata','order','orderitems','seller','notifications'));
            }
            else
                return redirect('/dashboard/main/');
        }
        else
            return redirect('/login');
    }

    public function exchangeList($status = 'All')
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            if($status === 'All')
                $exchanges = Exchange::with('seller','exchangeItems')->where('buyer_id',$userdata->user_id)->orderBy('exchange_id','desc')->paginate(10);
            if($status === 'PENDING')
                $exchanges = Exchange::with('seller','exchangeItems')->where('buyer_id',$userdata->user_id)->where('status','PENDING')->orderBy('exchange_id','desc')->paginate(10);
            if($status === 'CANCELED')
                $exchanges = Exchange::with('seller','exchangeItems')->where('buyer_id',$userdata->user_id)->where('status','CANCELED')->orderBy('exchange_id','desc')->paginate(10);
            if($status === 'REJECTED')
                $exchanges = Exchange::with('seller','exchangeItems')->where('buyer_id',$userdata->user_id)->where('status','REJECTED')->orderBy('exchange_id','desc')->paginate(10);
            if($status === 'ON THE WAY TO SELLER')
                $exchanges = Exchange::with('seller','exchangeItems')->where('buyer_id',$userdata->user_id)->where('status','ON THE WAY TO SELLER')->orderBy('exchange_id','desc')->paginate(10);
            if($status === 'ON THE WAY TO BUYER')
                $exchanges = Exchange::with('seller','exchangeItems')->where('buyer_id',$userdata->user_id)->where('status','ON THE WAY TO BUYER')->orderBy('exchange_id','desc')->paginate(10);
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('exchangelist',compact('userdata','exchanges','notifications'));
        }
        else
            return redirect('/login');
    }

    public function exchangeDetails($exchange_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $exchange = Exchange::where('exchange_id',$exchange_id)->first();
            $seller = User::where('user_id',$exchange->seller_id)->first();
            $exchangeitems = ExchangeItem::where('exchange_id',$exchange->exchange_id)->get();
            $address = Address::where('user_id',$exchange->seller_id)->where('default',true)->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('exchangefulldetails',compact('userdata','exchange','seller','exchangeitems','address','notifications'));
        }
        else
            return redirect('/login');
    }

    public function exchange(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array',
            'reason' => 'required|string',
            'quantities.*' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $orderitemId = str_replace('quantities.','',$attribute);
                    $orderitem = OrderItem::find($orderitemId);
        
                    if (!$orderitem) {
                        $fail('Invalid order item.');
                    }
        
                    $maxQuantity = $orderitem->quantity;
        
                    if ($value < 1 || $value > $maxQuantity) {
                        $fail("The quantity must be between 1 and $maxQuantity.");
                    }
                },
            ],
            'proof_pic1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'proof_pic2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'proof_pic3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'details' => 'nullable',
        ], [
            'quantities.*.required' => 'The quantity field is required.',
            'quantities.*.integer' => 'The quantity must be a number.',
            'quantities.*.min' => 'The quantity must be at least 1.',
        ]);

        DB::beginTransaction();
        try
        {
            $userdata = User::where('user_id', Session::get('loginId'))->first();
            $order = Order::where('order_id',$request->order_id)->first();
            $orderDate = Carbon::parse($order->date);
            $currentDate = Carbon::now();
            $dateDifference = $orderDate->diffInDays($currentDate);
            if($dateDifference >= 2)
                return redirect('/dashboard/orderdetails/'.$order->order_id)->with('fail','You can no longer request for an exchange.');
            if($order->has_exchange === 1)
                return redirect('/dashboard/orderdetails/'.$order->order_id)->with('fail','You can only request for a return once per order.');

            $exchange = new Exchange();
            $exchange->buyer_id = $request->buyer_id;
            $exchange->seller_id = $request->seller_id;
            $exchange->phone = $request->phone;
            $exchange->province = $request->province;
            $exchange->city = $request->city;
            $exchange->street_add = $request->street_add;
            $exchange->postal = $request->postal;
            $exchange->reason = $request->reason;
            $exchange->details = $request->details;
            $exchange->date = now();
            $exchange->status = 'PENDING';
            $proof_pic1 = $request->file('proof_pic1')->getClientOriginalName();
            $request->file('proof_pic1')->storeAs('public/custom_exch_pics/',$proof_pic1);
            $exchange->proof_pic1 = $proof_pic1;
            if($request->hasFile('proof_pic2'))
            {
                $proof_pic2 = $request->file('proof_pic2')->getClientOriginalName();
                $request->file('proof_pic2')->storeAs('public/custom_exch_pics/',$proof_pic2);
                $exchange->proof_pic2 = $proof_pic2;
            }
            else
                $exchange->proof_pic2 = 'default_pics/default_proof_pic.jpg';
            if($request->hasFile('proof_pic3'))
            {
                $proof_pic3 = $request->file('proof_pic3')->getClientOriginalName();
                $request->file('proof_pic3')->storeAs('public/custom_exch_pics/',$proof_pic3);
                $exchange->proof_pic3 = $proof_pic3;
            }
            else
                $exchange->proof_pic3 = 'default_pics/default_proof_pic.jpg';
            $exchange->save();

            $order->has_exchange = 1;
            $order->save();

            $notify = new Notification();
            $notify->sender_id = $userdata->user_id;
            $notify->receiver_id = $exchange->seller_id;
            $notify->content = "You have a new incoming exchange request: Exchange Request " . $exchange->exchange_id;
            $notify->quick_link = url('/seller/exchangedetails/'.$exchange->exchange_id);
            $notify->save();

            foreach ($request->input('selected_items',[]) as $orderitemId) {
                $quantity = $request->input('quantities.' . $orderitemId, 0);
                $orderitem = OrderItem::find($orderitemId);
            
                if($orderitem && $quantity > 0 && $quantity <= $orderitem->quantity) {
                    $exchangeItem = new ExchangeItem();
                    $exchangeItem->exchange_id = $exchange->exchange_id;
                    $exchangeItem->prod_id = $orderitem->prod_id;
                    $exchangeItem->prod_name = $orderitem->prod_name;
                    $exchangeItem->prod_image = $orderitem->prod_image;
                    $exchangeItem->color = $orderitem->color;
                    $exchangeItem->size = $orderitem->size;
                    $exchangeItem->quantity = $quantity;
                    $exchangeItem->save();
                }
                else
                {
                    DB::rollback();
                    return back()->with('fail','Quantity allowed is only within the quantity range of the order item. Please try again.');
                }
            }
            
            DB::commit();
            return redirect('/exchange/exchangedetails/'.$exchange->exchange_id)->with('success','Exchange request submitted successfully!');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','An error occurred while submitting your request. Please try again.');
        }
    }

    public function exchangeCancel($exchange_id)
    {
        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $exchange = Exchange::where('exchange_id',$exchange_id)->first();
        if($exchange)
        {
            DB::beginTransaction();

            try
            {
                if($exchange->status !== 'PENDING')
                    return redirect('/exchange/exchangedetails/'.$exchange->exchange_id)->with('fail','This request can no longer be canceled.');

                $exchange->status = "CANCELED";
                $exchange->save();

                $notify = new Notification();
                $notify->sender_id = $userdata->user_id;
                $notify->receiver_id = $exchange->seller_id;
                $notify->content = "You have a new incoming exchange request: Exchange Request " . $exchange->exchange_id;
                $notify->quick_link = url('/seller/exchangedetails/'.$exchange->exchange_id);
                $notify->save();

                DB::commit();
                return back()->with('success','Exchange Request ID ' . $exchange->exchange_id . ' has been canceled.');
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while canceling the request. Please try again.');
            }
        }
        else
            return redirect('/exchange/list/');
    }

    public function markNotif(Request $request)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        Notification::where('receiver_id',$userdata->user_id)->update(['is_read' => 1]);
        return response()->json();
    }

    public function orderTracking()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('ordertracking',compact('notifications'))->with(['loggedIn' => true]);
        }
        else
            return view('ordertracking')->with(['loggedIn' => false]);
    }

    public function ticketList()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            $warns = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->get();
            return view('ticketlist',compact('notifications','warns'));
        }
        else
            return redirect('/login');
    }
}
