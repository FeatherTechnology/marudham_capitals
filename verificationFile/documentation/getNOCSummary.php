<?php
if(isset($_POST['req_id'])){
    $noc_summaryreq_id = $_POST['req_id'];
}

if(isset($_POST['cus_name'])){
    $noc_summarycus_name = $_POST['cus_name'];
}
?>

<style>
    .hr-line {
        position: relative;
        top: 20px;
        border: none;
        height: 1px;
        background: black;
    }
</style>

<!-- NOC window -->
<div class="card noc-card">
    <div class="card-body">
        <input type="hidden" name="noc_summary_req_id" id="noc_summary_req_id" value="<?php if(isset($noc_summaryreq_id)) echo $noc_summaryreq_id; ?>">
        <input type="hidden" name="noc_summary_cus_name" id="noc_summary_cus_name" value="<?php if(isset($noc_summarycus_name)) echo $noc_summarycus_name; ?>">
        <!-- Signed Document start -->
        <div class="row" id="sign_div">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:0px; margin-bottom:30px;'>Signed Document List</h5>
                            <span class="text-danger sign_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='signDocDiv'></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Signed Document End -->
         
        <hr class='hr-line' id="sign_hr">
        
        <!-- Cheque List Start -->
        <div class="row" id="cheque_div">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:30px; margin-bottom:30px;'>Cheque List</h5>
                            <span class="text-danger cheque_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='chequeDiv'></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Cheque List End -->

        <hr class='hr-line' id="cheque_hr">

        <!-- Mortgage List Start -->
        <div class="row" id="mort_div">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:30px; margin-bottom:30px;'>Mortgage List</h5>
                            <span class="text-danger mort_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='mortgageDiv'></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mortgage List End -->
         
        <hr class='hr-line' id="mort_hr">

        <!-- Endorsement List Start -->
        <div class="row" id="endorse_div">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:30px; margin-bottom:30px;'>Endorsement List</h5>
                            <span class="text-danger endorse_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='endorsementDiv'></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Endorsement List End -->
         
        <hr class='hr-line' id ="endo_hr">

        <!-- Gold List Start -->
        <div class="row" id="gold_div">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:30px; margin-bottom:30px;'>Gold List</h5>
                            <span class="text-danger gold_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='goldDiv'></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gold List End -->

        <hr class='hr-line' id="gold_hr">

        <!-- Document Info Start -->
        <div class="row" id="doc_div">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            <h5 style='margin-top:30px; margin-bottom:30px;'>Document List</h5>
                            <span class="text-danger doc_checklistCheck" style="display: none;">Please Select atleast one</span>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group" id='documentDiv'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Document Info End -->


<script>
$(document).ready(function () {

    const nocSummaryReqId   = $('#noc_summary_req_id').val();
    const nocSummaryCusName = $('#noc_summary_cus_name').val();

    const requests = [
        loadSection('nocFile/getSignedDocList.php', '#sign_div', '#signDocDiv', '#sign_hr'),
        loadSection('nocFile/getChequeDocList.php', '#cheque_div', '#chequeDiv', '#sign_hr'),
        loadSection('nocFile/getMortgageList.php', '#mort_div', '#mortgageDiv', '#cheque_hr'),
        loadSection('nocFile/getEndorsementList.php', '#endorse_div', '#endorsementDiv', '#mort_hr'),
        loadSection('nocFile/getGoldList.php', '#gold_div', '#goldDiv', '#endo_hr', true),
        loadSection('nocFile/getDocumentList.php', '#doc_div', '#documentDiv', '#gold_hr')
    ];

    // Run all AJAX calls, then disable checkboxes once
    Promise.all(requests).then(() => {
        disableCheckboxes();
    });

    function loadSection(url, sectionDiv, contentDiv, hrDiv, goldCheck = false) {
        
        return $.ajax({
            url: url,
            type: 'POST',
            data: { 'req_id': nocSummaryReqId, 'cus_name': nocSummaryCusName },
            cache: false,
            success: function (response) {
                const rows = $(response).find('tbody tr').length;
                const noData = goldCheck ? rows <= 1 : rows === 0;

                if (noData) {
                    $(sectionDiv).hide();
                    $(hrDiv).hide();
                } else {
                    $(sectionDiv).show();
                    $(contentDiv).html(response);
                }
            }
        });
    }

    function disableCheckboxes() {
        $('input[type=checkbox]').prop('disabled', true);
    }

});
</script>