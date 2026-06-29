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

    .dt-button-collection .buttons-columnVisibility.active-column {
        background: #c6f7c6 !important;
        background-color: #c6f7c6 !important;
        box-shadow: none !important;
        filter: none !important;
    }

    .dt-button-collection .buttons-columnVisibility.inactive-column {
        background: #f7c6c6 !important;
        background-color: #f7c6c6 !important;
        box-shadow: none !important;
        filter: none !important;
    }
</style>

<?php $current_page = isset($_GET['page']) ? $_GET['page'] : null; ?>

<!-- Required jQuery first, then Bootstrap Bundle JS -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/moment.js"></script>
    <!-- <script src="js/jspdf.js"></script>
<script src="js/xlsx.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

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
        // Detect page reload once when the page loads
        const isPageReloaded = performance.getEntriesByType("navigation")[0]?.type === "reload";

        function curDateJs(title) {
            // Get current date and time
            const now = new Date();

            // Format date as DD-MM-YYYY
            const formattedDate = new Intl.DateTimeFormat('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).format(now).replaceAll('/', '-');

            // Format time as HH-MM-SS AM/PM
            const time = new Intl.DateTimeFormat('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            }).format(now).replaceAll(':', '-');

            // Combine both            
            return `${title}_${formattedDate}_${time}`;
        }

        $(document).ready(function() {

            showOverlay();
            window.addEventListener('load', function() {
                hideOverlay();
            });

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

            var company_creation_table = $('#company_creation_table').DataTable({
                ...getStateSaveConfig('company_creation_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('company_creation_table'),
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
                        extend: 'excelHtml5',
                        title: 'Company List',
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Company_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('company_creation_table');
                    toggleAddButton();
                }
            });
            initColVisFeatures(company_creation_table, 'company_creation_table');

            var branch_creation_info = $('#branch_creation_info').DataTable({
                ...getStateSaveConfig('branch_creation_info'),
                "order": [
                    [0, "asc"]
                ],
                "displayStart": getDisplayStart('branch_creation_info'),
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
                        title: "Branch List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Branch_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('branch_creation_info');
                }
            });
            initColVisFeatures(branch_creation_info, 'branch_creation_info');

            var loan_creation_table = $('#loan_creation_table').DataTable({
                ...getStateSaveConfig('loan_creation_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('loan_creation_table'),
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
                        title: "Loan Category List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Loan_Category_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('loan_creation_table');
                }
            });
            initColVisFeatures(loan_creation_table, 'loan_creation_table');

            // Loan Calculation datatable
            var loan_calculation_info = $('#loan_calculation_info').DataTable({
                ...getStateSaveConfig('loan_calculation_info'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('loan_calculation_info'),
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
                        title: "Loan Calculation List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Loan_Calculation_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('loan_calculation_info');
                }
            });
            initColVisFeatures(loan_calculation_info, 'loan_calculation_info');

            var area_creation_info = $('#area_creation_info').DataTable({
                ...getStateSaveConfig('area_creation_info'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('area_creation_info'),
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
                        title: "Area List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Area_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('area_creation_info');
                }
            });
            initColVisFeatures(area_creation_info, 'area_creation_info');

            // Director Creation datatable
            var director_creation_table = $('#director_creation_table').DataTable({
                ...getStateSaveConfig('director_creation_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('director_creation_table'),
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
                        title: "Director List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Director_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('director_creation_table');
                }
            });
            initColVisFeatures(director_creation_table, 'director_creation_table');

            // Agent Creation datatable
            var agent_creation_table = $('#agent_creation_table').DataTable({
                ...getStateSaveConfig('agent_creation_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('agent_creation_table', 10),
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
                        title: "Agent List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Agent_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('agent_creation_table');
                }
            });
            initColVisFeatures(agent_creation_table, 'agent_creation_table');

            //Bank Creation Table
            var bank_creation_table = $('#bank_creation_table').DataTable({
                ...getStateSaveConfig('bank_creation_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('bank_creation_table'),
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
                        title: "Bank List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Bank_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('bank_creation_table');
                }
            });
            initColVisFeatures(bank_creation_table, 'bank_creation_table');

            // Documentation Mapping datatable
            var doc_mapping_table = $('#doc_mapping_table').DataTable({
                ...getStateSaveConfig('doc_mapping_table'),
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
            initColVisFeatures(doc_mapping_table, 'doc_mapping_table');

            // Request datatable
            var request_table = $('#request_table').DataTable({
                ...getStateSaveConfig('request_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('request_table'),
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
                        title: "Request List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Request_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('request_table');
                }
            });
            initColVisFeatures(request_table, 'request_table');

            // Verification datatable
            var verification_table = $('#verification_table').DataTable({
                ...getStateSaveConfig('verification_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('verification_table'),
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
                        title: "Verification List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Verification_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('verification_table');
                }
            });
            initColVisFeatures(verification_table, 'verification_table');

            // Approval datatable
            var approval_table = $('#approval_table').DataTable({
                ...getStateSaveConfig('approval_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('approval_table'),
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
                        title: "Approval List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Approval_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('approval_table');
                }
            });
            initColVisFeatures(approval_table, 'approval_table');

            // Acknowledgement List
            var acknowledge_table = $('#acknowledge_table').DataTable({
                ...getStateSaveConfig('acknowledge_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('acknowledge_table'),
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
                        title: "Acknowledgement List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Acknowledgement_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('acknowledge_table');
                }
            });
            initColVisFeatures(acknowledge_table, 'acknowledge_table');

            // Loan Issue List
            var loanIssue_table = $('#loanIssue_table').DataTable({
                ...getStateSaveConfig('loanIssue_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('loanIssue_table'),
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
                        title: "Loan Issue List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Loan_Issue_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('loanIssue_table');
                }
            });
            initColVisFeatures(loanIssue_table, 'loanIssue_table');

            // accounts Loan Issue Table
            // Loan Issue List
            var accountsloanIssue_table = $('#accountsloanIssue_table').DataTable({
                ...getStateSaveConfig('accountsloanIssue_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('accountsloanIssue_table'),
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxAccountsLoanIssueFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "Loan Issue List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Accounts_Loan_Issue_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    searchFunction('accountsloanIssue_table');
                    paginationFunction('accountsloanIssue_table');
                }
            });
            initColVisFeatures(accountsloanIssue_table, 'accountsloanIssue_table');

            // Closed
            var closed_table = $('#closed_table').DataTable({
                ...getStateSaveConfig('closed_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('closed_table'),
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
                        title: "Closed List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Closed_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('closed_table');
                }
            });
            initColVisFeatures(closed_table, 'closed_table');

            //NOC Table
            var noc_table = $('#noc_table').DataTable({
                ...getStateSaveConfig('noc_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('noc_table'),
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
                        title: "NOC List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('NOC_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('noc_table');
                }
            });
            initColVisFeatures(noc_table, 'noc_table');

            //NOC Handover Table 
            var noc_handover_table = $('#noc_handover_table').DataTable({
                ...getStateSaveConfig('noc_handover_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('noc_handover_table'),
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxNocHandoverFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "NOC Handover List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('NOC_Handover_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    searchFunction('noc_handover_table');
                    paginationFunction('noc_handover_table');
                }
            });
            initColVisFeatures(noc_handover_table, 'noc_handover_table');

            //UPDATE Table
            // var update_table = $('#update_table').DataTable({
            //     "order": [
            //         [0, "desc"]
            //     ],
            //     "displayStart": getDisplayStart('update_table'),
            //     'processing': true,
            //     'serverSide': true,
            //     'serverMethod': 'post',
            //     'ajax': {
            //         'url': 'ajaxFetch/ajaxUpdateFetch.php',
            //         'data': function(data) {
            //             var search = $('input[type=search]').val();
            //             data.search = search;
            //         }
            //     },
            //     dom: 'lBfrtip',
            //     buttons: [{
            //             extend: 'excel',
            //             title: "Update List",
            //             action: function (e, dt, button, config) {
            //                 var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
            //                 var dynamic = curDateJs('Update_List'); // or any base
            //                 config.title = dynamic;      // for versions that use title as filename
            //                 config.filename = dynamic;   // for html5 filename
            //                 defaultAction.call(this, e, dt, button, config);
            //             }
            //         },
            //         {
            //             extend: 'colvis',
            //             collectionLayout: 'fixed four-column',
            //         }
            //     ],
            //     "lengthMenu": [
            //         [10, 25, 50, -1],
            //         [10, 25, 50, "All"]
            //     ],
            //     'drawCallback': function() {
            //         searchFunction('update_table');
            //         paginationFunction('update_table');
            //     }
            // });

            //Document Track Table
            var doc_track_table = $('#doc_track_table').DataTable({
                ...getStateSaveConfig('doc_track_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('doc_track_table'),
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
                        title: "Document Track List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Document_Track_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('doc_track_table');
                    getDocOnClickFunction();
                }
            });
            initColVisFeatures(doc_track_table, 'doc_track_table');

            //NOC replace Table
            var noc_replace_table = $('#noc_replace_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('noc_replace_table'),
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'ajaxFetch/ajaxNocReplaceFetch.php',
                    'data': function(data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                    }
                },
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'excel',
                        title: "DOC Replace List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('NOC_Replace_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    searchFunction('noc_replace_table');
                    paginationFunction('noc_replace_table');
                }
            });
            initColVisFeatures(noc_replace_table, 'noc_replace_table');

            //Concern Table
            var concern_table = $('#concern_table').DataTable({
                ...getStateSaveConfig('concern_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('concern_table'),
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
                        title: "Concern List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Concern_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('concern_table');
                }
            });
            initColVisFeatures(concern_table, 'concern_table');

            //Concern Solution Table
            var concern_solution_table = $('#concern_solution_table').DataTable({
                ...getStateSaveConfig('concern_solution_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('concern_solution_table'),
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
                        title: "Concern Solution List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Concern_Solution_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('concern_solution_table');
                }
            });
            initColVisFeatures(concern_solution_table, 'concern_solution_table');

            //Concern Feedback Table
            var concern_feedback_table = $('#concern_feedback_table').DataTable({
                ...getStateSaveConfig('concern_feedback_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('concern_feedback_table'),
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
                        title: "Concern Feedback List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Concern_Feedback_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('concern_feedback_table');
                }
            });
            initColVisFeatures(concern_feedback_table, 'concern_feedback_table');

            //SMS Generation
            var customer_birthday_table = $('#customer_birthday_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('customer_birthday_table'),
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
                        title: "Customer Birthday List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Customer_Birthday_List'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('customer_birthday_table');
                }
            });

            var loan_follow_table = $('#loan_follow_table').DataTable({
                ...getStateSaveConfig('loan_follow_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('loan_follow_table'),
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
                        title: "Loan Followup List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Loan_Followup'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('loan_follow_table');
                    loanFollowupTableOnclick();
                }
            });
            initColVisFeatures(loan_follow_table, 'loan_follow_table');

            var conf_follow_table = $('#conf_follow_table').DataTable({
                ...getStateSaveConfig('conf_follow_table'),
                "order": [
                    [0, "desc"]
                ],
                "displayStart": getDisplayStart('conf_follow_table'),
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
                        title: "Confirmation Followup List",
                        action: function(e, dt, button, config) {
                            var defaultAction = $.fn.dataTable.ext.buttons.excelHtml5.action;
                            var dynamic = curDateJs('Confirmation_Followup'); // or any base
                            config.title = dynamic; // for versions that use title as filename
                            config.filename = dynamic; // for html5 filename
                            defaultAction.call(this, e, dt, button, config);
                        }
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
                    paginationFunction('conf_follow_table');
                    confirmationTableOnClick();
                }
            });
            initColVisFeatures(conf_follow_table, 'conf_follow_table');

            $('#search_input_').keyup(function() {
                let search_content = $('#search_input_').val();
                $.post('searchScreens.php', {
                    search_content
                }, function(response) {
                    if (response.status == 'Fetched') {
                        let append = '';
                        $.each(response, function(index, val) {
                            if (val.display_name != undefined) {
                                append += "<li class='dropdown-contents'><a href='" + val.module_name + "'>" + val.display_name + "</a></li>";
                            }
                        })
                        $('#search_ul').empty().append(append);
                    }
                }, 'json')
            });

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
            // $(document).on("click", '.delete_area', function() {
            //     var dlt = confirm("Do you want to delete this Area ?");
            //     if (dlt) {
            //         return true;
            //     } else {
            //         return false;
            //     }
            // });

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
                    timer: 5000,
                    showConfirmButton: true,
                    confirmButtonColor: '#f2372b',
                    timerProgressBar: true,
                    // allowOutsideClick: false, // Disable outside click
                    // allowEscapeKey: false, // Disable escape key
                    // allowEnterKey: false, // Disable enter key
                })

                return false;
            };

            //running every 6 secs so it may cause issue while in high traffic so code commented. Need to check anyother solution to solve cache issue from user end.
            // // For Hard Reload and need to create a text file version   
            // let currentVersion = null;

            // fetch("version.txt?rand=" + Math.random())
            //     .then(res => res.text())
            //     .then(v => currentVersion = v.trim());

            // // Check every 5 seconds
            // setInterval(() => {
            //     fetch("version.txt?rand=" + Math.random())
            //         .then(res => res.text())
            //         .then(serverVersion => {
            //             if (currentVersion && serverVersion.trim() !== currentVersion) {
            //                 location.reload(true);
            //             }
            //         });
            // }, 6000);

            // Centralized handler for showing customer status modal
            $(document).on('click', 'a.customer-status', async function(event) {
                event.preventDefault();
                try {
                    var cus_id = $(this).data('value');
                    var screen = $(this).data('screen') || ''; // To show extra columns in acknowledgement screen
                    showOverlay(); //loader start

                    //to get customer existing type
                    if(screen =='request'){
                        $.ajax({
                            url: 'requestFile/getCustomerStatus1.php',
                            data: { "cus_id": cus_id },
                            type: 'post',
                            cache: false,
                            success: function (response) {
                                $('#exist_type').val(response);
                            }
                        });
                    }

                    // Wait here until the function COMPLETES
                    let status = await callresetCustomerStatus(cus_id); //this function will give the customer's status like pending od current

                    let {
                        pending_sts,
                        od_sts,
                        due_nil_sts,
                        closed_sts,
                        bal_amt
                    } = status; //take all the values from the function then send to customer status file to fetch details

                    $.ajax({
                        url: 'requestFile/getCustomerStatus.php',
                        type: 'POST',
                        data: {
                            cus_id,
                            pending_sts,
                            od_sts,
                            due_nil_sts,
                            closed_sts,
                            bal_amt,
                            screen
                        },
                        cache: false,
                        success: function(response) {
                            $('#cusHistoryTable').empty().html(response);

                            $('#cusHistoryTable tbody tr').each(function() {
                                var val = $(this).find('td:nth-child(6)').text().trim();

                                if (['Request', 'Verification', 'Approval', 'Acknowledgement', 'Issue'].includes(val)) {
                                    $(this).find('td:nth-child(6)').css({
                                        backgroundColor: 'rgba(240,0,0,0.8)',
                                        color: 'white',
                                        fontWeight: 'bolder'
                                    });
                                } else if (val === 'Present') {
                                    $(this).find('td:nth-child(6)').css({
                                        backgroundColor: 'rgba(0,160,0,0.8)',
                                        color: 'white',
                                        fontWeight: 'bolder'
                                    });
                                } else if (val === 'Closed') {
                                    $(this).find('td:nth-child(6)').css({
                                        backgroundColor: 'rgba(0,0,255,0.8)',
                                        color: 'white',
                                        fontWeight: 'bolder'
                                    });
                                }
                            });
                        },
                        complete: function() {
                            hideOverlay();
                        }
                    });

                } catch (err) {
                    console.error(err);
                    hideOverlay();
                }
            });

            $(document).on('click', 'a.loan-summary', function (event) {
                event.preventDefault();
                var cus_id = $(this).data('value');
                $.ajax({
                    url: 'requestFile/getLoanSummary.php',
                    data: { "cus_id": cus_id },
                    type: 'post',
                    cache: false,
                    success: function (response) {
                        $('#loanSummaryTable').empty();
                        $('#loanSummaryTable').html(response);
                    }
                })
            });

            $(document).on('click', '.loan-follow-chart', function () {
                let cus_id = $(this).data('cusid');
                $.post('followupFiles/loanFollowup/getLoanFollowupChart.php', { cus_id }, function (html) {
                    $('#loanFollowChartDiv').empty().html(html);
                })
            });

        }); //Document Ready End

        $(document).on('click', '.dropdown', function(event) {
            var $target = $(event.target);

            // If clicking inside dropdown content
            if ($target.closest('.dropdown-content').length) {
                if ($target.is('a')) {
                    let href = $target.attr('href');
                    if (href === '#' || href === '') {
                        event.preventDefault();
                    }
                }
                return;
            }

            // Toggle dropdown
            event.preventDefault();
            event.stopPropagation();

            $('.dropdown').not(this).removeClass('active');
            $(this).toggleClass('active');
        });

        // Close on outside click
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.dropdown').length) {
                $('.dropdown').removeClass('active');
            }
        });

        $(document).on('click', '.logout-link', function(event) {
            event.preventDefault();
            event.stopPropagation();
            $('.dropdown').removeClass('active');
            window.location.href = 'logout.php'; // Redirect to logout script
        });

        ////////// Show Loader if ajax function is called inside anywhere in entire project  ////////
        $(document).ajaxStart(function() {
            showOverlay();
            // Stop session timers while AJAX is in progress
            clearTimeout(warningTimeout);
            clearTimeout(logoutTimeout);
        });

        $(document).ajaxStop(function() {
            hideOverlay();
            resetTimers(); // Reset again after AJAX completes
        });

        // Function to add the overlay
		function showOverlay() {
			var overlayDiv = document.createElement('div');
			overlayDiv.classList.add('overlay');
			document.body.appendChild(overlayDiv);

			var loaderDiv = document.createElement('div');
			loaderDiv.classList.add('loader');
			overlayDiv.appendChild(loaderDiv);

			var overlayText = document.createElement('span');
			overlayText.classList.add('overlay-text');
			overlayText.innerText = 'Please Wait';
			overlayDiv.appendChild(overlayText);
		}

		// Function to remove the overlay and clear the timer
		function hideOverlay() {
			var overlayDiv = document.querySelector('.overlay');
			overlayDiv.remove();
		}

        // Centralized function to get customer loan status
        function callresetCustomerStatus(cus_id) {
			return new Promise((resolve, reject) => {
				$.ajax({
					url: 'collectionFile/resetCustomerStatus.php',
					type: 'POST',
					data: { 'cus_id': cus_id },
					dataType: 'json',
					cache: false,
					success: function(response) {
                        // Resolve with empty strings if response is empty, to prevent errors
						const status = {
							pending_sts: (response?.pending_customer || []).join(','),
							od_sts: (response?.od_customer || []).join(','),
							due_nil_sts: (response?.due_nil_customer || []).join(','),
							closed_sts: (response?.closed_customer || []).join(','),
							bal_amt: (response?.balAmnt || []).join(',')
						};
						resolve(status);
					},
					error: function(xhr, status, error) {
						reject(error);
					}
				});
			});
		}

        function moneyFormatIndia(num) {
            var isNegative = false;
            if (num < 0) {
                isNegative = true;
                num = Math.abs(num);
            }

            // Round to 2 decimals to avoid float precision issues
            num = Math.round(Number(num) * 100) / 100;

            var hasDecimal = num % 1 !== 0;

            // 🔹 Split decimal part (minimal addition)
            var parts = num.toString().split('.');
            var intPart = parts[0];
            var decPart = hasDecimal ? '.' + (parts[1] || '').padEnd(2, '0').substring(0, 2) : '';

            var explrestunits = "";

            if (intPart.length > 3) {
                var lastthree = intPart.substr(intPart.length - 3);
                var restunits = intPart.substr(0, intPart.length - 3);
                restunits = (restunits.length % 2 == 1) ? "0" + restunits : restunits;
                var expunit = restunits.match(/.{1,2}/g);

                for (var i = 0; i < expunit.length; i++) {
                    explrestunits += (i === 0 ? parseInt(expunit[i], 10) : expunit[i]) + ',';
                }

                var thecash = explrestunits + lastthree + decPart;
            } else {
                var thecash = intPart + decPart;
            }

            return isNegative ? "-" + thecash : thecash;
        }

        function searchFunction(table_name) {
            let DACC = <?php echo DACC; ?>;

            $(`#search, #${table_name}_search`).attr({
                'title': 'Click Outside to search',
                'autocomplete': 'off'
            })
            // new search on keyup event for search by display content
            $(`#search, #${table_name}_search`).off().on('blur', function(e) {
                // if (e.which == 10 && e.ctrlKey == true) { //control and enter key pressed then key value will be 10
                let table = $(`#${table_name}`).DataTable();
                table.search(this.value).draw();
                // }
            });

            // Check if DACC is 1 and hide Excel button if true
            if (DACC === 1) {
                // Find and remove the Excel button
                let table = $(`#${table_name}`).DataTable();
                table.buttons().container().find('.buttons-excel').hide();
            }
        }

        function compressImage(input, targetSizeKB) {
            if (input.files.length > 0) {
                const fileSize = input.files[0].size; // Get the size of the selected file
                const maxSize = targetSizeKB * 1024; // Maximum size in bytes (200 KB)
                if (fileSize > maxSize) {
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
            $('.view-track, .combine-doc').click(function() {
                var cus_name = $(this).data('cusname');
                var req_id = $(this).data('reqid');
                var useINcondition = $(this).data('multi-reqid') ?? '0';
                var currentReqId = $(this).data('current-req-id');
                if (useINcondition == '1') {
                    $('#combine_doc')
                        .attr('reqid', currentReqId)
                        .show();
                } else {
                    $('#combine_doc')
                        .removeAttr('reqid')
                        .hide();
                }

                $.ajax({
                    url: 'documentTrackFile/viewTrack.php',
                    type: 'post',
                    data: {},
                    cache: false,
                    success: function(html) {
                        $('#viewTrackDiv').html(html);
                    }
                }).then(function() {
                    getAllDocumentList(req_id, cus_name);
                }); //then function end
            }); //click function end

            $('.receive-track').click(function() {
                var tableid = $(this).data('id');
                let cusid = $(this).data('cusid');
                let replace_status = $(this).data('replace-status');
                event.preventDefault();
                if (confirm('Are you sure to Mark this Track as Received?')) {
                    $.ajax({
                        url: 'documentTrackFile/receiveTrack.php',
                        type: 'post',
                        data: {
                            'id': tableid,
                            'cus_id': cusid,
                            replace_status
                        },
                        cache: false,
                        success: function(response) {
                            swalAlert(response).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = 'document_track';
                                }
                            });
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
                            swalAlert(response).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = 'document_track';
                                }
                            });
                        }
                    });
                }
            });

            $('.return-track').click(function() {
                var tableid = $(this).data('id');
                event.preventDefault();
                if (confirm('Are you sure to Return this Track to Issued User?')) {
                    $.ajax({
                        url: 'documentTrackFile/removeTrack.php',
                        type: 'post',
                        data: {
                            'id': tableid
                        },
                        cache: false,
                        success: function(response) {
                            swalAlert(response).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = 'document_track';
                                }
                            });
                        }
                    });
                }
            });
        }

        //////////////////////////////////// Pagination  Start ////////////////////////////////////

        function paginationFunction(tableId) {
            const table = $(`#${tableId}`).DataTable();
            const pagination = $(`#${tableId}_paginate`);
            const pageInfo = table.page.info();

            // If no data, show disabled pagination
            if (pageInfo.pages === 0) {
                pagination.html('<span class="paginate_button disabled">No pages</span>');
                return;
            }

            const currentPage = pageInfo.page;
            const totalPages = pageInfo.pages;
            const maxVisible = 6;

            // Save current page
            localStorage.setItem(`${tableId}_currentPage`, currentPage);

            pagination.empty();

            const addButton = (label, pageNum, isActive = false, isDisabled = false) => {
                const classList = ['paginate_button'];
                if (isActive) classList.push('current');
                if (isDisabled) classList.push('disabled');
                pagination.append(`<span class="${classList.join(' ')}" data-page="${pageNum}">${label}</span>`);
            };

            // Show even for 1 page
            addButton('Previous', currentPage - 1, false, currentPage === 0);

            // Always show first page
            addButton(1, 0, currentPage === 0);

            if (totalPages > 1) {
                let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
                let end = start + maxVisible - 1;

                if (end >= totalPages - 1) {
                    end = totalPages - 2;
                    start = Math.max(1, end - maxVisible + 1);
                }

                if (start > 1) pagination.append('<span class="paginate_ellipsis">...</span>');

                for (let i = start; i <= end; i++) {
                    addButton(i + 1, i, currentPage === i);
                }

                if (end < totalPages - 2) pagination.append('<span class="paginate_ellipsis">...</span>');

                // Always show last page
                addButton(totalPages, totalPages - 1, currentPage === totalPages - 1);
            }

            addButton('Next', currentPage + 1, false, currentPage === totalPages - 1 || totalPages === 1);

            // Jump to page input (always render it)
            if ($(`#${tableId}_jumpToPage`).length === 0) {
                pagination.append(`<input type="number" id="${tableId}_jumpToPage" min="1" max="${totalPages}" placeholder="Page" style="width: 40px; height: 30px; margin-left: 10px;" />`);
            }

            // Handle pagination click
            pagination.off('click').on('click', '.paginate_button', function() {
                if ($(this).hasClass('disabled')) return;
                const page = parseInt($(this).attr('data-page'));
                if (!isNaN(page)) {
                    table.page(page).draw('page');
                }
            });

            // Handle jump input
            $(`#${tableId}_jumpToPage`).off('keypress').on('keypress', function(e) {
                if (e.which === 13 || e.which === 9) {
                    const inputPage = parseInt($(this).val(), 10);
                    if (!isNaN(inputPage) && inputPage > 0 && inputPage <= totalPages) {
                        table.page(inputPage - 1).draw('page');
                    } else {
                        alert(`Please enter a valid page number (1 - ${totalPages})`);
                    }
                }
            });
        }

        function getDisplayStart(tableId, pageLength = 10) {
            if (isPageReloaded) {
                localStorage.removeItem(`${tableId}_currentPage`);
                return 0;
            }
            const savedPage = localStorage.getItem(`${tableId}_currentPage`);
            return savedPage ? parseInt(savedPage) * pageLength : 0;
        }

        //////////////////////////////////// Pagination  End ////////////////////////////////////

        //////////////////////////////////// Session Logout Time Start ////////////////////////////////////

        /* ---------------- CONFIG ---------------- */
        let warningTimeout, logoutTimeout;
        let swalOpen = false;

        const idleTime = 10 * 60 * 1000; // 10 minutes;
        const warningDuration = 10 * 1000; // 10 seconds
        const STORAGE_KEY = "last-activity";
        const FORCE_LOGOUT_KEY = "force-logout";

        /* ---------------- HELPERS ---------------- */
        const now = () => Date.now();

        const getLastActivity = () =>
            Number(localStorage.getItem(STORAGE_KEY)) || now();

        const setLastActivity = () =>
            localStorage.setItem(STORAGE_KEY, now());

        /* ---------------- TIMER LOGIC ---------------- */
        function startTimers() {
            clearTimeout(warningTimeout);
            clearTimeout(logoutTimeout);

            const idle = now() - getLastActivity();
            const remaining = idleTime - idle;

            if (remaining <= 0) {
                showWarning();
                return;
            }

            warningTimeout = setTimeout(
                showWarning,
                Math.max(remaining - warningDuration, 0)
            );
        }

        /* ---------------- ACTIVITY ---------------- */
        function resetTimers() {
            if (swalOpen) hideWarning();
            setLastActivity();
            startTimers();
        }

        /* ---------------- ALERT ---------------- */
        function showWarning() {
            if (swalOpen) return;

            swalOpen = true;

            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Session will expire in 10 seconds due to inactivity',
                timer: warningDuration,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false
            });

            logoutTimeout = setTimeout(() => {
                window.location.href = 'logout.php';
            }, warningDuration);
        }

        function hideWarning() {
            swalOpen = false;
            Swal.close();
        }

        /* ---------------- EVENT BINDINGS ---------------- */

        // Initial load
        window.addEventListener("load", () => {
            setLastActivity();
            startTimers();
        });

        // Keyboard (capture phase → works with SweetAlert)
        document.addEventListener("keydown", resetTimers, true);

        // Mouse activity
        window.addEventListener("mousemove", resetTimers);

        // STORAGE SYNC (IDLE + FORCE LOGOUT)
        window.addEventListener("storage", (e) => {

            // Idle activity sync
            if (e.key === STORAGE_KEY) {
                if (swalOpen) hideWarning();
                startTimers();
            }

            // Force logout across all tabs
            if (e.key === FORCE_LOGOUT_KEY) {
                window.location.href = 'logout.php';
            }
        });

        //////////////////////////////////// Session Logut Time End ////////////////////////////////////

        // Reusable function to sort a dropdown alphabetically
        function sortDropdownAlphabetically(selectSelector) {
            var $select = $(selectSelector);
            var $firstOption = $select.find('option:first-child');
            $select.find('option:not(:first-child)')
                .sort(function(a, b) {
                    return a.text.localeCompare(b.text);
                })
                .appendTo($select); // moves elements, preserves selected
            $select.prepend($firstOption); // keep first option at top
        }

        function formatIndianNumber(num) {
            if (num == null || num === '') return '';

            num = String(num).replace(/,/g, ''); // remove existing commas

            let lastThree = num.slice(-3);
            let rest = num.slice(0, -3);

            if (rest !== '') {
                rest = rest.replace(/\B(?=(\d{2})+(?!\d))/g, ',');
                num = rest + ',' + lastThree;
            } else {
                num = lastThree;
            }

            return num;
        }

        function validateInputNumber(e, screen) {
            let val = $(e).val();

            if (screen === 'withOutDot') { //Collection track to insert one round off so not allowed dot
                // Remove all non-digit characters
                val = val.replace(/[^0-9]/g, '');

            } else if (screen === 'withDot') {
                // Allow only numbers and dot
                val = val.replace(/[^0-9.]/g, '');
                // Allow only one dot
                val = val.replace(/(\..*)\./g, '$1');

                // Limit to 2 digits after decimal
                if (val.includes('.')) {
                    let parts = val.split('.');
                    parts[1] = parts[1].slice(0, 2);
                    val = parts[0] + '.' + parts[1];
                }
            } else if (screen === 'alphaNumeric') {
                val = val.replace(/[^A-Za-z0-9]/g, ''); // ONLY A-Z, a-z, 0-9
            }
            // ENFORCE MAXLENGTH MANUALLY
            const max = e.getAttribute('maxlength');
            if (max) {
                val = val.slice(0, max);
            }
            // Update the field with only numeric value
            $(e).val(val);
        }

        // To download Excel file
        function exportToExcel(tableId, data, reportName) {
            // ✅ Get table headers dynamically from the given table ID
            const table = document.getElementById(tableId);

            const headers = Array.from(table.querySelectorAll("thead th"))
                .map(th => th.textContent.trim());

            // ✅ Combine headers + data
            const wsData = [headers, ...data];

            // ✅ Create worksheet & workbook
            const ws = XLSX.utils.aoa_to_sheet(wsData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, reportName || "Sheet1");

            // ✅ Generate dynamic filename
            const fileName = curDateJs(reportName || 'Report') + '.xlsx';

            // ✅ Save file
            XLSX.writeFile(wb, fileName);
        }

        function nameFormatter(selector) {
            $(selector).on('input', function() {
                let value = $(this).val();

                // Split by space
                let parts = value.split(" ");

                if (parts.length > 1) {
                    // Convert second part to CAPS and allow only 2 letters
                    parts[1] = parts[1].toUpperCase()
                        .replace(/[^A-Z]/g, "")
                        .substring(0, 2);

                    // Block more than 2 parts
                    if (parts.length > 2) {
                        parts = parts.slice(0, 2);
                    }
                }

                $(this).val(parts.join(" "));
            });
        }

        // <------------------------------------------------------ COLUMN VISIBILITY AND COLOR CHNAGE START -------------------------------------------------------->


        function getStateSaveConfig(tableId) {

            return {
                // Tells DataTables to remember state (we override which parts to save)
                stateSave: true,

                // 🔹 This runs whenever the table state is saved
                stateSaveParams: function(settings) {

                    // Collect ONLY column visibility (true/false for each column)
                    // Ex: [true, false, true, true] means column 2 is hidden
                    const visibility = settings.aoColumns.map(col => col.bVisible);

                    // Save into localStorage, tied to this table's ID
                    // Example key: "company_creation_table_colVis"
                    localStorage.setItem(tableId + "_colVis", JSON.stringify(visibility));
                },

                // 🔹 This runs BEFORE DataTables builds the table
                // It restores the previously saved visibility
                stateLoadParams: function(settings) {

                    // Read saved visibility from local storage
                    const saved = localStorage.getItem(tableId + "_colVis");
                    if (!saved) return; // nothing saved yet → do nothing

                    // Convert back from JSON string → array of booleans
                    const visibility = JSON.parse(saved);

                    // Apply saved visibility to each column
                    visibility.forEach((isVisible, index) => {
                        settings.aoColumns[index].bVisible = isVisible;
                    });
                }
            };
        }

        function initColVisFeatures(table, tableId) {

            const STORAGE_KEY = tableId + "_colVis";
            const COLLECTION_SELECTOR = '.dt-button-collection .buttons-columnVisibility';

            // 1. Sync ColVis button active state with column
            function syncButtonActiveState() {
                table.buttons('.buttons-columnVisibility').each(function(idx) {
                    const btn = table.button(idx);
                    const colIdx = btn.conf?._fnInit?.columns;
                    if (colIdx === undefined) return;
                    btn.active(table.column(colIdx).visible());
                });
            }

            // 2. Apply green/red color classes
            function updateColVisColors() {
                $(COLLECTION_SELECTOR).each(function() {
                    const isActive = $(this).hasClass('active');
                    $(this)
                        .toggleClass('active-column', isActive)
                        .toggleClass('inactive-column', !isActive);
                });
            }

            // 3. One master fix (state + color)
            function fixColVisUI() {
                syncButtonActiveState();
                updateColVisColors();
            }

            // 4. Restore column visibility from localStorage
            function applySavedVisibility() {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (!saved) return;

                JSON.parse(saved).forEach((isVisible, i) => {
                    table.column(i).visible(isVisible, false);
                });

                table.columns.adjust();
            }

            // 5. Event bindings
            function bindColVisEvents() {
                table.on('buttons-collection.dt column-visibility.dt', fixColVisUI);

                $(document)
                    .off('click.colvisFix_' + tableId)
                    .on('click.colvisFix_' + tableId, '.buttons-collection', fixColVisUI);
            }

            // 6. Execution order (DO NOT CHANGE)
            applySavedVisibility(); // restore actual column state
            bindColVisEvents(); // attach listeners
            fixColVisUI(); // initial correction
        }

        // <------------------------------------------------------ COLUMN VISIBILITY AND COLOR CHNAGE END -------------------------------------------------------->

        /////////////////////////////////////////////////////  Check Transaction Details START  //////////////////////////////////////////////////// 
        function checkBankTransactionDetails(crdrType, bankId, transId, amount) {
            return $.post('accountsFile/bankclearance/getBankTransactionDetails.php', {
                crdrType,
                bankId,
                transId,
                amount
            }, null, 'json');
        }
        /////////////////////////////////////////////////////  Check Transaction Details END  //////////////////////////////////////////////////// 
        function scrollToFirstError(formId) {

            let firstError = $(formId).find('span.text-danger:visible').filter(function() {

                let txt = $(this).text().trim();
                return txt !== '' && !txt.startsWith('*');
            }).first();

            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.closest('div').offset().top - 120
                }, 500);
            }
        }

        ///////////////////////////////// swal alert START///////////////////////////////////
        function swalError(title, text) {
            Swal.fire({
                icon: 'error',
                title: title,
                text: text,
                confirmButtonColor: '#009688',
            });
        }
        ///////////////////////////////// swal alert END///////////////////////////////////
        /////////////////////////////////////Notification////////////////////////////
        function loadNotifications() {
            $.ajax({
                url: 'ajaxFetch/notification.php',
                type: 'GET',
                dataType: 'json',
                global: false,
                success: function(res) {
                    let totalTotal = 0;
                    let html = `
                <table style="width:100%; border-collapse: collapse;">
                    <tr style="background:#f0f0f0;">
                        <th style="padding:8px; border:1px solid #ddd;">Screen</th>
                        <th style="padding:8px; border:1px solid #ddd;">Today</th>
                        <th style="padding:8px; border:1px solid #ddd;">Total</th>
                    </tr>
            `;

                const stages = [
                {access: 'verification_access', today: 'verification_today', total: 'verification_total', name: 'Verification', ids: 'verification_ids', link: 'verification_list'},
                {access: 'approval_access', today: 'approval_today', total: 'approval_total', name: 'Approval', ids: 'approval_ids', link: 'approval_list'},
                {access: 'acknowledgement_access', today: 'acknowledgement_today', total: 'acknowledgement_total', name: 'Acknowledgement', ids: 'acknowledgement_ids', link: 'edit_acknowledgement_list'},
                {access: 'loan_issue_access', today: 'loan_issue_today', total: 'loan_issue_total', name: 'Loan Issue', ids: 'loan_ids', link: 'edit_loan_issue'},
                {access: 'accounts_loan_issue_access', today: 'accounts_loan_issue_today', total: 'accounts_loan_issue_total', name: 'Accounts Loan Issue', ids: 'ac_loan_ids', link: 'edit_accounts_loan_issue'},
                {access: 'closed_access', today: 'closed_today', total: 'closed_total', name: 'Closed', ids: 'closed_ids', link: 'edit_closed'},
                {access: 'noc_access', today: 'noc_today', total: 'noc_total', name: 'NOC', ids: 'noc_ids', link: 'edit_noc'},
                {access: 'noc_handover_access', today: 'noc_handover_today', total: 'noc_handover_total', name: 'NOC Handover', ids: 'noc_handover_ids', link: 'edit_noc_handover'}
            ];
                    stages.forEach(stage => {
                        if (res[stage.access] == 0) {
                            totalTotal += parseInt(res[stage.total] || 0);
                            html += `
                        <tr>
                            <td style="padding:8px; border:1px solid #ddd;">
                                <a href="${stage.link}" 
                                   title="${res[stage.ids]}" 
                                   onclick="event.stopPropagation(); event.preventDefault(); window.location.href='${stage.link}';"
                                   style="text-decoration:none; color:#333;">
                                   ${stage.name}
                                </a>
                            </td>
                            <td style="padding:8px; text-align:center; border:1px solid #ddd;">${res[stage.today] || 0}</td>
                            <td style="padding:8px; text-align:center; border:1px solid #ddd;">${res[stage.total] || 0}</td>
                        </tr>
                    `;
                        }
                    });

                    html += '</table>';
                    $('.count-label').text(totalTotal);
                    $('.header-notifications').html(html);
                },
                error: function() {
                    console.log('Notification load failed');
                }
            });
        }
        //////////////////////////////////Notification///////////////////////////////////////////////
        
        ////////////////////////////////// Mantra device initialize START //////////////////////////////////
        function mantraInitDevice() {
            const deviceList = GetConnectedDeviceList();

            console.log("Connected Devices:", deviceList);

            const desc = deviceList?.data?.ErrorDescription;

            if (deviceList?.httpStaus && deviceList?.data?.ErrorCode == "0" && desc) {

                const device = desc.split(":")[1]?.trim();

                if (device) {
                    console.log("Device Name:", device);
                    const init = InitDevice(device, "");
                    console.log("Init result:", init);
                } else {
                    console.error("Device not found in description");
                }

            } else {
                console.error("Device not connected");
            }
        }
        ////////////////////////////////// Mantra device initialize END //////////////////////////////////

        function getUserMappedDetails(typeVal){
            if(typeVal != '1'){ //if type user then no need to show mapping.
                $.post('reportFile/promotion_activity/getUserMappedDetails.php', {typeVal}, function (response) {

                    map_name.clearStore();

                    const items = response.map(row => ({
                        value: row.ids,
                        label: row.map_name
                    }));

                    map_name.setChoices(items);

                },'json');
            }
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

    if ($current_page == 'edit_staff_creation') { ?>
        <script src="js/edit_staff_creation.js"></script>
    <?php }

    if ($current_page == 'staff_creation') { ?>
        <script src="js/staff_creation.js"></script>
    <?php }

    if ($current_page == 'edit_manage_user') { ?>
        <script src="js/edit_manage_user.js"></script>
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
        <script src="js/fingerprint_common.js"></script>
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
        <script src="js/fingerprint_common.js"></script>
    <?php }

    //Loan Issue screen
    if ($current_page == 'edit_loan_issue') { ?>
        <script src="js/edit_loan_issue.js"></script>
    <?php }

    if ($current_page == 'loan_issue') { ?>
        <script src="js/loan_issue.js"></script>
        <script src="js/fingerprint_common.js"></script>
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

    if ($current_page == 'noc_handover') { ?>
        <script src="js/noc_handover.js"></script>
        <script src="js/fingerprint_common.js"></script>
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

    // update screen
    if ($current_page == 'edit_update') { ?>
        <script src="js/edit_update.js"></script>
    <?php }

    //Document track Screen
    if ($current_page == 'document_track') { ?>
        <script src="js/document_track.js"></script>
    <?php }

    //NOC Replace Screen
    if ($current_page == 'noc_replace') { ?>
        <script src="js/noc_replace.js"></script>
    <?php }

    //Update Customer Status Screen
    if ($current_page == 'update_customer_status') { ?>
        <script src="js/update_customer_status.js"></script>
    <?php }

    //Update Screen
    if ($current_page == 'update') { ?>
        <script src="js/update.js"></script>
        <script src="js/fingerprint_common.js"></script>
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

    //Hand Cash Balance Sheet
    if ($current_page == 'hand_cash_balance_sheet') { ?>
        <script src="js/hand_cash_balance_sheet.js"></script>
    <?php }

    // accounts loan Isue
    if ($current_page == 'edit_accounts_loan_issue') { ?>
        <script src="js/edit_accounts_loan_issue.js"></script>
    <?php }

    if ($current_page == 'accounts_loan_issue') { ?>
        <script src="js/accounts_loan_issue.js"></script>
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

    if ($current_page == 'ecs_followup') { ?>
        <script src="js/ecs_followup.js"></script>
    <?php }

    if ($current_page == 'ecs_followup_info') { ?>
        <script src="js/ecs_followup_info.js"></script>
    <?php }

    if ($current_page == 'ecs_edit_followup') { ?>
        <script src="js/ecs_edit_followup.js"></script>
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

    if ($current_page == 'verification_report') { ?>
        <script src="js/verification_report.js"></script>
    <?php }

    if ($current_page == 'approval_report') { ?>
        <script src="js/approval_report.js"></script>
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

    if ($current_page == 'principal_interest_report') { ?>
        <script src="js/principal_interest_report.js"></script>
    <?php }

    if ($current_page == 'balance_report') { ?>
        <script src="js/balance_report.js"></script>
    <?php }

    if ($current_page == 'due_list_report') { ?>
        <script src="js/due_list_report.js"></script>
    <?php }

    if ($current_page == 'in_closed_report') { ?>
        <script src="js/in_closed_report.js"></script>
    <?php }

    if ($current_page == 'closed_report') { ?>
        <script src="js/closed_report.js"></script>
    <?php }

    if ($current_page == 'confirmation_followup_report') { ?>
        <script src="js/confirmation_followup_report.js"></script>
    <?php }

    if ($current_page == 'agent_report') { ?>
        <script src="js/agent_report.js"></script>
    <?php }

    if ($current_page == 'no_due_pay_report') { ?>
        <script src="js/no_due_pay_report.js"></script>
    <?php }

    if ($current_page == 'branch_request_count_report') { ?>
        <script src="js/branch_request_count_report.js"></script>
    <?php }

    if ($current_page == 'other_transaction_report') { ?>
        <script src="js/other_transaction_report.js"></script>
    <?php }

    if ($current_page == 'due_followup_customer_count_report') { ?>
        <script src="js/due_followup_customer_count_report.js"></script>
    <?php }

    if ($current_page == 'day_end_report') { ?>
        <script src="js/day_end_report.js"></script>
    <?php }

    if ($current_page == 'cash_tally_activity_report') { ?>
        <script src="js/cash_tally_activity_report.js"></script>
    <?php }

    if ($current_page == 'commitment_report') { ?>
        <script src="js/commitment_report.js"></script>
    <?php }

    if ($current_page == 'customer_status_report') { ?>
        <script src="js/customer_status_report.js"></script>
    <?php }

    if ($current_page == 'promotion_activity_report') { ?>
        <script src="js/promotion_activity_report.js"></script>
    <?php }

    if ($current_page == 'cleared_report') { ?>
        <script src="js/cleared_report.js"></script>
    <?php }

    if ($current_page == 'work_count_report') { ?>
        <script src="js/work_count_report.js"></script>
    <?php }

    if ($current_page == 'events_report') { ?>
        <script src="js/events_report.js"></script>
    <?php }

    if ($current_page == 'area_loan_count_report') { ?>
        <script src="js/area_loan_count_report.js"></script>
    <?php }

    if ($current_page == 'noc_handover_report') { ?>
        <script src="js/noc_handover_report.js"></script>
    <?php }

    if ($current_page == 'request_count_report') { ?>
        <script src="js/request_count_report.js"></script>
    <?php }

    if ($current_page == 'verification_count_report') { ?>
        <script src="js/verification_count_report.js"></script>
    <?php }

    if ($current_page == 'approval_count_report') { ?>
        <script src="js/approval_count_report.js"></script>
    <?php }

    if ($current_page == 'loan_issued_count_report') { ?>
        <script src="js/loan_issued_count_report.js"></script>
    <?php }

    if ($current_page == 'promotion_count_report') { ?>
        <script src="js/promotion_count_report.js"></script>
    <?php }

    if ($current_page == 'confirmation_count_report') { ?>
        <script src="js/confirmation_count_report.js"></script>
    <?php }

    if ($current_page == 'duefollowup_count_report') { ?>
        <script src="js/duefollowup_count_report.js"></script>
    <?php }

    if ($current_page == 'back_office_count_report') { ?>
        <script src="js/back_office_count_report.js"></script>
    <?php }

    if ($current_page == 'concern_report') { ?>
        <script src="js/concern_report.js"></script>
    <?php }

    if ($current_page == 'partners_report') { ?>
        <script src="js/partners_report.js"></script>
    <?php }

    if ($current_page == 'location_track_report') { ?>
        <script src="js/location_track_report.js"></script>
    <?php }

    if ($current_page == 'outstanding_report') { ?>
        <script src="js/outstanding_report.js"></script>
    <?php }

    if ($current_page == 'intrest_ledger_report') { ?>
        <script src="js/intrest_ledger_report.js"></script>
    <?php }

    if ($current_page == 'intrest_loan_issue_report') { ?>
        <script src="js/intrest_loan_issue_report.js"></script>
    <?php }

    if ($current_page == 'intrest_collection_report') { ?>
        <script src="js/intrest_collection_report.js"></script>
    <?php }

    if ($current_page == 'intrest_balance_report') { ?>
        <script src="js/intrest_balance_report.js"></script>
    <?php }

    if ($current_page == 'intrest_closed_report') { ?>
        <script src="js/intrest_closed_report.js"></script>
    <?php }

    if ($current_page == 'search_module') { ?>
        <script src="js/search_module.js"></script>
        <script src="js/fingerprint_common.js"></script>
    <?php }

    if ($current_page == 'bulk_upload') { ?>
        <script src="js/bulk_upload.js"></script>
    <?php }
    if ($current_page == 'loan_track') { ?>
        <script src="js/loan_track.js"></script>
    <?php }

    if ($current_page == 'sms_generation') { ?>
        <script src="js/sms_generation.js"></script>
    <?php } ?>

    <script src="js/logincreation.js"></script>

    <!-- Slimscroll JS -->
    <script src="vendor/slimscroll/slimscroll.min.js"></script>
    <script src="vendor/slimscroll/custom-scrollbar.js"></script>

    <!-- Datepickers -->
    <script src="vendor/datepicker/js/picker.js"></script>
    <script src="vendor/datepicker/js/picker.date.js"></script>
    <script src="vendor/datepicker/js/custom-picker.js"></script>