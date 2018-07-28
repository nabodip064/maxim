@extends('maxim.layouts.layouts')
@section('title','Booking Maxim')
@section('print-body')
<?php
	$getBuyerName = '';

	foreach($bookingReport as $details){
		$getBuyerName = $details->buyer_name;
	}
?>
	<center>
		<div class="topPreviews">
			<a href="#" onclick="myFunction()"  class="print" id="print">Print & Preview</a>
		</div>
	</center>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 40px;">
			@php($i=0)
			@foreach($bookingReport as $details)
				@for($i;$i <= 0;$i++)
					<h2 align="center">{{$details->buyer_name}} , ACC BOOKING</h2>
				@endfor
			@endforeach
			<div align="center">
					<p>NWP - AW18 BOOKING ( JUNE )</p>
			</div>
		</div>

		<div class="col-md-12 col-xs-12">
			@php ($k =0)
			@foreach ($bookingReport as $details)
				@for ($k;$k <= 0; $k++)
				<div class="col-xs-6 col-md-6">
					<div class="pull-left">
						<p>Booking Date: {{Carbon\Carbon::parse($details->created_at)->format('Y-m-d')}}</p>
					</div>
				</div>
				<div class="col-xs-6 col-md-6">
					<div class="pull-right">
						<ul>
							<li>Booking No: {{$details->booking_order_id}}</li>
							<li>Delivery Date: {{Carbon\Carbon::parse($details->shipmentDate)->format('Y-m-d')}}</li>
						</ul>
					</div>
				</div>
				@endfor
			@endforeach

		</div>
	</div>

	<div class="row body-top">
		<div class="col-md-8 col-sm-8 col-xs-7 body-list">
			<ul>
				@foreach ($companyInfo as $details)
				<li>To : {{$details->header_title}}</li>
				<li><p> {{$details->address1}} {{(!empty($details->address2) ? ',' : '')}} {{$details->address2}} {{(!empty($details->address3) ? ',' : '')}} {{$details->address3}}</p></li>

				<li>CELL : {{$details->cell_number}}</li>
				<li>Attn : {{$details->attention}}</li>
				@endforeach
			</ul>
		</div>

		<div class="col-md-4 col-sm-4 col-xs-5">
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<center>
				<h4>Re : PURCHASE ORDER of ACCESSORIES against {{$getBuyerName}}</h4>
			</center>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h4>Dear Sir's</h4>
			<p>Please issue P/I and supply the flowing goods favoring Importer's Factory as under :</p>
		</div>
	</div>

	<div class="row body-top">
		<div class="col-md-8 col-sm-8 col-xs-7 body-list">
					@php($i=0)
					@foreach($bookingReport as $details)
					@for($i;$i <= 0;$i++)
						<ul>
							<li style="font-weight: bold;">Buyer : {{$details->buyer_name}}</li>
						</ul>
					@endfor
					@endforeach
		</div>

		<div class="col-md-4 col-sm-4 col-xs-5">
		</div>
	</div>

    @foreach ($bookingReport as $details)
		<table class="table table-bordered">
	        <?php

	        	$gmtsColor = [];
	        	$gmtsItemSize = [];
	        	$gmtsquantity = '';
	        	$gmtsItemQtyValue = [];
	        	foreach ($gmtsOrSizeGroup as $gmtsValue) {
	        		$gmtsColor[] = $gmtsValue->gmts_color;
	        		$gmtsItemSize = explode(',', $gmtsValue->itemSize);
	        		$gmtsquantity = explode(',', $gmtsValue->quantity);
	        		$gmtsItemQtyValue[] = array_combine($gmtsItemSize, $gmtsquantity);
	        	}
	        	$count = count($gmtsColor) - 1;
	        	$gmtscolorAllValues = array();
	        	for ($i=0; $i <= $count ; $i++) {
	        		$gmtscolorAllValues[$i][] =$gmtsColor[$i];
	        		$gmtscolorAllValues[$i][] =$gmtsItemQtyValue[$i];
	        	}


	        	$itemsize = explode(',', $details->itemSize);
	        	$qty = explode(',', $details->quantity);
	        	$itemQtyValue = array_combine($itemsize, $qty);

	        	$itemName = [];
	        	foreach ($itemQtyValue as $key => $value) {
	        		$itemName[$key] = $key;
	        	}

	        	// colspan count values

	        	$array1 =[
	        		'0' => 's','1' => 'S','2' => 'm','3' => 'M','4' => 'xl','5' => 'XL',
	        		'6' => 'xxl','7' => 'XXL','8' => '4xl','9' => '4XL','10' => '5xl',
	        		'11' => '5XL'
	        	];
	        	$countValue = array_intersect($array1, $itemName);
	        	$colspanValue = 2 + count($countValue);


	        	// print_r("<pre>");
	        	// print_r($colspanValue);

	        ?>


	        <!-- table head section -->

		    <thead>
		        <tr>
		        	<th width="18%">ITEM / CODE NO</th>

		        	@if(!empty($details->gmtsColor))
		        		<th width="22%">GMTS COLOR</th>
		        	@endif

		        	@php($s = 0)
		        		@for($s;$s<=0;$s++)
		        			@if(
		        			'S' === array_search('S',$itemName) ||
		        			's' === array_search('s',$itemName) ||
		        			'M' === array_search('M',$itemName) ||
		        			'm' === array_search('m',$itemName) ||
		        			'xl' === array_search('xl',$itemName) ||
		        			'XL' === array_search('XL',$itemName) ||
		        			'xxl' === array_search('xxl',$itemName) ||
		        			'XXL' === array_search('XXL',$itemName) ||
		        			'4xl' === array_search('4xl',$itemName) ||
		        			'4XL' === array_search('4XL',$itemName) ||
		        			'5xl' === array_search('5xl',$itemName) ||
		        			'5XL' === array_search('5XL',$itemName)
		        			)
		        				@foreach ($itemQtyValue as $keys =>$itemFormat)
			        				@if(sizeof($itemQtyValue) === 1)
			        					<th width="">{{$keys}}</th>
			        				@else
						        		<th>{{$keys}}</th>
						        	@endif
					        	@endforeach

					        @elseif (empty($details->others_color))
		        				<th width="45%">Size</th>
		        			@elseif ($details->others_color)
		        				<th>{{$details->others_color}}</th>
		        			@else
		        				<th>Something Wrong</th>
		        			@endif
		        		@endfor

		        	<th width="21%">ORDER QTY</th>

		        	<th>UNIT</th>

		        </tr>
		    </thead>

		    <!-- End table head section -->

		    <!-- table tbody Section -->

		    <tbody>
		    	<td width="18%">{{$details->item_code}}</td>

		    	@if(!empty($details->gmtsColor))
		    		@if(
		    			'S' === array_search('S',$itemName) ||
				        's' === array_search('s',$itemName) ||
		    			'M' === array_search('M',$itemName) ||
		    			'm' === array_search('m',$itemName) ||
		    			'xl' === array_search('xl',$itemName) ||
		    			'XL' === array_search('XL',$itemName) ||
		    			'xxl' === array_search('xxl',$itemName) ||
		    			'XXL' === array_search('XXL',$itemName) ||
		    			'4xl' === array_search('4xl',$itemName) ||
		    			'4XL' === array_search('4XL',$itemName) ||
		    			'5xl' === array_search('5xl',$itemName) ||
		    			'5XL' === array_search('5XL',$itemName)
		    		)
			    		<td class="colspan-td" colspan="{{$colspanValue}}">
			    			<table>
			    				@foreach ($gmtscolorAllValues as $values)
			    					@foreach ($values[1] as $keys => $aaa)
			    					<?php $totalQnty = 0; $gmtscolorAllValuesKeys = [];$gmtscolorAllValuesKeys[$keys] = $keys; ?>
					    			@endforeach

				    					@if(
							    			'S' === array_search('S',$gmtscolorAllValuesKeys) ||
									        's' === array_search('s',$gmtscolorAllValuesKeys) ||
							    			'M' === array_search('M',$gmtscolorAllValuesKeys) ||
							    			'm' === array_search('m',$gmtscolorAllValuesKeys) ||
							    			'xl' === array_search('xl',$gmtscolorAllValuesKeys) ||
							    			'XL' === array_search('XL',$gmtscolorAllValuesKeys) ||
							    			'xxl' === array_search('xxl',$gmtscolorAllValuesKeys) ||
							    			'XXL' === array_search('XXL',$gmtscolorAllValuesKeys) ||
							    			'4xl' === array_search('4xl',$gmtscolorAllValuesKeys) ||
							    			'4XL' === array_search('4XL',$gmtscolorAllValuesKeys) ||
							    			'5xl' === array_search('5xl',$gmtscolorAllValuesKeys) ||
							    			'5XL' === array_search('5XL',$gmtscolorAllValuesKeys)
							    		)
					    					<tr>
						    					@if(!empty($values[0]))
							    					<td width="30%">{{$values[0]}}</td>
							    				@endif

							    				@if(!empty($values[1]))
							    					@foreach ($values[1] as $keyss => $values)
							    					<?php
							    						$totalQnty = $totalQnty + $values;
							    					?>
							    						<td>{{$values}}</td>
							    					@endforeach
							    				@endif

							    				<td width="29%">{{$totalQnty}}</td>
							    			</tr>
							    		@else
							    		@endif
					    		@endforeach
			    			</table>
			    		</td>
		    		@else
				    	<td class="colspan-td" colspan="3">
			    			<table>
			    				<tbody>
			    					@foreach ($gmtscolorAllValues as $keys => $values)
			    						@foreach ($values[1] as $keys => $aaa)
				    					<?php $gmtscolorAllValuesKeys = [];$gmtscolorAllValuesKeys[$keys] = $keys; ?>
						    			@endforeach

					    					@if(
								    			'S' === array_search('S',$gmtscolorAllValuesKeys) ||
										        's' === array_search('s',$gmtscolorAllValuesKeys) ||
								    			'M' === array_search('M',$gmtscolorAllValuesKeys) ||
								    			'm' === array_search('m',$gmtscolorAllValuesKeys) ||
								    			'xl' === array_search('xl',$gmtscolorAllValuesKeys) ||
								    			'XL' === array_search('XL',$gmtscolorAllValuesKeys) ||
								    			'xxl' === array_search('xxl',$gmtscolorAllValuesKeys) ||
								    			'XXL' === array_search('XXL',$gmtscolorAllValuesKeys) ||
								    			'4xl' === array_search('4xl',$gmtscolorAllValuesKeys) ||
								    			'4XL' === array_search('4XL',$gmtscolorAllValuesKeys) ||
								    			'5xl' === array_search('5xl',$gmtscolorAllValuesKeys) ||
								    			'5XL' === array_search('5XL',$gmtscolorAllValuesKeys)
								    		)
								    		@else
						    					<tr>
						    						@if(!empty($values[0]))
							    					<td width="30.7%">{{$values[0]}}</td>
							    					<td width="69.3%" colspan="2">
							    					 	@foreach ($values[1] as $size => $qty)
							    					 	<div class="middel-table">
								    					 	<table>
								    					 		<tr>
								    					 			<td width="57.5%">{{$size}}</td>
								    					 			<td width="42.5%">{{$qty}}</td>
								    					 		</tr>
								    					 	</table>
								    					</div>
							    					 	@endforeach
							    					</td>
							    					@endif
						    					</tr>
						    				@endif
			    					@endforeach
			    				</tbody>
			    			</table>
			    		</td>
		        	@endif
		        @endif

		        @if(
		        'S' === array_search('S',$itemName) ||
		        's' === array_search('s',$itemName) ||
    			'M' === array_search('M',$itemName) ||
    			'm' === array_search('m',$itemName) ||
    			'xl' === array_search('xl',$itemName) ||
    			'XL' === array_search('XL',$itemName) ||
    			'xxl' === array_search('xxl',$itemName) ||
    			'XXL' === array_search('XXL',$itemName) ||
    			'4xl' === array_search('4xl',$itemName) ||
    			'4XL' === array_search('4XL',$itemName) ||
    			'5xl' === array_search('5xl',$itemName) ||
    			'5XL' === array_search('5XL',$itemName)
    			)

		        @elseif (empty($details->others_color))
		        	<td class="colspan-td" colspan="2">
		        		<table>
		        			@foreach ($itemQtyValue as $sizeValue => $qtyValue)
		        				<tr>
		        					<td width="68.7%">{{$sizeValue}}</td>
	    							<td width="37.3%">{{$qtyValue}}</td>
		        				</tr>
		        			@endforeach
		        		</table>
		        	</td>
    			@else
    			@endif

		        <td>PCS</td>
		    </tbody>

		    <!-- End table tbody Section -->
		</table>
    @endforeach


	<script type="text/javascript">
		function myFunction() {
			$(".print").addClass("hidden");
		    window.print();
		}
	</script>
@endsection
