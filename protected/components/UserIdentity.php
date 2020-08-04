<?php
class UserIdentity extends CUserIdentity {
	const ERROR_USER_PASSWORD_INVALID = 3;
	public $identityid;
	public function authenticate() {
		$connection	 = Yii::app()->db;
		$sql				 = 'select ifnull(count(1),0) as jumlah from useraccess 
			where lower(username) = :username and password = md5(:password)';
		$command		 = $connection->createCommand($sql);
		$command->bindvalue(':username', $this->username, PDO::PARAM_STR);
		$command->bindvalue(':password', $this->password, PDO::PARAM_STR);
		$user				 = $command->queryScalar();
		if ($user > 0) {
			$this->errorCode = self::ERROR_NONE;
			$sql = "select a.useraccessid, a.username, a.realname, a.password, a.email, a.phoneno, a.languageid, a.themeid, b.languagename, 
				c.themename, a.isonline, a.authkey, a.wallpaper
				from useraccess a 
				join language b on b.languageid = a.languageid 
				join theme c on c.themeid = a.themeid 
				where username = :username";
			$command		 = $connection->createCommand($sql);
			$command->bindvalue(':username', $this->username, PDO::PARAM_STR);
			$user = $command->queryRow();
			Yii::app()->user->useraccessid = $user['useraccessid'];
			Yii::app()->user->username = $user['username'];
			Yii::app()->user->realname = $user['realname'];
			Yii::app()->user->password = $user['password'];
			Yii::app()->user->email = $user['email'];
			Yii::app()->user->phoneno = $user['phoneno'];
			Yii::app()->user->languageid = $user['languageid'];
			Yii::app()->user->languagename = $user['languagename'];
			Yii::app()->user->themeid = $user['themeid'];
			Yii::app()->user->themename = $user['themename'];
			Yii::app()->user->isonline = $user['isonline'];
			Yii::app()->user->token = $user['authkey'];
			Yii::app()->user->wallpaper = $user['wallpaper'];
			Yii::app()->user->basecurrencyid = GetParamValue('basecurrencyid');
			Yii::app()->user->basecurrencyname = GetParamValue('basecurrencyname');
    	$sql = "SELECT c.menuvalueid
				FROM useraccess a 
				JOIN usergroup b ON b.useraccessid = a.useraccessid 
				JOIN groupmenuauth c ON c.groupaccessid = b.groupaccessid 
				WHERE c.menuauthid = 16 AND a.username = '".$this->username."' LIMIT 1";
			Yii::app()->user->defaultplant = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "SELECT c.menuvalueid
				FROM useraccess a 
				JOIN usergroup b ON b.useraccessid = a.useraccessid 
				JOIN groupmenuauth c ON c.groupaccessid = b.groupaccessid 
				WHERE c.menuauthid = 1 AND a.username = '".$this->username."' LIMIT 1";
			Yii::app()->user->defaultsloc = Yii::app()->db->createCommand($sql)->queryScalar();
		} else {
			$this->errorCode = self::ERROR_USER_PASSWORD_INVALID;
		}
		return $this->errorCode == self::ERROR_NONE;
	}
}