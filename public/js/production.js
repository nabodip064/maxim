$('#booking_advanc_search').click(function (ev) {
    ev.preventDefault();
    $("#booking_simple_search_form").css('display','none');
    $('#advance_search_form').css('display','block');
});

$('#booking_simple_search_btn').click(function (ev) {
    ev.preventDefault();
    $("#booking_simple_search_form").css('display','block');
    $('#advance_search_form').css('display','none');
});

$("#booking_simple_search").click(function (ev) {
    var booking_id = $('#booking_id_search').val();
    ev.preventDefault();

    $.ajax({
        url:"/booking_list_by_booking_id/",
        type:"GET",
        data:"booking_id="+booking_id,
        cache: false,
        async: false,
        success:function(result){

            if(result.length > 0){
                addRow(result);
            }
            else {
                alert("No data  found");
                return;
            }
        },
        error:function(result){
            alert("Error");
        }
    });
});

$('#advance_search_form').submit(function (ev) {

    ev.preventDefault();
    var  data = $('#advance_search_form').serialize();

    $.ajax({
        url: "/booking_list_by_search/",
        type:"POST",
        data: data,
        success:function(result)
        {
            if(result.length > 0){
                addRow(result);
            }
            else {
                alert("No data  found");
                return;
            }
        },
        error:function(er) {
            console.log(er);
            alert("ERROR "+er);
        }
    });
});

function addRow(results){

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

