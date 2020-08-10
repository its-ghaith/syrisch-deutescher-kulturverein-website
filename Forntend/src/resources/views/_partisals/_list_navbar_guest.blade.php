@hasRole(['guest','user'])
<li class="nav-item {{current_page()? 'active':''}}">
    <a class="nav-link" href="{{url('/')}}">{{__('Home')}} <span class="sr-only"></span></a>
</li>
<li class="nav-item {{current_page('projects')? 'active':''}}">
    <a class="nav-link" href="{{url('/projects')}}">{{__('Our projects')}}</a>
</li>
<li class="nav-item dropdown {{current_page('alsahaba_mosque')? 'active':''}}">
    <a class="nav-link dropdown-toggle" href="{{url('/alsahaba_mosque')}}" id="navbarDropdownMenuLink" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{__('Alsahaba Mosque')}}
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="{{url('/alsahaba_mosque')}}">{{__('Home')}}</a>
        <a class="dropdown-item" href="{{url('/alsahaba_mosque/prayer_times')}}">{{__('Prayer times')}}</a>
        <a class="dropdown-item" href="{{url('/alsahaba_mosque/forum')}}">{{__('Forum')}}</a>
        <a class="dropdown-item" href="{{url('/alsahaba_mosque/about')}}">{{__('About')}}</a>
    </div>
</li>
@endhasRole
