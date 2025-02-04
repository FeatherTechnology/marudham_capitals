//Sub Area Multi select initialization
const loanCatMultiselect = new Choices('#loan_category1', {
    removeItemButton: true,
    noChoicesText: 'Select Loan Category',
    allowHTML: true
});
const subCatMultiselect = new Choices('#sub_category1', {
    removeItemButton: true,
    noChoicesText: 'Select Sub Category',
    allowHTML: true
});
const schemeMultiselect = new Choices('#scheme1', {
    removeItemButton: true,
    noChoicesText: 'Select Scheme Name',
    allowHTML: true
});

// Document is ready
$(document).ready(function () {


    {//To Order ag_group Alphabetically
        var firstOption = $("#ag_group option:first-child");
        $("#ag_group").html($("#ag_group option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#ag_group").prepend(firstOption);
    }

    $("#state").change(function () {
        var StateSelected = $(this).val();
        getDistrictDropdown(StateSelected);
    });

    $('#district').change(function () {
        var DistSelected = $(this).val();
        $('#district1').val(DistSelected);
        getTalukDropdown(DistSelected);
    });

    $('#taluk').change(function () {
        var talukselected = $(this).val();
        $('#taluk1').val(talukselected);
    })

    //change sub category based on Loan category
    $('#loan_category1').change(function () {
        //Area Multi select store
        var loanCatList = loanCatMultiselect.getValue();
        var loancat = '';
        for (var i = 0; i < loanCatList.length; i++) {
            if (i > 0) {
                loancat += ',';
            }
            loancat += loanCatList[i].value;
        }
        var arr = loancat.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#loan_category').val(sortedStr);
        var loanselected = $('#loan_category').val();

        getSubCategory(loanselected);
    })

    $('#sub_category1').change(function () {
        //Sub category select store
        var subCatList = subCatMultiselect.getValue();
        var subcat = '';
        for (var i = 0; i < subCatList.length; i++) {
            if (i > 0) {
                subcat += ',';
            }
            subcat += subCatList[i].value;
        }
        var arr = subcat.split(",");
        arr.sort(function (a, b) { return a - b });
        var sortedStr = arr.join(",");
        $('#sub_category').val(sortedStr);

        var subselected = $('#sub_category').val();
        getSchemeValues(subselected);
    })

    //store scheme when selected
    $('#scheme1').change(function () {
        var scheme1 = schemeMultiselect.getValue();
        var scheme = '';
        for (var i = 0; i < scheme1.length; i++) {
            if (i > 0) {
                scheme += ',';
            }
            scheme += scheme1[i].value;
        }
        $('#scheme').val(scheme);
    })

    //IFSC to upper case
    $('#ifsc').keyup(function () {
        $(this).val($(this).val().toUpperCase());
    });


    $('#submit_agent_creation').click(function () {
        //Validation
        var ag_name = $('#ag_name').val(); var ag_group = $('#ag_group').val(); var company_id = $('#company_id').val();/*var branch_id = $('#branch_id').val();*/var state = $('#state').val(); var district = $('#district1').val(); var taluk = $('#taluk1').val(); var place = $('#place').val(); var pincode = $('#pincode').val();
        var name = $('#name').val(); var designation = $('#designation').val(); var mobile = $('#mobile').val(); var whatsapp = $('#whatsapp').val(); var loan_category = $('#loan_category').val();
        var subCat = subCatMultiselect.getValue(); var loan_pay = $('input[name=loan_pay]:checked').val(); var responsible = $('input[name=responsible]:checked').val(); var coll_point = $('input[name=coll_point]:checked').val(); var bank_name = $('#bank_name').val();
        var branch_name = $('#bank_branch_name').val(); var acc_no = $('#acc_no').val(); var ifsc = $('#ifsc').val(); var holder_name = $('#holder_name').val();
        if (ag_name === '' || ag_group == '' || state === '' || district === '' || taluk === '' || place === '' || pincode === '' || name === '' ||
            designation === '' || mobile === '' || whatsapp === '' || loan_category === '' || subCat.length == 0 || loan_pay == undefined || responsible == undefined ||
            coll_point == undefined || bank_name === '' || branch_name === '' || acc_no === '' || ifsc === '' || holder_name === '') {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#009688'
            });
            event.preventDefault();
        }
    })

});
$(function () {
    getLoanCategory();//auto call for loan category dropdown
    resetagentgroupTable();//auto call for agent group table
    getAgentGroupDropdown();//auto call for agent grop dropdown
    var ag_id_upd = $('#ag_id_upd').val();
    if (ag_id_upd > 0) {
        var company_id_upd = $('#company_id_upd').val();
        var state_upd = $('#state_upd').val();
        var district_upd = $('#district_upd').val();
        var loan_cat_upd = $('#loan_category_upd').val();
        var sub_category_upd = $('#sub_category_upd').val();
        getBranchDropdown(company_id_upd);
        getDistrictDropdown(state_upd);
        getTalukDropdown(district_upd);
        getSchemeValues(sub_category_upd);
        getSubCategory(loan_cat_upd);
    } else {
        getAgentCode();//auto call for agent code
    }
})

//For auto resizing textarea
$('#more_info').on('input', function () {
    $(this).css('height', 'auto');
    $(this).css('height', this.scrollHeight + 'px');
});

//get district dropdown
function getDistrictDropdown(StateSelected) {

    var optionsList;
    var htmlString = "<option value='Select District'>Select District</option>";
    {
        var TamilNadu = ["Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Kancheepuram", "Kanniyakumari", "Karur", "Madurai", "Nagapattinam",
            "Namakkal", "Nilgiris", "Perambalur", "Pudukottai", "Ramanathapuram", "Salem", "Sivagangai", "Thanjavur", "Theni", "Thiruvallur", "Tiruvannamalai", "Thiruvarur",
            "Thoothukudi", "Tiruchirappalli", "Thirunelveli", "Vellore", "Viluppuram", "Virudhunagar", "Ariyalur", "Krishnagiri", "Tiruppur", "Chengalpattu", "Kallakurichi",
            "Ranipet", "Tenkasi", "Tirupathur", "Mayiladuthurai"];
        var Puducherry = ["Puducherry"];
    }//District list
    switch (StateSelected) {
        case "TamilNadu":
            optionsList = TamilNadu;
            break;
        case "Puducherry":
            optionsList = Puducherry;
            break;
        case "SelectState":
            optionsList = [];
            break;
    }

    var district_upd = $('#district_upd').val();
    for (var i = 0; i < optionsList.length; i++) {
        var selected = '';
        if (district_upd != '' && district_upd == optionsList[i]) { selected = "selected"; }
        htmlString = htmlString + "<option value='" + optionsList[i] + "' " + selected + " >" + optionsList[i] + "</option>";
    }
    $("#district").html(htmlString);
    $("#district1").val(district_upd);

    {//To Order district Alphabetically
        var firstOption = $("#district option:first-child");
        $("#district").html($("#district option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#district").prepend(firstOption);
    }

}

//get Taluk Dropdown
function getTalukDropdown(DistSelected) {

    var optionsList;
    var htmlString = "<option value='Select Taluk'>Select Taluk</option>";
    {
        var Chennai = ["Alandur", "Ambattur", "Aminjikarai", "Ayanavaram", "Egmore", "Guindy", "Madhavaram", "Madhuravoyal", "Mambalam", "Mylapore", "Perambur", "Purasavakkam", "Sholinganallur", "Thiruvottriyur", "Tondiarpet", "Velacherry"];
        var Coimbatore = ["Aanaimalai", "Annur", "Coimbatore(North)", "Coimbatore(South)", "Kinathukadavu", "Madukarai", "Mettupalayam", "Perur", "Pollachi", "Sulur", "Valparai"];
        var Cuddalore = ["Cuddalore", "Bhuvanagiri", "Chidambaram", "Kattumannarkoil", "Kurinjipadi", "Panruti", "Srimushnam", "Thittakudi", "Veppur", "Virudhachalam"];
        var Dharmapuri = ["Dharmapuri", "Harur", "Karimangalam", "Nallampalli", "Palacode", "Pappireddipatti", "Pennagaram"];
        var Dindigul = ["Atthur", "Dindigul (East)", "Dindigul (West)", "Guziliyamparai", "Kodaikanal", "Natham", "Nilakottai", "Oddanchatram", "Palani", "Vedasandur"];
        var Erode = ["Erode", "Anthiyur", "Bhavani", "Gobichettipalayam", "Kodumudi", "Modakurichi", "Nambiyur", "Perundurai", "Sathiyamangalam", "Thalavadi"];
        var Kancheepuram = ["Kancheepuram", "Kundrathur", "Sriperumbudur", "Uthiramerur", "Walajabad"];
        var Kanniyakumari = ["Agasteeswaram", "Kalkulam", "Killiyur", "Thiruvatar", "Thovalai", "Vilavankodu"];
        var Karur = ["Karur", "Aravakurichi", "Kadavur", "Krishnarayapuram", "Kulithalai", "Manmangalam", "Pugalur"];
        var Madurai = ["Kallikudi", "Madurai (East)", "Madurai (North)", "Madurai (South)", "Madurai (West)", "Melur", "Peraiyur", "Thirumangalam", "Thiruparankundram", "Usilampatti", "Vadipatti"];
        var Nagapattinam = ["Nagapattinam", "Kilvelur", "Thirukkuvalai", "Vedaranyam"];
        var Namakkal = ["Namakkal", "Kholli Hills", "Kumarapalayam", "Mohanoor", "Paramathi Velur", "Rasipuram", "Senthamangalam", "Tiruchengode"];
        var Nilgiris = ["Udagamandalam", "Coonoor", "Gudalur", "Kothagiri", "Kundah", "Pandalur"];
        var Perambalur = ["Perambalur", "Alathur", "Kunnam", "Veppanthattai"];
        var Pudukottai = ["Pudukottai", "Alangudi", "Aranthangi", "Avudiyarkoil", "Gandarvakottai", "Iluppur", "Karambakudi", "Kulathur", "Manamelkudi", "Ponnamaravathi", "Thirumayam", "Viralimalai"];
        var Ramanathapuram = ["Ramanathapuram", "Kadaladi", "Kamuthi", "Kezhakarai", "Mudukulathur", "Paramakudi", "Rajasingamangalam", "Rameswaram", "Tiruvadanai"];
        var Salem = ["Salem", "Attur", "Edapadi", "Gangavalli", "Kadaiyampatti", "Mettur", "Omalur", "Pethanayakanpalayam", "Salem South", "Salem West", "Sankari", "Vazhapadi", "Yercaud"];
        var Sivagangai = ["Sivagangai", "Devakottai", "Ilayankudi", "Kalaiyarkovil", "Karaikudi", "Manamadurai", "Singampunari", "Thirupuvanam", "Tirupathur"];
        var Thanjavur = ["Thanjavur", "Boothalur", "Kumbakonam", "Orathanadu", "Papanasam", "Pattukottai", "Peravurani", "Thiruvaiyaru", "Thiruvidaimaruthur"];
        var Theni = ["Theni", "Aandipatti", "Bodinayakanur", "Periyakulam", "Uthamapalayam"];
        var Thiruvallur = ["Thiruvallur", "Avadi", "Gummidipoondi", "Pallipattu", "Ponneri", "Poonamallee", "R.K. Pet", "Tiruthani", "Uthukottai"];
        var Tiruvannamalai = ["Thiruvannamalai", "Arni", "Chengam", "Chetpet", "Cheyyar", "Jamunamarathur", "Kalasapakkam", "Kilpennathur", "Polur", "Thandramet", "Vandavasi", "Vembakkam"];
        var Thiruvarur = ["Thiruvarur", "Kodavasal", "Koothanallur", "Mannargudi", "Nannilam", "Needamangalam", "Thiruthuraipoondi", "Valangaiman"];
        var Thoothukudi = ["Thoothukudi", "Eral", "Ettayapuram", "Kayathar", "Kovilpatti", "Ottapidaram", "Sattankulam", "Srivaikundam", "Tiruchendur", "Vilathikulam"];
        var Tiruchirappalli = ["Lalgudi", "Manachanallur", "Manapparai", "Marungapuri", "Musiri", "Srirangam", "Thottiam", "Thuraiyur", "Tiruchirapalli (West)", "Tiruchirappalli (East)", "Tiruverumbur"];
        var Thirunelveli = ["Tirunelveli", "Ambasamudram", "Cheranmahadevi", "Manur", "Nanguneri", "Palayamkottai", "Radhapuram", "Thisayanvilai"];
        var Vellore = ["Vellore", "Aanikattu", "Gudiyatham", "K V Kuppam", "Katpadi", "Pernambut"];
        var Viluppuram = ["Villupuram", "Gingee", "Kandachipuram", "Marakanam", "Melmalaiyanur", "Thiruvennainallur", "Tindivanam", "Vanur", "Vikravandi"];
        var Virudhunagar = ["Virudhunagar", "Aruppukottai", "Kariyapatti", "Rajapalayam", "Sathur", "Sivakasi", "Srivilliputhur", "Tiruchuli", "Vembakottai", "Watrap"];
        var Ariyalur = ["Ariyalur", "Andimadam", "Sendurai", "Udaiyarpalayam"];
        var Krishnagiri = ["Krishnagiri", "Anjetty", "Bargur", "Hosur", "Pochampalli", "Sulagiri", "Thenkanikottai", "Uthangarai"];
        var Tiruppur = ["Avinashi", "Dharapuram", "Kangeyam", "Madathukkulam", "Oothukuli", "Palladam", "Tiruppur (North)", "Tiruppur (South)", "Udumalaipettai"];
        var Chengalpattu = ["Chengalpattu", "Cheyyur", "Maduranthakam", "Pallavaram", "Tambaram", "Thirukalukundram", "Tiruporur", "Vandalur"];
        var Kallakurichi = ["Kallakurichi", "Chinnaselam", "Kalvarayan Hills", "Sankarapuram", "Tirukoilur", "Ulundurpet"];
        var Ranipet = ["Arakkonam", "Arcot", "Kalavai", "Nemili", "Sholingur", "Walajah"];
        var Tenkasi = ["Tenkasi", "Alangulam", "Kadayanallur", "Sankarankovil", "Shenkottai", "Sivagiri", "Thiruvengadam", "Veerakeralampudur"];
        var Tirupathur = ["Tirupathur", "Ambur", "Natrampalli", "Vaniyambadi"];
        var Mayiladuthurai = ["Mayiladuthurai", "Kuthalam", "Sirkali", "Tharangambadi"];
        var Puducherry = ["Puducherry", "Oulgaret", "Villianur", "Bahour", "Karaikal", "Thirunallar", "Mahe", "Yanam"];

    }//taluk list
    switch (DistSelected) {
        case "Ariyalur":
            optionsList = Ariyalur;
            break;
        case "Chengalpattu":
            optionsList = Chengalpattu;
            break;
        case "Chennai":
            optionsList = Chennai;
            break;
        case "Coimbatore":
            optionsList = Coimbatore;
            break;
        case "Dharmapuri":
            optionsList = Dharmapuri;
            break;
        case "Erode":
            optionsList = Erode;
            break;
        case "Cuddalore":
            optionsList = Cuddalore;
            break;
        case "Dindigul":
            optionsList = Dindigul;
            break;
        case "Kallakurichi":
            optionsList = Kallakurichi;
            break;
        case "Kanniyakumari":
            optionsList = Kanniyakumari;
            break;
        case "Krishnagiri":
            optionsList = Krishnagiri;
            break;
        case "Nagapattinam":
            optionsList = Nagapattinam;
            break;
        case "Perambalur":
            optionsList = Perambalur;
            break;
        case "Ramanathapuram":
            optionsList = Ramanathapuram;
            break;
        case "Salem":
            optionsList = Salem;
            break;
        case "Tenkasi":
            optionsList = Tenkasi;
            break;
        case "Theni":
            optionsList = Theni;
            break;
        case "Thirunelveli":
            optionsList = Thirunelveli;
            break;
        case "Thiruvarur":
            optionsList = Thiruvarur;
            break;
        case "Tirupathur":
            optionsList = Tirupathur;
            break;
        case "Tiruvannamalai":
            optionsList = Tiruvannamalai;
            break;
        case "Vellore":
            optionsList = Vellore;
            break;
        case "Virudhunagar":
            optionsList = Virudhunagar;
            break;
        case "Kancheepuram":
            optionsList = Kancheepuram;
            break;
        case "Karur":
            optionsList = Karur;
            break;
        case "Madurai":
            optionsList = Madurai;
            break;
        case "Namakkal":
            optionsList = Namakkal;
            break;
        case "Pudukottai":
            optionsList = Pudukottai;
            break;
        case "Ranipet":
            optionsList = Ranipet;
            break;
        case "Sivagangai":
            optionsList = Sivagangai;
            break;
        case "Thanjavur":
            optionsList = Thanjavur;
            break;
        case "Nilgiris":
            optionsList = Nilgiris;
            break;
        case "Thiruvallur":
            optionsList = Thiruvallur;
            break;
        case "Thoothukudi":
            optionsList = Thoothukudi;
            break;
        case "Tiruppur":
            optionsList = Tiruppur;
            break;
        case "Tiruchirappalli":
            optionsList = Tiruchirappalli;
            break;
        case "Viluppuram":
            optionsList = Viluppuram;
            break;
        case "Mayiladuthurai":
            optionsList = Mayiladuthurai;
            break;
        case "Puducherry":
            optionsList = Puducherry;
            break;
        case "Select District":
            optionsList = [];
            break;
    }
    var taluk_upd = $('#taluk_upd').val();
    for (var i = 0; i < optionsList.length; i++) {
        var selected = '';
        if (taluk_upd != '' && taluk_upd == optionsList[i]) { selected = "selected"; }
        htmlString = htmlString + "<option value='" + optionsList[i] + "' " + selected + " >" + optionsList[i] + "</option>";
    }
    $("#taluk").html(htmlString);
    $("#taluk1").val(taluk_upd);

    {//To Order taluk Alphabetically
        var firstOption = $("#taluk option:first-child");
        $("#taluk").html($("#taluk option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#taluk").prepend(firstOption);
    }

}

//Get Agent Code 
function getAgentCode() {
    $.ajax({
        url: 'agentCreationFile/getAgentCode.php',
        type: "post",
        dataType: "json",
        data: {},
        cache: false,
        success: function (response) {
            var ag_code = response;
            $('#ag_code').val(ag_code);
        }
    })
}

//Get Loan Category 
function getLoanCategory() {
    var loan_category_upd = $('#loan_category_upd').val();
    var values = loan_category_upd.split(',');
    $.ajax({
        url: 'agentCreationFile/ajaxGetLoanCategory.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            loanCatMultiselect.clearStore();
            var len = response.length;
            for (var i = 0; i < len; i++) {
                var loan_category_name_id = response[i]['loan_category_name_id'];
                var loan_category_name = response[i]['loan_category_name'];
                var selected = '';
                if (loan_category_upd != '' && values.includes(loan_category_name_id)) {
                    selected = 'selected';
                }
                var items = [
                    {
                        value: loan_category_name_id,
                        label: loan_category_name,
                        selected: selected,
                    }
                ];
                loanCatMultiselect.setChoices(items);
                loanCatMultiselect.init();

            }
        }
    });
}
//Fetch Sub Category Based on loan category
function getSubCategory(loan_cat) {
    var sub_category_upd = $('#sub_category_upd').val();
    var values = sub_category_upd.split(',');
    $.ajax({
        url: 'agentCreationFile/getSubCategory.php',
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: { 'loan_cat': loan_cat },
        success: function (response) {

            subCatMultiselect.clearStore();
            for (var i = 0; i < response.length; i++) {
                for (var j = 0; j < response[i].length; j++) {
                    var sub_category_name = response[i][j]['sub_category_name'];

                    var selected = '';
                    if (sub_category_upd != '' && values.includes(sub_category_name)) {
                        selected = 'selected';
                    }
                    var items = [
                        {
                            value: sub_category_name,
                            label: sub_category_name,
                            selected: selected,
                        }
                    ];
                    subCatMultiselect.setChoices(items);
                    subCatMultiselect.init();
                }
            }


        }
    })
}

//Get Scheme Values selected using Choices plugin
function getSchemeValues(sub_cat) {

    var scheme_upd = $('#scheme_upd').val();
    var values = scheme_upd.split(',');
    $.ajax({
        url: 'agentCreationFile/ajaxGetSchemeValues.php',
        type: 'post',
        data: { 'sub_cat': sub_cat },
        dataType: 'json',
        success: function (response) {

            schemeMultiselect.clearStore();
            for (var i = 0; i < response.length; i++) {
                for (var j = 0; j < response[i].length; j++) {
                    var scheme_id = response[i][j]['scheme_id'];
                    var scheme_name = response[i][j]['scheme_name'];
                    var selected = '';
                    if (scheme_upd != '' && values.includes(scheme_id)) {
                        selected = 'selected';
                    }
                    var items = [
                        {
                            value: scheme_id,
                            label: scheme_name,
                            selected: selected,
                        }
                    ];

                    schemeMultiselect.setChoices(items);
                    schemeMultiselect.init();
                }
            }
        }
    });
}

//Agent Group Modal
{
    // Modal Box for Agent Group
    $("#agentnameCheck").hide();
    $(document).on("click", "#submiAgentBtn", function () {
        var agent_group_id = $("#agent_group_id").val();
        var agent_group_name = $("#agent_group_name").val();
        if (agent_group_name != "") {
            $.ajax({
                url: 'agentCreationFile/ajaxInsertAgentGroup.php',
                type: 'POST',
                data: { "agent_group_name": agent_group_name, "agent_group_id": agent_group_id },
                cache: false,
                success: function (response) {
                    var insresult = response.includes("Exists");
                    var updresult = response.includes("Updated");
                    if (insresult) {
                        $('#agentInsertNotOk').show();
                        setTimeout(function () {
                            $('#agentInsertNotOk').fadeOut('fast');
                        }, 2000);
                    } else if (updresult) {
                        $('#agentUpdateOk').show();
                        setTimeout(function () {
                            $('#agentUpdateOk').fadeOut('fast');
                        }, 2000);
                        $("#agentgroupTable").remove();
                        resetagentgroupTable(taluk);
                        $("#agent_group_name").val('');
                        $("#agent_group_id").val('');
                    }
                    else {
                        $('#agentInsertOk').show();
                        setTimeout(function () {
                            $('#agentInsertOk').fadeOut('fast');
                        }, 2000);
                        $("#agentgroupTable").remove();
                        resetagentgroupTable(taluk);
                        $("#agent_group_name").val('');
                        $("#agent_group_id").val('');
                    }
                }
            });
        }
        else {
            $("#agentnameCheck").show();
        }
    });


    function resetagentgroupTable() {
        $.ajax({
            url: 'agentCreationFile/ajaxResetAgentGroupTable.php',
            type: 'POST',
            data: {},
            cache: false,
            success: function (html) {
                $("#updatedagentgroupTable").empty();
                $("#updatedagentgroupTable").html(html);

                $("#agent_group_name").val('');
                $("#agent_group_id").val('');
            }
        });
    }

    $("#agent_group_name").keyup(function () {
        var CTval = $("#agent_group_name").val();
        if (CTval.length == '') {
            $("#agentnameCheck").show();
            return false;
        } else {
            $("#agentnameCheck").hide();
        }
    });

    $("body").on("click", "#edit_agent_group", function () {
        var agent_group_id = $(this).attr('value');
        $("#agent_group_id").val(agent_group_id);
        $.ajax({
            url: 'agentCreationFile/ajaxEditAgentGroup.php',
            type: 'POST',
            data: { "agent_group_id": agent_group_id },
            cache: false,
            success: function (response) {
                $("#agent_group_name").val(response);
            }
        });
    });

    $("body").on("click", "#delete_agent_group", function () {
        var isok = confirm("Do you want delete this Agent Group?");
        if (isok == false) {
            return false;
        } else {
            var agent_group_id = $(this).attr('value');
            var c_obj = $(this).parents("tr");
            $.ajax({
                url: 'agentCreationFile/ajaxDeleteAgentGroup.php',
                type: 'POST',
                data: { "agent_group_id": agent_group_id },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Rights");
                    if (delresult) {
                        $('#agentDeleteNotOk').show();
                        setTimeout(function () {
                            $('#agentDeleteNotOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {
                        c_obj.remove();
                        $('#agentDeleteOk').show();
                        setTimeout(function () {
                            $('#agentDeleteOk').fadeOut('fast');
                        }, 2000);
                        resetagentgroupTable();
                        $("#agent_group_name").val('');
                        $("#agent_group_id").val('');
                    }
                }
            });
        }
    });

    $(function () {
        $('#agentgroupTable').DataTable({
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "createdRow": function (row, data, dataIndex) {
                $(row).find('td:first').html(dataIndex + 1);
            },
            "drawCallback": function (settings) {
                this.api().column(0).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            },
            dom: 'lBfrtip',
            buttons: [{
                extend: 'excel',
            },
            {
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            }
            ],
        });
    });

    function closeModal() {
        $.ajax({
            url: 'agentCreationFile/ajaxGetAgentGroup.php',
            type: 'post',
            data: {},
            dataType: 'json',
            success: function (response) {

                var len = response.length;
                $("#ag_group").empty();
                $("#ag_group").append("<option value=''>" + 'Select Agent Group' + "</option>");
                for (var i = 0; i < len; i++) {
                    var agent_group_id = response[i]['agent_group_id'];
                    var agent_group_name = response[i]['agent_group_name'];
                    $("#ag_group").append("<option value='" + agent_group_id + "'>" + agent_group_name + "</option>");
                }
                {//To Order ag_group Alphabetically
                    var firstOption = $("#ag_group option:first-child");
                    $("#ag_group").html($("#ag_group option:not(:first-child)").sort(function (a, b) {
                        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                    }));
                    $("#ag_group").prepend(firstOption);
                }
            }
        });
    }

}


//Communication details table
{
    var co = 16;
    // add row 
    $(document).on("click", '.add_comm', function () {
        var appendTxt = `<tr>
            <td>
                <input type="text" tabindex='`+ co + `' name="name[]" id="name" class="form-control" pattern="[a-zA-Z\s]+">
            </td>`;
        co = co + 1;
        appendTxt += `<td>
                <input type="text" tabindex='`+ co + `' name="designation[]" id="designation" class="form-control" pattern="[a-zA-Z\s]+">
            </td>`;
        co = co + 1;
        appendTxt += `<td>
                <input type="number" tabindex='`+ co + `' name="mobile[]" id="mobile" class="form-control" onKeyPress="if(this.value.length==10) return false;" onblur="if(this.value < 10) $(this).focus();" >
            </td>`;
        co = co + 1;
        appendTxt += `<td>
                <input type="number" tabindex='`+ co + `' name="whatsapp[]" id="whatsapp" class="form-control" onKeyPress="if(this.value.length==10) return false;" >
            </td>`;
        co = co + 1;
        appendTxt += `<td>
                <button type="button" tabindex='`+ co + `' id="add_comm[]" name="add_comm" value="Submit" class="btn btn-primary add_comm">Add</button> 
            </td>`;
        co = co + 1;
        appendTxt += `<td>
                <span class='icon-trash-2' tabindex='`+ co + `' id="deleterow"></span>
            </td>
        </tr>`;
        co = co + 1;
        $('#moduleTable').find('tbody').append(appendTxt);
    });

    // Delete unwanted Rows
    $(document).on("click", '#deleterow', function () {
        $(this).parent().parent().remove();
    });
}

//Get BranchDropdown Based on Company id
function getBranchDropdown(company_id) {
    var branch_id_upd = $('#branch_id_upd').val();
    $.ajax({
        url: 'areaMapping/getBranchDropdown.php',
        type: 'post',
        dataType: 'json',
        data: { 'company_id': company_id },
        cache: false,
        success: function (response) {
            $('#branch_id').empty();
            $('#branch_id').append('<option>Select Branch</option>');
            for (var i = 0; i < response.length; i++) {
                // console.log(response[i]['branch_id'])
                var selected = '';
                if (branch_id_upd != '' && branch_id_upd == response[i]['branch_id']) {
                    selected = "selected";
                }
                $('#branch_id').append("<option value ='" + response[i]['branch_id'] + "' " + selected + " > " + response[i]['branch_name'] + " </option>");
            }
            {//To Order Branch Alphabetically
                var firstOption = $("#branch_id option:first-child");
                $("#branch_id").html($("#branch_id option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#branch_id").prepend(firstOption);
            }
        }
    })
}

//Validation
function validateAgentGroup(ag_name) {
    $.ajax({
        url: 'agentCreationFile/checkAgentGroup.php',
        data: { 'ag_name': ag_name },
        // dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            var check = '';
            if (response.includes('Exist')) {
                Swal.fire({
                    timerProgressBar: true,
                    timer: 3000,
                    title: 'Agent Group Name Already Exist, Please Select Group Name!',
                    icon: 'error',
                    showConfirmButton: true,
                    confirmButtonColor: '#009688'
                });
                check = "yes";
            } else {
                check = "no";
            }
        }
    });
    if (check == "yes") {
        event.preventDefault();
    }

}

//reset Agent Group dropdown
function getAgentGroupDropdown() {
    var ag_group_upd = $('#ag_group_upd').val();
    $.ajax({
        url: 'agentCreationFile/ajaxGetAgentGroup.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {
            var len = response.length;
            $("#ag_group").empty();
            $("#ag_group").append("<option value=''>" + 'Select Agent Group' + "</option>");
            for (var i = 0; i < len; i++) {
                var agent_group_id = response[i]['agent_group_id'];
                var agent_group_name = response[i]['agent_group_name'];
                var selected = "";
                if (ag_group_upd != '' && ag_group_upd == agent_group_id) {
                    selected = 'selected';
                }
                $("#ag_group").append("<option value='" + agent_group_id + "' " + selected + " >" + agent_group_name + "</option>");
            }
            {//To Order ag_group Alphabetically
                var firstOption = $("#ag_group option:first-child");
                $("#ag_group").html($("#ag_group option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#ag_group").prepend(firstOption);
            }
        }
    });
}