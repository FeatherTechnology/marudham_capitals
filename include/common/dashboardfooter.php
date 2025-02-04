<!-- Scroll to Top button -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<a href="#" id="scrollToTopButton" class="scroll-to-top-button">
    <i class="fas fa-arrow-up"></i>
  </a> -->

<style>
    /* Scroll to Top button styles */
    .scroll-to-top-button {
        position: fixed;
        right: 30px;
        top: 100px;
        width: 50px;
        height: 50px;
        background-color: #009688;
        color: white;
        border-radius: 50%;
        font-size: 24px;
        text-align: center;
        line-height: 50px;
        cursor: pointer;
        display: none;
        z-index: 99999;
        transition: background-color 0.3s ease-in-out;
    }

    .scroll-to-top-button:hover {
        background-color: #009968;
    }

    table.dataTable td,
    table.dataTable th {
        /* this will set all datatable's cell to not wrap the contents globally */
        white-space: nowrap;
    }

    .dropdown-content {
        color: black;
    }
</style>
<script>
    $(document).ready(function() {
        // Show/hide the Scroll to Top button based on the user's scroll position
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('#scrollToTopButton').fadeIn();
            } else {
                $('#scrollToTopButton').fadeOut();
            }
        });

        // Smooth scroll to the top when the button is clicked
        $('#scrollToTopButton').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

        // Event listener to hide the "Scroll to Top" button when any element with data-toggle='modal' is clicked
        $(document).on('click', '[data-toggle="modal"]', function() {
            hideScrollToTopButton();
        });
        // Function to hide the "Scroll to Top" button
        function hideScrollToTopButton() {
            $('#scrollToTopButton').fadeOut();
        }

        // Event listener to Show the "Scroll to Top" button when any element with data-dismiss='modal' is clicked
        $(document).on('click', '[data-dismiss="modal"]', function() {
            showScrollToTopButton();
        });
        // Function to show the "Scroll to Top" button if not visible
        function showScrollToTopButton() {
            $('#scrollToTopButton').fadeIn();
        }


    });
</script>
<?php $current_page = isset($_GET['page']) ? $_GET['page'] : null; ?>

<!-- Required jQuery first, then Bootstrap Bundle JS -->
<scripft src="js/jquery.min.js">
    </script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/moment.js"></script>
    <!-- <script src="js/jspdf.js"></script>
<script src="js/xlsx.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <script src="vendor/apex/apexcharts.min.js"></script>

    <script src="js/logincreation.js"></script>

    <!-- Slimscroll JS -->
    <script src="vendor/slimscroll/slimscroll.min.js"></script>
    <script src="vendor/slimscroll/custom-scrollbar.js"></script>

    <!-- Daterange -->
    <script src="vendor/daterange/daterange.js"></script>
    <script src="vendor/daterange/custom-daterange.js"></script>

    <script src="vendor/bs-select/bs-select.min.js"></script>
    <!-- Font -->
    <script src="js/main.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

    <!-- Multi select Plugin -->
    <script src="vendor/multiselect/public/assets/scripts/choices.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.7/dist/sweetalert2.all.min.js"></script>

    <script type="text/javascript" src="jsd/datatables.min.js"></script>
    <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->
    <script type="text/javascript" language="javascript">
        $(document).ready(function() {
            var company_creation_table = $('#company_creation_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                // 'searching': false, // Remove default Search Control
                'ajax': {
                    'url': 'ajaxFetch/ajaxCompanyCreationFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },

                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Company List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    },

                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('company_creation_table');
                    toggleAddButton();
                }
            });

            var branch_creation_info = $('#branch_creation_info').DataTable({
                "order": [
                    [0, "asc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                //'searching': false, // Remove default Search Control
                'ajax': {
                    'url': 'ajaxFetch/ajaxBranchCreationFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },

                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Branch List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }

                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('branch_creation_info');
                }
            });

            var loan_creation_table = $('#loan_creation_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                //'searching': false, // Remove default Search Control
                'ajax': {
                    'url': 'ajaxFetch/ajaxLoanCategoryFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },

                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Loan Category List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }

                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('loan_creation_table');
                }
            });

            // Loan Calculation datatable
            var loan_calculation_info = $('#loan_calculation_info').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                //'searching': false, // Remove default Search Control
                'ajax': {
                    'url': 'ajaxFetch/ajaxLoanCalculationFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Loan Calculation List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('loan_calculation_info');
                }
            });

            var area_creation_info = $('#area_creation_info').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                //'searching': false, // Remove default Search Control
                'ajax': {
                    'url': 'ajaxFetch/ajaxAreaCreationFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },

                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Area List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }

                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('area_creation_info');
                }
            });


            // Director Creation datatable
            var director_creation_table = $('#director_creation_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxDirectorCreationFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Director List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('director_creation_table');
                }
            });
            // Agent Creation datatable
            var agent_creation_table = $('#agent_creation_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxAgentCreationFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Agent List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('agent_creation_table');
                }
            });
            // Staff Creation datatable
            var staff_creation_table = $('#staff_creation_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxStaffCreationFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Staff List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('staff_creation_table');
                }
            });

            //Bank Creation Table
            var bank_creation_table = $('#bank_creation_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxBankCreationFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "NOC List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('bank_creation_table');
                }
            });

            // Manage user datatable
            var manage_user_table = $('#manage_user_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxManageUserFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "User List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('manage_user_table');
                }
            });
            // Documentation Mapping datatable
            var doc_mapping_table = $('#doc_mapping_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "ordering": false,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxDocumentationMappingFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Documentation Mapping List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction();
                }
            });
            // Request datatable
            var request_table = $('#request_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxRequestFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Request List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('request_table');
                    callOnClickEvents();
                }
            });


            // Verification datatable
            var verification_table = $('#verification_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxVerificationFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Verification List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('verification_table');
                    callOnClickEvents();
                }
            });


            // Approval datatable
            var approval_table = $('#approval_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxApprovalFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Approval List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('approval_table');
                    callOnClickEvents();
                }
            });

            // Acknowledgement List
            var acknowledge_table = $('#acknowledge_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxAcknowledgementFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Acknowledgement List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('acknowledge_table');
                    callOnClickEvents();
                }
            });

            // Loan Issue List
            var loanIssue_table = $('#loanIssue_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxLoanIssueFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Loan Issue List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('loanIssue_table');
                    callOnClickEvents();
                }
            });

            // Closed
            var closed_table = $('#closed_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxClosedFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Closed List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('closed_table');
                    setNOCButton();
                }
            });

            //NOC Table
            var noc_table = $('#noc_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxNocFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "NOC List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('noc_table');
                    callOnClickEvents();
                }
            });

            //UPDATE Table
            var update_table = $('#update_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxUpdateFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Update List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('update_table');
                }
            });
            //Document Track Table
            var doc_track_table = $('#doc_track_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxDocumentTrackFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Document Track List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('doc_track_table');
                    getDocOnClickFunction();
                }
            });
            //Concern Table
            var concern_table = $('#concern_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxConcernFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Concern List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('concern_table');
                }
            });

            //Concern Solution Table
            var concern_solution_table = $('#concern_solution_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxConcernSolutionFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Concern Solution List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('concern_solution_table');
                }
            });
            //Concern Feedback Table
            var concern_feedback_table = $('#concern_feedback_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxConcernFeedbackFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Concern Feedback List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('concern_feedback_table');
                }
            });

            //SMS Generation
            var customer_birthday_table = $('#customer_birthday_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxCustomerBirthdayFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "NOC List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('customer_birthday_table');
                }
            });

            var loan_follow_table = $('#loan_follow_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'followupFiles/loanFollowup/resetLoanFollowupTable.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Loan Followup List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('loan_follow_table');
                    loanFollowupTableOnclick();
                }
            });

            var conf_follow_table = $('#conf_follow_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'followupFiles/confirmation/resetConfirmationFollowupTable.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Confirmation Followup List"
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed four-column',
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                'drawCallback': function() {
                    searchFunction('conf_follow_table');
                    confirmationTableOnClick();
                }
            });


        }); //Document Ready End
    </script>

    <?php
    // Master Module
    if ($current_page == 'company_creation') { ?>
        <script src="js/company_creation.js"></script>
    <?php }

    if ($current_page == 'branch_creation') { ?>
        <script src="js/branch_creation.js"></script>
    <?php }

    if ($current_page == 'loan_category') { ?>
        <script src="js/loan_category.js"></script>
    <?php }

    if ($current_page == 'loan_calculation') { ?>
        <script src="js/loan_calculation.js"></script>
    <?php }

    if ($current_page == 'loan_scheme') { ?>
        <script src="js/loan_scheme.js"></script>
    <?php }
    if ($current_page == 'edit_loan_scheme') { ?>
        <script src="js/edit_loan_scheme.js"></script>
    <?php }

    if ($current_page == 'area_creation') { ?>
        <script src="js/area_creation.js"></script>
    <?php }

    if ($current_page == 'area_mapping') { ?>
        <script src="js/area_mapping.js"></script>
    <?php }
    if ($current_page == 'edit_area_mapping') { ?>
        <script src="js/edit_area_mapping.js"></script>
    <?php }

    if ($current_page == 'area_status') { ?>
        <script src="js/area_status.js"></script>
    <?php }

    // Administration Module

    if ($current_page == 'director_creation') { ?>
        <script src="js/director_creation.js"></script>
    <?php }

    if ($current_page == 'agent_creation') { ?>
        <script src="js/agent_creation.js"></script>
    <?php }

    if ($current_page == 'staff_creation') { ?>
        <script src="js/staff_creation.js"></script>
    <?php }

    if ($current_page == 'manage_user') { ?>
        <script src="js/manage_user.js"></script>
    <?php }

    if ($current_page == 'bank_creation') { ?>
        <script src="js/bank_creation.js"></script>
    <?php }

    if ($current_page == 'doc_mapping') { ?>
        <script src="js/doc_mapping.js"></script>
    <?php }

    // Request Module
    if ($current_page == 'request') { ?>
        <script src="js/request.js"></script>
    <?php }
    if ($current_page == 'edit_request') { ?>
        <script src="js/edit_request.js"></script>
    <?php }

    if ($current_page == 'verification') { ?>
        <script src="js/verification.js"></script>
    <?php }

    if ($current_page == 'verification_list') { ?>
        <script src="js/verification_list.js"></script>
    <?php }

    if ($current_page == 'approval_list') { ?>
        <script src="js/approval_list.js"></script>
    <?php }

    //Acknowledgement screen
    if ($current_page == 'edit_acknowledgement_list') { ?>
        <script src="js/edit_acknowledgement_list.js"></script>
    <?php }

    if ($current_page == 'acknowledgement_creation') { ?>
        <script src="js/acknowledgement_creation.js"></script>
    <?php }

    //Loan Issue screen
    if ($current_page == 'edit_loan_issue') { ?>
        <script src="js/edit_loan_issue.js"></script>
    <?php }

    if ($current_page == 'loan_issue') { ?>
        <script src="js/loan_issue.js"></script>
    <?php }

    if ($current_page == 'edit_collection') { ?>
        <script src="js/edit_collection.js"></script>
    <?php }

    if ($current_page == 'collection') { ?>
        <script src="js/collection.js"></script>
    <?php }

    if ($current_page == 'noc') { ?>
        <script src="js/noc.js"></script>
    <?php }

    //Closed
    if ($current_page == 'edit_closed') { ?>
        <script src="js/edit_closed.js"></script>
    <?php }

    if ($current_page == 'closed') { ?>
        <script src="js/closed.js"></script>
    <?php }

    //Concern Creation
    if ($current_page == 'concern_creation') { ?>
        <script src="js/concern_creation.js"></script>
    <?php }

    if ($current_page == 'concern_solution' || $current_page == 'concern_solution_view') { ?>
        <script src="js/concern_solution.js"></script>
    <?php }
    //Concern Feedback
    if ($current_page == 'concern_feedback') { ?>
        <script src="js/concern_feedback.js"></script>
    <?php }

    //Document track Screen
    if ($current_page == 'document_track') { ?>
        <script src="js/document_track.js"></script>
    <?php }

    //Update Customer Status Screen
    if ($current_page == 'update_customer_status') { ?>
        <script src="js/update_customer_status.js"></script>
    <?php }


    //Update Screen
    if ($current_page == 'update') { ?>
        <script src="js/update.js"></script>
    <?php }

    //Cash Tally
    if ($current_page == 'cash_tally') { ?>
        <script src="js/cash_tally.js"></script>
    <?php }

    //Bank Clearance
    if ($current_page == 'bank_clearance') { ?>
        <script src="js/bank_clearance.js"></script>
    <?php }

    if ($current_page == 'edit_bank_clearance') { ?>
        <script src="js/edit_bank_clearance.js"></script>
    <?php }

    //Financial Insights
    if ($current_page == 'finance_insight') { ?>
        <script src="js/finance_insight.js"></script>
    <?php }

    //Follow up
    if ($current_page == 'promotion_activity') { ?>
        <script src="js/promotion_activity.js"></script>
    <?php }

    if ($current_page == 'due_followup') { ?>
        <script src="js/due_followup.js"></script>
    <?php }

    if ($current_page == 'due_followup_info') { ?>
        <script src="js/due_followup_info.js"></script>
    <?php }

    if ($current_page == 'edit_due_followup') { ?>
        <script src="js/edit_due_followup.js"></script>
    <?php }

    if ($current_page == 'loan_followup') { ?>
        <script src="js/loan_followup.js"></script>
    <?php }

    if ($current_page == 'confirmation_followup') { ?>
        <script src="js/confirmation_followup.js"></script>
    <?php }

    if ($current_page == 'ledger_report') { ?>
        <script src="js/ledger_report.js"></script>
    <?php }
    if ($current_page == 'request_report') { ?>
        <script src="js/request_report.js"></script>
    <?php }
    if ($current_page == 'cancel_revoke_report') { ?>
        <script src="js/cancel_revoke_report.js"></script>
    <?php }
    if ($current_page == 'cus_profile_report') { ?>
        <script src="js/cus_profile_report.js"></script>
    <?php }
    if ($current_page == 'loan_issue_report') { ?>
        <script src="js/loan_issue_report.js"></script>
    <?php }
    if ($current_page == 'collection_report') { ?>
        <script src="js/collection_report.js"></script>
    <?php }
    if ($current_page == 'closed_report') { ?>
        <script src="js/closed_report.js"></script>
    <?php }
    if ($current_page == 'balance_report') { ?>
        <script src="js/balance_report.js"></script>
    <?php }
    if ($current_page == 'agent_report') { ?>
        <script src="js/agent_report.js"></script>
    <?php }
    if ($current_page == 'search_module') { ?>
        <script src="js/search_module.js"></script>
    <?php }

    if ($current_page == 'bulk_upload') { ?>
        <script src="js/bulk_upload.js"></script>
    <?php }
    if ($current_page == 'loan_track') { ?>
        <script src="js/loan_track.js"></script>
    <?php }

    if ($current_page == 'sms_generation') { ?>
        <script src="js/sms_generation.js"></script>
    <?php }

    ?>


    <script src="js/logincreation.js"></script>

    <!-- Slimscroll JS -->
    <script src="vendor/slimscroll/slimscroll.min.js"></script>
    <script src="vendor/slimscroll/custom-scrollbar.js"></script>


    <!-- Datepickers -->
    <script src="vendor/datepicker/js/picker.js"></script>
    <script src="vendor/datepicker/js/picker.date.js"></script>
    <script src="vendor/datepicker/js/custom-picker.js"></script>

    <script type="text/javascript">
        // item delete
        $(document).on("click", '.delete_company', function() {
            var dlt = confirm("Do you want to delete this Company ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });

        // loan category delete
        $(document).on("click", '.delete_loan_calculation', function() {
            var dlt = confirm("Do you want to delete this Loan Category ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });
        // loan category delete
        $(document).on("click", '.loan_category_delete', function() {
            var dlt = confirm("Do you want to delete this Loan Category ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });

        // Branch Creation delete
        $(document).on("click", '.delete_branch', function() {
            var dlt = confirm("Do you want to delete this Branch ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });
        // Area Creation delete
        $(document).on("click", '.delete_area', function() {
            var dlt = confirm("Do you want to delete this Area ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });
        // Loan Scheme delete
        $(document).on("click", '.delete_loan_scheme', function() {
            var dlt = confirm("Do you want to delete this Scheme ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });
        // Area Mapping delete
        $(document).on("click", '.delete_area_mapping', function() {
            var dlt = confirm("Do you want to delete this Mapping ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });

        // Director creation delete
        $(document).on("click", '.delete_dir', function() {
            var dlt = confirm("Do you want to delete this Director ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });
        // Agent creation delete
        $(document).on("click", '.delete_ag', function() {
            var dlt = confirm("Do you want to delete this Agent ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });
        // Staff creation delete
        $(document).on("click", '.delete_staff', function() {
            var dlt = confirm("Do you want to delete this Staff ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });
        // Manage user delete
        $(document).on("click", '.delete_user', function() {
            var dlt = confirm("Do you want to delete this User ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });
        // Bank Creation delete
        $(document).on("click", '.delete_bank', function() {
            var dlt = confirm("Do you want to delete this Bank Account ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });
        // Documentation Mapping delete
        $(document).on("click", '.delete_doc_mapping', function() {
            var dlt = confirm("Do you want to delete this Documentation Mapping ?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });

        // Request Actions
        $(document).on("click", '.removerequest', function() {
            var dlt = confirm("Do you want to Remove this Request?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });


        // Verification Actions
        $(document).on("click", '.removeverification', function() {
            var dlt = confirm("Do you want to Remove this Verification?");
            if (dlt) {
                return true;
            } else {
                return false;
            }
        });

        $(document).on("click", '.removeapproval', function() {
            var appdlt = confirm("Do you want to Remove this Approval?");
            if (appdlt) {
                return true;
            } else {
                return false;
            }
        });

        $(document).on("click", '.ack-remove', function() {
            var appdlt = confirm("Do you want to remove this Acknowledgement?");
            if (appdlt) {
                return true;
            } else {
                return false;
            }
        });
    </script>

    <script>
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 2000);

        $('.modal').attr({
            'data-backdrop': "static",
            'data-keyboard': "false"
        }); //this will disable clicking outside of a modal in overall project

        $('input').attr('autocomplete', 'off');


        window.alert = function(message) { // this will prevent normal window.alert messages to set it as swal

            Swal.fire({
                text: message,
                target: 'body',
                toast: true,
                position: 'top-right',
                // background: '#00e2cd',
                timer: 2000,
                showConfirmButton: true,
                confirmButtonColor: '#f2372b',
                timerProgressBar: true,
                // allowOutsideClick: false, // Disable outside click
                // allowEscapeKey: false, // Disable escape key
                // allowEnterKey: false, // Disable enter key
            })

            return false;
        };


        function moneyFormatIndia(num) {
            var isNegative = false;
            if (num < 0) {
                isNegative = true;
                num = Math.abs(num);
            }

            var explrestunits = "";
            if (num.toString().length > 3) {
                var lastthree = num.toString().substr(num.toString().length - 3);
                var restunits = num.toString().substr(0, num.toString().length - 3);
                restunits = (restunits.length % 2 == 1) ? "0" + restunits : restunits;
                var expunit = restunits.match(/.{1,2}/g);
                for (var i = 0; i < expunit.length; i++) {
                    if (i == 0) {
                        explrestunits += parseInt(expunit[i]) + ",";
                    } else {
                        explrestunits += expunit[i] + ",";
                    }
                }
                var thecash = explrestunits + lastthree;
            } else {
                var thecash = num;
            }

            return isNegative ? "-" + thecash : thecash;
        }

        function searchFunction(table_name) {
            let DACC = <?php echo DACC; ?>;

            $('#search').attr({
                'title': 'Click Outside to search',
                'autocomplete': 'off'
            })
            // new search on keyup event for search by display content
            $('#search').off().on('blur', function(e) {
                // if (e.which == 10 && e.ctrlKey == true) { //control and enter key pressed then key value will be 10
                let table = $(`#${table_name}`).DataTable();
                table.search(this.value).draw();
                // }
            });

            $('.dropdown').click(function(event) {
                let linkcheck = $('.dropdown .dropdown-content a').attr('href');
                if (linkcheck == '#' || linkcheck == undefined) {
                    event.preventDefault();
                }
                $('.dropdown').not(this).removeClass('active');
                $(this).toggleClass('active');
            });

            $(document).click(function(event) {
                var target = $(event.target);
                if (!target.closest('.dropdown').length) {
                    $('.dropdown').removeClass('active');
                }
            });

            // Check if DACC is 1 and hide Excel button if true
            if (DACC === 1) {
                // Find and remove the Excel button
                let table = $(`#${table_name}`).DataTable();
                table.buttons().container().find('.buttons-excel').hide();
            }
        }


        ////////// Show Loader if ajax function is called inside anywhere in entire project  ////////

        $(document).ajaxStart(function() {
            showOverlay();
        });

        $(document).ajaxStop(function() {
            hideOverlay();
        });

        function compressImage(input, targetSizeKB) {
            if (input.files.length > 0) {
                const fileSize = input.files[0].size; // Get the size of the selected file
                const maxSize = targetSizeKB * 1024; // Maximum size in bytes (200 KB)
                if (fileSize > maxSize) {
                    console.log("hjhhhh");
                    const file = input.files[0];
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const img = new Image();
                        img.onload = () => {
                            const canvas = document.createElement("canvas");
                            const ctx = canvas.getContext("2d");

                            // Resize image if needed
                            const maxSize = 800;
                            let {
                                width,
                                height
                            } = img;
                            if (width > maxSize || height > maxSize) {
                                const scale = Math.min(maxSize / width, maxSize / height);
                                width *= scale;
                                height *= scale;
                            }

                            // Set canvas dimensions and draw the image
                            canvas.width = width;
                            canvas.height = height;
                            ctx.drawImage(img, 0, 0, width, height);

                            // Compress and check size
                            let quality = 0.9; // Start with high quality
                            const targetSizeBytes = targetSizeKB * 1024;

                            function compress() {
                                canvas.toBlob((blob) => {
                                    if (blob.size > targetSizeBytes && quality > 0.1) {
                                        quality -= 0.1;
                                        compress(); // Retry with lower quality
                                    } else if (blob.size <= targetSizeBytes) {
                                        const compressedFile = new File([blob], file.name, {
                                            type: file.type,
                                            lastModified: Date.now(),
                                        });

                                        // Replace the input file with the compressed file
                                        const dataTransfer = new DataTransfer();
                                        dataTransfer.items.add(compressedFile);
                                        input.files = dataTransfer.files;
                                    } else {
                                        alert("Unable to compress below the target size.");
                                    }
                                }, file.type, quality);
                            }

                            compress();
                        };
                        img.src = event.target.result;
                    };

                    reader.readAsDataURL(file);
                }
            }
        }
        // function checkInputFileSize(input, allowdsize) {
        //     if (input.files.length > 0) {
        //         const fileSize = input.files[0].size; // Get the size of the selected file
        //         const maxSize = allowdsize * 1024; // Maximum size in bytes (200 KB)

        //         if (fileSize > maxSize) {
        //             alert("Maximum File Size " + allowdsize + " KB. Please select a smaller file.");
        //             input.value = ''; // Clear the selected file
        //         }
        //     }
        // }

        //Document Track on click function 
        function getDocOnClickFunction() {
            $('.view-track').click(function() {
                var cus_id = $(this).data('cusid');
                var cus_name = $(this).data('cusname');
                var req_id = $(this).data('reqid');
                $.ajax({
                    url: 'documentTrackFile/viewTrack.php',
                    type: 'post',
                    data: {
                        'cus_id': cus_id,
                        "req_id": req_id
                    },
                    cache: false,
                    success: function(html) {
                        $('#viewTrackDiv').empty();
                        $('#viewTrackDiv').html(html);
                    }
                }).then(function() {
                    getAllDocumentList(req_id, cus_name, cus_id);
                }); //then function end
            }); //click function end

            $('.receive-track').click(function() {
                var tableid = $(this).data('id');
                event.preventDefault();
                if (confirm('Are you sure to Mark this Track as Received?')) {
                    $.ajax({
                        url: 'documentTrackFile/receiveTrack.php',
                        type: 'post',
                        data: {
                            'id': tableid
                        },
                        cache: false,
                        success: function(response) {
                            swalAlert(response);
                            setTimeout(() => {
                                window.location = 'document_track';
                            }, 1500)
                        }
                    });
                }
            });

            $('.send-track').click(function() {
                var tableid = $(this).data('id');
                event.preventDefault();
                if (confirm('Are you sure to Mark this Track as Sent?')) {
                    $.ajax({
                        url: 'documentTrackFile/sendTrack.php',
                        type: 'post',
                        data: {
                            'id': tableid
                        },
                        cache: false,
                        success: function(response) {
                            swalAlert(response);
                            setTimeout(() => {
                                window.location = 'document_track';
                            }, 1500)
                        }
                    });
                }
            });

            $('.remove-track').click(function() {
                var tableid = $(this).data('id');
                event.preventDefault();
                if (confirm('Are you sure to Remove this Track from List?')) {
                    $.ajax({
                        url: 'documentTrackFile/removeTrack.php',
                        type: 'post',
                        data: {
                            'id': tableid
                        },
                        cache: false,
                        success: function(response) {
                            swalAlert(response);
                            setTimeout(() => {
                                window.location = 'document_track';
                            }, 1500)
                        }
                    });
                }
            });
        }
    </script>