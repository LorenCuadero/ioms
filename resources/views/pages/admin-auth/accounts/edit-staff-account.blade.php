@extends('layouts.admin.app')
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-body" style="background-color: none; border: none;">
                <h1 class="card-title mb-3 mb-md-0" style="color:#1f3c88;"><b>Staff Account Information: Update Account</b>
                </h1>
                <br>
                <hr>
                @if ($errors->has('msg'))
                    {{ $errors->first('msg') }}
                @endif

                <form method="POST" action="{{ route('admin.updateStaffAccount', ['id' => $user->user_id]) }}">
                    @method('PUT')
                    @csrf
                    <div class="row" style="text-align: left;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="first_name_staff">First Name  <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name_staff" name="first_name"
                                    value="{{ $user->first_name }}" autocomplete="on" />
                            </div>
                            <div class="form-group">
                                <label for="middle_name_staff">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name_staff" name="middle_name"
                                    value="{{ $user->middle_name }}" />
                            </div>
                            <div class="form-group">
                                <label for="last_name_staff">Last Name  <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name_staff" name="last_name"
                                    value="{{ $user->last_name }}" />
                            </div>
                            <div class="form-group">
                                <label for="email_staff">Email Address  <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email_staff" name="email"
                                    value="{{ $user->email }}" autocomplete="on" />
                            </div>
                            @error('email')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact_number_staff">Contact Number</label>
                                <input type="number" class="form-control" id="contact_number_staff" name="contact_number"
                                    value="{{ $user->contact_number }}" />
                            </div>
                            <div class="form-group">
                                <label for="address_staff">Address  <span class="text-danger">*</span></label>
                                <input name="address" class="form-control" id="address_staff" value="{{ $user->address }}"
                                    autocomplete="on" />
                            </div>
                            <div class="form-group">
                                <label for="birthdate_staff">Birthdate  <span class="text-danger">*</span></label>
                                <input type="date" max="{{ now()->subYears(18)->format('Y-m-d') }}" class="form-control"
                                    id="birthdate_staff" name="birthdate" value="{{ $user->birthdate }}" />
                            </div>
                            @error('birthdate')
                                <div class="alert alert-danger">
                                    {{ $message = 'You must be at least 18 years old.' }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender_staff">Gender  <span class="text-danger">*</span></label>
                                <select class="form-control" id="gender_staff" name="gender">
                                    <option value="{{ $user->gender }}">
                                        @if ($user->gender == 'Male')
                                            He
                                        @endif
                                        @if ($user->gender == 'Female')
                                            She
                                        @endif
                                    </option>
                                    <option value="Male">He</option>
                                    <option value="Female">She</option>
                                    <option value="Non-binary">Non-Binary</option>
                                    <option value="Prefer not to say">Prefer not to say</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="department_staff">Department  <span class="text-danger">*</span></label>
                                <select name="department" id="department_staff" class="form-control">
                                    <option value="{{ $user->department }}">{{ $user->department }}</option>
                                    <option value="Administrative">Administrative</option>
                                    <option value="Administrative Assistant">Administrative Assistant</option>
                                    <option value="Finance">Finance</option>
                                    <option value="HR">HR</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="civil_status_staff">Civil Status  <span class="text-danger">*</span></label>
                                <select name="civil_status" id="civil_status_staff" class="form-control">
                                    <option value="{{ $user->civil_status }}">{{ $user->civil_status }}</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widow">Widow</option>
                                    <option value="Separated">Separated</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                            </div>
                            <br>
                            <br>
                            <div class="form-group" style="float: right">
                                <button type="submit" class="btn btn-primary mr-2">Save changes</button>
                                <button type="button" class="btn btn-danger mr-2" data-toggle="modal"
                                    data-target="#confirmDeleteModal">Delete</button>
                                <a href="{{ route('admin.accounts.staff-accounts') }}" class="btn btn-default">Cancel</a>
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
                                    action="{{ route('admin.softDeleteStaffAccount', ['id' => $user->user_id]) }}">
                                    @method('DELETE')
                                    @csrf
                                    <div class="form-group">
                                        <a href="{{ route('admin.accounts.staff-accounts') }}"
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
