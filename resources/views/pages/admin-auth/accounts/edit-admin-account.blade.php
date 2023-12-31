@extends('layouts.admin.app')
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-body" style="background-color: none; border: none;">
                <h1 class="card-title" style="color:#1f3c88;"><b>Admin Account Information: Update Form</b></h1>
                <br>
                <hr>
                @if ($errors->has('msg'))
                    {{ $errors->first('msg') }}
                @endif

                <form method="POST" action="{{ route('admin.updateAdminAccount', ['id' => $user->user_id]) }}">
                    @method('PUT')
                    @csrf
                    <div class="row" style="text-align: left;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="first_name_admin">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name_admin" name="first_name"
                                    value="{{ $user->first_name }}" autocomplete="on" />
                            </div>
                            <!-- Add the new fields to match the student information add form -->
                            <div class="form-group">
                                <label for="suffix_admin">Suffix <span class="text-danger">*</span></label>
                                <select class="form-control" id="suffix_admin" name="suffix">
                                    <option value="">Select Suffix</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gender_admin">Gender <span class="text-danger">*</span></label>
                                <select class="form-control" id="gender_admin" name="gender">
                                    <option value="Male">He</option>
                                    <option value="Female">She</option>
                                    <option value="Non-binary">Non-Binary</option>
                                    <option value="Prefer not to say">Prefer not to say</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="contact_number_admin">Contact Number</label>
                                <input type="number" class="form-control" id="contact_number_admin" name="contact_number"
                                    value="{{ $user->contact_number }}" />
                            </div>
                            @error('contact_number')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-group">
                                <label for="civil_status_admin">Civil Status <span class="text-danger">*</span></label>
                                <select name="civil_status" id="civil_status_admin" class="form-control">
                                    <option value="{{ $user->civil_status }}">{{ $user->civil_status }}</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widow">Widow</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="middle_name_admin">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name_admin" name="middle_name"
                                    value="{{ $user->middle_name }}" />
                            </div>
                            <div class="form-group">
                                <label for="address_admin">Address <span class="text-danger">*</span></label>
                                <input name="address" class="form-control" id="address_admin" value="{{ $user->address }}"
                                    autocomplete="on" />
                            </div>
                            <div class="form-group">
                                <label for="email_admin">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email_admin" name="email"
                                    value="{{ $user->email }}" autocomplete="on" />
                            </div>
                            @error('email')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-group">
                                <label for="password_admin">Password <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="password_admin" name="password" />
                            </div>
                            @error('password')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="last_name_admin">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name_admin" name="last_name"
                                    value="{{ $user->last_name }}" />
                            </div>
                            <div class="form-group">
                                <label for="birthdate_admin">Birthdate <span class="text-danger">*</span></label>
                                <input type="date" max="{{ now()->subYears(18)->format('Y-m-d') }}"
                                    class="form-control" id="birthdate_admin" name="birthdate"
                                    value="{{ $user->birthdate }}" />
                            </div>
                            @error('birthdate')
                                <div class="alert alert-danger">
                                    {{ $message = 'You must be at least 18 years old.' }}
                                </div>
                            @enderror
                            <div class="form-group">
                                <label for="department_admin">Department <span class="text-danger">*</span></label>
                                <select name="department" id="department_admin" class="form-control">
                                    <option value="Administrative & Finance"
                                        @if ($user->department == 'Administrative & Finance') selected @endif>
                                        Administrative & Finance
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status_admin">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status_admin" class="form-control">
                                    <option value="0" @if ($user->status == 0) selected @endif>Active
                                    </option>
                                    <option value="1" @if ($user->status == 1) selected @endif>Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <br>
                            <div class="form-group" style="float: right">
                                <button type="submit" class="btn btn-primary mr-2">Save changes</button>
                                <button type="button" class="btn btn-danger mr-2" data-toggle="modal"
                                    data-target="#confirmDeleteModal">Delete</button>
                                <a href="{{ route('admin.accounts.admin-accounts') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Modal for confirming delete action -->
                <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
                    aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this account?
                            </div>
                            <div class="modal-footer">
                                <form method="POST"
                                    action="{{ route('admin.softDeleteAdminAccount', ['id' => $user->user_id]) }}">
                                    @method('DELETE')
                                    @csrf
                                    <div class="form-group">
                                        <a href="{{ route('admin.accounts.admin-accounts') }}"
                                            class="btn btn-default mr-2">Cancel</a>
                                        <!-- Delete button in the modal -->
                                        <button type="submit" class="btn btn-danger" data-toggle="modal"
                                            data-target="#confirmDeleteModal">Confirm Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @include('assets.asst-loading-spinner')
            </div>
        </div>
    </section>
@endsection
