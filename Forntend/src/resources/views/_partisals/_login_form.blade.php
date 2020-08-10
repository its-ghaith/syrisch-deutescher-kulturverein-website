@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif
<form class="form-horizontal" role="form" method="POST"
      action="{{url('/login')}}">
    @csrf
    <div class="form-group">
        <div class="col-md-12">
            <input type="email" class="form-control py-md-4 rounded-0 text-left shadow-sm"
                   name="email" placeholder="{{__('Email')}}" value=""
                   required="">
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <input type="password" class="form-control py-md-4 rounded-0 shadow-sm text-left shadow-sm"
                   placeholder="{{__('Password')}}" name="password" required="">
        </div>
    </div>

    <div class="form-group mb-0">
        <div class="col-md-12 text-center">
            <label>
                <input type="checkbox" name="remember"> {{__('Remember me')}}
            </label>
        </div>
    </div>

    <div class="small p-3">
        By entering your account, you agree to the <a href="#">
            Privacy  Policy</a> and
        <a href="#">Terms of Use</a>.
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" class="btn btn-block btn-primary shadow-sm">{{__('Login')}}</button>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 pb-md-4 text-center">
            <a href="#"
               title="{{__('Did you forget your password?')}}">{{__('Did you forget your password?')}}</a>
        </div>
    </div>
</form>
