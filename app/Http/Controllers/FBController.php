<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FBController extends Controller
{
    public function redirectToFacebook(){
        return Socialite::driver('facebook')->redirect();
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback(){
        try {
            $user = Socialite::driver('facebook')->user();
            $create['name'] = $user->getName();
            if($user->getEmail() == '' || $user->getEmail() == ' '){
                $create['email'] = "exampleFB".rand(1,100000)."@qrorpa.ch";
            }else{
                $create['email'] = $user->getEmail();
            }
            
            $create['facebook_id'] = $user->getId();
            $userModel = new User;
            $createdUser = $userModel->addNew($create);
            Auth::loginUsingId($createdUser->id);

            return redirect()->route('menu',["Res"=>"13","t"=>"2"]);
        } catch (Exception $e) {
            return redirect('authFB/facebook');
        }
    }
}
