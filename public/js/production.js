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
        addRow(results.responseJSON, 0);
    }
});

$('#advance_search_form').submit(function (ev)
{
    ev.preventDefault();
    var  data = $('#advance_search_form').serialize();
    var results = ajaxFunc("/booking_list_by_search/", "POST", data);

    if((results.responseJSON != '') && (results.responseJSON != null))
        addRow(results.responseJSON, 0);
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

function setPagination(results, position) {
    // if(results.length > end)
    // {
    var pageNum = Math.ceil(results.length/15);
    var previous = (position-1);
    var next = (position+1);
    if(position == 1)
        previous = 1;
    if(position == pageNum)
        next = pageNum;
    $('.pagination').append('<li data-page="'+ previous +'"><span>&laquo;<span class="sr-only">(current)</span></span></li>').show();
    for (i = 1; i <= pageNum;)
    {
        $('.pagination').append('<li data-page="'+i+'">\<span>'+ i++ +'<span class="sr-only">(current)</span></span>\</li>').show();
    }
    $('.pagination').append('<li data-page="'+ next +'"><span>&raquo;<span class="sr-only">(current)</span></span></li>').show();
    // $('.pagination').append('<li><a href="http://127.0.0.1:8000/view/challan/list?page=2" rel="next">&raquo;</a></li>').show();

    $('.pagination li:nth-child('+ (position+1) +')').addClass('active');

    if(position == 1)
        $('.pagination li:first-child').addClass('disabled');
    if(position == pageNum)
        $('.pagination li:last-child').addClass('disabled');
    // }
}

function addRow(results, start)
{
    $('.pagination').empty();
    $('#booking_list_tbody').empty();
    $("#booking_list_pagination").css('display','none');

    var sl = 1;

    var position = start+1;
    start = start*15;

    if(results.length <start+15)
        end = results.length;
    else
        end = start+15;

    var rows = $.map(results, function(value, index) {
        return [value];
    });

    for (var i = start; i < end; i++)
    {
        $('#booking_list_tbody').append('<tr class="booking_list_table"><td>'+sl+
            '</td><td>'+rows[i].buyer_name+
            '</td><td>'+rows[i].Company_name+
            '</td><td>'+rows[i].attention_invoice+
            '</td><td>'+rows[i].booking_order_id+
            '</td><td>'+rows[i].created_at+
            '</td><td>'+
            '</td><td><form action="./view/" target="_blank"><input type="hidden" name="bid" value="'+ rows[i].booking_order_id+
            '"><button class="btn btn-success">View</button></form></td></tr>');
        sl++;
    }

    setPagination(results, position);

    $('.pagination li').on('click',(function () {

        var begin = $(this).attr("data-page");
        addRow(results, begin-1);
    }));
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
        addRowInChallanList(results.responseJSON, 0);
    }
});

$('#challan_advance_search_form').submit(function (ev)
{
    ev.preventDefault();
    var  data = $('#challan_advance_search_form').serialize();
    var results = ajaxFunc("/challan_list_by_search/", "POST", data);

    if((results.responseJSON != '') && (results.responseJSON != null))
        addRowInChallanList(results.responseJSON, 0);
    else {
        alert("No data  found");
    }
});

function addRowInChallanList(results, start)
{
    $('.pagination').empty();
    $('#challan_list_tbody').empty();
    $("#challan_list_pagination").css('display','none');

    var getUrl = document.URL;
    var setUrl = getUrl.replace("view/challan/list","challan/list/action/task")

    var sl = 1;

    var position = start+1;
    start = start*15;

    if(results.length <start+15)
        end = results.length;
    else
        end = start+15;

    var rows = $.map(results, function(value, index) {
        return [value];
    });

    for (var i = start; i < end; i++)
    {
        $('#challan_list_tbody').append('<tr class="challan_list_table"><td>'+sl+
                '</td><td>'+rows[i].checking_id+
                '</td><td>'+rows[i].challan_id+
                '</td><td>'+rows[i].created_at+
                '</td><td><form action='+setUrl+' target="_blank"><input type="hidden" name="cid" value="'+ rows[i].checking_id+
                '"><input type="hidden" name="bid" value="'+ rows[i].challan_id+
                '"><button class="btn btn-success">View</button></form></td></tr>');
            sl++;
    }

    setPagination(results, position);

    $('.pagination li').on('click',(function () {

        var begin = $(this).attr("data-page");
        addRowInChallanList(results, begin-1);

    }));
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
        addRowInMrfanList(results.responseJSON, 0);
    }
});

$('#mrf_advance_search_form').submit(function (ev)
{
    ev.preventDefault();
    var  data = $('#mrf_advance_search_form').serialize();
    var results = ajaxFunc("/mrf_list_by_search/", "POST", data);

    if((results.responseJSON != '') && (results.responseJSON != null))
        addRowInMrfanList(results.responseJSON, 0);
    else {
        alert("No data  found");
    }
});

function addRowInMrfanList(results, start)
{
    $('.pagination').empty();
    $('#mrf_list_tbody').empty();
    $('.mrf_list_table').remove();
    $("#mrf_list_pagination").css('display','none');

    var getUrl = document.URL;
    var setUrl = getUrl.replace("mrf/list/list","task/mrf/task/list")
    var sl = 1;

    var position = start+1;
    start = start*15;

    if(results.length <start+15)
        end = results.length;
    else
        end = start+15;

    var rows = $.map(results, function(value, index) {
        return [value];
    });

    for (var i = start; i < end; i++)
    {
        $('#mrf_list_tbody').append('<tr class="mrf_list_table"><td>'+sl+
            '</td><td>'+rows[i].booking_order_id+
            '</td><td>'+rows[i].mrf_id+
            '</td><td>'+rows[i].created_at+
            '</td><td>'+rows[i].shipmentDate+
            '</td><td><form action='+setUrl+' target="_blank"><input type="hidden" name="mid" value="'+ rows[i].mrf_id+
            '"><input type="hidden" name="bid" value="'+ rows[i].booking_order_id+
            '"><button class="btn btn-success">View</button></form></td></tr>');
        sl++;
    }

    setPagination(results, position);

    $('.pagination li').on('click',(function () {

        // $('.pagination li').removeClass('active');
        // $(this).addClass('active');
        var begin = $(this).attr("data-page");
        addRowInMrfanList(results, begin-1);

    }));
}

$('#mrf_reset_btn').click(function () {
    resetAllInputs('#mrf_id_search','#mrf_advance_search_form');
})
