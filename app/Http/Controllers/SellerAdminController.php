<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\Variation;
use App\Models\Review;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\StockAudit;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Exchange;
use App\Models\ExchangeItem;
use App\Models\Notification;
use App\Models\Release;
use App\Models\ReturnItem;
use App\Models\ShipFee;
use Session;
use Hash;

class SellerAdminController extends Controller
{
    public function sellerProfile()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $defaultaddress = Address::where('user_id',$userdata->user_id)->where('default',true)->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            $warns = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->get();
            return view('seller.sellerprofile',compact('userdata','defaultaddress','notifications','warns'));
        }
        else
            return redirect('/login');
    }

    public function adminProfile()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $fee = ShipFee::where('fee_id',1)->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
            return view('admin.adminprofile',compact('userdata','fee','notifications'));
        }
        else
            return redirect('/login');
    }

    public function saveSellerDetails(Request $request)
    {
        $request->validate([
            'org_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required',
            'chat_link' => ['required', 'regex:/^(https?:\/\/(www\.)?facebook\.com\/.+)|(https?:\/\/(www\.)?messenger\.com\/.+)/'],
            'gcash_no' => 'required|digits:11',
            'prof_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = User::find($request->user_id);
        $warnCount = Notification::where('receiver_id',$user->user_id)->where('is_warn',true)->count();
            if($warnCount >= 3)
                return redirect('/seller/sellerprofile')->with('fail','This account has been disabled!');
        if($user->email == $request->email)
        {
            if ($request->hasFile('prof_pic'))
            {
                $pic = $request->file('prof_pic')->getClientOriginalName();
                $request->file('prof_pic')->storeAs('public/custom_prof_pics/',$pic);

                User::where('user_id','=',$request->user_id)->update([
                    'org_name' => $request->org_name,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'chat_link' => $request->chat_link,
                    'gcash_no' => $request->gcash_no,
                    'prof_pic' => $pic
                ]);
            }
            else
            {
                User::where('user_id','=',$request->user_id)->update([
                    'org_name' => $request->org_name,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'chat_link' => $request->chat_link,
                    'gcash_no' => $request->gcash_no
                ]);
            }
            return back()->with('success','Profile Updated');
        }
        else if($user->email != $request->email)
        {
            $user2 = User::where('email',$request->email)->first();
            if($user2)
                return back()->with('fail','Email has been taken!');
            else
            {
                if ($request->hasFile('prof_pic'))
                {
                    $pic = $request->file('prof_pic')->getClientOriginalName();
                    $request->file('prof_pic')->storeAs('public/custom_prof_pics/',$pic);

                    User::where('user_id','=',$request->user_id)->update([
                        'org_name' => $request->org_name,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'chat_link' => $request->chat_link,
                        'gcash_no' => $request->gcash_no,
                        'prof_pic' => $pic
                    ]);
                }
                else
                {
                    User::where('user_id','=',$request->user_id)->update([
                        'org_name' => $request->org_name,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'chat_link' => $request->chat_link,
                        'gcash_no' => $request->gcash_no
                    ]);
                }
                return back()->with('success','Profile Updated');
            }
        }
    }

    public function saveAdminDetails(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required',
            'prof_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = User::find($request->user_id);
        if($user->email == $request->email)
        {
            if ($request->hasFile('prof_pic'))
            {
                $pic = $request->file('prof_pic')->getClientOriginalName();
                $request->file('prof_pic')->storeAs('public/custom_prof_pics/',$pic);

                User::where('user_id','=',$request->user_id)->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'prof_pic' => $pic
                ]);
            }
            else
            {
                User::where('user_id','=',$request->user_id)->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email
                ]);
            }
            return back()->with('success','Profile Updated');
        }
        else if($user->email != $request->email)
        {
            $user2 = User::where('email',$request->email)->first();
            if($user2)
                return back()->with('fail','Email has been taken!');
            else
            {
                if ($request->hasFile('prof_pic'))
                {
                    $pic = $request->file('prof_pic')->getClientOriginalName();
                    $request->file('prof_pic')->storeAs('public/custom_prof_pics/',$pic);

                    User::where('user_id','=',$request->user_id)->update([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'prof_pic' => $pic
                    ]);
                }
                else
                {
                    User::where('user_id','=',$request->user_id)->update([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email
                    ]);
                }
                return back()->with('success','Profile Updated');
            }
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:7|max:15|confirmed',
        ]);

        $pass = User::find(Session::get('loginId'));
        $warnCount = Notification::where('receiver_id',$pass->user_id)->where('is_warn',true)->count();
        if($warnCount >= 3)
            return redirect('/seller/sellerprofile')->with('fail','This account has been disabled!');

        if (!Hash::check($request->current_password, $pass->password))
            return back()->with('fail-pass','Current password is incorrect!');

        $pass->password = Hash::make($request->new_password);
        $pass->save();

        return back()->with('success-pass','Password changed successfully!');
    }

    public function sellerAddressBook()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $addresses = Address::where('user_id','=',$userdata->user_id)->get();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.selleraddress',compact('userdata','addresses','notifications'));
        }
        else
            return redirect('/login');
    }

    public function addSellerAddress()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            if($userdata->is_disabled === 1)
                return redirect('/seller/sellerprofile/addressbook')->with('fail','This account has been disabled!');
            $provinces = ["Negros Oriental","Negros Occidental"];
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.newselleraddress',compact('userdata','provinces','notifications'));
        }
        else
            return redirect('/login');
    }

    public function editSellerAddress($add_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $warnCount = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->count();
            if($warnCount >= 3)
                return redirect('/seller/sellerprofile/addressbook')->with('fail','This account has been disabled!');
            $provinces = ["Negros Oriental","Negros Occidental"];
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            $address = Address::find($add_id);
            if (!$address)
                return redirect('/seller/sellerprofile/addressbook')->with('fail','Address not found!');
            return view('seller.editselleraddress',compact('userdata','provinces','address','notifications'));
        }
        else
            return redirect('/login');
    }

    public function saveSellerAddress(Request $request)
    {
        $request->validate([
            'province' => 'required',
            'city' => 'required',
            'street_add' => 'required',
            'postal' => 'required',
            'phone' => 'required|digits:11'
        ]);
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/seller/sellerprofile/addressbook')->with('fail','This account has been disabled!');

        $new = new Address();
        $new->user_id = $request->user_id;
        $new->province = $request->province;
        $new->city = $request->city;
        $new->street_add = $request->street_add;
        $new->postal = $request->postal;
        $new->phone = $request->phone;
        $existingAddresses = Address::where('user_id', $request->user_id)->count();
        if ($existingAddresses == 0)
            $new->default = 1;
        $save = $new->save();

        if($save)
        {
            $usertype = User::where('user_id','=',Session::get('loginId'))->first();
            if($usertype->type === "SELLER")
                return redirect('/seller/sellerprofile/addressbook')->with('success','New address added!');
            else if($usertype->type === "BUYER")
                return redirect('/dashboard/addressbook')->with('success','New address added!');
        }
        else
            return back()->with('fail','Adding of new address failed!');
    }

    public function updateSellerAddress(Request $request)
    {
        $request->validate([
            'province' => 'required',
            'city' => 'required',
            'street_add' => 'required',
            'postal' => 'required',
            'phone' => 'required|digits:11'
        ]);
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $warnCount = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->count();
        if($warnCount >= 3)
            return redirect('/seller/sellerprofile/addressbook')->with('fail','This account has been disabled!');
        $address = Address::where('add_id', $request->add_id)->first();

        if($address)
        {
            $updateData = [
                'province' => $request->province,
                'city' => $request->city,
                'street_add' => $request->street_add,
                'postal' => $request->postal,
                'phone' => $request->phone
            ];

            if($request->has('default') && $request->default)
            {
                Address::where('user_id',$address->user_id)->update(['default' => 0]);
                $updateData['default'] = 1;     //set this address as the default
            }

            Address::where('add_id','=',$request->add_id)->update($updateData);

            return back()->with('success', 'Address Updated');
        }
        else
        {
            $usertype = User::where('user_id','=',Session::get('loginId'))->first();
            if($usertype->type === "SELLER")
                return redirect('/seller/sellerprofile/addressbook')->with('fail', 'Address not found!');
            else if ($usertype->type === "BUYER")
                return redirect('/dashboard/addressbook')->with('fail','Address not found!');
        }
    }

    public function deleteSellerAddress($add_id)
    {
        $usertype = User::where('user_id','=',Session::get('loginId'))->first();
        $warnCount = Notification::where('receiver_id',$usertype->user_id)->where('is_warn',true)->count();
        if($warnCount >= 3)
            return redirect('/dashboard/addressbook')->with('fail','This account has been disabled!');
        $address = Address::find($add_id);
        if(!$address)
        {
            if($usertype->type === "SELLER")
                return redirect('/seller/sellerprofile/addressbook')->with('fail','Address not found!');
            else if($usertype->type === "BUYER")
                return redirect('/dashboard/addressbook')->with('fail','Address not found!');
        }  
        else
        {
            if($address->default)       //check if address being deleted is the default address
            {
                $nextAddress = Address::where('user_id',$address->user_id)->where('add_id','!=',$add_id)->first();     //find the next address of the user and set it as default if it exists
                if($nextAddress)
                {
                    $nextAddress->default = 1;
                    $nextAddress->save();
                }
            }
            $address->delete();
            return back()->with('success','Address Deleted');
        }
    }

    public function sellerProduct()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $productlist = Product::where('user_id','=',$userdata->user_id)->wherein('prod_status',['ENABLED','DISABLED'])->get();
            $categories = Category::all();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.sellerproduct',compact('userdata','productlist','categories','notifications'));
        }
        else
            return redirect('/login');
    }

    public function rejectedProduct()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $rejectedlist = Product::where('user_id','=',$userdata->user_id)->where('prod_status','DELETED')->get();
            $categories = Category::all();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.rejectedproduct',compact('userdata','rejectedlist','categories','notifications'));
        }
        else
            return redirect('/login');
    }

    public function addSellerProduct()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            if($userdata->is_disabled === 1)
                return redirect('/seller/product')->with('fail','This account has been disabled!');
            $categories = Category::all();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.addsellerproduct',compact('userdata','categories','notifications'));
        }
        else
            return redirect('/login');
    }

    public function editSellerProduct($prod_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $warnCount = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->count();
            if($warnCount >= 3)
                return redirect('/seller/product')->with('fail','This account has been disabled!');
            $product = Product::find($prod_id);
            $categories = Category::all();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            if(!$product)
                return redirect('/seller/product')->with('fail','Product not found!');
            return view('seller.editsellerproduct',compact('userdata','product','categories','notifications'));
        }
        else
            return redirect('/login');
    }

    public function rejectedDetails($prod_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            if($userdata->is_disabled === 1)
                return redirect('/seller/rejected')->with('fail','This account has been disabled!');
            $product = Product::find($prod_id);
            if(!$product)
                return redirect('/seller/rejected')->with('fail','Product not found!');
            $variationexist = Variation::where('prod_id','=',$product->prod_id)->exists();
            $variationlist = Variation::where('prod_id','=',$product->prod_id)->get();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.rejecteddetails',compact('userdata','product','variationexist','variationlist','notifications'));
        }
        else
            return redirect('/login');
    }

    public function editProductVariation($prod_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $warnCount = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->count();
            if($warnCount >= 3)
                return redirect('/seller/product')->with('fail','This account has been disabled!');
            $productdetails = Product::find($prod_id);
            $colors = Variation::where('prod_id',$productdetails->prod_id)->select('color')->distinct()->get();
            $sizes = Variation::where('prod_id',$productdetails->prod_id)->select('size')->distinct()->get();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.editproductvariation',compact('userdata','colors','sizes','productdetails','notifications'));
        }
        else
            return redirect('/login');
    }

    public function removeColor(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $quantityRemove = Variation::where('prod_id',$request->prod_id)->where('color',$request->color)->sum('quantity');
            $remainingColors = Variation::where('prod_id',$request->prod_id)->where('color','!=',null)->distinct()->count('color');
            
            if ($remainingColors === 1)
            {
                Variation::where('prod_id',$request->prod_id)->where('color',$request->color)->update([
                    'color' => null,
                    'quantity' => 0,
                ]);
            }
            else
                Variation::where('prod_id',$request->prod_id)->where('color',$request->color)->delete();

            $product = Product::find($request->prod_id);
            if($product)
            {
                $product->quantity -= $quantityRemove;
                $product->save();
                DB::commit();
                return back()->with('success','Color removed successfully!');
            }
            else
            {
                DB::commit();
                return redirect('/seller/product')->with('fail','Product not found!');
            }
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','Failed to remove color!');
        }
    }

    public function removeSize(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $quantityRemove = Variation::where('prod_id',$request->prod_id)->where('size',$request->size)->sum('quantity');
            $remainingSizes = Variation::where('prod_id',$request->prod_id)->where('size','!=',null)->distinct()->count('size');

            if ($remainingSizes === 1)
            {
                Variation::where('prod_id',$request->prod_id)->where('size',$request->size)->update([
                    'size' => null,
                    'quantity' => 0,
                ]);
            }
            else
                Variation::where('prod_id',$request->prod_id)->where('size',$request->size)->delete();

            $product = Product::find($request->prod_id);
            if($product)
            {
                $product->quantity -= $quantityRemove;
                $product->save();
                DB::commit();
                return back()->with('success','Size removed successfully!');
            }
            else
            {
                DB::commit();
                return redirect('/seller/product')->with('fail','Product not found!');
            }
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','Failed to remove size!');
        }
    }

    public function addColor(Request $request)
    {
        $request->validate(['color' => 'required|max:30']);
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/seller/product')->with('fail','This account has been disabled!');
        $color = Variation::where('prod_id', $request->prod_id)->where('color', $request->color)->first();

        if (!$color)
        {
            $sizes = Variation::where('prod_id', $request->prod_id)->select('size')->distinct()->get();
            if ($sizes->isEmpty())
            {
                Variation::create([
                    'prod_id' => $request->prod_id,
                    'color' => $request->color,
                    'quantity' => 0,
                ]);
            }
            else
            {
                $nullColorVariations = Variation::where('prod_id', $request->prod_id)->where('color', null)->get();

                if ($nullColorVariations->isEmpty())
                {
                    foreach ($sizes as $size) {
                        Variation::create([
                            'prod_id' => $request->prod_id,
                            'color' => $request->color,
                            'size' => $size->size,
                            'quantity' => 0,
                        ]);
                    }
                }
                else
                {
                    foreach ($nullColorVariations as $variation) {
                        $variation->color = $request->color;
                        $variation->save();
                    }
                }
            }

            return back()->with('success', 'Color added successfully!');
        }
        else
            return back()->with('fail', 'Color already exists!');
    }

    public function addSize(Request $request)
    {
        $request->validate(['size' => 'required|max:30']);
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/seller/product')->with('fail','This account has been disabled!');
        $size = Variation::where('prod_id', $request->prod_id)->where('size', $request->size)->first();

        if (!$size)
        {
            $colors = Variation::where('prod_id', $request->prod_id)->select('color')->distinct()->get();
            if ($colors->isEmpty())
            {
                Variation::create([
                    'prod_id' => $request->prod_id,
                    'size' => $request->size,
                    'quantity' => 0,
                ]);
            }
            else
            {
                $nullSizeVariations = Variation::where('prod_id', $request->prod_id)->where('size', null)->get();

                if ($nullSizeVariations->isEmpty())
                {
                    foreach ($colors as $color) {
                        Variation::create([
                            'prod_id' => $request->prod_id,
                            'color' => $color->color,
                            'size' => $request->size,
                            'quantity' => 0,
                        ]);
                    }
                }
                else
                {
                    foreach ($nullSizeVariations as $variation) {
                        $variation->size = $request->size;
                        $variation->save();
                    }
                }
            }

            return back()->with('success', 'Size added successfully!');
        }
        else
            return back()->with('fail', 'Size already exists!');
    }

    public function saveSellerProduct(Request $request)
    {
        $request->validate([
            'prod_name' => 'required|string|max:40',
            'category' => 'required',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'color.*' => 'nullable|string|max:30',
            'size.*' => 'nullable|string|max:30',
        ]);
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/seller/product')->with('fail','This account has been disabled!');

        DB::beginTransaction();     //start database transaction to ensure data integrity
        try
        {
            $product = new Product();
            $product->user_id = $request->user_id;
            $product->prod_name = $request->prod_name;
            $product->category = $request->category;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->quantity = 0;
            if($request->hasFile('image1'))
            {
                $image1 = $request->file('image1')->getClientOriginalName();
                $request->file('image1')->storeAs('public/custom_prod_pics/',$image1);
                $product->image1 = $image1;
            }
            else
                $product->image1 = 'default_pics/default_prod_pic.jpg';
            if($request->hasFile('image2'))
            {
                $image2 = $request->file('image2')->getClientOriginalName();
                $request->file('image2')->storeAs('public/custom_prod_pics/',$image2);
                $product->image2 = $image2;
            }
            if($request->hasFile('image3'))
            {
                $image3 = $request->file('image3')->getClientOriginalName();
                $request->file('image3')->storeAs('public/custom_prod_pics/',$image3);
                $product->image3 = $image3;
            }
            if($request->hasFile('image4'))
            {
                $image4 = $request->file('image4')->getClientOriginalName();
                $request->file('image4')->storeAs('public/custom_prod_pics/',$image4);
                $product->image4 = $image4;
            }
            if($request->hasFile('image5'))
            {
                $image5 = $request->file('image5')->getClientOriginalName();
                $request->file('image5')->storeAs('public/custom_prod_pics/',$image5);
                $product->image5 = $image5;
            }
            $product->save();

            $colors = $request->color;
            $sizes = $request->size;

            if($request->has('color') && $request->has('size'))
            {
                foreach($colors as $color)
                {
                    foreach($sizes as $size)
                    {
                        $variation = new Variation();
                        $variation->prod_id = $product->prod_id;
                        $variation->color = $color;
                        $variation->size = $size;
                        $variation->quantity = 0;
                        $variation->save();
                    }
                }
            }
            else if(!$request->has('color') && $request->has('size'))
            {
                foreach($sizes as $size)
                {
                    $variation = new Variation();
                    $variation->prod_id = $product->prod_id;
                    $variation->size = $size;
                    $variation->quantity = 0;
                    $variation->save();
                }
            }
            else if(!$request->has('size') && $request->has('color'))
            {
                foreach($colors as $color)
                {
                    $variation = new Variation();
                    $variation->prod_id = $product->prod_id;
                    $variation->color = $color;
                    $variation->quantity = 0;
                    $variation->save();
                }
            }

            $notify = new Notification();									// THIS PART IS MODIFIED
            $notify->sender_id = $userdata->user_id;
            $notify->receiver_id = 1;
            $notify->content = "There is a new product that requires your approval!";
            $notify->quick_link = url('/admin/productlist/productdetails/'.$product->prod_id);
            $notify->save();

            DB::commit();
            return redirect('/seller/product')->with('success','Product added and sent for approval!');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','Failed to create product!');
        }
    }

    public function updateSellerProduct(Request $request)
    {
        $request->validate([
            'prod_name' => 'required|string|max:40',
            'category' => 'required',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $warnCount = Notification::where('receiver_id',$userdata->user_id)->where('is_warn',true)->count();
        if($warnCount >= 3)
            return redirect('/seller/product')->with('fail','This account has been disabled!');

        DB::beginTransaction(); // Start a database transaction to ensure data integrity
        try
        {
            $product = Product::findOrFail($request->prod_id);
            $product->prod_name = $request->prod_name;
            $product->category = $request->category;
            $product->description = $request->description;
            $product->price = $request->price;
            foreach (range(1,5) as $i)
            {
                $inputName = "image{$i}";
                if($request->hasFile($inputName))
                {
                    $image = $request->file($inputName)->getClientOriginalName();
                    $request->file($inputName)->storeAs('public/custom_prod_pics/',$image);
                    $product->$inputName = $image;
                }
            }
            $product->save();

            DB::commit();
            return redirect('/seller/product')->with('success','Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('fail','Failed to update product!');
        }
    }

    public function productStock($prod_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            if($userdata->is_disabled === 1)
                return redirect('/seller/product')->with('fail','This account has been disabled!');
            $product = Product::find($prod_id);
            if(!$product)
                return redirect('/seller/product')->with('fail','Product not found!');
            $variationexist = Variation::where('prod_id','=',$product->prod_id)->exists();
            $variationlist = Variation::where('prod_id','=',$product->prod_id)->get();
            $auditlist = StockAudit::where('prod_id','=',$product->prod_id)->orderBy('date','desc')->get();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.productstock',compact('userdata','product','variationexist','variationlist','auditlist','notifications'));
        }
        else
            return redirect('/login');
    }

    public function addStock(Request $request)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/seller/product')->with('fail','This account has been disabled!');
        DB::beginTransaction();
        try
        {
            $request->validate(['quantity' => 'required|integer',]);						// THIS LINE IS MODIFIED
            $product = Product::find($request->prod_id);
            if(!$product)
                return redirect('/seller/product')->with('fail','Product not found!');
            $product->quantity += $request->quantity;
            $product->save();

            $audit = new StockAudit();
            $audit->prod_id = $request->prod_id;
            $audit->audit_trail = "Quantity of " . $request->quantity . " has been added to the product " . $product->prod_name . " overall stock.";
            $audit->save();
            
            DB::commit();
            return back()->with('success','Stock successfully increased!');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','Failed to increase stock!');
        }
    }

    public function addStock2(Request $request)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/seller/product')->with('fail','This account has been disabled!');
        DB::beginTransaction();
        try
        {
            $request->validate(['quantity.*' => 'required|integer',]);						// THIS LINE IS MODIFIED
            $product = Product::find($request->prod_id);
            if(!$product)
                return redirect('/seller/product')->with('fail','Product not found!');
            foreach ($request->quantity as $key => $quantity) {
                $variation = Variation::find($request->variation_id[$key]);
                if (!$variation)
                    return redirect('/seller/product')->with('fail','Variation not found!');
                else
                {
                    $variation->quantity += $quantity;
                    $variation->save();
                    $product->quantity += $quantity;
                    $product->save();

                    $audit = new StockAudit();
                    $audit->prod_id = $request->prod_id;
                    $audit->audit_trail = "Quantity of " . $quantity . " has been added to the variation " . $variation->color . " " . $variation->size . " overall stock of product " . $product->prod_name . ".";
                    $audit->save();
                }
            }
            
            DB::commit();
            return back()->with('success','Variation stock successfully increased!');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','Failed to increase stock!');
        }
    }

    public function deleteSellerProduct($prod_id)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        if($userdata->is_disabled === 1)
            return redirect('/seller/product')->with('fail','This account has been disabled!');
        $product = Product::find($prod_id);
        if(!$product)
            return redirect('/seller/product')->with('fail','Product not found!');
        else
        {
            $product->prod_status = "DELETED";
            $product->save();
            return back()->with('success','Product successfully removed!');
        }
    }

    public function userList()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $userlist = User::whereIn('type',['BUYER','SELLER'])->get();
            $warnCount = Notification::where('is_warn', true)->whereIn('receiver_id',$userlist->pluck('user_id'))->select('receiver_id',\DB::raw('count(*) as warning_count'))->groupBy('receiver_id')->get()->keyBy('receiver_id');
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
            return view('admin.userlist',compact('userdata','userlist','warnCount','notifications'));
        }
        else
            return redirect('/login');
    }

    public function newSeller()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
            return view('admin.newseller',compact('userdata','notifications'));
        }
        else
            return redirect('/');
    }

    public function saveNewSeller(Request $request)
    {
        $request->validate([
            'org_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:7|max:15',
            'chat_link' => ['required', 'regex:/^(https?:\/\/(www\.)?facebook\.com\/.+)|(https?:\/\/(www\.)?messenger\.com\/.+)/'],
            'gcash_no' => 'required|digits:11',
            'prof_pic' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        if ($request->hasFile('prof_pic')) 
        {
            $pic = $request->file('prof_pic')->getClientOriginalName();
            $request->file('prof_pic')->storeAs('public/custom_prof_pics/',$pic);

            $new = new User();
            $new->org_name = $request->org_name;
            $new->first_name = $request->first_name;
            $new->last_name = $request->last_name;
            $new->email = $request->email;
            $new->password = Hash::make($request->password);
            $new->chat_link = $request->chat_link;
            $new->gcash_no = $request->gcash_no;
            $new->prof_pic = $pic;
            $new->type = "SELLER";
            $save = $new->save();
        }
        else
        {
            $new = new User();
            $new->org_name = $request->org_name;
            $new->first_name = $request->first_name;
            $new->last_name = $request->last_name;
            $new->email = $request->email;
            $new->password = Hash::make($request->password);
            $new->chat_link = $request->chat_link;
            $new->gcash_no = $request->gcash_no;
            $new->prof_pic = 'default_pics/default_prof_pic.jpg';
            $new->type = "SELLER";
            $save = $new->save();
        }

        if($save)
            return redirect('/admin/userlist');
        else
            return back()->with('fail','Seller registration failed!');
    }

    public function userDetails($user_id)
    {
        if(Session::has('loginId'))
        {
            $userdetails = User::where('user_id','=',$user_id)->first();
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
            $warns = Notification::where('receiver_id',$userdetails->user_id)->where('is_warn',true)->get();
            $warnCount = Notification::where('receiver_id',$userdetails->user_id)->where('is_warn',true)->count();
            return view('admin.userdetails',compact('userdetails','userdata','notifications','warns','warnCount'));
        }
        else
            return redirect('/login');
    }

    public function disableUser($user_id)
    {
        $user = User::find($user_id);
        if($user)
        {
            DB::beginTransaction();
            try
            {
                $warnCount = Notification::where('receiver_id',$user->user_id)->where('is_warn',true)->count();
                if($warnCount >= 3)
                    return back()->with('fail','User has already been permanently deactivated');
                $user->is_disabled = true;
                $user->save();
                if($user->type === 'SELLER')
                    Product::where('user_id',$user->user_id)->where('prod_status','ENABLED')->update(['prod_status' => 'DISABLED']);
                DB::commit();
                return back()->with('success','User is disabled!');
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while disabling the user. Please try again.');
            }
        }
        else
            return redirect('/admin/userlist')->with('fail','User not found!');
    }

    public function enableUser($user_id)
    {
        $user = User::find($user_id);
        if($user)
        {
            DB::beginTransaction();

            try
            {
                $warnCount = Notification::where('receiver_id',$user->user_id)->where('is_warn',true)->count();
                if($warnCount >= 3)
                    return back()->with('fail','User has already been permanently deactivated');
                $user->is_disabled = false;
                $user->save();
                if($user->type === 'SELLER')
                    Product::where('user_id',$user->user_id)->where('prod_status','DISABLED')->update(['prod_status' => 'ENABLED']);
                DB::commit();
                return back()->with('success','User is enabled!');
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while re-enabling the user. Please try again.');
            }
        }
        else
            return redirect('/admin/userlist')->with('fail','User not found!');
    }

    public function productList()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $productlist = Product::wherein('prod_status',['ENABLED','DISABLED'])->get();
            $categories = Category::all();
            $organizations = User::whereNotNull('org_name')->distinct()->pluck('org_name');
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
            return view('admin.productlist',compact('userdata','productlist','categories','organizations','notifications'));
        }
        else
            return redirect('/login');
    }

    public function rejectedList()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $rejectedlist = Product::where('prod_status','DELETED')->get();
            $categories = Category::all();
            $organizations = User::whereNotNull('org_name')->distinct()->pluck('org_name');
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
            return view('admin.rejectedlist',compact('userdata','rejectedlist','categories','organizations','notifications'));
        }
        else
            return redirect('/login');
    }

    public function rejectPage($prod_id)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $product = Product::find($prod_id);
        $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
        if($product)
            return view('admin.rejectitem',compact('userdata','product','notifications'));
        else
            return redirect('/admin/productlist')->with('fail','Product not found!');
    }

    public function productDetails($prod_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $product = Product::find($prod_id);
            if(!$product)
                return redirect('/admin/productlist')->with('fail','Product not found!');
            $variationexist = Variation::where('prod_id','=',$product->prod_id)->exists();
            $variationlist = Variation::where('prod_id','=',$product->prod_id)->get();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
            return view('admin.productdetails',compact('userdata','product','variationexist','variationlist','notifications'));
        }
        else
            return redirect('/');
    }

    public function approveProduct(Request $request)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $product = Product::find($request->prod_id);
        if($product)
        {
            DB::beginTransaction();
            try
            {
            Product::where('prod_id','=',$product->prod_id)->update(['is_approved' => true,]);

            $notify = new Notification();
            $notify->sender_id = $userdata->user_id;
            $notify->receiver_id = $product->user_id;
            $notify->content = "Your product " . $product->prod_name . " was approved by administration.";
            $notify->quick_link = url('/seller/product/editproduct/'.$product->prod_id);
            $notify->save();

            DB::commit();
            return back()->with('success','Product is approved!');
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while approving the product. Please try again.');
            }
        }
        else
            return redirect('/admin/productlist')->with('fail','Product not found!');
    }

    public function rejectProduct(Request $request)
    {
        $request->validate(['reject_reason' => 'required|string',]);
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $product = Product::find($request->prod_id);
        if($product)
        {
            DB::beginTransaction();
            try
            {
                $product->prod_status = "DELETED";
                $product->reject_reason = $request->reject_reason;
                $product->save();

                $notify = new Notification();
                $notify->sender_id = $userdata->user_id;
                $notify->receiver_id = $product->user_id;
                $notify->content = "Your product " . $product->prod_name . " was rejected by administration. Check product for additional details.";
                $notify->quick_link = url('/seller/rejected/rejecteddetails/'.$product->prod_id);
                $notify->save();
                
                DB::commit();
                return redirect('/admin/rejectedlist')->with('success','Product is rejected!');
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while rejecting the product. Please try again.');
            }
        }
        else
            return back()->with('fail','Product not found!');
    }

    public function disableProduct($prod_id)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $product = Product::find($prod_id);
        if($product)
        {
            DB::beginTransaction();
            try
            {
                $product->prod_status = "DISABLED";
                $product->save();

                $notify = new Notification();
                $notify->sender_id = $userdata->user_id;
                $notify->receiver_id = $product->user_id;
                $notify->content = "Your product " . $product->prod_name . " has been disabled by administration for inappropriate content/violating website policy.";
                $notify->quick_link = url('/seller/product/editproduct/'.$product->prod_id);
                $notify->save();
                
                DB::commit();
                return back()->with('success','Product is disabled!');
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while disabling the product. Please try again.');
            }
        }
        else
            return redirect('/admin/productlist')->with('fail','Product not found!');
    }

    public function enableProduct($prod_id)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $product = Product::find($prod_id);
        if($product)
        {
            DB::beginTransaction();
            try
            {
                $product->prod_status = "ENABLED";
                $product->save();

                $notify = new Notification();
                $notify->sender_id = $userdata->user_id;
                $notify->receiver_id = $product->user_id;
                $notify->content = "Your product " . $product->prod_name . " has been re-enabled by administration.";
                $notify->quick_link = url('/seller/product/editproduct/'.$product->prod_id);
                $notify->save();
                
                DB::commit();
                return back()->with('success','Product is enabled!');
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while enabling the product. Please try again.');
            }
        }
        else
            return redirect('/admin/productlist')->with('fail','Product not found!');
    }

    public function categoryList()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $categorylist = Category::all();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
            return view('admin.categorylist',compact('userdata','categorylist','notifications'));
        }
        else
            return redirect('/login');
    }

    public function editCategory($cat_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
            $category = Category::find($cat_id);
            if(!$category)
                return redirect('/admin/category')->with('fail','Category not found!');
            return view('admin.editcategory',compact('userdata','category','notifications'));
        }
        else
            return redirect('/login');
    }

    public function saveCategory(Request $request)
    {
        $request->validate(['cat_name' => 'required']);
        if(Category::where('cat_name',$request->cat_name)->exists())
            return back()->with('fail','Category with this name already exists.');

        $new = new Category();
        $new->cat_name = $request->cat_name;
        $save = $new->save();

        if($save)
            return back()->with('success','New category added!');
        return back()->with('fail','Adding of new product category failed!');
    }

    public function updateCategory(Request $request)
    {
        $request->validate(['cat_name' => 'required']);
        if(Category::where('cat_name',$request->cat_name)->exists())
            return back()->with('fail','Category with this name already exists.');

        $cat = Category::find($request->cat_id);
        if($cat)
        {
            Category::where('cat_id',$request->cat_id)->update(['cat_name' => $request->cat_name]);
            return back()->with('success','Category Updated');
        }
        return back()->with('fail','Category not found!');
    }

    public function deleteCategory($cat_id)
    {
        $category = Category::find($cat_id);
        if(!$category)
            return back()->with('fail','Category not found!');
        $category->delete();
        return back()->with('success','Category Deleted');
    }

    public function sellerOrder($status = 'All')
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            if($status === 'All')
                $orders = Order::with('seller','orderItems')->where('seller_id',$userdata->user_id)->orderBy('order_id','desc')->simplepaginate(10);
            if($status === 'UNPAID')
                $orders = Order::with('seller','orderItems')->where('seller_id',$userdata->user_id)->where('status','UNPAID')->orderBy('order_id','desc')->simplepaginate(10);
            if($status === 'ON THE WAY')
                $orders = Order::with('seller','orderItems')->where('seller_id',$userdata->user_id)->where('status','ON THE WAY')->orderBy('order_id','desc')->simplepaginate(10);
            if($status === 'COMPLETED')
                $orders = Order::with('seller','orderItems')->where('seller_id',$userdata->user_id)->where('status','COMPLETED')->orderBy('order_id','desc')->simplepaginate(10);
            if($status === 'CANCELED')
                $orders = Order::with('seller','orderItems')->where('seller_id',$userdata->user_id)->where('status','CANCELED')->orderBy('order_id','desc')->simplepaginate(10);
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.sellerorder',compact('userdata','orders','notifications'));
        }
        else
            return redirect('/login');
    }

    public function sellerOrderDetails($order_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $order = Order::where('order_id',$order_id)->first();
            $buyer = User::where('user_id',$order->buyer_id)->first();
            $seller = User::where('user_id',$order->seller_id)->first();
            $orderitems = OrderItem::where('order_id',$order->order_id)->get();
            $merchtotal = $order->total - $order->ship_fee;
            $releasedate = Release::whereIn('orderitem_id',$orderitems->pluck('orderitem_id'))->distinct()->value('date_sent');
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.sellerorderdetails',compact('userdata','order','buyer','seller','orderitems','merchtotal','releasedate','notifications'));
        }
        else
            return redirect('/login');
    }

    public function sellerOrderApprove($order_id)
    {
        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $order = Order::where('order_id',$order_id)->first();
        if($order)
        {
            DB::beginTransaction();
            try
            {
                if($order->status === 'UNPAID' && !$order->ref_no)
                    return back()->with('fail','Buyer may have removed the posted GCash reference number proof of payment.');
                if($order->status === 'CANCELED')
                    return back()->with('fail','Buyer has canceled the order.');
                if($order->status === 'ON THE WAY')
                    return back()->with('fail','You have already approved this order is now being shipped and on the way.');
                if($order->status === 'COMPLETED')
                    return back()->with('fail','The package has been received by the buyer.');
                $order->status = "ON THE WAY";
                $order->save();

                $notify = new Notification();
                $notify->sender_id = $userdata->user_id;
                $notify->receiver_id = $order->buyer_id;
                $notify->content = "Seller has approved Order " . $order->order_id;
                $notify->quick_link = url('/dashboard/orderdetails/'.$order->order_id);
                $notify->save();

                DB::commit();
                return back();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while approving the order. Please try again.');
            }
        }
        else
            return redirect('/seller/order/');
    }

    public function sellerOrderTrack(Request $request)
    {
        $request->validate(['track_num' => 'required|string|max:20',]);
        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $order = Order::where('order_id',$request->order_id)->first();
        if($order)
        {
            DB::beginTransaction();
            try
            {
                if($order->status === 'COMPLETED')
                    return back()->with('fail','The package has been received by the buyer.');
                if($order->track_num === null && $request->track_num !== null)
                {
                    $notify = new Notification();
                    $notify->sender_id = $userdata->user_id;
                    $notify->receiver_id = $order->buyer_id;
                    $notify->content = "Tracking number has been received for Order " . $order->order_id;
                    $notify->quick_link = url('/dashboard/orderdetails/'.$order->order_id);
                    $notify->save();

                    $orderItems = OrderItem::where('order_id',$order->order_id)->get();
                    foreach ($orderItems as $orderItem) {
                        $release = new Release();
                        $release->orderitem_id = $orderItem->orderitem_id;
                        $release->buyer_id = $order->buyer_id;
                        $release->seller_id = $order->seller_id;
                        $release->prod_name = $orderItem->prod_name;
                        $release->date_sent = now();
                        $release->save();
                    }
                }
                $order->track_num = $request->track_num;
                $order->save();

                DB::commit();
                return back()->with('success','Tracking number has been updated.');
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while trying to post the tracking number. Please try again.');
            }
        }
        else
            return redirect('/seller/order/');
    }

    public function sellerExchange($status = 'All')
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            if($status === 'All')
                $exchanges = Exchange::with('seller','exchangeItems')->where('seller_id',$userdata->user_id)->orderBy('exchange_id','desc')->simplepaginate(10);
            if($status === 'PENDING')
                $exchanges = Exchange::with('seller','exchangeItems')->where('seller_id',$userdata->user_id)->where('status','PENDING')->orderBy('exchange_id','desc')->simplepaginate(10);
            if($status === 'ON THE WAY TO SELLER')
                $exchanges = Exchange::with('seller','exchangeItems')->where('seller_id',$userdata->user_id)->where('status','ON THE WAY TO SELLER')->orderBy('exchange_id','desc')->simplepaginate(10);
            if($status === 'ON THE WAY TO BUYER')
                $exchanges = Exchange::with('seller','exchangeItems')->where('seller_id',$userdata->user_id)->where('status','ON THE WAY TO BUYER')->orderBy('exchange_id','desc')->simplepaginate(10);
            if($status === 'REJECTED')
                $exchanges = Exchange::with('seller','exchangeItems')->where('seller_id',$userdata->user_id)->where('status','REJECTED')->orderBy('exchange_id','desc')->simplepaginate(10);
            if($status === 'CANCELED')
                $exchanges = Exchange::with('seller','exchangeItems')->where('seller_id',$userdata->user_id)->where('status','CANCELED')->orderBy('exchange_id','desc')->simplepaginate(10);
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.sellerexchange',compact('userdata','exchanges','notifications'));
        }
        else
            return redirect('/login');
    }

    public function sellerExchangeDetails($exchange_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $exchange = Exchange::where('exchange_id',$exchange_id)->first();
            $buyer = User::where('user_id',$exchange->buyer_id)->first();
            $seller = User::where('user_id',$exchange->seller_id)->first();
            $exchangeitems = ExchangeItem::where('exchange_id',$exchange->exchange_id)->get();
            $uniqueProductIds = $exchangeitems->pluck('prod_id')->unique()->toArray();
            $sellerProducts = Product::whereIn('prod_id',$uniqueProductIds)->where('user_id',$userdata->user_id)->get();
            $variationOptions = collect();
            foreach ($exchangeitems as $exchangeitem) {
                $product = $sellerProducts->where('prod_id', $exchangeitem->prod_id)->first();
    
                if($product)
                {
                    $variations = Variation::where('prod_id', $product->prod_id);
                    if($exchangeitem->color)
                        $variations->where('color', $exchangeitem->color);
                    $variations = $variations->get(['variation_id', 'color', 'size']);
                    foreach ($variations as $variation) {
                        $variationOptions->push($variation);
                    }
                }
            }
            $returnItems = ReturnItem::where('exchange_id',$exchange->exchange_id)->get();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.sellerexchangedetails',compact('userdata','exchange','buyer','seller','exchangeitems','notifications','sellerProducts','variationOptions','returnItems'));
        }
        else
            return redirect('/login');
    }

    public function sellerExchangeApprove($exchange_id)
    {
        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $exchange = Exchange::where('exchange_id',$exchange_id)->first();
        if($exchange)
        {
            DB::beginTransaction();

            try
            {
                if($exchange->status === 'CANCELED')
                    return back()->with('fail','Buyer has canceled the order.');
                if($exchange->status === 'REJECTED')
                    return back()->with('fail','You have already rejected the exchange request.');
                if($exchange->status === 'ON THE WAY TO SELLER')
                    return back()->with('fail','You have already approved the exchange request.');
                $exchange->status = "ON THE WAY TO SELLER";
                $exchange->save();

                $notify = new Notification();
                $notify->sender_id = $userdata->user_id;
                $notify->receiver_id = $exchange->buyer_id;
                $notify->content = "Seller has approved Exchange Request " . $exchange->exchange_id;
                $notify->quick_link = url('/exchange/exchangedetails/'.$exchange->exchange_id);
                $notify->save();

                DB::commit();
                return back();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while approving the request. Please try again.');
            }
        }
        else
            return redirect('/seller/exchange/');
    }

    public function addReturnItem(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'variation' => 'required',
            'quantity' => 'required|numeric',
        ]);
        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $exchange = Exchange::where('exchange_id',$request->exchange_id)->first();
        if($exchange)
        {
            DB::beginTransaction();
            try
            {
                $product = Product::where('prod_id',$request->product)->first();
                $variation = Variation::where('variation_id',$request->variation)->first();

                $return = new ReturnItem();
                $returnItemId = $this->getAvailableReturnItemId();
                $return->returnitem_id = $returnItemId;
                $return->exchange_id = $exchange->exchange_id;
                $return->prod_id = $product->prod_id;
                $return->prod_name = $product->prod_name;
                $return->prod_image = $product->image1;
                $return->color = $variation->color;
                $return->size = $variation->size;
                $return->quantity = $request->quantity;
                $return->save();

                DB::commit();
                return back();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while adding a return item. Please try again.');
            }
        }
        else
            return redirect('/seller/exchange/');
    }

    private function getAvailableReturnItemId()
    {
        $lastId = ReturnItem::max('returnitem_id');
        for($i = 1; $i <= $lastId; $i++)
        {
            $existingRecord = ReturnItem::find($i);
            if(!$existingRecord)
                return $i;
        }
        return $lastId + 1;
    }

    public function getVariations(Request $request, $productId)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $exchangeitems = ExchangeItem::where('exchange_id',$request->input('exchange_id'))->where('prod_id',$productId)->get();
        $uniqueProductIds = $exchangeitems->pluck('prod_id')->unique()->toArray();
        $sellerProducts = Product::whereIn('prod_id',$uniqueProductIds)->where('user_id',$userdata->user_id)->get();
        $variationOptions = collect();

        foreach ($exchangeitems as $exchangeitem) {
            $product = $sellerProducts->where('prod_id', $exchangeitem->prod_id)->first();
            if($product)
            {
                $variations = Variation::where('prod_id', $product->prod_id);
                if($exchangeitem->color)
                    $variations->where('color', $exchangeitem->color);
                $variations = $variations->get(['variation_id', 'color', 'size']);
                foreach ($variations as $variation) {
                    $variationOptions->push($variation);
                }
            }
        }

        return response()->json($variationOptions);
    }

    public function removeReturnItem($returnitem_id)
    {
        DB::beginTransaction();
        try
        {
            $returnItem = ReturnItem::findOrFail($returnitem_id);
            $returnItem->delete();
            DB::commit();
            return back();
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','An error occurred while removing the return item. Please try again.');
        }
    }

    public function receive($exchange_id)
    {
        DB::beginTransaction();
        try
        {
            $exchange = Exchange::findOrFail($exchange_id);
            if($exchange->reason === 'Exchange Product Size')
            {
                $exchangeItems = ExchangeItem::where('exchange_id',$exchange_id)->get();
                foreach ($exchangeItems as $exchangeItem) {
                    $variation = Variation::where('prod_id',$exchangeItem->prod_id)->where('color',$exchangeItem->color)->where('size',$exchangeItem->size)->first();
                    $variation->quantity += $exchangeItem->quantity;
                    $variation->save();
                    $product = Product::findOrFail($exchangeItem->prod_id);
                    $product->quantity += $exchangeItem->quantity;
                    $product->save();
                }

                $returnItems = ReturnItem::where('exchange_id',$exchange_id)->get();
                foreach ($returnItems as $returnItem) {
                    $variation = Variation::where('prod_id',$returnItem->prod_id)->where('color',$returnItem->color)->where('size',$returnItem->size)->first();
                    $variation->quantity -= $returnItem->quantity;
                    $variation->save();
                    $product = Product::findOrFail($returnItem->prod_id);
                    $product->quantity -= $returnItem->quantity;
                    $product->save();
                }
            }
            if($exchange->reason === 'Damaged Product/s (e.g. dented, scratched, shattered)' || $exchange->reason === 'Faulty/Defective Product/s (e.g. malfunction, does not work as intended)')
            {
                $exchangeItems = ExchangeItem::where('exchange_id',$exchange_id)->get();
                foreach ($exchangeItems as $exchangeItem) {
                    if($exchangeItem->color && $exchangeItem->size)
                        $variation = Variation::where('prod_id',$exchangeItem->prod_id)->where('color',$exchangeItem->color)->where('size',$exchangeItem->size)->first();
                    if($exchangeItem->color && !$exchangeItem->size)
                        $variation = Variation::where('prod_id',$exchangeItem->prod_id)->where('color',$exchangeItem->color)->first();
                    if($exchangeItem->size && !$exchangeItem->color)
                        $variation = Variation::where('prod_id',$exchangeItem->prod_id)->where('size',$exchangeItem->size)->first();
                    if($variation)
                    {
                        $variation->quantity -= $exchangeItem->quantity;
                        $variation->save();
                    }

                    $product = Product::findOrFail($exchangeItem->prod_id);
                    $product->quantity -= $exchangeItem->quantity;
                    $product->save();
                }
            }

            $exchange->status = 'ON THE WAY TO BUYER';
            $exchange->save();
            DB::commit();
            return back();
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','An error occurred while receiving items. Please try again.');
        }
    }

    public function sellerExchangeReject($exchange_id)
    {
        $userdata = User::where('user_id', Session::get('loginId'))->first();
        $exchange = Exchange::where('exchange_id',$exchange_id)->first();
        if($exchange)
        {
            DB::beginTransaction();
            try
            {
                if($exchange->status === 'CANCELED')
                    return back()->with('fail','Buyer has canceled the order.');
                if($exchange->status === 'REJECTED')
                    return back()->with('fail','You have already rejected the exchange request.');
                if($exchange->status === 'ON THE WAY TO SELLER')
                    return back()->with('fail','You have already approved the exchange request.');
                $exchange->status = "REJECTED";
                $exchange->save();

                $notify = new Notification();
                $notify->sender_id = $userdata->user_id;
                $notify->receiver_id = $exchange->buyer_id;
                $notify->content = "Seller has rejected Exchange Request " . $exchange->exchange_id;
                $notify->quick_link = url('/exchange/exchangedetails/'.$exchange->exchange_id);
                $notify->save();

                DB::commit();
                return back();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                return back()->with('fail','An error occurred while rejecting the request. Please try again.');
            }
        }
        else
            return redirect('/seller/exchange/');
    }

    public function salesReport(Request $request)
    {
        $userdata = User::where('user_id','=',Session::get('loginId'))->first();
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');
        $years = Order::selectRaw('YEAR(date) as year')->distinct()->pluck('year');
        $months = Order::selectRaw('MONTH(date) as month')->distinct()->pluck('month');
        $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();

        $salesQuery = Order::where('seller_id',$userdata->user_id)->whereIn('status',['ON THE WAY','COMPLETED']);
        if($selectedYear && $selectedYear !== 'All')
            $salesQuery->whereYear('date',$selectedYear);
        if($selectedMonth && $selectedMonth !== 'All')
            $salesQuery->whereMonth('date',$selectedMonth);
        $sales = $salesQuery->get();
        $grandTotal = $sales->sum('total');

        return view('seller.salesreport', compact('userdata','selectedMonth','selectedYear','years','months','notifications','sales','grandTotal'));
    }

    public function sendTicket(Request $request)
    {
        $request->validate(['content' => 'required|string',]);
        DB::beginTransaction();
        try
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $receiver = User::where('user_id',$request->user_id)->first();

            $notify = new Notification();
            $notify->sender_id = $userdata->user_id;
            $notify->receiver_id = $request->user_id;
            $notify->content = $request->content;
            $notify->is_warn = 1;
            $notify->save();

            $warnCount = Notification::where('receiver_id',$request->user_id)->where('is_warn',true)->count();
            if($warnCount >= 3)
            {
                $receiver->is_disabled = 1;
                $receiver->save();
                if($receiver->type === 'SELLER')
                    Product::where('user_id',$receiver->user_id)->update(['prod_status' => 'DELETED']);
            }

            $notify2 = new Notification();
            $notify2->sender_id = $userdata->user_id;
            $notify2->receiver_id = $request->user_id;
            if($receiver->type === 'BUYER')
            {
                $notify2->content = 'You have received a warning, check dashboard for further details.';
                $notify2->quick_link = url('/ticketlist');
            }
            elseif($receiver->type === 'SELLER')
            {
                $notify2->content = 'You have received a warning, check profile for further details.';
                $notify2->quick_link = url('/seller/sellerprofile');
            }
            $notify2->save();
            DB::commit();
            return back()->with('success','Ticket was successfully sent!');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','An error occurred while sending the ticket. Please try again.');
        }
    }

    public function reports()
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $reports = Notification::where('is_report',1)->orderBy('notify_id','desc')->get();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_report',0)->orderBy('notify_id','desc')->get();
            return view('admin.reports',compact('userdata','reports','notifications'));
        }
        else
            return redirect('/login');
    }

    public function reportUser($some_id,$type)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            if($type === 'order')
                $order = Order::where('order_id',$some_id)->first();
            elseif($type === 'exchange')
                $exchange = Exchange::where('exchange_id',$some_id)->first();
            elseif($type === 'review')
                $review = Review::where('rev_id',$some_id)->first();
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            if($type === 'order')
                return view('seller.reportuser',compact('userdata','order','type','notifications'));
            elseif($type === 'exchange')
                return view('seller.reportuser',compact('userdata','exchange','type','notifications'));
            elseif($type === 'review')
                return view('seller.reportuser',compact('userdata','review','type','notifications'));
        }
        else
            return redirect('/login');
    }

    public function reportSend(Request $request)
    {
        $request->validate(['content' => 'required|string',]);
        DB::beginTransaction();
        try
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $buyer = User::where('user_id',$request->user_id)->first();
            $admins = User::where('type','ADMIN')->get();

            foreach ($admins as $admin) {
                $notification = new Notification();
                $notification->sender_id = $userdata->user_id;
                $notification->receiver_id = $admin->user_id;
                $notification->content = $request->content;
                $notification->is_report = 1;
                $notification->reported_user = $buyer->user_id;
                $notification->reported_name = $buyer->first_name . ' ' . $buyer->last_name;
                $notification->save();

                $notification2 = new Notification();
                $notification2->sender_id = $userdata->user_id;
                $notification2->receiver_id = $admin->user_id;
                $notification2->content = 'A user has been reported.';
                $notification2->quick_link = url('/admin/userlist/reports');
                $notification2->save();
            }

            DB::commit();
            if($request->way === 'order')
                return redirect('/seller/orderdetails/'.$request->order_id)->with('success','Report was successfully submitted!');
            elseif($request->way === 'exchange')
                return redirect('/seller/exchangedetails/'.$request->exchange_id)->with('success','Report was successfully submitted!');
            elseif($request->way === 'review')
                return redirect('/seller/product/reviews/'.$request->prod_id)->with('success','Report was successfully submitted!');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','An error occurred while submitting the report. Please try again.');
        }
    }

    public function productReviews($prod_id)
    {
        if(Session::has('loginId'))
        {
            $userdata = User::where('user_id','=',Session::get('loginId'))->first();
            $product = Product::where('prod_id',$prod_id)->first();
            $reviews = Review::where('prod_id',$prod_id)->orderBy('rev_id','desc')->simplePaginate(10);
            $averageRating = $reviews->avg('rate');
            $notifications = Notification::where('receiver_id',$userdata->user_id)->where('is_read',0)->where('is_warn',0)->orderBy('notify_id','desc')->get();
            return view('seller.productreviews',compact('userdata','product','reviews','averageRating','notifications'));
        }
        else
            return redirect('/login');
    }

    public function updateFee(Request $request)
    {
        $request->validate(['fee' => 'required']);
        $fee = ShipFee::find($request->fee_id);
        if($fee)
        {
            ShipFee::where('fee_id',$request->fee_id)->update(['fee' => $request->fee]);
            return back()->with('success','Shipping Fee updated successfully!');
        }
        return back()->with('fail','There was an error trying to find the shipping fee!');
    }
}