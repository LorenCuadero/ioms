<div class="modal fade" id="student-graduation-fee-payable-modal" tabindex="-1" role="dialog"
    aria-labelledby="student-selection-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-0" id="student-selection-modal-label">Graduation Fee Payables</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <a href="{{ route('admin.counterpartRecords') }}"><span aria-hidden="true">&times;</span></a>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <!-- Bootstrap grid structure -->
                    <div class="row d-flex justify-content-center align-items-center">
                        @foreach ($unpaidGraduationFeeRecords as $record)
                            <div class="col-md-6">
                                <div class="card mb-3 scrollable-content"
                                    style="height: 100px; overflow: auto; font-size: 13px;">
                                    <div class="card-header sticky-top"
                                        style="background-color: rgb(246, 246, 246); color: #1f3c88; height: 50px;">
                                        <p class="mb-0">
                                            <strong>{{ \Carbon\Carbon::parse($record->date)->format('F d, Y') }}</strong>
                                        </p>
                                    </div>
                                    <div class="card-body p-2 text-left">
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="mb-1" style="color: #1f3c88;">Amount Due:</p>
                                            </div>
                                            <div class="col-5">
                                                <p class="mb-1">
                                                    ₱{{ number_format($record->amount_due - $record->amount_paid, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
