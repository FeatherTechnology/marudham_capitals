document.addEventListener('DOMContentLoaded', () => {


    initializeCounterAnimation();
    getBranchList();
    localStorage.clear();//clear localstorage before fetching data for prevent conflict

    $('#branch_id, #filter_month').change(function () {
        let opened = $('.card-body:visible').prev('.card-header').attr('id');
        $('#' + opened).trigger('click');
        showOverlay();
        setTimeout(() => {
            $('#' + opened).trigger('click');
            hideOverlay();
        }, 1000);

    });

    $('#req_title').click(function () {
        let check = $('#req_body');
        check.find('.card-body').show();//show the card body of this title
        if (check.is(':visible')) {
            check.slideUp();
        } else {
            getRequestDashboard();
            check.slideDown();
            $('.card-body').not('#req_body').not($('#req_body').find('.card-body')).slideUp();//hide the card body other than this card
        }
    });
    $('#ver_title').click(function () {
        let check = $('#ver_body');
        check.find('.card-body').show();//show the card body of this title
        if (check.is(':visible')) {
            check.slideUp();
        } else {
            getVerificationDashboard();
            check.slideDown();
            $('.card-body').not('#ver_body').not($('#ver_body').find('.card-body')).slideUp();//hide the card body other than this card
        }
    });
    $('#app_title').click(function () {
        let check = $('#app_body');
        check.find('.card-body').show();//show the card body of this title
        if (check.is(':visible')) {
            check.slideUp();
        } else {
            getApprovalDashboard();
            check.slideDown();
            $('.card-body').not('#app_body').not($('#app_body').find('.card-body')).slideUp();//hide the card body other than this card
        }
    });
    $('#ack_title').click(function () {
        let check = $('#ack_body');
        check.find('.card-body').show();//show the card body of this title
        if (check.is(':visible')) {
            check.slideUp();
        } else {
            getAcknowledgmentDashboard();
            check.slideDown();
            $('.card-body').not('#ack_body').not($('#ack_body').find('.card-body')).slideUp();//hide the card body other than this card
        }
    });
    $('#li_title').click(function () {
        let check = $('#li_body');
        check.find('.card-body').show();//show the card body of this title
        if (check.is(':visible')) {
            check.slideUp();
        } else {
            getLoanIssueDashboard();
            check.slideDown();
            $('.card-body').not('#li_body').not($('#li_body').find('.card-body')).slideUp();//hide the card body other than this card
        }
    });
    $('#col_title').click(function () {
        let check = $('#col_body');
        check.find('.card-body').show();//show the card body of this title
        if (check.is(':visible')) {
            check.slideUp();
        } else {
            getCollectionDashboard();
            check.slideDown();
            $('.card-body').not('#col_body').not($('#col_body').find('.card-body')).slideUp();//hide the card body other than this card
        }
        $('#col_chart_part').slideUp();
    });
    $('#cl_title').click(function () {
        let check = $('#cl_body');
        check.find('.card-body').show();//show the card body of this title
        if (check.is(':visible')) {
            check.slideUp();
        } else {
            getClosedDashboard();
            check.slideDown();
            $('.card-body').not('#cl_body').not($('#cl_body').find('.card-body')).slideUp();//hide the card body other than this card
        }
    });
    $('#noc_title').click(function () {
        let check = $('#noc_body');
        check.find('.card-body').show();//show the card body of this title
        if (check.is(':visible')) {
            check.slideUp();
        } else {
            getNOCDashboard();
            check.slideDown();
            $('.card-body').not('#noc_body').not($('#noc_body').find('.card-body')).slideUp();//hide the card body other than this card
        }
    });


});


function getBranchList() {
    $.post('dashboardFile/getBranchList.php', function (data) {
        $('#branch_id').empty();
        $('#branch_id').append('<option value="">Choose Branch</option>');
        $('#branch_id').append('<option value="0">All Branch</option>');
        for (let i = 0; i < data.length; i++) {
            $('#branch_id').append('<option value="' + data[i].branch_id + '">' + data[i].branch_name + '</option>');
        }
    }, 'json')
}

function getSubAreaList(branch_id) {

    return new Promise((resolve, reject) => {
        if (branch_id != '') {
            $.ajax({
                url: 'dashboardFile/getSubAreaList.php',
                type: 'POST',
                data: { branch_id },
                dataType: 'json',
                success: function (data) {
                    //convert json array to string to return as string if not empty and then return to calling function
                    if (data != 'Error') {
                        let sub_area_list = data;
                        $('#sub_area_list').val(sub_area_list);
                        resolve(sub_area_list);
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'No Sub Area for this Branch!',
                            icon: 'error',
                            confirmButtonColor: '#009688',
                        }).then(function () {
                            $('#branch_id').val('');
                        })
                        resolve('');
                    }
                }
            });
        } else {
            resolve('');
        }
    })

}



// *****************************************************************************************************************************************
//Request Dashboard initalization
function getRequestDashboard() {

    let branch_id = $('#branch_id').val();
    let month = $('#filter_month').val();
    localStorage.clear();//clear localstorage before fetching data for prevent conflict
    getSubAreaList(branch_id).then(sub_area_list => {

        $.post('dashboardFile/getRequestDashboard.php', { sub_area_list, month }, function (data) {

            $('#tot_req').text(data.tot_req)
            $('#tot_req_issue').text(data.tot_issue)
            $('#tot_req_bal').text(data.tot_balance)
            $('#today_req').text(data.today_req)
            $('#today_req_issue').text(data.today_issue)
            $('#today_req_bal').text(data.today_balance)

            localStorage.setItem('tot_cancel', data.tot_cancel);
            localStorage.setItem('tot_revoke', data.tot_revoke);
            localStorage.setItem('today_cancel', data.today_cancel);
            localStorage.setItem('today_revoke', data.today_revoke);

            localStorage.setItem('tot_new', data.tot_new);
            localStorage.setItem('tot_existing', data.tot_existing);
            localStorage.setItem('today_new', data.today_new);
            localStorage.setItem('today_existing', data.today_existing);


            $('input[name="req_radio"]').trigger('change');//trigger at start

        }, 'json');

        $('input[name="req_radio"]').change(function () {
            let selectedValue = $('input[name="req_radio"]:checked').next().text().trim();
            $('#req_tot_chart,#req_today_chart').empty();


            if (selectedValue == 'Cancel & Revoke') {
                let tot_cancel = localStorage.getItem('tot_cancel');
                let tot_revoke = localStorage.getItem('tot_revoke');
                let today_cancel = localStorage.getItem('today_cancel');
                let today_revoke = localStorage.getItem('today_revoke');

                tot_cr_chart(tot_cancel, tot_revoke, 'req_tot_chart');
                tdy_cr_chart(today_cancel, today_revoke, 'req_today_chart');
            } else if (selectedValue == 'Customer Type') {
                let tot_new = localStorage.getItem('tot_new');
                let tot_existing = localStorage.getItem('tot_existing');
                let today_new = localStorage.getItem('today_new');
                let today_existing = localStorage.getItem('today_existing');

                tot_ct_chart(tot_new, tot_existing, 'req_tot_chart');
                tdy_ct_chart(today_new, today_existing, 'req_today_chart');

            }
        });
    })

}

// *****************************************************************************************************************************************

function getVerificationDashboard() {
    let branch_id = $('#branch_id').val();
    let month = $('#filter_month').val();
    localStorage.clear();//clear localstorage before fetching data for prevent conflict
    getSubAreaList(branch_id).then(sub_area_list => {

        $.post('dashboardFile/getVerificationDashboard.php', { sub_area_list, month }, function (data) {

            $('#tot_in_ver').text(data.tot_in_ver)
            $('#tot_ver_issue').text(data.tot_issue)
            $('#tot_ver_bal').text(data.tot_balance)
            $('#today_in_ver').text(data.today_in_ver)
            $('#today_ver_issue').text(data.today_issue)
            $('#today_ver_bal').text(data.today_balance)

            localStorage.setItem('tot_cancel', data.tot_cancel);
            localStorage.setItem('tot_revoke', data.tot_revoke);
            localStorage.setItem('today_cancel', data.today_cancel);
            localStorage.setItem('today_revoke', data.today_revoke);

            localStorage.setItem('tot_new', data.tot_new);
            localStorage.setItem('tot_existing', data.tot_existing);
            localStorage.setItem('today_new', data.today_new);
            localStorage.setItem('today_existing', data.today_existing);


            $('input[name="ver_radio"]').trigger('change');//trigger at start

        }, 'json');

        $('input[name="ver_radio"]').change(function () {
            let selectedValue = $('input[name="ver_radio"]:checked').next().text().trim();
            $('#ver_tot_chart,#ver_today_chart').empty();


            if (selectedValue == 'Cancel & Revoke') {
                let tot_cancel = localStorage.getItem('tot_cancel');
                let tot_revoke = localStorage.getItem('tot_revoke');
                let today_cancel = localStorage.getItem('today_cancel');
                let today_revoke = localStorage.getItem('today_revoke');

                tot_cr_chart(tot_cancel, tot_revoke, 'ver_tot_chart');
                tdy_cr_chart(today_cancel, today_revoke, 'ver_today_chart');
            } else if (selectedValue == 'Customer Type') {
                let tot_new = localStorage.getItem('tot_new');
                let tot_existing = localStorage.getItem('tot_existing');
                let today_new = localStorage.getItem('today_new');
                let today_existing = localStorage.getItem('today_existing');

                tot_ct_chart(tot_new, tot_existing, 'ver_tot_chart');
                tdy_ct_chart(today_new, today_existing, 'ver_today_chart');

            }
        });
    });
}

// *****************************************************************************************************************************************

function getApprovalDashboard() {
    let branch_id = $('#branch_id').val();
    let month = $('#filter_month').val();
    localStorage.clear();//clear localstorage before fetching data for prevent conflict
    getSubAreaList(branch_id).then(sub_area_list => {

        $.post('dashboardFile/getApprovalDashboard.php', { sub_area_list, month }, function (data) {

            $('#tot_in_app').text(data.tot_in_app)
            $('#tot_app_issue').text(data.tot_issue)
            $('#tot_app_bal').text(data.tot_app_bal)
            $('#today_in_app').text(data.today_in_app)
            $('#today_app_issue').text(data.today_issue)
            $('#today_app_bal').text(data.today_app_bal)

            localStorage.setItem('tot_cancel', data.tot_cancel);
            localStorage.setItem('tot_revoke', data.tot_revoke);
            localStorage.setItem('today_cancel', data.today_cancel);
            localStorage.setItem('today_revoke', data.today_revoke);

            localStorage.setItem('tot_new', data.tot_new);
            localStorage.setItem('tot_existing', data.tot_existing);
            localStorage.setItem('today_new', data.today_new);
            localStorage.setItem('today_existing', data.today_existing);


            $('input[name="app_radio"]').trigger('change');//trigger at start

        }, 'json');

        $('input[name="app_radio"]').change(function () {
            let selectedValue = $('input[name="app_radio"]:checked').next().text().trim();
            $('#app_tot_chart,#app_today_chart').empty();


            if (selectedValue == 'Cancel & Revoke') {
                let tot_cancel = localStorage.getItem('tot_cancel');
                let tot_revoke = localStorage.getItem('tot_revoke');
                let today_cancel = localStorage.getItem('today_cancel');
                let today_revoke = localStorage.getItem('today_revoke');

                tot_cr_chart(tot_cancel, tot_revoke, 'app_tot_chart');
                tdy_cr_chart(today_cancel, today_revoke, 'app_today_chart');
            } else if (selectedValue == 'Customer Type') {
                let tot_new = localStorage.getItem('tot_new');
                let tot_existing = localStorage.getItem('tot_existing');
                let today_new = localStorage.getItem('today_new');
                let today_existing = localStorage.getItem('today_existing');

                tot_ct_chart(tot_new, tot_existing, 'app_tot_chart');
                tdy_ct_chart(today_new, today_existing, 'app_today_chart');

            }
        });
    });
}

// *****************************************************************************************************************************************

function getAcknowledgmentDashboard() {
    let branch_id = $('#branch_id').val();
    let month = $('#filter_month').val();
    localStorage.clear();//clear localstorage before fetching data for prevent conflict
    getSubAreaList(branch_id).then(sub_area_list => {

        $.post('dashboardFile/getAcknowledgmentDashboard.php', { sub_area_list, month }, function (data) {

            $('#tot_in_ack').text(data.tot_in_ack)
            $('#tot_ack_issue').text(data.tot_issue)
            $('#tot_ack_bal').text(data.tot_ack_bal)
            $('#today_in_ack').text(data.today_in_ack)
            $('#today_ack_issue').text(data.today_issue)
            $('#today_ack_bal').text(data.today_ack_bal)

            localStorage.setItem('tot_cancel', data.tot_cancel);
            localStorage.setItem('tot_revoke', data.tot_revoke);
            localStorage.setItem('today_cancel', data.today_cancel);
            localStorage.setItem('today_revoke', data.today_revoke);

            localStorage.setItem('tot_new', data.tot_new);
            localStorage.setItem('tot_existing', data.tot_existing);
            localStorage.setItem('today_new', data.today_new);
            localStorage.setItem('today_existing', data.today_existing);


            $('input[name="ack_radio"]').trigger('change');//trigger at start

        }, 'json');

        $('input[name="ack_radio"]').change(function () {
            let selectedValue = $('input[name="ack_radio"]:checked').next().text().trim();
            $('#ack_tot_chart,#ack_today_chart').empty();


            if (selectedValue == 'Cancel & Revoke') {
                let tot_cancel = localStorage.getItem('tot_cancel');
                let tot_revoke = localStorage.getItem('tot_revoke');
                let today_cancel = localStorage.getItem('today_cancel');
                let today_revoke = localStorage.getItem('today_revoke');

                tot_cr_chart(tot_cancel, tot_revoke, 'ack_tot_chart');
                tdy_cr_chart(today_cancel, today_revoke, 'ack_today_chart');
            } else if (selectedValue == 'Customer Type') {
                let tot_new = localStorage.getItem('tot_new');
                let tot_existing = localStorage.getItem('tot_existing');
                let today_new = localStorage.getItem('today_new');
                let today_existing = localStorage.getItem('today_existing');

                tot_ct_chart(tot_new, tot_existing, 'ack_tot_chart');
                tdy_ct_chart(today_new, today_existing, 'ack_today_chart');

            }
        });
    });
}

// *****************************************************************************************************************************************

function getLoanIssueDashboard() {
    let branch_id = $('#branch_id').val();
    let month = $('#filter_month').val();
    localStorage.clear();//clear localstorage before fetching data for prevent conflict
    getSubAreaList(branch_id).then(sub_area_list => {

        $.post('dashboardFile/getLoanIssueDashboard.php', { sub_area_list, month }, function (data) {

            $('#tot_li').text(data.tot_li)
            $('#tot_li_issue').text(data.tot_li_issue)
            $('#tot_li_bal').text(data.tot_li_bal)
            $('#today_li').text(data.today_li)
            $('#today_li_issue').text(data.today_li_issue)
            $('#today_li_bal').text(data.today_li_bal)

            localStorage.setItem('tot_bank', data.tot_bank);
            localStorage.setItem('tot_cash', data.tot_cash);
            localStorage.setItem('tot_cheque', data.tot_cheque);
            localStorage.setItem('tot_transaction', data.tot_transaction);
            localStorage.setItem('today_bank', data.today_bank);
            localStorage.setItem('today_cash', data.today_cash);
            localStorage.setItem('today_cheque', data.today_cheque);
            localStorage.setItem('today_transaction', data.today_transaction);

            localStorage.setItem('tot_new', data.tot_new);
            localStorage.setItem('tot_existing', data.tot_existing);
            localStorage.setItem('today_new', data.today_new);
            localStorage.setItem('today_existing', data.today_existing);

            localStorage.setItem('today_li_amt', data.today_li_amt);
            localStorage.setItem('today_issued_amt', data.today_issued_amt);


            $('input[name="li_radio"]').trigger('change');//trigger at start

        }, 'json');

        $('input[name="li_radio"]').change(function () {
            let selectedValue = $('input[name="li_radio"]:checked').next().text().trim();
            $('#li_tot_chart,#li_today_chart').empty();


            if (selectedValue == 'Issued by Modes') {
                // let tot_bank = localStorage.getItem('tot_bank');
                let tot_cash = localStorage.getItem('tot_cash');
                let tot_cheque = localStorage.getItem('tot_cheque');
                let tot_transaction = localStorage.getItem('tot_transaction');
                // let today_bank = localStorage.getItem('today_bank');
                let today_cash = localStorage.getItem('today_cash');
                let today_cheque = localStorage.getItem('today_cheque');
                let today_transaction = localStorage.getItem('today_transaction');

                tot_issue_chart(tot_cash, tot_cheque, tot_transaction, 'li_tot_chart');
                tdy_issue_chart(today_cash, today_cheque, today_transaction, 'li_today_chart');
            } else if (selectedValue == 'Customer Type') {
                let tot_new = localStorage.getItem('tot_new');
                let tot_existing = localStorage.getItem('tot_existing');
                let today_new = localStorage.getItem('today_new');
                let today_existing = localStorage.getItem('today_existing');

                tot_ct_chart(tot_new, tot_existing, 'li_tot_chart');
                tdy_ct_chart(today_new, today_existing, 'li_today_chart');

            } else if (selectedValue == 'Issue Amount') {
                let today_li_amt = localStorage.getItem('today_li_amt');
                let today_issued_amt = localStorage.getItem('today_issued_amt');
                tdy_li_chart(today_li_amt, today_issued_amt, 'li_today_chart');
            }
        });
    });
}

// *****************************************************************************************************************************************
function getCollectionDashboard() {

    let branch_id = $('#branch_id').val();
    let month = $('#filter_month').val();
    localStorage.clear();//clear localstorage before fetching data for prevent conflict
    getSubAreaList(branch_id).then(sub_area_list => {

        $.post('dashboardFile/getCollectionDashboard.php', { sub_area_list, month }, function (data) {
            $('#tot_col_paid').text(data.tot_col_paid)
            $('#tot_col_pen').text(data.tot_col_pen)
            $('#tot_col_fine').text(data.tot_col_fine)
            $('#today_col_paid').text(data.today_col_paid)
            $('#today_col_pen').text(data.today_col_pen)
            $('#today_col_fine').text(data.today_col_fine)
            $('#col_split_type').html(data.split_name);
        }, 'json').then(function () {

            $('.col-counter').off('click').click(function () {
                $('#col_chart_part').slideDown();
                let pid = $(this).attr('id');
                getCollectionSplit(pid, sub_area_list).then(function () {
                    $('#chart_part').empty();
                    let cur_amt = localStorage.getItem('cur_amt');
                    let cur_point = localStorage.getItem('cur_point');
                    let pend_amt = localStorage.getItem('pend_amt');
                    let pend_point = localStorage.getItem('pend_point');
                    let od_amt = localStorage.getItem('od_amt');
                    let od_point = localStorage.getItem('od_point');
                    setCollectionChart(cur_amt, cur_point, pend_amt, pend_point, od_amt, od_point);
                });
            })
        })

    })
    function getCollectionSplit(pid, sub_area_list) {
        return new Promise(function (resolve, reject) {
            $.post('dashboardFile/getCollectionSplit.php', { pid, sub_area_list }, function (data) {
                localStorage.setItem('cur_amt', data.cur_amt);
                localStorage.setItem('cur_point', data.cur_point);
                localStorage.setItem('pend_amt', data.pend_amt);
                localStorage.setItem('pend_point', data.pend_point);
                localStorage.setItem('od_amt', data.od_amt);
                localStorage.setItem('od_point', data.od_point);
                $('#col_split_type').text(data.split_name);
                resolve();
            }, 'json');
        })
    }
}

// *****************************************************************************************************************************************
function getClosedDashboard() {
    let branch_id = $('#branch_id').val();
    let month = $('#filter_month').val();
    localStorage.clear();//clear localstorage before fetching data for prevent conflict
    getSubAreaList(branch_id).then(sub_area_list => {

        $.post('dashboardFile/getClosedDashboard.php', { sub_area_list, month }, function (data) {
            $('#tot_in_cl').text(data.tot_in_cl)
            $('#month_in_cl').text(data.month_in_cl)
            $('#month_cl_status').text(data.month_cl_status)
            $('#month_cl_bal').text(data.month_cl_bal)
            $('#today_in_cl').text(data.today_in_cl)
            $('#today_cl_status').text(data.today_cl_status)
            $('#cl_wl').text(data.cl_wl)
            $('#cl_bl').text(data.cl_bl)
            $('#cl_cn').text(data.cl_cn)
            $('#cl_bronze').text(data.cl_bronze)
            $('#cl_silver').text(data.cl_silver)
            $('#cl_gold').text(data.cl_gold)
            $('#cl_platinum').text(data.cl_platinum)
            $('#cl_diamond').text(data.cl_diamond)
        }, 'json').then(function () {


        })

    })
}

// *****************************************************************************************************************************************
function getNOCDashboard() {
    let branch_id = $('#branch_id').val();
    let month = $('#filter_month').val();
    localStorage.clear();//clear localstorage before fetching data for prevent conflict
    getSubAreaList(branch_id).then(sub_area_list => {

        $.post('dashboardFile/getNOCDashboard.php', { sub_area_list, month }, function (data) {
            $('#tot_noc').text(data.tot_noc)
            $('#tot_noc_issued').text(data.tot_noc_issued)
            $('#month_noc').text(data.month_noc)
            $('#month_noc_issued').text(data.month_noc_issued)
            $('#month_noc_bal').text(data.month_noc_bal)
            $('#today_noc').text(data.today_noc)
            $('#today_noc_issued').text(data.today_noc_issued)
        }, 'json').then(function () {


        })

    })
}

// *****************************************************************************************************************************************
//Cancel and Revoke Charts
function tot_cr_chart(tot_cancel, tot_revoke, target_chart) {

    google.charts.load("current", { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Status", "Count", { role: "style" }],
            ["Cancel", parseInt(tot_cancel), "#faae7b"],
            ["Revoke", parseInt(tot_revoke), "#432371"],
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2]);

        var options = {
            title: "Total Cancelled and Revoked",
            width: '100%',
            height: '400px',
            bar: { groupWidth: "90%" },
            legend: { position: "none" },
            vAxis: { format: 'decimal', gridlines: { interval: [0, 5, 10, 15, 20] } },//this is for left vertical column count interval
            chartArea: {

            }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById(target_chart));
        chart.draw(view, options);
    }
}
function tdy_cr_chart(today_cancel, today_revoke, target_chart) {
    google.charts.load("current", { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Status", "Count", { role: "style" }],
            ["Cancel", parseInt(today_cancel), "#faae7b"],
            ["Revoke", parseInt(today_revoke), "#432371"],
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2]);

        var options = {
            title: "Today Cancelled and Revoked",
            width: '100%',
            height: '400px',
            bar: { groupWidth: "90%" },
            legend: { position: "none" },
            vAxis: { format: 'decimal', gridlines: { interval: [0, 5, 10, 15, 20] } },//this is for left vertical column count interval
        };
        var chart = new google.visualization.ColumnChart(document.getElementById(target_chart));
        chart.draw(view, options);
    }
}

//Customer Type Charts
function tot_ct_chart(tot_new, tot_existing, target_chart) {
    google.charts.load("current", { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Customer Type", "Count", { role: "style" }],
            ["New Customer", parseInt(tot_new), "#faae7b"],
            ["Existing Customer", parseInt(tot_existing), "#432371"],
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2]);

        var options = {
            title: "Total New & Existing Customers",
            width: '100%',
            height: '400px',
            bar: { groupWidth: "90%" },
            legend: { position: "none" },
            vAxis: { format: 'decimal', gridlines: { interval: [0, 5, 10, 15, 20] } },//this is for left vertical column count interval
        };
        var chart = new google.visualization.ColumnChart(document.getElementById(target_chart));
        chart.draw(view, options);
    }
}
function tdy_ct_chart(today_new, today_existing, target_chart) {
    google.charts.load("current", { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Customer Type", "Count", { role: "style" }],
            ["New Customer", parseInt(today_new), "#faae7b"],
            ["Existing Customers", parseInt(today_existing), "#432371"],
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2]);

        var options = {
            title: "Today New & Existing Customers",
            width: '100%',
            height: '400px',
            bar: { groupWidth: "90%" },
            legend: { position: "none" },
            vAxis: { format: 'decimal', gridlines: { interval: [0, 5, 10, 15, 20] } },//this is for left vertical column count interval
        };
        var chart = new google.visualization.ColumnChart(document.getElementById(target_chart));
        chart.draw(view, options);
    }
}

//Loan issue Pie charts
function tot_issue_chart(tot_cash, tot_cheque, tot_transaction, target_chart) {
    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Loan Issue Mode', 'Amount'],
            // ['Bank', { v: parseInt(tot_bank), f: 'Bank: '+ parseInt(tot_bank) }],
            ['Cash', { v: parseInt(tot_cash), f: 'Cash: ' + moneyFormatIndia(tot_cash) }],
            ['Cheque', { v: parseInt(tot_cheque), f: 'Cheque: ' + moneyFormatIndia(tot_cheque) }],
            ['Transaction', { v: parseInt(tot_transaction), f: 'Transaction: ' + moneyFormatIndia(tot_transaction) }],
        ]);

        var options = {
            pieSliceText: 'value',
            // is3D: true,
            tooltip: {
                trigger: 'selection', // Show tooltip on selection
            },
            title: 'Total Issued Amount by Modes',
            // pieHole:0.5,
            sliceVisibilityThreshold: 0,//shows legend even value is 0

        };

        var chart = new google.visualization.PieChart(document.getElementById(target_chart));
        chart.draw(data, options);

    }
}
function tdy_issue_chart(today_cash, today_cheque, today_transaction, target_chart) {
    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Loan Issue Mode', 'Amount'],
            // ['Bank', { v: parseInt(today_bank), f: 'Bank: '+ parseInt(today_bank) }],
            ['Cash', { v: parseInt(today_cash), f: 'Cash: ' + moneyFormatIndia(today_cash) }],
            ['Cheque', { v: parseInt(today_cheque), f: 'Cheque: ' + moneyFormatIndia(today_cheque) }],
            ['Transaction', { v: parseInt(today_transaction), f: 'Transaction: ' + moneyFormatIndia(today_transaction) }],
        ]);

        var options = {
            pieSliceText: 'value',
            // is3D: true,
            tooltip: {
                trigger: 'selection', // Show tooltip on selection
            },
            title: 'Today Issued Amount by Modes',
            sliceVisibilityThreshold: 0,//shows legend even value is 0

        };

        var chart = new google.visualization.PieChart(document.getElementById(target_chart));
        chart.draw(data, options);

    }
}

//Issue and about issued amounts
function tdy_li_chart(issue_amt, issued_amt, target_chart) {
    google.charts.load("current", { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Issue Amounts", "Count", { role: "style" }],
            ["Issue Amount", parseInt(issue_amt), "#faae7b"],
            ["Issued Amount", parseInt(issued_amt), "#432371"],
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2]);

        var options = {
            title: "Today Issue Amounts",
            width: '100%',
            height: '400px',
            bar: { groupWidth: "90%" },
            legend: { position: "none" },
            vAxis: { format: 'decimal', gridlines: { interval: [0, 5, 10, 15, 20] } },//this is for left vertical column count interval
        };
        var chart = new google.visualization.ColumnChart(document.getElementById(target_chart));
        chart.draw(view, options);
    }
}

//Dual column Collection chart part
function setCollectionChart(cur_amt, cur_point, pend_amt, pend_point, od_amt, od_point) {

    google.charts.load("current", { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart1);
    function drawChart1() {
        var data = google.visualization.arrayToDataTable([
            ["Stages", "Amount", { role: "style" }],
            ["Current", parseInt(cur_amt), "#edae49"],
            ["Pending", parseInt(pend_amt), "#d1495b"],
            ["OD", parseInt(od_amt), "#00798c"],
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2]);

        var options = {
            title: "Split by Amount",
            width: '100%',
            height: '400px',
            bar: { groupWidth: "90%" },
            legend: { position: "none" },
            vAxis: { format: 'decimal', gridlines: { interval: [0, 5, 10, 15, 20] } },//this is for left vertical column count interval
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('col_chart1'));
        chart.draw(view, options);
    }

    google.charts.load("current", { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart2);
    function drawChart2() {
        var data = google.visualization.arrayToDataTable([
            ["Stages", "Point", { role: "style" }],
            ["Current", parseInt(cur_point), "#edae49"],
            ["Pending", parseInt(pend_point), "#d1495b"],
            ["OD", parseInt(od_point), "#00798c"],
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2]);

        var options = {
            title: "Split by Points",
            width: '100%',
            height: '400px',
            bar: { groupWidth: "90%" },
            legend: { position: "none" },
            vAxis: { format: 'decimal', gridlines: { interval: [0, 5, 10, 15, 20] } },//this is for left vertical column count interval
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('col_chart2'));
        chart.draw(view, options);
    }
}
// *****************************************************************************************************************************************




function initializeCounterAnimation() {
    const counterUp = window.counterUp.default;

    const callback = entries => {
        entries.forEach(entry => {
            const el = entry.target;
            if (entry.isIntersecting && !el.classList.contains('is-visible')) {
                counterUp(el, {
                    // duration: 2000,
                    // delay: 70,
                });
                el.classList.add('is-visible');
            }
        });
    };

    const IO = new IntersectionObserver(callback, {
        threshold: 0
    });

    const els = document.querySelectorAll('.counter');
    els.forEach(el => {
        IO.observe(el);
    });
}