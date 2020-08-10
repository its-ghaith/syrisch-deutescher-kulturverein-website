@hasRole(['admin'])
<li class="nav-item {{current_page('admin/dashboard')? 'active':''}}">
    <a class="nav-link" href="{{url('/admin/dashboard')}}">{{__('Dashboard')}} <span class="sr-only"></span></a>
</li>
<li class="nav-item dropdown {{current_page('admin/times_management')? 'active':''}}">
    <a class="nav-link dropdown-toggle" href="{{url('/admin/times_management')}}" id="navbarDropdownMenuLink" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{__('Times Management')}}
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <a class="dropdown-item" href="{{url('/admin/times_management/add_new_time')}}">{{__('Add new time')}}</a>
        <a class="dropdown-item" href="{{url('/admin/times_management/delete_time')}}">{{__('Delete old time')}}</a>
        <a class="dropdown-item" href="{{url('/admin/times_management/show_cities')}}">{{__('Show all cities')}}</a>
    </div>
</li>
@endhasRole
