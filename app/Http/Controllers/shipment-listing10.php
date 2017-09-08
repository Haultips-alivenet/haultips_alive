<?php $this->load->view('header') ?>

<div class="clearfix"></div>

<section class="login_bg">
<div class="headering_tab">
<div class="container">
<div class="row">
   <h2 class="title text-center">HAULTIPS smart transportation tool. Book and find custom as easy as 1-2-3</h2>
 </div>
 </div>
   </div>
</section>

<section class="inner_pages">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-12 col-md-12	-sm-12 col-xs-12">
            	<div class="fullwith">
                	<div class="title-strip"></div>
                    
                    <form class="form-horizontal categryy" name="form" method="post" action="<?php echo base_url().'shipping/newshipment/'?>shipping_generate_shipment_listing10" enctype="multipart/form-data">
                <h4>HAULTIPS for Carriers</h4>
               
                    <hr>
                   
                        
                         <div class="form-group">
                        <div class="col-md-3 col-sm-3 col-lg-3"><label>Collection Floor:</label></div>
                       <div class="col-md-5 col-sm-5 col-lg-5">
                       <select name="collection_floor" class="online-item" required>
                           <option value="House"> House </option>
                           <option value="Flat/Apartment"> Flat/Apartment </option>
                           <option value="Condo"> Condo </option>
                        </select>
                       </div>
                        </div>
                        
                        
                        
                        <div class="form-group">
                        <div class="col-md-3 col-sm-3 col-lg-3"><label>Delivery Floor:</label></div>
                       <div class="col-md-5 col-sm-5 col-lg-5">
                       <select name="delivery_floor" class="online-item" required>
                       		<option value=""> -Select- </option>
                           <option value="1"> 0 </option>
                           <option value="2"> 1 </option>
                           <option value="3"> 2+ </option>
                        </select>
                       </div>
                        </div>
                        
                        <div class="form-group">
                        <div class="col-md-3 col-sm-3 col-lg-3"><label>Lift/Elevator</label></div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                        <input type="radio" name="lift_elevator" value="1" onclick="elevator_service(this.value)"> <label> Yes</label>
                        <input type="radio" name="lift_elevator" value="0" checked onclick="elevator_service(this.value)"> <label> No</label>
						</div>
                        </div>

                        <div class="form-group" style="display:none" id="service-lift-elevator">
                        <div class="col-md-3 col-sm-3 col-lg-3"><label>Is it a service lift/elevator?</label></div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                        <input type="radio" name="service_elevator" value="1"> <label> Yes</label>
                        <input type="radio" name="service_elevator" value="0" checked> <label> No</label>
						</div>
                        </div>
                        
                        <div class="form-group">
                        <div class="col-md-3 col-sm-3 col-lg-3"><label>Will cubicles or other items need to be disassembled?</label></div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                        <input type="radio" name="cubicles_disassembled" value="1"> <label> Yes</label>
                        <input type="radio" name="cubicles_disassembled" value="0" checked> <label> No</label>
						</div>
                        </div>
                        
                        <div class="form-group">
                        <div class="col-md-3 col-sm-3 col-lg-3"><label>Will cubicles or other items need to be re-assembled?</label></div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                        <input type="radio" name="cubicles_assembled" value="1"> <label> Yes</label>
                        <input type="radio" name="cubicles_assembled" value="0" checked> <label> No</label>
						</div>
                        </div>
                        
                        
                        <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-lg-3">Delivery Title:</label>
                       <div class="col-md-5 col-sm-5 col-lg-5">
                       <input type="text" required="" name="delivery_title" id="delivery_title" value="" class="form-control-delevery-title form-control">
                       <span> (e.g., Office Removal)</span>
                       <small>This will become the title of your listing, so be as descriptive as possible. You do not have to include collection or delivery information here.</small>
                        </div>
                        </div>
                        <div class="form-group">
                       
                       <div class="col-md-12 col-sm-12 col-lg-12">
                       <strong>Get the best bids by filling out the information below.</strong>
                       	
                        <div class="col-md-12 col-sm-12 col-lg-12">
                        <label>
                        <a href="javascript:void()" onclick="set_toggle(1)" class="pull-right testt-show"><i class="fa fa-plus-square"></i>  General:</a>
                        </label>
                        </div>
                        <div class="test-dwn1" style="display:none;">
                       
                        
                        <ul class="moveproc-form">
                       <?php foreach ($general as $generals) { ?>

                        <li class="col-sm-4">
                        <span class="item-label"><?php echo $generals['name'] ?></span>
                        <div class="qty-box">
                        <input class="input-text" type="text" name="general[]" maxlength="2" onkeypress="return isNumber(event)" id="general<?php echo  $generals['id'] ?>">
                        <div class="qty-arrows">
                        <input class="box" value="+" type="button" onclick="addnumber('general<?php echo  $generals['id'] ?>')">
                        <input class="box" value="-" type="button" onclick="subsnumber('general<?php echo  $generals['id'] ?>')">
                        </div>
                        </div>  
                        </li>

                        <?php  } ?>  

                        </ul>
                       
                        </div>
                       </div>
                        </div>
                        
                        <div class="form-group">                       
                       <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                        <label><a href="javascript:void()" onclick="set_toggle(2)" class="pull-right testt-show"><i class="fa fa-plus-square"></i> Equipment:</a></label></div>
                        
                        <div class="test-dwn2" style="display:none;">
                       
                       
                        <ul class="moveproc-form">
                       <?php foreach ($equipment as $equipments) { ?>

                        <li class="col-sm-4">
                        <span class="item-label"><?php echo $equipments['name'] ?></span>
                        <div class="qty-box">
                        <input class="input-text" type="text" name="equipment[]" maxlength="2" onkeypress="return isNumber(event)" id="equipment<?php echo  $equipments['id'] ?>">
                        <div class="qty-arrows">
                        <input class="box" value="+" type="button" onclick="addnumber('equipment<?php echo  $equipments['id'] ?>')">
                        <input class="box" value="-" type="button" onclick="subsnumber('equipment<?php echo  $equipments['id'] ?>')">
                        </div>
                        </div>  
                        </li>

                        <?php  } ?>  

                        </ul>
                       
                       </div>
                        </div>
                        </div>
                        
                        
                        
                        <div class="form-group">
                       <div class="col-md-12 col-sm-12 col-lg-12">
                       <div class="col-md-12 col-sm-12 col-lg-12">
   <label><a href="javascript:void()" onclick="set_toggle(3)" class="pull-right testt-show"><i class="fa fa-plus-square"></i> Miscellaneous & Boxes:
</a></label></div>
                        
                        <div class="test-dwn3" style="display:none;">                        
                        
                        <ul class="moveproc-form">
                       <?php foreach ($boxes as $boxess) { ?>

                        <li class="col-sm-4">
                        <span class="item-label"><?php echo $boxess['name'] ?></span>
                        <div class="qty-box">
                        <input class="input-text" type="text" name="box[]" maxlength="2" onkeypress="return isNumber(event)" id="boxes<?php echo  $boxess['id'] ?>">
                        <div class="qty-arrows">
                        <input class="box" value="+" type="button" onclick="addnumber('boxes<?php echo  $boxess['id'] ?>')">
                        <input class="box" value="-" type="button" onclick="subsnumber('boxes<?php echo  $boxess['id'] ?>')">
                        </div>
                        </div>  
                        </li>

                        <?php  } ?>  

                        </ul>
                       
                       </div>
                        </div>
                        </div>
                        
                     
                        <hr>
                        
                        <div class="form-group">
                    <div class="file-wrap">
                    <div class="col-md-3 col-sm-3 col-lg-3">
                    <label>Upload pictures:</label>
                    </div>
                    <div class="col-md-7 col-sm-7 col-lg-7">
                    <input type="file" name="file">
                    </div>
                    </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="col-md-7 col-sm-7 col-lg-7 col-sm-offset-3">
                    <div class="tip mt10">
                    <img src="<?=base_url();?>assets/img/tip.png"> <strong>Tip:</strong>  Listings with pictures get  <strong>63%</strong> more bids than listings without pictures! You can add an image now or do it later from your <strong> dashboard.</strong>
                     </div>
                     </div>
                     </div>
                     
                     <div class="form-group">
                        <div class="col-md-3 col-sm-3 col-lg-3"><label>Additional details:</label></div>
                       <div class="col-md-7 col-sm-7 col-lg-7">
                       <textarea class="form-control" rows="6" name="item_detail"></textarea>
                        </div>
                        </div>
                        
                        <div class="form-group">
                    <div class="col-md-7 col-sm-7 col-lg-7 col-sm-offset-3">
                    <div class="tip mt10">
                    <img src="<?php echo base_url() ?>assets/img/tip.png"> <strong>IMPORTANT:</strong>   Do not include your contact information here. For your security, your contact information will be exchanged only after you book a delivery with your chosen Service Provider
                     </div>
                     </div>
                     </div>
                     
                     <hr>
                     <div class="col-md-3 col-sm-3 col-lg-3"></div>
                     <div class="col-md-6 col-sm-6 col-lg-6">

                     <input type="hidden" name="cat_id" value="<?php echo $category_id ?>">
                     <input type="hidden" name="subcat_id" value="<?php echo $subcategory_id ?>"> 	
                     <input type="submit" value="Continue" class="btn btn-all-color" onclick="validate()"></div>  
                        
      
                        </form>
                </div>
                
                
            </div>
            
            
        </div>
    </div>
</section>

<?php $this->load->view('footer') ?>


<script type="text/javascript">
	$('#delivery_title').bind('keypress', function (event) {
    var regex =new RegExp("^[ a-zA-Z0-9\b]+$")
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
    });
function set_toggle(val)
 {
  $('.test-dwn'+val).slideToggle();
  
 }

$(".testt-show").click(function()
{
               
  $(this).find('i').toggleClass("fa-minus-square");

});

function addnumber(id)
{
  var val=$("#"+id).val();
  if(val=="" || val==null)
  {
    $("#"+id).val(0);
  }
  else
  {
     $("#"+id).val(parseInt(val)+1);
  }
}

function subsnumber(id)
{
  var val=$("#"+id).val();

if(val=="" || val==null || val==0)
  {
    $("#"+id).val('');
  }
  else
  {
     $("#"+id).val(parseInt(val)-1);
  }
}

function validate() {
  if(document.form.delivery_title.value == "")
 {
  alert("Please enter title");
document.form.delivery_title.focus();
return false;
  }
}

function elevator_service(val)
{
	if(val==1)
	{
		$("#service-lift-elevator").attr("style","display:block");
	}
	else
	{
		$("#service-lift-elevator").attr("style","display:none");
	}

}

</script>
