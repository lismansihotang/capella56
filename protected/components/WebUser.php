<?php
class WebUser extends CWebUser {
  private $_model;
  public function getUseraccessid() {
		return $this->getState('__useraccessid');
  }
  
  public function setUseraccessid($value) {
		$this->setState('__useraccessid',$value);
	}

  public function getUsername() {
		return $this->getState('__username');
  }
  
  public function setUsername($value) {
		$this->setState('__username',$value);
	}

  public function getRealname() {
		return $this->getState('__realname');
  }
  
  public function setRealname($value) {
		$this->setState('__realname',$value);
	}

  public function getPassword() {
		return $this->getState('__password');
  }
  
  public function setPassword($value) {
		$this->setState('__password',$value);
	}

  public function getEmail() {
		return $this->getState('__email');
  }
  
  public function setEmail($value) {
		$this->setState('__email',$value);
	}

  public function getPhoneno() {
		return $this->getState('__phoneno');
  }
  
  public function setPhoneno($value) {
		$this->setState('__phoneno',$value);
	}

  public function getLanguageid() {
		return $this->getState('__languageid');
  }
  
  public function setLanguageid($value) {
		$this->setState('__languageid',$value);
	}

  public function getLanguagename() {
		return $this->getState('__languagename');
  }
  
  public function setLanguagename($value) {
		$this->setState('__languagename',$value);
	}

  public function getThemeid() {
		return $this->getState('__themeid');
  }
  
  public function setThemeid($value) {
		$this->setState('__themeid',$value);
	}

  public function getThemename() {
		return $this->getState('__themename');
  }
  
  public function setThemename($value) {
		$this->setState('__themename',$value);
	}

  public function getToken() {
		return $this->getState('__token');
  }
  
  public function setToken($value) {
		$this->setState('__token',$value);
	}
  
  public function getWallpaper() {
    return $this->getState('__wallpaper');
  }

  public function setWallpaper($value){
    return $this->setState('__wallpaper',$value);
  }

  public function getIsonline() {
    return $this->getState('__isonline');
  }

  public function setIsonline($value){
    return $this->setState('__isonline',$value);
  }

  public function getDefaultPlant() {
    return $this->getState('__plant');
  }

  public function setDefaultPlant($value){
    return $this->setState('__plant',$value);
  }

  public function getDefaultSloc() {
    return $this->getState('__sloc');
  }

  public function setDefaultSloc($value){
    return $this->setState('__sloc',$value);
  }

  public function getBaseCurrencyID() {
    return $this->getState('__basecurrencyid');
  }

  public function setBaseCurrencyID($value){
    return $this->setState('__basecurrencyid',$value);
  }

  public function getBaseCurrencyName() {
    return $this->getState('__basecurrencyid');
  }

  public function setBaseCurrencyName($value){
    return $this->setState('__basecurrencyname',$value);
  }
}