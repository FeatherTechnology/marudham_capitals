const loanCategory = new Choices('#loan_category', {
    removeItemButton: true,
    noChoicesText: 'Select Category',
    allowHTML: true
});

$('#loan_category').closest('.choices').hide();

$(document).ready(function () {
    // 🔹 Reset / Show Button Click
    $('#type').change(function () {
        let type = $(this).val();
        resetAllTables(); 
        if (type == '1') {
            // Show Branch and the Loan Category wrapper
            $('#branch_id').show();
            $('#loan_category').closest('.choices').show();

            // Hide Agent
            $('#agent').hide();
            getBranchNames()
            getUserLoanCategories();
        }
        else if (type == '2') {
            // Hide Branch and Loan Category wrapper
            $('#branch_id').hide();
            $('#loan_category').closest('.choices').hide();

            // Show Agent
            $('#agent').show();
            getAgentName();
        }
        else {
            // Reset everything if 'Select Type' (0) is chosen
            $('#branch_id').hide().val('');
            $('#agent').hide().val('');

            // Reset and hide Choices.js properly
            let loanCategory = $('#loan_category');
            loanCategory.closest('.choices').hide();
            loanCategory.val([]).trigger('change'); // Clears selection & updates UI
        }
    });

    $('#reset_btn').click(function () {

        let branchName = $('#branch_id option:selected').text();
        let agentName = $('#agent option:selected').text();

        let monthVal = $('#from_month').val();
        let branch = $('#branch_id').val();
        let loan_category = $('#loan_category').val();
        let type = $('#type').val();
        let agent = $('#agent').val();

        if (!monthVal || !type || (type == '1' && !branch) || (type == '2' && !agent)) {
            let msg = !monthVal ? 'Please Select Month!' : !type ? 'Please Select Type!' : type == '1' ? 'Please Select Branch!' : 'Please Select Agent!';
            swalError('Warning!', msg);
            return;
        }
        let monthName = '';
        if (monthVal) {
            const date = new Date(monthVal + '-01');
            monthName = date.toLocaleString('en-US', {
                month: 'short',
                year: 'numeric'
            });
        }

        buildBranchHeader(branchName, monthName, agentName, type);
        outStandingReport(monthVal, branch, loan_category, type, agent);
    });

    ///Document end
});



function outStandingReport(monthVal, branch, loan_category, type, agent) {

    let url_link = '';

    if (type == '1') {
        url_link = 'reportFile/outstanding_report/getOutStandingReport.php';
    } else {
        url_link = 'reportFile/outstanding_report/getAgentOutStandingReport.php';
    }

    $.ajax({
        url: url_link,
        type: 'POST',
        data: {
            monthVal: monthVal,
            branch: branch,
            loan_category: loan_category,
            agent: agent
        },
        dataType: 'json',

        success: function (res) {

            if (!res.data || res.data.length === 0) {

                if ($.fn.DataTable.isDataTable('#outstanding_table')) {
                    $('#outstanding_table').DataTable().clear().draw();
                }
                return;
            }

            // Separate TOTAL row
            let tableData = [];
            let totalRow = null;

            $.each(res.data, function (i, row) {
                if (row.details === 'TOTAL') {
                    totalRow = row;
                } else {
                    tableData.push(row);
                }
            });

            // Add TOTAL at last
            if (totalRow) {
                tableData.push(totalRow);
            }

            if ($.fn.DataTable.isDataTable('#outstanding_table')) {
                $('#outstanding_table').DataTable().destroy();
            }

            const columns = [
                { data: 'details', defaultContent: '' },
                {
                    data: 'pre_os_amount',
                    render: function (data) {
                        return moneyFormatIndia(data || 0);
                    }
                },
                { data: 'pre_os_po', defaultContent: 0 },
                {
                    data: 'collection_amount',
                    render: function (data) {
                        return moneyFormatIndia(data || 0);
                    }
                },
                {
                    data: 'waiver_amount',
                    render: function (data) {
                        return moneyFormatIndia(data || 0);
                    }
                },
                { data: 'end_po', defaultContent: 0 },
                {
                    data: 'cash_amount',
                    render: function (data) {
                        return moneyFormatIndia(data || 0);
                    }
                },
                {
                    data: 'profit',
                    render: function (data) {
                        return moneyFormatIndia(data || 0);
                    }
                },
                {
                    data: 'doc',
                    render: function (data) {
                        return moneyFormatIndia(data || 0);
                    }
                },
                {
                    data: 'total_amount',
                    render: function (data) {
                        return moneyFormatIndia(data || 0);
                    }
                },
                { data: 'issue_po', defaultContent: 0 },
                {
                    data: 'current_os_amount',
                    render: function (data) {
                        return moneyFormatIndia(data || 0);
                    }
                },
                { data: 'current_os_po', defaultContent: 0 }
            ];

            const outstanding_table = $('#outstanding_table').DataTable({

                ...getStateSaveConfig('outstanding_table'),

                destroy: true,
                data: tableData,
                columns: columns,

                // Prevent automatic sorting
                order: [],
                ordering: false,

                dom: 'lBfrtip',

                buttons: [
                    {
                        extend: 'excel',
                        title: 'Outstanding_Report',
                        action: function (e, dt, button, config) {

                            const file = curDateJs('outstanding_table');

                            config.title = file;
                            config.filename = file;

                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(
                                this,
                                e,
                                dt,
                                button,
                                config
                            );
                        }
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column'
                    }
                ],

                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],

                rowCallback: function (row, data) {

                   if (data.details === 'TOTAL') {
                        
                        // Check if loan_category is an array or string and check its length
                        let isCatEmpty = (!loan_category || loan_category.length === 0 || loan_category == "");

                        if (type == '1' && isCatEmpty) {
                            // Style for pure Branch summary view (Normal Text)
                            $(row).css({
                                'font-weight': 'normal',
                                'background-color': '#ffffff'
                            });
                        } else {
                            // Style for Agent summary or Branch + Loan Category combo view (Bold Highlighted)
                            $(row).css({
                                'font-weight': 'bold',
                                'background-color': '#f5f5f5'
                            });
                        }
                    }
                },

                drawCallback: function () {
                    searchFunction('outstanding_table');
                    paginationFunction('outstanding_table');
                }
            });

            initColVisFeatures(
                outstanding_table,
                'outstanding_table'
            );
        },

        error: function (xhr, status, error) {

            console.error('Ajax Error:', error);
            console.log(xhr.responseText);

        }
    });
}

function getBranchNames() {
    $.post('manageUser/getBranchList.php', function (response) {
        $('#branch_id').empty()
            .append("<option value=''>Select Branch</option>")

        $.each(response, function (i, val) {
            $('#branch_id').append("<option value='" + val.branch_id + "'>" + val.branch_name + "</option>");
        });
    }, 'json');
}


function getUserLoanCategories() {
    $.ajax({
        url: 'reportFile/customer_status_report/ajaxGetUserLoanCategory.php',
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            loanCategory.clearStore();
            let items = [];
            for (let i = 0; i < response.length; i++) {
                items.push({
                    value: response[i]['loan_category_creation_id'],
                    label: response[i]['loan_category_creation_name']
                });
            }
            loanCategory.setChoices(items);
        }
    });
}
function resetAllTables() {

    if ($.fn.DataTable.isDataTable('#outstanding_table')) {
        $('#outstanding_table').DataTable().clear().destroy();
    }

    $('#outstanding_table tbody').empty();
    $('#outstanding_table thead').empty();
    $('#outstanding_table tfoot').empty();
}

function buildBranchHeader(branchName, monthName, agentName, type) {
    let displayName = (type == 1) ? branchName : agentName;
    let html = `
    <tr>
        <th rowspan="3">Details</th>
        <th colspan="8">${displayName}</th>
        <th colspan="4">${monthName}</th>
    </tr>
    <tr>
        <th colspan="2">Pre O/s</th>
        <th colspan="1">Collection</th>
        <th colspan="1">Waiver</th>
        <th colspan="1">End</th>
        <th colspan="1">Cash</th>
        <th colspan="1">Profit</th>
        <th colspan="1">Doc</th>
        <th colspan="2">Total Amount</th>
        <th colspan="2">Current O/s</th>
    </tr>

    <tr>
        <th>Amount</th>
        <th>Po</th>
        <th>Amount</th>
        <th>Amount</th>
        <th>Po</th>
        <th>Amount</th>
        <th>Rs</th>
        <th>Rs</th>
        <th>Rs</th>
        <th>I.Po</th>
        <th>Amount</th>
        <th>Po</th>
    </tr>
    `;
    $('#outstanding_table thead').html(html);
}


function getAgentName() {
    $.ajax({
        url: 'accountsFile/cashtally/agent/getAgentName.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#agent').empty();
            $('#agent').append("<option value=''>Select Agent Name</option>");
            $.each(response, function (index, item) {
                $('#agent').append("<option value='" + item['ag_id'] + "'>" + item['ag_name'] + "</option> ")
            })
        }
    })
}