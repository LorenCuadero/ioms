<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Academic;
use App\Models\Disciplinary;
use App\Models\Student;
use App\Models\User;
use App\Services\StoreLogsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendStudentNotification;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();

        $batchYears = [];

        foreach ($students as $student) {
            if (!in_array($student->batch_year, $batchYears)) {
                $batchYears[] = $student->batch_year;
            }
        }

        return view('pages.staff-auth.students.index', [
            'students' => $students,
            'batchYears' => $batchYears,
        ]);
    }

    public function addStudentPage()
    {
        return view('pages.staff-auth.students.student-info-page-add');
    }

    public function getStudent($id)
    {
        $student = Student::find($id);
        return view('pages.staff-auth.students.index', compact('student'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'second_name' => 'nullable',
            'suffix' => 'nullable',
            'last_name' => 'required',
            'phone' => 'nullable',
            'birthdate' => 'required|date',
            'address' => 'required',
            'parent_name' => 'required',
            'parent_contact' => 'required',
            'batch_year' => 'required',
            'joined' => 'required|date',
        ]);

        $specialCharacters = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '+', '=', '{', '}', '[', ']', '|', ';', ':', '"', "'", '<', '>', ',', '.', '?', '/'];

        if (
            strpbrk($request->input('first_name'), implode('', $specialCharacters)) !== false ||
            strpbrk($request->input('last_name'), implode('', $specialCharacters)) !== false ||
            strpbrk($request->input('middle_name'), implode('', $specialCharacters)) !== false ||
            strpbrk($request->input('parent_name'), implode('', $specialCharacters)) !== false ||
            strpbrk($request->input('second_name'), implode('', $specialCharacters)) !== false
        ) {
            return redirect()->back()->with('error', 'Name should not contain special characters');
        }

        if (request()->input('second_name') == null) {
            $validatedData['email'] = strtolower($validatedData['first_name'] . '.' . $validatedData['last_name'] . '@student.passerellesnumeriques.org');
        } else if (request()->input('second_name') != null) {
            $validatedData['email'] = strtolower($validatedData['first_name'] . '_' . $validatedData['second_name'] . '.' . $validatedData['last_name'] . '@student.passerellesnumeriques.org');
        }

        $student = new Student($validatedData);
        $student->save();

        // Create a new user instance associated with the student's email
        $user = new User();
        $user->email = $validatedData['email'];
        $user->name = $validatedData['first_name'] . ' ' . $validatedData['last_name'];
        $user->password = bcrypt('d3f@ultP@$$w0rd');
        $user->save();
        $defaultPassUnHashed = 'd3f@ultP@$$w0rd';
        session()->flash('success', 'Student added successfully.');

        $action = "Added";
        StoreLogsService::storeLogs(auth()->user()->id, $action, "Student", $student->id, null, $validatedData['batch_year']);

        Mail::to($user->email)->send(new SendStudentNotification($user->name, $user->email, $defaultPassUnHashed));

        // Redirect to the students index page with a success message
        return redirect()->back()->with('success', 'New student added successfully!');
    }


    public function updateStudent(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'required',
            'birthdate' => 'required|date',
            'address' => 'required',
            'parent_name' => 'required',
            'parent_contact' => 'required',
            'batch_year' => 'required',
            'joined' => 'required|date',
        ]);

        $student = Student::findOrFail($id);
        $student->first_name = $request->get('first_name');
        $student->last_name = $request->get('last_name');
        $student->middle_name = $request->get('middle_name');
        $student->email = $request->get('email');
        $student->phone = $request->get('phone');
        $student->birthdate = $request->get('birthdate');
        $student->address = $request->get('address');
        $student->parent_name = $request->get('parent_name');
        $student->parent_contact = $request->get('parent_contact');
        $student->joined = $request->get('joined');
        $student->batch_year = $request->get('batch_year');
        $student->save();

        session()->flash('success', 'Student added successfully.');

        return back()->with('success', 'Student updated!');
    }

    // Academic Reports Controllers

    public function indexAcdRpt()
    {
        $students = Student::all();
        $batchYears = [];

        foreach ($students as $student) {
            if (!in_array($student->batch_year, $batchYears)) {
                $batchYears[] = $student->batch_year;
            }

            // Retrieve all academic records for the student
            $academics = Academic::where('student_id', $student->id)->get();

            $first_sem_fisrt_year_gpa = 0;
            $second_sem_first_year_gpa = 0;
            $first_sem_second_year_gpa = 0;
            $second_sem_second_year_gpa = 0;
            $first_sem_third_year_gpa = 0;

            foreach ($academics as $academic) {
                switch ($academic->year_and_sem) {
                    case 0:
                        $first_sem_fisrt_year_gpa += ($academic->midterm_grade + $academic->final_grade) / 2;
                        break;
                    case 1:
                        $second_sem_first_year_gpa += ($academic->midterm_grade + $academic->final_grade) / 2;
                        break;
                    case 2:
                        $first_sem_second_year_gpa += ($academic->midterm_grade + $academic->final_grade) / 2;
                        break;
                    case 3:
                        $second_sem_second_year_gpa += ($academic->midterm_grade + $academic->final_grade) / 2;
                        break;
                    case 4:
                        $first_sem_third_year_gpa += ($academic->midterm_grade + $academic->final_grade) / 2;
                        break;
                }
            }

            $total_gpa_per_semester = $first_sem_fisrt_year_gpa + $second_sem_first_year_gpa + $first_sem_second_year_gpa + $second_sem_second_year_gpa + $first_sem_third_year_gpa;

            // Assign the total GPA to the student model
            $student->gwa = round($total_gpa_per_semester, 2);
        }

        return view('pages.staff-auth.reports.rpt-academic.rpt-academic-page', [
            'students' => $students,
            'batchYears' => $batchYears,
        ]);
    }


    public function getStudentAcademicReport($id)
    {
        $student = Student::find($id);
        return view('pages.staff-auth.students.student-info-page', compact('student'));
    }

    public function getStudentGradeReport($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return back()->with('error', 'Student not found!');
        }

        $academics = Academic::where('student_id', $student->id)->get();

        return view('pages.staff-auth.reports.rpt-academic.rpt-academic-grade-page', compact('student', 'academics'));
    }

    public function addStudentGradeReport(Request $request, $id)
    {
        if ($request->input('course_code') == null) {
            return back()->with('error', 'Please enter a course code');
        }  elseif ($request->input('midterm_grade') > 4 || $request->input('midterm_grade') < 0) {
            return back()->with('error', 'Please enter a valid midterm grade');
        } elseif ($request->input('final_grade') > 4 || $request->input('final_grade') < 0) {
            return back()->with('error', 'Please enter a valid final grade');
        } elseif ($request->input('year_and_sem') == null && $request->input('midterm_grade') != null) {
            return back()->with('error', 'Please select a year and semester');
        }

        // Validate the input data
        $validatedData = $request->validate([
            'course_code' => 'required|string',
            'student_id' => 'required|exists:students,id',
            'year_and_sem' => 'nullable|numeric|between:0,4',
            'midterm_grade' => 'nullable|numeric|between:0,5',  // Updated validation for midterm_grade
            'final_grade' => 'nullable|numeric|between:0,5',    // Updated validation for final_grade
            'gpa' => 'nullable|numeric|between:0,5',
        ]);

        $validatedData['gpa'] = $validatedData['midterm_grade'] + $validatedData['final_grade'] / 2;

        $academic = Academic::where('student_id', $validatedData['student_id'])
            ->where('course_code', $validatedData['course_code'])
            ->first();

        if ($academic) {
            return redirect()->back()->with('error', 'An academic record for this course already exists.');
        }

        // Create a new academic record
        Academic::create($validatedData);

        return redirect()->back()->with('success', 'Academic record added successfully!');
    }


    public function updateStudentGradeReport(Request $request, $id)
    {
        $request->validate([
            'course_code' => 'required|string',
            'year_and_sem' => 'nullable',
            'midterm' => 'nullable|numeric',
            'final' => 'nullable|numeric',
        ]);

        if ($request->input('course_code') == null) {
            return back()->with('error', 'Please enter a course code');
        } elseif ($request->input('year_and_sem') == null && $request->input('midterm') != null) {
            return back()->with('error', 'Please select a year and semester');
        } elseif ($request->input('midterm') > 4 || $request->input('midterm') < 0) {
            return back()->with('error', 'Please enter a valid midterm grade');
        } elseif ($request->input('final') > 4 || $request->input('final') < 0) {
            return back()->with('error', 'Please enter a valid final grade');
        }

        $academicId = $request->input('academic_id');

        $academic = Academic::find($academicId);

        if (!$academic) {
            return back()->with('error', 'Academic record not found.');
        }

        $academic->course_code = $request->input('course_code');
        $academic->year_and_sem = $request->input('year_and_sem');
        $academic->midterm_grade = $request->input('midterm');
        $academic->final_grade = $request->input('final');
        $academic->gpa = $request->input('midterm') + $request->input('final') / 2;

        $academic->save();

        return redirect()->back()->with('success', 'Academic record updated successfully.');
    }

    public function indexStudsList(Request $request)
    {
        // Retrieve all students
        $students = Student::whereDoesntHave('disciplinary')->get();

        // Retrieve all disciplinary records along with their associated students
        $studentsWithDisciplinaryRecords = Disciplinary::with('student')->get();

        // Get the selected student's ID from the request (replace 'student_id' with your actual route parameter)
        $selectedStudentId = $request->route('student_id');

        // Filter records for the selected student
        $selectedStudentRecords = $studentsWithDisciplinaryRecords->where('student_id', $selectedStudentId);

        return view('pages.staff-auth.reports.rpt-disciplinary.rpt-disciplinary-page', compact('students', 'selectedStudentId', 'selectedStudentRecords', 'studentsWithDisciplinaryRecords'));
    }

    // Student Information Controllers

    public function indexStudent()
    {
        $students = Student::all();
        return view('pages.staff-auth.students.student-info-page', compact('students'));
    }

    public function getStudentInfo($id)
    {
        $student = Student::find($id);
        return view('pages.staff-auth.students.student-info-page', compact('student'));
    }
}
