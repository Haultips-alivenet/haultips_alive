@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //echo'<pre>'; print_r($commanData);print_r($data); echo count($data);die;
error_reporting(0);
?>
 <div id="page-wrapper">
     <input type="button" value="Back" onclick="history.back()">
    <div class="graphs">
 <table class="table table-bordered"> 
     <tbody>
      <tr>
         <td style="width: 20%;">Name</td>
         <td>{{$commanData["userName"]}}</td>
     </tr>  
    
     <tr>
         <td style="width: 20%;">DELIVERY TITLE</td>
         <td>{{$commanData["deliveryTitle"]}}</td>
     </tr> 
     <tr>
         <td style="width: 20%;">CATEGORY</td>
         <td>{{$commanData["category"]}}</td>
     </tr> 
     <tr>
         <td style="width: 20%;">SUB CATEGORY</td>
         <td>{{$commanData["subCategory"]}}</td>
     </tr> 
     <tr>
         <td style="width: 20%;">PICKUP ADDRESS</td>
         <td>{{$commanData["pickupAddress"]}}</td>
     </tr> 
      <tr>
         <td style="width: 20%;">PICKUP DATE</td>
         <td><?php echo date('d-M-Y',strtotime($commanData["pickupDate"])); ?></td>
     </tr>
      <tr>
         <td style="width: 20%;">DELIVERY ADDRESS</td>
         <td>{{$commanData["deliveryAddress"]}}</td>
     </tr> 
     
     <tr>
         <td style="width: 20%;">DELIVERY DATE</td>
         <td>{{date('d-M-Y',strtotime($commanData["deliveryDate"]))}}</td>
     </tr> 
     <tr>
         <td style="width: 20%;">DISTANCE</td>
         <td>{{$commanData["distance"]}}</td>
     </tr>
     <?php foreach($data as $key=>$value) { $key=str_replace("_"," ",$key); $key = strtoupper($key);
    
     ?>
      <tr>
         <td style="width: 20%;">{{$key}}</td>
         <?php if($key=="ITEMIMAGE") { $img=explode(",",$value);   ?>
         <td> <?php if($value) { for($k=0;$k<count($img);$k++) { ?><img src="{{asset('public/uploads/userimages/'.$img[$k])}}"  width='50' height='30'/> <?php } } ?></td>
         <?php  } else { ?>
          <td>{{$value}}</td>
         <?php } ?>
     </tr>
     <?php } ?>
 </tbody> 
 </table>
    </div>
</div>
@endsection





