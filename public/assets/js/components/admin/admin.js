const loadingOverlay = $(".loading-spinner-overlay");

function showLoadingSpinner() {
    loadingOverlay.show();
    $("body").css("overflow", "hidden");
}

$(document).ready(function () {
    $(".dropdown-item").click(function () {
        const selectedYear = $(this).text();

        $("#selectedBatchYear").val(selectedYear);
        const sendButton = $("#sendButton");

        $("#batch_year_selected").val(selectedYear);

        if (selectedYear === "Year") {
            sendButton.removeAttr("required");
        } else {
            sendButton.attr("required", "required");
        }
    });
});

$(document).ready(function () {
    $("#selectToAddStudentCounterpart").click(function () {
        const addModal = $("#student-selection-counterpart-modal");

        addModal.modal("show");
    });
});

$(document).ready(function () {
    $("#selectToAddStudentMedicalShare").click(function () {
        const addModal = $("#student-selection-medical-share-modal");

        addModal.modal("show");
    });
});

$(document).ready(function () {
    $("#selectToAddStudentPersonalCA").click(function () {
        const addModal = $("#student-selection-personal-ca-modal");

        addModal.modal("show");
    });
});

$(document).ready(function () {
    $("#selectToAddStudentGraduationFee").click(function () {
        const addModal = $("#student-selection-graduation-fee-modal");

        addModal.modal("show");
    });
});

$(document).ready(function () {
    $(".select-student-link-counterpart").click(function (event) {
        event.preventDefault();

        const studentId = $(this).data("student-id");
        const redirectUrl = $(this).attr("href");
        const loadingOverlay = $(".loading-spinner-overlay");

        function showLoadingSpinner() {
            loadingOverlay.show();
            $("body").css("overflow", "hidden");
        }
        showLoadingSpinner();
        window.location.href = redirectUrl;
    });
});

$(document).ready(function () {
    // $(".back").click(function (event) {
    //     event.preventDefault();

    //     // Specify the URL you want to navigate to
    //     const customUrl = "/counterpart-records";

    //     const loadingOverlay = $(".loading-spinner-overlay");

    //     function showLoadingSpinner() {
    //         loadingOverlay.show();
    //         $("body").css("overflow", "hidden");
    //     }

    //     showLoadingSpinner();

    //     // Navigate to the custom URL
    //     window.location.href = customUrl;
    // });

    $(".back-medical").click(function (event) {
        event.preventDefault();

        // Specify the URL you want to navigate to
        const customUrl = "/medical-share-records";

        const loadingOverlay = $(".loading-spinner-overlay");

        function showLoadingSpinner() {
            loadingOverlay.show();
            $("body").css("overflow", "hidden");
        }

        showLoadingSpinner();

        // Navigate to the custom URL
        window.location.href = customUrl;
    });
});

$(document).ready(function () {
    $("#addStudentCounterpartRecordBtn").click(function (event) {
        $("#add-student-counterpart-modal").modal("show");
    });
});

$(document).ready(function () {
    $("#addStudentPersonalCARecordBtn").click(function (event) {
        $("#add-student-personal-ca-modal").modal("show");
    });
});

$(document).ready(function () {
    $("#addStudentMedicalShareRecordBtn").click(function (event) {
        $("#add-student-medical-share-modal").modal("show");
    });
});

$(document).ready(function () {
    $(".editStudentMedicalShareRecordBtn").click(function (event) {
        const editStudentMedicalShare = $(this).data("medical-share-id");
        const medical_concern = $(this).data("medical-concern");
        const total_cost = $(this).data("total-cost");
        const amount_paid = $(this).data("amount-paid");
        const date_paid = $(this).data("date");
        const med_share = $(this).data("med-share-percent");

        $("#medical_id").val(editStudentMedicalShare);
        $("#medical_concern_ms_edit").val(medical_concern);
        $("#amount_due_ms_edit").val(total_cost);
        $("#percent_share").val(med_share);
        $("#amount_paid_ms_edit").val(amount_paid);
        $("#date_paid_ms_edit").val(date_paid);
    });
});

// $(document).ready(function () {
//     $("#edit-student-medical-share-modal").on("show.bs.modal", function (event) {
//         const button = $(event.relatedTarget);
//         const editStudentMedicalShare = button.data("medical-share-id");
//         const medical_concern = button.data("medical-concern");
//         const total_cost = button.data("total-cost");
//         const amount_paid = button.data("amount-paid");
//         const date_paid = button.data("date");

//         $("#medical_id").val(editStudentMedicalShare);
//         $("#medical_concern").val(medical_concern);
//         $("#total-cost").val(total_cost);
//         const share15 = (parseFloat(total_cost) * 0.15).toFixed(2);
//         $("#amount_paid").val(amount_paid);
//         $("#date").val(date_paid);
//     });
// });

$(document).ready(function () {
    $("#addStudentGraduationFeeRecordRecordBtn").click(function (event) {
        $("#add-student-graduation-fee-modal").modal("show");
    });
});

$(document).ready(function () {
    // Handle the click event on the "View" button
    $(".view-button-counterpart").on("click", function () {
        const studentId = $(this).data("student-id");
        const finalUrl = `/counterpart-records/${studentId}`;
        window.location.href = finalUrl;
    });
});

$(document).ready(function () {
    $(".view-button-graduation-fee").on("click", function () {
        const studentId = $(this).data("student-id");
        const finalUrl = `/graduation-fees-records/${studentId}`;
        window.location.href = finalUrl;
    });
});

$(document).ready(function () {
    $(".view-button-personal-ca").on("click", function () {
        const studentId = $(this).data("student-id");
        const finalUrl = `/personal-cash-advance-records/${studentId}`;
        window.location.href = finalUrl;
    });
});

$(document).ready(function () {
    // Access the data passed from the Blade view
    const $percentageInput = $(".percentage");

    // Counterpart
    const counterpartPercentageJanuary = $percentageInput.data(
        "counterpart-january"
    );
    const counterpartPercentageFebruary = $percentageInput.data(
        "counterpart-february"
    );
    const counterpartPercentageMarch =
        $percentageInput.data("counterpart-march");
    const counterpartPercentageApril =
        $percentageInput.data("counterpart-april");
    const counterpartPercentageMay = $percentageInput.data("counterpart-may");
    const counterpartPercentageJune = $percentageInput.data("counterpart-june");
    const counterpartPercentageJuly = $percentageInput.data("counterpart-july");
    const counterpartPercentageAugust =
        $percentageInput.data("counterpart-august");
    const counterpartPercentageSeptember = $percentageInput.data(
        "counterpart-september"
    );
    const counterpartPercentageOctober = $percentageInput.data(
        "counterpart-october"
    );
    const counterpartPercentageNovember = $percentageInput.data(
        "counterpart-november"
    );
    const counterpartPercentageDecember = $percentageInput.data(
        "counterpart-december"
    );

    // MedicalShare
    const medicalSharePercentageJanuary =
        $percentageInput.data("medical-january");
    const medicalSharePercentageFebruary =
        $percentageInput.data("medical-february");
    const medicalSharePercentageMarch = $percentageInput.data("medical-march");
    const medicalSharePercentageApril = $percentageInput.data("medical-april");
    const medicalSharePercentageMay = $percentageInput.data("medical-may");
    const medicalSharePercentageJune = $percentageInput.data("medical-june");
    const medicalSharePercentageJuly = $percentageInput.data("medical-july");
    const medicalSharePercentageAugust =
        $percentageInput.data("medical-august");
    const medicalSharePercentageSeptember =
        $percentageInput.data("medical-september");
    const medicalSharePercentageOctober =
        $percentageInput.data("medical-october");
    const medicalSharePercentageNovember =
        $percentageInput.data("medical-november");
    const medicalSharePercentageDecember =
        $percentageInput.data("medical-december");

    // PersonalCashAdvance
    const personalCashAdvancePercentageJanuary = $percentageInput.data(
        "personal-ca-january"
    );
    const personalCashAdvancePercentageFebruary = $percentageInput.data(
        "personal-ca-february"
    );
    const personalCashAdvancePercentageMarch =
        $percentageInput.data("personal-ca-march");
    const personalCashAdvancePercentageApril =
        $percentageInput.data("personal-ca-april");
    const personalCashAdvancePercentageMay =
        $percentageInput.data("personal-ca-may");
    const personalCashAdvancePercentageJune =
        $percentageInput.data("personal-ca-june");
    const personalCashAdvancePercentageJuly =
        $percentageInput.data("personal-ca-july");
    const personalCashAdvancePercentageAugust =
        $percentageInput.data("personal-ca-august");
    const personalCashAdvancePercentageSeptember = $percentageInput.data(
        "personal-ca-september"
    );
    const personalCashAdvancePercentageOctober = $percentageInput.data(
        "personal-ca-october"
    );
    const personalCashAdvancePercentageNovember = $percentageInput.data(
        "personal-ca-november"
    );
    const personalCashAdvancePercentageDecember = $percentageInput.data(
        "personal-ca-december"
    );

    // GraduationFee
    const graduationFeePercentageJanuary = $percentageInput.data(
        "graduation-fee-january"
    );
    const graduationFeePercentageFebruary = $percentageInput.data(
        "graduation-fee-february"
    );
    const graduationFeePercentageMarch = $percentageInput.data(
        "graduation-fee-march"
    );
    const graduationFeePercentageApril = $percentageInput.data(
        "graduation-fee-april"
    );
    const graduationFeePercentageMay =
        $percentageInput.data("graduation-fee-may");
    const graduationFeePercentageJune = $percentageInput.data(
        "graduation-fee-june"
    );
    const graduationFeePercentageJuly = $percentageInput.data(
        "graduation-fee-july"
    );
    const graduationFeePercentageAugust = $percentageInput.data(
        "graduation-fee-august"
    );
    const graduationFeePercentageSeptember = $percentageInput.data(
        "graduation-fee-september"
    );
    const graduationFeePercentageOctober = $percentageInput.data(
        "graduation-fee-october"
    );
    const graduationFeePercentageNovember = $percentageInput.data(
        "graduation-fee-november"
    );
    const graduationFeePercentageDecember = $percentageInput.data(
        "graduation-fee-december"
    );

    // Define your chart data and options
    const barChartData = {
        labels: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ],
        datasets: [
            {
                label: "Counterpart Percentage",
                backgroundColor: "rgba(60,141,188,0.9)",
                borderColor: "rgba(60,141,188,0.8)",
                pointRadius: false,
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
                data: [
                    counterpartPercentageJanuary,
                    counterpartPercentageFebruary,
                    counterpartPercentageMarch,
                    counterpartPercentageApril,
                    counterpartPercentageMay,
                    counterpartPercentageJune,
                    counterpartPercentageJuly,
                    counterpartPercentageAugust,
                    counterpartPercentageSeptember,
                    counterpartPercentageOctober,
                    counterpartPercentageNovember,
                    counterpartPercentageDecember,
                ],
            },
            {
                label: "Medical Percentage",
                backgroundColor: "#7EB1ED",
                borderColor: "#7EB1ED",
                pointRadius: false,
                pointColor: "#3b8bba",
                pointStrokeColor: "#7EB1ED",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "#7EB1ED",
                data: [
                    medicalSharePercentageJanuary,
                    medicalSharePercentageFebruary,
                    medicalSharePercentageMarch,
                    medicalSharePercentageApril,
                    medicalSharePercentageMay,
                    medicalSharePercentageJune,
                    medicalSharePercentageJuly,
                    medicalSharePercentageAugust,
                    medicalSharePercentageSeptember,
                    medicalSharePercentageOctober,
                    medicalSharePercentageNovember,
                    medicalSharePercentageDecember,
                ],
            },
            {
                label: "Personal CA Percentage",
                backgroundColor: "#1F3C88",
                borderColor: "#1F3C88",
                pointRadius: false,
                pointColor: "#3b8bba",
                pointStrokeColor: "#1F3C88",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "#1F3C88",
                data: [
                    personalCashAdvancePercentageJanuary,
                    personalCashAdvancePercentageFebruary,
                    personalCashAdvancePercentageMarch,
                    personalCashAdvancePercentageApril,
                    personalCashAdvancePercentageMay,
                    personalCashAdvancePercentageJune,
                    personalCashAdvancePercentageJuly,
                    personalCashAdvancePercentageAugust,
                    personalCashAdvancePercentageSeptember,
                    personalCashAdvancePercentageOctober,
                    personalCashAdvancePercentageNovember,
                    personalCashAdvancePercentageDecember,
                ],
            },
            {
                label: "Graduation Fee Percentage",
                backgroundColor: "#FFB13D",
                borderColor: "#FFB13D",
                pointRadius: false,
                pointColor: "#3b8bba",
                pointStrokeColor: "#FFB13D",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "#FFB13D",
                data: [
                    graduationFeePercentageJanuary,
                    graduationFeePercentageFebruary,
                    graduationFeePercentageMarch,
                    graduationFeePercentageApril,
                    graduationFeePercentageMay,
                    graduationFeePercentageJune,
                    graduationFeePercentageJuly,
                    graduationFeePercentageAugust,
                    graduationFeePercentageSeptember,
                    graduationFeePercentageOctober,
                    graduationFeePercentageNovember,
                    graduationFeePercentageDecember,
                ],
            },
        ],
    };

    const barChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
            display: true,
        },
        scales: {
            xAxes: [
                {
                    gridLines: {
                        display: true,
                    },
                },
            ],
            yAxes: [
                {
                    gridLines: {
                        display: true,
                    },
                },
            ],
        },
    };

    // Create the bar chart using the data and options
    const barChartCanvas = $(".barChart");

    if (barChartCanvas.length > 0) {
        const context = barChartCanvas[0].getContext("2d");

        if (context) {
            // Create or update the bar chart using the data and options
            new Chart(context, {
                type: "bar",
                data: barChartData,
                options: barChartOptions,
            });
        } else {
        }
    } else {
    }
});

$(document).ready(function () {
    const currentYearData = new Date().getFullYear();
    $("#currentYear").text("Year " + currentYearData);
});

$(document).ready(function () {
    var currentYear = new Date().getFullYear();
    for (var i = currentYear; i >= currentYear - 30; i--) {
        $("#yearDropdownAnalytics").append(
            $("<option>", {
                id: "year",
                name: "year",
                value: i,
                text: i,
            })
        );
    }

    var successNotificationShown = false;

    function showLoadingSpinner() {
        var loadingOverlay11 = $(".loading-spinner-overlay");
        loadingOverlay11.show();
        $("body").css("overflow", "hidden");
    }

    function hideLoadingSpinner() {
        var loadingOverlay11 = $(".loading-spinner-overlay");
        loadingOverlay11.hide();
        $("body").css("overflow", "auto");
    }

    function submitMonthlyForm() {
        // showLoadingSpinner();

        $.ajax({
            url: $("#monthly-form").attr("action"),
            type: $("#monthly-form").attr("method"),
            data: $("#monthly-form").serialize(),

            success: function (response) {
                if (!successNotificationShown) {
                    successNotificationShown = true;
                }

                // Assuming changeCanvasValues is defined somewhere
                changeCanvasValues(response);
                hideLoadingSpinner();
            },
            error: function (error) {
                // hideLoadingSpinner();
            },
        });
    }

    // Change event for the year dropdown
    $("#yearDropdownAnalytics").change(function () {
        var selectedYear = $(this).val();

        $("#year_analytics").val(selectedYear);
        $("#hiddenYearInput").val(selectedYear);

        // Call the form submission function
        submitMonthlyForm();
    });

    function changeCanvasValues(response) {
        // Counterpart
        var counterpartPercentageJanuary = response.counterpartPaidCountJanuary;
        var counterpartPercentageFebruary =
            response.counterpartPaidCountFebruary;
        var counterpartPercentageMarch = response.counterpartPaidCountMarch;
        var counterpartPercentageApril = response.counterpartPaidCountApril;
        var counterpartPercentageMay = response.counterpartPaidCountMay;
        var counterpartPercentageJune = response.counterpartPaidCountJune;
        var counterpartPercentageJuly = response.counterpartPaidCountJuly;
        var counterpartPercentageAugust = response.counterpartPaidCountAugust;
        var counterpartPercentageSeptember =
            response.counterpartPaidCountSeptember;
        var counterpartPercentageOctober = response.counterpartPaidCountOctober;
        var counterpartPercentageNovember =
            response.counterpartPaidCountNovember;
        var counterpartPercentageDecember =
            response.counterpartPaidCountDecember;

        // MedicalShare
        var medicalSharePercentageJanuary =
            response.medicalSharePaidCountJanuary;
        var medicalSharePercentageFebruary =
            response.medicalSharePaidCountFebruary;
        var medicalSharePercentageMarch = response.medicalSharePaidCountMarch;
        var medicalSharePercentageApril = response.medicalSharePaidCountApril;
        var medicalSharePercentageMay = response.medicalSharePaidCountMay;
        var medicalSharePercentageJune = response.medicalSharePaidCountJune;
        var medicalSharePercentageJuly = response.medicalSharePaidCountJuly;
        var medicalSharePercentageAugust = response.medicalSharePaidCountAugust;
        var medicalSharePercentageSeptember =
            response.medicalSharePaidCountSeptember;
        var medicalSharePercentageOctober =
            response.medicalSharePaidCountOctober;
        var medicalSharePercentageNovember =
            response.medicalSharePaidCountNovember;
        var medicalSharePercentageDecember =
            response.medicalSharePaidCountDecember;

        // PersonalCashAdvance
        var personalCashAdvancePercentageJanuary =
            response.personalCashAdvancePaidCountJanuary;
        var personalCashAdvancePercentageFebruary =
            response.personalCashAdvancePaidCountFebruary;
        var personalCashAdvancePercentageMarch =
            response.personalCashAdvancePaidCountMarch;
        var personalCashAdvancePercentageApril =
            response.personalCashAdvancePaidCountApril;
        var personalCashAdvancePercentageMay =
            response.personalCashAdvancePaidCountMay;
        var personalCashAdvancePercentageJune =
            response.personalCashAdvancePaidCountJune;
        var personalCashAdvancePercentageJuly =
            response.personalCashAdvancePaidCountJuly;
        var personalCashAdvancePercentageAugust =
            response.personalCashAdvancePaidCountAugust;
        var personalCashAdvancePercentageSeptember =
            response.personalCashAdvancePaidCountSeptember;
        var personalCashAdvancePercentageOctober =
            response.personalCashAdvancePaidCountOctober;
        var personalCashAdvancePercentageNovember =
            response.personalCashAdvancePaidCountNovember;
        var personalCashAdvancePercentageDecember =
            response.personalCashAdvancePaidCountDecember;

        // GraduationFee
        var graduationFeePercentageJanuary =
            response.graduationFeePaidCountJanuary;
        var graduationFeePercentageFebruary =
            response.graduationFeePaidCountFebruary;
        var graduationFeePercentageMarch = response.graduationFeePaidCountMarch;
        var graduationFeePercentageApril = response.graduationFeePaidCountApril;
        var graduationFeePercentageMay = response.graduationFeePaidCountMay;
        var graduationFeePercentageJune = response.graduationFeePaidCountJune;
        var graduationFeePercentageJuly = response.graduationFeePaidCountJuly;
        var graduationFeePercentageAugust =
            response.graduationFeePaidCountAugust;
        var graduationFeePercentageSeptember =
            response.graduationFeePaidCountSeptember;
        var graduationFeePercentageOctober =
            response.graduationFeePaidCountOctober;
        var graduationFeePercentageNovember =
            response.graduationFeePaidCountNovember;
        var graduationFeePercentageDecember =
            response.graduationFeePaidCountDecember;

        // Define your chart data and options
        const barChartData = {
            labels: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ],
            datasets: [
                {
                    label: "Counterpart Percentage",
                    backgroundColor: "rgba(60,141,188,0.9)",
                    borderColor: "rgba(60,141,188,0.8)",
                    pointRadius: false,
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: [
                        counterpartPercentageJanuary,
                        counterpartPercentageFebruary,
                        counterpartPercentageMarch,
                        counterpartPercentageApril,
                        counterpartPercentageMay,
                        counterpartPercentageJune,
                        counterpartPercentageJuly,
                        counterpartPercentageAugust,
                        counterpartPercentageSeptember,
                        counterpartPercentageOctober,
                        counterpartPercentageNovember,
                        counterpartPercentageDecember,
                    ],
                },
                {
                    label: "Medical Percentage",
                    backgroundColor: "#7EB1ED",
                    borderColor: "#7EB1ED",
                    pointRadius: false,
                    pointColor: "#3b8bba",
                    pointStrokeColor: "#7EB1ED",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "#7EB1ED",
                    data: [
                        medicalSharePercentageJanuary,
                        medicalSharePercentageFebruary,
                        medicalSharePercentageMarch,
                        medicalSharePercentageApril,
                        medicalSharePercentageMay,
                        medicalSharePercentageJune,
                        medicalSharePercentageJuly,
                        medicalSharePercentageAugust,
                        medicalSharePercentageSeptember,
                        medicalSharePercentageOctober,
                        medicalSharePercentageNovember,
                        medicalSharePercentageDecember,
                    ],
                },
                {
                    label: "Personal CA Percentage",
                    backgroundColor: "#1F3C88",
                    borderColor: "#1F3C88",
                    pointRadius: false,
                    pointColor: "#3b8bba",
                    pointStrokeColor: "#1F3C88",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "#1F3C88",
                    data: [
                        personalCashAdvancePercentageJanuary,
                        personalCashAdvancePercentageFebruary,
                        personalCashAdvancePercentageMarch,
                        personalCashAdvancePercentageApril,
                        personalCashAdvancePercentageMay,
                        personalCashAdvancePercentageJune,
                        personalCashAdvancePercentageJuly,
                        personalCashAdvancePercentageAugust,
                        personalCashAdvancePercentageSeptember,
                        personalCashAdvancePercentageOctober,
                        personalCashAdvancePercentageNovember,
                        personalCashAdvancePercentageDecember,
                    ],
                },
                {
                    label: "Graduation Fee Percentage",
                    backgroundColor: "#FFB13D",
                    borderColor: "#FFB13D",
                    pointRadius: false,
                    pointColor: "#3b8bba",
                    pointStrokeColor: "#FFB13D",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "#FFB13D",
                    data: [
                        graduationFeePercentageJanuary,
                        graduationFeePercentageFebruary,
                        graduationFeePercentageMarch,
                        graduationFeePercentageApril,
                        graduationFeePercentageMay,
                        graduationFeePercentageJune,
                        graduationFeePercentageJuly,
                        graduationFeePercentageAugust,
                        graduationFeePercentageSeptember,
                        graduationFeePercentageOctober,
                        graduationFeePercentageNovember,
                        graduationFeePercentageDecember,
                    ],
                },
            ],
        };

        const barChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: true,
            },
            scales: {
                xAxes: [
                    {
                        gridLines: {
                            display: true,
                        },
                    },
                ],
                yAxes: [
                    {
                        gridLines: {
                            display: true,
                        },
                    },
                ],
            },
        };

        let barChart;

        const barChartCanvas = $(".barChart")[0];

        if (barChartCanvas) {
            const context = barChartCanvas.getContext("2d");

            if (context) {
                createOrUpdateBarChart(barChartData);
            } else {
            }
        } else {
        }

        function createOrUpdateBarChart(data) {
            if (barChart) {
                barChart.data = data;
                barChart.update();
            } else {
                barChart = new Chart(barChartCanvas, {
                    type: "bar",
                    data: data,
                    options: barChartOptions,
                });
            }
        }
    }
});

$(document).ready(function () {
    const currentDate = new Date();

    const monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];

    // Get the current month and year
    const currentMonth = monthNames[currentDate.getMonth()];
    const currentYearDash = new Date().getFullYear();

    $("#currentMonthYear").text(currentMonth + " " + currentYearDash);
});

$(document).ready(function () {
    const monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];

    $(".edit-student-counterpart-button").click(function () {
        const editModal = $("#edit-student-counterpart-modal");
        const month = $(this).data("month");
        const year = $(this).data("year");
        const amountDue = $(this).data("amount-due");
        const amountPaid = $(this).data("amount-paid");
        const date = $(this).data("date");
        const id = $(this).data("id");
        const editUrl = $(this).data("edit-url");
        const monthNumber = $(this).data("month");
        const monthName = monthNames[monthNumber - 1]; // Adjust for 0-based index
        // Set the values in the modal field
        editModal.find("#edit-month-display").val(monthName);
        editModal.find("#edit-month-hidden").val(monthNumber);
        editModal.find("#edit-year").val(year);
        editModal.find("#edit-month").val(month);
        editModal.find("#edit-year").val(year);
        editModal.find("#edit-amount_due").val(amountDue);
        editModal.find("#edit-amount_paid").val(amountPaid);
        editModal.find("#edit-date").val(date);
        $("#edit-counterpart-form").attr(
            "action",
            editUrl.replace("counterpart_id", id)
        );

        editModal.modal("show");
    });
});

$(document).ready(function () {
    $(".edit-student-personal-ca-button").click(function () {
        const editModal = $("#edit-student-personal-ca-modal");
        const purpose = $(this).data("purpose");
        const amountDue = $(this).data("amount-due");
        const amountPaid = $(this).data("amount-paid");
        const date = $(this).data("date");
        const id = $(this).data("id");
        const editUrl = $(this).data("edit-url");

        editModal.find("#edit_purpose").val(purpose);
        editModal.find("#edit_amount_due").val(amountDue);
        editModal.find("#edit_amount_paid").val(amountPaid);
        editModal.find("#edit_date").val(date);
        $("#edit-personal-ca-form").attr(
            "action",
            editUrl.replace("personal_ca_id", id)
        );
        editModal.modal("show");
    });
});

$(document).ready(function () {
    $(".edit-student-graduation-fee-button").click(function () {
        const editModal = $("#edit-student-graduation-fee-modal");
        const amountDue = $(this).data("amount-due");
        const amountPaid = $(this).data("amount-paid");
        const date = $(this).data("date");
        const id = $(this).data("id");
        const editUrl = $(this).data("edit-url");

        editModal.find("#edit_amount_due_gf").val(amountDue);
        editModal.find("#edit_amount_paid_gf").val(amountPaid);
        editModal.find("#edit_date_gf").val(date);
        $("#edit-graduation-fee-form").attr(
            "action",
            editUrl.replace("graduation_fee_id", id)
        );
        editModal.modal("show");
    });
});

$(document).ready(function () {
    $(".delete-student").click(function () {
        const deleteId = $(this).data("id");
        const deleteModalConfirm = $("#delete-student-confirmation-modal");
        const deletionUrl = $(this).data("url");

        $("#deletion-confirmed-form-student").attr(
            "action",
            deletionUrl.replace("grad_id", deleteId)
        );
        deleteModalConfirm.modal("show");
    });
});

$(document).ready(function () {
    $(".delete-graduation-fee").click(function () {
        const deleteId = $(this).data("id");
        const deleteModal = $("#delete-graduation-confirmation-modal");
        const deletionUrl = $(this).data("url");

        $("#deletion-confirmed-form-graduation").attr(
            "action",
            deletionUrl.replace("student_id", deleteId)
        );

        deleteModal.modal("show");
    });
});

$(document).ready(function () {
    $(".delete-academic").click(function () {
        const deleteId = $(this).data("id");
        const deleteModal = $("#delete-student-academic-confirmation-modal");
        const deletionUrl = $(this).data("url");
        const studentId = $(this).data("student-id");

        $("#deletion-confirmed-form-student-academic").attr(
            "action",
            deletionUrl.replace("student_id", studentId)
        );

        $("#acad_id").val(deleteId);

        deleteModal.modal("show");
    });
});

$(document).ready(function () {
    $(".delete-dcpl-btn").click(function () {
        const deleteId = $(this).data("id");
        const deleteModalDCPL = $(
            "#delete-student-disciplinary-confirmation-modal"
        );
        const deletionUrl = $(this).data("url");

        $("#deletion-confirmed-form-student-disciplinary").attr(
            "action",
            deletionUrl.replace("dcpl_id", deleteId)
        );

        deleteModalDCPL.modal("show");
    });
});

$(document).ready(function () {
    $(".delete-counterpart").click(function () {
        const deleteId = $(this).data("id");
        const deleteModal = $("#delete-counterpart-confirmation-modal");
        const deletionUrl = $(this).data("delete-url");

        $("#deletion-confirmed-form").attr(
            "action",
            deletionUrl.replace("counterpart_id", deleteId)
        );

        deleteModal.modal("show");
    });
});

$(document).ready(function () {
    $(".delete-personal-ca").click(function () {
        const deleteId = $(this).data("id");
        const deleteModal = $("#delete-personal-confirmation-modal");
        const deletionUrl = $(this).data("delete-url");

        $("#deletion-confirmed-form-personal").attr(
            "action",
            deletionUrl.replace("personal_ca_id", deleteId)
        );

        deleteModal.modal("show");
    });
});

$(document).ready(function () {
    $(".delete-medical-share").click(function () {
        const deleteId = $(this).data("id");
        const deleteModal = $("#delete-medical-share-confirmation-modal");
        const deletionUrl = $(this).data("delete-url");

        $("#deletion-confirmed-form-medical").attr(
            "action",
            deletionUrl.replace("medical_share_id", deleteId)
        );

        deleteModal.modal("show");
    });
});

// Medical View Button
$(document).ready(function () {
    // Handle the click event on the "View" button
    $(".view-button-medical").on("click", function () {
        const studentId = $(this).data("student-id");
        const finalUrl = `/medical-share-records/${studentId}`;
        window.location.href = finalUrl;
    });
});

$(document).ready(function () {
    $(".view-all").click(function () {
        const viewModalDash = $("#dashboard-modal");
        viewModalDash.modal("show");
    });
});

$(document).ready(function () {
    const batchYearSelect = $("#batch_year");
    const selectedBatchYearElement = $("#selected-batch-year");
    const totalStudentsPerYearElement = $("#total-students-per-year");
    const totalNumberOfStudentsPerBatch = $("#batch-year-form");
    const totalByYear = totalNumberOfStudentsPerBatch.data("total-by-year");
    const formYear = $("#get_totals_by_batch_year_form");
    const allBatch = $("#allBatch");
    let successNotificationShown = false;

    // Function to update the modal content with totals
    function updateModalContent(response) {
        // Update the modal content with data from the response
        $("#counterpartPaidStudentsCount").text(response.totalPaidCounterpart);
        $("#counterpartUnpaidStudentsCount").text(
            response.totalUnpaidCounterpart
        );
        $("#counterpartNotFullyPaidStudentsCount").text(
            response.totalNotFullyPaidCounterpart
        );
        $("#medicalSharePaidStudentsCount").text(
            response.totalPaidMedicalShare
        );
        $("#medicalShareUnpaidStudentsCount").text(
            response.totalUnpaidMedicalShare
        );
        $("#medicalShareNotFullyPaidStudentsCount").text(
            response.totalNotFullyPaidMedicalShare
        );
        $("#personalCashAdvancePaidStudentsCount").text(
            response.totalPaidPersonalCashAdvance
        );
        $("#personalCashAdvanceUnpaidStudentsCount").text(
            response.totalUnpaidPersonalCashAdvance
        );
        $("#personalCashAdvanceNotFullyPaidStudentsCount").text(
            response.totalNotFullyPaidPersonalCashAdvance
        );
        $("#graduationFeePaidStudentsCount").text(
            response.totalPaidGraduationFees
        );
        $("#graduationFeeUnpaidStudentsCount").text(
            response.totalUnpaidGraduationFees
        );
        $("#graduationFeeNotFullyPaidStudentsCount").text(
            response.totalNotFullyPaidGraduationFees
        );

        // Show the modal
        $("#dashboard-modal").modal("show");
    }

    function updateModalContentWhenAllBatch(response) {
        // Update the modal content with data from the response
        $("#counterpartPaidStudentsCount").text(
            response.counterpartPaidStudentsCount
        );
        $("#counterpartUnpaidStudentsCount").text(
            response.counterpartUnpaidStudentsCount
        );
        $("#counterpartNotFullyPaidStudentsCount").text(
            response.counterpartNotFullyPaidStudentsCount
        );
        $("#medicalSharePaidStudentsCount").text(
            response.medicalSharePaidStudentsCount
        );
        $("#medicalShareUnpaidStudentsCount").text(
            response.medicalShareUnpaidStudentsCount
        );
        $("#medicalShareNotFullyPaidStudentsCount").text(
            response.medicalShareNotFullyPaidStudentsCount
        );
        $("#personalCashAdvancePaidStudentsCount").text(
            response.personalCashAdvancePaidStudentsCount
        );
        $("#personalCashAdvanceUnpaidStudentsCount").text(
            response.personalCashAdvanceUnpaidStudentsCount
        );
        $("#personalCashAdvanceNotFullyPaidStudentsCount").text(
            response.personalCashAdvanceNotFullyPaidStudentsCount
        );
        $("#graduationFeePaidStudentsCount").text(
            response.graduationFeePaidStudentsCount
        );
        $("#graduationFeeUnpaidStudentsCount").text(
            response.graduationFeeUnpaidStudentsCount
        );
        $("#graduationFeeNotFullyPaidStudentsCount").text(
            response.graduationFeeNotFullyPaidStudentsCount
        );
    }

    function showLoadingSpinner() {
        const loadingOverlay = $(".loading-spinner-overlay");
        loadingOverlay.show();
        $("body").css("overflow", "hidden");
    }

    function hideLoadingSpinner() {
        const loadingOverlay = $(".loading-spinner-overlay");
        loadingOverlay.hide();
        $("body").css("overflow", "auto");
    }

    batchYearSelect.on("change", function () {
        selectedBatchYearElement.show();
        const selectedYear = batchYearSelect.val();
        selectedBatchYearElement.text(
            "Batch " + selectedYear + " total number of students: "
        );

        if (selectedYear in totalByYear) {
            totalStudentsPerYearElement.text(totalByYear[selectedYear]);
            formYear.submit();
            $("#total-students-per-year").show();
        } else {
            selectedBatchYearElement.text("");
            selectedBatchYearElement.hide();
            $("#total-students-per-year").hide();

            if (allBatch) {
                // showLoadingSpinner();
                $.ajax({
                    url: "/allBatchTotalCount",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        updateModalContentWhenAllBatch(response);
                        hideLoadingSpinner();
                    },
                    error: function (error) {
                        hideLoadingSpinner();
                    },
                });
                return;
            }
        }
    });

    $("#batch_year").change(function () {
        var selectedOption = $(this).val();
        if (selectedOption === "") {
            // If "All Batch Year" is selected, show the total number of all students
            var totalNumberOfStudents = $("#all-student-number").val();
            $("#TotalNumberOfAllStudents").text(
                "Total number of all students: " + totalNumberOfStudents
            );
        } else {
            // Otherwise, clear the total number of students
            $("#TotalNumberOfAllStudents").empty();
        }
    });

    if ($("#batch_year").val() === "") {
        var totalNumberOfStudents = $("#all-student-number").val();
        $("#TotalNumberOfAllStudents").text(
            "Total number of all students: " + totalNumberOfStudents
        );
    } else {
        $("#TotalNumberOfAllStudents").empty();
    }

    formYear.submit(function (e) {
        e.preventDefault();

        // showLoadingSpinner();

        $.ajax({
            url: $(this).attr("action"),
            type: $(this).attr("method"),
            data: $(this).serialize(),

            success: function (response) {
                if (!successNotificationShown) {
                    successNotificationShown = true;
                }

                // Update the modal with data
                updateModalContent(response);

                hideLoadingSpinner();
            },
            error: function (error) {
                hideLoadingSpinner();
                // Handle errors if needed
            },
        });
    });

    $(".printButtonOnModal").click(function () {
        // Get the content of the TotalNumberOfAllStudents div
        const totalStudentsDivContent = $("#TotalNumberOfAllStudents").html();
        const selectedBatchYearElementOnPrint = $(
            "#selected-batch-year"
        ).html();
        const totalStudentsPerYearElementOnPrint = $(
            "#total-students-per-year"
        ).html();

        // Get the current date
        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString("en-US", {
            month: "long",
            day: "numeric",
            year: "numeric",
        });

        // Open a new window or tab with the data in a table
        const printWindow = window.open("", "_blank");
        printWindow.document.write(
            "<html><head><title>Print Summary Report</title>"
        );
        printWindow.document.write("<style>");
        printWindow.document.write(
            "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
        );
        printWindow.document.write(
            "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
        );
        printWindow.document.write(
            "table, th, td { border: 1px solid black; padding: 10px; }"
        );
        printWindow.document.write(".centered { text-align: center; }");
        printWindow.document.write(
            ".logo { width: 150px; height: 50px; margin: 0 auto; }"
        );
        printWindow.document.write("</style>");
        printWindow.document.write("</head><body>");

        printWindow.document.write(
            '<br><div class="centered">' +
                '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                "</div>"
        );
        printWindow.document.write(
            '<br><h4 class="centered">Summary Report</h4>'
        );
        printWindow.document.write(
            '<span class="centered">' + "as of " + formattedDate + "</span><br>"
        );
        // Display the content of the div
        printWindow.document.write(
            "<br><p>" + totalStudentsDivContent + "</p>"
        );
        printWindow.document.write(
            "<p>" +
                selectedBatchYearElementOnPrint +
                totalStudentsPerYearElementOnPrint +
                "</p>"
        );

        // Display the table content
        printWindow.document.write(
            '<table class="table table-bordered table-hover text-center">' +
                $("#example").html() +
                "</table>"
        );

        // Display the summary report as of the current date

        printWindow.document.write("</body></html>");
        printWindow.document.close();

        // Trigger the print function on the new window or tab
        setTimeout(() => {
            printWindow.print();
        }, 1000);
    });

    $("#printButtonOnFinancial").click(function () {
        const printWindowOnFinancialReports = window.open("", "_blank");
        printWindowOnFinancialReports.document.write(
            "<html><head><title>Summary of Payments and Payables</title>"
        );
        printWindowOnFinancialReports.document.write("<style>");
        printWindowOnFinancialReports.document.write(
            "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
        );
        printWindowOnFinancialReports.document.write(
            "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
        );
        printWindowOnFinancialReports.document.write(
            "table, th, td { border: 1px solid black; padding: 10px; }"
        );
        printWindowOnFinancialReports.document.write(
            ".centered { text-align: center; }"
        );
        printWindowOnFinancialReports.document.write(
            ".logo { width: 150px; height: 50px; margin: 0 auto; }"
        );
        printWindowOnFinancialReports.document.write("</style></head><body>");

        // Header with logo and title
        printWindowOnFinancialReports.document.write(
            '<br><div class="centered">' +
                '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                "</div>"
        );
        printWindowOnFinancialReports.document.write(
            '<br><h4 class="centered">Summary of Payments and Payables</h4>'
        );

        if ($("#selected-batch-year-reports").length) {
            printWindowOnFinancialReports.document.write(
                '<span class="centered">' +
                    $("#selected-batch-year-reports").text() +
                    "</span><br>"
            );
        } else if ($("#all-batch-year-reports").length) {
            printWindowOnFinancialReports.document.write(
                '<span class="centered">' +
                    $("#all-batch-year-reports").text() +
                    "</span><br>"
            );
        }

        if ($("#dates-from-text-when-set").length) {
            // Date range
            printWindowOnFinancialReports.document.write(
                '<span class="centered">' +
                    $("#dates-from-text-when-set").text() +
                    " - " +
                    $("#dates-to-text-when-set").text() +
                    "</span><br>"
            );
        } else if ($("#dates-started").length && $("#date-current").length) {
            printWindowOnFinancialReports.document.write(
                '<span class="centered">' +
                    $("#dates-started").text() +
                    " - " +
                    $("#date-current").text() +
                    "</span><br>"
            );
        }

        // Display the table content
        printWindowOnFinancialReports.document.write(
            '<table class="table table-bordered table-hover text-center">' +
                $("#example2").html() +
                "</table>"
        );

        printWindowOnFinancialReports.document.write("</body></html>");
        printWindowOnFinancialReports.document.close();

        // Trigger the print function on the new window or tab
        setTimeout(() => {
            printWindowOnFinancialReports.print();
        }, 1000);
    });

    $("#printPayableSummary").click(function () {
        const printWindow = window.open("", "_blank");
        printWindow.document.write(
            "<html><head><title>Summary of Payables</title>"
        );
        printWindow.document.write("<style>");
        printWindow.document.write(
            "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
        );
        printWindow.document.write(
            "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
        );
        printWindow.document.write(
            "table, th, td { border: 1px solid black; padding: 10px; }"
        );
        printWindow.document.write(".centered { text-align: center; }");
        printWindow.document.write(
            ".logo { width: 150px; height: 50px; margin: 0 auto; }"
        );
        printWindow.document.write("</style></head><body>");

        // Header with logo and title
        printWindow.document.write(
            '<br><div class="centered">' +
                '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                "</div>"
        );
        printWindow.document.write(
            '<br><h4 class="centered">Summary of Payables</h4>'
        );

        if ($("#selected-batch-year-reports").length) {
            printWindow.document.write(
                '<span class="centered">' +
                    $("#selected-batch-year-reports").text() +
                    "</span><br>"
            );
        } else if ($("#all-batch-year-reports").length) {
            printWindow.document.write(
                '<span class="centered">' +
                    $("#all-batch-year-reports").text() +
                    "</span><br>"
            );
        }

        if ($("#dates-from-text-when-set").length) {
            // Date range
            printWindow.document.write(
                '<span class="centered">' +
                    $("#dates-from-text-when-set").text() +
                    " - " +
                    $("#dates-to-text-when-set").text() +
                    "</span><br>"
            );
        } else if ($("#dates-started").length && $("#date-current").length) {
            printWindow.document.write(
                '<span class="centered">' +
                    $("#dates-started").text() +
                    " - " +
                    $("#date-current").text() +
                    "</span><br>"
            );
        }

        // Calculate total from "Payables" column
        const totalPayables = $("#example2 tbody tr")
            .map(function () {
                return parseFloat(
                    $(this)
                        .find("td:eq(2)")
                        .text()
                        .replace(/[^0-9.-]+/g, "")
                );
            })
            .get()
            .reduce((total, value) => total + value, 0);

        // Display the table content with only "Name of Records" and "Payables" columns
        printWindow.document.write(
            '<table class="table table-bordered table-hover text-center">' +
                '<thead style="color:#1f3c88;" >' +
                "<tr>" +
                "<th>Name of Records</th>" +
                "<th>Payables</th>" +
                "</tr>" +
                "</thead>" +
                "<tbody>" +
                $("#example2 tbody tr")
                    .map(function () {
                        return (
                            "<tr>" +
                            "<td>" +
                            $(this).find("td:eq(0)").text() +
                            "</td>" +
                            "<td>" +
                            $(this).find("td:eq(2)").text() +
                            "</td>" +
                            "</tr>"
                        );
                    })
                    .get()
                    .join("") +
                "</tbody>" +
                '<tfoot style="color:#1f3c88">' +
                "<tr>" +
                "<th>Total</th>" +
                "<th>₱ " +
                totalPayables.toFixed(2) +
                "</th>" +
                "</tr>" +
                "</tfoot>" +
                "</table>"
        );

        printWindow.document.write("</body></html>");
        printWindow.document.close();

        // Trigger the print function on the new window or tab
        setTimeout(() => {
            printWindow.print();
        }, 1000);
    });

    $("#printPaymentSummary").click(function () {
        const printWindow = window.open("", "_blank");
        printWindow.document.write(
            "<html><head><title>Summary of Payments</title>"
        );
        printWindow.document.write("<style>");
        printWindow.document.write(
            "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
        );
        printWindow.document.write(
            "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
        );
        printWindow.document.write(
            "table, th, td { border: 1px solid black; padding: 10px; }"
        );
        printWindow.document.write(".centered { text-align: center; }");
        printWindow.document.write(
            ".logo { width: 150px; height: 50px; margin: 0 auto; }"
        );
        printWindow.document.write("</style></head><body>");

        // Header with logo and title
        printWindow.document.write(
            '<br><div class="centered">' +
                '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                "</div>"
        );
        printWindow.document.write(
            '<br><h4 class="centered">Summary of Payments</h4>'
        );

        if ($("#selected-batch-year-reports").length) {
            printWindow.document.write(
                '<span class="centered">' +
                    $("#selected-batch-year-reports").text() +
                    "</span><br>"
            );
        } else if ($("#all-batch-year-reports").length) {
            printWindow.document.write(
                '<span class="centered">' +
                    $("#all-batch-year-reports").text() +
                    "</span><br>"
            );
        }

        if ($("#dates-from-text-when-set").length) {
            // Date range
            printWindow.document.write(
                '<span class="centered">' +
                    $("#dates-from-text-when-set").text() +
                    " - " +
                    $("#dates-to-text-when-set").text() +
                    "</span><br>"
            );
        } else if ($("#dates-started").length && $("#date-current").length) {
            printWindow.document.write(
                '<span class="centered">' +
                    $("#dates-started").text() +
                    " - " +
                    $("#date-current").text() +
                    "</span><br>"
            );
        }

        // Calculate total from "Payments" column
        const totalPayments = $("#example2 tbody tr")
            .map(function () {
                return parseFloat(
                    $(this)
                        .find("td:eq(1)")
                        .text()
                        .replace(/[^0-9.-]+/g, "")
                );
            })
            .get()
            .reduce((total, value) => total + value, 0);

        // Display the table content with only "Name of Records" and "Payments" columns
        printWindow.document.write(
            '<table class="table table-bordered table-hover text-center">' +
                '<thead style="color:#1f3c88;">' +
                "<tr>" +
                "<th>Name of Records</th>" +
                "<th>Payments</th>" +
                "</tr>" +
                "</thead>" +
                "<tbody>" +
                $("#example2 tbody tr")
                    .map(function () {
                        return (
                            "<tr>" +
                            "<td>" +
                            $(this).find("td:eq(0)").text() +
                            "</td>" +
                            "<td>" +
                            $(this).find("td:eq(1)").text() +
                            "</td>" +
                            "</tr>"
                        );
                    })
                    .get()
                    .join("") +
                "</tbody>" +
                '<tfoot style="color:#1f3c88;">' +
                "<tr>" +
                "<th>Total</th>" +
                "<th>₱ " +
                totalPayments.toFixed(2) +
                "</th>" +
                "</tr>" +
                "</tfoot>" +
                "</table>"
        );

        printWindow.document.write("</body></html>");
        printWindow.document.close();

        // Trigger the print function on the new window or tab
        setTimeout(() => {
            printWindow.print();
        }, 1000);
    });

    $(".printButtonOnAcademicReports").click(function () {
        const printWindowOnAcademicReports = window.open("", "_blank");
        printWindowOnAcademicReports.document.write(
            "<html><head><title>Academic Report</title>"
        );
        printWindowOnAcademicReports.document.write("<style>");
        printWindowOnAcademicReports.document.write(
            "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
        );
        printWindowOnAcademicReports.document.write(
            "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
        );
        printWindowOnAcademicReports.document.write(
            "table, th, td { border: 1px solid black; padding: 10px; }"
        );
        printWindowOnAcademicReports.document.write(
            ".centered { text-align: center; }"
        );
        printWindowOnAcademicReports.document.write(
            ".logo { width: 150px; height: 50px; margin: 0 auto; }"
        );
        printWindowOnAcademicReports.document.write(
            "</style></head><body> " +
                '<br><div class="centered">' +
                '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                "</div>"
        );

        printWindowOnAcademicReports.document.write(
            '<h4 class="centered" style="margin-top:20px">CERTIFICATE IN COMPUTER TECHNOLOGY</h4>' +
                "<p>Effective SY: " +
                $("#user_joined_year_int").val() +
                " - " +
                $("#user_joined_effective_year").val() +
                "</p>"
        );

        printWindowOnAcademicReports.document.write(
            '<br><br><span class="centered">' +
                $(".student_name_academic").val() +
                "</span><br><br><br>"
        );

        printWindowOnAcademicReports.document.write(
            '<p class="text-disp"><b>General Point Average Per Semester</b></p><br>'
        );

        // Display the table content
        printWindowOnAcademicReports.document.write(
            '<table class="table table-bordered table-hover text-center">' +
                $("#academic-table-report").html() +
                "</table>"
        );

        printWindowOnAcademicReports.document.write(
            '<p class="text-disp centered" style="font-size: 12px"><i>Note:</i> This record presents only the general ' +
                "point average per semester and your general weighted average. For further details, please open" +
                ' your <a href="https://ismis.usc.edu.ph/Account/Login?ReturnUrl=%2F"' +
                ' style="text-decoration: #1f3c88" title="USC-ISMIS Link">ISMIS</a> account.</p>'
        );

        printWindowOnAcademicReports.document.write(
            '<br><p class="centered">' +
                "General Weighted Average: " +
                $("#total_gpa_acad").val() +
                "</p><br>"
        );

        printWindowOnAcademicReports.document.write("</body></html>");
        printWindowOnAcademicReports.document.close();

        // Trigger the print function on the new window or tab
        setTimeout(() => {
            printWindowOnAcademicReports.print();
        }, 1000);
    });

    $(".viewSummaryAcad").click(function () {
        const printWindowOnAcademicReports = window.open("", "_blank");
        printWindowOnAcademicReports.document.write(
            "<html><head><title>Academic Report</title>"
        );
        printWindowOnAcademicReports.document.write("<style>");
        printWindowOnAcademicReports.document.write(
            "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
        );
        printWindowOnAcademicReports.document.write(
            "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
        );
        printWindowOnAcademicReports.document.write(
            "table, th, td { border: 1px solid black; padding: 10px; }"
        );
        printWindowOnAcademicReports.document.write(
            ".centered { text-align: center; }"
        );
        printWindowOnAcademicReports.document.write(
            ".logo { width: 150px; height: 50px; margin: 0 auto; }"
        );
        printWindowOnAcademicReports.document.write(
            "</style></head><body>" +
                '<br><div class="centered">' +
                '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                "</div>"
        );

        printWindowOnAcademicReports.document.write(
            '<br><h4 class="centered" style="margin-top:20px">CERTIFICATE IN COMPUTER TECHNOLOGY</h4>' +
                "<p>Effective SY: " +
                $("#user_joined_year_int").val() +
                " - " +
                $("#user_joined_effective_year").val() +
                "</p>"
        );

        printWindowOnAcademicReports.document.write(
            '<br><br><span class="centered">' +
                $(".student_name_academic").val() +
                "</span><br><br><br>"
        );

        printWindowOnAcademicReports.document.write(
            '<p class="text-disp"><b>General Point Average Per Semester</b></p><br>'
        );

        // Display the table content
        printWindowOnAcademicReports.document.write(
            '<table class="table table-bordered table-hover text-center">' +
                $("#academic-table-report").html() +
                "</table>"
        );

        printWindowOnAcademicReports.document.write(
            '<p class="text-disp centered" style="font-size: 12px"><i>Note:</i> This record presents only the general ' +
                "point average per semester and your general weighted average. For further details, please open" +
                ' your <a href="https://ismis.usc.edu.ph/Account/Login?ReturnUrl=%2F"' +
                ' style="text-decoration: #1f3c88" title="USC-ISMIS Link">ISMIS</a> account.</p>'
        );

        printWindowOnAcademicReports.document.write(
            '<br><p class="centered">' +
                "General Weighted Average: " +
                $("#gwa_hidden_input").val() +
                "</p><br>"
        );

        printWindowOnAcademicReports.document.write("</body></html>");
        printWindowOnAcademicReports.document.close();

        // Trigger the print function on the new window or tab
        setTimeout(() => {
            printWindowOnAcademicReports.print();
        }, 1000);
    });

    $(".printButtonOnAdminAcademicReports").click(function () {
        const printWindowOnAcademicReports = window.open("", "_blank");
        printWindowOnAcademicReports.document.write(
            "<html><head><title>Academic Report</title>"
        );
        printWindowOnAcademicReports.document.write("<style>");
        printWindowOnAcademicReports.document.write(
            "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
        );
        printWindowOnAcademicReports.document.write(
            "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
        );
        printWindowOnAcademicReports.document.write(
            "table, th, td { border: 1px solid black; padding: 10px; }"
        );
        printWindowOnAcademicReports.document.write(
            ".centered { text-align: center; }"
        );
        printWindowOnAcademicReports.document.write(
            ".logo { width: 150px; height: 50px; margin: 0 auto; }"
        );
        printWindowOnAcademicReports.document.write(
            "</style></head><body>" +
                '<br><div class="centered">' +
                '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                "</div>"
        );

        printWindowOnAcademicReports.document.write(
            '<h4 class="centered" style="margin-top:20px">CERTIFICATE IN COMPUTER TECHNOLOGY</h4>' +
                "<p>Effective SY: " +
                $("#user_joined_year_int").val() +
                " - " +
                $("#user_joined_effective_year").val() +
                "</p>"
        );

        printWindowOnAcademicReports.document.write(
            '<br><br><span class="centered">' +
                $(".student_name_academic").val() +
                "</span><br><br><br>"
        );

        printWindowOnAcademicReports.document.write(
            '<p class="text-disp"><b>General Point Average Per Semester</b></p><br>'
        );

        // Display the table content
        printWindowOnAcademicReports.document.write(
            '<table class="table table-bordered table-hover text-center">' +
                $("#academic-table-report").html() +
                "</table>"
        );

        printWindowOnAcademicReports.document.write(
            '<p class="text-disp centered" style="font-size: 12px"><i>Note:</i> This record presents only the general ' +
                "point average per semester and your general weighted average. For further details, please open" +
                ' your <a href="https://ismis.usc.edu.ph/Account/Login?ReturnUrl=%2F"' +
                ' style="text-decoration: #1f3c88" title="USC-ISMIS Link">ISMIS</a> account.</p>'
        );

        printWindowOnAcademicReports.document.write(
            '<br><p class="centered">' +
                "General Weighted Average: " +
                $("#total_gpa_acad").val() +
                "</p><br>"
        );

        printWindowOnAcademicReports.document.write("</body></html>");
        printWindowOnAcademicReports.document.close();

        // Trigger the print function on the new window or tab
        setTimeout(() => {
            printWindowOnAcademicReports.print();
        }, 1000);
    });

    $(".printButtonOnDisciplinaryReports").click(function () {
        const printButtonOnDisciplinaryReports = window.open("", "_blank");
        printButtonOnDisciplinaryReports.document.write(
            "<html><head><title>Disciplinary Report</title>"
        );
        printButtonOnDisciplinaryReports.document.write("<style>");
        printButtonOnDisciplinaryReports.document.write(
            "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
        );
        printButtonOnDisciplinaryReports.document.write(
            "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
        );
        printButtonOnDisciplinaryReports.document.write(
            "table, th, td { border: 1px solid black; padding: 10px; }"
        );
        printButtonOnDisciplinaryReports.document.write(
            ".centered { text-align: center; }"
        );
        printButtonOnDisciplinaryReports.document.write(
            ".logo { width: 150px; height: 50px; margin: 0 auto; }"
        );
        printButtonOnDisciplinaryReports.document.write(
            "</style></head><body>" +
                '<br><div class="centered">' +
                '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                "</div>"
        );

        printButtonOnDisciplinaryReports.document.write(
            '<h4 class="centered" style="margin-top:20px">Disciplinary Records</h4>' +
                "</p>"
        );

        printButtonOnDisciplinaryReports.document.write(
            '<span class="centered">' +
                $(".student_name_academic").val() +
                "</span><br>"
        );

        // Display the table content
        printButtonOnDisciplinaryReports.document.write(
            '<table class="table table-bordered table-hover text-center">' +
                $("#disciplinary-report-table").html() +
                "</table>"
        );

        printButtonOnDisciplinaryReports.document.write("</body></html>");
        printButtonOnDisciplinaryReports.document.close();

        // Trigger the print function on the new window or tab
        setTimeout(() => {
            printButtonOnDisciplinaryReports.print();
        }, 1000);
    });

    $(".printButtonOnAdminDisciplinaryReports").click(function () {
        // Open a new window for printing
        const printWindow = window.open("", "_blank");

        // Write HTML and styles for the printing content
        printWindow.document.write(
            "<html><head><title>Disciplinary Report</title>"
        );
        printWindow.document.write("<style>");
        printWindow.document.write(
            "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
        );
        printWindow.document.write(
            "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
        );
        printWindow.document.write(
            "table, th, td { border: 1px solid black; padding: 10px; }"
        );
        printWindow.document.write(".centered { text-align: center; }");
        printWindow.document.write(
            ".logo { width: 150px; height: 50px; margin: 0 auto; }"
        );
        printWindow.document.write(
            "</style></head><body>" +
                '<br><div class="centered">' +
                '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                "</div>"
        );

        printWindow.document.write(
            '<h4 class="centered" style="margin-top:20px">Disciplinary Records</h4></p>'
        );

        // Display the data from the modal
        printWindow.document.write(
            '<span class="centered">' +
                $(".first_name_edit").text() +
                " " +
                $(".last_name_edit").text() +
                "</span><br>"
        );

        // Create a table for disciplinary records
        printWindow.document.write('<table class="centered">');
        printWindow.document.write(
            "<thead><tr><th>Title</th><th>Date</th><th>Description</th></tr></thead><tbody>"
        );

        // Add rows with disciplinary records
        printWindow.document.write("<tr>");
        printWindow.document.write("<td>Verbal Warning</td>");
        printWindow.document.write(
            "<td>" + $("#verbal_warning_date").val() + "</td>"
        );
        printWindow.document.write(
            "<td>" + $("#verbal_warning_description").val() + "</td>"
        );
        printWindow.document.write("</tr>");

        printWindow.document.write("<tr>");
        printWindow.document.write("<td>Written Warning</td>");
        printWindow.document.write(
            "<td>" + $("#written_warning_date").val() + "</td>"
        );
        printWindow.document.write(
            "<td>" + $("#written_warning_description").val() + "</td>"
        );
        printWindow.document.write("</tr>");

        printWindow.document.write("<tr>");
        printWindow.document.write("<td>Probationary Warning</td>");
        printWindow.document.write(
            "<td>" + $("#provisionary_date").val() + "</td>"
        );
        printWindow.document.write(
            "<td>" + $("#provisionary_description").val() + "</td>"
        );
        printWindow.document.write("</tr>");

        printWindow.document.write("</tbody></table>");

        printWindow.document.write("</body></html>");
        printWindow.document.close();

        // Trigger the print function on the new window or tab
        setTimeout(() => {
            printWindow.print();
        }, 1000);
    });

    $(".printButtonOnCOA").click(function () {
        const currentDateOnCOA = new Date();
        const formattedDateOnCOA = currentDateOnCOA.toLocaleDateString(
            "en-US",
            { month: "long", day: "numeric", year: "numeric" }
        );

        const printWindowOnCOA = window.open("", "_blank");
        printWindowOnCOA.document.write(
            "<html><head><title>Closing Of Accounts Records</title>"
        );
        printWindowOnCOA.document.write("<style>");
        printWindowOnCOA.document.write(
            "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
        );
        printWindowOnCOA.document.write(
            "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
        );
        printWindowOnCOA.document.write(
            "table, th, td { border: 1px solid black; padding: 10px; }"
        );
        printWindowOnCOA.document.write(".centered { text-align: center; }");
        printWindowOnCOA.document.write(
            ".logo { width: 150px; height: 50px; margin: 0 auto; }"
        );
        printWindowOnCOA.document.write("</style></head><body>");

        // Header with logo and title
        printWindowOnCOA.document.write(
            '<br><div class="centered">' +
                '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                "</div>"
        );
        printWindowOnCOA.document.write(
            '<br><h4 class="centered">Closing of Accounts Records<h4>'
        );
        printWindowOnCOA.document.write(
            '<p class="centered">' + "as of " + formattedDateOnCOA + "</p><br>"
        );

        // Display the table content
        printWindowOnCOA.document.write(
            '<table class="table table-bordered table-hover text-center">' +
                $("#exampleOnCOA").html() +
                "</table>"
        );

        printWindowOnCOA.document.write("</body></html>");
        printWindowOnCOA.document.close();

        // Trigger the print function on the new window or tab
        setTimeout(() => {
            printWindowOnCOA.print();
        }, 1000);
    });
});

$(document).ready(function () {
    function generateAndPrintSummaryReport() {
        // Check the value right before you need it
        if ($(".receipt_true").val() == 1) {
            const printWindowOnStudentCounterpart = window.open("", "_blank");
            if (!printWindowOnStudentCounterpart) {
                return;
            }
            printWindowOnStudentCounterpart.document.write(
                "<html><head><title>Print Acknowledgement Receipt</title>"
            );
            printWindowOnStudentCounterpart.document.write("<style>");
            printWindowOnStudentCounterpart.document.write(
                "* { font-family: Arial, sans-serif; text-align: center; margin: 0 auto; }"
            );
            printWindowOnStudentCounterpart.document.write(
                "table { border-collapse: collapse; width: 80%; margin: 20px auto; }"
            );
            printWindowOnStudentCounterpart.document.write(
                "table, th, td { border: 1px solid black; padding: 10px; }"
            );
            printWindowOnStudentCounterpart.document.write(
                ".centered { text-align: center; }"
            );
            printWindowOnStudentCounterpart.document.write(
                ".logo { width: 150px; height: 50px; margin: 0 auto; }"
            );
            printWindowOnStudentCounterpart.document.write(
                "</style></head><body><div style='padding-left:100px; padding-right:100px'>"
            );

            // Header with logo and title
            printWindowOnStudentCounterpart.document.write(
                '<div style="text-align: left; margin-top:20px;">' +
                    '<img src="https://i.ibb.co/1fRXrc4/PN-Logo-English-Blue-Baseline-1-removebg-preview.png" class="logo">' +
                    "</div>" +
                    '<div style="text-align: center; margin-top:50px;">' +
                    "<h4>PASSERELLES NUMERIQUES PHILIPPINES FOUNDATION INC.</h4>" +
                    "<h6>The Bird Building, New Era St., Baranggay Luz, Cebu City, Philippines</h6>" +
                    "<h4 style='margin-top:20px;'>Acknowledgement Receipt</h4>" +
                    "</div>"
            );

            printWindowOnStudentCounterpart.document.write(
                '<div style="display: flex; justify-content: space-between; margin-top:20px;">' +
                    '<div style="text-align: left;">' +
                    '<p style="text-align:left;">' +
                    "To Whom It May Concern:" +
                    "</p>" +
                    "</div>" +
                    '<div style="text-align: right;">' +
                    '<p class="text-right">' +
                    "Date: " +
                    "<u><b>" +
                    $(".date_of_transaction").val() +
                    "</u></b>" +
                    "</p>" +
                    "</div>" +
                    "</div><br>"
            );

            printWindowOnStudentCounterpart.document.write(
                '<p style="text-align: justify; text-indent: 20px; padding:10px">' +
                    "This is to acknowledge receipt from " +
                    "<u><b>" +
                    $(".student_name").val() +
                    "</b></u>" +
                    " the amount of " +
                    "<u><b>" +
                    $(".amount_paid_in_words").val() +
                    " pesos only (PHP " +
                    $(".amount_paid_receipt").val() +
                    ")" +
                    "</u></b>" +
                    " as payment for " +
                    "<u><b>" +
                    $(".category").val() +
                    "</u></b>" +
                    ".</p><br><br><br>"
            );

            printWindowOnStudentCounterpart.document.write(
                '<div style="display: flex; justify-content: space-between; float:right; text-align: right; ">' +
                    '<div style="text-align: right; ">' +
                    "Payment Received By:<br><br><br>" +
                    '<span style="display: block;">' +
                    "<u><b>" +
                    $(".current_user_name").val() +
                    "</u></b>" +
                    "</span>" +
                    '<span style="display: block;">Admin and Finance Staff</span>' +
                    "</div>" +
                    // '<div style="text-align: center;  margin-top: 52px">' +
                    // '<span style="display: block;">' +
                    // "<u><b>" +
                    // $(".student_name").val() +
                    // "</u></b>" +
                    // "</span>" +
                    // '<span style="display: block;">Name and Signature Payee</span>' +
                    // "</div>" +
                    "</div>"
            );

            printWindowOnStudentCounterpart.document.write(
                "</div></body></html>"
            );
            printWindowOnStudentCounterpart.document.close();
            setTimeout(() => {
                printWindowOnStudentCounterpart.print();
            }, 500);

            // const student_id_on_pc = $("#student_id_on_pc").val();

            // window.location.href = "/counterpart-records/" + student_id_on_pc;
        }
    }

    // Use setTimeout to ensure that the content is fully loaded
    setTimeout(function () {
        generateAndPrintSummaryReport();
    }, 100); // Adjust the timeout duration as needed
});

$(document).ready(function () {
    $("#salutation").change(function () {
        var otherSalutationInput = $("#otherSalutation");
        otherSalutationInput.toggle(this.value == "3");
    });

    $("#conclusion_salutation").change(function () {
        var otherSalutationInput = $("#otherConclusionSalutation");
        otherSalutationInput.toggle(this.value == "11");
    });
});

$(document).ready(function () {
    $("#previewLink").click(function () {
        // Remove existing error messages
        $(".error-message").remove();

        // Validate the form
        if (!validateCustomizedEmailForm()) {
            return;
        }

        // Map the selected values to their corresponding text content
        var salutationText = getSalutationText($("#salutation").val());
        var conclusionSalutationText = getConclusionSalutationText(
            $("#conclusion_salutation").val()
        );

        // Set content in the modal
        $("#previewSubject").text($("#subject").val());
        $("#previewSalutation").text(
            salutationText + " Batch " + $("#batch_year_selected").val() + ","
        );
        $("#previewMessage").text($("#message").val());
        $("#previewConclusionSalutation").text(conclusionSalutationText);
        $("#previewSender").text($("#sender").val());

        var attachmentInput = $("#attachment")[0];
        if (attachmentInput.files.length > 0) {
            var attachmentName = attachmentInput.files[0].name;
            var attachmentLink;
            // var fileExtension = attachmentName.split(".").pop().toLowerCase();

            // if (fileExtension) {
            attachmentLink = '<p class="text-muted">' + attachmentName + "</p>";
            // }
            $("#previewAttachment").html("Attachment: " + attachmentLink);
        } else {
            $("#previewAttachment").empty();
        }

        $("#previewModal").modal("show");
    });

    // Function to get the text content for salutation based on the selected value
    function getSalutationText(selectedValue) {
        switch (selectedValue) {
            case "0":
                return "Hi";
            case "1":
                return "Hello";
            case "2":
                return "Dear";
            case "3":
                return $("#otherSalutation").val(); // Assuming 'otherSalutation' is the ID of the input field
            default:
                return "";
        }
    }

    // Function to get the text content for conclusion salutation based on the selected value
    function getConclusionSalutationText(selectedValue) {
        switch (selectedValue) {
            case "0":
                return "Sincerely";
            case "1":
                return "Yours truly";
            case "2":
                return "Yours Sincerely";
            case "3":
                return "Regards";
            case "4":
                return "Kind Regards";
            case "5":
                return "Warm regards";
            case "6":
                return "Respectfully";
            case "7":
                return "Best wishes";
            case "8":
                return "Yours";
            case "9":
                return "Very truly yours";
            case "10":
                return "Best regards";
            case "11":
                return $("#otherConclusionSalutation").val();
            default:
                return "";
        }
    }
});

function validateCustomizedEmailForm() {
    var isValid = true;
    $(".error-message").remove();

    // Validate 'To' field
    if ($("#batch_year_selected").val() === "") {
        displayError($("#batch_year_selected"), "Please select a batch year.");
        isValid = false;
    }

    // Validate 'Subject' field
    if ($("#subject").val().trim() === "") {
        displayError($("#subject"), "Please enter a subject.");
        isValid = false;
    }

    // Validate 'Message' field
    if ($("#message").val().trim() === "") {
        displayError($("#message"), "Please enter a message.");
        isValid = false;
    }

    // Validate 'Conclusion' field
    if (
        $("#conclusion_salutation").val() === "3" &&
        $("#otherConclusionSalutation").val().trim() === ""
    ) {
        displayError(
            $("#otherConclusionSalutation"),
            "Please enter a conclusion salutation."
        );
        isValid = false;
    }

    // Validate 'Sender' field
    if ($("#sender").val().trim() === "") {
        displayError($("#sender"), "Please enter the sender.");
        isValid = false;
    }

    // Validate 'Salutation' field
    if ($("#salutation").val() == "3") {
        var otherSalutation = $("#otherSalutation").val().trim() === "";
        if (otherSalutation) {
            displayError(
                $("#otherSalutation"),
                "Please enter the other salutation."
            );
            isValid = false;
        }
    }

    // Check if conclusion is "Other" and validate the input
    if ($("#conclusion_salutation").val() == "11") {
        var otherConclusionSalutation =
            $("#otherConclusionSalutation").val().trim() === "";
        if (otherConclusionSalutation) {
            displayError(
                $("#otherConclusionSalutation"),
                "Please enter the other conclusion salutation."
            );
            isValid = false;
        }
    }

    // Validate 'Attachment' field (if needed)
    // You can customize this validation based on your requirements

    return isValid;
}

// Function to display an error message below an input field
function displayError(element, message) {
    var errorElement = $(
        '<div class="error-message" style="color: red; font-size: 12px;">' +
            message +
            "</div>"
    );
    element.parent().append(errorElement);
}

$(document).ready(function () {
    const date_form = $("#date-form");
    const date_from_input = $("#date-from");
    const date_to_input = $("#date-to");
    const filter_button = $("#filter-submit");

    const date_from_error = $(
        '<div class="error-message" style="color: red;"></div>'
    ).insertAfter(date_from_input);
    const date_to_error = $(
        '<div class="error-message" style="color: red;"></div>'
    ).insertAfter(date_to_input);

    filter_button.click(function () {
        let isValid = true;

        if (date_from_input.val() == null) {
            date_from_error.text("Please select a From date.").show();
            isValid = false;
        } else {
            date_from_error.hide();
        }

        if (date_to_input.val() == null) {
            date_to_error.text("Please select a To date.").show();
            isValid = false;
        } else {
            date_to_error.hide();
        }

        if (isValid) {
            date_form.submit(function (e) {
                e.preventDefault;
                var loadingOverlay1 = $(".loading-spinner-overlay");
                let successNotificationShown = false;

                function showLoadingSpinner() {
                    loadingOverlay1.show();
                    $("body").css("overflow", "hidden");
                }

                function hideLoadingSpinner() {
                    loadingOverlay1.hide();
                    $("body").css("overflow", "auto");
                }

                if (!validateCustomizedEmailForm()) {
                    submitButton.prop("disabled", false); // Re-enable the button
                    return;
                }

                showLoadingSpinner();

                var formData = new FormData(this);
                formData.append("attachment", $("#attachment")[0].files[0]);

                $.ajax({
                    url: $(this).attr("action"),
                    type: $(this).attr("method"),
                    data: formData,
                    processData: false, // Important! Prevent $ from processing the data
                    contentType: false, // Important! Specify content type as false for FormData

                    success: function (response) {
                        toastr.success("Success");
                    },
                    error: function (error) {
                        hideLoadingSpinner();
                        console.log(error);
                        toastr.error("An error occurred, please try again.");
                    },
                    complete: function () {
                        submitButton.prop("disabled", false);
                    },
                });
            });
        }
    });
});

$(document).ready(function () {
    $(".printButton").click(function () {
        // const filters = $(".filters");
        // const buttons = $(".buttons");

        // filters.hide();
        // buttons.hide();

        window.print();
    });
});
