@extends('layouts.admin.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="dashboard">
                <h2 class="text-left" style="color: #1f3c88;">Dashboard</h2>
                <p class="text-left" style="color: #1f3c88;">As of <span id="currentMonthYear"></span></p>
                <div class="row">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-12 col-sm-10 col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon elevation-1"><i class="fas fa-cog"
                                            style="color: #1f3c88"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Counterpart</span>
                                        <span class="info-box-number">
                                            {{ $counterpartTotal }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-10 col-md-4">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon elevation-1"><i class="fas fa-thumbs-up"
                                            style="color: #1f3c88"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Medical Share</span>
                                        <span class="info-box-number">{{ $medicalShareTotal }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix hidden-md-up"></div>
                            <div class="col-12 col-sm-10 col-md-4">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon elevation-1"><i class="fas fa-shopping-cart"
                                            style="color: #1f3c88"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Received</span>
                                        <span class="info-box-number">{{ $receivedTotal }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-10 col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon elevation-1"><i class="fas fa-cog"
                                            style="color: #1f3c88"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Personal Cash Advance</span>
                                        <span class="info-box-number">
                                            {{ $personalCashAdvanceTotal }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-10 col-md-4">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon elevation-1"><i class="fas fa-thumbs-up"
                                            style="color: #1f3c88"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Graduation Fees</span>
                                        <span class="info-box-number">{{ $graduationFeeTotal }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix hidden-md-up"></div>
                            <div class="col-12 col-sm-10 col-md-4">
                                <div class="info-box mb-3">
                                    <span class="info-box-icon elevation-1"><i class="fas fa-shopping-cart"
                                            style="color: #1f3c88"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Receivable</span>
                                        <span class="info-box-number">{{ $receivableTotal }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header border-0" style="background-color: #ffff;">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Monthly Acquisition</h3>
                                    <form action=""><input type="hidden" id="year_analytics"> <select
                                            id="yearDropdownAnalytics" class="form-control" style="width: 150px;"></select>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body" style="padding-top: 1%">
                                <div class="chart">
                                    <input type="hidden" id="percentage"
                                        data-medical-january="{{ $medicalSharePaidCountJanuary }}"
                                        data-medical-february="{{ $medicalSharePaidCountFebruary }}"
                                        data-medical-march="{{ $medicalSharePaidCountMarch }}"
                                        data-medical-april="{{ $medicalSharePaidCountApril }}"
                                        data-medical-may="{{ $medicalSharePaidCountMay }}"
                                        data-medical-june="{{ $medicalSharePaidCountJune }}"
                                        data-medical-july="{{ $medicalSharePaidCountJuly }}"
                                        data-medical-august="{{ $medicalSharePaidCountAugust }}"
                                        data-medical-september="{{ $medicalSharePaidCountSeptember }}"
                                        data-medical-october="{{ $medicalSharePaidCountOctober }}"
                                        data-medical-november="{{ $medicalSharePaidCountNovember }}"
                                        data-medical-december="{{ $medicalSharePaidCountDecember }}"
                                        data-counterpart-january="{{ $counterpartPaidCountJanuary }}"
                                        data-counterpart-february="{{ $counterpartPaidCountFebruary }}"
                                        data-counterpart-march="{{ $counterpartPaidCountMarch }}"
                                        data-counterpart-april="{{ $counterpartPaidCountApril }}"
                                        data-counterpart-may="{{ $counterpartPaidCountMay }}"
                                        data-counterpart-june="{{ $counterpartPaidCountJune }}"
                                        data-counterpart-july="{{ $counterpartPaidCountJuly }}"
                                        data-counterpart-august="{{ $counterpartPaidCountAugust }}"
                                        data-counterpart-september="{{ $counterpartPaidCountSeptember }}"
                                        data-counterpart-october="{{ $counterpartPaidCountOctober }}"
                                        data-counterpart-november="{{ $counterpartPaidCountNovember }}"
                                        data-counterpart-december="{{ $counterpartPaidCountDecember }}"
                                        data-personal-ca-january="{{ $personalCashAdvancePaidCountJanuary }}"
                                        data-personal-ca-february="{{ $personalCashAdvancePaidCountFebruary }}"
                                        data-personal-ca-march="{{ $personalCashAdvancePaidCountMarch }}"
                                        data-personal-ca-april="{{ $personalCashAdvancePaidCountApril }}"
                                        data-personal-ca-may="{{ $personalCashAdvancePaidCountMay }}"
                                        data-personal-ca-june="{{ $personalCashAdvancePaidCountJune }}"
                                        data-personal-ca-july="{{ $personalCashAdvancePaidCountJuly }}"
                                        data-personal-ca-august="{{ $personalCashAdvancePaidCountAugust }}"
                                        data-personal-ca-september="{{ $personalCashAdvancePaidCountSeptember }}"
                                        data-personal-ca-october="{{ $personalCashAdvancePaidCountOctober }}"
                                        data-personal-ca-november="{{ $personalCashAdvancePaidCountNovember }}"
                                        data-personal-ca-december="{{ $personalCashAdvancePaidCountDecember }}"
                                        data-graduation-fee-january="{{ $graduationFeePaidCountJanuary }}"
                                        data-graduation-fee-february="{{ $graduationFeePaidCountFebruary }}"
                                        data-graduation-fee-march="{{ $graduationFeePaidCountMarch }}"
                                        data-graduation-fee-april="{{ $graduationFeePaidCountApril }}"
                                        data-graduation-fee-may="{{ $graduationFeePaidCountMay }}"
                                        data-graduation-fee-june="{{ $graduationFeePaidCountJune }}"
                                        data-graduation-fee-july="{{ $graduationFeePaidCountJuly }}"
                                        data-graduation-fee-august="{{ $graduationFeePaidCountAugust }}"
                                        data-graduation-fee-september="{{ $graduationFeePaidCountSeptember }}"
                                        data-graduation-fee-october="{{ $graduationFeePaidCountOctober }}"
                                        data-graduation-fee-november="{{ $graduationFeePaidCountNovember }}"
                                        data-graduation-fee-december="{{ $graduationFeePaidCountDecember }}">
                                    <canvas id="barChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 514px;"
                                        width="606" height="294" class="chartjs-render-monitor"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 align-items-center align-middle text-left" style="padding-left: 5vh">
                        <div class="col-md-14">
                            <div class="table-responsive">
                                <h4 style="color: #1f3c88;">Analytics</h4>
                                <p>Percentage of accumulated amount</p>
                                <table class="table" style="border: none">
                                    <tr style="border: none">
                                        <td style="border: none padding: 0%;"><b style="color: #1f3c88;">STATUS</b></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 0vh">Total No of Students with Paid Counterpart
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 0vh"><b>{{ $counterpartPaidStudentsCount }}</b></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 0vh">Total No of Students with Unpaid Counterpart
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 0vh"><b>{{ $counterpartUnpaidStudentsCount }}</b></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 0vh">Total No of Students with Paid MedicShare
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 0vh"><b>{{ $medicalSharePaidStudentsCount }}</b></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 0vh">Total No of Students with Unpaid MedicShare
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 0vh"><b>{{ $medicalShareUnpaidStudentsCount }}</b></td>
                                    </tr>
                                </table>
                            </div>
                            <button type="submit" class="btn" style="background-color: #1f3c88; color: #ffff;">View All</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
