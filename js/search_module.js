$(document).ready(function () {
    $('#cus_id').keyup(function () {
        var value = $(this).val();
        value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
        $(this).val(value);
    });

    $('#search').click(function () {
        let cus_id = $('#cus_id').val(); let autogen_cus_id = $('#autogen_cus_id').val(); let cus_name = $('#cus_name').val(); let area = $('#cus_area').val();
        let sub_area = $('#cus_sub_area').val(); let mobile = $('#mobile').val(); let fingerprint_person_id = $('#fingerprint_person_id').val();
        cus_id = cus_id.replace(/\s+/g, '');//removes spaces in adhar number
        if (validate()) {
            $.ajax({
                url: 'searchModule/search_customer.php',
                type: 'POST',
                data: { cus_id, autogen_cus_id, cus_name, area, sub_area, mobile, fingerprint_person_id },
                dataType: 'json',
                success: function (data) {
                    let appendData;
                    $('#custListTable tbody,#famlistTable tbody').empty()
                    if (Array.isArray(data.customer_data) && data.customer_data.length > 0) {
                        $.each(data.customer_data, function (key, val) {
                            appendData += `<tr><td>${val.sno}</td>
                                <td>${val.cus_id}</td>
                                <td>${val.autogen_cus_id}</td>
                                <td>${val.cus_name}</td>
                                <td>${val.area}</td>
                                <td>${val.sub_area}</td>
                                <td>${val.branch}</td>
                                <td>${val.line}</td>
                                <td>${val.group}</td>
                                <td>${val.mobile1}</td>
                                <td>${val.mobile2}</td>
                                <td>${val.action}</td>
                                </tr>`;
                        })
                    } else {
                        appendData = `<tr><td colspan='11'>No Records available</td></tr>`;
                    }
                    $('#custListTable tbody').html(appendData);

                    if (Array.isArray(data.family_data) && data.family_data.length > 0) {
                        appendData = '';
                        $.each(data.family_data, function (key, val) {
                            appendData += `<tr><td>${val.sno}</td>
                                <td>${val.name}</td>
                                <td>${val.relationship}</td>
                                <td>${val.adhaar}</td>
                                <td>${val.mobile}</td>
                                <td>${val.under_cus}</td>
                                <td>${val.under_cus_id}</td>
                                <td>${val.under_autogen_cus_id}</td>
                                </tr>`;
                        })
                    } else {
                        appendData = `<tr><td colspan='7'>No Records available</td></tr>`;
                    }
                    $('#famlistTable tbody').html(appendData);

                    $('.radio-container,#search_container').show();
                }
            }).then(function () {
                viewCusOnClick();
            })
        } else {
            $('.radio-container,#search_container').hide();
        }
    })
    $('input[name="search_radio"]').change(function () {
        let selectedValue = $('input[name="search_radio"]:checked').attr('id');
        if (selectedValue == 'cus_list_radio') {
            $('#customer_list_card').show();
            $('#family_list_card').hide();
        } else if (selectedValue == 'fam_list_radio') {
            $('#family_list_card').show();
            $('#customer_list_card').hide();

        }
    })

    $('#searchbox').keyup(function () {
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;
        // Loop through the comment list
        $("#custListTable tbody tr").each(function () {
            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();
                // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                count++;
            }
        })
    })

    $('.scanBtn').click(function () {

        showOverlay();//loader start

        setTimeout(() => { //Set Timeout, because loadin animation will be intrupped by this capture event
            var quality = 60; //(1 to 100) (recommended minimum 55)
            var timeout = 10; // seconds (minimum=10(recommended), maximum=60, unlimited=0)
            var res = CaptureFinger(quality, timeout);
            if (res.httpStaus) {
                if (res.data.ErrorCode == "0") {
                    $('#search_fingerprint').val(res.data.AnsiTemplate); // Take ansi template that is the unique id which is passed by sensor
                }//Error codes and alerts below
                else if (res.data.ErrorCode == -1307) {
                    alert('Connect Your Device');
                
                } else if (res.data.ErrorCode == -1140 || res.data.ErrorCode == 700) {
                    alert('Timeout');
                
                } else if (res.data.ErrorCode == 720) {
                    alert('Reconnect Device');
                
                } else if (res.data.ErrorCode == 730) {
                    alert('Capture Finger Again');
                
                } else {
                    alert('Error Code:' + res.data.ErrorCode);
                
                }
            }
            else {
                alert(res.err);
            }

            //Verify the finger is matched with member name
            
            $.post("searchModule/getAllFingerprints.php", {}, function(data){
                let search_fingerprint = $('#search_fingerprint').val()
                if (data.fingerprints && data.fingerprints.length > 0) {
                    let matchedCustomer = null;
                    for (let i = 0; i < data.fingerprints.length; i++) {
                        let stored = data.fingerprints[i].template;
                        let res = VerifyFinger(stored, search_fingerprint);
                        if (res.httpStaus && res.data.Status) {
                            matchedCustomer = data.fingerprints[i];
                            $('#fingerprint_person_id').val(matchedCustomer.cus_id);
                            break;
                        }
                    }

                    if (matchedCustomer) {
                        Swal.fire({
                            title: `Fingerprint Matched: ${matchedCustomer.cus_name}`,
                            icon: 'success',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        }).then(() => {
                            // Trigger search only after clicking OK
                            $('#search').trigger('click');
                        });
                    } else {
                        Swal.fire({
                            title: 'No Match Found',
                            icon: 'error',
                            showConfirmButton: true,
                            confirmButtonColor: '#009688'
                        });
                    }
                }else{
                     Swal.fire({
                        title: 'Error While getting Fingerprint',
                        icon: 'error',
                        showConfirmButton: true,
                        confirmButtonColor: '#009688'
                    });
                }

            },'json');

            hideOverlay();//loader stop

        }, 700) //Timeout End

    })//Scan button Onclick end

})


function validate() {
    let response = true;
    let cus_id = $('#cus_id').val(); let autogen_cus_id = $('#autogen_cus_id').val(); let cus_name = $('#cus_name').val(); let area = $('#cus_area').val(); let sub_area = $('#cus_sub_area').val(); let mobile = $('#mobile').val(); let fingerprint_person_id = $('#fingerprint_person_id').val();

    if (cus_id == '' && autogen_cus_id == '' && cus_name == '' && area == '' && sub_area == '' && mobile == '' && fingerprint_person_id == '') {
        response = false;
        event.preventDefault();
        alert('Please fill any one field to search!')
    }

    return response;
}

function viewCusOnClick() {
    $('.view_cust').off('click').click(function () {
        $('#customerStatusDiv').empty();
        let cus_id = $(this).data('cusid');
        callresetCustomerStatus(cus_id, function () {
            showOverlay(); //loader start
            var pending_sts = $('#pending_sts').val();
            var od_sts = $('#od_sts').val();
            var due_nil_sts = $('#due_nil_sts').val();
            var closed_sts = $('#closed_sts').val()
            var bal_amt = $('#bal_amt').val()
            $.post("searchModule/getCustomerStatus.php", { cus_id, pending_sts, od_sts, due_nil_sts, closed_sts, bal_amt }, function (response) {
                $('#customerStatusDiv').html(response);
                hideOverlay();
            });

        }); //this function will give the customer's status like pending od current
    });
}

function callresetCustomerStatus(cus_id, callback) {
    //To get loan sub Status
    var pending_arr = [];
    var od_arr = [];
    var due_nil_arr = [];
    var closed_arr = [];
    var balAmnt = [];
    $.ajax({
        url: 'collectionFile/resetCustomerStatus.php',
        data: {
            'cus_id': cus_id
        },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            if (response.length != 0) {
                let pendingCnt = (response['pending_customer']) ? response['pending_customer'].length : 0;
                for (var i = 0; i < pendingCnt; i++) {
                    pending_arr[i] = response['pending_customer'][i]
                    od_arr[i] = response['od_customer'][i]
                    due_nil_arr[i] = response['due_nil_customer'][i]
                    closed_arr[i] = response['closed_customer'][i]
                    balAmnt[i] = response['balAmnt'][i]
                }
                var pending_sts = pending_arr.join(',');
                $('#pending_sts').val(pending_sts);
                var od_sts = od_arr.join(',');
                $('#od_sts').val(od_sts);
                var due_nil_sts = due_nil_arr.join(',');
                $('#due_nil_sts').val(due_nil_sts);
                var closed_sts = closed_arr.join(',');
                $('#closed_sts').val(closed_sts);
                var bal_amt = balAmnt.join(',');
                $('#bal_amt').val(bal_amt);
            };

             // ✅ Call the callback properly here
             if (typeof callback === 'function') {
                callback();
            }
            
        }
    });
}

function customerStatusOnClickEvents() {
    $('.dropdown').off('click').click(function (event) {
        event.preventDefault();
        $('.dropdown').not(this).removeClass('active');
        $(this).toggleClass('active');
    });

    $(document).off('click').click(function (event) {
        var target = $(event.target);
        if (!target.closest('.dropdown').length) {
            $('.dropdown').removeClass('active');
        }
    });

    $('.personal-info').off('click').click(function () {
        let cus_id = $(this).data('cusid');
        $.post('followupFiles/promotion/getPersonalInfo.php', {
            cus_id
        }, function (html) {
            $('#personalInfoDiv').empty().html(html);
        })
    })
    $('.cust-profile').off('click').click(function () {
        let req_id = $(this).data('reqid');
        // window.location.href = 'due_followup_info&upd='+req_id+'&pgeView=1';
        window.open('due_followup_info&upd=' + req_id + '&pgeView=1', '_blank');
    })
    //Documentaion
    $('.documentation').off('click').click(function () {
        let req_id = $(this).data('reqid');
        // window.location.href = 'due_followup_info&upd='+req_id+'&pgeView=2';
        window.open('due_followup_info&upd=' + req_id + '&pgeView=2', '_blank');
    })
    //Loan Calculation
    $('.loan-calc').off('click').click(function () {
        let req_id = $(this).data('reqid');
        window.open('due_followup_info&upd=' + req_id + '&pgeView=3', '_blank');
    })

    $('.due-chart').off('click').click(function () {
        let req_id = $(this).attr('value');
        let cus_id = $(this).data('cusid');
        dueChartList(req_id, cus_id, function(){
            $('.print_due_coll').off('click').click(function () {
                var id = $(this).attr('value');
                Swal.fire({
                    title: 'Print',
                    text: 'Do you want to print this collection?',
                    imageUrl: 'img/printer.png',
                    imageWidth: 300,
                    imageHeight: 210,
                    imageAlt: 'Custom image',
                    showCancelButton: true,
                    confirmButtonColor: '#009688',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'collectionFile/print_collection.php',
                            data: {
                                'coll_id': id
                            },
                            type: 'post',
                            cache: false,
                            success: function (html) {
                                $('#printcollection').html(html)
                            }
                        })
                    }
                })
            })
        });
    })
    $('.penalty-chart').off('click').click(function () {
        let req_id = $(this).attr('value');
        let cus_id = $(this).data('cusid');
        $.ajax({
            //to insert penalty by on click
            url: 'collectionFile/getLoanDetails.php',
            data: { req_id, cus_id },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                penaltyChartList(req_id, cus_id); //To show Penalty List.
            }
        })
    })

    $('.coll-charge-chart').off('click').click(function () {
        var req_id = $(this).attr('value');
        collectionChargeChartList(req_id) //To Show Fine Chart List
    })
    //Commitment chart
    $('.commitment-chart').off('click').click(function () {
        let req_id = $(this).data('reqid');
        let cus_id = $(this).data('cusid');
        $.post('followupFiles/dueFollowup/getCommitmentChart.php', { cus_id, req_id }, function (html) {
            $('#commChartDiv').empty().html(html);
        })
    })

    $('.noc-summary').off('click').click(function (e) {
        e.preventDefault();
        let req_id = $(this).data('reqid');
        var cus_name = $(this).data('cusname');
        $.ajax({
            url: 'verificationFile/documentation/getNOCSummary.php',
            data: { req_id, cus_name },
            type: 'post',
            cache: false,
            success: function (html) {
                $('#nocsummaryModal').html(html);
            }
        });
    });

    $('.loansummary-chart').off('click').click(function () {
        var req_id = $(this).data('reqid'); var cus_id = $(this).data('cusid');
        loanSummaryList(req_id, cus_id);
    })
}

//Due Chart List
function dueChartList(req_id, cus_id, callback) {
    $.ajax({
        url: 'collectionFile/getDueChartList.php',
        data: { 'req_id': req_id, 'cus_id': cus_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#dueChartTableDiv').empty()
            $('#dueChartTableDiv').html(response)

            if(typeof callback === 'function'){
                callback();
            }
        }
    }).then(function () {

        $.post('collectionFile/getDueMethodName.php', { req_id }, function (response) {
            $('#dueChartTitle').text(`Due Chart ( Aadhaar Number : ${response.cus_id} | Cus ID : ${response.autogen_cus_id}  | Cus Name : ${response.cus_name}  | Loan ID : ${response.loan_id}  | Loan Category : ${response.loan_category} )`);
        }, 'json');
    })
}

//Penalty Chart List
function penaltyChartList(req_id, cus_id) {
    $.ajax({
        url: 'collectionFile/getPenaltyChartList.php',
        data: { 'req_id': req_id, 'cus_id': cus_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#penaltyChartTableDiv').empty()
            $('#penaltyChartTableDiv').html(response)
        }
    });//Ajax End.
}
//Fine Chart
function collectionChargeChartList(req_id) {
    $.ajax({
        url: 'collectionFile/getCollectionChargeList.php',
        data: { 'req_id': req_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#collectionChargeDiv').empty()
            $('#collectionChargeDiv').html(response)
        }
    });//Ajax End.
}

//Loan Summary Chart List
function loanSummaryList(req_id, cus_id) {
    $.ajax({
        url: 'closedFile/loan_summary_list.php',
        type: 'POST',
        data: { "reqId": req_id },
        cache: false,
        success: function (html) {
            $("#loanSummaryDiv").empty();
            $("#loanSummaryDiv").html(html);
        }
    });
}
