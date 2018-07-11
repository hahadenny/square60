@extends('layouts.app')

@section('header')
    {{--<header>
        @include('layouts.header')
    </header>--}}
@endsection

@section('content')
    <div class="mobile-menu" id="mobile-menu">
        @include('layouts.header2_1')        
    </div>

    <div id="app">
        <div>
            @include('partial.header')  
            <section>
                <div class="main-content columns is-mobile is-centered" style="margin-bottom:40px;">
                    
                    <div class="column is-half is-narrow">
                        @if (session('status'))
                        <div class="alert alert-success" style="text-align:center;margin-bottom:30px;">
                            {{ session('status') }}
                        </div>
                        @endif
                        {{--<div id='calendar'></div>--}}
                        <h2 style="font-size:18px;"><b>Open House:</b></h2>
                        @if(isset($listings) && !empty($listings) && count($listings))
                            @foreach($listings as $k=>$value)
                                <br><a href="/show/{{str_replace(' ','_',$value->name)}}/{{$value->id}}">{{$value->full_address}} {{$value->unit}}</a>
                                                {{$value->neighborhood}}, {{$value->state}}<br>
                                <div class="showOpen button mainbgc" style="margin-top:1rem; color:#fff;border:none;">Open House</div>
                                        <div class="show" style="display: none" >
                                            <form action="{{route('saveOpenHouse')}}" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="listingId" class="input" value="{{$value->id}}">
                                                @if(isset($value->OpenHouse) && !$value->OpenHouse->isEmpty())
                                                    @foreach($value->OpenHouse as $key=>$v)
                                                        @if(Carbon\Carbon::now() > $v->end_time)
                                                            @php unset($value->OpenHouse[$key]) @endphp
                                                        @endif
                                                    @endforeach
                                                    @foreach($value->OpenHouse as $key=>$item)
                                                        <div id="openHouse-{{$key+$k}}" class="block">
                                                            <div class="form-content columns is-variable">
                                                                <div class="column" style="padding-bottom:0px;">
                                                                    <label class="label form-content" for="">Open House:</label>
                                                                    <input type="date" name="openHouse[{{$key+$k}}][date]" class="input" value="{{$item->date}}" style="max-width:150px;">
                                                                    <input type="hidden" name="openHouse[{{$key+$k}}][openID]" class="input" value="{{$item->id}}">
                                                                </div>
                                                                <div class="column" style="padding-bottom:0px;">
                                                                    <label class="label form-content" for="">Starts:</label>
                                                                    <div class="select">
                                                                        <select name="openHouse[{{$key+$k}}][start]">
                                                                            @foreach($openHours as $value)
                                                                                <option value="{{$value->hour}}" {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->start_time)->format('H:i') == $value->hour ? 'selected' : ''}}>{{$value->title}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="column" style="padding-bottom:0px;">
                                                                    <label class="label form-content" for="">Ends:</label>
                                                                    <div class="select">
                                                                        <select name="openHouse[{{$key+$k}}][end]">
                                                                            @foreach($openHours as $value)
                                                                                <option value="{{$value->hour}}" {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->end_time)->format('H:i') == $value->hour ? 'selected' : ''}}>{{$value->title}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="column" style="padding-bottom:0px;margin-top:2rem;">
                                                                    <label class="label form-content" for="" style="white-space: nowrap;">
                                                                        <input type="checkbox" name="openHouse[{{$key+$k}}][appointment]" value="1" {{$item->appointment == 1 ? 'checked' : ''}}>&nbsp;By Appointment</label>
                                                                </div>
                                                                <div class="column listremove" style="padding-bottom:0px;">
                                                                    <deleteopenhouse inline-template v-bind:id="'{{$item->id}}'" v-bind:num="'{{$key+$k}}'">
                                                                        <a v-on:click="deleteOpenHouse">Remove</a>
                                                                    </deleteopenhouse>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                    <div class="form-content">
                                                        <a class="more button is-primary" {{$key>=0 ? "data-key=".$key : ''}}>Add an Open House</a>
                                                    </div>
                                                @else

                                                    <div class="form-content">
                                                        <a class="more button is-primary">Add an Open House</a>
                                                    </div>

                                                @endif
                                                <br>
                                                <button type="submit" class="button is-success mainbgc">Save</button>
                                            </form>
                                        </div><br>
                            @endforeach
                        @else
                            <br><br>You do not have open house.
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('footer')
    {{--@include('layouts.footer')--}}
    @include('layouts.footerMain2')
@endsection

@section('additional_scripts')
    <script>

        $('.showOpen').on('click', function(){

            $(this).next('.show').first().toggle();
        });


        var key = '{{isset($key) ? $key+$k : 'undefined'}}';

        if (key === 'undefined' || key === null) {
            var i = 0;
        }else{
            i=parseInt(key)+1;
        }
        console.log(i);

        $('.more').click(function() {
            $(this).before('<div id="openHouse-'+i+'" class="block">' +
                '                     <div class="form-content columns is-variable">' +
                '                         <div class="column">' +
                '                             <label class="label form-content" for="">Open House:</label>\n' +
                '                                 <input type="date" name="openHouse['+i+'][date]" class="input" value="">' +
                '                         </div>' +
                '                         <div class="column">' +
                '                             <label class="label form-content" for="">Starts:</label>\n' +
                '                                 <div class="select">' +
                '                                     <select name="openHouse['+i+'][start]">' +
                '                                          @foreach($openHours as $value)' +
                '                                              <option value="{{$value->hour}}">{{$value->title}}</option>' +
                '                                          @endforeach' +
                '                                     </select>' +
                '                                 </div>' +
                '                         </div>' +
                '                         <div class="column">' +
                '                             <label class="label form-content" for="">Ends:</label>\n' +
                '                                 <div class="select">' +
                '                                     <select name="openHouse['+i+'][end]">' +
                '                                         @foreach($openHours as $value)' +
                '                                            <option value="{{$value->hour}}">{{$value->title}}</option>' +
                '                                         @endforeach' +
                '                                     </select>' +
                '                                 </div>' +
                '                         </div>' +
                '                         <div class="column" style="margin-top:2rem;">' +
                '                             <label class="label form-content" for="" style="white-space: nowrap;">' +
                '                                    <input type="checkbox" name="openHouse['+i+'][appointment]" value="1">&nbsp;By Appointment</label>' +
                '                         </div>' +
                '                         <div class="column" style="margin-top:3rem;">' +
                '                             <a onclick="removeOpenHouse('+i+')">Remove</a>' +
                '                         </div>' +
                '                         </div>' +
                '                         </div>');
            i++;
        });

        function removeOpenHouse(id){
            $("#openHouse-"+id).remove();
        }

        $('#calendar').fullCalendar({

                editable:true,
                defaultTimedEventDuration: '00:30:00',
                minTime: "08:00:00",
                maxTime: "17:00:00",
                slotDuration: '00:30:00',
                slotLabelInterval: 30,
                slotLabelFormat: 'h(:mm)a',
                header:{
                    left:'prev,next today',
                    center:'title',
                    right:'month,agendaDay'
                },
                events: '/loadopenhouse',
                selectable:true,
                selectHelper:true,
                select: function(start, end, allDay, view)
                {
                    if(view.name == 'agendaDay') {

                        var title = prompt("Enter Event Title");
                        if (title) {
                            var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                            var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: "/addopenhouse",
                                type: "POST",
                                data: {title: title, start: start, end: end},
                                success: function (response) {
                                    alert(response);
                                    $('#calendar').fullCalendar('refetchEvents');
                                }
                            })
                        }
                    }
                },
                eventDrop:function(event)
                {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    var title = event.title;
                    var id = event.id;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url:"/updateopenhouse",
                        type:"POST",
                        data:{title:title, start:start, end:end, id:id},
                        success:function(response)
                        {
                            $('#calendar').fullCalendar('refetchEvents');
                            alert(response);
                        }
                    });
                },

                eventClick:function(event)
                {
                    if(confirm("Are you sure you want to remove it?"))
                    {
                        var id = event.id;
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url:"/deleteopenhouse",
                            type:"POST",
                            data:{id:id},
                            success:function(response)
                            {
                                $('#calendar').fullCalendar( 'refetchEvents' );
                                alert(response);
                            }
                        })
                    }
                },
            dayClick: function(date, jsEvent, view) {

                if(view.name == 'month') {
                    $('#calendar').fullCalendar('changeView', 'agendaDay');
                    $('#calendar').fullCalendar('gotoDate', date);
                }

            },
        });


    </script>
@endsection