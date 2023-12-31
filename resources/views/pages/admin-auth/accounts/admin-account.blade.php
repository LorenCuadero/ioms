@extends('layouts.admin.app')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12" id="table">
                    <div class="card">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between custom-admin-accounts-header"
                            style="background-color: #ffff; color: #1f3c88;">
                            <h3 class="card-title mb-3 mb-md-0"><b>Admin Accounts (Total: {{ $users->total() }})</b></h3>
                            <div class="d-flex flex-wrap align-items-center ml-auto">
                                <form class="form-inline mr-auto mr-md-0 mb-2 mb-md-0"
                                    style="display: flex; align-items: center;">
                                    <div class="nav-item btn btn-sm p-0" style="display: flex; align-items:center;">
                                        <a href="{{ route('admin.createAdminAccount') }}"
                                            class="nav-link align-items-center btn p-0"
                                            style="color:#1f3c88; text-decoration: none;">
                                            <i class="fas fa-user-plus" style="font-size: 23px"></i>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive scrollable-content">
                                <table id="example2" class="table table-hover data-table text-center"  style="font-size: 14px;width: 100%;border-collapse: collapse;">
                                    <thead style="color:#1f3c88">
                                        <tr>
                                            <th style="display:none;">ID</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Email</th>
                                            <th>Department</th>
                                            <th>Email Verified At</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    @php
                                        use Illuminate\Support\Str;
                                    @endphp
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td style="display:none;">{{ $user->id }}</td>
                                                <td>{{ $user->last_name }}</td>
                                                <td>{{ $user->first_name }}</td>
                                                <td>{{ Str::limit($user->email, 30) }}</td>
                                                <td>{{ $user->department }}</td>
                                                {{-- <td class="truncate">{{ $user->password }}</td> --}}
                                                <td>{{ $user->email_verified_at }}</td>
                                                <td class="admin-accounts-mobile-btn">
                                                    @if ($user->status == 0)
                                                        <strong><span class="text-success"
                                                                style="padding: 5px; font-size: 13px">Active</span></strong>
                                                    @else
                                                        <strong><span class="text-warning"
                                                                style="padding: 5px; font-size: 13px">Inactive</span></strong>
                                                    @endif
                                                </td>
                                                <td class="admin-accounts-mobile-btn">
                                                    <a href="{{ route('admin.getAdminAccount', ['id' => $user->user_id]) }}"
                                                        class="btn btn-sm view-button-counterpart"
                                                        style="color: #1f3c88; border-radius: 20px">
                                                        <strong><i class="fa fa-eye"></i> View</strong></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
