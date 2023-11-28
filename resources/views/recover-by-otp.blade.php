@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('One Time Password') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('recover-submit') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Please enter valid OTP to recover</label>
                                <input type="hidden" id="email_recover" name="email" value="{{ $user_email }}">
                                <input type="number" id="otp_recover" name="otp" class="form-control"
                                    placeholder="Enter token here">
                                @if ($errors->any())
                                    <p><span class="text-danger error-display"> {{ $errors->first() }}</span>
                                    </p>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('login') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                        @include('assets.asst-loading-spinner')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
