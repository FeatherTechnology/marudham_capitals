//Sub Area Multi select initialization
const intance = new Choices('#sub_area1', {
    removeItemButton: true,
    noChoicesText: 'Select Sub Area',
    allowHTML: true
});

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
        getTalukBasedArea(talukselected);
        resetAreaTable(talukselected);
    })

    $('#area').change(function () {
        var areaselected = $('#area').val();
        getAreaBasedSubArea(areaselected);
        resetSubAreaTable(areaselected);
    })

    $('#add_area').click(function () {
        var taluk = $('#taluk1').val();
        if (taluk == '') {
            alert('Please Select Taluk Name');
        } else {
            $('#add_area').attr({ "data-toggle": "modal", "data-target": ".add_area" });
        }
    })

    $('#add_sub_area').click(function () {
        var area = $('#area').val();
        if (area == '') {
            alert('Please Select Area Name');
        } else {
            $('#add_sub_area').attr({ "data-toggle": "modal", "data-target": ".add_sub_area" });
        }
    })

    // Download button
    $('#downloadarea').click(function () {
        window.location.href = 'uploads/excel_format/area_creation_format.xlsx';
    });

    $("#insertsuccess").hide();
    $("#notinsertsuccess").hide();
    //bulk upload
    $("#submitAreaUploadbtn").click(function () {

        var file_data = $('#file').prop('files')[0];
        var area = new FormData();
        area.append('file', file_data);
        if (file.files.length == 0) {
            alert("Please Select File");
            return false;
        }
        $.ajax({
            url: 'areaCreation/ajaxAreaDetailsupload.php',
            type: 'POST',
            data: area,
            // dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $('#file').attr("disabled", true);
                $('#submitAreaUploadbtn').attr("disabled", true);
            },
            success: function (data) {
                console.log(data)
                if (data == 0) {
                    $("#notinsertsuccess").hide();
                    $("#insertsuccess").show();
                    $("#file").val('');
                } else if (data == 1) {
                    $("#insertsuccess").hide();
                    $("#notinsertsuccess").show();
                    $("#file").val('');
                }
            },
            complete: function () {
                $('#file').attr("disabled", false);
                $('#submitAreaUploadbtn').attr("disabled", false);
            }
        });
    });

    //on submit add sub area list to hidden input
    $('#submit_area_creation').click(function () {
        var sub_area_list = intance.getValue();
        //Validation
        var state = $('#state').val(); var district = $('#district').val(); var taluk = $('#taluk').val(); var area = $('#area').val();
        if (state == '' || district == '' || taluk == '' || area == '' || sub_area_list.length == 0) {
            Swal.fire({
                timerProgressBar: true,
                timer: 2000,
                title: 'Please Fill out Mandatory fields!',
                icon: 'error',
                showConfirmButton: true,
                confirmButtonColor: '#009688'
            });
            return false;
        }

        var sub_area_list = intance.getValue();
        var sub_area = '';
        for (var i = 0; i < sub_area_list.length; i++) {
            if (i > 0) {
                sub_area += ',';
            }
            sub_area += sub_area_list[i].value;
        }
        $('#sub_area').val(sub_area);

    })


});//document ready end

//on page load for Edit page
$(function () {
    var area_creation_id_upd = $('#area_creation_id_upd').val();
    if (area_creation_id_upd > 0) {
        var state_upd = $('#state_upd').val();
        var district_upd = $('#district_upd').val(); if (district_upd != '') { $('#district1').val(district_upd); }
        var taluk_upd = $('#taluk_upd').val(); if (taluk_upd != '') { $('#taluk1').val(taluk_upd); $('#add_area').attr({ "data-toggle": "modal", "data-target": ".add_area" }) }
        var area_upd = $('#area_upd').val();

        getDistrictDropdown(state_upd);
        getTalukDropdown(district_upd);

        getTalukBasedArea(taluk_upd);
        resetAreaTable(taluk_upd);

        getAreaBasedSubArea(area_upd);
        resetSubAreaTable(area_upd);
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

    {//To Order Alphabetically
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

    {//To Order Alphabetically
        var firstOption = $("#taluk option:first-child");
        $("#taluk").html($("#taluk option:not(:first-child)").sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
        $("#taluk").prepend(firstOption);
    }
}

//Get Taluk Based Area
function getTalukBasedArea(talukselected) {
    var area_upd = $('#area_upd').val();
    $.ajax({
        url: 'areaCreation/ajaxGetAreaName.php',
        type: 'post',
        data: { 'talukselected': talukselected },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            $("#area").empty();
            $("#area").append("<option value=''>" + 'Select Area' + "</option>");
            for (var i = 0; i < len; i++) {
                var area_id = response[i]['area_id'];
                var area_name = response[i]['area_name'];
                var selected = '';
                if (area_upd != '' && area_upd == area_id) {
                    selected = 'selected';
                }
                $("#area").append("<option value='" + area_id + "' " + selected + ">" + area_name + "</option>");
            }

            $("#area_name").val('');
            $("#area_id").val('');

            {//To Order Alphabetically
                var firstOption = $("#area option:first-child");
                $("#area").html($("#area option:not(:first-child)").sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));
                $("#area").prepend(firstOption);
            }
        }
    });
}
//Get Area Based Sub Area
function getAreaBasedSubArea(area) {
    var sub_area_upd = $('#sub_area_upd').val();
    var values = sub_area_upd.split(',');

    $.ajax({
        url: 'areaCreation/ajaxGetSubAreaName.php',
        type: 'post',
        data: { 'area': area },
        dataType: 'json',
        success: function (response) {

            var len = response.length;
            intance.clearStore();
            for (var i = 0; i < len; i++) {
                var sub_area_id = response[i]['sub_area_id'];
                var sub_area_name = response[i]['sub_area_name'];
                var checked = response[i]['disabled'];
                var selected = '';
                if (sub_area_upd != '' && values.includes(sub_area_id)) {
                    selected = 'selected';
                    checked = false;
                }
                var items = [
                    {
                        value: sub_area_id,
                        label: sub_area_name,
                        selected: selected,
                        disabled: checked,
                    }
                ];

                intance.setChoices(items);
                intance.init();
            }

            $("#sub_area_name").val('');
            $("#sub_area_id").val('');
        }
    });
}

// ************************************************************************************************************************************************
//Area Modal
{
    // Modal Box for Area Name
    $("#areanameCheck").hide();
    $(document).on("click", "#submiAreaBtn", function () {
        var area_id = $("#area_id").val();
        var area_name = $("#area_name").val();
        var taluk = $('#taluk1').val();
        if (area_name != "") {
            $.ajax({
                url: 'areaCreation/ajaxInsertArea.php',
                type: 'POST',
                data: { "area_name": area_name, "area_id": area_id, 'taluk': taluk },
                cache: false,
                success: function (response) {
                    var insresult = response.includes("Exists");
                    var updresult = response.includes("Updated");
                    if (insresult) {
                        $('#areaInsertNotOk').show();
                        setTimeout(function () {
                            $('#areaInsertNotOk').fadeOut('fast');
                        }, 2000);
                    } else if (updresult) {
                        $('#areaUpdateOk').show();
                        setTimeout(function () {
                            $('#areaUpdateOk').fadeOut('fast');
                        }, 2000);
                        $("#areaTable").remove();
                        resetAreaTable(taluk);
                        $("#area_name").val('');
                        $("#area_id").val('');
                    }
                    else {
                        $('#areaInsertOk').show();
                        setTimeout(function () {
                            $('#areaInsertOk').fadeOut('fast');
                        }, 2000);
                        $("#areaTable").remove();
                        resetAreaTable(taluk);
                        $("#area_name").val('');
                        $("#area_id").val('');
                    }
                }
            });
        }
        else {
            $("#areanameCheck").show();
        }
    });


    function resetAreaTable(taluk) {
        $.ajax({
            url: 'areaCreation/ajaxResetAreaTable.php',
            type: 'POST',
            data: { 'taluk': taluk },
            cache: false,
            success: function (html) {
                $("#updatedareaTable").empty();
                $("#updatedareaTable").html(html);

                $("#area_name").val('');
                $("#area_id").val('');
            }
        });
    }

    $("#area_name").keyup(function () {
        var CTval = $("#area_name").val();
        if (CTval.length == '') {
            $("#areanameCheck").show();
            return false;
        } else {
            $("#areanameCheck").hide();
        }
    });

    $("body").on("click", "#edit_area", function () {
        var area_id = $(this).attr('value');
        $("#area_id").val(area_id);
        $.ajax({
            url: 'areaCreation/ajaxEditArea.php',
            type: 'POST',
            data: { "area_id": area_id },
            cache: false,
            success: function (response) {
                $("#area_name").val(response);
            }
        });
    });

    $("body").on("click", "#delete_area", function () {
        var isok = confirm("Do you want delete this Area?");
        if (isok == false) {
            return false;
        } else {
            var area_id = $(this).attr('value');
            var c_obj = $(this).parents("tr");
            $.ajax({
                url: 'areaCreation/ajaxDeleteArea.php',
                type: 'POST',
                data: { "area_id": area_id },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Rights");
                    if (delresult) {
                        $('#areaDeleteNotOk').show();
                        setTimeout(function () {
                            $('#areaDeleteNotOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {
                        c_obj.remove();
                        $('#areaDeleteOk').show();
                        setTimeout(function () {
                            $('#areaDeleteOk').fadeOut('fast');
                        }, 2000);

                        $("#area_name").val('');
                        $("#area_id").val('');
                    }
                }
            });
        }
    });

    $(function () {
        $('#areaTable').DataTable({
            "order": [[0, "desc"]],
            'processing': true,
            'iDisplayLength': 5,
            // "language": {
            //   "lengthMenu": "Display _MENU_ Records Per Page",
            //   "info": "Showing Page _PAGE_ of _PAGES_",
            // }
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
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
        var talukselected = $('#taluk1').val();
        getTalukBasedArea(talukselected);
    }
}

// ************************************************************************************************************************************************
//Sub Area Modal 
{
    // Modal Box for Sub Area Name
    $("#subareanameCheck").hide();
    $(document).on("click", "#submiSubAreaBtn", function () {
        var sub_area_id = $("#sub_area_id").val();
        var sub_area_name = $("#sub_area_name").val();
        var area_id_ref = $("#area").val();
        if (sub_area_name != "") {
            $.ajax({
                url: 'areaCreation/ajaxInsertSubArea.php',
                type: 'POST',
                data: { "sub_area_name": sub_area_name, "sub_area_id": sub_area_id, 'area_id_ref': area_id_ref },
                cache: false,
                success: function (response) {
                    var insresult = response.includes("Exists");
                    var updresult = response.includes("Updated");
                    if (insresult) {
                        $('#subareaInsertNotOk').show();
                        setTimeout(function () {
                            $('#subareaInsertNotOk').fadeOut('fast');
                        }, 2000);
                    } else if (updresult) {
                        $('#subareaUpdateOk').show();
                        setTimeout(function () {
                            $('#subareaUpdateOk').fadeOut('fast');
                        }, 2000);
                        $("#subAreaTable").remove();
                        resetSubAreaTable(area_id_ref);
                        $("#sub_area_name").val('');
                        $("#sub_area_id").val('');
                    }
                    else {
                        $('#subareaInsertOk').show();
                        setTimeout(function () {
                            $('#subareaInsertOk').fadeOut('fast');
                        }, 2000);
                        $("#subAreaTable").remove();
                        resetSubAreaTable(area_id_ref);
                        $("#sub_area_name").val('');
                        $("#sub_area_id").val('');
                    }
                }
            });
        }
        else {
            $("#subareanameCheck").show();
        }
    });


    function resetSubAreaTable(area) {
        $.ajax({
            url: 'areaCreation/ajaxResetSubAreaTable.php',
            type: 'POST',
            data: { 'area': area },
            cache: false,
            success: function (html) {
                $("#updatedSubAreaTable").empty();
                $("#updatedSubAreaTable").html(html);

                $("#sub_area_name").val('');
                $("#sub_area_id").val('');
            }
        });
    }

    $("#sub_area_name").keyup(function () {
        var CTval = $("#sub_area_name").val();
        if (CTval.length == '') {
            $("#subareanameCheck").show();
            return false;
        } else {
            $("#subareanameCheck").hide();
        }
    });

    $("body").on("click", "#edit_sub_area", function () {
        var sub_area_id = $(this).attr('value');
        $("#sub_area_id").val(sub_area_id);
        $.ajax({
            url: 'areaCreation/ajaxEditSubArea.php',
            type: 'POST',
            data: { "sub_area_id": sub_area_id },
            cache: false,
            success: function (response) {
                $("#sub_area_name").val(response);
            }
        });
    });

    $("body").on("click", "#delete_sub_area", function () {
        var isok = confirm("Do you want delete this Sub Area?");
        if (isok == false) {
            return false;
        } else {
            var sub_area_id = $(this).attr('value');
            var c_obj = $(this).parents("tr");
            $.ajax({
                url: 'areaCreation/ajaxDeleteSubArea.php',
                type: 'POST',
                data: { "sub_area_id": sub_area_id },
                cache: false,
                success: function (response) {
                    var delresult = response.includes("Rights");
                    if (delresult) {
                        $('#subareaDeleteNotOk').show();
                        setTimeout(function () {
                            $('#subareaDeleteNotOk').fadeOut('fast');
                        }, 2000);
                    }
                    else {
                        c_obj.remove();
                        $('#subareaDeleteOk').show();
                        setTimeout(function () {
                            $('#subareaDeleteOk').fadeOut('fast');
                        }, 2000);

                        $("#sub_area_name").val('');
                        $("#sub_area_id").val('');
                    }
                }
            });
        }
    });

    $(function () {
        $('#subAreaTable').DataTable({
            "order": [[0, "desc"]],
            'processing': true,
            'iDisplayLength': 5,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
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

    function closeSubModal() {
        var area = $('#area').val();
        getAreaBasedSubArea(area)
    }
}