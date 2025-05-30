<?php
class AdminUserIdentity extends CUserIdentity
{
	const LOGIN_TOKEN="logintoken";
	const LOGIN_TYPE="admin";
	private $_id;
	
	public function authenticate()
	{				
		$user=AdminUser::model()->find('LOWER(username)=?',array(strtolower($this->username)));				
		if($user===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$user->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		elseif ($user->status!="active"){			
			$this->errorCode=self::ERROR_UNKNOWN_IDENTITY;
		} else {
						
			$role_id=0;			
			if($user->main_account!=1){
				$role_id = $user->role;
			}		
			
			$this->_id=$user->admin_id;
			$this->username=$user->username;
			$this->setState('first_name', $user->first_name);
			$this->setState('last_name', $user->last_name);
			$this->setState('email_address', $user->email_address);
			$this->setState('contact_number', $user->contact_number);
			$this->setState('avatar', $user->profile_photo);
			$this->setState('avatar_path', $user->path);
			$this->setState('login_type', self::LOGIN_TYPE );
			$this->setState('main_account', $user->main_account);
			$this->setState('role_id', intval($role_id) );
			$this->setState('user_uuid', $user->admin_id_token );
			$this->errorCode=self::ERROR_NONE;
						
			$user->session_token = CommonUtility::generateToken("{{admin_user}}",'session_token');			
			$user->last_login = CommonUtility::dateNow();
			$user->ip_address = CommonUtility::userIp();
			
			$this->setState(self::LOGIN_TOKEN, $user->session_token);
			
			$user->save();
			
		}
		return $this->errorCode==self::ERROR_NONE;
	}
	
	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
	
	public static function verifyAccess()
	{				
		/*ALLOW ACCESS IF MAIN ACCOUNT OF ADMIN*/
		$main_account = Yii::app()->user->getState("main_account");		
		$login_type = Yii::app()->user->getState("login_type");
				
		if($login_type!="admin"){
			return false;
		}
		
		if($main_account==1){
			return true;
		}
		
		$controller = Yii::app()->controller->id;
        $action = Yii::app()->controller->action->id;
        $actions = "$controller.$action";				
                
        $criteria=new CDbCriteria();
        $criteria->alias="a";              
        $criteria->condition = "a.action_name=:action_name AND b.admin_id=:admin_id";
        $criteria->join='LEFT JOIN {{admin_user}} b on a.role_id = b.role ';
        $criteria->params = array(
          ':action_name'=>$actions,
          ':admin_id'=>intval(Yii::app()->user->id)
        );                       
        $dependency = CCacheData::dependency();
        if($model = AR_role_access::model()->cache(Yii::app()->params->cache, $dependency)->find($criteria)){
        	return true;
        } else {
			if($actions=='admin.profile' || $actions=='admin.change_password'){
				return true;
			}
		}		
        return false;
	}

	public static function getRoleAccess()
	{
		$criteria=new CDbCriteria();
        $criteria->alias="a";              
		$criteria->select = "a.action_name";
        $criteria->condition = "b.admin_id=:admin_id";
        $criteria->join='LEFT JOIN {{admin_user}} b on a.role_id = b.role ';
        $criteria->params = array(          
			':admin_id'=>intval(Yii::app()->user->id)
        );                       
        $dependency = CCacheData::dependency();
        if($model = AR_role_access::model()->cache(Yii::app()->params->cache, $dependency)->findAll($criteria)){
			$data = [];
			foreach ($model as $key => $items) {				
				$data[$items->action_name]=$items->action_name;
			}
			return $data;
		} 
		return false;
	}

	public static function CheckHasAccess($access=[],$link='')
	{
		$main_account = Yii::app()->user->getState("main_account");		
		$login_type = Yii::app()->user->getState("login_type");

		if($login_type!="admin"){
			return false;
		}
		
		if($main_account==1){
			return true;
		}
		
		return in_array($link,(array)$access)?true:false;
	}
	
}
/*end class*/