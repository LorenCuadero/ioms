<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendClosingOfAccountsEmail;
use App\Mail\SendCustomizedEmail;
use App\Mail\SendEmailPayable;
use App\Models\Counterpart;
use App\Models\GraduationFee;
use App\Models\MedicalShare;
use App\Models\PersonalCashAdvance;
use App\Models\Student;
use App\Services\UploadFileService;
use dateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Services\StoreLogsService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexAdmin()
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }
        // Calculate the total amount due from each table
        $medicalShareTotal = MedicalShare::sum('total_cost') * 0.15;
        $counterpartTotal = Counterpart::sum('amount_due');
        $graduationFeeTotal = GraduationFee::sum('amount_due');
        $personalCashAdvanceTotal = PersonalCashAdvance::sum('amount_due');

        // Receivables
        $medicalShareReceivable = $medicalShareTotal - MedicalShare::sum('amount_paid');
        $counterpartReceivable = $counterpartTotal - Counterpart::sum('amount_paid');
        $graduationFeeReceivable = $graduationFeeTotal - GraduationFee::sum('amount_paid');
        $personalCashAdvanceReceivable = $personalCashAdvanceTotal - PersonalCashAdvance::sum('amount_paid');
        $receivableTotal = $medicalShareReceivable + $counterpartReceivable + $graduationFeeReceivable + $personalCashAdvanceReceivable;

        // Received
        $medicalShareReceived = MedicalShare::sum('amount_paid');
        $counterpartReceived = Counterpart::sum('amount_paid');
        $graduationFeeReceived = GraduationFee::sum('amount_paid');
        $personalCashAdvanceReceived = PersonalCashAdvance::sum('amount_paid');
        $receivedTotal = $medicalShareReceived + $counterpartReceived + $graduationFeeReceived + $personalCashAdvanceReceived;

        // Count the total number of students
        $users = User::where('is_deleted', false)->get();
        $studentIds = $users->pluck('id');
        $totalNumberOfStudents = Student::whereIn('user_id', $studentIds)->count();
        // $totalNumberOfStudents = Student::count();

        // Batch Years
        $batchYears = Student::distinct('batch_year')->pluck('batch_year');

        $totalStudentsByBatchYear = [];
        foreach ($batchYears as $year) {
            $totalStudentsByBatchYear[$year] = Student::where('batch_year', $year)->count();
        }

        // Calculate the total number of students that are paid in:
        // Counterparts
        $counterpartPaidStudentsCount = Counterpart::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('counterparts')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) >= SUM(amount_due)');
        })->distinct()->count('student_id');

        // MedicalShare
        $medicalSharePaidStudentsCount = MedicalShare::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('medical_shares')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) >= SUM(total_cost * 0.15)');
        })->distinct()->count('student_id');

        // Personal Cash Advance
        $personalCashAdvancePaidStudentsCount = PersonalCashAdvance::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('personal_cash_advances')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) >= SUM(amount_due)');
        })->distinct()->count('student_id');

        // Graduation Fee
        $graduationFeePaidStudentsCount = GraduationFee::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('graduation_fees')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) >= SUM(amount_due)');
        })->distinct()->count('student_id');

        // Counterpart Not Fully Paid Students
        $counterpartNotFullyPaidStudentsCount = Counterpart::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('counterparts')
                ->whereColumn('amount_paid', '<', 'amount_due')
                ->groupBy('student_id');
        })->distinct()->count('student_id');

        // MedicalShare Not Fully Paid Students
        $medicalShareNotFullyPaidStudentsCount = MedicalShare::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('medical_shares')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) < SUM(total_cost * 0.15)');
        })->distinct()->count('student_id');

        // Personal Cash Advance Not Fully Paid Students
        $personalCashAdvanceNotFullyPaidStudentsCount = PersonalCashAdvance::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('personal_cash_advances')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) < SUM(amount_due)');
        })->distinct()->count('student_id');

        // Graduation Fee Not Fully Paid Students
        $graduationFeeNotFullyPaidStudentsCount = GraduationFee::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('graduation_fees')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) < SUM(amount_due)');
        })->distinct()->count('student_id');

        // Counterpart Unpaid Students
        $counterpartUnpaidStudentsCount = Counterpart::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('counterparts')
                ->where('amount_paid', '=', 0)
                ->groupBy('student_id');
        })->distinct()->count('student_id');

        // MedicalShare Unpaid Students
        $medicalShareUnpaidStudentsCount = MedicalShare::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('medical_shares')
                ->where('amount_paid', '=', 0)
                ->groupBy('student_id');
        })->distinct()->count('student_id');

        // Personal Cash Advance Unpaid Students
        $personalCashAdvancetUnpaidStudentsCount = PersonalCashAdvance::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('personal_cash_advances')
                ->where('amount_paid', '=', 0)
                ->groupBy('student_id');
        })->distinct()->count('student_id');

        // Graduation Fee Unpaid Students
        $graduationFeeUnpaidStudentsCount = GraduationFee::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('graduation_fees')
                ->where('amount_paid', '=', 0)
                ->groupBy('student_id');
        })->distinct()->count('student_id');

        // Count the number of students who have paid for each category
        $medicalSharePaidCount = MedicalShare::where('amount_paid', '>', 0)->count();
        $counterpartPaidCount = Counterpart::where('amount_paid', '>', 0)->count();
        $graduationFeePaidCount = GraduationFee::where('amount_paid', '>', 0)->count();
        $personalCashAdvancePaidCount = PersonalCashAdvance::where('amount_paid', '>', 0)->count();

        // Calculate the percentage for each category
        $medicalSharePercentage = ($medicalSharePaidCount / $totalNumberOfStudents) * 100;
        $counterpartPercentage = ($counterpartPaidCount / $totalNumberOfStudents) * 100;
        $graduationFeePercentage = ($graduationFeePaidCount / $totalNumberOfStudents) * 100;
        $personalCashAdvancePercentage = ($personalCashAdvancePaidCount / $totalNumberOfStudents) * 100;

        // Calculate the percentage for each category per month
        // January
        $year = intval(date('Y'));

        $medicalSharePaidCountJanuary = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 1)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidJanuary = $medicalSharePaidCountJanuary->count();
        $medicalSharePaidCountJanuary = $uniqueStudentsMedicalSharePaidJanuary / $totalNumberOfStudents * 100;

        $studentsWithCounterpartPaidJanuary = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 1)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidJanuary = $studentsWithCounterpartPaidJanuary->count();
        $counterpartPaidCountJanuary = round(($uniqueStudentsCounterpartPaidJanuary / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountJanuary = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 1)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidJanuary = $graduationFeePaidCountJanuary->count();
        $graduationFeePaidCountJanuary = round($uniqueStudentsGraduationFeePaidJanuary / $totalNumberOfStudents * 100);

        $personalCashAdvancePaidCountJanuary = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 1)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidJanuary = $personalCashAdvancePaidCountJanuary->count();
        $personalCashAdvancePaidCountJanuary = round($uniqueStudentsPersonalCashAdvancePaidJanuary / $totalNumberOfStudents * 100);

        // February
        $medicalSharePaidCountFebruary = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 2)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidFebruary = $medicalSharePaidCountFebruary->count();
        $medicalSharePaidCountFebruary = round($uniqueStudentsMedicalSharePaidFebruary / $totalNumberOfStudents * 100);

        $studentsWithCounterpartPaidFebruary = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 2)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidFebruary = $studentsWithCounterpartPaidFebruary->count();
        $counterpartPaidCountFebruary = round(($uniqueStudentsCounterpartPaidFebruary / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountFebruary = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 2)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidJanuary = $graduationFeePaidCountFebruary->count();
        $graduationFeePaidCountFebruary = round($uniqueStudentsGraduationFeePaidJanuary / $totalNumberOfStudents * 100);

        $personalCashAdvancePaidCountFebruary = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 2)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidFebruary = $personalCashAdvancePaidCountFebruary->count();
        $personalCashAdvancePaidCountFebruary = round($uniqueStudentsPersonalCashAdvancePaidFebruary / $totalNumberOfStudents * 100);

        // March
        $medicalSharePaidCountMarch = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 3)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidMarch = $medicalSharePaidCountMarch->count();
        $medicalSharePaidCountMarch = round(($uniqueStudentsMedicalSharePaidMarch / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidMarch = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 3)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidMarch = $studentsWithCounterpartPaidMarch->count();
        $counterpartPaidCountMarch = round(($uniqueStudentsCounterpartPaidMarch / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountMarch = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 3)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidMarch = $graduationFeePaidCountMarch->count();
        $graduationFeePaidCountMarch = round(($uniqueStudentsGraduationFeePaidMarch / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountMarch = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 3)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidMarch = $personalCashAdvancePaidCountMarch->count();
        $personalCashAdvancePaidCountMarch = round(($uniqueStudentsPersonalCashAdvancePaidMarch / $totalNumberOfStudents) * 100);

        // April
        $medicalSharePaidCountApril = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 4)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidApril = $medicalSharePaidCountApril->count();
        $medicalSharePaidCountApril = round(($uniqueStudentsMedicalSharePaidApril / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidApril = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 4)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidApril = $studentsWithCounterpartPaidApril->count();
        $counterpartPaidCountApril = round(($uniqueStudentsCounterpartPaidApril / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountApril = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 4)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidApril = $graduationFeePaidCountApril->count();
        $graduationFeePaidCountApril = round(($uniqueStudentsGraduationFeePaidApril / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountApril = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 4)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidApril = $personalCashAdvancePaidCountApril->count();
        $personalCashAdvancePaidCountApril = round(($uniqueStudentsPersonalCashAdvancePaidApril / $totalNumberOfStudents) * 100);

        // May
        $medicalSharePaidCountMay = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 5)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidMay = $medicalSharePaidCountMay->count();
        $medicalSharePaidCountMay = round(($uniqueStudentsMedicalSharePaidMay / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidMay = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 5)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidMay = $studentsWithCounterpartPaidMay->count();
        $counterpartPaidCountMay = round(($uniqueStudentsCounterpartPaidMay / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountMay = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 5)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidMay = $graduationFeePaidCountMay->count();
        $graduationFeePaidCountMay = round(($uniqueStudentsGraduationFeePaidMay / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountMay = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 5)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidMay = $personalCashAdvancePaidCountMay->count();
        $personalCashAdvancePaidCountMay = round(($uniqueStudentsPersonalCashAdvancePaidMay / $totalNumberOfStudents) * 100);

        // June
        $medicalSharePaidCountJune = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 6)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidJune = $medicalSharePaidCountJune->count();
        $medicalSharePaidCountJune = round(($uniqueStudentsMedicalSharePaidJune / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidJune = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 6)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidJune = $studentsWithCounterpartPaidJune->count();
        $counterpartPaidCountJune = round(($uniqueStudentsCounterpartPaidJune / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountJune = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 6)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidJune = $graduationFeePaidCountJune->count();
        $graduationFeePaidCountJune = round(($uniqueStudentsGraduationFeePaidJune / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountJune = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 6)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidJune = $personalCashAdvancePaidCountJune->count();
        $personalCashAdvancePaidCountJune = round(($uniqueStudentsPersonalCashAdvancePaidJune / $totalNumberOfStudents) * 100);

        // July
        $medicalSharePaidCountJuly = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 7)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidJuly = $medicalSharePaidCountJuly->count();
        $medicalSharePaidCountJuly = round(($uniqueStudentsMedicalSharePaidJuly / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidJuly = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 7)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidJuly = $studentsWithCounterpartPaidJuly->count();
        $counterpartPaidCountJuly = round(($uniqueStudentsCounterpartPaidJuly / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountJuly = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 7)
            ->select('student_id')
            ->whereYear('date', $year)

            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidJuly = $graduationFeePaidCountJuly->count();
        $graduationFeePaidCountJuly = round(($uniqueStudentsGraduationFeePaidJuly / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountJuly = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 7)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidJuly = $personalCashAdvancePaidCountJuly->count();
        $personalCashAdvancePaidCountJuly = round(($uniqueStudentsPersonalCashAdvancePaidJuly / $totalNumberOfStudents) * 100);

        // August
        $medicalSharePaidCountAugust = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 8)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidAugust = $medicalSharePaidCountAugust->count();
        $medicalSharePaidCountAugust = round(($uniqueStudentsMedicalSharePaidAugust / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidAugust = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 8)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidAugust = $studentsWithCounterpartPaidAugust->count();
        $counterpartPaidCountAugust = round(($uniqueStudentsCounterpartPaidAugust / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountAugust = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 8)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidAugust = $graduationFeePaidCountAugust->count();
        $graduationFeePaidCountAugust = round(($uniqueStudentsGraduationFeePaidAugust / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountAugust = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 8)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidAugust = $personalCashAdvancePaidCountAugust->count();
        $personalCashAdvancePaidCountAugust = round(($uniqueStudentsPersonalCashAdvancePaidAugust / $totalNumberOfStudents) * 100);

        // September
        $medicalSharePaidCountSeptember = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 9)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidSeptember = $medicalSharePaidCountSeptember->count();
        $medicalSharePaidCountSeptember = round(($uniqueStudentsMedicalSharePaidSeptember / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidSeptember = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 9)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidSeptember = $studentsWithCounterpartPaidSeptember->count();
        $counterpartPaidCountSeptember = round(($uniqueStudentsCounterpartPaidSeptember / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountSeptember = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 9)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidSeptember = $graduationFeePaidCountSeptember->count();
        $graduationFeePaidCountSeptember = round(($uniqueStudentsGraduationFeePaidSeptember / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountSeptember = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 9)
            ->whereYear('date', $year)

            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidSeptember = $personalCashAdvancePaidCountSeptember->count();
        $personalCashAdvancePaidCountSeptember = round(($uniqueStudentsPersonalCashAdvancePaidSeptember / $totalNumberOfStudents) * 100);

        // October
        $medicalSharePaidCountOctober = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 10)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidOctober = $medicalSharePaidCountOctober->count();
        $medicalSharePaidCountOctober = round(($uniqueStudentsMedicalSharePaidOctober / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidOctober = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 10)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidOctober = $studentsWithCounterpartPaidOctober->count();
        $counterpartPaidCountOctober = round(($uniqueStudentsCounterpartPaidOctober / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountOctober = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 10)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidOctober = $graduationFeePaidCountOctober->count();
        $graduationFeePaidCountOctober = round(($uniqueStudentsGraduationFeePaidOctober / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountOctober = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 10)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidOctober = $personalCashAdvancePaidCountOctober->count();
        $personalCashAdvancePaidCountOctober = round(($uniqueStudentsPersonalCashAdvancePaidOctober / $totalNumberOfStudents) * 100);

        // November
        $medicalSharePaidCountNovember = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 11)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidNovember = $medicalSharePaidCountNovember->count();
        $medicalSharePaidCountNovember = round(($uniqueStudentsMedicalSharePaidNovember / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidNovember = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 11)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidNovember = $studentsWithCounterpartPaidNovember->count();
        $counterpartPaidCountNovember = round(($uniqueStudentsCounterpartPaidNovember / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountNovember = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 11)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidNovember = $graduationFeePaidCountNovember->count();
        $graduationFeePaidCountNovember = round(($uniqueStudentsGraduationFeePaidNovember / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountNovember = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 11)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidNovember = $personalCashAdvancePaidCountNovember->count();
        $personalCashAdvancePaidCountNovember = round(($uniqueStudentsPersonalCashAdvancePaidNovember / $totalNumberOfStudents) * 100);

        // December
        $medicalSharePaidCountDecember = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 12)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidDecember = $medicalSharePaidCountDecember->count();
        $medicalSharePaidCountDecember = round(($uniqueStudentsMedicalSharePaidDecember / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidDecember = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 12)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidDecember = $studentsWithCounterpartPaidDecember->count();
        $counterpartPaidCountDecember = round(($uniqueStudentsCounterpartPaidDecember / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountDecember = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 12)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidDecember = $graduationFeePaidCountDecember->count();
        $graduationFeePaidCountDecember = round(($uniqueStudentsGraduationFeePaidDecember / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountDecember = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 12)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidDecember = $personalCashAdvancePaidCountDecember->count();
        $personalCashAdvancePaidCountDecember = round(($uniqueStudentsPersonalCashAdvancePaidDecember / $totalNumberOfStudents) * 100);

        // Pass the totals and percentages to the view
        $data['header_title'] = "Dashboard |";
        return view('pages.admin-auth.dashboard.index', [
            'medicalShareTotal' => $medicalShareTotal,
            'counterpartTotal' => $counterpartTotal,
            'graduationFeeTotal' => $graduationFeeTotal,
            'personalCashAdvanceTotal' => $personalCashAdvanceTotal,
            'medicalSharePercentage' => $medicalSharePercentage,
            'counterpartPercentage' => $counterpartPercentage,
            'graduationFeePercentage' => $graduationFeePercentage,
            'personalCashAdvancePercentage' => $personalCashAdvancePercentage,
            'receivableTotal' => $receivableTotal,
            'receivedTotal' => $receivedTotal,
            'totalNumberOfStudents' => $totalNumberOfStudents,
            // Medical
            'medicalSharePaidCountJanuary' => $medicalSharePaidCountJanuary,
            'medicalSharePaidCountFebruary' => $medicalSharePaidCountFebruary,
            'medicalSharePaidCountMarch' => $medicalSharePaidCountMarch,
            'medicalSharePaidCountApril' => $medicalSharePaidCountApril,
            'medicalSharePaidCountMay' => $medicalSharePaidCountMay,
            'medicalSharePaidCountJune' => $medicalSharePaidCountJune,
            'medicalSharePaidCountJuly' => $medicalSharePaidCountJuly,
            'medicalSharePaidCountAugust' => $medicalSharePaidCountAugust,
            'medicalSharePaidCountSeptember' => $medicalSharePaidCountSeptember,
            'medicalSharePaidCountOctober' => $medicalSharePaidCountOctober,
            'medicalSharePaidCountNovember' => $medicalSharePaidCountNovember,
            'medicalSharePaidCountDecember' => $medicalSharePaidCountDecember,
            // Counterpart
            'counterpartPaidCountJanuary' => $counterpartPaidCountJanuary,
            'counterpartPaidCountFebruary' => $counterpartPaidCountFebruary,
            'counterpartPaidCountMarch' => $counterpartPaidCountMarch,
            'counterpartPaidCountApril' => $counterpartPaidCountApril,
            'counterpartPaidCountMay' => $counterpartPaidCountMay,
            'counterpartPaidCountJune' => $counterpartPaidCountJune,
            'counterpartPaidCountJuly' => $counterpartPaidCountJuly,
            'counterpartPaidCountAugust' => $counterpartPaidCountAugust,
            'counterpartPaidCountSeptember' => $counterpartPaidCountSeptember,
            'counterpartPaidCountOctober' => $counterpartPaidCountOctober,
            'counterpartPaidCountNovember' => $counterpartPaidCountNovember,
            'counterpartPaidCountDecember' => $counterpartPaidCountDecember,
            // Graduation Fee
            'graduationFeePaidCountJanuary' => $graduationFeePaidCountJanuary,
            'graduationFeePaidCountFebruary' => $graduationFeePaidCountFebruary,
            'graduationFeePaidCountMarch' => $graduationFeePaidCountMarch,
            'graduationFeePaidCountApril' => $graduationFeePaidCountApril,
            'graduationFeePaidCountMay' => $graduationFeePaidCountMay,
            'graduationFeePaidCountJune' => $graduationFeePaidCountJune,
            'graduationFeePaidCountJuly' => $graduationFeePaidCountJuly,
            'graduationFeePaidCountAugust' => $graduationFeePaidCountAugust,
            'graduationFeePaidCountSeptember' => $graduationFeePaidCountSeptember,
            'graduationFeePaidCountOctober' => $graduationFeePaidCountOctober,
            'graduationFeePaidCountNovember' => $graduationFeePaidCountNovember,
            'graduationFeePaidCountDecember' => $graduationFeePaidCountDecember,
            // Personal Cash Advance
            'personalCashAdvancePaidCountJanuary' => $personalCashAdvancePaidCountJanuary,
            'personalCashAdvancePaidCountFebruary' => $personalCashAdvancePaidCountFebruary,
            'personalCashAdvancePaidCountMarch' => $personalCashAdvancePaidCountMarch,
            'personalCashAdvancePaidCountApril' => $personalCashAdvancePaidCountApril,
            'personalCashAdvancePaidCountMay' => $personalCashAdvancePaidCountMay,
            'personalCashAdvancePaidCountJune' => $personalCashAdvancePaidCountJune,
            'personalCashAdvancePaidCountJuly' => $personalCashAdvancePaidCountJuly,
            'personalCashAdvancePaidCountAugust' => $personalCashAdvancePaidCountAugust,
            'personalCashAdvancePaidCountSeptember' => $personalCashAdvancePaidCountSeptember,
            'personalCashAdvancePaidCountOctober' => $personalCashAdvancePaidCountOctober,
            'personalCashAdvancePaidCountNovember' => $personalCashAdvancePaidCountNovember,
            'personalCashAdvancePaidCountDecember' => $personalCashAdvancePaidCountDecember,
            // Analytics
            'totalNumberOfStudents' => $totalNumberOfStudents,
            'counterpartPaidStudentsCount' => $counterpartPaidStudentsCount,
            'medicalSharePaidStudentsCount' => $medicalSharePaidStudentsCount,
            'personalCashAdvancePaidStudentsCount' => $personalCashAdvancePaidStudentsCount,
            'personalCashAdvanceNotFullyPaidStudentsCount' => $personalCashAdvanceNotFullyPaidStudentsCount,
            'graduationFeePaidStudentsCount' => $graduationFeePaidStudentsCount,
            'graduationFeeNotFullyPaidStudentsCount' => $graduationFeeNotFullyPaidStudentsCount,
            'counterpartUnpaidStudentsCount' => $counterpartUnpaidStudentsCount,
            'medicalShareUnpaidStudentsCount' => $medicalShareUnpaidStudentsCount,
            'personalCashAdvanceUnpaidStudentsCount' => $personalCashAdvancetUnpaidStudentsCount,
            'graduationFeeUnpaidStudentsCount' => $graduationFeeUnpaidStudentsCount,
            'medicalShareNotFullyPaidStudentsCount' => $medicalShareNotFullyPaidStudentsCount,
            'counterpartNotFullyPaidStudentsCount' => $counterpartNotFullyPaidStudentsCount,
            'batchYears' => $batchYears,
            'totalStudentsByBatchYear' => $totalStudentsByBatchYear,
        ], $data);
    }


    public function perYearViewMonthlyAcquisition(Request $request)
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $year = $request->input('year');
        $totalNumberOfStudents = Student::count();

        // Calculate the percentage for each category per month
        // January
        $medicalSharePaidCountJanuary = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 1)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidJanuary = $medicalSharePaidCountJanuary->count();
        $medicalSharePaidCountJanuary = round($uniqueStudentsMedicalSharePaidJanuary / $totalNumberOfStudents * 100);

        $studentsWithCounterpartPaidJanuary = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 1)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidJanuary = $studentsWithCounterpartPaidJanuary->count();
        $counterpartPaidCountJanuary = round(($uniqueStudentsCounterpartPaidJanuary / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountJanuary = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 1)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidJanuary = $graduationFeePaidCountJanuary->count();
        $graduationFeePaidCountJanuary = round($uniqueStudentsGraduationFeePaidJanuary / $totalNumberOfStudents * 100);

        $personalCashAdvancePaidCountJanuary = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 1)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidJanuary = $personalCashAdvancePaidCountJanuary->count();
        $personalCashAdvancePaidCountJanuary = round($uniqueStudentsPersonalCashAdvancePaidJanuary / $totalNumberOfStudents * 100);

        // February
        $medicalSharePaidCountFebruary = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 2)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidFebruary = $medicalSharePaidCountFebruary->count();
        $medicalSharePaidCountFebruary = round($uniqueStudentsMedicalSharePaidFebruary / $totalNumberOfStudents * 100);

        $studentsWithCounterpartPaidFebruary = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 2)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidFebruary = $studentsWithCounterpartPaidFebruary->count();
        $counterpartPaidCountFebruary = round(($uniqueStudentsCounterpartPaidFebruary / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountFebruary = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 2)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidJanuary = $graduationFeePaidCountFebruary->count();
        $graduationFeePaidCountFebruary = round($uniqueStudentsGraduationFeePaidJanuary / $totalNumberOfStudents * 100);

        $personalCashAdvancePaidCountFebruary = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 2)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidFebruary = $personalCashAdvancePaidCountFebruary->count();
        $personalCashAdvancePaidCountFebruary = round($uniqueStudentsPersonalCashAdvancePaidFebruary / $totalNumberOfStudents * 100);

        // March
        $medicalSharePaidCountMarch = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 3)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidMarch = $medicalSharePaidCountMarch->count();
        $medicalSharePaidCountMarch = round(($uniqueStudentsMedicalSharePaidMarch / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidMarch = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 3)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidMarch = $studentsWithCounterpartPaidMarch->count();
        $counterpartPaidCountMarch = round(($uniqueStudentsCounterpartPaidMarch / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountMarch = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 3)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidMarch = $graduationFeePaidCountMarch->count();
        $graduationFeePaidCountMarch = round(($uniqueStudentsGraduationFeePaidMarch / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountMarch = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 3)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidMarch = $personalCashAdvancePaidCountMarch->count();
        $personalCashAdvancePaidCountMarch = round(($uniqueStudentsPersonalCashAdvancePaidMarch / $totalNumberOfStudents) * 100);

        // April
        $medicalSharePaidCountApril = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 4)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidApril = $medicalSharePaidCountApril->count();
        $medicalSharePaidCountApril = round(($uniqueStudentsMedicalSharePaidApril / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidApril = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 4)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidApril = $studentsWithCounterpartPaidApril->count();
        $counterpartPaidCountApril = round(($uniqueStudentsCounterpartPaidApril / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountApril = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 4)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidApril = $graduationFeePaidCountApril->count();
        $graduationFeePaidCountApril = round(($uniqueStudentsGraduationFeePaidApril / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountApril = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 4)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidApril = $personalCashAdvancePaidCountApril->count();
        $personalCashAdvancePaidCountApril = round(($uniqueStudentsPersonalCashAdvancePaidApril / $totalNumberOfStudents) * 100);

        // May
        $medicalSharePaidCountMay = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 5)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidMay = $medicalSharePaidCountMay->count();
        $medicalSharePaidCountMay = round(($uniqueStudentsMedicalSharePaidMay / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidMay = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 5)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidMay = $studentsWithCounterpartPaidMay->count();
        $counterpartPaidCountMay = round(($uniqueStudentsCounterpartPaidMay / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountMay = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 5)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidMay = $graduationFeePaidCountMay->count();
        $graduationFeePaidCountMay = round(($uniqueStudentsGraduationFeePaidMay / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountMay = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 5)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidMay = $personalCashAdvancePaidCountMay->count();
        $personalCashAdvancePaidCountMay = round(($uniqueStudentsPersonalCashAdvancePaidMay / $totalNumberOfStudents) * 100);

        // June
        $medicalSharePaidCountJune = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 6)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidJune = $medicalSharePaidCountJune->count();
        $medicalSharePaidCountJune = round(($uniqueStudentsMedicalSharePaidJune / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidJune = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 6)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidJune = $studentsWithCounterpartPaidJune->count();
        $counterpartPaidCountJune = round(($uniqueStudentsCounterpartPaidJune / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountJune = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 6)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidJune = $graduationFeePaidCountJune->count();
        $graduationFeePaidCountJune = round(($uniqueStudentsGraduationFeePaidJune / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountJune = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 6)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidJune = $personalCashAdvancePaidCountJune->count();
        $personalCashAdvancePaidCountJune = round(($uniqueStudentsPersonalCashAdvancePaidJune / $totalNumberOfStudents) * 100);

        // July
        $medicalSharePaidCountJuly = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 7)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidJuly = $medicalSharePaidCountJuly->count();
        $medicalSharePaidCountJuly = round(($uniqueStudentsMedicalSharePaidJuly / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidJuly = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 7)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidJuly = $studentsWithCounterpartPaidJuly->count();
        $counterpartPaidCountJuly = round(($uniqueStudentsCounterpartPaidJuly / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountJuly = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 7)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidJuly = $graduationFeePaidCountJuly->count();
        $graduationFeePaidCountJuly = round(($uniqueStudentsGraduationFeePaidJuly / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountJuly = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 7)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidJuly = $personalCashAdvancePaidCountJuly->count();
        $personalCashAdvancePaidCountJuly = round(($uniqueStudentsPersonalCashAdvancePaidJuly / $totalNumberOfStudents) * 100);

        // August
        $medicalSharePaidCountAugust = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 8)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidAugust = $medicalSharePaidCountAugust->count();
        $medicalSharePaidCountAugust = round(($uniqueStudentsMedicalSharePaidAugust / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidAugust = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 8)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidAugust = $studentsWithCounterpartPaidAugust->count();
        $counterpartPaidCountAugust = round(($uniqueStudentsCounterpartPaidAugust / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountAugust = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 8)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidAugust = $graduationFeePaidCountAugust->count();
        $graduationFeePaidCountAugust = round(($uniqueStudentsGraduationFeePaidAugust / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountAugust = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 8)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidAugust = $personalCashAdvancePaidCountAugust->count();
        $personalCashAdvancePaidCountAugust = round(($uniqueStudentsPersonalCashAdvancePaidAugust / $totalNumberOfStudents) * 100);

        // September
        $medicalSharePaidCountSeptember = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 9)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidSeptember = $medicalSharePaidCountSeptember->count();
        $medicalSharePaidCountSeptember = round(($uniqueStudentsMedicalSharePaidSeptember / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidSeptember = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 9)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidSeptember = $studentsWithCounterpartPaidSeptember->count();
        $counterpartPaidCountSeptember = round(($uniqueStudentsCounterpartPaidSeptember / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountSeptember = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 9)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidSeptember = $graduationFeePaidCountSeptember->count();
        $graduationFeePaidCountSeptember = round(($uniqueStudentsGraduationFeePaidSeptember / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountSeptember = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 9)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidSeptember = $personalCashAdvancePaidCountSeptember->count();
        $personalCashAdvancePaidCountSeptember = round(($uniqueStudentsPersonalCashAdvancePaidSeptember / $totalNumberOfStudents) * 100);

        // October
        $medicalSharePaidCountOctober = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 10)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidOctober = $medicalSharePaidCountOctober->count();
        $medicalSharePaidCountOctober = round(($uniqueStudentsMedicalSharePaidOctober / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidOctober = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 10)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidOctober = $studentsWithCounterpartPaidOctober->count();
        $counterpartPaidCountOctober = round(($uniqueStudentsCounterpartPaidOctober / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountOctober = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 10)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidOctober = $graduationFeePaidCountOctober->count();
        $graduationFeePaidCountOctober = round(($uniqueStudentsGraduationFeePaidOctober / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountOctober = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 10)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidOctober = $personalCashAdvancePaidCountOctober->count();
        $personalCashAdvancePaidCountOctober = round(($uniqueStudentsPersonalCashAdvancePaidOctober / $totalNumberOfStudents) * 100);

        // November
        $medicalSharePaidCountNovember = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 11)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidNovember = $medicalSharePaidCountNovember->count();
        $medicalSharePaidCountNovember = round(($uniqueStudentsMedicalSharePaidNovember / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidNovember = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 11)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidNovember = $studentsWithCounterpartPaidNovember->count();
        $counterpartPaidCountNovember = round(($uniqueStudentsCounterpartPaidNovember / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountNovember = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 11)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidNovember = $graduationFeePaidCountNovember->count();
        $graduationFeePaidCountNovember = round(($uniqueStudentsGraduationFeePaidNovember / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountNovember = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 11)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidNovember = $personalCashAdvancePaidCountNovember->count();
        $personalCashAdvancePaidCountNovember = round(($uniqueStudentsPersonalCashAdvancePaidNovember / $totalNumberOfStudents) * 100);

        // December
        $medicalSharePaidCountDecember = MedicalShare::where('amount_paid', '>', 0)
            ->whereMonth('date', 12)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsMedicalSharePaidDecember = $medicalSharePaidCountDecember->count();
        $medicalSharePaidCountDecember = round(($uniqueStudentsMedicalSharePaidDecember / $totalNumberOfStudents) * 100);

        $studentsWithCounterpartPaidDecember = Counterpart::where('amount_paid', '>', 0)
            ->whereMonth('date', 12)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsCounterpartPaidDecember = $studentsWithCounterpartPaidDecember->count();
        $counterpartPaidCountDecember = round(($uniqueStudentsCounterpartPaidDecember / $totalNumberOfStudents) * 100);

        $graduationFeePaidCountDecember = GraduationFee::where('amount_paid', '>', 0)
            ->whereMonth('date', 12)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsGraduationFeePaidDecember = $graduationFeePaidCountDecember->count();
        $graduationFeePaidCountDecember = round(($uniqueStudentsGraduationFeePaidDecember / $totalNumberOfStudents) * 100);

        $personalCashAdvancePaidCountDecember = PersonalCashAdvance::where('amount_paid', '>', 0)
            ->whereMonth('date', 12)
            ->whereYear('date', $year)
            ->select('student_id')
            ->distinct()
            ->get();
        $uniqueStudentsPersonalCashAdvancePaidDecember = $personalCashAdvancePaidCountDecember->count();
        $personalCashAdvancePaidCountDecember = round(($uniqueStudentsPersonalCashAdvancePaidDecember / $totalNumberOfStudents) * 100);

        return response()->json([
            // Medical
            'medicalSharePaidCountJanuary' => $medicalSharePaidCountJanuary,
            'medicalSharePaidCountFebruary' => $medicalSharePaidCountFebruary,
            'medicalSharePaidCountMarch' => $medicalSharePaidCountMarch,
            'medicalSharePaidCountApril' => $medicalSharePaidCountApril,
            'medicalSharePaidCountMay' => $medicalSharePaidCountMay,
            'medicalSharePaidCountJune' => $medicalSharePaidCountJune,
            'medicalSharePaidCountJuly' => $medicalSharePaidCountJuly,
            'medicalSharePaidCountAugust' => $medicalSharePaidCountAugust,
            'medicalSharePaidCountSeptember' => $medicalSharePaidCountSeptember,
            'medicalSharePaidCountOctober' => $medicalSharePaidCountOctober,
            'medicalSharePaidCountNovember' => $medicalSharePaidCountNovember,
            'medicalSharePaidCountDecember' => $medicalSharePaidCountDecember,
            // Counterpart
            'counterpartPaidCountJanuary' => $counterpartPaidCountJanuary,
            'counterpartPaidCountFebruary' => $counterpartPaidCountFebruary,
            'counterpartPaidCountMarch' => $counterpartPaidCountMarch,
            'counterpartPaidCountApril' => $counterpartPaidCountApril,
            'counterpartPaidCountMay' => $counterpartPaidCountMay,
            'counterpartPaidCountJune' => $counterpartPaidCountJune,
            'counterpartPaidCountJuly' => $counterpartPaidCountJuly,
            'counterpartPaidCountAugust' => $counterpartPaidCountAugust,
            'counterpartPaidCountSeptember' => $counterpartPaidCountSeptember,
            'counterpartPaidCountOctober' => $counterpartPaidCountOctober,
            'counterpartPaidCountNovember' => $counterpartPaidCountNovember,
            'counterpartPaidCountDecember' => $counterpartPaidCountDecember,
            // Graduation Fee
            'graduationFeePaidCountJanuary' => $graduationFeePaidCountJanuary,
            'graduationFeePaidCountFebruary' => $graduationFeePaidCountFebruary,
            'graduationFeePaidCountMarch' => $graduationFeePaidCountMarch,
            'graduationFeePaidCountApril' => $graduationFeePaidCountApril,
            'graduationFeePaidCountMay' => $graduationFeePaidCountMay,
            'graduationFeePaidCountJune' => $graduationFeePaidCountJune,
            'graduationFeePaidCountJuly' => $graduationFeePaidCountJuly,
            'graduationFeePaidCountAugust' => $graduationFeePaidCountAugust,
            'graduationFeePaidCountSeptember' => $graduationFeePaidCountSeptember,
            'graduationFeePaidCountOctober' => $graduationFeePaidCountOctober,
            'graduationFeePaidCountNovember' => $graduationFeePaidCountNovember,
            'graduationFeePaidCountDecember' => $graduationFeePaidCountDecember,
            // Personal Cash Advance
            'personalCashAdvancePaidCountJanuary' => $personalCashAdvancePaidCountJanuary,
            'personalCashAdvancePaidCountFebruary' => $personalCashAdvancePaidCountFebruary,
            'personalCashAdvancePaidCountMarch' => $personalCashAdvancePaidCountMarch,
            'personalCashAdvancePaidCountApril' => $personalCashAdvancePaidCountApril,
            'personalCashAdvancePaidCountMay' => $personalCashAdvancePaidCountMay,
            'personalCashAdvancePaidCountJune' => $personalCashAdvancePaidCountJune,
            'personalCashAdvancePaidCountJuly' => $personalCashAdvancePaidCountJuly,
            'personalCashAdvancePaidCountAugust' => $personalCashAdvancePaidCountAugust,
            'personalCashAdvancePaidCountSeptember' => $personalCashAdvancePaidCountSeptember,
            'personalCashAdvancePaidCountOctober' => $personalCashAdvancePaidCountOctober,
            'personalCashAdvancePaidCountNovember' => $personalCashAdvancePaidCountNovember,
            'personalCashAdvancePaidCountDecember' => $personalCashAdvancePaidCountDecember,
        ]);
    }

    public function getTotals(Request $request)
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You are not authorized to access this page');
        }
        $batchYear = $request->input('batch_year');

        // Counterpart
        $totalPaidCounterpart = Counterpart::select('student_id')
            ->selectRaw('SUM(amount_due) - SUM(amount_paid) as balance')
            ->join('students', 'counterparts.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('counterparts.student_id')
            ->havingRaw('SUM(amount_paid) >= SUM(amount_due)')
            ->count();

        $totalNotFullyPaidCounterpart = Counterpart::select('student_id')
            ->selectRaw('SUM(amount_due) - SUM(amount_paid) as balance')
            ->join('students', 'counterparts.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('counterparts.student_id')
            ->havingRaw('SUM(amount_paid) < SUM(amount_due)')
            ->count();

        $totalUnpaidCounterpart = Counterpart::select('student_id')
            ->selectRaw('SUM(amount_due) - SUM(amount_paid) as balance')
            ->join('students', 'counterparts.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('counterparts.student_id')
            ->havingRaw('SUM(amount_paid) = 0')
            ->count();

        // Medical Share
        $totalPaidMedicalShare = MedicalShare::select('student_id')
            ->selectRaw('SUM(total_cost) * 0.15 - SUM(amount_paid) as balance')
            ->join('students', 'medical_shares.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('medical_shares.student_id')
            ->havingRaw('SUM(total_cost) * 0.15 >= SUM(amount_paid)')
            ->count();

        $totalNotFullyPaidMedicalShare = MedicalShare::select('student_id')
            ->selectRaw('SUM(total_cost) * 0.15 - SUM(amount_paid) as balance')
            ->join('students', 'medical_shares.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('medical_shares.student_id')
            ->havingRaw('SUM(total_cost) * 0.15 > SUM(amount_paid)')
            ->count();

        $totalUnpaidMedicalShare = MedicalShare::select('student_id')
            ->selectRaw('SUM(total_cost) * 0.15 - SUM(amount_paid) as balance')
            ->join('students', 'medical_shares.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('medical_shares.student_id')
            ->havingRaw('SUM(amount_paid) = 0')
            ->count();

        // Personal Cash Advances
        $totalPaidPersonalCashAdvance = PersonalCashAdvance::select('student_id')
            ->selectRaw('SUM(amount_due) - SUM(amount_paid) as balance')
            ->join('students', 'personal_cash_advances.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('personal_cash_advances.student_id')
            ->havingRaw('SUM(amount_paid) >= SUM(amount_due)')
            ->count();

        $totalNotFullyPaidPersonalCashAdvance = PersonalCashAdvance::select('student_id')
            ->selectRaw('SUM(amount_due) - SUM(amount_paid) as balance')
            ->join('students', 'personal_cash_advances.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('personal_cash_advances.student_id')
            ->havingRaw('SUM(amount_paid) < SUM(amount_due)')
            ->count();

        $totalUnpaidPersonalCashAdvance = PersonalCashAdvance::select('student_id')
            ->selectRaw('SUM(amount_due) - SUM(amount_paid) as balance')
            ->join('students', 'personal_cash_advances.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('personal_cash_advances.student_id')
            ->havingRaw('SUM(amount_paid) = 0')
            ->count();

        // Graduation Fees
        $totalPaidGraduationFees = GraduationFee::select('student_id')
            ->selectRaw('SUM(amount_due) - SUM(amount_paid) as balance')
            ->join('students', 'graduation_fees.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('graduation_fees.student_id')
            ->havingRaw('SUM(amount_paid) >= SUM(amount_due)')
            ->count();

        $totalNotFullyPaidGraduationFees = GraduationFee::select('student_id')
            ->selectRaw('SUM(amount_due) - SUM(amount_paid) as balance')
            ->join('students', 'graduation_fees.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('graduation_fees.student_id')
            ->havingRaw('SUM(amount_paid) < SUM(amount_due)')
            ->count();

        $totalUnpaidGraduationFees = GraduationFee::select('student_id')
            ->selectRaw('SUM(amount_due) - SUM(amount_paid) as balance')
            ->join('students', 'graduation_fees.student_id', '=', 'students.id')
            ->where('students.batch_year', '=', $batchYear)
            ->groupBy('graduation_fees.student_id')
            ->havingRaw('SUM(amount_paid) = 0')
            ->count();

        return response()->json([
            'totalPaidCounterpart' => $totalPaidCounterpart,
            'totalUnpaidCounterpart' => $totalUnpaidCounterpart,
            'totalNotFullyPaidCounterpart' => $totalNotFullyPaidCounterpart,
            'totalPaidMedicalShare' => $totalPaidMedicalShare,
            'totalUnpaidMedicalShare' => $totalUnpaidMedicalShare,
            'totalNotFullyPaidMedicalShare' => $totalNotFullyPaidMedicalShare,
            'totalPaidPersonalCashAdvance' => $totalPaidPersonalCashAdvance,
            'totalUnpaidPersonalCashAdvance' => $totalUnpaidPersonalCashAdvance,
            'totalNotFullyPaidPersonalCashAdvance' => $totalNotFullyPaidPersonalCashAdvance,
            'totalPaidGraduationFees' => $totalPaidGraduationFees,
            'totalNotFullyPaidGraduationFees' => $totalNotFullyPaidGraduationFees,
            'totalUnpaidGraduationFees' => $totalUnpaidGraduationFees,
        ]);
    }

    public function allBatchTotalCount()
    {

        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $counterpartPaidStudentsCount = Counterpart::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('counterparts')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) >= SUM(amount_due)');
        })->distinct()->count('student_id');

        // MedicalShare
        $medicalSharePaidStudentsCount = MedicalShare::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('medical_shares')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) >= SUM(total_cost * 0.15)');
        })->distinct()->count('student_id');

        // Personal Cash Advance
        $personalCashAdvancePaidStudentsCount = PersonalCashAdvance::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('personal_cash_advances')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) >= SUM(amount_due)');
        })->distinct()->count('student_id');

        // Graduation Fee
        $graduationFeePaidStudentsCount = GraduationFee::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('graduation_fees')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) >= SUM(amount_due)');
        })->distinct()->count('student_id');

        // Counterpart Not Fully Paid Students
        $counterpartNotFullyPaidStudentsCount = Counterpart::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('counterparts')
                ->whereColumn('amount_paid', '<', 'amount_due')
                ->groupBy('student_id');
        })->distinct()->count('student_id');

        // MedicalShare Not Fully Paid Students
        $medicalShareNotFullyPaidStudentsCount = MedicalShare::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('medical_shares')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) < SUM(total_cost * 0.15)');
        })->distinct()->count('student_id');

        // Personal Cash Advance Not Fully Paid Students
        $personalCashAdvanceNotFullyPaidStudentsCount = PersonalCashAdvance::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('personal_cash_advances')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) < SUM(amount_due)');
        })->distinct()->count('student_id');

        // Graduation Fee Not Fully Paid Students
        $graduationFeeNotFullyPaidStudentsCount = GraduationFee::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('graduation_fees')
                ->groupBy('student_id')
                ->havingRaw('SUM(amount_paid) < SUM(amount_due)');
        })->distinct()->count('student_id');

        // Counterpart Unpaid Students
        $counterpartUnpaidStudentsCount = Counterpart::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('counterparts')
                ->where('amount_paid', '=', 0)
                ->groupBy('student_id');
        })->distinct()->count('student_id');

        // MedicalShare Unpaid Students
        $medicalShareUnpaidStudentsCount = MedicalShare::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('medical_shares')
                ->where('amount_paid', '=', 0)
                ->groupBy('student_id');
        })->distinct()->count('student_id');

        // Personal Cash Advance Unpaid Students
        $personalCashAdvancetUnpaidStudentsCount = PersonalCashAdvance::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('personal_cash_advances')
                ->where('amount_paid', '=', 0)
                ->groupBy('student_id');
        })->distinct()->count('student_id');

        // Graduation Fee Unpaid Students
        $graduationFeeUnpaidStudentsCount = GraduationFee::whereIn('student_id', function ($query) {
            $query->select('student_id')
                ->from('graduation_fees')
                ->where('amount_paid', '=', 0)
                ->groupBy('student_id');
        })->distinct()->count('student_id');

        return response()->json([
            'counterpartPaidStudentsCount' => $counterpartPaidStudentsCount,
            'medicalSharePaidStudentsCount' => $medicalSharePaidStudentsCount,
            'personalCashAdvancePaidStudentsCount' => $personalCashAdvancePaidStudentsCount,
            'personalCashAdvanceNotFullyPaidStudentsCount' => $personalCashAdvanceNotFullyPaidStudentsCount,
            'graduationFeePaidStudentsCount' => $graduationFeePaidStudentsCount,
            'graduationFeeNotFullyPaidStudentsCount' => $graduationFeeNotFullyPaidStudentsCount,
            'counterpartUnpaidStudentsCount' => $counterpartUnpaidStudentsCount,
            'medicalShareUnpaidStudentsCount' => $medicalShareUnpaidStudentsCount,
            'personalCashAdvanceUnpaidStudentsCount' => $personalCashAdvancetUnpaidStudentsCount,
            'graduationFeeUnpaidStudentsCount' => $graduationFeeUnpaidStudentsCount,
            'medicalShareNotFullyPaidStudentsCount' => $medicalShareNotFullyPaidStudentsCount,
            'counterpartNotFullyPaidStudentsCount' => $counterpartNotFullyPaidStudentsCount,
        ]);
    }

    public function email()
    {
        if (Auth::user()->role != '2') {
            return response()->json([
                'error' => 'You are not authorized to access this page.'
            ]);
        }
        $students = Student::all();

        $batchYears = [];

        foreach ($students as $student) {
            if (!in_array($student->batch_year, $batchYears)) {
                $batchYears[] = $student->batch_year;
            }
        }

        return view('pages.admin-auth.email.soa.index', [
            'students' => $students,
            'batchYears' => $batchYears,
        ]);
    }

    public function sendEmail(Request $request)
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You are not authorized to access this page.');
        }
        if ($request->selectedBatchYear == null) {
            return redirect()->back()->with('error', 'Please select a batch year');
        } else if ($request->month == null) {
            return redirect()->back()->with('error', 'Please select a month');
        } else if ($request->year == null) {
            return redirect()->back()->with('error', 'Please select a year');
        }

        $students = Student::where('batch_year', $request->selectedBatchYear)->get();
        $month = $request->input('month');
        $year = $request->input('year');

        foreach ($students as $student) {
            $student_name = $student->first_name . ' ' . $student->last_name;

            // Retrieve the student's counterpart balance
            if ($student->counterpart) {
                $amountDue = $student->counterpart
                    ->sum('amount_due');

                $amountPaid = $student->counterpart
                    ->sum('amount_paid');

                $counterpartBalance = $amountDue - $amountPaid;
            }

            // Retrieve the student's medical share balance
            if ($student->medicalShare) {
                $totalCost = $student->medicalShare
                    ->sum('total_cost');

                $amountPaid = $student->medicalShare
                    ->sum('amount_paid');

                $medicalShareBalance = ($totalCost * 0.15) - $amountPaid;
            }

            $counterpartBalance = $counterpartBalance ?? 0.00;
            $medicalShareBalance = $medicalShareBalance ?? 0.00;

            $total = $counterpartBalance + $medicalShareBalance;
            Mail::to($student->email)->send(new SendEmailPayable($student_name, $month, $year, $counterpartBalance, $medicalShareBalance, $total));

            // Log the action
            $action = "Sent";
            StoreLogsService::storeLogs(auth()->user()->id, $action, "SOA Email", null, null, $request->selectedBatchYear);
        }

        return redirect()->back()->with('success', 'Statement of Account emails sent successfully');
    }

    public function coa()
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You are not authorized to access this page.');
        }
        $students = Student::all();

        $batchYears = [];

        foreach ($students as $student) {
            if (!in_array($student->batch_year, $batchYears)) {
                $batchYears[] = $student->batch_year;
            }
        }

        return view('pages.admin-auth.email.coa.index', [
            'students' => $students,
            'batchYears' => $batchYears,
        ]);
    }

    public function sendCOA(Request $request)
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You are not authorized to access this page.');
        }

        if ($request->selectedBatchYear == null) {
            return redirect()->back()->with('error', 'Please select a batch year');
        } else if ($request->graduation_date == null) {
            return redirect()->back()->with('error', 'Please select a graduation date');
        }

        $selectedBatchYear = $request->selectedBatchYear;
        $graduation_date_value = $request->graduation_date;
        $datetime = new dateTime($graduation_date_value);
        $graduation_date = $datetime->format('F d, Y');

        $students = Student::where('batch_year', $selectedBatchYear)->get();

        foreach ($students as $student) {
            $student_name = $student->first_name . ' ' . $student->last_name;

            // Calculate the balances for counterpart
            $counterpartBalance = $student->counterpart
                ->sum('amount_due') - $student->counterpart->sum('amount_paid');

            // Calculate the balances for medical share
            $medicalShareBalance = ($student->medicalShare->sum('total_cost') * 0.15) - $student->medicalShare->sum('amount_paid');

            // Calculate the balances for personal share
            $personalShareBalance = $student->personalCashAdvance
                ->sum('amount_due') - $student->personalCashAdvance->sum('amount_paid');

            // Calculate the balances for graduation fee
            $graduationFeeBalance = $student->graduationFee
                ->sum('amount_due') - $student->graduationFee->sum('amount_paid');

            // Send the email with the calculated balances
            Mail::to($student->email)->send(
                new SendClosingOfAccountsEmail(
                    $student_name,
                    $graduation_date,
                    $counterpartBalance,
                    $medicalShareBalance,
                    $personalShareBalance,
                    $graduationFeeBalance
                )
            );

            $action = "Sent";
            StoreLogsService::storeLogs(auth()->user()->id, $action, "COA Email", null, null, $request->selectedBatchYear);
        }

        return redirect()->back()->with('success', 'Closing of Accounts email sent successfully');
    }

    public function customizedEmail()
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You are not authorized to access this page.');
        }

        $students = Student::all();

        $batchYears = [];

        foreach ($students as $student) {
            if (!in_array($student->batch_year, $batchYears)) {
                $batchYears[] = $student->batch_year;
            }
        }

        return view('pages.admin-auth.email.customize.index', [
            'students' => $students,
            'batchYears' => $batchYears,
        ]);
    }

    public function sendCustomized(Request $request)
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You are not authorized to access this page.');
        }

        if ($request->batch_year_selected == null) {
            return redirect()->back()->with('error', 'Please select a batch year');
        } else if ($request->subject == null) {
            return redirect()->back()->with('error', 'Please select a subject');
        } else if ($request->salutation == null) {
            return redirect()->back()->with('error', 'Please select a salutation');
        } else if ($request->message == null) {
            return redirect()->back()->with('error', 'Please enter a message');
        } else if ($request->conclusion_salutation == null) {
            return redirect()->back()->with('error', 'Please select a conclusion salutation');
        } else if ($request->sender == null) {
            return redirect()->back()->with('error', 'Please enter a sender name');
        }

        $selectedBatchYear = $request->batch_year_selected;
        $students = Student::where('batch_year', $selectedBatchYear)->get();

        foreach ($students as $student) {
            $subject = $request->subject;
            $salutation = $this->getSalutation($request->salutation, $request->otherSalutation);
            $message_content = $request->message;
            $conclusion_salutation = $this->getConclusionSalutation($request->conclusion_salutation, $request->otherConclusionSalutation);
            $sender = $request->sender;
            $attachment = $request->file('attachment');
            $student_id = $student->id;

            $attachmentPath = null;
            $realFileName = null;
            $fileType = null;

            if ($attachment) {
                if ($attachment->isValid()) {
                    $attachmentPath = $attachment->storeAs("attachments/{$student_id}", $attachment->getClientOriginalName(), 'public');

                    $realFileName = $attachment->getClientOriginalName();
                    $fileType = $attachment->getClientMimeType();
                } else {
                    return redirect()->back()->with('error', 'Invalid file');
                }
            }

            Mail::to($student->email)->send(
                new SendCustomizedEmail(
                    $subject,
                    $salutation,
                    $selectedBatchYear,
                    $message_content,
                    $conclusion_salutation,
                    $sender,
                    $student_id,
                    $attachmentPath,
                    $realFileName,
                    $fileType
                )
            );

            $action = "Sent";
            StoreLogsService::storeLogs(auth()->user()->id, $action, "Customized Email", null, null, $selectedBatchYear);
        }

        return redirect()->back()->with('success', 'Customized email sent successfully');
    }


    private function getSalutation($selectedSalutation, $otherSalutation)
    {
        switch ($selectedSalutation) {
            case '0':
                return 'Hi';
            case '1':
                return 'Hello';
            case '2':
                return 'Dear';
            case '3':
                return $otherSalutation;
            default:
                return null;
        }
    }

    private function getConclusionSalutation($selectedConclusion, $otherConclusion)
    {
        switch ($selectedConclusion) {
            case '0':
                return 'Sincerely';
            case '1':
                return 'Yours truly';
            case '2':
                return 'Yours sincerely';
            case '3':
                return 'Regards';
            case '4':
                return 'Kind regards';
            case '5':
                return 'Warm regards';
            case '6':
                return 'Respectfully';
            case '7':
                return 'Best wishes';
            case '8':
                return 'Yours';
            case '9':
                return 'Very truly yours';
            case '10':
                return 'Best regards';
            case '11':
                return $otherConclusion;
            default:
                return null;
        }
    }

    public function updateReceiveOTP(Request $request)
    {
        $user = auth()->user();

        // Update receive OTP setting
        $user->receive_otp = $request->input('receiveOTP', 1); // Default to 0 if not provided
        $user->save();

        return redirect()->back()->with('success', 'Settings updated!');
    }
}
