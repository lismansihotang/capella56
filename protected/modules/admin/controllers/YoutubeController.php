<?php
class YoutubeController extends Controller {
	public $menuname = 'youtube';
  public function actionIndex() {
		parent::actionIndex();
    $this->renderPartial('index',array());
  }
}