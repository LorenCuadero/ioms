<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Counterpart;
use App\Models\GraduationFee;
use App\Models\MedicalShare;
use App\Models\PersonalCashAdvance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

//
class FinancialReportController extends Controller
{
    public function index()
    {
        if (Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $counterpartTotal = Counterpart::sum('amount_paid');
        $medicalShareTotal = MedicalShare::sum('amount_paid');
        $graduationFeeTotal = GraduationFee::sum('amount_paid');
        $personalCashAdvanceTotal = PersonalCashAdvance::sum('amount_paid');
        $counterpartTotalPayable = Counterpart::sum('amount_due') - $counterpartTotal;
        $medicalShareTotalPayable = MedicalShare::sum('total_cost') * 0.15 - $medicalShareTotal;
        $personalCashAdvanceTotalPayable = PersonalCashAdvance::sum('amount_due') - $personalCashAdvanceTotal;
        $graduationFeeTotalPayable = GraduationFee::sum('amount_due') - $graduationFeeTotal;

        $dates = [
            Counterpart::min('date'),
            MedicalShare::min('date'),
            GraduationFee::min('date'),
            PersonalCashAdvance::min('date'),
        ];

        // Remove null and invalid dates
        $validDates = array_filter($dates, function ($date) {
            return $date !== null && strtotime($date) !== false;
        });

        // Find the earliest date
        $earliestDate = $validDates ? min($validDates) : null;

        // Set the start date to the earliest date or null
        $startFromDate = $earliestDate ? Carbon::parse($earliestDate)->format('F d, Y') : null;

        // Set the end date to the current date
        $endToDate = Carbon::now()->format('F d, Y');

        $total = $counterpartTotal + $medicalShareTotal + $graduationFeeTotal + $personalCashAdvanceTotal;
        $totalPayable = $counterpartTotalPayable + $medicalShareTotalPayable + $personalCashAdvanceTotalPayable + $graduationFeeTotalPayable;

        // Count the total number of students
        $totalNumberOfStudents = Student::count();

        // Batch Years
        $batchYears = Student::distinct('batch_year')->pluck('batch_year');

        $totalStudentsByBatchYear = [];
        foreach ($batchYears as $year) {
            $totalStudentsByBatchYear[$year] = Student::where('batch_year', $year)->count();
        }

        $allBatchYear = "All Batch Year";

        $data['header_title'] = "Reports |";

        return view(
            'pages.admin-auth.financial-reports.index',
            compact(
                'counterpartTotal',
                'medicalShareTotal',
                'graduationFeeTotal',
                'personalCashAdvanceTotal',
                'total',
                'counterpartTotalPayable',
                'medicalShareTotalPayable',
                'graduationFeeTotalPayable',
                'personalCashAdvanceTotalPayable',
                'totalPayable',
                'startFromDate',
                'endToDate',
                'totalNumberOfStudents',
                'totalStudentsByBatchYear',
                'batchYears',
                'year',
                'allBatchYear',
            ), $data
        );
    }

    public function viewFinancialReportByDateFromAndTo(Request $request) {
        if(Auth::user()->role != '2') {
            return redirect()->back()->with('error', 'You do not have permission to access this page');
        }

        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');
        $batchYearSelected = $request->input('batchYear');

        $dates = [
            Counterpart::min('date'),
            MedicalShare::min('date'),
            GraduationFee::min('date'),
            PersonalCashAdvance::min('date'),
        ];

        // Remove null and invalid dates
        $validDates = array_filter($dates, function ($date) {
            return $date !== null && strtotime($date) !== false;
        });

        // Find the earliest date
        $earliestDate = $validDates ? min($validDates) : null;

        // Set the start date to the earliest date or null
        $startFromDate = $earliestDate ? Carbon::parse($earliestDate)->format('F d, Y') : null;

        // Set the end date to the current date
        $endToDate = Carbon::now()->format('F d, Y');


        if ($batchYearSelected == null) {
            $allBatchYear = "All Batch Year";
            $counterpartTotal = Counterpart::whereBetween('date', [$dateFrom, $dateTo])->sum('amount_paid');
            $medicalShareTotal = MedicalShare::whereBetween('date', [$dateFrom, $dateTo])->sum('amount_paid');
            $graduationFeeTotal = GraduationFee::whereBetween('date', [$dateFrom, $dateTo])->sum('amount_paid');
            $personalCashAdvanceTotal = PersonalCashAdvance::whereBetween('date', [$dateFrom, $dateTo])->sum('amount_paid');
            $counterpartTotalPayable = Counterpart::whereBetween('date', [$dateFrom, $dateTo])->sum('amount_due') - $counterpartTotal;
            $medicalShareTotalPayable = MedicalShare::whereBetween('date', [$dateFrom, $dateTo])->sum('total_cost') * 0.15 - $medicalShareTotal;
            $personalCashAdvanceTotalPayable = PersonalCashAdvance::whereBetween('date', [$dateFrom, $dateTo])->sum('amount_due') - $personalCashAdvanceTotal;
            $graduationFeeTotalPayable = GraduationFee::whereBetween('date', [$dateFrom, $dateTo])->sum('amount_due') - $graduationFeeTotal;

            $total = $counterpartTotal + $medicalShareTotal + $graduationFeeTotal + $personalCashAdvanceTotal;
            $totalPayable = $counterpartTotalPayable + $medicalShareTotalPayable + $personalCashAdvanceTotalPayable + $graduationFeeTotalPayable;

            $dateFrom = date('F d, Y', strtotime($dateFrom));
            $dateTo = date('F d, Y', strtotime($dateTo));


            // Count the total number of students
            $totalNumberOfStudents = Student::count();

            // Batch Years
            $batchYears = Student::distinct('batch_year')->pluck('batch_year');

            $totalStudentsByBatchYear = [];
            foreach ($batchYears as $year) {
                $totalStudentsByBatchYear[$year] = Student::where('batch_year', $year)->count();
            }

            return view(
                'pages.admin-auth.financial-reports.index',
                compact(
                    'counterpartTotal',
                    'medicalShareTotal',
                    'graduationFeeTotal',
                    'personalCashAdvanceTotal',
                    'total',
                    'counterpartTotalPayable',
                    'medicalShareTotalPayable',
                    'graduationFeeTotalPayable',
                    'personalCashAdvanceTotalPayable',
                    'totalPayable',
                    'startFromDate',
                    'endToDate',
                    'totalNumberOfStudents',
                    'totalStudentsByBatchYear',
                    'batchYears',
                    'allBatchYear',
                )
            );
        }

        $counterpartTotal = Counterpart::join('students', 'counterparts.student_id', '=', 'students.id')
            ->whereBetween('counterparts.date', [$dateFrom, $dateTo])
            ->where('students.batch_year', $batchYearSelected)
            ->sum('counterparts.amount_paid');

        $counterpartTotalPayable = Counterpart::join('students', 'counterparts.student_id', '=', 'students.id')
            ->whereBetween('counterparts.date', [$dateFrom, $dateTo])
            ->where('students.batch_year', $batchYearSelected)
            ->sum('counterparts.amount_due') - $counterpartTotal;

        $medicalShareTotal = MedicalShare::join('students', 'medical_shares.student_id', '=', 'students.id')
            ->whereBetween('medical_shares.date', [$dateFrom, $dateTo])
            ->where('students.batch_year', $batchYearSelected)
            ->sum('medical_shares.amount_paid');

        $medicalShareTotalPayable = MedicalShare::join('students', 'medical_shares.student_id', '=', 'students.id')
            ->whereBetween('medical_shares.date', [$dateFrom, $dateTo])
            ->where('students.batch_year', $batchYearSelected)
            ->sum(\DB::raw('total_cost * 0.15')) - $medicalShareTotal;

        $graduationFeeTotal = GraduationFee::join('students', 'graduation_fees.student_id', '=', 'students.id')
            ->whereBetween('graduation_fees.date', [$dateFrom, $dateTo])
            ->where('students.batch_year', $batchYearSelected)
            ->sum('graduation_fees.amount_paid');

        $graduationFeeTotalPayable = GraduationFee::join('students', 'graduation_fees.student_id', '=', 'students.id')
            ->whereBetween('graduation_fees.date', [$dateFrom, $dateTo])
            ->where('students.batch_year', $batchYearSelected)
            ->sum('graduation_fees.amount_due') - $graduationFeeTotal;

        $personalCashAdvanceTotal = PersonalCashAdvance::join('students', 'personal_cash_advances.student_id', '=', 'students.id')
            ->whereBetween('personal_cash_advances.date', [$dateFrom, $dateTo])
            ->where('students.batch_year', $batchYearSelected)
            ->sum('personal_cash_advances.amount_paid');

        $personalCashAdvanceTotalPayable = PersonalCashAdvance::join('students', 'personal_cash_advances.student_id', '=', 'students.id')
            ->whereBetween('personal_cash_advances.date', [$dateFrom, $dateTo])
            ->where('students.batch_year', $batchYearSelected)
            ->sum('personal_cash_advances.amount_due') - $personalCashAdvanceTotal;

        $total = $counterpartTotal + $medicalShareTotal + $graduationFeeTotal + $personalCashAdvanceTotal;
        $totalPayable = $counterpartTotalPayable + $medicalShareTotalPayable + $personalCashAdvanceTotalPayable + $graduationFeeTotalPayable;


        $dateFrom = date('F d, Y', strtotime($dateFrom));
        $dateTo = date('F d, Y', strtotime($dateTo));

        // Count the total number of students
        $totalNumberOfStudents = Student::count();

        // Batch Years
        $batchYears = Student::distinct('batch_year')->pluck('batch_year');

        $totalStudentsByBatchYear = [];
        foreach ($batchYears as $year) {
            $totalStudentsByBatchYear[$year] = Student::where('batch_year', $year)->count();
        }

        return view(
            'pages.admin-auth.financial-reports.index',
            compact(
                'counterpartTotal',
                'medicalShareTotal',
                'graduationFeeTotal',
                'personalCashAdvanceTotal',
                'total',
                'counterpartTotalPayable',
                'medicalShareTotalPayable',
                'graduationFeeTotalPayable',
                'personalCashAdvanceTotalPayable',
                'totalPayable',
                'dateFrom',
                'dateTo',
                'batchYearSelected',
                'totalNumberOfStudents',
                'totalStudentsByBatchYear',
                'batchYears'
            )
        );
    }
}
