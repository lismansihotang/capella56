<?php
Yii::import('zii.widgets.CPortlet');
class Form extends CPortlet {
  public $menuname = '';
  public $isnew = 1;
  public $iswrite = 1;
  public $ispurge = 1;
  public $ispost = 0;
  public $isreject = 0;
  public $isupload = 1;
  public $isdownload = 1;
	public $ispdf = 1;
	public $isxls = 1;
	public $isdoc = 1;
	public $writebuttons = '';
	public $postbuttons = '';
	public $rejectbuttons = '';
	public $purgebuttons = '';
	public $downloadbuttons = '';
	public $downscript = '';
	public $otherbuttons = '';
	public $addonbuttons = '';
	public $addonscripts = '';
	public $formtype = 'master'; //master, masterdetail
	public $columns = null; //array columns / column header
	public $searchfield = null; //array search field
	public $addonsearchfield = '';
	public $beginedit = ''; //on begin edit header
	public $addload = '';
	public $rowstyler = '';
	public $loadsuccess = '';
	public $headerform = '';
	public $idfield = '';
	public $urlgetdata = '';
	public $url = '';
	public $wfapp = '';
	public $saveurl = '';
	public $updateurl = '';
	public $destroyurl = '';
	public $uploadurl = '';
	public $approveurl = '';
	public $rejecturl = '';
	public $downpdf = '';
	public $downxls = '';
	public $downdoc = '';
	public $columndetails = null; //array column details
  protected function renderContent() {
		$this->render('form');
	}
}