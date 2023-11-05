<div class="modal fade" id="edit-student-personal-ca-modal" tabindex="-1" role="dialog"
    aria-labelledby="edit-student-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-student-modal-label">Edit Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: left">
                <form id="edit-personal-ca-form" method="POST"
                    action="{{ route('admin.updatePersonalCA', ['id' => 'personal_ca_id']) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="student_id">
                    <div class="form-group">
                        <label for="course_code">Purpose</label>
                        <textarea class="form-control" id="edit_purpose" name="purpose"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="amount_due">Amount Due</label>
                        <input type="number" name="amount_due" id="edit_amount_due" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="amount_paid">Amount Paid</label>
                        <input type="number" name="amount_paid" id="edit_amount_paid" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control" id="edit_date" rows="3"
                            placeholder="" required />
                    </div>
                    <div class="form-group" style="float: right;">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
                @include('assets.asst-loading-spinner')
            </div>
        </div>
    </div>
</div>
