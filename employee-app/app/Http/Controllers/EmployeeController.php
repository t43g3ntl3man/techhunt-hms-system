<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function loginView(){
        return view('employee.login');
    }

    public function loginPost(Request $req){
        $parameters = [
            'email' => $req['email'],
            'password'=> $req['password']
        ];
        // dd($parameters);
        if(Auth::guard('emp')->attempt($parameters)){
            // $req->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        dd($req);
    }

    public function dashboard(){
    if(Auth::check()){
        dd('asfasofhsodfh');
    }
        dd(Auth::user());
    }
}
