<nav class="navbar navbar-expand-lg navbar-dark bg-primary container-fluid">

    <a class="navbar-brand mb-5 m-md-0 ml-md-3" href="{{url('/')}}">
        @include('_partisals._logo')
        <p class="d-inline mr-5" style="font-family: 'Orator Std Medium', 'Oswald', sans-serif;">{{__('Syrisch Deutsher Kulturverein')}}</p>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02"
            aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto ml-auto mt-2 mt-lg-0 mb-4">
            @include('_partisals._list_navbar_guest')
            @include('_partisals._list_navbar_admin')
        </ul>
        <ul class="navbar-nav ml-auto mr-md-3">
            @not_login
            <li class="nav-item mb-4">
                <a id="login-btn" class="nav-link btn bg-warning btn-sm ml-1 px-5 text-white" title="{{__('Login')}}"
                   href="{{url('/login')}}">{{__('Login')}}</a>
            </li>
            <li class="nav-item">
                <a id="register-btn" class="nav-link btn bg-warning bg-white btn-sm ml-1 px-5 text-dark" title="{{__('Register')}}"
                   href="{{url('/register')}}">{{__('Register')}}</a>
            </li>
            @else
            <li class="nav-item mb-4">
                <form class="form-inline" role="form" method="POST" action="{{url('/logout')}}">
                    @csrf
                    <button type="submit" class="btn ml-1 px-5 btn-danger shadow-sm">{{__('Logout')}}</button>
                </form>
            </li>
            @endnot_login
        </ul>
    </div>
</nav>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 230">
    <path fill="#1dd1a1" fill-opacity="1"
          d="M0,192L48,197.3C96,203,192,213,288,213.3C384,213,480,203,576,170.7C672,139,768,85,864,85.3C960,85,1056,139,1152,144C1248,149,1344,107,1392,85.3L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
    </path>
</svg>
