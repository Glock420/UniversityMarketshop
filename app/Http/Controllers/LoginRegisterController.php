<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use Session;
use Hash;

class LoginRegisterController extends Controller
{
    public function login()
    {
        return view('login');
    }

    // public function login2()
    // {
    //     return view('login2');
    // }

    public function register()
    {
        return view('register');
    }

    // public function loginBuyer(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required',
    //         'password' => 'required'
    //     ]);

    //     $user = User::where('email','=',$request->email)->first();

    //     if($user)
    //     {
    //         if($user->type == "BUYER")
    //         {
    //             if(Hash::check($request->password,$user->password))
    //             {
    //                 $request->session()->put('loginId',$user->user_id);
    //                 return redirect('/');
    //             }
    //             else
    //                 return back()->with('fail','Invalid email or password!');
    //         }
    //         else
    //             return back()->with('fail','Account does not exist!');
    //     }    
    //     else
    //         return back()->with('fail','Account does not exist!');
    // }

    // public function loginSA(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required',
    //         'password' => 'required'
    //     ]);

    //     $user2 = User::where('email','=',$request->email)->first();

    //     if($user2)
    //     {
    //         if($user2->type == "SELLER")
    //         {
    //             if(Hash::check($request->password,$user2->password))
    //             {
    //                 $request->session()->put('loginSAId',$user2->user_id);
    //                 //$request->session()->put('userType',$user2->type);
    //                 return redirect('/seller/sellerprofile');
    //             }
    //             else
    //                 return back()->with('fail','Invalid email or password!');
    //         }
    //         else if($user2->type == "ADMIN")
    //         {
    //             if($user2 && $request->password === $user2->password)
    //             {
    //                 $request->session()->put('loginSAId',$user2->user_id);
    //                 //$request->session()->put('userType',$user2->type);
    //                 return redirect('/seller/userlist');
    //             }
    //             else
    //                 return back()->with('fail','Invalid email or password!');
    //         }
    //         else
    //             return back()->with('fail','Account does not exist!');
    //     }    
    //     else
    //         return back()->with('fail','Account does not exist!');
    // }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email','=',$request->email)->first();

        if($user)
        {
            if($user->type == "BUYER")
            {
                if(Hash::check($request->password,$user->password))
                {
                    $request->session()->put('loginId',$user->user_id);
                    return redirect('/');
                }
                else
                    return back()->with('fail','Invalid email or password!');
            }
            else if($user->type == "SELLER")
            {
                if(Hash::check($request->password,$user->password))
                {
                    $request->session()->put('loginId',$user->user_id);
                    return redirect('/seller/product');
                }
                else
                    return back()->with('fail','Invalid email or password!');
            }
            else if($user->type == "ADMIN")
            {
                if(Hash::check($request->password,$user->password))
                {
                    $request->session()->put('loginId',$user->user_id);
                    return redirect('/admin/userlist');
                }
                else
                    return back()->with('fail','Invalid email or password!');
            }
            else
                return back()->with('fail','Account does not exist!');
        }    
        else
            return back()->with('fail','Account does not exist!');
    }

    public function registerBuyer(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:7|max:15'
        ]);

        DB::beginTransaction();

        try
        {
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->prof_pic = 'default_pics/default_prof_pic.jpg';
            $user->type = "BUYER";
            $user->save();

            $cart = new Cart();
            $cart->user_id = $user->user_id;
            $cart->save();

            $request->session()->put('loginId',$user->user_id);
            DB::commit();
            return redirect('/');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return back()->with('fail','Registration failed!');
        }
    }

    public function logout()
    {
        if(Session::has('loginId'))
        {
            Session::pull('loginId');
            return redirect('/login');
        }
    }

    // public function logout2()
    // {
    //     if(Session::has('loginSAId'))
    //     {
    //         Session::pull('loginSAId');
    //         return redirect('/seller/login');
    //     }
    // }
}
