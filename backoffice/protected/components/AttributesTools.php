<?php
class AttributesTools
{
	
	public static function initialStatus()
	{
		return 'draft';
	}
	
	public static function PosCode()
	{
		return 'pos';
	}
	
	public static function refundStatus()
	{
		return array('partial_refund','refund');
	}
	
	public static function unit()
	{
		return array(
		  'mi'=>t("Miles"),
		  'km'=>t("Kilometers"),
		);
	}
	
	public static function mapsProvider()
	{
		return array(
		  'google.maps'=>t("Google Maps (default)"),
	      'mapbox'=>t("Mapbox"),
		);
	}
	
	public static function verificationType()
	{
		return array(
		  'email'=>t("Using Email verification"),
	      'sms'=>t("Using SMS verification"),
		);
	}
	
	public static function reviewType()
	{
		return array(
		   2=>t("Review per order"),
		   1=>t("Review merchant"),           
		);
	}
	
	public static function SearchType()
	{
		return array(
		   'address'=>t("Address using map provider"),
		//    'zone'=>t("Zone"),
		//    'postcode'=>t("Location using define address"),           
		);
	}
	
	public static function locationNickName()
	{
		return array(
		   'home'=>t("Home"),
		   'work'=>t("Work"),           
		   'other'=>t("Other"),           
		);
	}
	
	public static function statusGroup()
	{
		return array(
		   'customer'=>t("customer"),
		   'post'=>t("post"),	
		   'booking'=>t("booking"),
		   'payment'=>t("payment"),
		   'transaction'=>t("transaction"),
		   'gateway'=>t("gateway"),
		);
	}
	
	public static function soldOutOptions()
	{
		return array(
		  'substitute'=>t("Go with merchant recommendation"),
		  'refund'=>t("Refund this item"),
		  'contact'=>t("Contact me"),
		  'cancel'=>t("Cancel the entire order")
		);
	}
	
	public static function orderButtonsActions()
	{
		return array(
		 'reject_form'=>t("Rejection form")
		);		
	}
	
	public static function transactionTypeList($standard=false)
	{
		if($standard){
			return array(
			  'credit'=>t("Credit"),
			  'debit'=>t("Debit"),			  
			);
		} else {
			return array(
			  'credit'=>t("Credit"),
			  'debit'=>t("Debit"),
			  'payout'=>t("Payout"),
			  'cashin'=>t("Cash In"),
			);
		}
	}
	
	public static function signupTypeList()
	{
		return array(
		   'standard'=>t("Standard signup"),
		   'mobile_phone'=>t("Mobile phone signup"),
		);
	}
	
	public static function paymentStatus()
	{
		return array(
		   'unpaid'=>t("Unpaid"),
		   'paid'=>t("Paid"),
		);
	}
	
	public static function commissionBased()
	{		
		return [
			'subtotal'=>t("Method 1"),
			'method2'=>t("Method 2"),
			'method3'=>t("Method 3"),
		];
	}

	public static function BankStatusList()
	{
		return array(
		   'pending'=>t("Pending"),
		   'approved'=>t("Approved"),
		);
	}


	public static function JwtTokenID(){
		return 'jwt_token';
	}

	public static function JwtMainTokenID(){
		return 'website_jwt_token';
	}

	public static function JwtDriverTokenID(){
		return 'driver_jwt_token';
	}

	public static function JwtMerchantTokenID(){
		return 'merchant_jwt_token';
	}

	public static function JwttablesideTokenID(){
		return 'tableside_jwt_token';
	}

	public static function JwtKitchenTokenID(){
		return 'kitchen_jwt_token';
	}

	public static function StatusManagement($group_name='',$lang = KMRS_DEFAULT_LANGUAGE)
	{
		/*$cuisine = CommonUtility::getDataToDropDown("{{status_management}}",'status','title',"
		WHERE group_name=".q($group_name)." ","ORDER BY title ASC");
		return $cuisine;*/
		
		$data = array();
		$criteria=new CDbCriteria();
		$criteria->alias = "a";
		$criteria->select = "a.status_id, a.title , b.status ";
		$criteria->condition = "a.language=:language AND b.group_name=:group_name 
		and a.title IS NOT NULL AND TRIM(a.title) <> ''
		";
		$criteria->join='
		LEFT JOIN {{status_management}} b on  a.status_id = b.status_id 		
		';
		$criteria->params = array(
		 ':language'=>$lang,
		 ':group_name'=>$group_name
		);		
		$criteria->order = "a.title ASC";
		
		if($model = AR_status_management_translation::model()->findAll($criteria)){
			foreach ($model as $item) {
				$data[$item->status] = $item->title;
			}
		}
		return $data;
	}
	
	public static function ListSelectCuisine()
	{
		$cuisine = CommonUtility::getDataToDropDown("{{cuisine}}",'cuisine_id','cuisine_name',"
		WHERE status = 'publish'","ORDER BY cuisine_name ASC");
		return $cuisine;
	}
	
	public static function ListSelectTags()
	{
		$tags = CommonUtility::getDataToDropDown("{{tags}}",'tag_id','tag_name',"","ORDER BY tag_name ASC");
		return $tags;
	}
	
	public static function ListSelectServices()
	{
		$services = CommonUtility::getDataToDropDown("{{services}}",'service_code','service_name',
		"WHERE status='publish' ","ORDER BY service_name ASC");
		return $services;
	}
		
	public static function ListMerchantType($lang = KMRS_DEFAULT_LANGUAGE)
	{
		/*$list = CommonUtility::getDataToDropDown("{{merchant_type}}",'type_id','type_name',
		"WHERE status='publish' ","ORDER BY type_id ASC");
		return $list;*/
		
		$data = CommonUtility::getDataToDropDown("{{merchant_type_translation}}",'type_id','type_name',
    	"where language=".q($lang)."","ORDER BY type_name ASC" 	
    	);
    	return $data;
	}
	
	public static function ListPlans($plant_type='membership')
	{
		/*$list = CommonUtility::getDataToDropDown("{{packages}}",'package_id','title',
		"","ORDER BY package_id ASC");
		return $list;*/
		$list = CommonUtility::getDataToDropDown("{{plans}}",'package_id','title',
		"WHERE plan_type=".q($plant_type)." ","ORDER BY package_id ASC");
		return $list;
	}
	
	public static function PaymentProvider()
	{
		$list = CommonUtility::getDataToDropDown("{{payment_gateway}}",'payment_code','payment_name',
		"WHERE status='active'","ORDER BY sequence ASC");
		return $list;
	}
	
	public static function PaymentPayoutProvider()
	{
		$model = AR_payment_gateway::model()->findAll("status=:status AND is_payout=:is_payout",array(
		  ':status'=>"active",
		  ':is_payout'=>1,
		));
		if($model){
			$data = array();
			foreach ($model as $val) {
				$logo_image = '';
		   	   if(!empty($val['logo_image'])){
		   	      $logo_image = CMedia::getImage($val['logo_image'],$val['path'],Yii::app()->params->size_image_thumbnail,
				  CommonUtility::getPlaceholderPhoto('item'));
		   	   }
				
		   	   $data[] = array(
		   	    'payment_name'=>$val['payment_name'],
		   	    'payment_code'=>$val['payment_code'],
		   	    'logo_type'=>$val['logo_type'],
		   	    'logo_class'=>$val['logo_class'],
		   	    'logo_image'=>$logo_image,
		   	  );
			}			
			return $data;
		}
		throw new Exception( 'no results' );
	}
	
	public static function PaymentPlansProvider()
	{
		$model = AR_payment_gateway::model()->findAll("status=:status AND is_plan=:is_plan",array(
		  ':status'=>"active",
		  ':is_plan'=>1,
		));
		if($model){
			$data = array();
			foreach ($model as $val) {
				$logo_image = '';
		   	   if(!empty($val['logo_image'])){
		   	      $logo_image = CMedia::getImage($val['logo_image'],$val['path'],Yii::app()->params->size_image_thumbnail,
				  CommonUtility::getPlaceholderPhoto('item'));
		   	   }
				
		   	   $data[] = array(
		   	    'payment_name'=>$val['payment_name'],
		   	    'payment_code'=>$val['payment_code'],
		   	    'logo_type'=>$val['logo_type'],
		   	    'logo_class'=>$val['logo_class'],
		   	    'logo_image'=>$logo_image,
		   	  );
			}			
			return $data;
		}
		throw new Exception( 'no available payment method' );
	}
	
	public static function PaymentProviderByMerchant($merchant_id='')
	{
		$data = array();
		$stmt="
		SELECT a.payment_id,a.payment_name
		FROM {{payment_gateway}} a	
		WHERE a.payment_code IN (
		  select meta_value from {{merchant_meta}}
		  where meta_name='payment_gateway'
		  and meta_value = a.payment_code
		  and merchant_id = ".q($merchant_id)."
		)	
		AND a.status='active'
		ORDER BY a.sequence ASC
		";		
		if( $res = CCacheData::queryAll($stmt,'merchant')){
		   $data = array();
		   foreach ($res as $val) {
		   	   $data[$val['payment_id']] = Yii::app()->input->xssClean($val['payment_name']);
		   }
		   return $data;
		} 
		return false;
	}

	public static function paymentProviderDetails($payment_code='')
	{
		$provider = AR_payment_gateway::model()->find("payment_code=:payment_code",array(
	      ':payment_code'=>$payment_code
	    ));
	    if($provider){
	    	return array(
	    	  'payment_code'=>$provider->payment_code,
	    	  'payment_name'=>$provider->payment_name,
	    	  'is_online'=>$provider->is_online,
	    	  'logo_type'=>$provider->logo_type,
	    	  'logo_class'=>$provider->logo_class,
	    	  'logo_image'=>$provider->logo_image,
	    	  'path'=>$provider->path,
	    	);
	    }
	    return false;
	}
	
	public static function MerchantList()
	{
		$list = CommonUtility::getDataToDropDown("{{merchant}}",'merchant_id','restaurant_name',
		"WHERE status='active'","ORDER BY restaurant_name ASC");
		return $list;
	}
	
	public static function StatusList()
	{
		$list = CommonUtility::getDataToDropDown("{{order_status}}",'description','description',
		"WHERE 1","ORDER BY description ASC");
		return $list;
	}
	
	public static function CurrencyList()
	{
		$list = CommonUtility::getDataToDropDown("{{currency}}",'currency_code','description',
		"WHERE is_hidden=0","ORDER BY currency_code ASC");
		return $list;
	}
	
	public static function defaultCurrency($all=false)
	{
		$model = AR_currency::model()->find("as_default=:as_default",array(
		  ':as_default'=>1
		));
		if($model){
			if($all){
				return array(
				  'currency_code'=>$model->currency_code,
				  'currency_symbol'=>$model->currency_symbol,
				  'description'=>$model->description,
				);
			} else return $model->currency_code;			
		}
		return false;
	}
	
	public static function getLanguage()
	{
		$list = CommonUtility::getDataToDropDown("{{language}}",'code','title',
		"WHERE status='publish' AND CODE NOT IN (".q(KMRS_DEFAULT_LANGUAGE).") ","ORDER BY sequence ASC");
		return $list;
	}
	
	public static function getLanguageAll()
	{
		$list = CommonUtility::getDataToDropDown("{{language}}",'code','title',
		"WHERE status='publish'","ORDER BY sequence ASC");
		return $list;
	}
	
	public static function SMSProvider()
	{
		$list = CommonUtility::getDataToDropDown("{{sms_provider}}",'provider_id','provider_name',
		"WHERE 1","ORDER BY provider_name ASC");
		return $list;
	}
		
	public static function Dish()
	{
		$list = CommonUtility::getDataToDropDown("{{dishes}}",'dish_id','dish_name',"
		WHERE status = 'publish'","ORDER BY dish_name ASC");
		return $list;
	}
	
	public static function Subcategory($merchant_id='')
	{
		$list = CommonUtility::getDataToDropDown("{{subcategory}}",'subcat_id','subcategory_name',"
		WHERE status = 'publish' AND merchant_id=".q($merchant_id)." ",
		"ORDER BY subcategory_name ASC");
		return $list;
	}
	
	public static function Category($merchant_id='',$lang=KMRS_DEFAULT_LANGUAGE)
	{
		$data = [];
		$stmt = "
		SELECT 		
		a.cat_id,
		a.merchant_id,
		IF(COALESCE(NULLIF(b.category_name, ''), '') = '', a.category_name, b.category_name) as category_name
		FROM {{category}} a
		left JOIN (
			SELECT category_name,cat_id FROM {{category_translation}} where language=".q($lang)."
		) b 
		ON a.cat_id = b.cat_id
		WHERE 
		a.merchant_id=".q($merchant_id)."		
		";
		if ( $res = CCacheData::queryAll($stmt)){
			foreach ($res as $items) {
				$data[$items['cat_id']] = $items['category_name'];
			}
		}
    	return $data;
	}
	
	public static function Size($merchant_id='')
	{
		$list = CommonUtility::getDataToDropDown("{{size}}",'size_id','size_name',"
		WHERE status = 'publish' AND merchant_id=".q($merchant_id)."
		","ORDER BY size_name ASC");
		
		$none[''] = t("Select Unit");		
		$list = $none + $list;		
		return $list;
	}
	
	public static function Supplier($merchant_id='')
	{
		$list = CommonUtility::getDataToDropDown("{{inventory_supplier}}",'supplier_id','supplier_name',"
		WHERE merchant_id=".q($merchant_id)."
		","ORDER BY supplier_name ASC");
		
		$none[''] = t("Select Supplier");		
		$list = $none + $list;		
		return $list;
	}
	
	public static function Cooking($merchant_id='')
	{
		$list = CommonUtility::getDataToDropDown("{{cooking_ref}}",'cook_id','cooking_name',"
		WHERE merchant_id=".q($merchant_id)." AND status='publish'
		","ORDER BY cooking_name ASC");
				
		return $list;
	}
	
	public static function Ingredients($merchant_id='')
	{
		$list = CommonUtility::getDataToDropDown("{{ingredients}}",'ingredients_id','ingredients_name',"
		WHERE merchant_id=".q($merchant_id)." AND status='publish'
		","ORDER BY ingredients_name ASC");
				
		return $list;
	}
	
	public static function ItemSize($merchant_id='',$item_id='')
	{
		$list = array();
		$stmt="SELECT item_size_id,
		size_name,price
		FROM {{view_item_size}}
		WHERE
		merchant_id=".q($merchant_id)."
		AND item_id = ".q($item_id)."
		ORDER BY sequence ASC
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			foreach ($res as $val) {
				$list[ $val['item_size_id'] ] = t("[price] [size_name]",array(
				  '[price]'=>Price_Formatter::formatNumberNoSymbol($val['price']),
				  '[size_name]'=>Yii::app()->input->stripClean($val['size_name']),
				));
			}
		}
		return $list;
	}
	
	public static function CommissionType()
	{
		return array(
		  ''=>t("Select comission type"),
		  'fixed'=>t("Fixed"),
		  'percentage'=>t("percentage"),
		);
	}
	
	public static function InvoiceTerms()
    {
    	return array(
		  0=>t("Please select"),
    	  1=>t("Daily"),
    	  7=>t("Weekly"),
    	  15=>t("Every 15 Days"),
    	  30=>t("Every 30 Days"),
    	);
    }
    
    public static function ExpirationType()
    {
    	return array(
    	 'days'=>t("Days"),
    	 'year'=>t("Year")
    	);
    }
    
    public static function ListlimitedPost()
    {
    	return array(
    	  2=>t("Unlimited"),
    	  1=>t("Limited")
    	);
    }
    
    public static function PlanPeriod()
    {
    	return array(
    	 'daily'=>t("Daily"),
    	 'weekly'=>t("Weekly"),
    	 'monthly'=>t("Monthly"),
    	 'anually'=>t("Anually")
    	);
    }
    
    public static function getDishes($dish_id=0)
	{
		$data = array();
		$stmt = "
		SELECT 
		a.dish_id,
		a.dish_name,
		a.photo,
		a.status,
		IFNULL(b.language,'default') as language,
		IFNULL(b.dish_name,'') as  dish_name_trans
		
		FROM {{dishes}} a		
		LEFT JOIN {{dishes_translation}} b
		ON
		a.dish_id = b.dish_id
		
		WHERE a.dish_id = ".q($dish_id)."
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			foreach ($res as $val) {				
				$data[$val['language']] = $val['language']=="default"?$val['dish_name']:$val['dish_name_trans'];
			}
			return $data;
		}
		return false;
	}	    
	
	public static function timezoneList()
	{		
		$version=phpversion();				
		if ($version<=5.2){
			return array();
		}		
		$list[''] = t("Please Select");
		$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
		if (is_array($tzlist) && count($tzlist)>=1){
			foreach ($tzlist as $val) {
				$list[$val]=$val;
			}
		}			
		return $list;		
	}
	
	public static function DateFormat()
	{
		/*return array(
		  'dd MMM yyyy'=>Date_Formatter::date(date('c'),'dd MMM yyyy'),		  
		  'dd/MMM/yyyy'=>Date_Formatter::date(date('c'),'dd/MMM/yyyy'),		  
		  'yyyy MMM dd'=>Date_Formatter::date(date('c'),'yyyy MMM dd'),		  		  
		  'yyyy-MMM-dd'=>Date_Formatter::date(date('c'),'yyyy-MM-dd'),		  
		  'MMM dd yyyy'=>Date_Formatter::date(date('c'),'MMM dd yyyy'),		  
		  'MMM/dd/yyyy'=>Date_Formatter::date(date('c'),'MMM/dd/yyyy'),		  
		  'MMM-dd-yyyy'=>Date_Formatter::date(date('c'),'MMM-dd-yyyy'),		  
		);*/		
		return array(
		  'EEEE, MMMM d, y'=>Date_Formatter::date(date('c'),'EEEE, MMMM d, y',true),		  	
		  'EEE, MMMM d, y'=>Date_Formatter::date(date('c'),'EEE, MMMM d, y',true),	
		  'EEE, MMM d, y'=>Date_Formatter::date(date('c'),'EEE, MMM d, y',true),	
		  'MMMM EEEE d, y'=>Date_Formatter::date(date('c'),'MMMM EEEE d, y',true),		  	
		  'MMMM EEE d, y'=>Date_Formatter::date(date('c'),'MMMM EEE d, y',true),		  	
		  'MMM EEE d, y'=>Date_Formatter::date(date('c'),'MMM EEE d, y',true),		  	
		  
		  'MMM d, y'=>Date_Formatter::date(date('c'),'MMM d, y',true),
		  'M/d/yy'=>Date_Formatter::date(date('c'),'M/d/yy',true),
		  'dd/MMM/yyyy'=>Date_Formatter::date(date('c'),'dd/MMM/yyyy',true),	
		  'yyyy MMM dd'=>Date_Formatter::date(date('c'),'yyyy MMM dd',true),		  
		  'dd MMM yyyy'=>Date_Formatter::date(date('c'),'dd MMM yyyy',true),		  
		);
	}
	
	public static function TimeFormat()
	{
		/*return array(
		  'h:mm a'=>Date_Formatter::date(date('c'),'h:mm a'),		  
		  'h:mm'=>Date_Formatter::date(date('c'),'h:mm'),		  
		  'hh:mm:ss a'=>Date_Formatter::date(date('c'),'hh:mm:ss a'),		  
		  'hh:mm:ss'=>Date_Formatter::date(date('c'),'hh:mm:ss'),		  
		  'HH:mm:ss'=>Date_Formatter::date(date('c'),'HH:mm:ss'),		  
		  'HH:mm'=>Date_Formatter::date(date('c'),'HH:mm'),	
		);*/
		/*return array(
		  'h:mm:ss a'=>Date_Formatter::Time(date('c'),'h:mm:ss a'),
		  'h:mm a'=>Date_Formatter::Time(date('c'),'h:mm a'),
		  'h:mm:ss a zzzz'=>Date_Formatter::Time(date('c'),'h:mm:ss a zzzz'),
		  'h:mm:ss a z'=>Date_Formatter::Time(date('c'),'h:mm:ss a z'),		  
		);*/
		return array(
		  'h:mm:ss a'=>'h:mm:ss a',
		  'h:mm a'=>'h:mm a',
		  'h:mm:ss a zzzz'=>'h:mm:ss a zzzz',
		  'h:mm:ss a z'=>'h:mm:ss a z',
		  'H:m'=>'H:m',
		  'H:m:s'=>'H:m:s',
		  'HH:mm'=>'HH:mm',
		  'HH:mm:ss'=>'HH:mm:ss',
		);
	}
	
	public static function CountryList($key='shortcode')
	{
		$list = CommonUtility::getDataToDropDown("{{location_countries}}",$key,'country_name',
		"WHERE 1","ORDER BY country_name ASC");
		return $list;
	}
	
	public static function CurrencyPosition()
	{
	   return array(
	     'left'=>t("Left $11"),
	     'right'=>t("Right 11$"),
	     'left_space'=>t("Left with space $ 11"),
	     'right_space'=>t("Right with space 11 $")
	   );
	}
	
	public static function MenuStyle()
	{
		return array( 
		  1=>t("Menu 1"),
		  2=>t("Menu 2"),
		  3=>t("Menu 3"),
		);
	}
	
	public static function LocationSearchType()
	{		
		return array(
		  1=>t("City / Area"),
		  2=>t("State / City"),
		  3=>t("PostalCode/ZipCode"),	
		);
	}
	
	public static function currencyListSelection()
	{
		$data = array();
		$data['']=t("Please select");
		$stmt="
		SELECT currency_name,symbol,code
		FROM {{multicurrency_list}}
		ORDER BY code ASC		
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			foreach ($res as $val) {
				$string = '[code] - [name]';
				if(!empty($val['symbol'])){
					$string = '[code] - [name] ([symbol])';
				}
				$data[$val['code']]= t($string,array(
				  '[code]'=>$val['code'],
				  '[name]'=>$val['currency_name'],
				  '[symbol]'=>$val['symbol'],
				));
			}			
		}
		return $data;
	}
	
	public static function CurrencyDetails($code='')
	{
		$stmt="
		SELECT currency_name,symbol,code FROM {{multicurrency_list}}
		WHERE code=".q($code)."
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			return $res;
		}
		return false;
	}
	
	public static function couponType()
    {
    	return array(
    	  'fixed amount'=>t("fixed amount"),
    	  'percentage'=>t("Percentage")
    	);
    }

	public static function TipType()
    {
    	return array(
    	  'fixed'=>t("Fixed"),
    	  'percentage'=>t("Percentage")
    	);
    }
    
    public static function couponOoptions()
    {
    	 return array(
		    1=>t("Unlimited for all user"),
		    2=>t("Use only once"),
		    3=>t("Once per user"),
            4=>t("Discount delivery"),
		    5=>t("Once for new user first order"),
		    6=>t("Once for new user second order"),
		    7=>t("Once for new user third order"),
		    8=>t("Custom limit per user"),
		    9=>t("Only to selected customer")
		  );
    }
    
    public static function dayList()
    {
    	return array(
    	  'monday'=>t("monday"),
    	  'tuesday'=>t("tuesday"),
    	  'wednesday'=>t("wednesday"),
    	  'thursday'=>t("thursday"),
    	  'friday'=>t("friday"),
    	  'saturday'=>t("saturday"),
    	  'sunday'=>t("sunday")
    	);
    }
    
    public static function dayWeekList()
    {
    	return array(
    	  1=>t("monday"),
    	  2=>t("tuesday"),
    	  3=>t("wednesday"),
    	  4=>t("thursday"),
    	  5=>t("friday"),
    	  6=>t("saturday"),
    	  7=>t("sunday")
    	);
    }
    
    public static function pagesTranslation($page_id=0)
	{
		$data = array();
		$stmt = "
		SELECT 
		a.page_id,
		a.title,
		a.long_content,		
		IFNULL(b.language,'default') as language,
		IFNULL(b.title,'') as  title_trans,
		IFNULL(b.long_content,'') as  long_content_trans,
		IFNULL(b.meta_title,'') as  meta_title_trans,
		IFNULL(b.meta_description,'') as  meta_description_trans,
		IFNULL(b.meta_keywords,'') as  meta_keywords_trans
		
		FROM {{pages}} a		
		LEFT JOIN {{pages_translation}} b
		ON
		a.page_id = b.page_id
		
		WHERE a.page_id = ".q($page_id)."
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			foreach ($res as $val) {								
				$data['title'][$val['language']] = $val['language']=="default"?$val['title']:$val['title_trans'];
				$data['long_content'][$val['language']] = $val['language']=="default"?$val['long_content']:$val['long_content_trans'];
			}
			return $data;	
		}
		return false;
	}	        

	public static function pagesTranslation2($page_id=0)
	{
		$data = array();
		$stmt = "
		SELECT 
		a.page_id,
		a.title,
		a.long_content,		
		a.meta_title,
		a.meta_description,
		a.meta_keywords,
		IFNULL(b.language,'default') as language,
		IFNULL(b.title,'') as  title_trans,
		IFNULL(b.long_content,'') as  long_content_trans,
		IFNULL(b.meta_title,'') as  meta_title_trans,
		IFNULL(b.meta_description,'') as  meta_description_trans,
		IFNULL(b.meta_keywords,'') as  meta_keywords_trans
		
		FROM {{pages}} a		
		LEFT JOIN {{pages_translation}} b
		ON
		a.page_id = b.page_id
		
		WHERE a.page_id = ".q($page_id)."
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			foreach ($res as $val) {								
				$data['title'][$val['language']] = $val['language']=="default"?$val['title']:$val['title_trans'];
				$data['long_content'][$val['language']] = $val['language']=="default"?$val['long_content']:$val['long_content_trans'];
				$data['meta_title'][$val['language']] = $val['language']=="default"?$val['meta_title']:$val['meta_title_trans'];
				$data['meta_description'][$val['language']] = $val['language']=="default"?$val['meta_description']:$val['meta_description_trans'];
				$data['meta_keywords'][$val['language']] = $val['language']=="default"?$val['meta_keywords']:$val['meta_keywords_trans'];
			}
			return $data;	
		}
		return false;
	}	        
	
    public static function smsPackageTranslation($sms_package_id=0)
	{
		$data = array();
		$stmt = "
		SELECT 
		a.sms_package_id,
		a.title,
		a.description,		
		IFNULL(b.language,'default') as language,
		IFNULL(b.title,'') as  title_trans,
		IFNULL(b.description,'') as  description_trans		
		
		FROM {{sms_package}} a		
		LEFT JOIN {{sms_package_translation}} b
		ON
		a.sms_package_id = b.sms_package_id
		
		WHERE a.sms_package_id = ".q($sms_package_id)."
		";		
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){
			foreach ($res as $val) {								
				$data['title'][$val['language']] = $val['language']=="default"?$val['title']:$val['title_trans'];
				$data['description'][$val['language']] = $val['language']=="default"?$val['description']:$val['description_trans'];
			}
			return $data;	
		}
		return false;
	}	        	
	
	public static function SecureConnection()
	{
		return array(
		  'tls'=>t("TLS"),
		  'ssl'=>t("SSL"),
		);
	}
	
	public static function ContactFields()
	{
		return array(
		  'fullname'=>t("Name"),
		  'email_address'=>t("Email Address"),
		  'contact_number'=>t("Phone"),
		  'country_name'=>t("Country"),
		  'message'=>t("Message"),
		);
	}
	
    public static function GetFromTranslation($id=0, $table1='',$table2='',$primary='',$fields1=array(), $fields2=array())
	{
		$data = array();
		$stmt_field1=''; $stmt_field2='';

		foreach ($fields1 as $fields1_val) {			
			$stmt_field1.="a.$fields1_val,\n";
		}
		
		foreach ($fields2 as $key=>$fields2_val) {
			$stmt_field2.="IFNULL(b.$key, a.$key ) as  $fields2_val,\n";
		}
		
		$stmt_field1 = substr($stmt_field1,0,-1);
		$stmt_field2 = substr($stmt_field2,0,-2);
			
		$stmt = "
		SELECT 
		$stmt_field1
		
		IFNULL(b.language,'default') as language,
		$stmt_field2
		
		FROM $table1 a		
		LEFT JOIN $table2 b
		ON
		a.$primary = b.$primary
		
		WHERE a.$primary = ".q($id)."
		";											
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){			
			foreach ($res as $val) {		
				foreach ($fields2 as $fields2_key=>$fields2_val) {				   
				   //$data[$fields2_key][$val['language']] = $val['language']=="default"?$val[$fields2_key]:$val[$fields2_val];
				   if(isset($val[$fields2_key])){
				      $data[$fields2_key][$val['language']] = $val['language']=="default"?$val[$fields2_key]:$val[$fields2_val];
				   }
				}
			}					
			return $data;	
		}
		return false;
	}	        	

	public static function getLocaleLanguages()
	{
		$locale = Yii::app()->localeDataPath."/en.php";
		if(file_exists($locale)){
			$localy = require $locale;
			return $localy['languages'];
		}
		return false;
	}
	
	public static function foodOptionsListing()
	{		
		return array(
		  0=>t("Please select..."),
		  1=>t("Hide"),
		  2=>t("Disabled"),		  
		);
	}
	
	public static function twoFlavorOptions()
	{		
		return array(
		  0=>t("Please select..."),
		  1=>t("Highest price"),
		  2=>t("Sumup and divided by 2"),		  
		);
	}
	
	public static function Tips()
	{						
		return CommonUtility::getDataToDropDown("{{admin_meta}}",'meta_value','meta_value',"
		WHERE meta_name='tips'
		","ORDER BY meta_value ASC");
	}
	
    public static function transportType()
	{
		return array(		  
		  'truck'=>t("Truck"),
		  'car'=>t("Car"),
		  'bike'=>t("Bike"),
		  'bicycle'=>t("Bicycle"),
		  'scooter'=>t("Scooter"),
		  'walk'=>t("Walk"),
		);
	}	
	
	public static function MultiOption()
	{
		return array(
		  'one'=>t("Select Only One"),
		  'multiple'=>t("Select Multiple With Qty"),		  
		  'custom'=>t("Select Multiple"),		  
		  //'two_flavor'=>t("Two Flavors"),
		);
	}
	
	public static function TwoFlavor()
	{
		return array(
		  'left'=>t("left"),
		  'right'=>t("Right"),
		);
	}
	
	public static function ItemFeatured()
	{
		return array(
		  'new'=>t("New Items"),
		  'trending'=>t("Trending"),		  
		  'best_seller'=>t("Best Seller"),
		  'recommended'=>t("Recommended"),
		);
	}
	
	public static function MerchantFeatured()
	{
		return array(
		  'new'=>t("New Restaurant"),
		  'popular'=>t("Popular"),		  
		  'best_seller'=>t("Best Seller"),
		  'recommended'=>t("Recommended"),
		);
	}
	
	public static function DeliveryChargeType()
	{
		return array(
		  'fixed'=>t("Fixed Charge"),
		  'dynamic'=>t("Dynamic Rates"),
		);
	}
	
	public static function ShippingType()
	{
		return array(
		  'standard'=>t("Standard"),
		  'priority'=>t("Priority"),
		  'no_rush'=>t("No rush"),
		);
	}
	
	public static function metaMedia()
	{
		return 'merchant_gallery';
	}
	
	public static function metaReview()
	{
		return 'review';
	}
	
	public static function metaProfile()
	{
		return 'profile_photo';
	}
	
	public static function SMSBroadcastType()
	{
		return array(
		  1=>t("Send to All Subscriber"),
		  2=>t("Send to Customer Who already buy your products"),
		  3=>t("Send to specific mobile numbers")
		);
	}
	
	public static function ItemPromoType()
	{
		return array(
		  'buy_one_get_free'=>t("Buy (qty) to get the (qty) item free"),
		  'buy_one_get_discount'=>t("Buy (qty) and get 1 at (percen)% off"),
		);
	}

	public static function ItemPromoType2()
	{
		return array(
		  'buy_one_get_free'=>t("Buy (buy_qty) to get the (get_qty) item free"),
		  'buy_one_get_discount'=>t("Buy (buy_qty) and get 1 at (percent)% off"),
		);
	}
	
	public static function SortMerchant()
	{
		return array(
		  'sort_most_popular'=>t("Most popular"),
		  'sort_rating'=>t("Rating"),		  
		  'sort_promo'=>t("Promo"),
		  'sort_free_delivery'=>t("Free delivery first order"),
		);
	}

	public static function SortMerchant2()
	{		
		return [
			[
				'label'=>t("Most popular"),
				'value'=>'sort_most_popular'
			],
			[
				'label'=>t("Rating"),
				'value'=>'sort_rating'
			],
			[
				'label'=>t("Promo"),
				'value'=>'sort_promo'
			],
			[
				'label'=>t("Free delivery first order"),
				'value'=>'sort_free_delivery'
			]
		];
	}
	
	public static function SortPrinceRange()
	{
		$data = [];
		for ($x = 1; $x <= 4; $x++) {
			//$data[$x] = str_pad($symbol,$x,$symbol);
			$new_symbol = '';
			$symbol = Price_Formatter::$number_format['currency_symbol'];			
			for ($y = 1; $y <= $x; $y++) {
			    $new_symbol.=$symbol;
		    }
			$data[$x] = $new_symbol;
		}
		return $data;
	}

	public static function SortPrinceRange2()
	{		
		for ($x = 1; $x <= 4; $x++) {
			$symbol = Price_Formatter::$number_format['currency_symbol'];
			if(Price_Formatter::$number_format['currency_code']=="EUR"){
				$symbol="&euro;";
			}
			$newSymbols = '';
			for ($i = 1; $i <= $x; $i++) {
				$newSymbols.= $symbol;
			}
			$data[$x] = $newSymbols;
		}
		return $data;
	}

    public static function SortPrinceRangeWithLabel()
    {
        $data = [];
        for ($x = 1; $x <= 4; $x++) {
            $new_symbol = '';
            $symbol = Price_Formatter::$number_format['currency_symbol'];
            for ($y = 1; $y <= $x; $y++) {
                $new_symbol.=$symbol;
            }
            $data[] = [
                'label'=>$new_symbol,
                'value'=>$x
            ];
        }
        return $data;
    }

	public static function MaxDeliveryFee()
	{		
		$y=1;
		for ($x = 1; $x <= 10; $x++) {						
			$symbol = Price_Formatter::$number_format['currency_symbol'];
			$data[] = [
				'label'=>$symbol,
				'value'=>$y,
			];			
			$y = $y+2;
	    }
		return $data;
	}

	public static function SortList()
	{
		return [
			'distance'=>t("Distance"),
			//'quick_delivery'=>t("Quickest delivery"),
			'recommended'=>t("Recommended"),
			'top_rated'=>t("Top-rated"),
		];
	}
		
	public static function countryMobilePrefix()
	{
		$stmt="
		SELECT shortcode,phonecode
		FROM {{location_countries}}
		ORDER BY shortcode ASC
		";
		
		if(Yii::app()->params->db_cache_enabled){			
			$dependency = new CDbCacheDependency("SELECT count(*) FROM {{location_countries}}");
			$res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll();		  
		} else $res = Yii::app()->db->createCommand($stmt)->queryAll();
		
		if($res){
			foreach ($res as $val) {						
				$data[] = array(
				 'name'=>t("+[phonecode] ([shortcode])",array(
					 '[phonecode]'=>$val['phonecode'],
					 '[shortcode]'=>$val['shortcode'],
					)),
				  'value'=>$val['phonecode']
				);
			}
			return $data;
		}		
		return false;
	}

	public static function countryMobilePrefixWithFilter($countrycode_list=array())
	{		
		$criteria=new CDbCriteria();			
		if(is_array($countrycode_list) && count($countrycode_list)>=1){
			$criteria->addInCondition('shortcode', (array) $countrycode_list );		
		}		
		$criteria->order="shortcode ASC";
		
		$model = AR_location_countries::model()->findAll($criteria); 
		if($model){
			foreach ($model as $item) {
				$data[] = array(
					'label'=>t("+[phonecode] ([shortcode])",array(
						'[phonecode]'=>$item->phonecode,
						'[shortcode]'=>$item->shortcode
					   )),
					 'value'=>$item->phonecode
				   );
			}
			return $data;
		}		
		return false;
	}

	public static function getMobileByShortCode($shortcode='')
	{
		$default_prefix_array = array();
		$dependency = CCacheData::dependency();			
		$model = AR_location_countries::model()->cache( Yii::app()->params->cache , $dependency  )->find("shortcode=:shortcode",array(
			':shortcode'=>$shortcode
		));
		if($model){
			$default_prefix_array = [
				'label'=>t("+[phonecode] ([shortcode])",array(
					'[phonecode]'=>$model->phonecode,
					'[shortcode]'=>$model->shortcode
					)),
					'value'=>$model->phonecode
			];			
		}	
		return $default_prefix_array;					
	}

	public static function getMobileByPhoneCode($phonecode='')
	{
		$default_prefix_array = array();
		$dependency = CCacheData::dependency();			
		$model = AR_location_countries::model()->cache( Yii::app()->params->cache , $dependency  )->find("phonecode=:phonecode",array(
			':phonecode'=>$phonecode
		));
		if($model){
			$default_prefix_array = [
				'label'=>t("+[phonecode] ([shortcode])",array(
					'[phonecode]'=>$model->phonecode,
					'[shortcode]'=>$model->shortcode
					)),
					'value'=>$model->phonecode
			];			
		}	
		return $default_prefix_array;					
	}
	
	public static function getMobileByPhoneCodeInfo($phonecode='')
	{		
		$dependency = CCacheData::dependency();			
		$model = AR_location_countries::model()->cache( Yii::app()->params->cache , $dependency  )->find("phonecode=:phonecode",array(
			':phonecode'=>$phonecode
		));
		if($model){
			return $model;
		}	
		return false;
	}

	public static function getOrderStatusList($lang=KMRS_DEFAULT_LANGUAGE,$group_name='order_status')
	{
		$stmt="
		SELECT a.stats_id,a.description as status,
		b.description 
		FROM {{order_status}} a
		LEFT JOIN {{order_status_translation}} b
		ON 
		a.stats_id = b.stats_id
		WHERE b.language=".q($lang)."
		AND a.group_name=".q($group_name)."	
		";			
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){					
			return $res;			
		}
		return false;
	}
	
	public static function getOrderStatus($lang=KMRS_DEFAULT_LANGUAGE , $group_name='order_status', $with_label = false)
	{
		$stmt="
		SELECT a.stats_id,
		a.description as original_status,
		b.description as status
		FROM {{order_status}} a		
		left JOIN (
			SELECT stats_id,description FROM {{order_status_translation}} where language = ".q($lang)."
		) b 
		ON a.stats_id = b.stats_id
		WHERE
		a.group_name=".q($group_name)."	
		";					
		$dependency = CCacheData::dependency();         
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){	
			$data = array();
			foreach ($res as $val) {
				if($with_label){
					$data[] = [
						'label'=>empty($val['status'])?$val['original_status']:$val['status'],
						'value'=>$val['original_status']
					];
				} else $data[$val['original_status']] = empty($val['status'])?$val['original_status']:$val['status'];				
			}
			return $data;
		}
		return false;
	}

	public static function getOrderStatusMany($lang=KMRS_DEFAULT_LANGUAGE , $group_name=array(), $with_label = false)
	{		
		if(!is_array($group_name)&& count($group_name)<=0){
			return false;
		}
		$in_group = CommonUtility::arrayToQueryParameters($group_name);
		$stmt="
		SELECT a.stats_id,
		a.description as original_status,
		b.description as status
		FROM {{order_status}} a		
		left JOIN (
			SELECT stats_id,description FROM {{order_status_translation}} where language = ".q($lang)."
		) b 
		ON a.stats_id = b.stats_id
		WHERE
		a.group_name IN ($in_group)
		";								
		$dependency = CCacheData::dependency();         
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){	
			$data = array();
			foreach ($res as $val) {
				if($with_label){
					$data[] = [
						'label'=>empty($val['status'])?$val['original_status']:$val['status'],
						'value'=>$val['original_status']
					];
				} else $data[$val['original_status']] = empty($val['status'])?$val['original_status']:$val['status'];				
			}
			return $data;
		}
		return false;
	}	

	public static function getOrderStatusWithColor($lang=KMRS_DEFAULT_LANGUAGE , $group_name='order_status')
	{
		$stmt="
		SELECT a.stats_id,a.description as status,		
		a.background_color_hex,a.font_color_hex,
		b.description 
		FROM {{order_status}} a
		LEFT JOIN {{order_status_translation}} b
		ON 
		a.stats_id = b.stats_id
		WHERE b.language=".q($lang)."	
		AND a.group_name=".q($group_name)."	
		";	
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){					
			$data = array();
			foreach ($res as $val) {
				$val['background_color_hex'] = !empty($val['background_color_hex'])?$val['background_color_hex']:'#78909c';
				$data[$val['status']] = [
					'label'=>$val['description'],
					'bg_color'=>$val['background_color_hex'],
					'bg_color_raw'=>str_replace("#","",$val['background_color_hex']),
					'font_color'=>$val['font_color_hex'],
				];
			}
			return $data;
		}
		return false;
	}

	public static function getOrderStatus2($lang=KMRS_DEFAULT_LANGUAGE , $group_name='order_status')
	{
		$stmt="
		SELECT a.stats_id,a.description as status,
		a.font_color_hex,a.background_color_hex,
		b.description 
		FROM {{order_status}} a
		LEFT JOIN {{order_status_translation}} b
		ON 
		a.stats_id = b.stats_id
		WHERE b.language=".q($lang)."	
		AND a.group_name=".q($group_name)."	
		";	
		if($res = Yii::app()->db->createCommand($stmt)->queryAll()){					
			$data = array();
			foreach ($res as $val) {
				$data[$val['status']] = [
					'description'=>$val['description'],
					'font_color_hex'=>$val['font_color_hex'],
					'background_color_hex'=>$val['background_color_hex'],
				];
			}
			return $data;
		}
		return false;
	}
	
	public static function formatAsSelect2($data=array())
	{
		$results = array();
		if(is_array($data) && count($data)>=1){			
			foreach ($data as $items) {				
				$results[] = array(
				 'id'=>intval($items['stats_id']),
				 'text'=>$items['description']
				);
			}			
		}
		return $results;
	}
	
	public static function delayedMinutes($with_label=true,$start=5,$end=6)
	{
		$time = $start; $times = array();
		for ($x = 1; $x <= $end; $x++) {
		   if($with_label){
			 $times[]= array(
				'id'=>($time*$x),
				'value'=>t("{{mins}} min(s)",array('{{mins}}'=>($time*$x)))
			  );
		   } else {
			  $times[$time*$x] = t("{{mins}} min(s)",array('{{mins}}'=>($time*$x)));
		   }		   
		} 
		return $times;
	}
	
	public static function statusManagementTranslationList($group_name='' , $lang = KMRS_DEFAULT_LANGUAGE )
	{
		$criteria=new CDbCriteria();
		$criteria->alias = "a";			
		$criteria->select = "a.status,b.title";
		$criteria->join='LEFT JOIN {{status_management_translation}} b on  a.status_id=b.status_id ';
		$criteria->condition = "a.group_name=:group_name AND language=:language ";
		$criteria->params = array(
		  ':group_name'=>$group_name,
		  ':language'=>$lang
		);
		$model=AR_status_management::model()->findAll($criteria);
		if($model){
			$data = array();
			foreach ($model as $item) {
				$data[$item->status] = $item->title;
			}
			return $data;
		}
		return false;
	}
	
	public static function orderSortList()
	{
		/*return array(
		  'order_id_asc'=>t("Order ID - Ascending"),
		  'order_id_desc'=>t("Order ID - Descending"),
		  'delivery_time_asc'=>t("Delivery Time - Ascending"),
		  'delivery_time_desc'=>t("Delivery Time - Descending"),
		);*/
		return array(
		  'order_id_asc'=>array(		    
		    'text'=>t("Order ID - Ascending"),
		    'icon'=>'fas fa-sort-alpha-down',
		  ),
		  'order_id_desc'=>array(
		   'text'=>t("Order ID - Descending"),
		   'icon'=>'fas fa-sort-alpha-up',
		  ),
		  'delivery_time_asc'=>array(
		    'text'=>t("Delivery Time - Ascending"),
		    'icon'=>'fas fa-sort-alpha-down',
		  ),
		  'delivery_time_desc'=>array(
		    'text'=>t("Delivery Time - Descending"),
		    'icon'=>'fas fa-sort-alpha-up',
		  ),
		);
	}
	
	public static function pushInterestList()
	{
		return array(  
		   'order_update'=>t("Order updates"),
		   'customer_new_signup'=>t("Customer new signup"),
		   'merchant_new_signup'=>t("Merchant new signup"),
		   'payout_request'=>t("Payout request"),		
		   'invoice'=>'Invoice',   
		);
	}
	
	public static function pushInterest()
	{
		return array(  
		   'order_update'=>'order_update',
		   'customer_new_signup'=>'customer_new_signup',
		   'merchant_new_signup'=>'merchant_new_signup',
		   'payout_request'=>'payout_request',		   
		   'invoice'=>'invoice',
		);
	}
	
	public static function cleanString($text='', $lower=true)
	{
		if(!empty($text)){
			if($lower){
				return trim( strtolower($text) );
			} else return trim($text);			
		}
		return $text;
	}
	
	public static function getSetSpecificCountry()
	{
		$provider = Yii::app()->params['settings']['map_provider'];
	    $country = Yii::app()->params['settings']['merchant_specific_country'];
	    $country = !empty($country)?json_decode($country,true):false;
	    $country_params = '';				
	    if(is_array($country) && count($country)>=1){
	   	   foreach ($country as $key=> $item) {		   	  	 
	   	   	  if($key<=0){
				 if($provider=="mapbox"){
					$country_params.="$item";
				 } else $country_params.="$item|";	   	  	  	 
	   	  	  } else {
				if($provider=="mapbox"){
					$country_params.=",";
					$country_params.="$item ";		   	  	 
				} else $country_params.="country:$item|";		   	  	 
			  }			  
	   	  }		
		  if(count($country)>1){
			 $country_params = substr($country_params,0,-1);	   	   
		  }	   	  
	   }
	   return $country_params;		   		  
	}
	
	public static function getSetSpecificCountryArray()
	{
	    $country = Yii::app()->params['settings']['merchant_specific_country'];
	    $country = !empty($country)?json_decode($country,true):false;
	    $country_params = array();
	    if(is_array($country) && count($country)>=1){
	   	   $country_params = $country;
	   }
	   return $country_params;		   		  
	}
	
	public static function dashboardOrdersTab()
	{
		return array(
		  'all'=>t("All"),
		  'order_processing'=>t("Processing"),
		  'order_ready'=>t("Ready"),
		  'completed_today'=>t("Completed"),		  
		);
	}	
	
	public static function dashboardItemTab()
	{
		return array(
		  'item_overview'=>array(
		    'title'=>t("Popular items"),
		    'sub_title'=>t("latest popular items"),
		  ),		  
		  'sales_overview'=>array(
		    'title'=>t("Last 30 days sales"),
		    'sub_title'=>t("sales for last 30 days"),
		  ),		  		  
		);
	}
		
	public static function dashboardPopularMerchantTab()
	{
		return array(
		  'popular'=>array(
		    'title'=>t("Popular merchants"),
		    'sub_title'=>t("best selling restaurant"),
		  ),		  
		  'review'=>array(
		    'title'=>t("Popular by review"),
		    'sub_title'=>t("most reviewed"),
		  ),		  		  
		);
	}
	
	public static function sizeList($merchant_id=0, $lang='')
	{
		$data = [];
		$stmt = "
		SELECT 		
		a.size_id,
		a.merchant_id,
		IF(COALESCE(NULLIF(b.size_name, ''), '') = '', a.size_name, b.size_name) as new_size_name,
		b.size_name		
		FROM {{size}} a
		left JOIN (
			SELECT size_name,size_id FROM {{size_translation}} where language=".q($lang)."
		) b 
		ON a.size_id = b.size_id
		WHERE 
		a.merchant_id=".q($merchant_id)."		
		";
		if ( $res = CCacheData::queryAll($stmt)){
			foreach ($res as $items) {
				$data[$items['size_id']] = $items['new_size_name'];
			}
		}
    	return $data;
	}
	
	public static function itemNameList($merchant_id=0, $lang='')
	{
		$data = CommonUtility::getDataToDropDown("{{item_translation}}",'item_id','item_name',
    	"
    	where language=".q(Yii::app()->language)." 
    	and item_id IN (
    	 select item_id from {{item}}
    	 where merchant_id=".q(intval($merchant_id))."
    	 and item_name IS NOT NULL AND TRIM(item_name) <> ''
    	)
    	"
    	);    	
    	return $data;
	}
	
	public static function cuisineGroup($lang='',$merchant_ids=array())
	{
		$data = array();
		$criteria=new CDbCriteria();
		$criteria->alias ="a";
		$criteria->select = "
		a.merchant_id, 
		(
		 select GROUP_CONCAT(cuisine_name)
		 from {{cuisine_translation}}
		 where language=".q($lang)."		 		 
		 and cuisine_name IS NOT NULL AND TRIM(cuisine_name) <> ''
		 and cuisine_id in (
		   select cuisine_id from {{cuisine_merchant}}
		   where merchant_id = a.merchant_id
		 )		 
		) as cuisine_group
		";
		$criteria->condition = "a.status=:status";
		$criteria->params = array(':status'=>'active');
		$criteria->addInCondition("a.merchant_id",$merchant_ids);		

		if($model = AR_merchant::model()->findAll($criteria)){
			foreach ($model as $item) {								
				if(!empty($item->cuisine_group)){
					$cuisine_group = explode(",",$item->cuisine_group);
					$data[$item->merchant_id] = $cuisine_group;
				}				
			}
			return $data;
		}
		throw new Exception( "No cuisine" );
	}
	
	public static function priceFormat()
	{
		return array(
		   'symbol'=>Price_Formatter::$number_format['currency_symbol'],
            'decimals'=>Price_Formatter::$number_format['decimals'],
            'decimal_separator'=>Price_Formatter::$number_format['decimal_separator'],
            'thousand_separator'=>Price_Formatter::$number_format['thousand_separator'],
            'position'=>Price_Formatter::$number_format['position'],
		);
	}
	
	public static function CategoryResponsiveSettings($size="full")
	{
		$responsive_data = array();
		if($size=="half"){
			$responsive_data[0] = array('items'=>1,'nav'=>true,'loop'=>false);
			$responsive_data[320] = array('items'=>3,'nav'=>true,'loop'=>false);
			$responsive_data[480] = array('items'=>4,'nav'=>true,'loop'=>false);
			$responsive_data[600] = array('items'=>5,'nav'=>true,'loop'=>false);
			$responsive_data[1000] = array('items'=>5,'nav'=>true,'loop'=>false);
			$responsive_data[1200] = array('items'=>5,'nav'=>true,'loop'=>false);		
		} elseif ( $size=="full" ){
			$responsive_data[0] = array('items'=>1,'nav'=>true,'loop'=>false);
			$responsive_data[320] = array('items'=>3,'nav'=>true,'loop'=>false);
			$responsive_data[480] = array('items'=>4,'nav'=>true,'loop'=>false);
			$responsive_data[600] = array('items'=>3,'nav'=>true,'loop'=>false);
			$responsive_data[1000] = array('items'=>8,'nav'=>true,'loop'=>true);
			$responsive_data[1200] = array('items'=>11,'nav'=>true,'loop'=>false);		
		}
		return $responsive_data;
	}

	public static function FrontCarouselResponsiveSettings($size="full")
	{
		$responsive_data = array();
		if($size=="half"){
			$responsive_data[0] = array('items'=>1,'nav'=>true,'loop'=>false);
			$responsive_data[320] = array('items'=>2,'nav'=>true,'loop'=>false);
			$responsive_data[480] = array('items'=>3,'nav'=>true,'loop'=>false);
			$responsive_data[600] = array('items'=>4,'nav'=>false,'loop'=>false);
			$responsive_data[1000] = array('items'=>5,'nav'=>false,'loop'=>false);			
		} elseif ( $size=="full" ){			
			$responsive_data[0] = array('items'=>1,'nav'=>false,'loop'=>false);
			$responsive_data[320] = array('items'=>2,'nav'=>false,'loop'=>false);
			$responsive_data[480] = array('items'=>3,'nav'=>false,'loop'=>false);
			$responsive_data[600] = array('items'=>4,'nav'=>false,'loop'=>true);
			$responsive_data[1000] = array('items'=>5,'nav'=>false,'loop'=>true);		
		}
		return $responsive_data;
	}
	
	public static function MoneyConfig($return_array = false)
	{	
		$prefix = ''; $suffix='';	
		$settings = Price_Formatter::$number_format;		
				
		if($settings['position']=="right"){
			$suffix=$settings['currency_symbol'];
		} else $prefix = $settings['currency_symbol'];
		
		$data = array(
		  'prefix'=>$prefix,
		  'suffix'=>$suffix,
		  'thousands'=>!empty($settings['thousand_separator'])?$settings['thousand_separator']:",",
		  'decimal'=>$settings['decimal_separator'],
		  'precision'=>intval($settings['decimals']),
		);
		return $return_array ? $data : json_encode($data);
	}
	
	public static function CashinAmount()
	{
		return array(
		  10=>Price_Formatter::formatNumber(10),
		  20=>Price_Formatter::formatNumber(20),
		  30=>Price_Formatter::formatNumber(30),
		);
	}
	
	public static function CashinMinimumAmount()
	{
		return 10;
	}
	
	public static function translationVendor()
	{
		return array(
		  'the_results_could_loaded'=>t("The results could not be loaded."),
		  'no_results'=>t("No results"),
		  'searching'=>t("Searching..."),
		  'the_results_could_not_found'=>t("The results could not be loaded."),
		  'loading_more_results'=>t("Loading more results"),
		  'remove_all_items'=>t("Remove all items"),
		  'remove_item'=>t("Remove item"),
		  'search'=>t("Search"),
		  'today'=>t("Today"),
		  'Yesterday'=>t("Yesterday"),
		  'last_7_days'=>t("Last 7 Days"),
		  'last_30_days'=>t("Last 30 Days"),
		  'this_month'=>t("This Month"),
		  'last_month'=>t("Last Month"),
		  'custom_range'=>t("Custom Range"),
		  'su'=>t("Su"),
		  'mo'=>t("Mo"),
		  'tu'=>t("Tu"),
		  'we'=>t("We"),
		  'th'=>t("Th"),
		  'fr'=>t("Fr"),
		  'sa'=>t("Sa"),
		  'january'=>t("January"),
		  'february'=>t("February"),
		  'march'=>t("March"),
		  'april'=>t("April"),
		  'may'=>t("May"),
		  'june'=>t("June"),
		  'july'=>t("July"),
		  'august'=>t("August"),
		  'september'=>t("September"),
		  'october'=>t("October"),
		  'november'=>t("November"),
		  'december'=>t("December"),
		  'search_sidebar'=>t("Search Side Bar"),
		  'delete'=>t("Delete Confirmation"),
		  'are_you_sure'=>t("Are you sure you want to permanently delete the selected item?"),
		  'cancel'=>t("Cancel"),
		  'delete'=>t("Delete"),
		  'duplicate_item'=>t("Duplicate Item"),
		  'duplicate'=>t("Duplicate"),
		  'duplicate_confirmation'=>t("Are you sure you want to duplicate this food item?"),
		  'delivery_address_required'=>t("Delivery address is required"),
		  'table_number_required'=>t("Table number is required"),
		  'please_select_customer'=>t("Please select customer"),
		  'processing'=>t("Processing..."),
		  'close_window'=>t("please don't close this window"),
		  'enter_your_location'=>t("Enter your location or select on the map"),
		  'payment_processing'=>t("Payment Processing."),
		  'confirm_deletion'=>t("Confirm deletion"),
		  'delete_records_confirm'=>t("Are you sure you want to delete this order? This action cannot be undone. Sent orders to kitchen will also be canceled."),
		  'yes'=>t("Yes"),
		  'cancel'=>t("Cancel"),
		  'deleting_records'=>t("Deleting records"),
		  'sending_receipt'=>t("Sending receipt...."),
		  'printing'=>t("Printing...."),
		  'websocket_error'=>t("WebSocket error occurred, but no detailed ErrorEvent is available."),
		  'websocket_is_not_open'=>t("WebSocket is not open. Ready state is:"),
		  'view'=>t("View"),
		  'clear_notifications'=>t("Clear notifications"),
		  'clear_all_items'=>t("Clear all items"),
		  'are_you_sure'=>t("are you sure?"),
		  'confirm'=>t("Confirm"),
		  'table_not_available'=>t("This table is not available"),
		  'sending_orders'=>t("Sending orders to kitchen"),
		  'unabled_to_pay'=>t("Unable to Pay since the cart containes new item(s) that has not been sent to the kitchen"),
		  'clear_items'=>t("Click to continue and clear new item(s)"),
		  'continue'=>t("Continue"),
		  'clearing_items'=>t("Clearing new items"),
		  'customer'=>t("Customer"),
		  'table_order_type'=>t("ORDER TYPE"),
		  'table_customer'=>t("CUSTOMER"),
		  'table_amount'=>t("AMOUNT"),
		  'table_date'=>t("DATE"),
		  'table_status'=>t("STATUS"),
		  'item'=>t("Item"),
		  'qty'=>t("Qty"),
		  'status'=>t("Status"),
		  'record_per_page'=>t("Records per page"),
		  'oflabel'=>t('of'),
		  'calendar_days'=>[ t("Sunday") , t("Monday") , t("Tuesday"), t("Wednesday") , t("Thursday") , t("Friday") , t("Saturday") ],
		  'calendar_short_days'=>[ t("Sun") , t("Mon") , t("Tue"), t("Wed") , t("Thu") , t("Fri") , t("Sat") ],
		  'calendar_months'=>[ t("January") , t("February") , t("March"), t("April") ,
		     t("May") , t("June") , t("July") , t("August"), t("September"), t("October") , t("November") ,t("December")
		  ],
		  'calendar_short_months'=>[ t("Jan") , t("Feb") , t("Mar"), t("Apr") ,
		     t("May") , t("Jun") , t("Jul") , t("Aug"), t("Sep"), t("Oct") , t("Nov") ,t("Dec")
		  ],
		  'days'=>t('days'),
		);
	}
	
	public static function suggestionTabs()
	{
		return array(
           //'all'=>t("All"),
		   'restaurant'=>t("Restaurant"),
		   'food'=>t("Food"),
		);		
	}

	public static function BannerType()
	{
		return array(
		   'food'=>t("Food"),		   
		);
	}

	public static function BannerType2()
	{
		return array(
		   'restaurant'=>t("Restaurant"),		   
		   'food'=>t("Food"),		   
		   'restaurant_featured'=>t("Restaurant featured"),
		   'cuisine'=>t("Cuisine"),
		);
	}

	public static function getPushJsonFile()
	{
		$settings = AR_admin_meta::getValue('push_json_file');
		$jsonfile = isset($settings['meta_value'])?$settings['meta_value']:'';		
		if(!empty($jsonfile)){
			$path = CommonUtility::uploadDestination('upload/all/'.$jsonfile);		
			if(file_exists($path)){
				return $path;
			}
		} 
		throw new Exception( 'json file not found' );
	}

	public static function getMerchantPushJsonFile($merchant_id=0)
	{
		$settings = AR_merchant_meta::getValue($merchant_id,'push_json_file');
		$jsonfile = isset($settings['meta_value'])?$settings['meta_value']:'';		
		if(!empty($jsonfile)){
			$path = CommonUtility::uploadDestination('upload/all/'.$jsonfile);		
			if(file_exists($path)){
				return $path;
			}
		} 
		throw new Exception( 'json file not found' );
	}

	public static function Channel()
	{
		return [
			'customer'=>t("Customer"),
			'merchant'=>t("Merchant"),
			'driver'=>t("Driver"),
			'single'=>t("Single App"),
			'kitchen'=>t("Kitchen")
		];
	}

	public static function PlatformList()
	{
		return [
			'android'=>t("android"),
			'ios'=>t("ios")			
		];
	}

	public static function OrderStatusGroup()
	{
		return [
			'order_status'=>t("Order Status"),
			'delivery_status'=>t("Delivery Status")
		];
	}

	public static function StatusColor($data='')
	{		
		$status_data = [];
		try {
			$meta = AR_admin_meta::getValue($data);
			$status = isset($meta['meta_value'])?$meta['meta_value']:'';			
			$status_data = AttributesTools::getStatusWithColor("delivery_status",$status);		
			return $status_data;			
		} catch (Exception $e) {
			return  [
				'font_color'=>'#f8af01',
				'bg_color'=>"white"
			];
		}
	}

	public static function getStatusWithColor($group_name='', $description='')
	{
		$criteria=new CDbCriteria();
		$criteria->addCondition("group_name=:group_name AND description=:description");
		$criteria->params = [
			':group_name'=>$group_name,
			':description'=>$description
		];
		if($model = AR_status::model()->find($criteria)){
			return [
				'font_color'=>$model->font_color_hex,
				'bg_color'=>$model->background_color_hex,
				'description'=>$model->description
			];
		}
		throw new Exception( Helper_not_found);
	}

	public static function lastOrderTab()
	{		
		$data = [];
		$data[] = [
			'label'=>t("All"),
			'value'=>"all"
		];
		$data[] = [
			'label'=>t("Processing"),
			'value'=>"order_processing"
		];
		$data[] = [
			'label'=>t("Ready"),
			'value'=>"order_ready"
		];
		$data[] = [
			'label'=>t("Completed"),
			'value'=>"completed_today"
		];
		return $data;
	}

	public static function createTimeRange($start, $end, $interval = '15 mins', $format = '24',$keyFormat="H:i:s")
	{
		$startTime = strtotime($start); 
	    $startEnd = strtotime($start); 
	    $endTime   = strtotime($end);
	    $returnTimeFormat = ($format == '12')?'g:i:s A':'H:i:s';		
	    $current   = time(); 
	    $addTime   = strtotime('+'.$interval, $current); 
	    $diff      = $addTime - $current;	
	    $times = array(); 	    
	    while ($startTime < $endTime) { 	 
	    	$start_time =  date("H:i", $startTime);   		    		    		    	
	    	$startEnd  += $diff; 
	    	$start_end =  date("H:i", $startEnd);  

			if($format=='24'){
				$pretty_time = Date_Formatter::Time($startTime,"HH:mm"); 
			} else $pretty_time = Date_Formatter::Time($startTime,"hh:mm a"); 

			//$key = date($returnTimeFormat, $startTime);
			$key = date($keyFormat, $startTime);
			$times[$key] = $pretty_time;
	        $startTime += $diff; 
	    } 	    
	    $start_time =  date("H:i", $startTime);  	       
	    return $times; 
	}

	public static function timeInvertval()
	{
		return [
			900 =>t("15min"),
			1800 =>t("30min"),
			3600 =>t("1h"),
			5400 =>t("1h 30min"),
			7200 =>t("2h"),
			9000 =>t("2h 30min"),
			10800 =>t("3h"),
		];
	}

	public static function timeInvertvalue()
	{
		return [
			900 =>t("15 mins"),
			1800 =>t("30 mins"),
			3600 =>t("1 hour"),
			5400 =>t("1 hour 30 mins"),
			7200 =>t("2 hour"),
			9000 =>t("2 hour 30 mins"),
			10800 =>t("3 hour"),
		];
	}

	public static function bookingStatus()
	{
		return [
			'pending'=>t("Pending"),
			'confirmed'=>t("Confirmed"),
			'cancelled'=>t("Cancelled"),
			'denied'=>t("Denied"),
			'finished'=>t("Finished"),
			'no_show'=>t("No show"),
			'waitlist'=>t("Wait list"),
		];
	}

	public static function someWords()
	{
		return [
			"we_detected"=>t("We detected your location is {address} is this correct?"),
			"address_detected"=>t("Address Detected"),
			"yes"=>t("Yes"),
			"no"=>t("No"),
			'confirm_delete_address'=>t("Are you sure you want to delete this address?"),
			'delete'=>t("Delete"),
			'delete_adress'=>t("Delete Address"),
			'cancel'=>t("Cancel"),
			'ok'=>t("Okay"),
			'search_sidebar'=>t("Search Side Bar"),
			'search_in_menu'=>t("Search in menu"),
			'clear'=>t("Clear"),
			'created_new_order'=>t("Create new order"),			
			'new_order_label'=>t("New order"),
			'please_select_valid_payment'=>t("Please select valid payment method"),
			'pickup_collection_confirm'=>t("Please note that for pickup orders, your items will be ready for collection at the restaurant."),
			'confirm'=>t("Confirm")
		];
	}

	public static function getMenuID($menu_type='',$menu_name='')
	{
		$model = AR_menu::model()->find("menu_type=:menu_type AND menu_name=:menu_name",[
			':menu_type'=>$menu_type,
			':menu_name'=>$menu_name,			
		]);
		if($model){
			return $model->menu_id;
		}
		return false;
	}

	public static function getMenuAction($menu_type='',$action_name='',$visible='1')
	{
		$model = AR_menu::model()->find("menu_type=:menu_type AND action_name=:action_name AND visible=:visible",[
			':menu_type'=>$menu_type,
			':action_name'=>$action_name,
			':visible'=>$visible
		]);
		if($model){
			return $model->menu_id;
		}
		return false;
	}

	public static function getMenuParentID($menu_type='',$menu_name='')
	{
		$model = AR_menu::model()->find("menu_type=:menu_type AND menu_name=:menu_name AND parent_id=:parent_id",[
			':menu_type'=>$menu_type,
			':menu_name'=>$menu_name,
			':parent_id'=>0
		]);
		if($model){
			return $model->menu_id;
		}
		return false;
	}

	public static function getLanguageList()
	{
		$dependency = CCacheData::dependency();        
		$criteria=new CDbCriteria();
        $criteria->select="code,title,description,flag,rtl";
        $criteria->condition = "status=:status ";		    
        $criteria->params  = array(			  
            ':status'=>'publish'
        );
        $criteria->order ="sequence ASC";
        $model = AR_language::model()->cache(Yii::app()->params->cache, $dependency)->findAll($criteria);
		if($model){
			$data = [];
			foreach ($model as $items) {				
				$data[] = [
					'code'=>$items->code,
					'title'=>$items->title,
					'description'=>$items->description,
					'rtl'=>$items->rtl,
					'flag'=>$items->flag,
					'flag_url'=>CMedia::themeAbsoluteUrl()."/assets/flag/". strtolower($items->flag) .".svg",				
				];
				$flag_list[$items->code] = CMedia::themeAbsoluteUrl()."/assets/flag/". strtolower($items->flag) .".svg";
			}
			return [
				'list'=>$data,
				'flag_list'=>$flag_list
			];
		}
		return false;
	}

	public static function DriverSalaryType()
	{
		return [
			'salary'=>t("Salary amount only"),			
			'delivery_fee'=>t("Delivery fee only"),
			'commission'=>t("Commission rate only"),
			'fixed'=>t("Fixed rate only"),
			'fixed_and_commission'=>t("Fixed amount + commission"),			
		];
	}

	public static function DriverEmploymentType()
	{
		return [
			'employee'=>t("Employee"),			
			'contractor'=>t("Independent contractor"),
		];
	}

	public static function DriverCommissionType()
	{
		return array(		  
		  'fixed'=>t("Fixed"),
		  'percentage'=>t("percentage"),
		);
	}

	public static function DriverAfterRegistationProcess()
	{
		return array(		  
			'need_approval'=>t("Approval needed"),
			'activate_account'=>t("Activate account"),
		  );
	}

	public static function generateCalendarData($lenght=13)
	{
		$start  = date("Y-m-d", strtotime('monday this week'));
		$end  = date("Y-m-d", strtotime('sunday this week'));		
		$data = [];
		$start = date('Y-m-d', strtotime($start . ' -1 day'));
		for ($x = 0; $x <= $lenght; $x++) {
			$start = date('Y-m-d', strtotime($start . ' +1 day'));
			$data[] = [
				'label'=>Date_Formatter::date($start,"eee",true),
				'caption'=>Date_Formatter::date($start,"dd",true),
				'value'=>$start
			];
		}
		return $data;
	}

	public static function breakDuration()
	{
		$data = [];
		$data[5] = t("5 minutes");
		$data[10] = t("10 minutes");
		$data[15] = t("15 minutes");
		$data[20] = t("20 minutes");
		$data[30] = t("30 minutes");
		$data[45] = t("45 minutes");
		$data[60] = t("1 hour");
		//$data[99] = t("Until end of shift");
		return $data;
	}

	public static function getLegalMenu()
	{
		$legal_menu = array();
		$legal_menu['page_privacy_policy'] = t("Privacy Policy");
		$legal_menu['page_terms'] = t("Terms and condition");
		$legal_menu['page_aboutus'] = t("About us");
		return $legal_menu;
	}

	public static function getSearchBarMenu($menu_type='',$role_id=0,$merchant_id=0)
	{
		$sub_query = ''; $sub_sub_query = '';				
		if($role_id>0){			
			
			$sub_sub_query="
	 	  	  AND action_name IN (
		 	 	  select action_name from {{role_access}}
		 	 	  where role_id=".q($role_id)."				  				  
		 	   )
	 	  	";

			$sub_query="
	 	  	  AND a.action_name IN (
		 	 	  select action_name from {{role_access}}
		 	 	  where role_id=".q($role_id)."				  				  
		 	   )
	 	  	";
		} else if ( $merchant_id>0){
			$sub_query="
				AND a.action_name IN (
				select meta_value from {{merchant_meta}}
				where merchant_id=".q($merchant_id)."
				and meta_name='menu_access'
			 )
			 ";
		}

		$stmt = "		
		select a.menu_name,a.link,
		(
		select GROUP_CONCAT(menu_name,';',link ORDER BY sequence ASC SEPARATOR ',')
			from {{menu}} b
			where parent_id = a.menu_id
			and status=1
		    and visible=1		
			$sub_sub_query	
		) as sub_menu
		from {{menu}} a
		where menu_type=".q($menu_type)."
		and parent_id=0
		and status=1
		and visible=1
		$sub_query
		order by sequence asc
		";						
		$dependency = CCacheData::dependency();					
        if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){			
			$data = [];
			foreach ($res as $items) {
				$children = [];
				$sub_menu = !empty($items['sub_menu'])?explode(",",$items['sub_menu']):'';
				if(is_array($sub_menu) && count($sub_menu)>=1){
					foreach ($sub_menu as $subitems) {						
						$sub_sub_menu = !empty($subitems)? explode(";",$subitems):'';
						if(is_array($sub_sub_menu) && count($sub_sub_menu)>=1){							
							$children[] = [
								'label'=>isset($sub_sub_menu[0])? t($sub_sub_menu[0]) :'',
								'link'=>isset($sub_sub_menu[1])? Yii::app()->createAbsoluteUrl($sub_sub_menu[1]) :'',
							];
						}
					}
				}
				$data[] = [
					'label'=>t($items['menu_name']),
					'link'=> !empty($items['link'])? Yii::app()->createAbsoluteUrl($items['link']):'',
					'children'=>$children
				];
			}			
			return $data;
		}
		throw new Exception( Helper_not_found);
	}

	public static function getInvoicePaymentInformation()
	{
		$payment_info = OptionsTools::find(['invoice_payment_bank_name','invoice_payment_bank_account_name',
              'invoice_payment_bank_account_number','invoice_payment_bank_custom_template'
        ]);		
		if(is_array($payment_info) && count($payment_info)>=1){
			$bank_name = isset($payment_info['invoice_payment_bank_name'])?$payment_info['invoice_payment_bank_name']:'';
			$account_name = isset($payment_info['invoice_payment_bank_account_name'])?$payment_info['invoice_payment_bank_account_name']:'';
			$account_number = isset($payment_info['invoice_payment_bank_account_number'])?$payment_info['invoice_payment_bank_account_number']:'';
			$custom_template = isset($payment_info['invoice_payment_bank_custom_template'])?$payment_info['invoice_payment_bank_custom_template']:'';
			return [
				'bank_name'=>$bank_name,
				'account_name'=>$account_name,
				'account_number'=>$account_number,
				'custom_template'=>$custom_template,
			];
		}
		return false;		
	}

	public static function getTransactionTypeDetails($service_code='',$language='')
	{
		$stmt = "
		SELECT
		a.service_name as original_service_name,		
		b.service_name		
		FROM {{services}} a
		left JOIN (
			SELECT service_id,service_name FROM {{services_translation}} where language=".q($language)."
		) b 
		on a.service_id = b.service_id

		WHERE a.service_code=".q($service_code)."
		";
		if($data=CCacheData::queryRow($stmt)){
			if($data){
				return [
					'service_name'=>empty($data['service_name'])?$data['original_service_name']:$data['service_name']
				];
			}
		}
		return false;
	}

	public static function getPointsRuleBased()
	{
		return [
			'sub_total'=>t("Sub total"),
			'cart_total'=>t("Cart total"),			
			'food_item'=>t("Food item"),
		];
	}

	public static function redemptionPolicy()
	{
		return [
			'universal'=>t("Universal Redemption"),
			'merchant_specific'=>t("Merchant-Specific Redemption"),			
		];
	}

	public static function redemptionCostCovered()
	{
		return [
			'website'=>t("Website Owner Covers the Points Cost"),
			'merchant'=>t("Merchant Covers the Points Cost"),			
		];
	}

	public static function pointsExpiryOptions()
	{
		return [
			1=>t("Expire at the end of the next year after you earned them"),
			2=>t("Expire at the end of the year from registration"),			
			4=>t("Never expired")		
		];
	}

	public static function pointsThresholds()
	{
		return 'points_thresholds';
	}
	
	public static function adminMetaList($meta_name='allergens',$language=KMRS_DEFAULT_LANGUAGE,$with_key=true)
	{		
		$stmt = "
		SELECT a.meta_id, a.meta_value as meta_value_original,
		b.meta_value
		FROM {{admin_meta}}	a
		left JOIN (
			SELECT meta_id,meta_value FROM {{admin_meta_translation}} where language = ".q($language)."
		) b 
		on a.meta_id = b.meta_id

		WHERE
		meta_name=".q($meta_name)."
		";		
		$dependency = CCacheData::dependency();					
        if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){
			foreach ($res as $items) {
				if($with_key){
					$data[ $items['meta_id'] ] = !empty($items['meta_value'])?$items['meta_value']:$items['meta_value_original'];
				} else $data[] = !empty($items['meta_value'])?$items['meta_value']:$items['meta_value_original'];				
			}
			return $data;
		}
		throw new Exception( 'no results' );
	}

	public static function getLanguageData($code='')
	{		
		$dependency = CCacheData::dependency();       		
		$model = AR_language::model()->cache(Yii::app()->params->cache, $dependency)->find("code=:code",[
			':code'=>$code
		]);
		if($model){
			return $model;
		}
		return false;
	}

	public static function getDiscountCount($transaction_type='',$expiration_date='',$merchant_id=0)
	{
		$criteria=new CDbCriteria();
		$criteria->addCondition("merchant_id=:merchant_id 
			AND transaction_type=:transaction_type 
			AND DATE(expiration_date)>=:expiration_date
			AND status=:status			
		");
		$criteria->params = [
            ':merchant_id'=>$merchant_id,
            ':transaction_type'=>$transaction_type,
            ':expiration_date'=>$expiration_date,
			':status'=>1
        ];
		$criteria->limit = 1;
		$count = AR_discount::model()->count($criteria); 
		return $count;
	}

	public static function getDiscount($transaction_type='',$expiration_date='',$merchant_id=0)
	{
		$criteria=new CDbCriteria();
		$criteria->addCondition("merchant_id=:merchant_id 
			AND transaction_type=:transaction_type 
			AND DATE(expiration_date)>=:expiration_date
			AND status=:status			
		");
		$criteria->params = [
            ':merchant_id'=>$merchant_id,
            ':transaction_type'=>$transaction_type,
            ':expiration_date'=>$expiration_date,
			':status'=>1
        ];
		$criteria->order = "start_date ASC";	
		if($model = AR_discount::model()->findAll($criteria)){
			$data = [];
			foreach ($model as $items) {
				$data[] = [
					'title'=>t($items->title),
					'description'=>$items->description,
					'discount_type'=>$items->discount_type,
					'amount'=>$items->amount,
					'minimum_amount'=>$items->minimum_amount,
					'maximum_amount'=>$items->maximum_amount,
					'expiration_date'=>$items->expiration_date,
					'discount_details'=>t("Receive a {{amount}} bonus credit on all top-ups of {{minimum_amount}} or more.",[
						'{{amount}}'=> $items->discount_type=="percentage" ? Price_Formatter::convertToRaw($items->amount,0)."%" : Price_Formatter::formatNumber($items->amount),
						'{{minimum_amount}}'=>Price_Formatter::formatNumber($items->minimum_amount),
					]),
					'valid_discount'=>t("Valid for top-ups made between {{start}} and {{end}}",[
						'{{start}}'=>Date_Formatter::date($items->start_date),
						'{{end}}'=>Date_Formatter::date($items->expiration_date)
					]),
				];
			}
			return $data;
		}
		throw new Exception( HELPER_NO_RESULTS );
	}

	public static function getDiscountToApply($amount=0,$transaction_type='',$date='',$merchant_id=0)
	{
		$criteria=new CDbCriteria();
		$criteria->addCondition("merchant_id=:merchant_id 
			AND transaction_type=:transaction_type 
			AND :amount>=minimum_amount
			AND DATE(expiration_date)>=:expiration_date
			AND status=:status			
		");
		$criteria->params = [
            ':merchant_id'=>$merchant_id,
            ':transaction_type'=>$transaction_type,
			':amount'=>$amount,
            ':expiration_date'=>$date,
			':status'=>1
        ];		
		$criteria->order = "start_date ASC";	
		$criteria->limit = 1;				
		if($model = AR_discount::model()->find($criteria)){			
			$bonus = $model->discount_type=="percentage"? $amount * ($model->amount/100) : $model->amount;
			return $bonus;
		}
		throw new Exception( HELPER_NO_RESULTS );
	}

	public static function getUserUnion($uuid='')
	{
		$stmt = "SELECT * FROM {{view_user_union}}
		WHERE uuid=".q($uuid)."	
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			return $res;
		}
		return false;
	}

	public static function priceUpFormat()
	{
		$price_format = [
			'decimalPlaces'=>Price_Formatter::$number_format['decimals'],
			'separator'=>Price_Formatter::$number_format['thousand_separator'],
			'decimal'=>Price_Formatter::$number_format['decimal_separator'],				
		];
		if(Price_Formatter::$number_format['position']=="right"){
			$price_format['suffix'] = Price_Formatter::$number_format['currency_symbol'];
		} else $price_format['prefix'] = Price_Formatter::$number_format['currency_symbol'];
		return $price_format;
	}

	public static function createSlug($slug='',$table='',$field_name='slug')
	{
		$stmt="SELECT count(*) as total FROM $table
		WHERE $field_name=".q($slug)."
		";
		if($res = Yii::app()->db->createCommand($stmt)->queryRow()){
			if($res['total']>0){
				$new_slug = $slug.$res['total'];
				return AttributesTools::createSlug($new_slug,$table,$field_name);
			}
		}
		return $slug;
	}

	public static function OrderTabs()
	{
		$first_status = 'in_progress';
		$order_status_list[]  = [
			'value'=>'in_progress',
			'label'=>t("In Progress")
		];
		$order_status_list[]  = [
			'value'=>'history',
			'label'=>t("Order History")
		];
		$order_status_list[]  = [
			'value'=>'all',
			'label'=>t("All")
		];
		return [
			'first_tab'=>$first_status,
			'list'=>$order_status_list
		];
	}

	public static function OrderStatusList($order_type='')
	{
		if($order_type=="in_progress"){
			$status = [
				'new','accepted','ready for pickup','assigned','acknowledged','on the way to restaurant',
				'arrived at restaurant','waiting for order','order pickup','delivery started',
				'arrived at customer','unassigned','delivery on its way','delayed'
			] ;
		} else if ( $order_type=="history") {
			$status = [
				'rejected','delivered','cancelled','complete','declined','failed','delivery failed'
			];
		} else {
			$status = null;
		}
		return $status;
	}

	public static function statusList2($lang=KMRS_DEFAULT_LANGUAGE, $description='')
    {

    	$where = '';
    	if(!empty($description)){
    		$where="WHERE a.description=".q($description);
    	}

		$stmt = "
		SELECT 
		a.stats_id,
		a.description,
		b.description as status,
		a.font_color_hex,a.background_color_hex
		FROM {{order_status}} a

		left JOIN (
			SELECT stats_id, description FROM {{order_status_translation}} where language = ".q($lang)."
		) b 
		on a.stats_id = b.stats_id

		$where
		";
		$dependency = CCacheData::dependency();
		if($res = Yii::app()->db->cache(Yii::app()->params->cache, $dependency)->createCommand($stmt)->queryAll()){
    		$data = array();
    		foreach ($res as $val) {
				$data[$val['description']] =  empty($val['status'])? $val['description'] :$val['status'] ;
    		}
    		return $data;
    	}
    	return false;
    }

	public static function kitchenStatus()
	{
		return [
			'queue'=>t("Queue"),
			'in progress'=>t("In progress"),
			'ready'=>t("Ready"),
			'delayed'=>t("Delayed"),
			'cancelled'=>t("Cancelled"),
			'completed'=>t("Completed"),
		];
	}

    public static function childrenList(int $merchant_id)
    {
        $merchant = AR_merchant::model()->findByPk($merchant_id);

        $children_list = array();
        if(isset($merchant->isChain) && $merchant->isChain === true){
            foreach ($merchant->children as $child){
                $children_list[$child->merchant_id]= $child->restaurant_name;
            }

            return $children_list;
        }

        return FALSE;
    }

    // Function to check if the point is inside the polygon using the ray-casting algorithm
    public static function isPointInPolygon($point, $polygon) {
        $x = $point['lon'];
        $y = $point['lat'];
        $inside = false;
        $n = count($polygon);

        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $xi = $polygon[$i]['lon'];
            $yi = $polygon[$i]['lat'];
            $xj = $polygon[$j]['lon'];
            $yj = $polygon[$j]['lat'];

            $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
            if ($intersect) {
                $inside = !$inside;
            }
        }

        return $inside;
    }


    public static function getZonesIDS($point)
    {
        // Fetch all city boundaries data grouped by city_id
        $cityBoundaries = AR_city_boundaries::model()->findAll(array(
            'order' => 'city_id ASC'  // Ensure data is ordered by city_id
        ));

        // Format data grouped by city_id
        $cities = array();
        $city_id = NULL;
        foreach ($cityBoundaries as $boundary) {
            if (!isset($cities[$boundary->city_id])) {
                $cities[$boundary->city_id] = array(
                    'id' => $boundary->city_id,
                    'polygon' => array(),
                );
            }
            // Add polygon point to the corresponding city_id
            $cities[$boundary->city_id]['polygon'][] = array(
                'lat' => $boundary->latitude,
                'lon' => $boundary->longitude,
            );
        }

        if (!empty($cities)) {
            foreach ($cities as $cityId => $city) {
                if (AttributesTools::isPointInPolygon($point, $city['polygon'])) {
                    $city_id = $cityId;
                    break;
                }

            }
        }

        if(!empty($city_id)){
            $criteria=new CDbCriteria();
            $criteria->compare('city_id', intval($city_id));

            $zone_ids = [];
            if($zones = AR_zones::model()->findAll($criteria)){
                foreach ($zones as $zone) {

                    $coordinates = json_decode($zone->quardinates, true);
                    $polygon = [];

                    foreach ($coordinates[0] as $coord) {
                        $polygon[] = [
                            'lat' => $coord['lat'],
                            'lon' => $coord['lng']
                        ];
                    }

                    if (AttributesTools::isPointInPolygon($point, $polygon)) {
                        $zone_ids[] = intval($zone->zone_id);
                    }

                }
            }

            return $zone_ids;
        }

        return NUll;




    }

}
/*end class*/