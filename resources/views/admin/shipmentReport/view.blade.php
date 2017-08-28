@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($tables_value);
error_reporting(0);
?>
 <div id="page-wrapper">
    <div class="graphs">
 <table class="table table-bordered"> 
     <tbody>
      <tr>
         <td style="width: 20%;">PICKUP ADDRESS</td>
         <td>{{$pickup->pickup_address}}</td>
     </tr>  
     <tr>
         <td style="width: 20%;">PICKUP DATE</td>
         <td><?php echo date('d-m-Y',strtotime($pickup->pickup_date)); ?></td>
     </tr> 
     <tr>
         <td style="width: 20%;">DELIVERY ADDRESS</td>
         <td>{{$delivery->delivery_address}}</td>
     </tr>  
     <tr>
         <td style="width: 20%;">DELIVERY DATE</td>
         <td>{{date('d-m-Y',strtotime($delivery->delivery_date))}}</td>
     </tr> 
     <?php for($i=1;$i<count($tables_columns); $i++) {  
        
         $n=str_replace("_id"," ",$tables_columns[$i]);
         $name=str_replace("_"," ",$n);
         if(strtoupper($name)=='STATUS') {
             
         }
      ?>
     <tr>
         <td style="width: 20%;"><?php echo strtoupper($name); ?></td>
         <td>{{$tables_value->$tables_columns[$i]}}</td>
     </tr>
     
     <?php } ?>
     
 </tbody> 
 </table>
    </div>
</div>
@endsection





