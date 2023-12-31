<?php

namespace App\Http\Controllers;

use App\Models\Disciplinary;
use App\Services\StoreLogsService;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendDisciplinaryNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DisciplinaryController extends Controller {
    public function index() {
        if(Auth::user()->role != '1') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $users = User::where('is_deleted', false)->get();
        $studentIds = $users->pluck('id');
        $studentsWithDisciplinaryRecords = Student::whereIn('user_id', $studentIds)->has('disciplinary')->get();

        // $studentsWithDisciplinaryRecords = Student::has('disciplinary')->get();
        return view('pages.staff-auth.reports.rpt-disciplinary.rpt-disciplinary-page', compact('studentsWithDisciplinaryRecords'));
    }

    public function showDisciplinaryRecordsForStudent($id) {
        $students = Student::find($id);
        return view('modals.staff.mdl-student-dcpl-rpt-add', compact('students'));
    }

    public function showAdminDisciplinaryRecordsForStudent($id)
    {
        $students = Student::find($id);
        return view('modals.admin.reports.mdl-student-dcpl-rpt-add', compact('students'));
    }

    public function indexDisciplinaryReports(Request $request)
    {
        if (Auth::user()->role == '2') {
            // Retrieve all students
            $users = User::where('is_deleted', false)->get();
            $studentIds = $users->pluck('id');
            $students = Student::whereIn('user_id', $studentIds)->whereDoesntHave('disciplinary')->get();

            // Retrieve all disciplinary records along with their associated students
            $studentsWithDisciplinaryRecords = Disciplinary::with('student')->get();

            // Get the selected student's ID from the request (replace 'student_id' with your actual route parameter)
            $selectedStudentId = $request->route('student_id');

            // Filter records for the selected student
            $selectedStudentRecords = $studentsWithDisciplinaryRecords->where('student_id', $selectedStudentId);

            return view('pages.admin-auth.disciplinary-reports.index', compact('students', 'selectedStudentId', 'selectedStudentRecords', 'studentsWithDisciplinaryRecords'));
        } else {
            return redirect()->back()->with('error', 'You are not authorized to access this page.');
        }
    }


    public function create() {
        if(Auth::user()->role != '1') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $students = Student::all();
        return view('disciplinary.create', compact('students'));
    }

    public function store(Request $request) {
        if(Auth::user()->role != '1') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        if($request->input('student_id_dcpl') == null) {
            return redirect()->back()->with('error', 'Please select a student');
        } elseif($request->input('verbal_warning_date') == null && $request->input('verbal_warning_description') != null) {
            return redirect()->back()->with('error', 'Please select a verbal warning date');
        } elseif($request->input('written_warning_date') == null && $request->input('written_warning_description') != null) {
            return redirect()->back()->with('error', 'Please select a written warning date');
        } elseif($request->input('provisionary_date') == null && $request->input('provisionary_description') != null) {
            return redirect()->back()->with('error', 'Please select a probationary warning date');
        } elseif($request->input('verbal_warning_date') != null && $request->input('verbal_warning_description') == null) {
            return redirect()->back()->with('error', 'Please enter a verbal warning description');
        } elseif($request->input('written_warning_date') != null && $request->input('written_warning_description') == null) {
            return redirect()->back()->with('error', 'Please enter a written warning description');
        } elseif($request->input('provisionary_date') != null && $request->input('provisionary_description') == null) {
            return redirect()->back()->with('error', 'Please enter a probationary warning description');
        }

        $data = $request->validate([
            'verbal_warning_description' => 'nullable|string',
            'verbal_warning_date' => 'nullable|date',
            'written_warning_description' => 'nullable|string',
            'written_warning_date' => 'nullable|date',
            'provisionary_description' => 'nullable|string',
            'provisionary_date' => 'nullable|date',
            'student_id_dcpl' => 'required|exists:students,id',
        ]);

        $disciplinary = new Disciplinary($data);

        // Associate the disciplinary record with the student
        $student = Student::find($request->input('student_id_dcpl'));
        $student->disciplinary()->save($disciplinary);

        $action = 'Added';
        StoreLogsService::storeLogs(auth()->user()->id, $action, 'Disciplinary', $request->input('student_id_dcpl'), null, $student->batch_year);

        Mail::to($student->email)->send(new SendDisciplinaryNotification($student->first_name, $request->input('verbal_warning_description'), $request->input('verbal_warning_date'), $request->input('written_warning_description'), $request->input('written_warning_date'), $request->input('provisionary_description'), $request->input('provisionary_date')));

        return redirect()->route('rpt.dcpl.index')->with('success', 'Disciplinary record created.');
    }

    public function storeForAdmin(Request $request)
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        // dd($request->all());

        if ($request->input('student_id_dcpl_input_admin') == null) {
            return redirect()->back()->with('error', 'Please select a student');
        } elseif ($request->input('verbal_warning_date') == null && $request->input('verbal_warning_description') != null) {
            return redirect()->back()->with('error', 'Please select a verbal warning date');
        } elseif ($request->input('written_warning_date') == null && $request->input('written_warning_description') != null) {
            return redirect()->back()->with('error', 'Please select a written warning date');
        } elseif ($request->input('provisionary_date') == null && $request->input('provisionary_description') != null) {
            return redirect()->back()->with('error', 'Please select a probationary warning date');
        } elseif ($request->input('verbal_warning_date') != null && $request->input('verbal_warning_description') == null) {
            return redirect()->back()->with('error', 'Please enter a verbal warning description');
        } elseif ($request->input('written_warning_date') != null && $request->input('written_warning_description') == null) {
            return redirect()->back()->with('error', 'Please enter a written warning description');
        } elseif ($request->input('provisionary_date') != null && $request->input('provisionary_description') == null) {
            return redirect()->back()->with('error', 'Please enter a probationary warning description');
        }

        $data = $request->validate([
            'verbal_warning_description' => 'nullable|string',
            'verbal_warning_date' => 'nullable|date',
            'written_warning_description' => 'nullable|string',
            'written_warning_date' => 'nullable|date',
            'provisionary_description' => 'nullable|string',
            'provisionary_date' => 'nullable|date',
            'student_id_dcpl_input_admin' => 'required|exists:students,id',
        ]);

        $disciplinary = new Disciplinary($data);

        // Associate the disciplinary record with the student
        $student = Student::find($request->input('student_id_dcpl_input_admin'));
        $student->disciplinary()->save($disciplinary);

        $action = 'Added';
        StoreLogsService::storeLogs(auth()->user()->id, $action, 'Disciplinary', $request->input('student_id_dcpl_input_admin'), null, $student->batch_year);

        Mail::to($student->email)->send(new SendDisciplinaryNotification($student->first_name, $request->input('verbal_warning_description'), $request->input('verbal_warning_date'), $request->input('written_warning_description'), $request->input('written_warning_date'), $request->input('provisionary_description'), $request->input('provisionary_date')));

        return redirect()->route('admin.reports.indexDisciplinaryReports')->with('success', 'Disciplinary record created.');
    }



    public function show(Disciplinary $disciplinary) {
        if(Auth::user()->role != '1') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        return view('disciplinary.show', compact('disciplinary'));
    }

    public function edit(Disciplinary $disciplinary) {
        if(Auth::user()->role != '1') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $students = Student::all();
        return view('disciplinary.edit', compact('disciplinary', 'students'));
    }

    public function update(Request $request, $id) {
        if(Auth::user()->role != '1') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $existingRecord = Disciplinary::findOrFail($id);

        if(
            ($request->input('verbal_warning_date') == null && $request->input('verbal_warning_description') != null) ||
            ($request->input('written_warning_date') == null && $request->input('written_warning_description') != null) ||
            ($request->input('provisionary_date') == null && $request->input('provisionary_description') != null) ||
            ($request->input('verbal_warning_date') != null && $request->input('verbal_warning_description') == null) ||
            ($request->input('written_warning_date') != null && $request->input('written_warning_description') == null) ||
            ($request->input('provisionary_date') != null && $request->input('provisionary_description') == null)
        ) {
            return redirect()->back()->with('error', 'Invalid input. Please check your data.');
        }

        $data = [
            'verbal_warning_description' => $request->input('verbal_warning_description'),
            'verbal_warning_date' => $request->input('verbal_warning_date'),
            'written_warning_description' => $request->input('written_warning_description'),
            'written_warning_date' => $request->input('written_warning_date'),
            'provisionary_description' => $request->input('provisionary_description'),
            'provisionary_date' => $request->input('provisionary_date'),
        ];

        $existingRecord->fill($data);
        $existingRecord->save();
        $student = Student::find($existingRecord->student_id);

        $action = 'Updated';
        StoreLogsService::storeLogs(auth()->user()->id, $action, 'Disciplinary', $existingRecord->student_id, null, $student->batch_year);

        Mail::to($student->email)->send(new SendDisciplinaryNotification(
            $student->first_name,
            $data['verbal_warning_description'],
            $data['verbal_warning_date'],
            $data['written_warning_description'],
            $data['written_warning_date'],
            $data['provisionary_description'],
            $data['provisionary_date']
        ));

        return redirect()->route('rpt.dcpl.index')->with('success', 'Disciplinary record updated.');
    }

    public function updateForAdmin(Request $request, $id)
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $existingRecord = Disciplinary::findOrFail($id);

        if (
            ($request->input('verbal_warning_date') == null && $request->input('verbal_warning_description') != null) ||
            ($request->input('written_warning_date') == null && $request->input('written_warning_description') != null) ||
            ($request->input('provisionary_date') == null && $request->input('provisionary_description') != null) ||
            ($request->input('verbal_warning_date') != null && $request->input('verbal_warning_description') == null) ||
            ($request->input('written_warning_date') != null && $request->input('written_warning_description') == null) ||
            ($request->input('provisionary_date') != null && $request->input('provisionary_description') == null)
        ) {
            return redirect()->back()->with('error', 'Invalid input. Please check your data.');
        }

        $data = [
            'verbal_warning_description' => $request->input('verbal_warning_description'),
            'verbal_warning_date' => $request->input('verbal_warning_date'),
            'written_warning_description' => $request->input('written_warning_description'),
            'written_warning_date' => $request->input('written_warning_date'),
            'provisionary_description' => $request->input('provisionary_description'),
            'provisionary_date' => $request->input('provisionary_date'),
        ];

        $existingRecord->fill($data);
        $existingRecord->save();
        $student = Student::find($existingRecord->student_id);

        $action = 'Updated';
        StoreLogsService::storeLogs(auth()->user()->id, $action, 'Disciplinary', $existingRecord->student_id, null, $student->batch_year);

        Mail::to($student->email)->send(new SendDisciplinaryNotification(
            $student->first_name,
            $data['verbal_warning_description'],
            $data['verbal_warning_date'],
            $data['written_warning_description'],
            $data['written_warning_date'],
            $data['provisionary_description'],
            $data['provisionary_date']
        ));

        return redirect()->route('admin.reports.indexDisciplinaryReports')->with('success', 'Disciplinary record updated.');
    }

    public function destroy(Request $request, $id) {
        if(Auth::user()->role != '1') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $disciplinary = Disciplinary::findOrFail($id);
        $disciplinary->delete();

        $action = 'Deleted';
        StoreLogsService::storeLogs(auth()->user()->id, $action, 'Disciplinary', $disciplinary->student_id, null, $disciplinary->batch_year);

        return redirect()->route('rpt.dcpl.index')->with('success', 'Disciplinary record deleted.');
    }

    public function destroyForAdmin(Request $request, $id)
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $disciplinary = Disciplinary::findOrFail($id);
        $disciplinary->delete();

        $action = 'Deleted';
        StoreLogsService::storeLogs(auth()->user()->id, $action, 'Disciplinary', $disciplinary->student_id, null, $disciplinary->batch_year);

        return redirect()->route('admin.reports.indexDisciplinaryReports')->with('success', 'Disciplinary record deleted.');
    }
}
