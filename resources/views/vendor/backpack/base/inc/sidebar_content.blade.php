<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>
{{--
<li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>--}}

<li><a href="{{ backpack_url('network') }}"><i class="fa fa-user"></i> <span>Networks</span></a></li>

<li><a href="{{ backpack_url('offer') }}"><i class="fa fa-user"></i> <span>Offers</span></a></li>


<li><a href="{{ backpack_url('click') }}"><i class="fa fa-user"></i> <span>Clicks</span></a></li>


<li><a href="{{ backpack_url('lead') }}"><i class="fa fa-user"></i> <span>Leads</span></a></li>

@can('system')
    <!-- Users, Roles Permissions -->
    <li class="treeview">
        <a href="#"><i class="fa fa-group"></i> <span>Users, Roles, Permissions</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="{{ backpack_url('user') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>
            <li><a href="{{ backpack_url('role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>
            <li><a href="{{ backpack_url('permission') }}"><i class="fa fa-key"></i> <span>Permissions</span></a></li>
            <li><a href="{{ backpack_url('device') }}"><i class="fa fa-key"></i> <span>Devices</span></a></li>
            <li><a href="{{ backpack_url('location') }}"><i class="fa fa-key"></i> <span>Locations</span></a></li>
        </ul>
    </li>
@endcan

