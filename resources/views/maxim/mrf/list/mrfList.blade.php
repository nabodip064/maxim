@extends('layouts.dashboard')
@section('page_heading', trans("others.mxp_menu_mrf_list") )
@section('section')

@section('section')
	<button class="btn btn-warning" type="button" id="mrf_reset_btn">Reset</button>
	<div id="mrf_simple_search_form">
		<div class="form-group custom-search-form col-sm-9 col-sm-offset-2">
			<input type="text" name="mrfIdSearchFld" class="form-control" placeholder="MRF Id search" id="mrf_id_search">
			<button class="btn btn-info" type="button" id="mrf_simple_search">
				Search{{--<i class="fa fa-search"></i>--}}
			</button>
		</div>
		<button class="btn btn-primary " type="button" id="mrf_advanc_search">Advance Search</button>
	</div>
	<div>
		<form id="mrf_advance_search_form"  style="display: none" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="col-sm-2">
				Booking Id
				<input type="text" name="booking_id_search" class="form-control" placeholder="Booking Id search" id="booking_id_search">
			</div>
			<div class="col-sm-10">
				<div class="col-sm-10">
					<div class="col-sm-3">
						Create date from
						<input type="date" name="from_create_date_search" class="form-control" id="from_create_date_search">
					</div>
					<div class="col-sm-3">
						Create date to
						<input type="date" name="to_create_date_search" class="form-control" id="to_create_date_search">
					</div>
					<div class="col-sm-3">
						Shipment date from
						<input type="date" name="from_shipment_date_search" class="form-control" id="from_shipment_date_search">
					</div>
					<div class="col-sm-3">
						Shipment date to
						<input type="date" name="to_shipment_date_search" class="form-control" id="to_shipment_date_search">
					</div>
				</div>
				<br>
				<div class="col-sm-2">
					<input class="btn btn-info" type="submit" value="Search" name="mrf_advanceSearch_btn" id="mrf_advanceSearch_btn">
				</div>
			</div>
			<button class="btn btn-primary" type="button" id="mrf_simple_search_btn">Simple Search</button>
		</form>
	</div>
	<br>
	<div class="row">
		<div class="col-md-12 col-md-offset-0">
			<table class="table table-bordered">
				<tr>
					<thead>
					<th>Serial no</th>
					<th>booking id</th>
					<th>MRF Id</th>
					<th>MRF Create Date</th>
					<th>MRF Shipment Date</th>
					<th>Action</th>
					</thead>
				</tr>
				@php($j=1)
				<tbody id="mrf_list_tbody">
				@foreach($bookingList as $value)
					<tr id="mrf_list_table">
						<td>{{$j++}}</td>
						<td>{{$value->booking_order_id}}</td>
						<td>{{$value->mrf_id}}</td>
						<td>{{Carbon\Carbon::parse($value->created_at)}}</td>
						<td>{{$value->shipmentDate}}</td>
						<td>
							<form action="{{Route('mrf_list_action_task') }}" role="form" target="_blank">
								<input type="hidden" name="mid" value="{{$value->mrf_id}}">
								<input type="hidden" name="bid" value="{{$value->booking_order_id}}">
								<button class="btn btn-success" target="_blank">View</button>
							</form>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div id="mrf_list_pagination">{{$bookingList->links()}}</div>
			<div class="pagination-container">
				<nav>
					<ul class="pagination"></ul>
				</nav>
			</div>
		</div>
	</div>
@endsection