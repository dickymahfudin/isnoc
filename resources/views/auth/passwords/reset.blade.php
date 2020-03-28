@extends('layouts.template')

@section('content')
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <div class="w-100 p-3">
                    <img src="{{asset('images/LogoSundaya.png')}}" alt="IMG">
                </div>
                <div class="mt-2">
                    <img src="{{asset('images/Sundayahome.png')}}" alt="IMG">
                </div>
            </div>

            <form action="{{ route('password.update') }}" class="login100-form validate-form" method="POST">
                @csrf
                <span class="login100-form-title">
                    {{ __('Reset Password') }}
                </span>

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="wrap-input100 validate-input">
                    <input id="email" class="input100 @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email" value="{{ $email ?? old('email') }}" required autocomplete="email">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </span>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="wrap-input100 validate-input">
                    <input id="password" class="input100 @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password"  required autocomplete="new-password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="wrap-input100 validate-input">
                    <input id="password-confirm" class="input100" type="password" name="password_confirmation" placeholder="Password Confirmation" required autocomplete="new-password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="container-login100-form-btn">
                    <button type="submit"  class="login100-form-btn">
                        {{ __('Reset Password') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
