$('#booking_advanc_search').click(function (ev)
{
    displaySetup("#booking_simple_search_form", "#advance_search_form");
});

$('#booking_simple_search_btn').click(function (ev)
{
    displaySetup("#advance_search_form", "#booking_simple_search_form");
});

$("#booking_simple_search").click(function ()
{
    var booking_id = $('#booking_id_search').val();

    if(booking_id == ''){
        alert("The search field cannot be empty");
        return;
    }
    else
    {
        var results = ajaxFunc("/booking_list_by_booking_id/", "GET", "booking_id="+booking_id);
        addRow(results.responseJSON);
    }
});

$('#advance_search_form').submit(function (ev)
{
    ev.preventDefault();
    var  data = $('#advance_search_form').serialize();
    var results = ajaxFunc("/booking_list_by_search/", "POST", data);

    if((results.responseJSON != '') && (results.responseJSON != null))
        addRow(results.responseJSON);
    else {
        alert("No data  found");
    }
});

$('#booking_reset_btn').click(function () {
    resetAllInputs('#booking_id_search','#advance_search_form');
})

function ajaxFunc(url, type, data)
{
    return $.ajax({
        url:url,
        type:type,
        data:data,
        cache: false,
        async: false,
    });
}

function displaySetup(disNone, disBlock)
{
    $(disNone).css('display','none');
    $(disBlock).css('display','block');
}

function resetAllInputs(searchFld, form)
{
    $(searchFld).val('');
    $(form).each(function(){
        $(this).find(':input:text').val('');
        $("input[type='date']").val('');
    });
}

function addRow(results)
{
    $('#booking_list_tbody').empty();
    $("#booking_list_pagination").css('display','none');

    var sl = 1;

    results.forEach(function(result)
    {
        $('#booking_list_tbody').append('<tr class="booking_list_table"><td>'+sl+
            '</td><td>'+result.buyer_name+
            '</td><td>'+result.Company_name+
            '</td><td>'+result.attention_invoice+
            '</td><td>'+result.booking_order_id+
            '</td><td>'+result.created_at+
            '</td><td>'+
            '</td><td><form action="./view/" target="_blank"><input type="hidden" name="bid" value="'+ result.booking_order_id+
            '"><button class="btn btn-success">View</button></form></td></tr>');
        sl++;
    });
}

// challan list Search option

$('#challan_advanc_search').click(function (ev)
{
    displaySetup("#challan_simple_search_form", "#challan_advance_search_form");
});

$('#challan_simple_search_btn').click(function (ev)
{
    displaySetup("#challan_advance_search_form", "#challan_simple_search_form");
});

$("#challan_simple_search").click(function ()
{
    var challan_id = $('#challan_id_search').val();

    if(challan_id == ''){
        alert("The search field cannot be empty");
        return;
    }
    else
    {
        var results = ajaxFunc("/challan_list_by_challan_id/", "GET", "challan_id="+challan_id);
        addRowInChallanList(results.responseJSON);
    }
});

$('#challan_advance_search_form').submit(function (ev)
{
    ev.preventDefault();
    var  data = $('#challan_advance_search_form').serialize();
    var results = ajaxFunc("/challan_list_by_search/", "POST", data);

    if((results.responseJSON != '') && (results.responseJSON != null))
        addRowInChallanList(results.responseJSON);
    else {
        alert("No data  found");
    }
});

function addRowInChallanList(results)
{
    $('#challan_list_tbody').empty();
    $("#challan_list_pagination").css('display','none');

    var getUrl = document.URL;
    var setUrl = getUrl.replace("view/challan/list","challan/list/action/task")

    var sl = 1;

    results.forEach(function(result)
    {
        $('#challan_list_tbody').append('<tr class="challan_list_table"><td>'+sl+
            '</td><td>'+result.checking_id+
            '</td><td>'+result.challan_id+
            '</td><td>'+result.created_at+
            '</td><td><form action='+setUrl+' target="_blank"><input type="hidden" name="cid" value="'+ result.checking_id+
            '"><input type="hidden" name="bid" value="'+ result.challan_id+
            '"><button class="btn btn-success">View</button></form></td></tr>');
        sl++;
    });
}

$('#challan_reset_btn').click(function () {
    resetAllInputs('#challan_id_search','#challan_advance_search_form');
})

// MRF search List

$('#mrf_advanc_search').click(function (ev)
{
    displaySetup("#mrf_simple_search_form", "#mrf_advance_search_form");
});

$('#mrf_simple_search_btn').click(function (ev)
{
    displaySetup("#mrf_advance_search_form", "#mrf_simple_search_form");
});

$("#mrf_simple_search").click(function ()
{
    var mrf_id = $('#mrf_id_search').val();

    if(mrf_id == ''){
        alert("The search field cannot be empty");
        return;
    }
    else
    {
        var results = ajaxFunc("/mrf_list_by_mrf_id/", "GET", "mrf_id="+mrf_id);
        addRowInMrfanList(results.responseJSON);
    }
});

$('#mrf_advance_search_form').submit(function (ev)
{
    ev.preventDefault();
    var  data = $('#mrf_advance_search_form').serialize();
    var results = ajaxFunc("/mrf_list_by_search/", "POST", data);

    if((results.responseJSON != '') && (results.responseJSON != null))
        addRowInMrfanList(results.responseJSON);
    else {
        alert("No data  found");
    }
});

function addRowInMrfanList(results)
{
    $('#mrf_list_tbody').empty();
    $("#mrf_list_pagination").css('display','none');

    var getUrl = document.URL;
    var setUrl = getUrl.replace("mrf/list/list","task/mrf/task/list")
    var sl = 1;

    var start = 0;
    var end = 10;

    // alert(results.length);

    results.forEach(function(result)
    {
        alert(typeof(result));
        console.log(result);
        $('#mrf_list_tbody').append('<tr class="mrf_list_table"><td>'+sl+
            '</td><td>'+result.booking_order_id+
            '</td><td>'+result.mrf_id+
            '</td><td>'+result.created_at+
            '</td><td>'+result.shipmentDate+
            '</td><td><form action='+setUrl+' target="_blank"><input type="hidden" name="mid" value="'+ result.mrf_id+
            '"><input type="hidden" name="bid" value="'+ result.booking_order_id+
            '"><button class="btn btn-success">View</button></form></td></tr>');
        sl++;
    });
}

$('#mrf_reset_btn').click(function () {
    resetAllInputs('#mrf_id_search','#mrf_advance_search_form');
})
