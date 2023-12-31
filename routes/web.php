<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounterpartController;
use App\Http\Controllers\DisciplinaryController;
use App\Http\Controllers\GraduationFeeController;
use App\Http\Controllers\MedicalShareController;
use App\Http\Controllers\PersonalCashAdvanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentParentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\ClosingOfAccountController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AcademicController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', [AuthController::class, 'loginPage']);

Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify_otp');

Route::prefix('/login')->group(function () {
    Route::get('/', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login');
});

Route::prefix('/forgot-password')->group(function () {
    Route::get('/', [AuthController::class, 'forgotPassword'])->name('forgot_password');
    Route::post('/', [AuthController::class, 'postRecover'])->name('recover');
});

Route::post('/resend-otp', [AuthController::class, 'resend'])->name('resend');
Route::post('/resend-recover-otp', [AuthController::class, 'resendRecoverOTP'])->name('resend-recover-otp');

Route::prefix('/reset-password')->group(function () {
    Route::post('/', [AuthController::class, 'recoverOTP'])->name('recover-submit');
});

Route::prefix('/submit-reset')->group(function () {
    Route::post('/confirm', [AuthController::class, 'confirm_changes'])->name('confirm-changes');
});

Route::post('/submit', [AuthController::class, 'submitReset'])->name('user-submit-reset');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [AuthController::class, 'authorizedRedirect']);
    Route::get('/pn-portal', [StudentParentController::class, 'indexStudent'])->name('payable.index');

    Route::prefix('/dashboard')->group(function () {
        Route::get('/', [AdminController::class, 'indexAdmin'])->name('dashboard.index');
        Route::put('/admin/update-details', [AdminController::class, 'updateReceiveOTP'])->name('admin.updateReceiveOTP');
    });
    Route::post('/view-all-status', [AdminController::class, 'getTotals'])->name('admin.getTotals');
    Route::get('/allBatchTotalCount', [AdminController::class, 'allBatchTotalCount'])->name('admin.allBatchTotalCount');
    Route::post('/perYearViewMonthlyAcquisition', [AdminController::class, 'perYearViewMonthlyAcquisition'])->name('admin.perYearViewMonthlyAcquisition');
    Route::post('/validate-from-current-password', [AuthController::class, 'validate_from_current_pass'])->name('validate_from_current_pass');

    Route::prefix('/students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('students.index');
        Route::put('/staff/update-details', [StudentController::class, 'updateReceiveOTP'])->name('staff.updateReceiveOTP');
        Route::get('/{id}', [StudentController::class, 'getStudent'])->name('students.getStudent');
        Route::post('/', [StudentController::class, 'store'])->name('students.store');
        Route::put('/{id}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    });

    Route::get('/sample', function () {
        return view('layouts.student.sample');
    });

    Route::get('/student-add', [StudentController::class, 'addStudentPage'])->name('students.addStudentPage');

    Route::prefix('/reports-acd')->group(function () {
        Route::get('/', [StudentController::class, 'indexAcdRpt'])->name('rpt.acd.index');
        Route::get('/{id}', [StudentController::class, 'getStudentGradeReport'])->name('rpt.acd.getStudentGradeReport');
        Route::put('/{id}', [StudentController::class, 'updateStudentGradeReport'])->name('rpt.acd.updateStudentGradeReport');
        Route::post('/{id}', [StudentController::class, 'addStudentGradeReport'])->name('rpt.acd.addStudentGradeReport');
        Route::delete('/{id}', [StudentController::class, 'destroyStudentGradeReport'])->name('rpt.acd.destroyStudentGradeReport');
    });

    Route::prefix('/reports-dcpl')->group(function () {
        Route::get('/', [StudentController::class, 'indexStudsList'])->name('rpt.dcpl.index');
        Route::get('/{id}', [DisciplinaryController::class, 'showDisciplinaryRecordsForStudent'])->name('rpt.dcpl.showDisciplinaryRecordsForStudent');
        Route::post('/', [DisciplinaryController::class, 'store'])->name('rpt.dcpl.store');
        Route::put('/{id}', [DisciplinaryController::class, 'update'])->name('rpt.dcpl.update');
        Route::delete('/{id}', [DisciplinaryController::class, 'destroy'])->name('rpt.dcpl.destroy');
    });

    Route::prefix('/students-info')->group(function () {
        Route::get('/', [StudentController::class, 'indexStudent'])->name('students-info.index');
        Route::get('/{id}', [StudentController::class, 'getStudentInfo'])->name('students-info.getStudentInfo');
        Route::put('/{id}', [StudentController::class, 'updateStudent'])->name('students-info.updateStudent');
        Route::delete('/{id}', [StudentController::class, 'deleteStudent'])->name('students-info.deletestudent');
    });



    Route::prefix('/student-portal')->group(function () {
        Route::get('/', [StudentParentController::class, 'index'])->name('student.parent.index');
    });

    Route::prefix('/student-reports')->group(function () {
        Route::get('/', [StudentParentController::class, 'indexReports'])->name('student.reports.index');
    });

    Route::prefix('/student-payments')->group(function () {
        Route::get('/', [StudentParentController::class, 'indexPayment'])->name('student.payments.index');
    });

    Route::prefix('/student-profile')->group(function () {
        Route::get('/', [StudentParentController::class, 'indexProfile'])->name('student.profile.index');
        Route::put('/student/update-details', [StudentParentController::class, 'updateStudentDetails'])->name('student.updateDetails');
    });


    Route::prefix('/email')->group(function () {
        Route::get('/', [AdminController::class, 'email'])->name('admin.mail.email');
        Route::post('/', [AdminController::class, 'sendEmail'])->name('admin.sendEmail');
    });

    Route::prefix('/closing-of-accounts-letter')->group(function () {
        Route::get('/', [AdminController::class, 'coa'])->name('admin.mail.coa');
        Route::post('/', [AdminController::class, 'sendCoa'])->name('admin.sendCoa');
    });

    Route::prefix('/counterpart-records')->group(function () {
        Route::get('/', [CounterpartController::class, 'counterpartRecords'])->name('admin.records.counterpartRecords');
        Route::get('/{id}', [CounterpartController::class, 'studentPageCounterpartRecords'])->name('admin.studentPageCounterpartRecords');
        Route::post('/{id}', [CounterpartController::class, 'storeCounterpart'])->name('admin.storeCounterpart');
        Route::put('/{id}', [CounterpartController::class, 'updateCounterpart'])->name('admin.updateCounterpart');
        Route::delete('/{id}', [CounterpartController::class, 'deleteCounterpart'])->name('admin.deleteCounterpart');
    });

    Route::prefix('/customize-email')->group(function () {
        Route::get('/', [AdminController::class, 'customizedEmail'])->name('admin.mail.customizedEmail');
        Route::post('/', [AdminController::class, 'sendCustomized'])->name('admin.sendCustomized');
    });

    Route::prefix('/medical-share-records')->group(function () {
        Route::get('/', [MedicalShareController::class, 'medicalShare'])->name('admin.records.medicalShare');
        Route::get('/{id}', [MedicalShareController::class, 'studentMedicalShareRecords'])->name('admin.studentMedicalShareRecords');
        Route::post('/{id}', [MedicalShareController::class, 'storeMedicalShare'])->name('admin.storeMedicalShare');
        Route::put('/{id}', [MedicalShareController::class, 'updateMedicalShare'])->name('admin.updateMedicalShare');
        Route::delete('/{id}', [MedicalShareController::class, 'deleteMedicalShare'])->name('admin.deleteMedicalShare');
    });

    Route::prefix('/personal-cash-advance-records')->group(function () {
        Route::get('/', [PersonalCashAdvanceController::class, 'personalCA'])->name('admin.records.personalCA');
        Route::get('/{id}', [PersonalCashAdvanceController::class, 'studentPersonalCARecords'])->name('admin.reports.studentPersonalCARecords');
        Route::post('/{id}', [PersonalCashAdvanceController::class, 'storePersonalCA'])->name('admin.storePersonalCA');
        Route::put('/{id}', [PersonalCashAdvanceController::class, 'updatePersonalCA'])->name('admin.updatePersonalCA');
        Route::delete('/{id}', [PersonalCashAdvanceController::class, 'deletePersonalCA'])->name('admin.deletePersonalCA');
    });

    Route::prefix('/graduation-fees-records')->group(function () {
        Route::get('/', [GraduationFeeController::class, 'graduationFees'])->name('admin.records.graduationFees');
        Route::get('/{id}', [GraduationFeeController::class, 'studentGraduationFeeRecords'])->name('admin.studentGraduationFeeRecords');
        Route::post('/{id}', [GraduationFeeController::class, 'storeGraduationFee'])->name('admin.storeGraduationFee');
        Route::put('/{id}', [GraduationFeeController::class, 'updateGraduationFee'])->name('admin.updateGraduationFee');
        Route::delete('/{id}', [GraduationFeeController::class, 'deleteGraduationFee'])->name('admin.deleteGraduationFee');
    });

    Route::prefix('/reports')->group(function () {
        Route::get('/', [FinancialReportController::class, 'index'])->name('admin.reports.financialReports');
        Route::post('/', [FinancialReportController::class, 'viewFinancialReportByDateFromAndTo'])->name('admin.reports.viewFinancialReportByDateFromAndTo');

        Route::get('/academic-reports', [AcademicController::class, 'indexAcademicReports'])->name('admin.reports.academicReports');
        Route::get('/academic-reports/{id}', [AcademicController::class, 'getStudentGradeReport'])->name('admin.reports.getStudentGradeReport');
        Route::post('/academic-reports/{id}', [AcademicController::class, 'addStudentGradeReport'])->name('admin.reports.addStudentGradeReport');
        Route::put('/academic-reports/{id}', [AcademicController::class, 'updateStudentGradeReport'])->name('admin.reports.updateStudentGradeReport');
        Route::delete('/academic-reports/{id}', [AcademicController::class, 'destroyStudentGradeReport'])->name('admin.reports.destroyStudentGradeReport');

        Route::get('/disciplinary-reports', [DisciplinaryController::class, 'indexDisciplinaryReports'])->name('admin.reports.indexDisciplinaryReports');
        Route::post('/disciplinary-reports', [DisciplinaryController::class, 'storeForAdmin'])->name('admin.reports.storeForAdmin');
        Route::get('/disciplinary-reports/{id}', [DisciplinaryController::class, 'showAdminDisciplinaryRecordsForStudent'])->name('admin.reports.showAdminDisciplinaryRecordsForStudent');
        Route::put('/disciplinary-reports/{id}', [DisciplinaryController::class, 'updateForAdmin'])->name('admin.reports.updateForAdmin');
        Route::delete('/disciplinary-reports/{id}', [DisciplinaryController::class, 'destroyForAdmin'])->name('admin.reports.destroyForAdmin');


    });

    Route::prefix('/closing-of-accounts-admin')->group(function () {
        Route::get('/', [ClosingOfAccountController::class, 'index'])->name('admin.closingOfAccounts');
    });

    Route::prefix('/closing-of-accounts-staff')->group(function () {
        Route::get('/', [ClosingOfAccountController::class, 'indexStaff'])->name('staff.closingOfAccounts');
    });

    Route::get('/create-admin-account', [AccountController::class, 'createAdminAccount'])->name('admin.createAdminAccount');
    Route::get('/create-staff-account', [AccountController::class, 'createStaffAccount'])->name('admin.createStaffAccount');
    Route::get('/create-student-account', [AccountController::class, 'createStudentAccount'])->name('admin.createStudentAccount');

    Route::prefix('/admin-accounts')->group(function () {
        Route::get('/', [AccountController::class, 'indexAdminAccounts'])->name('admin.accounts.admin-accounts');
        Route::get('/{id}', [AccountController::class, 'getAdminAccount'])->name('admin.getAdminAccount');
        Route::put('/{id}', [AccountController::class, 'updateAdminAccount'])->name('admin.updateAdminAccount');
        Route::post('/', [AccountController::class, 'storeAdminAccount'])->name('admin.storeAdminAccount');
        Route::delete('/{id}', [AccountController::class, 'softDeleteAdminAccount'])->name('admin.softDeleteAdminAccount');

    });

    Route::prefix('/student-accounts')->group(function () {
        Route::get('/', [AccountController::class, 'indexStudentsAccounts'])->name('admin.accounts.student-accounts');
        Route::get('/{id}', [AccountController::class, 'getStudentAccount'])->name('admin.getStudentAccount');
        Route::put('/{id}', [AccountController::class, 'updateStudentAccount'])->name('admin.updateStudentAccount');
        Route::delete('/{id}', [AccountController::class, 'softDeleteStudentAccount'])->name('admin.softDeleteStudentAccount');
        Route::post('/', [AccountController::class, 'storeStudentAccount'])->name('admin.storeStudentAccount');
    });

    Route::prefix('/staff-accounts')->group(function () {
        Route::get('/', [AccountController::class, 'indexStaffAccounts'])->name('admin.accounts.staff-accounts');
        Route::get('/{id}', [AccountController::class, 'getStaffAccount'])->name('admin.getStaffAccount');
        Route::put('/{id}', [AccountController::class, 'updateStaffAccount'])->name('admin.updateStaffAccount');
        Route::delete('/{id}', [AccountController::class, 'softDeleteStaffAccount'])->name('admin.softDeleteStaffAccount');
        Route::post('/', [AccountController::class, 'storeStaffAccount'])->name('admin.storeStaffAccount');
    });

    Route::prefix('/logs')->group(function () {
        Route::get('/', [LogController::class, 'index'])->name('admin.logs');
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
