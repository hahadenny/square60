@extends('layouts.app2')

@section('header')
@endsection

@section('content')
	<div class="mobile-menu" id="mobile-menu">
		@include('layouts.header2_1')        
    </div>
    <div class="wrapper">
        <div class="content">
			@include('partial.header')  
            <div class="container">
				<div class="search-menu is-clearfix">
					<div id="app" class="search-form pull-right">
						@include('partial.search')
					</div>
				</div>

                <div class="filter-page">
					<form id="mainFrom" method="post" action="/search">
                        <ul class="filter-nav">          
                            <li>
                                <a href="/sales">Sales</a>
							</li>
							<li>
                                <span>Rentals</span>
                            </li>
						</ul>						
                        <div class="tabs">
                            <ul class="tabs-header">
								@foreach ( $districts as $k => $district)
                                <li>
									{{-- <span data-tab="tab1" class="active">Manhattan</span> --}}
									<span data-tab="tab{{$district->filter_data_id}}" id="district_{{$district->filter_data_id}}" class="s_city @if($district->filter_data_id==1) active @endif" value="{{$district->filter_data_id}}" onclick="showSubDistricts({{$district->filter_data_id}})">{{$district->value}}</span>
								</li>
								@endforeach
                            </ul>
                            <div class="tabs-body">
                                <div class="tabs-body-wr">
								    @foreach ( $districts as $district)
                                    <div class="tab @if($district->filter_data_id == 1) active @endif" id="tab{{$district->filter_data_id}}">
                                        <div class="tab-ttl" data-tab="district_{{$district->filter_data_id}}">{{$district->value}}</div>
                                        <ul class="col col-full-width">
                                            <li>
                                                <!-- !!! warning !!!-->
                                                <!-- for each checkbox  id === for -->
                                                <!-- !!! -->
                                                <input type="checkbox" class="checkbox" id="all_{{$district->filter_data_id}}" onchange="">
												<label for="all_{{$district->filter_data_id}}" class="subdistricts" data-parent="{{$district->filter_data_id}}">All {{$district->value}}</label>
												@if($district->value == 'Manhattan')
                                                <ul class="row">
                                                    <li class="col">
                                                        <!-- !!! warning -->
                                                        <!-- it is hidden checkbox for js script  -->
                                                        <!-- !!! don't remove -->
                                                        <!--  -->
                                                        <input type="checkbox" class="checkbox hidden">
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" id="{{$district->subdistritcs[1][0]->filter_data_id}}" data-id="{{$district->subdistritcs[1][0]->filter_data_id}}" class="parent checkbox no-mobile" onchange="" value="{{$district->subdistritcs[1][0]->filter_data_id}}"  name='districts[]'>
                                                                <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$district->subdistritcs[1][0]->filter_data_id}}">{{$district->subdistritcs[1][0]->value}}</label>
                                                                <div class="mobile-ttl">{{$district->subdistritcs[1][0]->value}}</div>
                                                                <ul>
																@foreach ($district->subdistritcs as $subdistritc)
																	@foreach ($subdistritc as $k=>$sub)
																		@if($sub->district_id == 1 && $k>0)
                                                                    <li>
																		<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
                                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																	</li>
																		@endif
																	@endforeach
																@endforeach
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="col">
                                                        <!-- !!! warning -->
                                                        <!-- it is hidden checkbox for js script  -->
                                                        <!-- !!! don't remove -->
                                                        <!--  -->
                                                        <input type="checkbox" class="checkbox hidden">
                                                        <ul>
                                                            <li>
																<input type="checkbox" id="{{$district->subdistritcs[2][0]->filter_data_id}}" data-id="{{$district->subdistritcs[2][0]->filter_data_id}}" class="parent checkbox no-mobile" onchange="" value="{{$district->subdistritcs[2][0]->filter_data_id}}"  name='districts[]'>
                                                                <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$district->subdistritcs[2][0]->filter_data_id}}">{{$district->subdistritcs[2][0]->value}}</label>
                                                                <div class="mobile-ttl">{{$district->subdistritcs[2][0]->value}}</div>
                                                                <ul>
																@foreach ($district->subdistritcs as $subdistritc)
																	@foreach ($subdistritc as $k=>$sub)
																		@if($sub->district_id == 2 && $k>0)
																			@if (in_array($sub->sub_district_id, array('2', '5')))
																	<li>
																		<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
                                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																		<ul style="margin-left:18px;">
																			@elseif (in_array($sub->sub_district_id, array('4', '7')))
																		</ul>
																	<li>
																		<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
																		<label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																	</li>															
																			@else
                                                                    <li>
																		<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
                                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																	</li>
																			@endif
																		@endif
																	@endforeach
																@endforeach
                                                                </ul>
                                                            </li>
                                                        </ul>

                                                    </li>
                                                    <li class="col">
                                                        <!-- !!! warning -->
                                                        <!-- it is hidden checkbox for js script  -->
                                                        <!-- !!! don't remove -->
                                                        <!--  -->
                                                        <input type="checkbox" class="checkbox hidden">
                                                        <ul>
                                                            <li>
																<input type="checkbox" id="{{$district->subdistritcs[3][0]->filter_data_id}}" data-id="{{$district->subdistritcs[3][0]->filter_data_id}}" class="parent checkbox no-mobile" onchange="" value="{{$district->subdistritcs[3][0]->filter_data_id}}"  name='districts[]'>
                                                                <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$district->subdistritcs[3][0]->filter_data_id}}">{{$district->subdistritcs[3][0]->value}}</label>
                                                                <div class="mobile-ttl">{{$district->subdistritcs[3][0]->value}}</div>
                                                                <ul style="margin-left:18px;">
																@foreach ($district->subdistritcs as $subdistritc)
																	@foreach ($subdistritc as $k=>$sub)
																		@if($sub->district_id == 3 && $k>0)
                                                                    <li>
																		<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
                                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																	</li>
																		@endif
																	@endforeach
																@endforeach
                                                                </ul>
                                                            </li>
                                                            <li>
																<input type="checkbox" id="{{$district->subdistritcs[4][0]->filter_data_id}}" data-id="{{$district->subdistritcs[4][0]->filter_data_id}}" class="parent checkbox no-mobile" onchange="" value="{{$district->subdistritcs[4][0]->filter_data_id}}"  name='districts[]'>
                                                                <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$district->subdistritcs[4][0]->filter_data_id}}">{{$district->subdistritcs[4][0]->value}}</label>
                                                                <div class="mobile-ttl">{{$district->subdistritcs[4][0]->value}}</div>
                                                                <ul style="margin-left:18px;">
																@foreach ($district->subdistritcs as $subdistritc)
																	@foreach ($subdistritc as $k=>$sub)
																		@if($sub->district_id == 4 && $k>0)
                                                                    <li>
																		<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
                                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																	</li>
																		@endif
																	@endforeach
																@endforeach
                                                                </ul>
                                                            </li>
                                                            <li>
																<input type="checkbox" id="{{$district->subdistritcs[5][0]->filter_data_id}}" data-id="{{$district->subdistritcs[5][0]->filter_data_id}}" class="parent checkbox no-mobile" onchange="" value="{{$district->subdistritcs[5][0]->filter_data_id}}"  name='districts[]'>
                                                                <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$district->subdistritcs[5][0]->filter_data_id}}">{{$district->subdistritcs[5][0]->value}}</label>
                                                                <div class="mobile-ttl">{{$district->subdistritcs[5][0]->value}}</div>
                                                                <ul style="margin-left:18px;">
																@foreach ($district->subdistritcs as $subdistritc)
																	@foreach ($subdistritc as $k=>$sub)
																		@if($sub->district_id == 5 && $k>0)
                                                                    <li>
																		<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
                                                                        <label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																	</li>
																		@endif
																	@endforeach
																@endforeach
                                                                </ul>
                                                            </li>
                                                        </ul>

                                                    </li>
                                                </ul>
                                            </li>
										</ul>
										@else
										<ul class="row">
											<li class="col">
												<!-- !!! warning -->
												<!-- it is hidden checkbox for js script  -->
												<!-- !!! don't remove -->
												<!--  -->
												<input type="checkbox" class="checkbox hidden">												
												<ul>
													<li>
														<input type="checkbox" class="checkbox hidden">	
														<ul>			
														@foreach ($district->subdistritcs as $sub)
															@if($sub->left)
																@if ($sub->mainboro && isset($subBorougths[$sub->district_id]))
																<li>
																	<strong>
																		<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
																		<label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																	</strong>
																</li>
																	@foreach($subBorougths[$sub->district_id] as $subBoro)
																	<li>
																		<input type='checkbox' id="{{$subBoro->filter_data_id}}" data-id="{{$subBoro->filter_data_id}}" class="checkbox parent parent-{{$subBoro->district_id}}" onchange="" value="{{$subBoro->filter_data_id}}"  name='districts[]'>
																		<label class="subdistricts sub-child parent-{{$subBoro->district_id}}" data-parent="{{$district->filter_data_id}}" for="{{$subBoro->filter_data_id}}">{{$subBoro->value}}</label>
																	</li>
																	@endforeach
																@else
																<li>
																	<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
																	<label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																</li>																	
																@endif
															@endif
														@endforeach
														</ul>
													</li>
												</ul>
											</li>
											<li class="col">
												<!-- !!! warning -->
												<!-- it is hidden checkbox for js script  -->
												<!-- !!! don't remove -->
												<!--  -->
												<input type="checkbox" class="checkbox hidden">												
												<ul>
													<li>
														<input type="checkbox" class="checkbox hidden">	
														<ul>			
														@foreach ($district->subdistritcs as $sub)
															@if($sub->center)
																@if ($sub->mainboro && isset($subBorougths[$sub->district_id]))
																<li>
																	<strong>
																		<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
																		<label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																	</strong>
																</li>
																	@foreach($subBorougths[$sub->district_id] as $subBoro)
																	<li>
																		<input type='checkbox' id="{{$subBoro->filter_data_id}}" data-id="{{$subBoro->filter_data_id}}" class="checkbox parent parent-{{$subBoro->district_id}}" onchange="" value="{{$subBoro->filter_data_id}}"  name='districts[]'>
																		<label class="subdistricts sub-child parent-{{$subBoro->district_id}}" data-parent="{{$district->filter_data_id}}" for="{{$subBoro->filter_data_id}}">{{$subBoro->value}}</label>
																	</li>
																	@endforeach
																@else
																<li>
																	<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
																	<label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																</li>																	
																@endif
															@endif
														@endforeach
														</ul>
													</li>
												</ul>
											</li>
											<li class="col">
												<!-- !!! warning -->
												<!-- it is hidden checkbox for js script  -->
												<!-- !!! don't remove -->
												<!--  -->
												<input type="checkbox" class="checkbox hidden">												
												<ul>
													<li>
														<input type="checkbox" class="checkbox hidden">	
														<ul>			
														@foreach ($district->subdistritcs as $sub)
															@if($sub->rigth)
																@if ($sub->mainboro && isset($subBorougths[$sub->district_id]))
																<li>
																	<strong>
																		<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
																		<label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																	</strong>
																</li>
																	@foreach($subBorougths[$sub->district_id] as $subBoro)
																	<li>
																		<input type='checkbox' id="{{$subBoro->filter_data_id}}" data-id="{{$subBoro->filter_data_id}}" class="checkbox parent parent-{{$subBoro->district_id}}" onchange="" value="{{$subBoro->filter_data_id}}"  name='districts[]'>
																		<label class="subdistricts sub-child parent-{{$subBoro->district_id}}" data-parent="{{$district->filter_data_id}}" for="{{$subBoro->filter_data_id}}">{{$subBoro->value}}</label>
																	</li>
																	@endforeach
																@else
																<li>
																	<input type='checkbox' id="{{$sub->filter_data_id}}" data-id="{{$sub->filter_data_id}}" class="checkbox parent" onchange="" value="{{$sub->filter_data_id}}"  name='districts[]'>
																	<label class="subdistricts" data-parent="{{$district->filter_data_id}}" for="{{$sub->filter_data_id}}">{{$sub->value}}</label>
																</li>																	
																@endif
															@endif
														@endforeach
														</ul>
													</li>
												</ul>
											</li>
										</ul>
										@endif

									</div>
									@endforeach
                                </div>
                                <div class="filter-box">
                                    <div class="filter-box-content">
                                        <div class="line-wr">
                                            <div class="filter-item line">
                                                <div class="ttl">Bed:</div>
                                                <select name="beds[]" id="bedFor" class="custom from">
													<option value="0" selected="selected">Studio</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
                                                </select>
                                                <span class="to-delimiter">to</span>
                                                <select name="beds[]" id="bedTO" class="custom to">
													<option value="9999" selected="selected">Any</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
                                                </select>
                                            </div>
                                            <div class="filter-item line">
                                                <div class="ttl">Bath:</div>
                                                <select name="baths[]" id="bathFor" class="custom from">
													{{--<option value="0" selected="selected">0</option>--}}
													<option value="1" selected="selected">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
                                                </select>
                                                <span class="to-delimiter">to</span>
                                                <select name="baths[]" id="bathTo" class="custom to">
													<option value="9999" selected="selected">Any</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
                                                </select>
                                            </div>

                                            <div class="filter-item line">
                                                <div class="ttl">Rent:</div>
                                                <input id="priceFor" placeholder="min" type="text" class="select is-primary custom-input" name="price[]" value="">
                                                <span class="to-delimiter">to</span>
                                                <input id="priceTo" placeholder="max" type="text" class="select is-primary custom-input" name="price[]" value="">
                                            </div>
                                        </div>
                                        <div class="filter-item services">
                                            <ul class="list">
												<li>
                                                    <input type="checkbox" name="filters[]" class="small-checkbox" id="354" value="354">
                                                    <label style="white-space: nowrap;" for="354">No Fee</label>
												</li>

												@php $i=0; @endphp
												@foreach ($filters as $k=>$filter)
												@php $i++; @endphp
												
                                                <li style="@if($i > 2) display:none; @endif">
                                                    <input type="checkbox" name="filters[]" class="small-checkbox" id="{{$filter->filter_data_id}}" value="{{$filter->filter_data_id}}">
                                                    <label style="white-space: nowrap;" for="{{$filter->filter_data_id}}">{{ucwords($filter->value)}}</label>
												</li>
												@endforeach												
                                            </ul>
                                            <span class="more-btn">
                                                <span>More filters</span>
                                            </span>
										</div>
										<input id="real_estate_type" type="hidden" value="2" name="estate_type">
										{{csrf_field()}}
                                        <button id="search_main" class="btn"><div style="width:100%;text-align:center;">Search</div></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
	</div>
@endsection
        
@section('footer')
	@include('layouts.footerMain2')
@endsection        

@section('additional_scripts')
	@include('layouts.commonjs')
@endsection