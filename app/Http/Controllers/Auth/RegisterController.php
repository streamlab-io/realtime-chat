<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use StreamLab\StreamLabProvider\Facades\StreamLabFacades;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
         $vaild =   Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
             'bio' => 'required'
        ]);


        if($vaild->fails()){
            return json_encode($vaild->errors());
        }

        return true;

    }

    public function register(Request $request)
    {

        $v = $this->validator($request->all());

        if(!is_bool($v)){
            return $v;
        }


        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);


        $data = [
            'id' => $user->id,
            'secret' => $user->id.$user->email.$user->name,
            'name' => $user->name,
            'bio' => $user->bio
        ];


        StreamLabFacades::createUser($data);



        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }



    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'bio' => $data['bio']
        ]);
    }

    protected function registered(Request $request, $user)
    {
        return "true";
    }


}
