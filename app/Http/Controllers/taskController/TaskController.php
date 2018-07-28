<?php

namespace App\Http\Controllers\taskController;

use App\Http\Controllers\dataget\ListGetController;
use App\Http\Controllers\Message\StatusMessage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RoleManagement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\MxpIpo;
use App\Model\MxpBookingBuyerDetails;
use App\Model\MxpBooking;
use Validator;
use Auth;
use DB;


class TaskController extends Controller
{
    CONST CREATE_IPO = "create";
    CONST UPDATE_IPO = "update";

    public function getBuyerCompany(Request $request){
    	return json_encode(DB::table('mxp_party')->where([['name_buyer', $request->buyerName],['user_id',Auth::user()->user_id]])->get());
    }

    public function getItemCode()
    {
        $results = array();
        $productDetails = DB::select("SELECT mp.product_code FROM mxp_product mp 
        LEFT JOIN mxp_productSize mps ON (mps.product_code = mp.product_code)
        LEFT JOIN mxp_gmts_color mgs ON (mgs.item_code = mps.product_code) GROUP BY mps.product_code, mgs.item_code");
        if(isset($productDetails) && !empty($productDetails)){
            foreach ($productDetails as $itemKey => $itemValue) {
                
                $results[]['name'] = $itemValue->product_code;
            }
        }
        print json_encode($results);
    }
    public function gettaskActionOrsubmited()
    {
        return \Redirect()->Route('dashboard_view');
    }
    public function taskActionOrsubmited(Request $request){
     	$roleManage = new RoleManagement();
     	$datas = $request->all();

     	$taskType = isset($request->taskType) ? $request->taskType : '';
     	if($taskType === 'booking'){
         	$taskType = "Create Booking";

         	$buyerDetails = DB::table('mxp_party')
         					->where([
         						['name_buyer',$request->buyerName],
         						['name',$request->companyName]				
         						])
         					->get();

            // $productDetails = DB::select("SELECT mp.product_code FROM mxp_product mp 
            // LEFT JOIN mxp_productSize mps ON (mps.product_code = mp.product_code)
            // LEFT JOIN mxp_gmts_color mgs ON (mgs.item_code = mps.product_code) GROUP BY mps.product_code, mgs.item_code");


         	return view('maxim.orderInput.orderInputIndex',compact('buyerDetails'))->with(['taskType' => $taskType]);

         }elseif($taskType === 'PI'){

            $formatTypes = $request->piFormat;
            $companyInfo = DB::table('mxp_header')->where('header_type',11)->get();
            $bookingDetails = DB::select('call getBookinAndBuyerDeatils("'.$request->bookingId.'")');
            if(empty($bookingDetails)){
                StatusMessage::create('empty_booking_data', 'This booking Id doesnot show any result . Please check booking Id !');

                return \Redirect()->Route('dashboard_view');
            }

            $footerData = DB::select("select * from mxp_reportFooter");

            return view('maxim.pi_format.piReportPage',compact('companyInfo','bookingDetails','footerData','formatTypes'));

         }elseif($taskType === 'IPO'){
            $roleManage = new RoleManagement();
            $ipoIncrease = $request->ipoIncrease;

            $validMessages = [
                'bookingId.required' => 'Booking Id is required.',
                'ipoIncrease.required' => 'Increase Value is required.'
                ];
            $datas = $request->all();
            $validator = Validator::make($datas, 
                  [
                    'bookingId' => 'required',
                    'ipoIncrease' => 'required'
                ],$validMessages);            

            if ($validator->fails()) {
              return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
            }
            $validationError = $validator->messages();
            $IpoUniqueID = "RGA".Carbon::now()->format('dmY')."-ACL-02-".mt_rand(10000,99999);

            $bookingDetails = DB::select("select user_id,booking_order_id,erp_code,item_code,item_description,GROUP_CONCAT(item_size) as item_size,GROUP_CONCAT(item_quantity) as item_quantity,item_price,matarial,gmts_color,others_color,orderDate,orderNo,shipmentDate,poCatNo,created_at,updated_at from mxp_booking where booking_order_id= '".$request->bookingId."' GROUP BY item_code");


            if (empty($bookingDetails)) {
              return \Redirect()->Route('dashboard_view');
            }
            foreach ($bookingDetails as $details) {
                $createIpo = new MxpIpo();
                $createIpo->user_id = Auth::user()->user_id;
                $createIpo->ipo_id = $IpoUniqueID;
                $createIpo->initial_increase = $ipoIncrease;
                $createIpo->booking_order_id = $details->booking_order_id;
                $createIpo->erp_code = $details->erp_code;
                $createIpo->item_code = $details->item_code;
                $createIpo->item_description = $details->item_description;
                $createIpo->item_size = $details->item_size;
                $createIpo->item_quantity = $details->item_quantity;
                $createIpo->item_price = $details->item_price;
                $createIpo->matarial = $details->matarial;
                $createIpo->gmts_color = $details->gmts_color;
                $createIpo->others_color = $details->others_color;
                $createIpo->orderDate = $details->orderDate;
                $createIpo->orderNo = $details->orderNo;
                $createIpo->shipmentDate = $details->shipmentDate;
                $createIpo->poCatNo = $details->poCatNo;
                $createIpo->status = self::CREATE_IPO;
                $createIpo->save();
            }
            $ipoDetails = DB::table("mxp_ipo")->where('ipo_id',$IpoUniqueID)->get();
            $buyerDetails = DB::table("mxp_bookingBuyer_details")->where('booking_order_id',$request->bookingId)->get();
            $headerValue = DB::table("mxp_header")->where('header_type',11)->get();
            return view('maxim.ipo.ipoBillPage',[
                'headerValue' => $headerValue,
                'initIncrease' => $request->ipoIncrease,
                'buyerDetails' => $buyerDetails,
                'sentBillId' => $ipoDetails
            ]);

         }elseif($taskType === 'MRF'){
            $data = $request->all();
            $booking_order_id = $request->bookingId;
            $validMessages = [
                    'bookingId.required' => 'Booking Id field is required.'
                    ];
            $validator = Validator::make($datas, 
                [
                    'bookingId' => 'required',
                ],
                $validMessages
            );

            if ($validator->fails()) {
                return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
            }

            $validationError = $validator->messages();

            $bookingDetails = DB::select("SELECT * FROM mxp_booking_challan WHERE booking_order_id = '".$request->bookingId."' GROUP BY item_code");
            // self::print_me($bookingDetails);

            $buyerDetails = DB::select("SELECT * FROM mxp_bookingBuyer_details WHERE booking_order_id = '".$request->bookingId."'");

            if(empty($bookingDetails)){
                StatusMessage::create('empty_booking_data', 'This booking Id does not show any result . Please check booking Id !');

                return \Redirect()->Route('dashboard_view');
            }

            $MrfDetails = DB::select("select * from mxp_MRF_table where booking_order_id = '".$request->bookingId."' GROUP BY mrf_id");

            return view('maxim.mrf.mrf',compact('bookingDetails','MrfDetails','booking_order_id'));

         }elseif($taskType === 'challan'){

            $validMessages = [
                    'bookingIdList.required' => 'Booking Id field is required.'
                    ];
            $validator = Validator::make($datas, 
                [
                    'bookingIdList' => 'required',
                ],
                $validMessages
            );

            if ($validator->fails()) {
                return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
            }

            $validationError = $validator->messages();
            $bookedId = rtrim($request->bookingIdList,", ");

            $bookingIds = explode(",",$bookedId);
            $bookingIdList = [];
            $companyName = '';
            $iteration = 0;

         	foreach ($bookingIds as $bookingId){
         	    $tempbookingId = $bookingId;
                $tempbookingId =  str_replace(' ','', $tempbookingId);
                $tempbookingId = str_replace(',', '', $tempbookingId);
                $companyDetails = MxpBookingBuyerDetails::where('booking_order_id', $tempbookingId)->first();
                if($iteration > 0){
                    if ($companyDetails->Company_name != $companyName){
                        return redirect()->back()->withInput($request->input())->withErrors("Booking order ids are not in same company");
                    }
                }
                $companyName = $companyDetails->Company_name;
                $iteration++;
                $bookingDetails = DB::select("SELECT * FROM mxp_items_details_by_booking_challan WHERE booking_order_id = '".$tempbookingId."'");
                foreach ($bookingDetails as $currentBooking){
                    $checkMatch = true;
                    foreach ($bookingIdList as $bookingList){
                        if(($bookingList->item_code == $currentBooking->item_code) && ($bookingList->item_size == $currentBooking->item_size) && ($bookingList->gmts_color == $currentBooking->gmts_color)){
//                            echo "item size is ".$currentBooking->item_size." has qnty ".$currentBooking->item_quantity." and pre total is ".$bookingList->item_quantity." and after add ";
                            $bookingList->item_quantity += $currentBooking->item_quantity;
                            $bookingList->booking_order_id = $bookingList->booking_order_id.','.$currentBooking->booking_order_id;
                            $bookingList->booking_challan_id = $bookingList->booking_challan_id.','.$currentBooking->booking_challan_id;
                            $checkMatch = false;
//                            echo $bookingList->item_quantity."<br>";
                        }
                    }
                    if ($checkMatch)
                        array_push($bookingIdList, $currentBooking);
                }
            }
//            $this->print_me($bookingIdList);

//            for ($i = 1; $i < count($bookingIdList); $i++){
//         	    for( $j = 0; $j < $i; $j++){
//                    if ($bookingIdList[$i]->item_code == $bookingIdList[$j]->item_code){
//                        $bookingIdList[$j]->item_size = $bookingIdList[$j]->item_size.','.$bookingIdList[$i]->item_size;
//                        $bookingIdList[$j]->item_quantity = $bookingIdList[$j]->item_quantity.','.$bookingIdList[$i]->item_quantity;
//                        $bookingIdList[$j]->gmts_color = $bookingIdList[$j]->gmts_color.','.$bookingIdList[$i]->gmts_color;
////                        $bookingIdList[$j]->booking_order_id = $bookingIdList[$j]->booking_order_id.','.$bookingIdList[$i]->booking_order_id;
//                        $bookingIdList[$j]->booking_challan_id = $bookingIdList[$j]->booking_challan_id.'_'.$bookingIdList[$i]->booking_challan_id;
//                        unset($bookingIdList[$i]);
//                    }
//                }
//            }
            foreach ($bookingIdList as $booking){
         	    if (!$booking->items_details_id)
                    unset($booking);
            }
            $bookingDetails = $bookingIdList;
//            $this->print_me($bookingIdList);

            $buyerDetails = DB::select("SELECT * FROM mxp_bookingBuyer_details WHERE booking_order_id = '".$request->bookingId."'");

            if(empty($bookingDetails)){
                StatusMessage::create('empty_booking_data', 'This booking Id does not show any result . Please check booking Id !');

                return \Redirect()->Route('dashboard_view');
            }

            return view('maxim.challan.challan',compact('bookingDetails'));
         }else{
            $validMessages = [
                    'taskType.required' => 'TaskType field is required.'
                    ];
            $validator = Validator::make($datas, 
                [
                    'taskType' => 'required',
                ],
                $validMessages
            );

            if ($validator->fails()) {
                return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
            }

            $validationError = $validator->messages();
         }
    }
    public static function getOrderQuantity($booking_order_id, $item_code, $item_size = null, $color = null)
    {
        
        if($item_size == null){
            if ($color == null)
                $bookingQuantityDetails = DB::select("SELECT item_quantity FROM mxp_booking WHERE booking_order_id = '".$booking_order_id."' AND item_code = '".$item_code."'");
            else
                $bookingQuantityDetails = DB::select("SELECT item_quantity FROM mxp_booking WHERE booking_order_id = '".$booking_order_id."' AND item_code = '".$item_code."' AND gmts_color = '".$color."'");
        }else{
            if ($color == null)
                $bookingQuantityDetails = DB::select("SELECT item_quantity FROM mxp_booking WHERE booking_order_id = '".$booking_order_id."' AND item_code = '".$item_code."' AND item_size = '".$item_size."'");
            else
                $bookingQuantityDetails = DB::select("SELECT item_quantity FROM mxp_booking WHERE booking_order_id = '".$booking_order_id."' AND item_code = '".$item_code."' AND item_size = '".$item_size."' AND gmts_color = '".$color."'");
        }
        return isset($bookingQuantityDetails[0]->item_quantity) ? $bookingQuantityDetails[0]->item_quantity : 0;

    }
}
