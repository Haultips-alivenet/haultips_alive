@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<div id="page-wrapper">
    <div class="graphs">
 <table class="table table-bordered"> 
     <tr>
         <td style="width: 20%;">Name</td>
         <td>{{$user->first_name.' '.$user->last_name}}</td>
     </tr>
     <tr>
         <td style="width: 20%;">Email</td>
         <td>{{$user->email}}</td>
     </tr> 
     <tr>
         <td style="width: 20%;">Mobile</td>
         <td>{{$user->mobile_number}}</td>
     </tr> 
     <tr>
         <td style="width: 20%;">Status</td>
         <td>{{($user->status=='1')?'Active' : 'Inactive'}}</td>
     </tr> 
     <tr>
         <td style="width: 20%;">Registered Date</td>
         <td>{{$user->created_at}}</td>
     </tr> 
     
 </tbody> 
 </table>
    </div>
</div>
@endsection
