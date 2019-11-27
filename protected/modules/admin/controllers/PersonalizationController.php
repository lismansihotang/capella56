<?php 
class PersonalizationController extends Controller {
  public $menuname = 'personalization';
  public function actionIndex() {
    parent::actionIndex();
    $this->render('index');
  }
  public function actionSave() {
    try {
      $sql = "update useraccess set wallpaper = :wallpaper 
        where username = :username";
      $command=Yii::app()->db->createCommand($sql);
      $command->bindvalue(':wallpaper',$_POST['wallpaper'],PDO::PARAM_STR);
      $command->bindvalue(':username',Yii::app()->user->name,PDO::PARAM_STR);
      $command->execute();
      GetMessage(false,getcatalog('insertsuccess'));
    }
    catch (CDbException $e) {
      $transaction->rollBack();
      GetMessage(true,implode(" ",$e->errorInfo));
    }	
  }
}
