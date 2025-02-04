// Document is ready
$(document).ready(function () {

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

    $('#submit_staff_creation').click(function () {
        //Validation
        var staff_name = $('#staff_name').val(); var staff_type = $('#staff_type').val(); var address = $('#address').val(); var state = $('#state').val(); var district = $('#district').val(); var taluk = $('#taluk').val(); var place = $('#place').val(); var pincode = $('#pincode').val(); let mobile1 = $('#mobile1').val(); let mobile2 = $('#mobile2').val(); let whatsapp = $('#whatsapp').val();
        if (staff_name === '' || staff_type === '' || address === '' || state === '' || district === '' || taluk === '' || place === '' || pincode === '') {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#009688'
            });
            event.preventDefault();
        } else if (whatsapp != '' && whatsapp.length < 10) {
            alert('Please enter 10 digit valid number');
            $('#whatsapp').focus();
            event.preventDefault();
            return false;
        } else if (mobile1 != '' && mobile1.length < 10) {
            alert('Please enter 10 digit valid number');
            $('#mobile1').focus();
            event.preventDefault();
            return false;
        } else if (mobile2 != '' && mobile2.length < 10) {
            alert('Please enter 10 digit valid number');
            $('#mobile2').focus();
            event.preventDefault();
            return false;
        }

    })

});// Document ready end

$(function () {
    resetStaffTypeTable();
    getStaffTypeDropdown();
    var staff_id_upd = $('#staff_id_upd').val();
    if (staff_id_upd > 0) {
        var state_upd = $('#state_upd').val();
        var district_upd = $('#district_upd').val();
        getDistrictDropdown(state_upd);
        getTalukDropdown(district_upd);
    } else {
        getStaffCode();//auto call for staff code
    }
})

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

    {//To Order loan_category Alphabetically
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

//Get Staff Code 
function getStaffCode() {
    $.ajax({
        url: 'staffCreation/getStaffCode.php',
        type: "post",
        dataType: "json",
        data: {},
        cache: false,
        success: function (response) {
            var staff_code = response;
            $('#staff_code').val(staff_code);
        }
    })
}

//get Staff  Type dropdown
function getStaffTypeDropdown() {
    var staff_type_upd = $('#staff_type_upd').val();
    $.ajax({
        url: 'staffCreation/ajaxGetStaffType.php',
        type: 'post',
        data: {},
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#staff_type").empty();
            $("#staff_type").append("<option value=''>" + 'Select Staff Type' + "</option>");
            for (var i = 0; i < len; i++) {
                var staff_type_id = response[i]['staff_type_id'];
                var staff_type_name = response[i]['staff_type_name'];
                var selected = '';
                if (staff_type_upd != '' && staff_type_upd == staff_type_id) {
                    selected = 'selected';
                }
                $("#staff_type").append("<option value='" + staff_type_id + "' " + selected + ">" + staff_type_name + "</option>");
            }
            {//To Order staff_type Alphabetically
                var firstOption = $("#staff_type option:first-child");
                $("#staff_type").html($("#staff_type option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#staff_type").prepend(firstOption);
            }
        }
    });
}

//Staff Type Modal
{
    // Modal Box for Staff Type
    $("#staffnameCheck").hide();
    $(document).on("click", "#submiStaffBtn", function () {
        var staff_type_id = $("#staff_type_id").val();
        var staff_type_name = $("#staff_type_name").val();
        if (staff_type_name != "") {
            $.ajax({
                url: 'staffCreation/ajaxInsertStaffType.php',
                type: 'POST',
                data: { "staff_type_name": staff_type_name, "staff_type_id": staff_type_id },
                cache: false,
                success: function (response) {
                    var insresult = response.includes("Exists");
                    var updresult = response.includes("Updated");
                    if (insresult) {
                        $('#staffInsertNotOk').show();
                        setTimeout(function () {
                            $('#staffInsertNotOk').fadeOut('fast');
                        }, 2000);
                    } else if (updresult) {
                        $('#staffUpdateOk').show();
                        setTimeout(function () {
                            $('#staffUpdateOk').fadeOut('fast');
                        }, 2000);
                        $("#StaffTypeTable").remove();
                        resetStaffTypeTable(taluk);
                        $("#staff_type_name").val('');
                        $("#staff_type_id").val('');
                    }
                    else {
                        $('#staffInsertOk').show();
                        setTimeout(function () {
                            $('#staffInsertOk').fadeOut('fast');
                        }, 2000);
                        $("#StaffTypeTable").remove();
                        resetStaffTypeTable(taluk);
                        $("#staff_type_name").val('');
                        $("#staff_type_id").val('');
                    }
                }
            });
        }
        else {
            $("#staffnameCheck").show();
        }
    });


    function resetStaffTypeTable() {
        $.ajax({
            url: 'staffCreation/ajaxResetStaffTypeTable.php',
            type: 'POST',
            data: {},
            cache: false,
            success: function (html) {
                $("#updatedStaffTypeTable").empty();
                $("#updatedStaffTypeTable").html(html);

                $("#staff_type_name").val('');
                $("#staff_type_id").val('');
            }
        });
    }

    $("#staff_type_name").keyup(function () {
        var CTval = $("#staff_type_name").val();
        if (CTval.length == '') {
            $("#staffnameCheck").show();
            return false;
        } else {
            $("#staffnameCheck").hide();
        }
    });

    $("body").on("click", "#edit_staff_type", function () {
        var staff_type_id = $(this).attr('value');
        $("#staff_type_id").val(staff_type_id);
        $.ajax({
            url: 'staffCreation/ajaxEditStaffType.php',
            type: 'POST',
            data: { "staff_type_id": staff_type_id },
            cache: false,
            success: function (response) {
                $("#staff_type_name").val(response);
            }
        });
    });

    $("body").on("click", "#delete_staff_type", function () {
        var isok = confirm("Do you want delete this Staff Type?");
        if (isok == false) {
            return false;
        } else {
            var staff_type_id = $(this).attr('value');
            var c_obj = $(this).parents("tr");
            $.ajax({
                url: 'staffCreation/ajaxDeleteStaffType.php',
                type: 'POST',
                data: { "staff_type_id": staff_type_id },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Rights");
                    if (delresult) {
                        $('#staffDeleteNotOk').show();
                        setTimeout(function () {
                            $('#staffDeleteNotOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {
                        c_obj.remove();
                        $('#staffDeleteOk').show();
                        setTimeout(function () {
                            $('#staffDeleteOk').fadeOut('fast');
                        }, 2000);
                        resetStaffTypeTable();
                        $("#staff_type_name").val('');
                        $("#staff_type_id").val('');
                    }
                }
            });
        }
    });

    $(function () {
        $('#staffTypeTable').DataTable({
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
            url: 'staffCreation/ajaxGetStaffType.php',
            type: 'post',
            data: {},
            dataType: 'json',
            success: function (response) {

                var len = response.length;
                $("#staff_type").empty();
                $("#staff_type").append("<option value=''>" + 'Select Staff Type' + "</option>");
                for (var i = 0; i < len; i++) {
                    var staff_type_id = response[i]['staff_type_id'];
                    var staff_type_name = response[i]['staff_type_name'];
                    $("#staff_type").append("<option value='" + staff_type_id + "'>" + staff_type_name + "</option>");
                }
                {//To Order staff_type Alphabetically
                    var firstOption = $("#staff_type option:first-child");
                    $("#staff_type").html($("#staff_type option:not(:first-child)").sort(function (a, b) {
                        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                    }));
                    $("#staff_type").prepend(firstOption);
                }
            }
        });
    }

}


