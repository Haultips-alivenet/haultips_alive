<?php 

error_reporting(0);

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newshipment extends CI_Controller {



	/**

	 * Index Page for this controller.

	 *

	 * Maps to the following URL

	 * 		http://example.com/index.php/welcome

	 *	- or -  

	 * 		http://example.com/index.php/welcome/index

	 *	- or -

	 * Since this controller is set as the default controller in 

	 * config/routes.php, it's displayed at http://example.com/

	 *

	 * So any other public methods not prefixed with an underscore will

	 * map to /index.php/welcome/<method_name>

	 * @see http://codeigniter.com/user_guide/general/urls.html

	 */

	function __construct(){

		parent::__construct();

		$this->load->model('shipping');	

		$this->load->model('pagemodel');

                $this->load->model('finddeliveries');
             

		$this->load->library('session');



        // for android view and check userid and password in db



        if($this->input->get('webView')== 'android')

        {

            $uid = $this->input->get('userId');

            $webview = $this->input->get('webView');

            $password = $this->input->get('pass');

            $data = $this->shipping->get_customer_by_id($uid);



            if(count($data) > 0)

            {

                $data = $data[0];



                $dbPass = $data['password'];

                if($dbPass == $password && $data['customer_id'] == $uid) {

                    $this->session->set_userdata('cus_id', $uid);

                    $this->session->set_userdata('webView', $webview);

                }

            }

        }





		/*if($this->session->userdata('cus_id') or $this->session->userdata('new_register_cus_id')) {

				         

			}

			else

			{

				redirect(base_url().'login');       

			}

		*/



			if($this->session->userdata('carr_cus_id'))

			{



				redirect(base_url().'carrier/dashboard');   

				         

			}

			else

			{

				

			}



	}



	public function index()

	{





if($this->input->post('singlebutton')!='')

{

	$category= $this->input->post('category');

	$subcategory= $this->input->post('subcategory');

	$custid= $this->session->userdata('new_register_cus_id');

	$category_function=$this->shipping->get_category_function($category,$subcategory);

	$function_name=$category_function[0]['function_name'];



	//PAYLOAD PART START//



	$query= "insert into  `shippment_category` set customer_id='".$custid."',category_id='".$category."',subcategory_id='".$subcategory."'";

	$result = $this->db->query($query);

    $insertedid=$this->db->insert_id();

    if($insertedid>0)

    {

               redirect(base_url()."shipping/newshipment/".$function_name."/$category/$subcategory");

    }

    else

    {

                print_r('error');

    }

    exit();







//PAYLAOD PART END//

}

		

		$query = "SELECT cat_id,cat_name FROM `admin_shippment_category` where status='active' and parent_id='0' and cat_id!='34'";

		$result = $this->db->query($query);

		$data['category']=$result->result_array();



		$this->load->view('shipment/index',$data);

		//$this->load->view('login_header');

	}



	public function getsub()

	{

		$id= $_POST['id'];

		$query= "SELECT cat_id,cat_name FROM `admin_shippment_category` where status='active' and parent_id='".$id."'";

		$result = $this->db->query($query);

		$subcategory=$result->result_array();

		//$sub=$subcategory[0]['cat_name'];

		$count=count($subcategory);

		if($count!=0)

		{

		$header='<select class="new-shipment-combox" name="subcategory" id="subcategory" data-style="btn-warning">

                            <option value=""> ... Select Subcategory ...</option>';

                            $footer='</select><span id="subcategoryerr" style="color:red"></span>';

                            $data='';



                            foreach ($subcategory as $key) {



                               $data.='<option value='.$key['cat_id'].'>'.$key['cat_name'].'</option>';

                            

                            }

                            $subcategory= $header.$data.$footer;



                            echo $subcategory;

                            exit();

                        }

		

	}

//Delivery_Item_Information(step2)

	public function delivery_item_payload($category,$subcategory)

	{



 

$data='';

		$data['category_id']= $category;

		$data['subcategory']= $subcategory;

		

	$packingtypequery="SELECT id,name from `shipping_packaging_type` where status='active' order by id";	

	$result = $this->db->query($packingtypequery);

		$data['packingtype']=$result->result_array();







		$freighttypesquery="SELECT id,name from `shipping_packaging_type` where status='active' order by id";	

	$result = $this->db->query($freighttypesquery);

		$data['freighttype']=$result->result_array();



		$pickuploactionquery="SELECT id,name from `shipping_pickup_location` where status='active' order by id";	

	$result = $this->db->query($pickuploactionquery);

		$data['pickuploaction']=$result->result_array();



		$deliveryloactionquery="SELECT id,name from `shipping_delivery_location` where status='active' order by id";	

	$result = $this->db->query($deliveryloactionquery);

		$data['deliveryloaction']=$result->result_array();



		$additionalservicesquery="SELECT id,name from `shipping_additional_services` where status='active' order by id";	

	$result = $this->db->query($additionalservicesquery);

		$data['additionalservices']=$result->result_array();

		$data['additionalservicescount']= count($data['additionalservices']);





		$collectionservicesquery="SELECT id,name from `shipping_collection_services` where status='active' order by id";	

	$result = $this->db->query($collectionservicesquery);

		$data['collectionservices']=$result->result_array();

		$data['collectionservicescount']= count($data['collectionservices']);



		$deliveryservicesquery="SELECT id,name from `shipping_delivery_services` where status='active' order by id";	

	$result = $this->db->query($deliveryservicesquery);

		$data['deliveryservices']=$result->result_array();

		$data['deliveryservicescount']= count($data['deliveryservices']);







$this->load->view('shipment/partload',$data);



	}





	public function shipment_generation_payload()

	{

		

		

   if($this->input->post('submit')!='')

   {

   	if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }



   	$shippingquery= "INSERT into shipping_details set user_id='".$custid."',category_id='".$_POST['category']."',subcategory_id='".$_POST['subcategory']."',`table_name`='shipping_item_payload'";

  

	$result = $this->db->query($shippingquery);

    $shippingid=$this->db->insert_id();

 if($shippingid>0)

 {

  $this->session->set_userdata('shipping_id', $shippingid);

 	//ITEM START//



  $total_item=$this->input->post('add_item');

  /*print_r($this->input->post('totalweight1'));

  exit();*/



 	for($i=1; $i<=$total_item; $i++)

  {

  	if($this->input->post('hazardous'.$i))

  	{

  		$hazardous=1;

  	}

  	else

  	{

  		$hazardous=0;

  	}

  	if($this->input->post('stackable'.$i))

  	{

  		$stackable=1;

  	}

  	else

  	{

  		$stackable=0;

  	}

  	$length=$this->input->post('length'.$i);

  	$width=$this->input->post('width'.$i);

  	$height=$this->input->post('height'.$i);

  	if($length=='' or $width=='' or $height=='')

  	{

  		$dimension_unit='';

  	}

  	else

  	{

  		$dimension_unit=$this->input->post('dimension_unit'.$i);

  	}

    $shipping_partload=$this->shipping->insert_data(array(



  'shipping_id'=>$shippingid, 

  'is_type'=>$this->input->post('is_type'), 

  'packing_type_id'=>$this->input->post('packingtype'.$i),

  'freight_type_id'=>$this->input->post('freighttype'.$i),

  'length'=>$this->input->post('length'.$i),

  'height'=>$this->input->post('width'.$i),

  'width'=>$this->input->post('height'.$i),

  'dimension_uint'=> $dimension_unit,

  'weight'=>$this->input->post('itemweight'.$i),

  'weight_unit'=>$this->input->post('weighttype'.$i),

  'unit'=>$this->input->post('unit'.$i),

  'is_stackable'=>$stackable,

  'id_hazardous'=>$hazardous,

  'total_units'=>$this->input->post('totalweight'.$i)

  ),'shipping_item_payload');



  }



 

//ITEM END//



	//SERVICES START//

//addtional services//

$additioncount= $this->input->post('additionalservicescount');

$additional='0';

for($i=1;$i<=$additioncount;$i++)

{

	if($this->input->post('additionalservices'.$i)!='')

	{

		$additional.=','.$i;

	}

}

//addition service end//



//Collection Services//

$collectioncount= $this->input->post('collectionservicescount');

$collection='0';

for($i=1;$i<=$collectioncount;$i++)

{

	if($this->input->post('collectionservices'.$i)!='')

	{

		$collection.=','.$i;

	}

}

//Collection Services End//

//Delivery Services//

$deliverycount= $this->input->post('deliveryservicescount');

$delivery='0';

for($i=1;$i<=$deliverycount;$i++)

{

	if($this->input->post('deliveryservices'.$i)!='')

	{

		$delivery.=','.$i;

	}

}



$servicequery= "INSERT into shipping_payload_services set shipping_id='".$shippingid."',collection_services_id='".$collection."',

additional_services_id='".$additional."',delivery_services_id='".$delivery."'";



$result = $this->db->query($servicequery);

//Delivery Services End//

	//SERVICES END//



//Location SET//



//Pickup laoction//

$current_location_id=$this->input->post('pickup_location');

$address= $this->input->post('pickup_current_location');

$pickup_date= $this->input->post('pickup_date');

/*$pickup_detail_query ="INSERT into shipping_pickup_details set 

shipment_id='".$shippingid."',address='".$address."',current_location_id='".$current_location_id."',

pick_up_from_date='".$pickup_date."' ,

pick_up_to_date='".$pickup_date."'";



$result = $this->db->query($pickup_detail_query);*/

//pickup location end//



//Delivery laoction//

$current_location_id=$this->input->post('delivery_location');

$address= $this->input->post('delivery_current_address');

/*$delivery_detail_query ="INSERT into shipping_delivery_details set shipment_id='".$shippingid."',address='".$address."',current_location_id='".$current_location_id."'";

$result = $this->db->query($delivery_detail_query);*/

//Delivery location end//

//$data['shippingid']= $shippingid;

redirect(base_url()."shipping/newshipment/delivery_item");

 }



     

   }





   //exit();

	}



	public function delivery_item()

	{





		if($this->input->post('submit')!='')

		{

			print_r($this->input->post());

			print_r($_FILE);

			exit();

		}

        

        $shippingid = $this->session->userdata('shipping_id');

        $data='';

        $data['shippingid']= $shippingid;

		$this->load->view('shipment/delivery_item',$data);

	}



	public function delivery_item_genarate(){

		$shipping_id=$this->input->post('shipping_id');

		$shipping_detail=$this->shipping->get_data_with_single_cond($shipping_id,'shipping_details','id');

		$category_id=$shipping_detail[0]['category_id'];

		if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }	

                

                $this->upload->do_upload('file');

                $photo=str_replace(' ','',$_FILES['file']['name']);

               

				$delivery_information=$this->shipping->set_delivery_information(array(

				'shipping_id'=>$this->input->post('shipping_id'),

				'image'=>$photo,

				'description'=>$this->input->post('description')

			));

				redirect(base_url()."shipping/newshipment/collection_information");

			}

	}



	public function collection_delivery_information(){



		$shippingid = $this->session->userdata('shipping_id');

        $data='';

        $data['shippingid']= $shippingid;

        $data['pickup_location']= $this->shipping->get_pickup_location();

        $data['delivery_location']= $this->shipping->get_delivery_location();

        $data['delivery_information']= $this->shipping->get_delivery_information($shippingid);

		$this->load->view('shipment/collection-information',$data);



	}



	public function confirm_pickup_delivery_information(){







$pickupdetail='';

 $pickupdetail= $this->shipping->get_lat_lang($this->input->post('pickup_address'));

 $deliveryupdetail='';

 $deliveryupdetail= $this->shipping->get_lat_lang($this->input->post('delivery_address'));



		$shipping_id=$this->input->post('shipping_id');

		$pickup_update=$this->shipping->update_pickup_details(array(

			'address'=>$this->input->post('pickup_address'),

			'latitude'=>$pickupdetail['lat'],

			'longitude'=>$pickupdetail['lng'],

			'current_location_id'=>$this->input->post('pickup_location'),

			'date_type'=>$this->input->post('pickup_date_type'),

			'pick_up_from_date'=>$this->input->post('pickup_from_date'),

			'pick_up_to_date'=>$this->input->post('pickup_to_date')



			),$shipping_id);



		$delivery_update=$this->shipping->update_delivery_details(array(

			'address'=>$this->input->post('delivery_address'),

			'latitude'=>$deliveryupdetail['lat'],

			'longitude'=>$deliveryupdetail['lng'],

			'current_location_id'=>$this->input->post('delivery_lication'),

			'date_type'=>$this->input->post('delivery_date_type'),

			'delivery_from_date'=>$this->input->post('delivery_from_date'),

			'delivery_to_date'=>$this->input->post('delivery_to_date')



			),$shipping_id);



		redirect(base_url()."shipping/newshipment/listing_option");



		/*if($this->session->userdata('cus_id'))

		{

			redirect(base_url().'shipping/dashboard');

		}

		else

		{

		$custid=$this->session->userdata('new_register_cus_id');

		$cust= $this->shipping->get_data_with_single_cond($custid,'shipping_customer','customer_id');

		$status=$cust[0]['status'];

		if($status==0)

		{

			redirect(base_url().'login?message=gdkfjhheruchHAJshdjs');

		}

		

		else

		{

		$this->session->set_userdata('cus_id', $custid);

		redirect(base_url().'shipping/dashboard');

	}



	}*/		

		//redirect(base_url().'dashboard/shipping');

		/*$custid=$this->session->userdata('new_register_cus_id');

		$cust= $this->shipping->get_data_with_single_cond($custid,'shipping_customer','customer_id');

		$status=$cust[0]['status'];

		if($status==0)

		{

			redirect(base_url().'login?message=gdkfjhheruchHAJshdjs');

		}

		else

		{

		$this->session->set_userdata('cus_id', $custid);

		redirect(base_url().'shipping/dashboard');

		}*/



	}





/////////////////////////// Start Vehicle Category With Caravans & Campars////////////////////////////////////////



	public function delivery_item_vehicle_caravans($category,$subcategory)

	{

		$data['related_company']=$this->shipping->select_data('shipping_related_website');

		$data['campertype']=$this->shipping->select_data('vehicle_camper_type');

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/delivery-item-vehicle-caravans',$data);

	}



	public function get_website()

	{

		$web_id=$this->input->post('web_id');

		$website=$this->shipping->get_web_data($web_id);

		$is_url=$website[0]['is_url'];

		echo $is_url;

		exit();

	}



	public function shipment_generation_vehicle()

	{

		if($this->input->post('submit'))

   {



   $count=$this->input->post('num_of_caravans');

   	



   	if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   	$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$this->input->post('cat_id'),

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipping_item_vehicle_caravans'

   		),'shipping_details');

   	$this->session->set_userdata('shipping_id', $shipping_id);



   	for($i=1;$i<=$count;$i++)

   	{



   	$length=$this->input->post('item_lenth_ft_m'.$i).','.$this->input->post('item_lenth_in_cm'.$i);

   	$width=$this->input->post('item_width_ft_m'.$i).','.$this->input->post('item_width_in_cm'.$i);

   	$height=$this->input->post('item_height_ft_m'.$i).','.$this->input->post('item_height_in_cm'.$i);





   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/23/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);



                

}

	

   	$shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'auction_classified'=>$this->input->post('classified'),

   		'auction_classified_name'=>$this->input->post('online_related_website'),

   		'auction_classified_url'=>$this->input->post('urlbox'),

   		'number_of_caravan'=>$this->input->post('num_of_caravans'),

   		'camper_type'=>$this->input->post('camper_type'.$i),

   		'manufacturing_year'=>$this->input->post('year'.$i),

   		'made_in'=>$this->input->post('made'.$i),

   		'model_no'=>$this->input->post('modal'.$i),

   		'dimentions_type'=>$this->input->post('imperial_type'.$i),

   		'length'=>$length,

   		'width'=>$width,

   		'height'=>$height,

   		'weight'=>$this->input->post('item_weight'.$i),

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'item_image'=>$photo,

   		'Item_detail'=>$this->input->post('item_detail')

   	),'shipping_item_vehicle_caravans');

}



	redirect(base_url()."shipping/newshipment/collection_information");

	}

}



/////////////////////////// End Vehicle Category With Caravans & Campars////////////////////////////////////////





//////////////////////Collection & Delivery For Categories/////////////////////////////////////////////

public function collection_information(){



		$shippingid = $this->session->userdata('shipping_id');

        $data='';

        $data['shippingid']= $shippingid;

        $data['pickup_location']= $this->shipping->get_pickup_location();

        $data['delivery_location']= $this->shipping->get_delivery_location();

        //$data['delivery_information']= $this->shipping->get_delivery_information($shippingid);

		$this->load->view('shipment/collection-delivery-information',$data);



	}





public function confirm_pick_del_information(){

 


 $pickupdetail='';

 $pickupdetail= $this->shipping->get_lat_lang($this->input->post('pickup_address'));
  

 $deliveryupdetail='';

 $deliveryupdetail= $this->shipping->get_lat_lang($this->input->post('delivery_address'));


 

 $pick_lat=$pickupdetail['lat'];

 $pick_lng=$pickupdetail['lng'];

 $del_lat=$deliveryupdetail['lat'];

 $del_lng=$deliveryupdetail['lng'];



 if(($pick_lat=="" and $pick_lng=="") or ($del_lat=="" and $del_lng=="") )

 {



 	$this->session->set_flashdata('error','Invalid pickup or delivery address');

 	redirect(base_url()."shipping/newshipment/collection_information");

 }

 

/*echo "<pre>";

print_r($pickupdetail['lat']);

echo "<br>";

echo "<pre>";

print_r($deliveryupdetail);

echo "<br>";

exit();*/



else

{



	$shipping_id=$this->input->post('shipping_id');

	if($this->input->post('pickup_to_date')=='')

	{

		$pick_to_date=$this->input->post('pickup_from_date');

	}

	else

	{

		$pick_to_date=$this->input->post('pickup_to_date');

	}



		$pickup_details=$this->shipping->get_data_with_single_cond($shipping_id,'shipping_pickup_details','shipment_id');

		$count_pickup_details=count($pickup_details);

		/*echo $count_pickup_details;

		exit();*/

		if($count_pickup_details > 0)

		{



		$update_pickup=$this->shipping->update_data_single_cond(array(

			'address'=>$this->input->post('pickup_address'),

			'latitude'=>$pickupdetail['lat'],

			'longitude'=>$pickupdetail['lng'],

			'current_location_id'=>$this->input->post('pickup_location'),

			'date_type'=>$this->input->post('pickup_date_type'),

			'pick_up_from_date'=>$this->input->post('pickup_from_date'),

			'pick_up_to_date'=>$pick_to_date



			),$shipping_id,'shipment_id','shipping_pickup_details');



		}

		else

		{



		$pickup=$this->shipping->insert_data(array(

			'shipment_id'=>$shipping_id,

			'address'=>$this->input->post('pickup_address'),

			'latitude'=>$pickupdetail['lat'],

			'longitude'=>$pickupdetail['lng'],

			'current_location_id'=>$this->input->post('pickup_location'),

			'date_type'=>$this->input->post('pickup_date_type'),

			'pick_up_from_date'=>$this->input->post('pickup_from_date'),

			'pick_up_to_date'=>$pick_to_date



			),'shipping_pickup_details');

		}



		

		$delivery_details=$this->shipping->get_data_with_single_cond($shipping_id,'shipping_delivery_details','shipment_id');

		$count_delivery_details=count($pickup_details);



		if($count_delivery_details>0)

		{

			$update_delivery=$this->shipping->update_data_single_cond(array(

				'address'=>$this->input->post('delivery_address'),

				'latitude'=>$deliveryupdetail['lat'],

				'longitude'=>$deliveryupdetail['lng'],

				'current_location_id'=>$this->input->post('delivery_lication'),

				'date_type'=>$this->input->post('delivery_date_type'),

				'delivery_from_date'=>$this->input->post('delivery_from_date'),

				'delivery_to_date'=>$this->input->post('delivery_to_date')



				),$shipping_id,'shipment_id','shipping_delivery_details');

		}



		else {



		$delivery=$this->shipping->insert_data(array(

			'shipment_id'=>$shipping_id,

			'address'=>$this->input->post('delivery_address'),

			'latitude'=>$deliveryupdetail['lat'],

			'longitude'=>$deliveryupdetail['lng'],

			'current_location_id'=>$this->input->post('delivery_lication'),

			'date_type'=>$this->input->post('delivery_date_type'),

			'delivery_from_date'=>$this->input->post('delivery_from_date'),

			'delivery_to_date'=>$this->input->post('delivery_to_date')



			),'shipping_delivery_details');



		}	



		if(!$this->session->userdata('cus_id') and !$this->session->userdata('new_register_cus_id'))

		{

			$this->session->set_userdata('shipping_id',$shipping_id);

			redirect(base_url().'register/shipment_register');

		}

		else

		{



		redirect(base_url()."shipping/newshipment/listing_option");



		}

	}



}



////////////////////// End Collection & Delivery For Categories/////////////////////////////////////////////		





////////////////////// Start Pric Listing For Categories///////////////////////////////////////////////////

	public function listing_option(){



		$data['urgent_priority_fee']=$this->shipping->get_data_with_single_cond('urgent','priority_fee','fee_name');

		$data['featured_priority_fee']=$this->shipping->get_data_with_single_cond('featured','priority_fee','fee_name');

		$this->load->view('shipment/listing-options',$data);



	}

 function fee($category_id)

 {

     $this->db->select('fee,fee_type');

     $this->db->where('category_id',$category_id);

     $this->db->where('status','active');

     $row=$this->db->get('admin_fee')->row();

     return $row;   

 }

	public function confirm_listion_option(){



		$submit=$this->input->post('submit');

		/*echo $submit;

		exit();*/

                $fee_type=$this->input->post('fee_type');

                $fee=$this->input->post('fee');

                $shipping_id=$this->input->post('shipping_id');

		$customer_detail=$this->shipping->get_customer_by_shipping_id($shipping_id);

               

		$price=$this->input->post('price');

		/*echo $price;

		exit();*/

		$priority=$this->input->post('priority');

		$payment_type=$this->input->post('pay_type');

		$partial_payment_type=$this->input->post('pay_sub_type');



		if($payment_type==2)

		{

			$partial_payment_type=$partial_payment_type;

		}

		else

		{

			$partial_payment_type='';

		}


                $notverified='';
		if($this->session->userdata('cus_id'))

		{
                        
			$customer_id=$this->session->userdata('cus_id');

		}

		else

		{

			$customer_id=$this->session->userdata('new_register_cus_id');
                        $notverified='not verified';

		}

		

		$shipping_detail=$this->shipping->get_data_with_single_cond($customer_id,'shipping_details','user_id');

		/*echo $submit;

		exit();*/

		$update_shipping_detail=$this->shipping->update_data_single_cond(array(



			'post_status'=>$notverified!='' ? $notverified : 'publish'

		),$shipping_id,'id','shipping_details');

               

		//if($submit=='Paskelbti uÅ¾sakymÄ…')

		$listing_option= $this->shipping->get_data_with_single_cond($shipping_id,'shipping_listing_option','shipment_id');

		$count_listing_options=count($listing_option);

		if($price=='')

		{



			if($count_listing_options>0)

			{

				$update_quotes=$this->shipping->update_data_single_cond(array(

				'shipping_price_option'=>0

				),$shipping_id,'shipment_id','shipping_listing_option');

			}



			else

			{

			$quotes=$this->shipping->insert_data(array(

				'shipment_id'=>$shipping_id,

				'shipping_price_option'=>0,

                                'fee_amount'=>$fee,

                                'fee_type'=>$fee_type

				),'shipping_listing_option');



			$this->shipping->update_data_single_cond(array(



				'customer_payment_option'=>$payment_type,

				'partial_amount_value'=>$partial_payment_type,

				

				'status'=>'active'

				),$shipping_id,'id','shipping_details');



			}



		}

		else

		{



			if($count_listing_options>0)

			{

				$update_quotes=$this->shipping->update_data_single_cond(array(

				'shipping_price_option'=>1,

				'price'=>$price

				),$shipping_id,'shipment_id','shipping_listing_option');

			}



			else

			{



			$quotes_price=$this->shipping->insert_data(array(

			'shipment_id'=>$shipping_id,

			'shipping_price_option'=>1,

			'price'=>$price,

                        'fee_amount'=>$fee,

                        'fee_type'=>$fee_type

			),'shipping_listing_option');



			$this->shipping->update_data_single_cond(array(



				'customer_payment_option'=>$payment_type,

				'partial_amount_value'=>$partial_payment_type,

				

				'status'=>'active'

				),$shipping_id,'id','shipping_details');



			}



		}



	///////////////////////////////Start code for sms verify//////////////////////////////////////	

                $shipping_detail1=$this->shipping->get_data_with_single_cond($shipping_id,'shipping_details','id');

                

                $carr_idss=$this->shipping->get_carrier_ids($shipping_detail1[0]['category_id'],'carrier_transport_category','transport_category');

               // print_r($carr_idss);



                

		 $x=0;

		foreach ($shipping_detail as $shipping_details) {

   		$sms_verify=$shipping_details['sms_verify'];

	   if($sms_verify=='Yes')

	   { 

	    $x=1;

	    break;

	   } 

   

		} 

                

		if($x==1 && $customer_detail[0]['old_mobile']==$customer_detail[0]['mobile'])

		{

		  

		 $update_shipping_detail=$this->shipping->update_data_single_cond(array(



				'sms_verify'=>'Yes'

				

				),$shipping_id,'id','shipping_details'); 

                 $shipping_data1=$this->shipping->get_data_with_single_cond($shipping_id,'shipping_details','id');

		 

		$table_name1=$shipping_data1[0]['table_name'];

		$shipping_detailss=$this->finddeliveries->get_shipment_by_shiiping_id($shipping_id,$table_name1);

               

                if($shipping_detailss[0]['sdtodate']!='0000-00-00 00:00:00' &&  $shipping_detailss[0]['sdpickfromdate']!='0000-00-00 00:00:00')

                    $itemexpire=date('Y-m-d H:i:s', strtotime($shipping_detailss[0]['sdpickfromdate'].'+1 day'));

                else if($shipping_detailss[0]['sdpickfromdate']!='0000-00-00 00:00:00')

                     $itemexpire=date('Y-m-d H:i:s', strtotime($shipping_detailss[0]['sdpickfromdate'].'+1 day'));

                else $itemexpire='0000-00-00 00:00:00';

               

                        $item['shipping_id']=$shipping_id;

                        $item['user_type']='carrier';

                        $item['subject']='New Post';

                        $item['messcat_id']=2;

                        $item['expiry_date']=$itemexpire;

                        $title=$this->title($shipping_id);

                        $item['message_body']='<p>New item is posted. for more details <a href="'.base_url('find-detail/'.$title.'/'.$shipping_id).'">click here</a></p>';

		if($this->session->userdata('cus_id'))

		{

                        $item['sendfrom']=$this->session->userdata('cus_id');

                        foreach($carr_idss as $car_id){

                          $item['sendto']=$car_id['carrier_id'];

                          $this->db->insert('message_inbox',$item);  

                        }

                        

                        

			if($priority==1 or $priority==2)

			{

				redirect(base_url().'shipping/additionalpayment/index/'.$shipping_id.'/'.$priority);

			}

			else

			{

				$this->session->unset_userdata('shipping_id');

				$this->session->unset_userdata('new_register_cus_id');

				redirect(base_url().'shipping/dashboard');

			}

			

		}

		else

		{

                    $item['sendfrom']=$this->session->userdata('new_register_cus_id');

                     foreach($carr_idss as $car_id){

                          $item['sendto']=$car_id['carrier_id'];

                          $this->db->insert('message_inbox',$item);  

                          }

		$custid=$this->session->userdata('new_register_cus_id');

		$cust= $this->shipping->get_data_with_single_cond($custid,'shipping_customer','customer_id');

		$status=$cust[0]['status'];

		if($status==0)

		{

			if($priority==1 or $priority==2)

			{

				redirect(base_url().'shipping/additionalpayment/index/'.$shipping_id.'/'.$priority);

			}

			else

			{

				$this->session->unset_userdata('shipping_id');

				$this->session->unset_userdata('new_register_cus_id');

				$this->session->set_flashdata('message','Please confirm your e-mail , then you can log-in to the system');

				redirect(base_url().'login');

			}

			

		}

		

		else

		{

		$this->session->set_userdata('cus_id', $custid);



		if($priority==1 or $priority==2)

			{

				redirect(base_url().'shipping/additionalpayment/index/'.$shipping_id.'/'.$priority);

			}

			else

			{

				$this->session->unset_userdata('shipping_id');

				$this->session->unset_userdata('new_register_cus_id');

				redirect(base_url().'shipping/dashboard');

			}

	}

}

		}

		else

		{

		    redirect(base_url().'shipping/newshipment/get_otp_sms/'.$shipping_id.'/'.$priority);

		}

		 

	////////////////////////End code for sms verify///////////////////////////////////////////////////	



		/*if(!$this->session->userdata('cus_id') and !$this->session->userdata('new_register_cus_id'))

		{

			//$this->session->set_userdata('shipping_id',$shipping_id);

			redirect(base_url().'register/shipment_register');

		}

		else

		{



		if($this->session->userdata('cus_id'))

		{

			redirect(base_url().'shipping/dashboard');

		}

		else

		{

		$custid=$this->session->userdata('new_register_cus_id');

		$cust= $this->shipping->get_data_with_single_cond($custid,'shipping_customer','customer_id');

		$status=$cust[0]['status'];

		if($status==0)

		{

			$this->session->set_flashdata('message','Please confirm your e-mail , then you can log-in to the system');

			redirect(base_url().'login');

		}

		

		else

		{

		$this->session->set_userdata('cus_id', $custid);

		redirect(base_url().'shipping/dashboard');

	}

}



}*/



	}



////////////////////// End Price Listing For Categories///////////////////////////////////////////////////



//////////////Start Vehicle Category With SubCategories(Trailer ,Mobilehomes,Airplanes,Antique and other vehicles) created by kishan 21-10-2015 ////////

	public function shipment_item_vehicle($category,$subcategory)

	{



		$data['related_company']=$this->shipping->select_data('shipping_related_website');

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/vehicle-trailer',$data);

	}



	public function shipping_generate_vehicle()

	{



		$category_id=$this->input->post('cat_id');



		if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipping_item_vehicle'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



   	$length=$this->input->post('item_lenth_ft_m1').','.$this->input->post('item_lenth_in_cm1');

   	$width=$this->input->post('item_width_ft_m1').','.$this->input->post('item_width_in_cm1');

   	$height=$this->input->post('item_height_ft_m1').','.$this->input->post('item_height_in_cm1');



   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);

                /*echo $photo;

                exit();*/



                $shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'auction_classified'=>$this->input->post('classified'),

   		'auction_classified_name'=>$this->input->post('online_related_website'),

   		'auction_classified_url'=>$this->input->post('urlbox'),

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'dimentions_type'=>$this->input->post('imperial_type'),

   		'length'=>$length,

   		'width'=>$width,

   		'height'=>$height,

   		'weight'=>$this->input->post('item_weight1'),

   		'item_image'=>$photo,

   		'item_detail'=>$this->input->post('item_detail')

   	),'shipping_item_vehicle');





	redirect(base_url()."shipping/newshipment/collection_information");

               

				

			}

 	



	}



/////////////End Vehicle Category With SubCategories(Trailer ,Mobilehomes,Airplanes,Antique and other vehicles) created by kishan 21-10-2015 ////////







//////////////////////Start Small Parcels Categories////////////////////////////////////////



	public function shipment_small_parcels($category)

	{



		$data['related_company']=$this->shipping->select_data('shipping_related_website');

		$data['category_id']=$category;

		$this->load->view('shipment/small-parcels',$data);

	}



	public function shipping_generate_small_parcels()

	{





		if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$this->input->post('cat_id'),

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipping_item_small_parcels'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



   	$length=$this->input->post('item_lenth_ft_m1').','.$this->input->post('item_lenth_in_cm1');

   	$width=$this->input->post('item_width_ft_m1').','.$this->input->post('item_width_in_cm1');

   	$height=$this->input->post('item_height_ft_m1').','.$this->input->post('item_height_in_cm1');



   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/103/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);

                /*echo $photo;

                exit();*/



                $shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'auction_classified'=>$this->input->post('classified'),

   		'auction_classified_name'=>$this->input->post('online_related_website'),

   		'auction_classified_url'=>$this->input->post('urlbox'),

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'dimentions_type'=>$this->input->post('imperial_type'),

   		'length'=>$length,

   		'width'=>$width,

   		'height'=>$height,

   		'weight'=>$this->input->post('item_weight1'),

   		'item_image'=>$photo,

   		'item_detail'=>$this->input->post('item_detail')

   	),'shipping_item_small_parcels');





	redirect(base_url()."shipping/newshipment/collection_information");

               

				

			}

 	



	}

//////////////////////Start Small Parcels Categories////////////////////////////////////////



//////////////////////Start Passangers category With their all SubCategories////////////////////////////////////////



	public function shipment_passenger($category,$subcategory)

	{



		$data['related_company']=$this->shipping->select_data('shipping_related_website');

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/passenger',$data);

	}



	public function shipping_generate_passenger()

	{





		if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$this->input->post('cat_id'),

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipping_item_passenger'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



   	

   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/104/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);

                /*echo $photo;

                exit();*/



                $shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'delivery_title'=>$this->input->post('title'),

   		'trip_description'=>$this->input->post('trip_description'),

   		'about_yourself'=>$this->input->post('about_yourself'),

   		'music_taste'=>$this->input->post('music_taste'),

   		'do_you_smoke'=>$this->input->post('smoke'),

   		'item_image'=>$photo

   		/*'item_detail'=>$this->input->post('additional_info')*/

   		

   	),'shipping_item_passenger');





	redirect(base_url()."shipping/newshipment/collection_information");

               

				

			}

 	



	}

//////////////////////End Passangers category With their all SubCategories////////////////////////////////////////







//////////////////////Start Junk categories and their Subcategories////////////////////////////////////////

	public function shipment_junk($category,$subcategory)

	{



		$data['related_company']=$this->shipping->select_data('shipping_related_website');

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/junk',$data);

	}



	public function shipping_generate_junk()

	{





		if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$this->input->post('cat_id'),

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipping_item_junk'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



   	

   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/108/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);

                /*echo $photo;

                exit();*/



                $shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'auction_classified'=>$this->input->post('classified'),

   		'auction_classified_name'=>$this->input->post('online_related_website'),

   		'auction_classified_url'=>$this->input->post('urlbox'),

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'junk_description'=>$this->input->post('junk_description'),

   		'junk_dimension'=>$this->input->post('junk_dimension'),

   		'item_image'=>$photo,

   		'item_detail'=>$this->input->post('item_detail')

   		

   	),'shipping_item_junk');





	redirect(base_url()."shipping/newshipment/collection_information");

               

				

			}

 	



	}



//////////////////////End Junk categories and their Subcategories////////////////////////////////////////







//////////////////////Start Vehicle With Vehicle Part SubCategories////////////////////////////////////////

	public function delivery_item_vehicle_part($category,$subcategory)

	{



 

$data='';

		$data['category_id']= $category;

		$data['subcategory']= $subcategory;

		

	$packingtypequery="SELECT id,name from `shipping_packaging_type` where status='active' order by id";	

	$result = $this->db->query($packingtypequery);

		$data['packingtype']=$result->result_array();







		$freighttypesquery="SELECT id,name from `shipping_packaging_type` where status='active' order by id";	

	$result = $this->db->query($freighttypesquery);

		$data['freighttype']=$result->result_array();



		$pickuploactionquery="SELECT id,name from `shipping_pickup_location` where status='active' order by id";	

	$result = $this->db->query($pickuploactionquery);

		$data['pickuploaction']=$result->result_array();



		$deliveryloactionquery="SELECT id,name from `shipping_delivery_location` where status='active' order by id";	

	$result = $this->db->query($deliveryloactionquery);

		$data['deliveryloaction']=$result->result_array();



		$additionalservicesquery="SELECT id,name from `shipping_additional_services` where status='active' order by id";	

	$result = $this->db->query($additionalservicesquery);

		$data['additionalservices']=$result->result_array();

		$data['additionalservicescount']= count($data['additionalservices']);





		$collectionservicesquery="SELECT id,name from `shipping_collection_services` where status='active' order by id";	

	$result = $this->db->query($collectionservicesquery);

		$data['collectionservices']=$result->result_array();

		$data['collectionservicescount']= count($data['collectionservices']);



		$deliveryservicesquery="SELECT id,name from `shipping_delivery_services` where status='active' order by id";	

	$result = $this->db->query($deliveryservicesquery);

		$data['deliveryservices']=$result->result_array();

		$data['deliveryservicescount']= count($data['deliveryservices']);







$this->load->view('shipment/vehicle-vehicle-part',$data);



	}





	public function shipment_generation_vehicle_part()

	{

		

		

   if($this->input->post('submit')!='')

   {



   	if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }



   	$shippingquery= "INSERT into shipping_details set user_id='".$custid."',category_id='".$_POST['category']."',subcategory_id='".$_POST['subcategory']."',`table_name`='shipping_item_vehicle_part'";

  

	$result = $this->db->query($shippingquery);

    $shippingid=$this->db->insert_id();

 if($shippingid>0)

 {

  $this->session->set_userdata('shipping_id', $shippingid);

 	//ITEM START//



  $total_item=$this->input->post('add_item');

  /*print_r($this->input->post('totalweight1'));

  exit();*/



 	for($i=1; $i<=$total_item; $i++)

  {

  	if($this->input->post('hazardous'.$i))

  	{

  		$hazardous=1;

  	}

  	else

  	{

  		$hazardous=0;

  	}

  	if($this->input->post('stackable'.$i))

  	{

  		$stackable=1;

  	}

  	else

  	{

  		$stackable=0;

  	}

  	$length=$this->input->post('length'.$i);

  	$width=$this->input->post('width'.$i);

  	$height=$this->input->post('height'.$i);

  	if($length=='' or $width=='' or $height=='')

  	{

  		$dimension_unit='';

  	}

  	else

  	{

  		$dimension_unit=$this->input->post('dimension_unit'.$i);

  	}

    $shipping_partload=$this->shipping->insert_data(array(



  'shipping_id'=>$shippingid, 

  'is_type'=>$this->input->post('is_type'), 

  'packing_type_id'=>$this->input->post('packingtype'.$i),

  'freight_type_id'=>$this->input->post('freighttype'.$i),

  'lenght'=>$this->input->post('length'.$i),

  'height'=>$this->input->post('width'.$i),

  'width'=>$this->input->post('height'.$i),

  'dimension_uint'=> $dimension_unit,

  'weight'=>$this->input->post('itemweight'.$i),

  'weight_unit'=>$this->input->post('weighttype'.$i),

  'unit'=>$this->input->post('unit'.$i),

  'is_stackable'=>$stackable,

  'id_hazardous'=>$hazardous,

  'total_units'=>$this->input->post('totalweight'.$i)

  ),'shipping_item_vehicle_part');



  }



 

 	

//ITEM END//



	//SERVICES START//

//addtional services//

$additioncount= $this->input->post('additionalservicescount');

$additional='0';

for($i=1;$i<=$additioncount;$i++)

{

	if($this->input->post('additionalservices'.$i)!='')

	{

		$additional.=','.$i;

	}

}

//addition service end//



//Collection Services//

$collectioncount= $this->input->post('collectionservicescount');

$collection='0';

for($i=1;$i<=$collectioncount;$i++)

{

	if($this->input->post('collectionservices'.$i)!='')

	{

		$collection.=','.$i;

	}

}

//Collection Services End//

//Delivery Services//

$deliverycount= $this->input->post('deliveryservicescount');

$delivery='0';

for($i=1;$i<=$deliverycount;$i++)

{

	if($this->input->post('deliveryservices'.$i)!='')

	{

		$delivery.=','.$i;

	}

}



$servicequery= "INSERT into shipping_payload_services set shipping_id='".$shippingid."',collection_services_id='".$collection."',

additional_services_id='".$additional."',delivery_services_id='".$delivery."'";



$result = $this->db->query($servicequery);

//Delivery Services End//

	//SERVICES END//



//Location SET//



//Pickup laoction//

$current_location_id=$this->input->post('pickup_location');

$address= $this->input->post('pickup_current_location');

$pickup_date= $this->input->post('pickup_date');

/*$pickup_detail_query ="INSERT into shipping_pickup_details set 

shipment_id='".$shippingid."',address='".$address."',current_location_id='".$current_location_id."',

pick_up_from_date='".$pickup_date."'";*/



/*$result = $this->db->query($pickup_detail_query);*/

//pickup location end//



//Delivery laoction//

$current_location_id=$this->input->post('delivery_location');

$address= $this->input->post('delivery_current_address');

/*$delivery_detail_query ="INSERT into shipping_delivery_details set shipment_id='".$shippingid."',address='".$address."',current_location_id='".$current_location_id."'";

$result = $this->db->query($delivery_detail_query);*/

//Delivery location end//

//$data['shippingid']= $shippingid;

redirect(base_url()."shipping/newshipment/delivery_item");

 }



     

   }





   //exit();

	}

//////////////////////End Vehicle With Vehicle Part SubCategories////////////////////////////////////////



//////////////////////Start Vehicle With Heavy Truck And Tractors SubCategories////////////////////////////////////////



	public function shipment_vehicle_heavy_truck($category,$subcategory=null)

	{



		$data['related_company']=$this->shipping->select_data('shipping_related_website');

		$data['equipment_category']=$this->shipping->select_data('equipment_category');

		$data['truck_trailer']=$this->shipping->select_data('shipping_truck_trailer');

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/vehicle-heavy-truck',$data);

	}
	
	
	//code by deepak for new type of category
	public function shipment_category_new_func($category,$subcategory=null)

	{



		$data['related_company']=$this->shipping->select_data('shipping_related_website');

		$data['equipment_category']=$this->shipping->select_data('equipment_category');

		$data['truck_trailer']=$this->shipping->select_data('shipping_truck_trailer');

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/shipment-category-new',$data);

	}


	public function get_equip_type()

	{

		$equip_cat_id=$this->input->post('id');

		$index=$this->input->post('index');

		$equipment_type=$this->shipping->get_data_with_single_cond($equip_cat_id,'equipment_type','equip_cat_id');

		$header1='<label class="col-sm-3"> Equipment type:: &nbsp; </label>';

		$header2='<div class="col-sm-5"><select class="form-control" name="equip_type'.$index.'" required id="equip_type" onchange="show_dimension('.$index.')" >;

                            <option value=""> -Select an equipment type-</option>';

                            $footer='</select></div>';

                            $data='';



                            foreach ($equipment_type as $key) {



                               $data.='<option value='.$key['equip_cat_id'].'>'.$key['equip_type_name'].'</option>';

                            

                            }

                            $equipment_type= $header1.$header2.$data.$footer;



                            echo $equipment_type;

                            exit();

                        }



    public function shipping_generate_vehicle_heavy_truck()

	{



		$category_id=$this->input->post('cat_id');



		if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipping_item_vehicle_heavytruck'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



   		$count=$this->input->post('adding_item');

   		/*print_r($count);

   		exit();*/





   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);

                /*echo $photo;

                exit();*/



                for($i=1; $i<=$count; $i++)

   		{

   			$modal=$this->input->post('model'.$i);

   			if($modal!='')

   			{

   				$equipment_category=$this->input->post('equip_category'.$i);

   				$equipment_type=$this->input->post('equip_category'.$i);

   				$dimentions_type=$this->input->post('imperial_type'.$i);
   				
   				
   				if($this->input->post('item_lenth_ft_m'.$i)!='' && $this->input->post('item_lenth_in_cm'.$i) != ''){
					$len1 = $this->input->post('item_lenth_ft_m'.$i);
					$len2 = $this->input->post('item_lenth_in_cm'.$i);
				} else if($this->input->post('item_lenth_ft_m'.$i)=='' && $this->input->post('item_lenth_in_cm'.$i) == '') {
					$len1 = 'NA';
					$len2 = 'NA';
				} else {
					if($this->input->post('item_lenth_ft_m'.$i)!=''){
						$len1 = $this->input->post('item_lenth_ft_m'.$i);
					} else {
						$len1 = '0';
					}
					if($this->input->post('item_lenth_in_cm'.$i) != ''){
						$len2 = $this->input->post('item_lenth_in_cm'.$i);
					} else {
						$len2 = '0';
					}
				}
				
				
				

   				$length=$len1.','.$len2;
   				
   				if($this->input->post('item_width_ft_m'.$i)!='' && $this->input->post('item_width_in_cm'.$i) !='' ){
					$wid1 = $this->input->post('item_width_ft_m'.$i);
					$wid2 = $this->input->post('item_width_in_cm'.$i);
				} else if($this->input->post('item_width_ft_m'.$i)=='' && $this->input->post('item_width_in_cm'.$i) ==''){
					$wid1 = 'NA';
					$wid2 = 'NA';
				} else {   				
					if($this->input->post('item_width_ft_m'.$i)!=''){
						$wid1 = $this->input->post('item_width_ft_m'.$i);
					} else {
						$wid1 = '0';
					}
					if($this->input->post('item_width_in_cm'.$i) !=''){
						$wid2 = $this->input->post('item_width_in_cm'.$i);
					} else {
						$wid2 = '0';
					}
				}
   				$width=$wid1.','.$wid2;
   				
   				
   				if($this->input->post('item_height_ft_m'.$i) !='' && $this->input->post('item_height_in_cm'.$i) !='') {
					$ht1 = $this->input->post('item_height_ft_m'.$i);
					$ht2 = $this->input->post('item_height_in_cm'.$i);
				} else if($this->input->post('item_height_ft_m'.$i) =='' && $this->input->post('item_height_in_cm'.$i) =='') {
					$ht1 = 'NA';
					$ht2 ='NA';
				} else {
					if($this->input->post('item_height_ft_m'.$i) !='' ) {
						$ht1 = $this->input->post('item_height_ft_m'.$i);
					} else {
						$ht1 = '0';
					}
					if($this->input->post('item_height_in_cm'.$i) !=''){
						$ht2 = $this->input->post('item_height_in_cm'.$i);
					} else {
						$ht2 ='0';
					}
				}
					
   				$height=$ht1.','.$ht2;
   				
   				if($this->input->post('item_weight'.$i)!=''){
					$wt = $this->input->post('item_weight'.$i);
				} else {
					$wt = 'NA';
				}
   				$weight=$wt;

   			}

   			else

   			{

   				$equipment_category='';

   				$equipment_type='';

   				$dimentions_type='';

   				$length='';

   				$width='';

   				$height='';

   				$weight='';

   			}	





                $shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'auction_classified'=>$this->input->post('classified'),

   		'auction_classified_name'=>$this->input->post('online_related_website'),

   		'auction_classified_url'=>$this->input->post('urlbox'),

   		'no_of_item'=>$this->input->post('item'.$i),

   		'make'=>$this->input->post('make'.$i),

   		'modal'=>$this->input->post('model'.$i),

   		'ecuipment_category_id'=>$equipment_category,

   		'equipment_type_id'=>$equipment_type,

   		'dimentions_type'=>$dimentions_type,

   		'length'=>$length,

   		'width'=>$width,

   		'height'=>$height,

   		'weight'=>$weight,

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'trailer_already'=>$this->input->post('already_trailer'),

   		'trailer_type'=>$this->input->post('trailer_type'),

   		'truck_trailer'=>$this->input->post('truck_trailer'),

   		'item_image'=>$photo,

   		'item_detail'=>$this->input->post('item_detail')

   	),'shipping_item_vehicle_heavytruck');



}

	redirect(base_url()."shipping/newshipment/collection_information");

               

				

			



		}

 	



	} 



	//////////////////////End Vehicle With Heavy Truck And Tractors SubCategories/////////////////////////////    





	/*public function send_sms()

	{

		 $this->load->library('sms/smsapi');

		 $otp=rand(10,10000);

		 $shipping_id=$this->input->post('shipping_id');

		 $data['shipping_id']=$shipping_id

		 $shipping_otp=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'otp'=>$otp

   		),'sms_otp');



		 $sms = $this->smsapi->sendsms_api('9716254254','kishan kumar',$otp);

		 redirect(base_url()."shipping/newshipment/confirm_otp");*/

		  



	/*$AccountSid = "ACe22b70b952e9785ea60c9b709eea3124";

    $AuthToken = "b9fbe81f659b61045eeaae3de5f6cc02";*/

 

    // Step 3: instantiate a new Twilio Rest Client

    //$client = new Services_Twilio($AccountSid, $AuthToken);

 

    // Step 4: make an array of people we know, to send them a message. 

    // Feel free to change/add your own phone number and name here.

   /* $people = array(

        "+919716254254" => "kishan kumar",*/

        /*"+14158675310" => "Boots",

        "+14158675311" => "Virgil",*/

    //);

 

    // Step 5: Loop over all our friends. $number is a phone number above, and 

    // $name is the name next to it

  //  foreach ($people as $number => $name) {

 

       // $sms = $client->account->messages->sendMessage(

 

        // Step 6: Change the 'From' number below to be a valid Twilio number 

        // that you've purchased, or the (deprecated) Sandbox number

           // "+12565429742", 

 

            // the number we are sending to - Any phone number

         //   $number,

 

            // the sms body

           // "Your one time password is".rand(10,10000)

       // );

 //

        // Display a confirmation message on the screen

       // echo "Sent message to $name";

    	//}

	//}



 //////////////////////////////Start code for otp on new shipment////////////////////////////





      public function get_otp_sms($shipping_id,$priority)

      {

      	 $data['customer_detail']=$this->shipping->get_customer_by_shipping_id($shipping_id);

      	 $data['shipping_id']=$shipping_id;

      	 $data['priority']=$priority;

      	 



      	 $this->load->view('shipment/sms-otp',$data);

      }  





	public function confirm_otp()

	{


		
		$shipping_id=$this->input->post('shipping_id');

		$priority=$this->input->post('priority');

		//$this->load->library('sms/smsapi');

		 $otp_rand=rand(1000000,9999999);

		 $otp=$otp_rand;

		 //$shipping_id=$this->input->post('shipping_id');

		 $data['shipping_id']=$shipping_id;

		 $customer_detail=$this->shipping->get_customer_by_shipping_id($shipping_id);

		 $mobile=$customer_detail[0]['country_code'].$customer_detail[0]['mobile'];

		 $shipping_otp=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'otp'=>$otp

   		),'sms_otp');



			//new sms code for route6 dated 25th may,2016

			$ch = curl_init();
			curl_setopt_array($ch, array(
    		CURLOPT_URL => 'http://sms6.routesms.com/bulksms/bulksms',
    		CURLOPT_RETURNTRANSFER => true,
    		CURLOPT_POST => true,
    		CURLOPT_POSTFIELDS => 'username=ALIVETRAN&password=ali12345&type=0&dlr=1&destination='.$customer_detail[0]['mobile'].'&source=HULTPS&message='.$otp
    		//,CURLOPT_FOLLOWLOCATION => true
				));
//get response
$output = curl_exec($ch);
curl_close($ch);
//echo $output;
//exit();

	//new sms code for route6 dated 25th may,2016 ends here



redirect(base_url().'shipping/newshipment/otp_confirmation/'.$shipping_id.'/'.$priority);


		/*
kishan's SMS code

		$ch=curl_init();

		curl_setopt($ch, CURLOPT_URL, 'http://bulksms.amassing.in/new/api/api_http.php?username=UGINFO&password=uginfo&senderid=UGINFO&to='.$mobile.'&text=your%20one%20time%20password%20is%20'.$otp.'&route=Informative&type=text&datetime=2015-12-22%2016%3A57%3A24');

		curl_setopt($ch, CURLOPT_HEADER, true);

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		curl_exec($ch);

		curl_close($ch);
*/
		



		//exit();





		 //$sms = $this->smsapi->sendsms_api($mobile,'kishan kumar',$otp);

		 

		  



		 

	}



	public function otp_confirmation($shipping_id,$priority)

	{

		$data['shipping_id']=$shipping_id;

		$data['priority']=$priority;

		$data['error']=$this->input->get('error');

		$this->load->view('shipment/sms-otp-confirm',$data);

	}



	public function set_confirm_otp()

	{

		$shipping_id=$this->input->post('shipping_id');

		$priority=$this->input->post('priority');

		$otp=$this->input->post('otp');

		$customer_detail=$this->shipping->get_customer_by_shipping_id($shipping_id);

		$otp_detail=$this->shipping->get_otp($shipping_id);

		$otp_org=$otp_detail[0]['otp'];

		$otp_count=strlen(trim($otp));



		if($otp_count>7 or $otp!=$otp_org )

		{

			redirect(base_url()."shipping/newshipment/otp_confirmation/$shipping_id/$priority?error=HHjhasdhkjashdsahdret");

		}

		else

		{

			$update_shipping_detail=$this->shipping->update_data_single_cond(array(



				'sms_verify'=>'Yes'

				/*'post_status'=>'publish'*/

				),$shipping_id,'id','shipping_details');



			$update_customer_mobile=$this->shipping->update_data_single_cond(array(



				'old_mobile'=>$customer_detail[0]['mobile']

				/*'post_status'=>'publish'*/

				),$customer_detail[0]['customer_id'],'customer_id','shipping_customer');





			if($this->session->userdata('cus_id'))

		{

			if($priority==1 or $priority==2)

			{

				redirect(base_url().'shipping/additionalpayment/index/'.$shipping_id.'/'.$priority);

			}

			else

			{

				$this->session->unset_userdata('shipping_id');

				$this->session->unset_userdata('new_register_cus_id');

				redirect(base_url().'shipping/dashboard');

			}

		}

		else

		{

		$custid=$this->session->userdata('new_register_cus_id');

		$cust= $this->shipping->get_data_with_single_cond($custid,'shipping_customer','customer_id');

		$status=$cust[0]['status'];

		if($status==0)

		{

			if($priority==1 or $priority==2)

			{

				redirect(base_url().'shipping/additionalpayment/index/'.$shipping_id.'/'.$priority);

			}

			else

			{

				$this->session->unset_userdata('shipping_id');

				$this->session->unset_userdata('new_register_cus_id');

				$this->session->set_flashdata('message','Please confirm your e-mail , then you can log-in to the system');

				redirect(base_url().'login');

			}

			

		}

		

		else

			{

			$this->session->set_userdata('cus_id', $custid);

			if($priority==1 or $priority==2)

			{

				redirect(base_url().'shipping/additionalpayment/index/'.$shipping_id.'/'.$priority);

			}

			else

			{



				$this->session->unset_userdata('shipping_id');

				$this->session->unset_userdata('new_register_cus_id');

				redirect(base_url().'shipping/dashboard');

			}

			}

		}





	}

		

}  



//////////////////////////End code for otp on new shipment////////////////////////////



	/////////////////////////Start code for save shipment/////////////////////////////////



	public  function save_shipment()

	{

		$shipping_id=$this->input->post('shipping_id');

		$update_shipping_detail=$this->shipping->update_data_single_cond(array(



			'post_status'=>'draft',

			'status'=>'active'

		),$shipping_id,'id','shipping_details');

		if($this->session->userdata('cus_id'))

		{

			redirect(base_url().'shipping/dashboard');

		}

		else

		{

			$custid=$this->session->userdata('new_register_cus_id');

			$cust= $this->shipping->get_data_with_single_cond($custid,'shipping_customer','customer_id');

			$status=$cust[0]['status'];

			if($status==0)

			{

				redirect(base_url().'login?message=gdkfjhheruchHAJshdjs');

			}



			else

			{

				$this->session->set_userdata('cus_id', $custid);

				redirect(base_url().'shipping/dashboard');

			}

		}

	}



	/////////////////////////End code for save shipment/////////////////////////////////



	public function listing_option_confirm($shipping_id){



		$payment_status=$this->shipping->get_data_with_single_cond($shipping_id,'shipping_details','id');

		if($payment_status[0]['additional_payment']=='paid')

		{

			$update_shipping_detail=$this->shipping->update_data_single_cond(array(



			'post_status'=>'publish'

		),$shipping_id,'id','shipping_details');

			redirect(base_url('shipping/shipper'));

		}

		else

		{



		$data['urgent_priority_fee']=$this->shipping->get_data_with_single_cond('urgent','priority_fee','fee_name');

		$data['featured_priority_fee']=$this->shipping->get_data_with_single_cond('featured','priority_fee','fee_name');

		$data['shippingid']=$shipping_id;

		$this->session->set_userdata('shipping_id',$shipping_id);

		$this->load->view('shipment/listing-options',$data);



		}



	}



	public function shipment_listing1($category,$subcategory)

	{

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/shipment-listing1',$data);

	} 



	public function shipment_generation_shipment_listing1()

	{

		$category_id=$this->input->post('cat_id');

		if($this->input->post('submit'))

		{



		   $count=$this->input->post('num_of_item');

		   	



		   	if($this->session->userdata('cus_id'))

		   	{

		   		$custid= $this->session->userdata('cus_id');

		   	}

		   	else

		   	{

		   		$custid= $this->session->userdata('new_register_cus_id');

		   	}

		   	$shipping_id=$this->shipping->insert_data(array(

		   		'user_id'=>$custid,

		   		'category_id'=>$category_id,

		   		'subcategory_id'=>$this->input->post('subcat_id'),

		   		'table_name'=>'shipment_listing1'

		   		),'shipping_details');

		   	$this->session->set_userdata('shipping_id', $shipping_id);



		   	for($i=1;$i<=$count;$i++)

		   	{



			   	$length=$this->input->post('item_lenth_ft_m'.$i).','.$this->input->post('item_lenth_in_cm'.$i);

			   	$width=$this->input->post('item_width_ft_m'.$i).','.$this->input->post('item_width_in_cm'.$i);

			   	$height=$this->input->post('item_height_ft_m'.$i).','.$this->input->post('item_height_in_cm'.$i);





			   	if ($this->input->server('REQUEST_METHOD') === 'POST')

			    {

			            	

	            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

	            	/*print_r($config['upload_path']);

	            	exit();*/

	                $config['allowed_types'] = '*';

	                $config['max_size'] = 1024*2;

	                $this->load->library('upload', $config);

	                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

	                if($_FILES['file']['name']!='')

	                {

	                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

	                }

	                else

	                {

	                	 $_FILES['file']['name']='';

	                }

	                

	                $this->upload->do_upload('file');

	               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

	                $photo=str_replace(' ','',$_FILES['file']['name']);			                

				}

				

			   	$shipping_vehicle=$this->shipping->insert_data(array(



			   		'shipping_id'=>$shipping_id,

			   		'number_of_items'=>$this->input->post('num_of_item'),

			   		'delivery_title'=>$this->input->post('delivery_title'),

			   		'dimentions_type'=>$this->input->post('imperial_type'.$i),

			   		'length'=>$length,

			   		'width'=>$width,

			   		'height'=>$height,

			   		'weight'=>$this->input->post('item_weight'.$i),

			   		'quantity'=>$this->input->post('item_quantity'.$i),

			   		'item_palletised'=>$this->input->post('item_palletised'),

			   		'item_crated'=>$this->input->post('item_crated'),

			   		'item_stackable'=>$this->input->post('item_stackable'),

			   		'item_image'=>$photo,

			   		'Item_detail'=>$this->input->post('item_detail')

			   		

			   	),'shipment_listing1');

			}



			redirect(base_url()."shipping/newshipment/collection_information");

			}

}  





public function shipment_listing2($category,$subcategory)

	{

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/shipment-listing2',$data);

	} 



public function shipping_generate_shipment_listing2()

	{



		$category_id=$this->input->post('cat_id');



		if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipment_listing2'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



   	$length=$this->input->post('item_lenth_ft_m1').','.$this->input->post('item_lenth_in_cm1');

   	$width=$this->input->post('item_width_ft_m1').','.$this->input->post('item_width_in_cm1');

   	$height=$this->input->post('item_height_ft_m1').','.$this->input->post('item_height_in_cm1');



   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);



                

}



                $shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'dimentions_type'=>$this->input->post('imperial_type1'),

   		'length'=>$length,

   		'width'=>$width,

   		'height'=>$height,

   		'weight'=>$this->input->post('item_weight1'),

   		'item_desc'=>$this->input->post('item_desc'),

   		'item_packaging'=>$this->input->post('item_packaging'),

   		'item_handling_instruction'=>$this->input->post('item_handling_instruction'),

   		'item_image'=>$photo,

   		'item_detail'=>$this->input->post('item_detail')

   	),'shipment_listing2');





	redirect(base_url()."shipping/newshipment/collection_information");

               

	

	}	





public function shipment_listing3($category,$subcategory)

	{

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/shipment-listing3',$data);

	}     

	



public function shipment_generation_shipment_listing3()

	{

		$category_id=$this->input->post('cat_id');

		if($this->input->post('submit'))

   {



   $count=$this->input->post('num_of_item');

   	



   	if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   	$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipment_listing3'

   		),'shipping_details');

   	$this->session->set_userdata('shipping_id', $shipping_id);



   	for($i=1;$i<=$count;$i++)

   	{



   	$length=$this->input->post('item_lenth_ft_m'.$i).','.$this->input->post('item_lenth_in_cm'.$i);

   	$width=$this->input->post('item_width_ft_m'.$i).','.$this->input->post('item_width_in_cm'.$i);

   	$height=$this->input->post('item_height_ft_m'.$i).','.$this->input->post('item_height_in_cm'.$i);





   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);



                

}

	

   	$shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'number_of_items'=>$this->input->post('num_of_item'),

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'boat_model'=>$this->input->post('boat_model'.$i),

   		'dimentions_type'=>$this->input->post('imperial_type'.$i),

   		'length'=>$length,

   		'width'=>$width,

   		'height'=>$height,

   		'weight'=>$this->input->post('item_weight'.$i),

   		'boat_trailer'=>$this->input->post('boat_trailer'.$i),

   		'item_image'=>$photo,

   		'Item_detail'=>$this->input->post('item_detail')

   		

   	),'shipment_listing3');

}



	redirect(base_url()."shipping/newshipment/collection_information");

	}

}



public function shipment_listing4($category,$subcategory)

	{

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/shipment-listing4',$data);

	}





public function shipping_generate_shipment_listing4()

	{



		$category_id=$this->input->post('cat_id');



		if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipment_listing4'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



   	

   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);



                

}



                $shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'item_breed'=>$this->input->post('item_breed'),

   		'item_weight'=>$this->input->post('iten_weight'),

   		'no_of_item'=>$this->input->post('no_of_item'),

   		'item_vaccinations'=>$this->input->post('item_vaccinations'),

   		'item_tag'=>$this->input->post('item_tag'),

   		'item_need'=>$this->input->post('item_need'),

   		'item_corral'=>$this->input->post('item_corral'),

   		'item_image'=>$photo,

   		'item_detail'=>$this->input->post('item_detail')

   	),'shipment_listing4');





	redirect(base_url()."shipping/newshipment/collection_information");

               

	

	}	



	public function shipment_listing5($category,$subcategory)

	{

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/shipment-listing5',$data);

	}





	public function shipment_generation_shipment_listing5()

	{

		$category_id=$this->input->post('cat_id');

		if($this->input->post('submit'))

   {



   $count=$this->input->post('num_of_item');

   	



   	if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   	$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipment_listing5'

   		),'shipping_details');

   	$this->session->set_userdata('shipping_id', $shipping_id);



   	for($i=1;$i<=$count;$i++)

   	{



   	if($this->input->post('pets_carrier'.$i))

   	{

   		$pets_size=$this->input->post('pet_size'.$i);

   	}

   	else

   	{

   		$pets_size='';

   	}



   	if($this->input->post('pets_need'.$i))

   	{

   		$pets_need_text=$this->input->post('pets_nees'.$i);

   	}

   	else

   	{

   		$pets_need_text='';

   	}





   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);



                

}

	

   	$shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'number_of_items'=>$this->input->post('num_of_item'),

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'pets_name'=>$this->input->post('pets_name'.$i),

   		'pets_breed'=>$this->input->post('pets_breed'.$i),

   		'pets_weight'=>$this->input->post('pets_weight'.$i),

   		'pets_vaccinations'=>$this->input->post('pets_vaccinations'.$i),

   		'pets_carrier'=>$this->input->post('pets_carrier'.$i),

   		'pet_size'=>$pets_size,

   		'pets_need'=>$this->input->post('pets_need'.$i),

   		'pets_need_text'=>$pets_need_text,

   		'item_image'=>$photo,

   		'item_detail'=>$this->input->post('item_detail')

   		

   	),'shipment_listing5');

}



	redirect(base_url()."shipping/newshipment/collection_information");

	}

}



public function shipment_listing6($category,$subcategory)

	{

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$data['item_breed']=$this->shipping->select_data('admin_item_breed');

		$this->load->view('shipment/shipment-listing6',$data);

	}



public function shipment_generation_shipment_listing6()

{



	/*print_r($this->input->post('item_breed')[0]);

	exit();*/



	$category_id=$this->input->post('cat_id');

		if($this->input->post('submit'))

   {



   $count=$this->input->post('num_of_item');

   	



   	if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   	$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipment_listing6'

   		),'shipping_details');

   	$this->session->set_userdata('shipping_id', $shipping_id);



   	for($i=0;$i<$count;$i++)

   	{





   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);



                

}

	

   	$shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'number_of_items'=>$this->input->post('num_of_item'),

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'item_breed'=>$this->input->post('item_breed')[$i],

   		'item_weight'=>$this->input->post('item_weight')[$i],

   		'item_tall'=>$this->input->post('item_tall')[$i],

   		'item_image'=>$photo,

   		'item_detail'=>$this->input->post('item_detail')

   		

   	),'shipment_listing6');

}



	redirect(base_url()."shipping/newshipment/collection_information");

	}





}	



public function shipment_listing7($category,$subcategory)

	{

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$this->load->view('shipment/shipment-listing7',$data);

	}





	public function shipping_generate_shipment_listing7()

	{



		$category_id=$this->input->post('cat_id');



		if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipment_listing7'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



   	$length=$this->input->post('item_lenth_ft_m1').','.$this->input->post('item_lenth_in_cm1');

   	$width=$this->input->post('item_width_ft_m1').','.$this->input->post('item_width_in_cm1');

   	$height=$this->input->post('item_height_ft_m1').','.$this->input->post('item_height_in_cm1');



   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);



                

}



                $shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'dimentions_type'=>$this->input->post('imperial_type1'),

   		'length'=>$length,

   		'width'=>$width,

   		'height'=>$height,

   		'weight'=>$this->input->post('item_weight1'),

   		'item_slate'=>$this->input->post('item_slate'),

   		'special_handling'=>$this->input->post('special_handaling'),

   		'item_image'=>$photo,

   		'item_detail'=>$this->input->post('item_detail')

   	),'shipment_listing7');





	redirect(base_url()."shipping/newshipment/collection_information");

               

	

	}



	public function shipment_listing8($category,$subcategory)

	{

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$data['vertical_piano']=$this->shipping->select_data('vertical_piano_type');

		$data['horizental_piano']=$this->shipping->select_data('horizental_piano_type');

		$this->load->view('shipment/shipment-listing8',$data);

	}







	public function shipping_generate_shipment_listing8()

	{



		$category_id=$this->input->post('cat_id');



		if($this->session->userdata('cus_id'))

   	{

   		$custid= $this->session->userdata('cus_id');

   	}

   	else

   	{

   	$custid= $this->session->userdata('new_register_cus_id');

   }

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipment_listing8'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



   	$length=$this->input->post('item_lenth_ft_m1').','.$this->input->post('item_lenth_in_cm1');

   	$width=$this->input->post('item_width_ft_m1').','.$this->input->post('item_width_in_cm1');

   	$height=$this->input->post('item_height_ft_m1').','.$this->input->post('item_height_in_cm1');



   	if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);



                

}



                $shipping_vehicle=$this->shipping->insert_data(array(



   		'shipping_id'=>$shipping_id,

   		'delivery_title'=>$this->input->post('delivery_title'),

   		'piano_type'=>$this->input->post('piano_type'),

   		'piano_vertical_type'=>$this->input->post('vertical_piano'),

   		'piano_horizental_type'=>$this->input->post('horizental_piano'),

   		'piano_caster'=>$this->input->post('piano_caster'),

   		'dimentions_type'=>$this->input->post('imperial_type1'),

   		'length'=>$length,

   		'width'=>$width,

   		'height'=>$height,

   		'weight'=>$this->input->post('item_weight1'),

   		'piano_instruction'=>$this->input->post('piano_instruction'),

   		'item_image'=>$photo,

   		'item_detail'=>$this->input->post('item_detail')

   	),'shipment_listing8');





	redirect(base_url()."shipping/newshipment/collection_information");

               

	

	}



	public function shipment_listing9($category,$subcategory)

	{

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$data['dinning_room']=$this->shipping->select_data('admin_dinning_room');

		$data['living_room']=$this->shipping->select_data('admin_living_room');

		$data['bedroom']=$this->shipping->select_data('admin_bedroom');

		$data['kitchen']=$this->shipping->select_data('admin_kitchen');

		$data['home_office']=$this->shipping->select_data('admin_home_office');

		$data['garage']=$this->shipping->select_data('admin_garage');

		$data['outdoors']=$this->shipping->select_data('admin_outdoors');

		$data['miscellaneous']=$this->shipping->select_data('admin_miscellaneous');

		$data['boxes']=$this->shipping->select_data('admin_boxes');

		$this->load->view('shipment/shipment-listing9',$data);

	}



	public function shipping_generate_shipment_listing9()

	{

		$dinning_arr=$this->input->post('dining');

		$living_arr=$this->input->post('living');

		$bedroom_arr=$this->input->post('bedroom');

		$kitchen_arr=$this->input->post('kitchen');

		$home_arr=$this->input->post('home');

		$garage_arr=$this->input->post('garage');

		$outdoor_arr=$this->input->post('outdoor');

		$misc_arr=$this->input->post('misc');

		$box_arr=$this->input->post('box');



		$dinning_room=$this->shipping->select_data('admin_dinning_room');

		$living_room=$this->shipping->select_data('admin_living_room');

		$bedroom=$this->shipping->select_data('admin_bedroom');

		$kitchen=$this->shipping->select_data('admin_kitchen');

		$home_office=$this->shipping->select_data('admin_home_office');

		$garage=$this->shipping->select_data('admin_garage');

		$outdoors=$this->shipping->select_data('admin_outdoors');

		$miscellaneous=$this->shipping->select_data('admin_miscellaneous');

		$boxes=$this->shipping->select_data('admin_boxes');



		$dining_val='0';

		$living_val='0';

		$bedroom_val='0';

		$kitchen_val='0';

		$home_val='0';

		$garage_val='0';

		$outdoor_val='0';

		$misc_val='0';

		$box_val='0';

		$k=0;

		foreach($dinning_arr as $value)

  		{

  			if($value!="")

  			{

  				$dining_val.=','.$dinning_room[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}

  		$k=0;

  		foreach($living_arr as $value)

  		{

  			if($value!="")

  			{

  				$living_val.=','.$living_room[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}

  		$k=0;

  		foreach($bedroom_arr as $value)

  		{

  			if($value!="")

  			{

  				$bedroom_val.=','.$bedroom[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}

  		$k=0;

  		foreach($kitchen_arr as $value)

  		{

  			if($value!="")

  			{

  				$kitchen_val.=','.$kitchen[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}

  		$k=0;

  		foreach($home_arr as $value)

  		{

  			if($value!="")

  			{

  				$home_val.=','.$home_office[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}

  		$k=0;

  		foreach($garage_arr as $value)

  		{

  			if($value!="")

  			{

  				$garage_val.=','.$garage[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}

  		$k=0;

  		foreach($outdoor_arr as $value)

  		{

  			if($value!="")

  			{

  				$outdoor_val.=','.$outdoors[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}

  		$k=0;

  		foreach($misc_arr as $value)

  		{

  			if($value!="")

  			{

  				$misc_val.=','.$miscellaneous[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}

  		$k=0;

  		foreach($box_arr as $value)

  		{

  			if($value!="")

  			{

  				$box_val.=','.$boxes[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}



  		$category_id=$this->input->post('cat_id');



		if($this->session->userdata('cus_id'))

   		{

   		$custid= $this->session->userdata('cus_id');

   		}

	   	else

	   	{

	   	$custid= $this->session->userdata('new_register_cus_id');

	  	}

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipment_listing9'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);

  		

  		if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);



                

}



                $shipping_vehicle=$this->shipping->insert_data(array(



		   		'shipping_id'=>$shipping_id,

		   		'residence_type'=>$this->input->post('residence_type'),

		   		'no_of_room'=>$this->input->post('no_of_room'),

		   		'collection_story'=>$this->input->post('collection_story'),

		   		'delivery_story'=>$this->input->post('delivery_story'),

		   		'delivery_title'=>$this->input->post('delivery_title'),

		   		'dining_room'=>$dining_val,

		   		'living_room'=>$living_val,

		   		'bedroom'=>$bedroom_val,

		   		'kitchen'=>$kitchen_val,

		   		'home_office'=>$home_val,

		   		'garage'=>$garage_val,

		   		'outdoor'=>$outdoor_val,

		   		'miscellaneous'=>$misc_val,

		   		'boxes'=>$box_val,

		   		'item_image'=>$photo,

		   		'item_detail'=>$this->input->post('item_detail')

		   	),'shipment_listing9');





	redirect(base_url()."shipping/newshipment/collection_information");

  		

	}	





	public function shipment_listing10($category,$subcategory)

	{

		$data['category_id']=$category;

		$data['subcategory_id']=$subcategory;

		$data['general']=$this->shipping->select_data('admin_general_shipment_inventory');

		$data['equipment']=$this->shipping->select_data('admin_equipment_shipment_inventory');

		$data['boxes']=$this->shipping->select_data('admin_boxes');

		$this->load->view('shipment/shipment-listing10',$data);

	}



	public function shipping_generate_shipment_listing10()

	{

		

		$general_arr=$this->input->post('general');

		$equipment_arr=$this->input->post('equipment');

		$box_arr=$this->input->post('box');



		

		$general=$this->shipping->select_data('admin_general_shipment_inventory');

		$equipment=$this->shipping->select_data('admin_equipment_shipment_inventory');

		$boxes=$this->shipping->select_data('admin_boxes');



		

		$general_val='0';

		$equipment_val='0';

		$box_val='0';

		

  		$k=0;

  		foreach($general_arr as $value)

  		{

  			if($value!="")

  			{

  				$general_val.=','.$general[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}

  		$k=0;

  		foreach($equipment_arr as $value)

  		{

  			if($value!="")

  			{

  				$equipment_val.=','.$equipment[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}

  		$k=0;

  		foreach($box_arr as $value)

  		{

  			if($value!="")

  			{

  				$box_val.=','.$boxes[$k]['id'].'//'.$value;

  			}

      		

      		$k++;

  		}



  		$category_id=$this->input->post('cat_id');



		if($this->session->userdata('cus_id'))

   		{

   		$custid= $this->session->userdata('cus_id');

   		}

	   	else

	   	{

	   	$custid= $this->session->userdata('new_register_cus_id');

	  	}

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$category_id,

   		'subcategory_id'=>$this->input->post('subcat_id'),

   		'table_name'=>'shipment_listing10'

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);

  		

  		if ($this->input->server('REQUEST_METHOD') === 'POST')

            {

            	

            	$config['upload_path'] = FCPATH.'uploads/'.$category_id.'/';

            	/*print_r($config['upload_path']);

            	exit();*/

                $config['allowed_types'] = '*';

                $config['max_size'] = 1024*2;

                $this->load->library('upload', $config);

                //$_FILES['file']['name']= rand().$_FILES['file']['name'];

                if($_FILES['file']['name']!='')

                {

                $_FILES['file']['name']= rand().str_replace(' ','',$_FILES['file']['name']);

                }

                else

                {

                	 $_FILES['file']['name']='';

                }

                

                $this->upload->do_upload('file');

               // move_uploaded_file ( $_FILES['file']['name'] , $config['upload_path'] );

                $photo=str_replace(' ','',$_FILES['file']['name']);



                

}



                $shipping_vehicle=$this->shipping->insert_data(array(



		   		'shipping_id'=>$shipping_id,

		   		'collection_floor'=>$this->input->post('collection_floor'),

		   		'delivery_floor'=>$this->input->post('delivery_floor'),

		   		'lift_elevator'=>$this->input->post('lift_elevator'),

		   		'service_elevator'=>$this->input->post('service_elevator'),

		   		'cubicles_disassembled'=>$this->input->post('cubicles_disassembled'),

		   		'cubicles_assembled'=>$this->input->post('cubicles_assembled'),

		   		'delivery_title'=>$this->input->post('delivery_title'),

		   		'general_shipment_inventory'=>$general_val,

		   		'equipment_shipment_inventory'=>$equipment_val,

		   		'boxes'=>$box_val,

		   		'item_image'=>$photo,

		   		'item_detail'=>$this->input->post('item_detail')

		   	),'shipment_listing10');





	redirect(base_url()."shipping/newshipment/collection_information");

  		

	}





	//////////////////////////////////Start Code of Ramesh/////////////////////////////////////////////



	public function delivery_page($category_id,$subcategory_id){

            //$this->load->model('pagemodel');

            $data['page']=$this->pagemodel->pagedetailget($id=0,'delivery_page');

            $data['category_id']=$category_id;

            $data['subcategory_id']=$subcategory_id;

            $this->load->view('shipment/delivery_page',$data);

        }



	public function deliveryPageDeatais()

        {

       // echo $image;

            if($this->session->userdata('cus_id'))

   		{

   		$custid= $this->session->userdata('cus_id');

   		}

	   	else

	   	{

	   	$custid= $this->session->userdata('new_register_cus_id');

	  	}

        $data['user_id']=$custid;

        $data['brief_desc']=$this->input->post('desc');

        $data['length_ft']=$this->input->post('lenm');

        $data['length_in']=$this->input->post('lencm');

        $data['width_ft']=$this->input->post('widm');

        $data['width_in']=$this->input->post('widcm');

        $data['height_ft']=$this->input->post('heim');

        $data['height_in']=$this->input->post('heicm');

        $data['weight']=$this->input->post('weikg');

        $data['qty']=$this->input->post('qty');

        $data['details']=$this->input->post('details'); 

        $data['images']=implode(',',$this->input->post('image'));//$_FILES['files']['name'];

        $data['function_name']=$this->input->post('function_name');

        //$function_name=$this->input->post('function_name');

        if($data['images']=='')

        {

        unset($data['images']);

        }

        unset($data['upload_data']);

        if($this->pagemodel->pagedetailinsertupdate($data,$this->input->post('id'),$data['function_name']))

        {

        $data['pagedetail']=$this->pagemodel->pagedetailget($id=0,$data['function_name']);

        if($data['pagedetail'])

        {

        echo  json_encode($data['pagedetail']);

        die;

        $data1['success']='success';

        $json=$data1;

        }

        else

        {

        echo $json=array('error1'=>'fail') ;; 

        }

        }

        else 

        {

        $json=array('error'=>'Please try again.') ;

        }

        echo json_encode($json);

        }

       public function deletedetail()

       {

       if($this->pagemodel->deletedetail($this->input->post('id'),$this->input->post('function_name')))

       {

       echo json_encode(array('success'=>1));

       }

       }

       public function getdetail()

       {

       $data['pagedetail']=$this->pagemodel->pagedetailget($this->input->post('id'),$this->input->post('function_name')); 

       if($data['pagedetail'])

       {

        echo  json_encode($data['pagedetail']);

        die;

        $data1['success']='success';

        $json=$data1;

        }

        }

        public function lastimage($images)

        {

        if($images!=''){

        $imagearr=explode(',',$images);

        return end($imagearr);

        }

        else 

        {

         return ' '; 

        }

        }

        public function countimages($images)

        {

        if($images!=''){

        $imagearr=explode(',',$images); 

        return count($imagearr);

        }

        else

        {

            return 0;

        }

        }

        function furniture()

        {

        	$cat_id=$this->input->post('cat_id');

        	$subcat_id=$this->input->post('subcat_id');

            $tablename="shipping_item_furniture";



        if($this->session->userdata('cus_id'))

   		{

   		$custid= $this->session->userdata('cus_id');

   		}

	   	else

	   	{

	   	$custid= $this->session->userdata('new_register_cus_id');

	  	}

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$cat_id,

   		'subcategory_id'=>$subcat_id,

   		'table_name'=> $tablename

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);





            $data['detail']=$this->pagemodel->pagedetailget($id=0,'delivery_page');

            foreach($data['detail'] as $value):

                $item['user_id']=$value->user_id;

                $item['delivery_title']=$this->input->post('title');

                $item['item_detail']=$this->input->post('additional');

            	$item['shipping_id']=$shipping_id;

               $item['delivery_desc']=$value->brief_desc;

               $item['delivery_detail']=$value->details;

              // $item['shipping_id']=$shipping_id;

              // $item['delivery_title']=$value->brief_desc;

            $item['length']=str_replace('ft','',str_replace('m','',$value->length_ft)).','.str_replace('in','',str_replace('cm','',$value->length_in));

            $item['width']=str_replace('ft','',str_replace('m','',$value->width_ft)).','.str_replace('in','',str_replace('cm','',$value->width_in));

            $item['height']=str_replace('ft','',str_replace('m','',$value->height_ft)).','.str_replace('in','',str_replace('cm','',$value->height_in));

               $item['weight']=$value->weight;

               $item['qty']=$value->qty;

               //$item['item_detail']=$value->details;

               $item['item_image']=$value->images;

               

               $this->pagemodel->furniture($tablename,$item);

            endforeach;

            $this->pagemodel->deleteadditem('delivery_page');

            $this->session->set_flashdata('success','Successfully submitted.');

            //redirect(base_url().'shipping/newshipment/delivery_page/'.$cat_id.'/'.$subcat_id);

           // print_r($this->session->userdata('cus_id'));

            redirect(base_url()."shipping/newshipment/collection_information");

            

        }

        

        function upload($folder)

        {

        if (isset($_FILES['files']))

        {

        $myFile = $_FILES['files'];

        $fileCount = count($myFile["name"]);

        for($i = 0; $i < $fileCount; $i++)

        {			

        $path = FCPATH."uploads/$folder/";

        $imagename = str_replace(' ','',$myFile['name'][$i]);

        $tmpname = $myFile['tmp_name'][$i];

        $image_name=explode(".",$imagename);

        $img_name=$image_name[0]."-".time().".".$image_name[1];

        $target = $path.basename($img_name) ;

        if(move_uploaded_file($tmpname,$target))

        {

               $img[]=$img_name;

        }

        }

        echo json_encode($img);

        }

        }

        function unsetimage($folder)

        {

            unlink(FCPATH."uploads/$folder/".$this->input->post('img'));

            if($this->input->post('id')!=0){

                $this->pagemodel->unlinkimage($this->input->post('id'),$this->input->post('img'),$this->input->post('function_name'));

            }

            echo json_encode(array('success'=>1));

        }

        

        function car_truck_part($category_id,$subcategory_id)

        {

          $data['page']=$this->pagemodel->pagedetailget($id=0,'car_truck_part');

          $data['category_id']=$category_id;

          $data['subcategory_id']=$subcategory_id;

          $this->load->view('shipment/car_truck_part',$data);

        }

        function car_part()

        {



        	$cat_id=$this->input->post('cat_id');

        	$subcat_id=$this->input->post('subcat_id');



            $tablename="shipping_item_car_truck";

            $function_name="car_truck_part";



         if($this->session->userdata('cus_id'))

   		{

   		$custid= $this->session->userdata('cus_id');

   		}

	   	else

	   	{

	   	$custid= $this->session->userdata('new_register_cus_id');

	  	}

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$cat_id,

   		'subcategory_id'=>$subcat_id,

   		'table_name'=> $tablename

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



            $data['detail']=$this->pagemodel->pagedetailget($id=0,$function_name);

            foreach($data['detail'] as $value):

               $item['delivery_title']=$this->input->post('title');

                $item['item_detail']=$this->input->post('additional');

            	$item['shipping_id']=$shipping_id;

               $item['delivery_desc']=$value->brief_desc;

               $item['delivery_detail']=$value->details;

               $item['item_image']=$value->images;

              

               $this->pagemodel->furniture($tablename,$item);

            endforeach;

            $this->pagemodel->deleteadditem($function_name);

            $this->session->set_flashdata('success','Successfully submitted.');

            //redirect(base_url().'shipping/newshipment/car_truck_part/'.$cat_id.'/'.$subcat_id);

            redirect(base_url()."shipping/newshipment/collection_information");      

        }

        

        function motor_power($category_id,$subcategory_id)

        {

         $data['page']=$this->pagemodel->pagedetailget($id=0,'motor_power');

         $data['category_id']=$category_id;

          $data['subcategory_id']=$subcategory_id;

         $this->load->view('shipment/motor_power',$data);   

        }

        function motor_power_support()

        {



        	$cat_id=$this->input->post('cat_id');

        	$subcat_id=$this->input->post('subcat_id');



            $tablename="shipping_item_motor_power";

            $function_name="motor_power";



            if($this->session->userdata('cus_id'))

   		{

   		$custid= $this->session->userdata('cus_id');

   		}

	   	else

	   	{

	   	$custid= $this->session->userdata('new_register_cus_id');

	  	}

   		$shipping_id=$this->shipping->insert_data(array(

   		'user_id'=>$custid,

   		'category_id'=>$cat_id,

   		'subcategory_id'=>$subcat_id,

   		'table_name'=> $tablename

   		),'shipping_details');

   		$this->session->set_userdata('shipping_id', $shipping_id);



            $data['detail']=$this->pagemodel->pagedetailget($id=0,$function_name);

            foreach($data['detail'] as $value):

                $item['delivery_title']=$this->input->post('title');

                $item['item_detail']=$this->input->post('additional');

            	$item['shipping_id']=$shipping_id;

               $item['delivery_desc']=$value->brief_desc;

               $item['delivery_detail']=$value->details;

               $item['item_image']=$value->images;

              

               $this->pagemodel->furniture($tablename,$item);

            endforeach;

            $this->pagemodel->deleteadditem($function_name);

            $this->session->set_flashdata('success','Successfully submitted.');

           // redirect(base_url().'shipping/newshipment/motor_power/'.$cat_id.'/'.$subcat_id);  

           redirect(base_url()."shipping/newshipment/collection_information");   

        }



	//////////////////////////////////End code of Ramesh///////////////////////////////////////////////

        function title($ship_id)

{

    $shipping_data=$this->shipping->get_data_with_single_cond($ship_id,'shipping_details','id');



    $table_name=$shipping_data[0]['table_name'];

    $shipping_details=$this->finddeliveries->get_shipment_by_shiiping_id($ship_id,$table_name);

//    print_r($shipping_details);

//    die;

    if($shipping_details[0]['title']!='') {return substr(str_replace(' ','-',$shipping_details[0]['title']),0,10);}elseif($shipping_details[0]['delivery_title']!=''){return substr(str_replace(' ','-',$shipping_details[0]['delivery_title']),0,10);}else {return substr(str_replace(' ','-',$shipping_details[0]['description']), 0,10);}

}

}	

