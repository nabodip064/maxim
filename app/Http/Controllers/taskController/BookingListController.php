<?php

namespace App\Http\Controllers\taskController;

use App\Http\Controllers\dataget\ListGetController;
use App\Http\Controllers\Message\StatusMessage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RoleManagement;
use Illuminate\Http\Request;
use Validator;
use Auth;
use DB;

class BookingListController extends Controller
{
    public function bookingListView(){

        $bookingList = DB::table('mxp_bookingBuyer_details')
            ->groupBy('booking_order_id')
            ->orderBy('id','DESC')
            ->paginate(15);

        return view('maxim.booking_list.booking_list_page',compact('bookingList'));
    }

    public function showBookingReport(Request $request){
        $bookingReport = DB::select("call getBookinAndBuyerDeatils('".$request->bid."')");

        $companyInfo = DB::table('mxp_header')->where('header_type',11)->get();

        $gmtsOrSizeGroup = DB::select("SELECT gmts_color,GROUP_CONCAT(item_size) as itemSize,GROUP_CONCAT(item_quantity) as quantity from mxp_booking WHERE booking_order_id = '".$request->bid."' GROUP BY gmts_color");

        return view('maxim.orderInput.reportFile',compact('bookingReport','companyInfo','gmtsOrSizeGroup'));
    }

    public function getBookingListByBookingId(Request $request){

        $bookingList = DB::table('mxp_bookingBuyer_details')
            ->where('booking_order_id', 'like', '%'.$request->booking_id.'%')
            ->orderBy('id','DESC')
            ->get();

        return $bookingList;
    }

    public function getBookingListBySearch(Request $request){

        $bookingList = DB::table('mxp_bookingBuyer_details');
        $checkValidation = false;

        if($request->buyer_name_search != '')
        {
            $checkValidation = true;
            $bookingList->where('buyer_name','like','%'.$request->buyer_name_search.'%');
        }
        if($request->company_name_search != '')
        {
            $checkValidation = true;
            $bookingList->where('Company_name','like','%'.$request->company_name_search.'%');
        }
        if($request->attention_search != '')
        {
            $checkValidation = true;
            $bookingList->where('attention_invoice','like','%'.$request->attention_search.'%');
        }
        if($request->from_oder_date_search != '' && $request->to_oder_date_search != '')
        {
            $checkValidation = true;
            if($request->from_oder_date_search == $request->to_oder_date_search)
                $bookingList->whereDate('created_at', $request->from_oder_date_search);
            else
                $bookingList->whereBetween('created_at', [$request->from_oder_date_search, $request->to_oder_date_search]);
        }
//      if($request->from_shipment_date_search != '' && $request->to_shipment_date_search != '')
//      {
//          $checkValidation = true;
//          if($request->from_shipment_date_search == $request->to_shipment_date_search)
//              $bookingList->whereDate('created_at', $request->from_shipment_date_search);
//          else
//              $bookingList->whereBetween('created_at', [$request->from_shipment_date_search, $request->to_shipment_date_search]);
//      }

        if($checkValidation)
        {
            $bookings = $bookingList->get();
            return $bookings;
        }
        else
            return null;
    }

    public function createIpoView(Request $request){
        return 'createIpoView and booking id is '.$request->booking_id;
    }

    public function createMrfView(Request $request){
        return 'createMrfView and booking id is '.$request->booking_id;
    }
}
