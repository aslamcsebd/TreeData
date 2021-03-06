@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="container-fluid">
   <div class="row justify-content-center">

      {{-- Left side --}}
      <div class="col-md-8">
            <fieldset>
               <legend>Tree title list</legend>
               <table class="table table-bordered mt-2">
                  <thead>
                     <th>Sl</th>
                     <th>Tree title</th>
                     <th>Action</th>
                  </thead>
                  <tbody>
                     @foreach($tree_title as $title)
                        <tr>
                           <td width="10">{{$title->id}}</td>
                           <td style="text-transform: capitalize;">{{$title->title_name}}</td>
                           <td width="20">
                              @if($tree_title->count()==$loop->iteration)
                                 <div class="btn-group" role="group" aria-label="Basic example">
                                     <a class="btn btn-sm btn-danger py-0" onclick="return confirm('Are you want to delete this?')" href="{{ url('deleteTreeTitle', $title->id)}}">Delete</a>
                                 </div>
                              @else

                              @endif
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
            </fieldset>

            <fieldset>
               <legend>Tree data</legend>
               <table class="table table-bordered">
                  <thead>
                     <th>Sl</th>
                     <th>Parent_id [Parent name]</th>
                     <th>name</th>
                     <th>Action</th>
                  </thead>
                  <tbody>
                     @foreach($tree_data as $data)
                        <tr>
                           <td width="10">{{$loop->iteration}}</td>
                           <td>{{($data->parent_id==null) ? 'Parent':$data->parent_id}}</td>
                           <td>{{$data->data_name}}</td>
                           <td width="10">
                              <div class="btn-group">
                                 <a class="btn btn-sm btn-outline-info py-0 disabled" href="{{ url('admin/viewPendingFaq', $data->ownerId)}}">View</a>
                                <a class="btn btn-sm btn-danger py-0" onclick="return confirm('Are you want to delete this?')" href="{{ url('deleteTreeData', $data->id)}}">Delete</a>
                              </div>
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
            </fieldset>
      </div>

      {{-- Right side --}}
      <div class="col-4">
         <fieldset>
            <legend>Tree title</legend>
               <form action="{{url('addTitle')}}" method="post">
                  @csrf
                  <div class="row">
                     <input type="text" class="form-control offset-1 col-7" name="title_name" placeholder="Title name" >
                     <button type="submit" name="addTitle" class="btn btn-sm btn-primary offset-1 col-flux">Add title</button>
                  </div>
               </form> 
         </fieldset>

         <fieldset>
            <legend>Add parent data</legend>
               <form action="{{url('addParent')}}" method="post">
                  @csrf
                  <div class="row">
                     <input type="text" class="form-control offset-1 col-7" name="parent" placeholder="Add Parent data">
                     <button type="submit" name="addTitle" class="btn btn-sm btn-success offset-1 col-flux">Add parent</button>
                  </div>
               </form> 
         </fieldset>

         <fieldset class="hide">
            <legend>Tree data</legend>
               <form action="" method="post">
                  @csrf
                  <div class="row">                 
                     <select class="bg-susscss select-country form-control offset-1 col-7" id="countryid" name="countryid" aria-label="Select country">
                        <option selected>Select parent</option>
                           @foreach ($tree_title as $country)
                              <option value="{{$country->id}}">{{$country->title_name}}</option>
                           @endforeach
                     </select>
                  </div>
                  <div class="row mt-2">
                     <input type="text" class="form-control offset-1 col-7" name="parent" placeholder="Add Parent data">
                     <button type="submit" name="addTreeData" class="btn btn-sm btn-primary offset-1 col-flux">Add data</button>
                  </div>
               </form>
         </fieldset>

         @if($tree_data_parents->count()>0)
            <fieldset>
               <legend>Tree data</legend>
                  <form action="{{url('addData')}}" method="post">
                     @csrf
                     @foreach($tree_title as $key => $title)
                        @if($key==0)
                           <label class="mt-2 mb-1 title_name">{{$title->title_name}}</label>
                           <select data-id="{{$key+1}}" id="{{$title->title_name}}" name="{{$title->title_name}}_id" class="form-control toCheck">
                              <option selected>Select {{$title->title_name}}</option>
                              @foreach($tree_data_parents as $data)
                                <option value="{{$data->id}}">{{$data->data_name}}</option>
                              @endforeach
                           </select>
                        @else
                           <style type="text/css">
                              .title_name{ font-weight: bold; text-transform: capitalize;}
                              #{{$title->title_name.$key}},
                              .{{$title->title_name.$key}}_left,
                              .{{$title->title_name.$key}}_right,
                              .fieldAlert{display:none;}
                           </style>
                           
                           <div id="{{$title->title_name.$key}}">                       
                              <label class="mt-2 mb-1 title_name">{{$title->title_name}}</label>
                              <div class="row">
                                 <div class="col {{$title->title_name.$key}}_left">
                                    <input type="text" class="form-control disabled" name="parent" placeholder="Add new {{$title->title_name}}">
                                 </div>
                                 <div class="col {{$title->title_name.$key}}_right">
                                    <select data-id="{{$key+1}}" id="{{$title->title_name}}" name="{{$title->title_name}}_id" class="form-control toCheck"></select>
                                 </div>
                              </div>                        
                           </div>
                        @endif
                     @endforeach
                     <div class="text-center p-2">
                        <span class="alert-danger fieldAlert">
                           please add title field
                        </span>                 
                     </div>
                     <button type="submit" class="mt-2 btn btn-success btn-block">Add data</button>
                  </form>
            </fieldset>
         @endif
      </div>

   </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
   $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
</script>

@php
   $nextTitle = array_column($tree_title->toArray(), 'title_name');
   $titleCount = $tree_title->count();
@endphp
   @if($tree_title->count()>1)
      @foreach($tree_title as $key => $title)
         @php 
            // echo $key;
            unset($nextTitle[$key]);
            foreach ($nextTitle as $k => $v){
               $childTitle = $v;
               break;
            }
            // echo "-Previous:=>".$title->title_name. "----Next:=> ".$childTitle."<br>";
         @endphp
         <script type="text/javascript">             

            $(document).ready(function(){

               var titleId;
             
               $('#{{$title->title_name}}').on('change',function(e){
                 
                  titleId = $(this).attr("data-id");
                  var {{$title->title_name}}_id = e.target.value;

                  $.ajax({
                     url:"{{ url($childTitle) }}",
                     type:"POST",
                     data: {
                        _token: "{{ csrf_token() }}",
                        parent_id: {{$title->title_name}}_id,
                        titleCount: {{$titleCount}},
                        titleId: titleId
                     },
                     success:function (data){
                        @foreach($nextTitle as $k2 => $v2)
                           $('#{{$v2.$k2}}').css('display', 'none');
                           $('#{{$v2}}').empty();
                        @endforeach

                        console.log(data);                 
                        
                        if( {{$tree_title->count()}} == titleId){
                           $('.fieldAlert').css('display', 'block');
                        }else{
                           $('.fieldAlert').css('display', 'none');
                        }
                        
                        if (data.totalChild==0){
                           $('#{{$childTitle.$k}}, .{{$childTitle.$k}}_left').css('display', 'block');
                           $('.{{$childTitle.$k}}_right').css('display', 'none');

                        }else{

                           $('#{{$childTitle.$k}}, .{{$childTitle.$k}}_left, .{{$childTitle.$k}}_right').css('display', 'block');                        
                           $('#{{$childTitle}}').html('');

                           $('#{{$childTitle}}').append('<option selected>Select {{$childTitle}}</option>');
                           $.each(data.{{$childTitle}},function(index,{{$childTitle}}){
                              $('#{{$childTitle}}').append('<option value="'+{{$childTitle}}.id+'">'+{{$childTitle}}.data_name+'</option>');
                           })
                        }

                     }
                  })
               });
            });
         </script>
      @endforeach
   @endif
@endsection
