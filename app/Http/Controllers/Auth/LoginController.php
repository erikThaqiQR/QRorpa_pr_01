<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Socialite;
use Auth;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }  
        if(isset($_SESSION['Res']) && isset($_SESSION['t'])){
            return redirect('/?Res='.$_SESSION['Res'].'&t='.$_SESSION['t']);
        }else{
            return redirect('/firstPIndex');
            // protected $redirectTo = '/firstPIndex';
        }
    }
    



    public function redirectToProvider($provider){
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider){
        $user = Socialite::driver($provider)->stateless()->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    public function findOrCreateUser($user, $provider){
        $authUser = User::where('email', $user->email)->first();
        if($authUser){
            return $authUser;
        }

        if($user->email == '' || $user->email == ' '){
            $createEmail = "exampleFB".rand(1,100000)."@qrorpa.ch";
        }else{
            $createEmail = $user->email;
        }
        return User::create([
            'name' => $user->name,
            'email' => $createEmail,
            'password' => '123456789012345678901234567890',
            'provider' => strtoupper($provider),
            'provider_id' => $user->id,
        ]);
    }

 	
}
