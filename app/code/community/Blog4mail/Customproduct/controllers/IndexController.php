<?php
class Blog4mail_Customproduct_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    $this->loadLayout();
    $this->renderLayout();
    }
	
	protected function set_file_name($new_name = '') { // this 'conversion' is used for unique/new filenames 
		if ($this->rename_file) {
			if ($this->the_file == '') return;
			$name = ($new_name == '') ? uniqid() : $new_name;
			sleep(3);
			$name = $name.$this->get_extension($this->the_file);
		} else {
			$name = str_replace(' ', '_', $this->the_file); // space will result in problems on linux systems
		}
		return $name;
	}

public function uploadAction(){
	

	$bsdir = Mage::getBaseDir('base');
	

	$dest_dir = $bsdir.'/media/finalimages/upload/';
        $source = $_FILES['userfile']['tmp_name'];
        $filename = $_FILES['userfile']['name'];
	$newfile = uniqid().$filename;
	move_uploaded_file($source,$dest_dir.$newfile);
	print $newfile;
	
}	

    public function ajaxoptionsAction()
    {
	$pid=$_REQUEST['product_id'];
	$product = Mage::getModel('catalog/product')->load($pid);
	$html=$this->getProductOptionsHtml($product);
	print $html; 
    }

    public function getProductOptionsHtml(Mage_Catalog_Model_Product $product)
    {

	$x = Mage::app()->getLayout()->createBlock("Mage_Core_Block_Template");
        
        $x->setTemplate("catalog/product/view/options/js.phtml");	
        $a=$x->toHtml();

       $y = Mage::app()->getLayout()->createBlock("Mage_Catalog_Block_Product_View_Options");        
       $y->setProduct($product);
       $y->setTemplate("catalog/product/view/options.phtml");
       $b=$y->toHtml();

       $z = Mage::app()->getLayout()->createBlock("Mage_Core_Block_Html_Calendar");        
       $z->setProduct($product);
       $z->setTemplate("page/js/calendar.phtml");
       $c = $z->toHtml();


        $blockOption = Mage::app()->getLayout()->createBlock("Mage_Catalog_Block_Product_View_Options");
        $blockOption->addOptionRenderer("default","catalog/product_view_options_type_default","catalog/product/view/options/type/default.phtml");        
        $blockOption->addOptionRenderer("text","catalog/product_view_options_type_text","catalog/product/view/options/type/text.phtml");
        $blockOption->addOptionRenderer("file","catalog/product_view_options_type_file","catalog/product/view/options/type/file.phtml");
        $blockOption->addOptionRenderer("select","catalog/product_view_options_type_select","catalog/product/view/options/type/select.phtml");
        $blockOption->addOptionRenderer("date","catalog/product_view_options_type_date","catalog/product/view/options/type/date.phtml") ;
        $blockOptionsHtml = null;

	

         if($product->getTypeId()=="simple"||$product->getTypeId()=="virtual"||$product->getTypeId()=="configurable")
         {
        
	
		
            $blockOption->setProduct($product);
            if($product->getOptions())
            {
                foreach ($product->getOptions() as $o)
                {
		    $h=$blockOption->getOptionHtml($o);			    
	
                    $blockOptionsHtml .= $blockOption->getOptionHtml($o);
                };
            }
         }

	

         if($product->getTypeId()=="configurable")
         {
            $blockViewType = Mage::app()->getLayout()->createBlock("Mage_Catalog_Block_Product_View_Type_Configurable");
            $blockViewType->setProduct($product);
            //$blockViewType->setTemplate("inchoo_catalog/product/view/type/options/configurable.phtml");
	    $blockViewType->setTemplate("mage_catalog/product/view/type/options/configurable.phtml");
            $blockOptionsHtml .= $blockViewType->toHtml();
         }
         return $a.$b.$blockOptionsHtml.$c;
    }

   public function setMyCustomOption($productId, $title, array $optionData, array $values = array())
    {
            $product = Mage::getModel('catalog/product')->load($productId);

            $data = array_merge( $optionData, array(
                                        'product_id'    => (int)$productId,
                                        'title'     => $title,
                                        'values'    => $values,
                                ));
	    

		//$data=$optionData;
           
            $product->setHasOptions(1)->save();                                    
            $option = Mage::getModel('catalog/product_option')->setData($data)->setProduct($product)->save();

            return $option;
    }


public function textAction(){
	$spath1 = $_SERVER['DOCUMENT_ROOT'];

	$spath = $_SERVER['SCRIPT_FILENAME'];
        

	$spath = substr($spath,0,-9);
	$ex=explode($spath1,$spath);

	$host=$_SERVER['HTTP_HOST'];

	$webpath = "http://".$host.$ex[1].'media/';
        //print $webpath;
	//return;

	$mytext = $_REQUEST['mytext'];
        $color=$_REQUEST['color'];

if($color==''){
$color = "blue";
}

	/* Create some objects */
	$image = new Imagick();
	$draw = new ImagickDraw();

	$pixel = new ImagickPixel('white');

	$transparent = new ImagickPixel('white');
	
       
	$w=strlen($mytext)*10;

	/* New image */
	$image->newImage($w,15,$pixel);
	$image->paintTransparentImage($transparent, 0, 10);

	/* Black text */
	$draw->setFillColor($color);

	/* Font properties */
	$draw->setFont('Bookman-DemiItalic');
	$draw->setFontSize(11);

	/* Create text */
	
	$image->annotateImage($draw,5,10,0,$mytext);

	/* Give image a format */

	$x = md5($mytext.time()).'.png';
	$filename = $spath.'media/'.$x;
	

	$image->setImageFormat('png');
	$image->writeImages($filename,true); 

	echo $webpath.$x;

	//echo $filename;
	/* Output the image with headers */
	//header('Content-type: image/png');
	//echo $image;
}

public function imageAction(){



$baseurl1 = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);

$bsdir = Mage::getBaseDir('base');
$product_id = $_REQUEST['product'];
$filename = $bsdir.'/media/finalimages/'.md5(time().$product).'.jpg';

$filename1=$baseurl1.'media/finalimages/'.md5(time().$product).'.jpg';

$mainimage = $_REQUEST['mainimage'];

//convert dragon.gif    -resize 64x64  resize_dragon.gif





$image = $_REQUEST['image'];


$check_size = new Imagick($image);
$size_info = $check_size->identifyImage();
$bg_image_width=$size_info['geometry']['width']; 
$bg_image_height=$size_info['geometry']['height'];

system("convert $mainimage -resize $bg_image_width×$bg_image_height $mainimage");

system("media/finalimages/sh1.sh $mainimage $mainimage");


$oldprice = trim(str_replace('$',' ',$_REQUEST['oldprice']));

$w = $_REQUEST['width'];
$h = $_REQUEST['height'];


/*$pos_left = $_REQUEST['left']=150;
$pos_top = $_REQUEST['top']=150;
*/

$pos_left = $_REQUEST['left'];
$pos_top = $_REQUEST['top'];

$pos_left_text=$_REQUEST['left_text'];
$pos_top_text=$_REQUEST['top_text'];

$w_text=$_REQUEST['w_text'];
$h_text=$_REQUEST['h_text'];

$textimage=$_REQUEST['textimage'];

//center
//system("/var/www/cylinderize -m vertical -r 73 -l 120 -w 90 -p 5 -n 94 -e 2 -s 200 -a 100 -v background -b none -f none -o -15-15 /var/www/yellowpage/image1.jpeg /var/www/yellowpage/mug1.jpeg /var/www/yellowpage/im4.jpeg");

//Right
//system("/var/www/cylinderize -m vertical -r 73 -l 100 -w 100 -p 5 -n 94 -e 2 -s 200 -a -100 -v background -b none -f none -o +24+10 /var/www/yellowpage/image1.jpeg /var/www/yellowpage/mug1.jpeg /var/www/yellowpage/im4.jpeg");

//Left
//system("/var/www/cylinderize -m vertical -r 73 -l 100 -w 100 -p 5 -n 94 -e 2 -s 200 -a 100 -v background -b none -f none -o -24+10 /var/www/yellowpage/image1.jpeg /var/www/yellowpage/mug1.jpeg /var/www/yellowpage/im4.jpeg");

//system("/var/www/cylinderize -m vertical -r 73 -l 150 -w 150 -p 5 -n 94 -e 2 -s 200 -a -100 -v background -b none -f none -o -15+15 /var/www/yellowpage/image1.jpeg /var/www/yellowpage/mug1.jpeg /var/www/yellowpage/im4.jpeg");

$foundimage = strpos($mainimage,'bgimage.png');
$foundtextimage = strpos($textimage,'textimage.png');

if ($foundimage !== false && $foundtextimage !== false) {
	$ar=array();
	$ar['filename']=0;
	$ar['opthtml']=0;
	print json_encode($ar);
	return;
}

$mug = new Imagick($image);

if ($foundimage === false) {
$im = new Imagick($mainimage);
$im->scaleImage($w,$h);
$im->setImageFormat('jpeg');
$im->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
$im->setImageMatte(true);

//$im->setImageBackgroundColor('red');

$height = $im->getImageHeight();

$mug->compositeImage($im, $im->getImageCompose(),$pos_left, $pos_top);
} 
//$im = new Imagick($bsdir.'/media/image1.jpeg');
//$mug = new Imagick($bsdir.'/media/mug1.jpeg');




if($foundtextimage === false) {
 $im1 = new Imagick($textimage);
 $mug->compositeImage($im1, $im1->getImageCompose(),$pos_left_text,$pos_top_text); 
} 

	$persp="$mainimage,$w,$h,$pos_left,$pos_top|$textimage,$w_text,$h_text,$pos_left_text,$pos_top_text";
	

        $mug->writeImages($filename,true); 

	$product = Mage::getModel('catalog/product')->load($product_id);
	$magentoid = $product->getId();
	

	$ctype=$product->getcustomproducttype();
	
        $sku=$product->getSku();
	$sku = $sku.'custom'.time();

	

	if($magentoid &&  $ctype == 'designed'){		
		// UPDATE RECORDS
		$data['id']=$product->getId();
		$data['name']=$product->getName();
		$data['desc']=$product->getDescription();
		$data['shortdesc']=$product->getShortDescription();                
		$data['sku']=$sku;                
		$data['weight']=$product->getWeight();
		$data['status']=$product->getStatus();		
		
		//$price = $product->getPrice();
		//$data['price'] = $price;
		$data['price'] = round($oldprice+($oldprice*5/100),2);
		$data['designed']=$product->getcustomproducttype();
		$data['image']=$filename;
		$data['persp']=$persp;

		$id=$this->_updateShopsProduct($data,$designedProduct='true',$magentoid);
		$opthtml=$this->getProductOptionsHtml($product);
	} else {
		//print 'create';
	        //return;
		// INSERT RECORDS
		$data['id']=$product->getId();
		$data['name']=$product->getName();
		$data['desc']=$product->getDescription();
		$data['shortdesc']=$product->getShortDescription();
		$data['sku'] = $sku;
		$data['weight']=$product->getWeight();
		$data['status']=$product->getStatus();
		//$price = $product->getPrice() + ($product->getPrice()*10/100);
		//$data['price'] = $price;
		$data['price'] = round($oldprice + ($oldprice*5/100),2);
		$data['designed']='designed';
		$data['image']=$filename;
		$data['persp']=$persp;
		$id = $this->createShopsProduct($data,$designedProduct='true');
		$opthtml=$this->getProductOptionsHtml($product);
	}



$ar=array();
$ar['filename']=$filename1;
$ar['opthtml']=$opthtml;
$ar['product_id']=$id;
$ar['price']=$data['price'];
print json_encode($ar);
}



protected function _updateShopsProduct($data,$designedProduct,$magentoid){
   $serverurl = $data['image'];
		try{
			$_product = Mage::getModel('catalog/product');
        	        $product = $_product->load($magentoid);
			
			// 
                        // foreach ($product->getOptions() as $opt)
			// {   
		    	//   $opt->delete();
			// }
			//   $product->save();
			
		$store = Mage::app()->getStore(Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID);
	        $product->setStoreId($store->getId());

	        	/*
				$websiteIds = array();


				if (Mage::app()->isSingleStoreMode()) {
					$websites = Mage::app()->getWebsites();
					foreach($websites as $curWebsite){
						$websiteIds[] = $curWebsite->getId();
					}

				}
				else {
				

					$website = Mage::app()->getWebsite();
					$websiteIds[] = $website->getId();
				}

				$product->setWebsiteIds($websiteIds);
			*/
	
			$product->setName($data['name']);
			$product->setDescription($data['desc']);
			$product->setShortDescription($data['shortdesc']);
			$product->setPrice($data['price']);  
			$product->setWeight($data['weight']);
			$product->setSku($data['sku']);
			$product->setcustomproducttype($designedProduct);
			$product->setcustomperspective($data['persp']);
			
			        $mediaApi = Mage::getModel("catalog/product_attribute_media_api");
				$items = $mediaApi->items($product->getId());
				
				foreach($items as $item){
				  $mediaApi->remove($product->getId(), $item['file']);
				}

			$product->addImageToMediaGallery($serverurl,array('thumbnail','small_image','image'),false,false);
		
			Mage::dispatchEvent('catalog_product_prepare_save',array('product' => $product, 'request' => null));
			$product->save();		
			return $magentoid;
		}
		catch (Exception $e) {
	    	Mage::logException($e);
	        return null;
		}
	}



	
public function customdesignAction(){

		$ar['alias'] = 'Mugs - Good';
		$ar['description']='Mugs - Good';
		$ar['shortDescription']='Mugs - Good';
		$ar['MSRP']='10.50';
		$ar['weight']=1;
		$ar['_id']=2;
		$ar['clientSku']='A1';

		

                $en=json_encode($ar);
                 

		$data = json_decode($en);
		

		$this->createShopsProduct($data, $designedProduct='true');
		

		print 'Helo';
}







public function createShopsProduct($data, $designedProduct='true'){


// Add or create new product magento through code with custom options, image and manage stock
// mmoves image and assigns to all three types image, small_image and thumbnail_image
//require_once 'app/Mage.php';
//umask(0);

//$app = Mage::app();
//$session = Mage::getSingleton('customer/session', array('name'=>'frontend'));



//create a new product

try {

//$serverurl = $_SERVER['DOCUMENT_ROOT'].'/yellowpage/image6.jpeg';
$serverurl=$data['image'];
$product = Mage::getModel('catalog/product');

$stockData = $product->getStockData();

$stockData['qty'] = 1;
$stockData['is_in_stock'] = 1;
$stockData['manage_stock'] = 1;
$stockData['use_config_manage_stock'] = 0;

$stockData['inventory_use_config_min_sale_qty'] = 1;
$stockData['inventory_use_config_max_sale_qty'] = 1;

$options = array();


$options['Colors']['opt']['type']='radio';
$options['Colors']['opt']['is_require']=0;
$options['Colors']['opt']['price']=0;
$options['Colors']['opt']['price_type']='fixed';


$options['Colors']['values'][0]['title']='Red';
$options['Colors']['values'][0]['price']=10.00;
$options['Colors']['values'][0]['price_type']='fixed';

$options['Colors']['values'][1]['title']='Green';
$options['Colors']['values'][1]['price']=11.00;
$options['Colors']['values'][1]['price_type']='fixed';

$options['Colors']['values'][2]['title']='Blue';
$options['Colors']['values'][2]['price']=12.00;
$options['Colors']['values'][2]['price_type']='fixed';


$options['Sizes']['opt']['type']='radio';
$options['Sizes']['opt']['is_require']=0;
$options['Sizes']['opt']['price']=0;
$options['Sizes']['opt']['price_type']='fixed';

$options['Sizes']['values'][0]['title']='L';
$options['Sizes']['values'][0]['price']=9.00;
$options['Sizes']['values'][0]['price_type']='fixed';

$options['Sizes']['values'][1]['title']='XL';
$options['Sizes']['values'][1]['price']=15.00;
$options['Sizes']['values'][1]['price_type']='fixed';



/*$options['title'] = array(
'title' => 'Colors',
'type' => 'radio',
'is_require' => 1,
'sort_order' => 0,
'values' => array()
);

$options['values'][] = array(
'title' => 'Red',
'price' => 10.00,
'price_type' => 'fixed',
'sku' => '',
'sort_order' => '1'
);

$options['values'][] = array(
'title' => 'Green',
'price' => 89.00,
'price_type' => 'fixed',
'sku' => '',
'sort_order' => '1'
);

$options['values'][] = array(
'title' => 'Blue',
'price' => 89.00,
'price_type' => 'fixed',
'sku' => '',
'sort_order' => '1'
);
*/




	$store = Mage::app()->getStore(Mage_Catalog_Model_Abstract::DEFAULT_STORE_ID);
        // Save all product attributes under "default" store, no matter whether system is in single or multi-store mode
        $product->setStoreId($store->getId());
		// Build array of website ids
        $websiteIds = array();

		if (Mage::app()->isSingleStoreMode()) {
			$websites = Mage::app()->getWebsites();
			foreach($websites as $curWebsite){
				$websiteIds[] = $curWebsite->getId();
			}
		}
        else {
			//set to specific website/store
			$website = Mage::app()->getWebsite();
			$websiteIds[] = $website->getId();
		}

$product->setWebsiteIds($websiteIds);

$product->setAttributeSetId(Mage::getModel('catalog/product')->getResource()->getEntityType()->getDefaultAttributeSetId());

//->setAttributeSetId(4)
//$product->setStoreId('default')

$product->setTypeId('simple') 
->setName($data['name'])
->setDescription($data['desc'])
->setShortDescription($data['shortdesc'])
->setSku($data['sku'])
->setWeight($data['weight'])
->setStatus($data['status'])
//->setVisibility(4)
->setPrice($data['price'])
->addImageToMediaGallery($serverurl,array('thumbnail','small_image','image'),false,false)
->setTaxClassId(0)
->setStockData($stockData)
->setCategoryIds('3')
//->setHasOptions(1)
//->setProductOptions(array($opt))
//->setCanSaveCustomOptions(true)
//->setPageLayout('one_column')		
->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
->setCreatedAt(strtotime('now'))
->setcustomproducttype($data['designed'])
->setcustomperspective($data['persp']);

	Mage::dispatchEvent('catalog_product_prepare_save',array('product' => $product, 'request' => null));
	Mage::log("setup product");

	$product->save();
	$product_id = $product->getId();

	//echo 'OK Product ID: '.$product->getId();

	foreach($options as $title=>$v){
	  $this->setMyCustomOption($product_id,$title,$optionData=$v['opt'],$values = $v['values']);
	}

	$cache = Mage::getSingleton('core/cache');
	$cache->flush();

	return $product->getId();
}
catch (Mage_Core_Exception $e) {
	echo $e->getMessage();
}
catch (Exception $e) {
	echo $e;
}



}


}
?>
