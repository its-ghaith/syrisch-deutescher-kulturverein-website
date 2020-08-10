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
      action="{{url('/register')}}">
    @csrf
    <div class="form-group">
        <div class="col-md-12">
            <input id="name" placeholder="{{__('Name')}}" type="text"
                   class="form-control py-md-4 rounded-0 text-left shadow-sm" name="name"
                   value="" required="">
            <span class="small text-secondary">{{__('Full name (appears for management)')}}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <input id="username" placeholder="{{__('Username')}}" type="text"
                   class="form-control py-md-4 rounded-0 text-left shadow-sm" name="username"
                   value="" required="">
            <span class="small text-secondary">{{__('Your display name (English letters and numbers only)')}}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <input id="email" placeholder="{{__('Email')}}" type="email"
                   class="form-control py-md-4 rounded-0 text-left shadow-sm" name="email"
                   value="" required="">
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <input placeholder="{{__('Password')}}" id="password" type="password"
                   class="form-control py-md-4 rounded-0 text-left shadow-sm" name="password"
                   required="">

        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <input id="password_confirmation" placeholder="{{__('Password Confirmation')}}"
                   type="password" class="form-control py-md-4 rounded-0 text-left shadow-sm"
                   name="password_confirmation" required="">
        </div>
    </div>

    <div class="small p-3">
        By entering your account, you agree to the <a href="#">
            Privacy  Policy</a> and
        <a href="#">Terms of Use</a>.
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" class="btn btn-block btn-primary shadow-sm">
                {{__('Sing up')}}
            </button>
        </div>
    </div>
</form>
