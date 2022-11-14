@extends('layouts.master-blank')

@section('content')

    <div class="wrapper-page">
      <div class="card overflow-hidden account-card mx-3">
          <div class="bg-primary p-4 text-white text-center position-relative">
              <h4 class="font-20 m-b-5">Welcome Back !</h4>
              <p class="text-white-50 mb-4">Sign in as Employee</p>
              <a href="{{ route('welcome') }}" class="logo logo-admin">
                  <h1>A</h1>
              </a>
          </div>
          <div class="account-card-content">
            <form action="{{url('post-login')}}" method="POST" id="logForm">
              {{ csrf_field() }}

              <div class="form-label-group">
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" >
                <label for="inputEmail">Email address</label>

                @if ($errors->has('email'))
                <span class="error">{{ $errors->first('email') }}</span>
                @endif    
              </div> 

              <div class="form-label-group">
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">
                <label for="inputPassword">Password</label>
                 
                @if ($errors->has('password'))
                <span class="error">{{ $errors->first('password') }}</span>
                @endif  
              </div>

              <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Sign In</button>
              <div class="text-center">If you have an account?
                <a class="small" href="{{url('registration')}}">Sign Up</a>
              </div>
            </form>
          </div>
      </div>
    </div>
    <!-- end wrapper-page -->
@endsection

@section('script')
@endsection









