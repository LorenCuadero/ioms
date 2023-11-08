<div class="modal fade" id="dashboard-modal" tabindex="-1" role="dialog" aria-labelledby="dashboard-modal-label"
    aria-hidden="true">
    <div class="modal-dialog custom-modal-width" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="student-selection-modal-label">Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <a href="{{ route('rpt.dcpl.index') }}"><span aria-hidden="true">&times;</span> </a> </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12" id="table">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-center">
                                            <div class="row">
                                                <div class="col-md-6" style="text-align: left">
                                                    <p style="margin-bottom: 0%"><b>Total number of students:</b>
                                                        {{ $totalNumberOfStudents }}</p>
                                                    <p><b><span id="selected-batch-year"></span></b><span
                                                            id="total-students-per-year"></span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <form id="get_totals_by_batch_year_form" class=""
                                                        action="{{ route('admin.getTotals') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" id="batch-year-form"
                                                            data-total-by-year="{{ json_encode($totalStudentsByBatchYear) }}">
                                                        <div class="form-group row">
                                                            <label for="batch_year" class="col-md-5 col-form-label"
                                                                style="text-align: right">Batch Year</label>
                                                            <div class="col-md-7">
                                                                <select class="form-control" name="batch_year"
                                                                    id="batch_year">
                                                                    <option value="">All Batch Year</option>
                                                                    @foreach ($batchYears as $batchYear)
                                                                        <option name="batch_year"
                                                                            value="{{ $batchYear }}">
                                                                            {{ $batchYear }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <tbody class="table-body1">
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Paid
                                                        Counterpart</td>
                                                    <td id="counterpartPaidStudentsCount">
                                                        {{ $counterpartPaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Unpaid
                                                        Counterpart</td>
                                                    <td id="counterpartUnpaidStudentsCount">
                                                        {{ $counterpartUnpaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Not Fully Paid
                                                        Counterpart</td>
                                                    <td id="counterpartNotFullyPaidStudentsCount">
                                                        {{ $counterpartNotFullyPaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Paid Medical
                                                        Share</td>
                                                    <td id="medicalSharePaidStudentsCount">
                                                        {{ $medicalSharePaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Unpaid Medical
                                                        Share</td>
                                                    <td id="medicalShareUnpaidStudentsCount">
                                                        {{ $medicalShareUnpaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Not Fully
                                                        Medical Share</td>
                                                    <td id="medicalShareNotFullyPaidStudentsCount">
                                                        {{ $medicalShareNotFullyPaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Paid Personal
                                                        Cash Advance</td>
                                                    <td id="personalCashAdvancePaidStudentsCount">
                                                        {{ $personalCashAdvancePaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Unpaid
                                                        Personal Cash Advance</td>
                                                    <td id="personalCashAdvanceUnpaidStudentsCount">
                                                        {{ $personalCashAdvanceUnpaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Not Fully
                                                        Personal Cash Advance</td>
                                                    <td id="personalCashAdvanceNotFullyPaidStudentsCount">
                                                        {{ $personalCashAdvanceNotFullyPaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Paid
                                                        Graduation Fees</td>
                                                    <td id="graduationFeePaidStudentsCount">
                                                        {{ $graduationFeePaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Unpaid
                                                        Graduation Fees</td>
                                                    <td id="graduationFeeUnpaidStudentsCount">
                                                        {{ $graduationFeeUnpaidStudentsCount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:left">Total No of Students with Not Fully
                                                        Graduation Fees</td>
                                                    <td id="graduationFeeNotFullyPaidStudentsCount">
                                                        {{ $graduationFeeNotFullyPaidStudentsCount }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>