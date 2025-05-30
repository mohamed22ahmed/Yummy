<?php
require 'dompdf/vendor/autoload.php';
require 'ar-php/vendor/autoload.php';
require 'twig/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
use ArPHP\I18N\Arabic;

class PreprintController extends CommonController
{
	
	public function actionpdf()
	{
		$order_uuid = Yii::app()->input->get('order_uuid');
		$size = Yii::app()->input->get('size');
		
		try {
			
			COrders::getContent($order_uuid,Yii::app()->language);
		    $merchant_id = COrders::getMerchantId($order_uuid);
		    $merchant_info = COrders::getMerchant($merchant_id,Yii::app()->language);
		    $items = COrders::getItems();		     
		    $total = COrders::getTotal();
		    $summary = COrders::getSummary();	
		    $order = COrders::orderInfo(Yii::app()->language, date("Y-m-d") );		
		    $client_id = $order?$order['order_info']['client_id']:0;		    
		    $customer = COrders::getClientInfo($client_id);
		    
		    $imageData = Yii::getPathOfAlias('home_dir')."/upload/all/447da499-36e3-11ec-a24b-9c5c8e164c2c.png";		    
		    
		    $site_data = OptionsTools::find(
		    array('website_title','website_address','website_contact_phone','website_contact_email')
		    );
		    
		    $site = array(
		      'title'=>isset($site_data['website_title'])?$site_data['website_title']:'',
		      'address'=>isset($site_data['website_address'])?$site_data['website_address']:'',
		      'contact'=>isset($site_data['website_contact_phone'])?$site_data['website_contact_phone']:'',
		      'email'=>isset($site_data['website_contact_email'])?$site_data['website_contact_email']:'',		      
		    );
		    
		    $print_settings = AOrderSettings::getPrintSettings();		 			
			$label = [
				'Summary'=>t("Summary"),
				'order_no'=>t("Order#"),
				'place_on'=>t("Place on"),
				'order_total'=>t("Order Total"),
				'delivery_date'=>t("Delivery Date/Time"),
				'delivery_address'=>t("DELIVERY ADDRESS"),
				'items_ordered'=>t("ITEMS ORDERED"),
				'qty'=>t("QTY"),
				'price'=>t("PRICE"),
				'contact_us'=>t("Contact Us"),
				'information'=>t("Information"),
			];    			
		    
		    $data = array(		       
		       'site'=>$site,
			   'label'=>$label,
		       'merchant'=>$merchant_info,
		       'order'=>$order,		       
		       'items'=>$items,
		       'total'=>Price_Formatter::formatNumber($total),
		       'summary'=>$summary,		
		       'customer'=>$customer,
		       'receipt_logo'=>isset($print_settings['receipt_logo'])?$print_settings['receipt_logo']:'',
		       'receipt_footer'=>isset($print_settings['receipt_footer'])?trim($print_settings['receipt_footer']):'',
		       'receipt_thank_you'=>isset($print_settings['receipt_thank_you'])?$print_settings['receipt_thank_you']:'',
		    );		
		    	
		    $path = Yii::getPathOfAlias('webroot')."/twig";		    
		    
		    $loader = new \Twig\Loader\FilesystemLoader($path);
		    $twig = new \Twig\Environment($loader, [
			    'cache' => $path."/compilation_cache",
			    'debug'=>true
			]);
									
			$reportHtml = $twig->render('print_order.html',$data);						
		    		    		   		    
		    $options = new Options();
			$options->set('isRemoteEnabled', true);
			$options->set('defaultFont', 'DejaVu Sans');
			$dompdf = new Dompdf($options);
			$arabic = new \ArPHP\I18N\Arabic();
			
			$p = $arabic->arIdentify($reportHtml);

			$htmlOutput = $reportHtml;

			for ($i = count($p)-1; $i >= 0; $i-=2) {
				$utf8ar = $arabic->utf8Glyphs(substr($htmlOutput, $p[$i-1], $p[$i] - $p[$i-1]));
				$htmlOutput   = substr_replace($htmlOutput, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
			}

			$dompdf->loadHtml($htmlOutput);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			$order_id = "Order#".$order['order_info']['order_id'];			
			$dompdf->stream($order_id, array('Attachment' => true));	
			
	    } catch (Exception $e) {
		    dump($e->getMessage());
		}			
	}
	
}
/*end class*/