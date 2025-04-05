<?php
class PPages
{
	public static function menuType()
	{
		return 'website';
	}

	public static function menuMerchantType()
	{
		return 'website_merchant';
	}
	
	public static function menuActiveKey()
	{
		return 'theme_menu_active';
	}
	
	public static function all($lang='')
	{
		$data = CommonUtility::getDataToDropDown("{{pages_translation}}",'page_id','title',
    	"where language=".q($lang)." 
    	and title IS NOT NULL AND TRIM(title) <> ''
    	and page_id IN (
    	  select page_id from {{pages}}
    	  where page_type='page'
    	  and status='publish'
		  and owner='admin'
    	)
    	"
    	);
    	return $data;
	}

	public static function merchantPages($lang='',$merchant_id=0)
	{
		$data = CommonUtility::getDataToDropDown("{{pages_translation}}",'page_id','title',
    	"where language=".q($lang)." 
    	and title IS NOT NULL AND TRIM(title) <> ''
    	and page_id IN (
    	  select page_id from {{pages}}
    	  where page_type='page'
    	  and status='publish'
		  and owner='merchant'
		  and merchant_id=".q(intval($merchant_id))."
    	)
    	"
    	);
    	return $data;
	}
	
	public static function get($page_id=0)
	{
		$model = AR_pages::model()->findByPk( intval($page_id) );
		if($model){
			return $model;
		}
		throw new Exception( 'page not found' );
	}
	
	public static function pageDetailsSlug($slug='',$lang=KMRS_DEFAULT_LANGUAGE , $where_field='a.slug')
	{
		$stmt = "
		SELECT
		a.title as title_original,
		a.long_content as long_content_original,
		a.short_content as short_content_original,
		a.meta_title as meta_title_original,
		a.meta_description as meta_description_original,
		a.meta_keywords as meta_keywords_original,
		a.meta_image,a.path,
		b.title,
		b.long_content,
		b.meta_title,
		b.meta_description,
		b.meta_keywords
		FROM {{pages}} a
		left JOIN (
			SELECT page_id,title,long_content,meta_title,meta_description,meta_keywords
			FROM {{pages_translation}} where language = ".q($lang)."
		) b 
		on a.page_id = b.page_id
		WHERE
		$where_field = ".q($slug)."
		";		
		if($model = CCacheData::queryRow($stmt)){			
			return (object) [
				'title'=> empty($model['title']) ? $model['title_original'] : $model['title'],
				'long_content'=> empty($model['long_content']) ? $model['long_content_original'] : $model['long_content'],
				'meta_title'=> empty($model['meta_title']) ? $model['meta_title_original'] : $model['meta_title'],
				'meta_description'=> empty($model['meta_description']) ? $model['meta_description_original'] : $model['meta_description'],
				'meta_keywords'=> empty($model['meta_keywords']) ? $model['meta_keywords_original'] : $model['meta_keywords'],
				'image'=> !empty($model['meta_image']) ? CMedia::getImage($model['meta_image'],$model['path']) :'',
			];
		}
		throw new Exception( 'page not found' );		
	}

	public static function pageDetailsByID($page_id='', $lang='')
	{
		$criteria=new CDbCriteria();
		$criteria->alias ="a";
		$criteria->select="a.title, a.long_content, a.meta_title ,a.meta_description,a.meta_keywords,
		b.meta_image,b.path,b.slug as page_slug
		";
		$criteria->join='LEFT JOIN {{pages}} b on a.page_id = b.page_id ';
		$criteria->condition = "a.language=:language AND b.page_id=:page_id AND a.title IS NOT NULL AND TRIM(a.title) <> ''";
		$criteria->params = array(
		  ':language'=>$lang,
		  ':page_id'=>intval($page_id)
		);		
		$dependency = CCacheData::dependency();
		$model = AR_pages_translation::model()->cache(Yii::app()->params->cache, $dependency)->find($criteria);
		if($model){
			return $model;
		}
		throw new Exception( 'page not found' );
	}	
	
	public static function pageTitleBySlug($merchant_id='', $lang='')
	{
		$criteria=new CDbCriteria();
		$criteria->alias ="a";
		$criteria->select="a.title, a.long_content, a.meta_title ,a.meta_description,a.meta_keywords,
		b.meta_image,b.path,b.slug as page_slug
		";
		$criteria->join='LEFT JOIN {{pages}} b on a.page_id = b.page_id ';
		$criteria->condition = "a.language=:language AND b.merchant_id=:merchant_id AND a.title IS NOT NULL AND TRIM(a.title) <> ''";
		$criteria->params = array(
		  ':language'=>$lang,
		  ':merchant_id'=>intval($merchant_id)
		);		
		$dependency = CCacheData::dependency();
		$model = AR_pages_translation::model()->cache(Yii::app()->params->cache, $dependency)->findAll($criteria);
		if($model){			
			$data = [];
			foreach ($model as $items) {				
				$data[$items->page_slug] = $items->title;
			}
			return $data;
		}
		return false;
	}	

	public static function getPageBySlug($slug='')
	{
		$model = AR_pages::model()->find("slug=:slug",[
			':slug'=>$slug
		]);
		if($model){
			return $model;
		}
		throw new Exception( 'page not found' );
	}	

}
/*end class*/