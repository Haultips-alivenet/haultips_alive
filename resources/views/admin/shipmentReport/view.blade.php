@extends('layouts.admin-layout')
@section('title')
    Admin Panel | Haultips
@endsection

@section('body')
<?php //print_r($tables_value);
error_reporting(0);
?>
 <div id="page-wrapper">
     <input type="button" value="Back" onclick="history.back()">
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
        
         $c_name=$tables_columns[$i];
         $n=str_replace("_id"," ",$tables_columns[$i]);
         $name=str_replace("_"," ",$n);
         if(strtoupper($name)=='STATUS') {
             if($tables_value->$tables_columns[$i]==0){ $tables_value->$tables_columns[$i]="Inactive"; } else if($tables_value->$tables_columns[$i]==1) { $tables_value->$tables_columns[$i]="Active"; } 
        }
        
        if($table_name == 'shipment_listing_homes'){
            if($n=='dining_room') {
               $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getDineInData($tables_value->$tables_columns[$i], 'admin_dinning_rooms');
            } else
            if($n=='living_room') {
               $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getDineInData($tables_value->$tables_columns[$i], 'admin_living_rooms');
            } else
            if($n=='kitchen') {
               $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getDineInData($tables_value->$tables_columns[$i], 'admin_kitchens');
            } else
             if($n=='home_office') {
               $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getDineInData($tables_value->$tables_columns[$i], 'admin_home_offices');
            }  else
             if($n=='garage') {
               $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getDineInData($tables_value->$tables_columns[$i], 'admin_garages');
            }  else
             if($n=='outdoor') {
               $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getDineInData($tables_value->$tables_columns[$i], 'admin_outdoors');
            }  else
             if($n=='miscellaneous') {
               $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getDineInData($tables_value->$tables_columns[$i], 'admin_miscellaneouses');
            }
        } else if($table_name == 'shipment_listing_offices'){
             
            if($n=='lift_elevator') {
               $tables_value->$tables_columns[$i] = ($tables_value->$tables_columns[$i] == 0) ? 'No' : 'Yes';
            } else if($n=='general_shipment_inventory') {
                $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getDineInData($tables_value->$tables_columns[$i], 'admin_general_shipments'); 
            } else if($n=='equipment_shipment_inventory') {
                $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getDineInData($tables_value->$tables_columns[$i], 'admin_equipments'); 
            } else if($n=='boxes') {
                $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getDineInData($tables_value->$tables_columns[$i], 'admin_boxes'); 
            }
        }  else if($table_name == 'shipment_listing_truck_bookings'){
            
            if($c_name=='truck_type_id') {
                $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getCategoryName($tables_value->$tables_columns[$i], 'id', 'category_name','vehicle_categories'); 
            } else   if($c_name=='truck_length_id') {
                 $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getCategoryName($tables_value->$tables_columns[$i], 'id', 'truck_length','truck_lengths'); 
            } else   if($c_name=='truck_capacity_id') {
                 $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getCategoryName($tables_value->$tables_columns[$i],  'id', 'truck_capacity','truck_capacities'); 
            } else   if($c_name=='material_id') {
                 $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getCategoryName($tables_value->$tables_columns[$i], 'id', 'name','materials'); 
            }
        }  else if($table_name == 'shipment_listing_materials'){
            if($c_name=='material_id') {
                 $tables_value->$tables_columns[$i] = (empty($tables_value->$tables_columns[$i])) ? 'N/A' : App\ShippingDetail::getCategoryName($tables_value->$tables_columns[$i], 'id', 'name','materials'); 
            }
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





