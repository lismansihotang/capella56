<?php
class DefaultController extends Controller {
	public $progress = 0;
	public function actionIndex() {
		if (Yii::app()->params['install'] == true) {
			Yii::app()->theme = 'startup';
			$this->render('index', array('progress' => $this->progress));
		} else {
			$this->redirect('site');
		}
	}
	public function actions() {
		return array(
			'connector' => array(
				'class' => 'ext.elFinder.ElFinderConnectorAction',
				'settings' => array(
					'mimeDetect' => "internal",
					'root' => Yii::getPathOfAlias('webroot').'/protected/config/',
					'URL' => Yii::app()->baseUrl,
					'rootAlias' => 'Home',
				)
			),
		);
	}
	public function actionSave() {
		$dbport		 = filterinput(1, 'dbport');
		$dbserver	 = filterinput(1, 'dbserver').';port='.$dbport;
		$dbuser		 = filterinput(1, 'dbuser');
		$dbpass		 = filterinput(1, 'dbpass');
		$dbname		 = filterinput(1, 'dbname');
		if ($dbport == '') {
			$dbport = '3306';
		}
		$connection	 = new CDbConnection('mysql:host='.$dbserver, $dbuser, $dbpass);
		$transaction = $connection->beginTransaction();
		try {
			$sql = "DROP DATABASE IF EXISTS `".$dbname."`";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE DATABASE `".$dbname."`";
			$connection->createCommand($sql)->execute();

			$sql = "USE `".$dbname."`";
			$connection->createCommand($sql)->execute();

			$this->progress = 5;

			$sql = "CREATE TABLE `parameter` (
					`paramid` INT(11) NOT NULL AUTO_INCREMENT,
					`paramname` VARCHAR(30) NOT NULL,
					`paramvalue` VARCHAR(45) NOT NULL,
					`description` VARCHAR(50) NOT NULL,
					PRIMARY KEY (`paramid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				replace into parameter (paramid,paramname,paramvalue,description) values (1,'sitename','".filterinput(1, 'sitename')."','Site Name');
				replace into parameter (paramid,paramname,paramvalue,description) values (2,'sitetitle','".filterinput(1, 'sitetitle')."','Site Title');			
				replace into parameter (paramid,paramname,paramvalue,description) values (3,'tagline','".filterinput(1, 'tagline')."','Tag Line');
				replace into parameter (paramid,paramname,paramvalue,description) values (4,'version','1.0','Version');				
				replace into parameter (paramid,paramname,paramvalue,description) values (5,'sitemode','1.0','Site Mode');
				replace into parameter (paramid,paramname,paramvalue,description) values (6,'email','".filterinput(1, 'email')."','Email');
				replace into parameter (paramid,paramname,paramvalue,description) values (7,'dateformat','".filterinput(1, 'dateformat')."','Date Format');
				replace into parameter (paramid,paramname,paramvalue,description) values (8,'timeformat','".filterinput(1, 'timeformat')."','Time Format');
				replace into parameter (paramid,paramname,paramvalue,description) values (9,'datetimeformat','".filterinput(1, 'datetimeformat')."','Date Time Format');
				replace into parameter (paramid,paramname,paramvalue,description) values (10,'weekstartson','".filterinput(1, 'weekstartson')."','Week Stars On');			
				replace into parameter (paramid,paramname,paramvalue,description) values (11,'defaultpagesize','".filterinput(1, 'defaultpagesize')."','Default Page Size');
				replace into parameter (paramid,paramname,paramvalue,description) values (12,'decimalseparator','".filterinput(1, 'decimalseparator')."','Decimal Separator');				
				replace into parameter (paramid,paramname,paramvalue,description) values (13,'groupseparator','".filterinput(1, 'groupseparator')."','Group Separator');				
				replace into parameter (paramid,paramname,paramvalue,description) values (14,'decimalqty','".filterinput(1, 'defaultnumberqty')."','Decimal for Qty');				
				replace into parameter (paramid,paramname,paramvalue,description) values (15,'decimalprice','".filterinput(1, 'defaultnumberprice')."','Decimal for Price');				
				replace into parameter (paramid,paramname,paramvalue,description) values (16,'smtpserver','".filterinput(1, 'smtpserver')."','SMTP Server');
				replace into parameter (paramid,paramname,paramvalue,description) values (17,'smtpport','".filterinput(1, 'smtpport')."','SMTP Port');
				replace into parameter (paramid,paramname,paramvalue,description) values (18,'fromemail','".filterinput(1, 'fromemail')."','From Email Notification');				
				replace into parameter (paramid,paramname,paramvalue,description) values (19,'reportengine','".filterinput(1, 'reportengine')."','Report Engine');	
				replace into parameter (paramid,paramname,paramvalue,description) values (20,'csvformat','^','CSV Format');
				replace into parameter (paramid,paramname,paramvalue,description) values (21,'basecurrency','Rp ','Base Currency');	
				replace into parameter (paramid,paramname,paramvalue,description) values (22,'basecurrencyid','40','Base Currency');	
				replace into parameter (paramid,paramname,paramvalue,description) values (23,'usinglog','1','Using Log');	
				replace into parameter (paramid,paramname,paramvalue,description) values (24,'sendnotifsms','0','Send Notification via SMS');
				replace into parameter (paramid,paramname,paramvalue,description) values (25,'sendnotifemail','0','Send Notification via Email');
				replace into parameter (paramid,paramname,paramvalue,description) values (26,'language','1','Default Language');
				replace into parameter (paramid,paramname,paramvalue,description) values (27,'language','1','Default Language');
			";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `language` (
					`languageid` INT(11) NOT NULL AUTO_INCREMENT,
					`languagename` VARCHAR(20) NULL DEFAULT NULL,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`languageid`),
					UNIQUE INDEX `uq_language_name` (`languagename`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				replace into language (languageid,languagename,recordstatus) values (1,'Indonesia',1);
				replace into language (languageid,languagename,recordstatus) values (2,'English',1);";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `country` (
					`countryid` INT(11) NOT NULL AUTO_INCREMENT,
					`countrycode` VARCHAR(2) NULL,
					`countryname` VARCHAR(30) NOT NULL,
					`recordstatus` INT(11) NOT NULL DEFAULT '0',
					PRIMARY KEY (`countryid`),
					UNIQUE INDEX `uq_country_code` (`countrycode`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (1, 'AD', 'ANDORRA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (2, 'AE', 'UNITED ARAB EMIRATES', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (3, 'AF', 'AFGHANISTAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (4, 'AG', 'ANTIGUA AND BARBUDA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (5, 'AI', 'ANGUILLA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (6, 'AL', 'ALBANIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (7, 'AM', 'ARMENIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (8, 'AN', 'NETHERLANDS ANTILLES', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (9, 'AO', 'ANGOLA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (10, 'AQ', 'ANTARCTICA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (11, 'AR', 'ARGENTINA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (12, 'AS', 'AMERICAN SAMOA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (13, 'AT', 'AUSTRIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (14, 'AU', 'AUSTRALIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (15, 'AW', 'ARUBA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (16, 'AZ', 'AZERBAIJAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (17, 'BA', 'BOSNIA HERZEGOVINA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (18, 'BB', 'BARBADOS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (19, 'BD', 'BANGLADESH', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (20, 'BE', 'BELGIUM', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (21, 'BF', 'BURKINA FASO', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (22, 'BG', 'BULGARIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (23, 'BH', 'BAHRAIN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (24, 'BI', 'BURUNDI', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (25, 'BJ', 'BENIN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (26, 'BM', 'BERMUDA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (27, 'BN', 'BRUNEI', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (28, 'BO', 'BOLIVIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (29, 'BR', 'BRAZIL', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (30, 'BS', 'BAHAMAS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (31, 'BT', 'BHUTAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (32, 'BV', 'BOUVET ISLAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (33, 'BW', 'BOTSWANA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (34, 'BY', 'BELARUS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (35, 'BZ', 'BELIZE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (36, 'CA', 'CANADA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (37, 'CC', 'COCOS ISLANDS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (38, 'CD', 'CONGO REPUBLIC', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (39, 'CF', 'CENTRAL AFRICA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (40, 'CG', 'CONGO', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (41, 'CH', 'SWITZERLAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (42, 'CI', 'COTE D\'IVOIRE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (43, 'CK', 'COOK ISLANDS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (44, 'CL', 'CHILI', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (45, 'CM', 'CAMEROON', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (46, 'CN', 'CHINA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (47, 'CO', 'COLOMBIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (48, 'CR', 'COSTA RICA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (49, 'CU', 'CUBA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (50, 'CV', 'CAPE VERDE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (51, 'CX', 'CHRISTMAS ISLAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (52, 'CY', 'CYPRUS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (53, 'CZ', 'CHECH REPUBLIC', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (54, 'DE', 'GERMAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (55, 'DJ', 'DJIBOUTI', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (56, 'DK', 'DENMARK', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (57, 'DM', 'DOMINICA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (58, 'DO', 'DOMINICAN REPUBLIC', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (59, 'DZ', 'ALGERIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (60, 'EC', 'ECUADOR', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (61, 'EE', 'ESTONIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (62, 'EG', 'EGYPT', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (63, 'ER', 'ERITREA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (64, 'ES', 'SPAIN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (65, 'ET', 'ETHIOPIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (66, 'FI', 'FINLANDIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (67, 'FJ', 'FIJI ISLANDS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (68, 'FM', 'MICRONESIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (69, 'FO', 'FAROE ISLANDS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (70, 'FR', 'FRANCE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (71, 'GA', 'GABON', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (72, 'GD', 'GRENADA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (73, 'GE', 'GEORGIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (74, 'GF', 'FRENCH GUIANA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (75, 'GH', 'GHANA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (76, 'GI', 'GIBRALTAR', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (77, 'GL', 'GREENLAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (78, 'GM', 'GAMBIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (79, 'GN', 'GUINEA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (80, 'GP', 'GUADELOUPE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (81, 'GQ', 'EQUATORIAL GUINEA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (82, 'GR', 'GREECE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (83, 'GT', 'GUATEMALA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (84, 'GU', 'GUAM', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (85, 'GW', 'GUINEA-BISSAU', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (86, 'GY', 'GUYANA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (87, 'HK', 'HONGKONGS.A.R.', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (88, 'HN', 'HONDURAS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (89, 'HR', 'CROATIA(HRVATSKA)', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (90, 'HT', 'HAITI', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (91, 'HU', 'HUNGARIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (92, 'ID', 'INDONESIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (93, 'IE', 'IRELAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (94, 'IL', 'ISRAEL', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (95, 'IN', 'INDIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (96, 'IO', 'BRITISH INDIAN OCEAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (97, 'IQ', 'IRAQ', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (98, 'IR', 'IRAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (99, 'IS', 'ICELAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (100, 'IT', 'ITALIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (101, 'JM', 'JAMAICA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (102, 'JO', 'JORDAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (103, 'JP', 'JAPAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (104, 'KE', 'KENYA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (105, 'KG', 'KYRGYZSTAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (106, 'KH', 'CAMBODIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (107, 'KI', 'KIRIBATI', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (108, 'KM', 'COMOROS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (109, 'KP', 'NORTH KOREAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (110, 'KR', 'SOUTH KOREA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (111, 'KW', 'KUWAIT', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (112, 'KY', 'CAYMAN ISLANDS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (113, 'KZ', 'KAZAKHSTAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (114, 'LA', 'LAOS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (115, 'LB', 'LEBANON', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (116, 'LI', 'LIECHTENSTEIN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (117, 'LK', 'SRILANKA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (118, 'LR', 'LIBERIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (119, 'LS', 'LESOTHO', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (120, 'LT', 'LITHUANIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (121, 'LU', 'LUXEMBOURG', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (122, 'LV', 'LATVIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (123, 'LY', 'LIBYA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (124, 'MA', 'MOROCCO', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (125, 'MC', 'MONACO', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (126, 'MD', 'REPUBLIC OF MOLDOVA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (127, 'MG', 'MADAGASKAR', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (128, 'MH', 'MARSHALL ISLANDS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (129, 'MK', 'REPUBLIC OF MACEDONIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (130, 'ML', 'MALI', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (131, 'MM', 'MYANMAR', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (132, 'MN', 'MONGOLIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (133, 'MO', 'MACAUS.A.R.', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (134, 'MQ', 'MARTINIQUE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (135, 'MR', 'MAURITANIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (136, 'MS', 'MONTSERRAT', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (137, 'MT', 'MALTA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (138, 'MU', 'MAURITIUS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (139, 'MV', 'MALDIVES', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (140, 'MW', 'MALAWI', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (141, 'MX', 'MEXICO', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (142, 'MY', 'MALAYSIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (143, 'MZ', 'MOZAMBIQUE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (144, 'NA', 'NAMIBIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (145, 'NC', 'NEW CALEDONIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (146, 'NE', 'NIGER', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (147, 'NF', 'NORFOLK ISLAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (148, 'NG', 'NIGERIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (149, 'NI', 'NICARAGUA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (150, 'NL', 'NETHERLAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (151, 'NO', 'NORWAY', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (152, 'NP', 'NEPAL', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (153, 'NR', 'NAURU', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (154, 'NU', 'NIUE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (155, 'NZ', 'NEW ZEALAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (156, 'OM', 'OMAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (157, 'PA', 'PANAMA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (158, 'PE', 'PERU', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (159, 'PF', 'FRENCH POLYNESIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (160, 'PG', 'PAPUA NEW GUINEA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (161, 'PH', 'PHILIPINES', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (162, 'PK', 'PAKISTAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (163, 'PL', 'POLAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (164, 'PN', 'PITCAIRN ISLAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (165, 'PR', 'PUERTORICO', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (166, 'PT', 'PORTUGAL', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (167, 'PW', 'PALAU', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (168, 'PY', 'PARAGUAY', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (169, 'QA', 'QATAR', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (170, 'RE', 'REUNION', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (171, 'RO', 'ROMANIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (172, 'RU', 'RUSSIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (173, 'RW', 'RWANDA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (174, 'SA', 'SAUDIARABIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (175, 'SB', 'SOLOMONISLANDS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (176, 'SC', 'SEYCHELLES', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (177, 'SD', 'SUDAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (178, 'SE', 'SWEDIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (179, 'SG', 'SINGAPORE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (180, 'SH', 'SAINTHELENA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (181, 'SI', 'SLOVENIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (182, 'SK', 'SLOVAKIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (183, 'SL', 'SIERRALEONE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (184, 'SM', 'SANMARINO', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (185, 'SN', 'SENEGAL', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (186, 'SO', 'SOMALIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (187, 'SR', 'SURINAME', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (188, 'ST', 'SAOTOMEANDPRINCIPE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (189, 'SV', 'ELSALVADOR', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (190, 'SY', 'SYRIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (191, 'SZ', 'SWAZILAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (192, 'TD', 'CHAD', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (193, 'TG', 'TOGO', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (194, 'TH', 'THAILAND', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (195, 'TJ', 'TAJIKISTAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (196, 'TK', 'TOKELAU', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (197, 'TM', 'TURKMENISTAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (198, 'TN', 'TUNISIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (199, 'TO', 'TONGA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (200, 'TP', 'TIMORTIMUR', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (201, 'TR', 'TURKI', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (202, 'TT', 'TRINIDADANDTOBAGO', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (203, 'TV', 'TUVALU', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (204, 'TW', 'TAIWAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (205, 'TZ', 'TANZANIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (206, 'UA', 'UKRAINE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (207, 'UG', 'UGANDA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (208, 'UK', 'INGGRIS', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (209, 'US', 'UNITED STATES OF AMERICA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (210, 'UY', 'URUGUAY', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (211, 'UZ', 'UZBEKISTAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (212, 'VA', 'VATICANCITY', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (213, 'VE', 'VENEZUELA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (214, 'VN', 'VIETNAM', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (215, 'VU', 'VANUATU', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (216, 'WS', 'SAMOA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (217, 'YE', 'YAMAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (218, 'YT', 'MAYOTTE', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (219, 'YU', 'YUGOSLAVIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (220, 'ZA', 'AFRIKASELATAN', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (221, 'ZM', 'ZAMBIA', 1);
				insert into `country` (`countryid`, `countrycode`, `countryname`, `recordstatus`) VALUES (223, 'ZW', 'ZIMBABWE', 1);";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `province` (
					`provinceid` INT(11) NOT NULL AUTO_INCREMENT,
					`countryid` INT(11) NOT NULL,
					`provincecode` VARCHAR(2) NULL,
					`provincename` VARCHAR(30) NOT NULL,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`provinceid`),
					INDEX `fk_province_country` (`countryid`),
					CONSTRAINT `fk_province_country` FOREIGN KEY (`countryid`) REFERENCES `country` (`countryid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (1, 92, '11', 'ACEH', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (2, 92, '12', 'SUMATERA UTARA', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (3, 92, '13', 'SUMATERA BARAT', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (4, 92, '14', 'RIAU', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (5, 92, '15', 'JAMBI', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (6, 92, '16', 'SUMATERA SELATAN', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (7, 92, '17', 'BENGKULU', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (8, 92, '18', 'LAMPUNG', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (9, 92, '19', 'KEPULAUAN BANGKA BELITUNG', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (10, 92, '21', 'KEPULAUAN RIAU', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (11, 92, '31', 'DKI JAKARTA', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (12, 92, '32', 'JAWA BARAT', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (13, 92, '33', 'JAWA TENGAH', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (14, 92, '34', 'DI YOGYAKARTA', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (15, 92, '35', 'JAWA TIMUR', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (16, 92, '36', 'BANTEN', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (17, 92, '51', 'BALI', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (18, 92, '52', 'NUSA TENGGARA BARAT', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (19, 92, '53', 'NUSA TENGGARA TIMUR', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (20, 92, '61', 'KALIMANTAN BARAT', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (21, 92, '62', 'KALIMANTAN TENGAH', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (22, 92, '63', 'KALIMANTAN SELATAN', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (23, 92, '64', 'KALIMANTAN TIMUR', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (24, 92, '65', 'KALIMANTAN UTARA', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (25, 92, '71', 'SULAWESI UTARA', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (26, 92, '72', 'SULAWESI TENGAH', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (27, 92, '73', 'SULAWESI SELATAN', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (28, 92, '74', 'SULAWESI TENGGARA', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (29, 92, '75', 'GORONTALO', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (30, 92, '76', 'SULAWESI BARAT', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (31, 92, '81', 'MALUKU', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (32, 92, '82', 'MALUKU UTARA', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (33, 92, '91', 'PAPUA BARAT', 1);
				insert into `province` (`provinceid`, `countryid`, `provincecode`, `provincename`, `recordstatus`) VALUES (34, 92, '94', 'PAPUA', 1);";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `city` (
					`cityid` INT(11) NOT NULL AUTO_INCREMENT,
					`provinceid` INT(11) NOT NULL,
					`citycode` VARCHAR(5) NOT NULL,
					`cityname` VARCHAR(40) NOT NULL,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`cityid`),
					UNIQUE INDEX `uq_city_prov` (`provinceid`, `cityname`),
					CONSTRAINT `fk_city_prov` FOREIGN KEY (`provinceid`) REFERENCES `province` (`provinceid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (1, 1, '1101', 'Sinabung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (2, 1, '1102', 'Singkil', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (3, 1, '1103', 'Tapakutan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (4, 1, '1104', 'Kutacane', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (5, 1, '1105', 'Langsa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (6, 1, '1106', 'Takengon', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (7, 1, '1107', 'Meulaboh', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (8, 1, '1108', 'Jantoi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (9, 1, '1109', 'Sigli', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (10, 1, '1110', 'Bireuen', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (11, 1, '1111', 'Lhokseumawe', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (12, 1, '1112', 'Blangpidie', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (13, 1, '1113', 'Blangkejeran', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (14, 1, '1114', 'Karang Baru', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (15, 1, '1115', 'Suka Makmue', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (16, 1, '1116', 'Calang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (17, 1, '1117', 'Simpang Tiga Redelong', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (18, 1, '1118', 'Meureudu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (19, 1, '1171', 'Banda Aceh', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (20, 1, '1172', 'Kota Sabang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (21, 1, '1173', 'Kota Langsa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (22, 1, '1174', 'Kota Lhokseumawe', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (23, 1, '1175', 'Subulussalam', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (24, 2, '1201', 'Gunungsitoli', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (25, 2, '1202', 'Penyabungan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (26, 2, '1203', 'Padang Sidempuan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (27, 2, '1204', 'Sibolga', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (28, 2, '1205', 'Tarutung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (29, 2, '1206', 'Balige', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (30, 2, '1207', 'Rantauprapat', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (31, 2, '1208', 'Kisaran', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (32, 2, '1209', 'Pematangsiantar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (33, 2, '1210', 'Sidikalang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (34, 2, '1211', 'Kabanjahe', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (35, 2, '1212', 'Lubukpakam', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (36, 2, '1213', 'Stabat', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (37, 2, '1214', 'Teluk Dalam', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (38, 2, '1215', 'Dolok Sanggul', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (39, 2, '1216', 'Salak', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (40, 2, '1217', 'Pangururan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (41, 2, '1218', 'Sei Rampah', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (42, 2, '1219', 'Lima Puluh', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (43, 2, '1271', 'Kota Sibolga', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (44, 2, '1272', 'Tanjung Balai', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (45, 2, '1273', 'Pematang Siantar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (46, 2, '1274', 'Tebingtinggi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (47, 2, '1275', 'Medan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (48, 2, '1276', 'Binjai', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (49, 2, '1277', 'Kota Padang Sidempuan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (50, 3, '1301', 'Tua Pejat', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (51, 3, '1302', 'Painan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (52, 3, '1303', 'Solok', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (53, 3, '1304', 'Muaro Sijunjung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (54, 3, '1305', 'Batusangkar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (55, 3, '1306', 'Pariaman', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (56, 3, '1307', 'Lubukbasung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (57, 3, '1308', 'Payakumbuh', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (58, 3, '1309', 'Lubuksikaping', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (59, 3, '1310', 'Padang Aro', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (60, 3, '1311', 'Pulau Punjung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (61, 3, '1312', 'Simpang Empat', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (62, 3, '1371', 'Padang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (63, 3, '1372', 'Kota Solok', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (64, 3, '1373', 'Sawah Lunto', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (65, 3, '1374', 'Padang Panjang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (66, 3, '1375', 'Bukittinggi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (67, 3, '1376', 'Kota Payakumbuh', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (68, 3, '1377', 'Kota Pariaman', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (69, 4, '1401', 'Teluk Kuantan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (70, 4, '1402', 'Rengat', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (71, 4, '1403', 'Tembilahan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (72, 4, '1404', 'Pangkalan Kerinci', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (73, 4, '1405', 'Siak Sriindrapura', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (74, 4, '1406', 'Bangkinang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (75, 4, '1407', 'Pasir Pangaraian', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (76, 4, '1408', 'Bengkalis', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (77, 4, '1409', 'Ujung Tanjung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (78, 4, '1471', 'Pekanbaru', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (79, 4, '1473', 'Dumai', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (80, 5, '1501', 'Sungaipenuh', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (81, 5, '1502', 'Bangko', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (82, 5, '1503', 'Sarolangun', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (83, 5, '1504', 'Muara Bulian', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (84, 5, '1505', 'Sengeti', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (85, 5, '1506', 'Muara Sabak', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (86, 5, '1507', 'Kuala Tungkal', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (87, 5, '1508', 'Muara Tebo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (88, 5, '1509', 'Muara Bungo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (89, 5, '1571', 'Jambi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (90, 6, '1601', 'Baturaja', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (91, 6, '1602', 'Kayu Agung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (92, 6, '1603', 'Muara Enim', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (93, 6, '1604', 'Lahat', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (94, 6, '1605', 'Lubuk Linggau', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (95, 6, '1606', 'Sekayu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (96, 6, '1607', 'Banyuasin', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (97, 6, '1608', 'Muaradua', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (98, 6, '1609', 'Martapura', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (99, 6, '1610', 'Indralaya', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (100, 6, '1611', 'Tebing Tinggi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (101, 6, '1671', 'Palembang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (102, 6, '1672', 'Prabumulih', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (103, 6, '1673', 'Pagaralam', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (104, 6, '1674', 'Lubuklinggau', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (105, 7, '1701', 'Manna', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (106, 7, '1702', 'Curup', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (107, 7, '1703', 'Argamakmur', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (108, 7, '1704', 'Bintuhan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (109, 7, '1705', 'Tais', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (110, 7, '1706', 'Mukomuko', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (111, 7, '1707', 'Tubei', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (112, 7, '1708', 'Kepahiang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (113, 7, '1771', 'Bengkulu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (114, 8, '1801', 'Liwa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (115, 8, '1802', 'Kotaagung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (116, 8, '1803', 'Kalianda', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (117, 8, '1804', 'Sukadana', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (118, 8, '1805', 'Gunungsugih', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (119, 8, '1806', 'Kotabumi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (120, 8, '1807', 'Blambangan Umpu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (121, 8, '1808', 'Menggala', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (122, 8, '1871', 'Bandar Lampung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (123, 8, '1872', 'Metro', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (124, 9, '1901', 'Sungailiat', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (125, 9, '1902', 'Tanjungpandan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (126, 9, '1903', 'Toboali', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (127, 9, '1904', 'Koba', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (128, 9, '1905', 'Mentok', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (129, 9, '1906', 'Manggar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (130, 9, '1971', 'Pangkal Pinang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (131, 10, '2101', 'Tanung Balai Karimun', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (132, 10, '2102', 'Tanjungpinang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (133, 10, '2103', 'Ranai', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (134, 10, '2104', 'Daik Lingga', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (135, 10, '2171', 'Batam', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (136, 10, '2172', 'Kota Tanjungpinang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (137, 11, '3101', 'Pulau Pramuka Kec. Kep. Seribu Utara', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (138, 11, '3171', 'Jakarta Selatan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (139, 11, '3172', 'Jakarta Timur', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (140, 11, '3173', 'Jakarta Pusat', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (141, 11, '3174', 'Puri Kembangan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (142, 11, '3175', 'Jakarta Utara', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (143, 12, '3201', 'Cibinong', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (144, 12, '3202', 'Sukabumi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (145, 12, '3203', 'Cianjur', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (146, 12, '3204', 'Soreang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (147, 12, '3205', 'Garut', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (148, 12, '3206', 'Tasikmalaya', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (149, 12, '3207', 'Ciamis', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (150, 12, '3208', 'Kuningan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (151, 12, '3209', 'Sumber', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (152, 12, '3210', 'Majalengka', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (153, 12, '3211', 'Sumedang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (154, 12, '3212', 'Indramayu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (155, 12, '3213', 'Subang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (156, 12, '3214', 'Purwakarta', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (157, 12, '3215', 'Karawang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (158, 12, '3216', 'Bekasi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (159, 12, '3217', 'Ngamprah', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (160, 12, '3271', 'Bogor', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (161, 12, '3272', 'Kota Sukabumi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (162, 12, '3273', 'Bandung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (163, 12, '3274', 'Cirebon', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (164, 12, '3275', 'Kota Bekasi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (165, 12, '3276', 'Depok', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (166, 12, '3277', 'Cimahi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (167, 12, '3278', 'Kota Tasikmalaya', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (168, 12, '3279', 'Banjar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (169, 13, '3301', 'Cilacap', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (170, 13, '3302', 'Purwokerto', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (171, 13, '3303', 'Purbalingga', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (172, 13, '3304', 'Banjarnegara', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (173, 13, '3305', 'Kebumen', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (174, 13, '3306', 'Purworejo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (175, 13, '3307', 'Wonosobo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (176, 13, '3308', 'Mungkid', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (177, 13, '3309', 'Boyolali', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (178, 13, '3310', 'Klaten', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (179, 13, '3311', 'Sukoharjo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (180, 13, '3312', 'Wonogiri', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (181, 13, '3313', 'Karanganyar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (182, 13, '3314', 'Sragen', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (183, 13, '3315', 'Grobogan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (184, 13, '3316', 'Blora', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (185, 13, '3317', 'Rembang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (186, 13, '3318', 'Pati', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (187, 13, '3319', 'Kudus', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (188, 13, '3320', 'Jepara', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (189, 13, '3321', 'Demak', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (190, 13, '3322', 'Ungaran', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (191, 13, '3323', 'Temanggung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (192, 13, '3324', 'Kendal', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (193, 13, '3325', 'Batang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (194, 13, '3326', 'Kajen', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (195, 13, '3327', 'Pemalang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (196, 13, '3328', 'Slawi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (197, 13, '3329', 'Brebes', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (198, 13, '3371', 'Magelang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (199, 13, '3372', 'Surakarta', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (200, 13, '3373', 'Salatiga', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (201, 13, '3374', 'Semarang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (202, 13, '3375', 'Pekalongan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (203, 13, '3376', 'Tegal', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (204, 14, '3401', 'Wates', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (205, 14, '3402', 'Bantul', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (206, 14, '3403', 'Wonosari', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (207, 14, '3404', 'Sleman', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (208, 14, '3471', 'Yogyakarta', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (209, 15, '3501', 'Pacitan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (210, 15, '3502', 'Ponorogo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (211, 15, '3503', 'Trenggalek', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (212, 15, '3504', 'Tulungagung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (213, 15, '3505', 'Blitar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (214, 15, '3506', 'Kediri', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (215, 15, '3507', 'Kepanjen', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (216, 15, '3508', 'Lumajang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (217, 15, '3509', 'Jember', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (218, 15, '3510', 'Banyuwangi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (219, 15, '3511', 'Bondowoso', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (220, 15, '3512', 'Situbondo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (221, 15, '3513', 'Probolinggo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (222, 15, '3514', 'Pasuruan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (223, 15, '3515', 'Sidoarjo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (224, 15, '3516', 'Mojokerto', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (225, 15, '3517', 'Jombang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (226, 15, '3518', 'Nganjuk', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (227, 15, '3519', 'Madiun', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (228, 15, '3520', 'Magetan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (229, 15, '3521', 'Ngawi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (230, 15, '3522', 'Bonjonegoro', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (231, 15, '3523', 'Tuban', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (232, 15, '3524', 'Lamongan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (233, 15, '3525', 'Gresik', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (234, 15, '3526', 'Bangkalan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (235, 15, '3527', 'Sampang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (236, 15, '3528', 'Pamekasan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (237, 15, '3529', 'Sumenep', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (238, 15, '3571', 'Kota Kediri', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (239, 15, '3572', 'Kota Blitar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (240, 15, '3573', 'Malang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (241, 15, '3574', 'Kota Probolinggo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (242, 15, '3575', 'Kota Pasuruan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (243, 15, '3576', 'Kota Mojokerto', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (244, 15, '3577', 'Kota Madiun', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (245, 15, '3578', 'Kota Surabaya', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (246, 15, '3579', 'Kota Batu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (247, 16, '3601', 'Padeglang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (248, 16, '3602', 'Rangkasbitung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (249, 16, '3603', 'Tigaraksa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (250, 16, '3604', 'Serang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (251, 16, '3671', 'Tangerang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (252, 16, '3672', 'Cilegon', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (253, 17, '5101', 'Negara', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (254, 17, '5102', 'Tabanan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (255, 17, '5103', 'Badung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (256, 17, '5104', 'Gianyar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (257, 17, '5105', 'Klungkung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (258, 17, '5106', 'Bangli', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (259, 17, '5107', 'Karangasem', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (260, 17, '5108', 'Singaraja', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (261, 17, '5171', 'Denpasar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (262, 18, '5201', 'Mataram', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (263, 18, '5202', 'Praya', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (264, 18, '5203', 'Selong', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (265, 18, '5204', 'Sumbawa Besar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (266, 18, '5205', 'Dompu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (267, 18, '5206', 'Raba', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (268, 18, '5207', 'Taliwang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (269, 18, '5271', 'Kota Mataram', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (270, 18, '5272', 'Bima', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (271, 19, '5301', 'Waikabubak', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (272, 19, '5302', 'Waingapu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (273, 19, '5303', 'Kupang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (274, 19, '5304', 'Soe', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (275, 19, '5305', 'Kefamenanu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (276, 19, '5306', 'Atambua', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (277, 19, '5307', 'Kalabhi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (278, 19, '5308', 'Lewoleba', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (279, 19, '5309', 'Larantuka', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (280, 19, '5310', 'Maumere', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (281, 19, '5311', 'Ende', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (282, 19, '5312', 'Bajawa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (283, 19, '5313', 'Ruteng', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (284, 19, '5314', 'Baa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (285, 19, '5315', 'Labuan Bajo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (286, 19, '5316', 'Tambolaka', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (287, 19, '5317', 'Waibakul', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (288, 19, '5318', 'Mbay', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (289, 19, '5371', 'Kota Kupang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (290, 20, '6101', 'Sambas', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (291, 20, '6102', 'Bengkayang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (292, 20, '6103', 'Ngabang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (293, 20, '6104', 'Mempawah', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (294, 20, '6105', 'Batang Tarang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (295, 20, '6106', 'Ketapang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (296, 20, '6107', 'Sintang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (297, 20, '6108', 'Putussibau', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (298, 20, '6109', 'Sekadau', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (299, 20, '6110', 'Nanga Pinoh', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (300, 20, '6111', 'Sukadana', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (301, 20, '6171', 'Pontianak', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (302, 20, '6172', 'Singkawang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (303, 21, '6201', 'Pankalan Bun', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (304, 21, '6202', 'Sampit', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (305, 21, '6203', 'Kuala Kapuas', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (306, 21, '6204', 'Buntok', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (307, 21, '6205', 'Muara Taweh', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (308, 21, '6206', 'Sukamara', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (309, 21, '6207', 'Nanga Bulik', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (310, 21, '6208', 'Kuala Pembuang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (311, 21, '6209', 'Kasongan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (312, 21, '6210', 'Pulang Pisau', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (313, 21, '6211', 'Kuala Kurun', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (314, 21, '6212', 'Tamiang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (315, 21, '6213', 'Purukcahu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (316, 21, '6271', 'Palangkaraya', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (317, 22, '6301', 'Pelaihari', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (318, 22, '6302', 'Kotabaru', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (319, 22, '6303', 'Martapura', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (320, 22, '6304', 'Marabahan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (321, 22, '6305', 'Rantau', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (322, 22, '6306', 'Kandangan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (323, 22, '6307', 'Barabai', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (324, 22, '6308', 'Amuntai', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (325, 22, '6309', 'Tanjung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (326, 22, '6310', 'Batulicin', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (327, 22, '6311', 'Paringin', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (328, 22, '6371', 'Banjarmasin', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (329, 22, '6372', 'Banjarbaru', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (330, 23, '6401', 'Tanah Grogot', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (331, 23, '6402', 'Sendawar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (332, 23, '6403', 'Tenggarong', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (333, 23, '6404', 'Sangatta', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (334, 23, '6405', 'Tanjungredep', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (335, 23, '6406', 'Malinau', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (336, 23, '6407', 'Tanjungselor', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (337, 23, '6408', 'Nunukan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (338, 23, '6409', 'Penajam', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (339, 23, '6471', 'Balikpapan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (340, 23, '6472', 'Samarinda', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (341, 23, '6473', 'Tarakan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (342, 23, '6474', 'Bontang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (343, 25, '7101', 'Kotamubagu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (344, 25, '7102', 'Tondano', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (345, 25, '7103', 'Tahuna', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (346, 25, '7104', 'Melonguane', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (347, 25, '7105', 'Amurang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (348, 25, '7106', 'Airmadidi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (349, 25, '7107', 'Boroko', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (350, 25, '7108', 'Ondong Siau', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (351, 25, '7109', 'Ratahan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (352, 25, '7171', 'Manado', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (353, 25, '7172', 'Bitung', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (354, 25, '7173', 'Tomohon', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (355, 25, '7174', 'Kotamobagu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (356, 26, '7201', 'Banggai', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (357, 26, '7202', 'Luwuk', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (358, 26, '7203', 'Bungku', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (359, 26, '7204', 'Poso', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (360, 26, '7205', 'Donggala', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (361, 26, '7206', 'Toli-toli', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (362, 26, '7207', 'Buol', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (363, 26, '7208', 'Parigi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (364, 26, '7209', 'Ampana', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (365, 26, '7271', 'Palu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (366, 27, '7301', 'Bantaeng', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (367, 27, '7302', 'Bulukumba', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (369, 27, '7304', 'Jeneponto', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (370, 27, '7305', 'Takalar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (371, 27, '7306', 'Sunggu Minasa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (372, 27, '7307', 'Sinjai', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (373, 27, '7308', 'Maros', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (374, 27, '7309', 'Pangkajene', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (375, 27, '7310', 'Barru', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (376, 27, '7311', 'Watampone', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (377, 27, '7312', 'Watan Soppeng', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (378, 27, '7313', 'Sengkang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (379, 27, '7314', 'Sidenreng', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (380, 27, '7315', 'Pinrang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (381, 27, '7316', 'Enrekang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (382, 27, '7317', 'Palopo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (383, 27, '7318', 'Makale', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (384, 27, '7322', 'Masamba', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (385, 27, '7325', 'Malili', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (386, 27, '7371', 'Makassar', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (387, 27, '7372', 'Pare-pare', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (389, 28, '7401', 'Bau-Bau', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (390, 28, '7402', 'Raha', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (391, 28, '7403', 'Unaaha', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (392, 28, '7404', 'Kolaka', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (393, 28, '7405', 'Andolo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (394, 28, '7406', 'Rumbia', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (395, 28, '7407', 'Wangi-Wangi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (396, 28, '7408', 'Lasusua', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (397, 28, '7409', 'Bonegunu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (398, 28, '7410', 'Asera', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (399, 28, '7471', 'Kendari', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (401, 29, '7501', 'Marisa/Tilamuta', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (402, 29, '7502', 'Gorontalo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (403, 29, '7503', 'Marisa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (404, 29, '7504', 'Suwawa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (405, 29, '7505', 'Kwandang', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (406, 29, '7571', 'Kota Gorontalo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (407, 30, '7601', 'Majene', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (408, 30, '7602', 'Polewali', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (409, 30, '7603', 'Mamasa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (410, 30, '7604', 'Mamuju', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (411, 30, '7605', 'Pasangkayu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (412, 31, '8101', 'Saumlaki', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (413, 31, '8102', 'Tual', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (414, 31, '8103', 'Masohi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (415, 31, '8104', 'Namlea', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (416, 31, '8105', 'Dobo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (417, 31, '8106', 'Dataran Hunipopu', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (418, 31, '8107', 'Dataran Hunimoa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (419, 31, '8108', 'Ambon', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (420, 32, '8201', 'Ternate', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (421, 32, '8202', 'Weda', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (422, 32, '8203', 'Sanana', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (423, 32, '8204', 'Labuha', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (424, 32, '8205', 'Tobelo', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (425, 32, '8206', 'Maba', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (427, 32, '8272', 'Tidore', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (428, 33, '9101', 'Fak-Fak', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (429, 33, '9102', 'Kaimana', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (430, 33, '9103', 'Rasiei', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (431, 33, '9104', 'Bintuni', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (432, 33, '9105', 'Manokwari', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (433, 33, '9106', 'Teminabuan', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (434, 33, '9107', 'Sorong', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (435, 33, '9108', 'Waisai', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (437, 34, '9401', 'Wamena', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (438, 34, '9402', 'Jayapura', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (439, 34, '9403', 'Nabire', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (440, 34, '9404', 'Serui', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (441, 34, '9405', 'Biak', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (442, 34, '9406', 'Enarotali', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (443, 34, '9407', 'Kotamulia', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (444, 34, '9408', 'Timika', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (445, 34, '9409', 'Tanah Merah', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (446, 34, '9410', 'Kepi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (447, 34, '9411', 'Agats', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (448, 34, '9412', 'Sumohai', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (449, 34, '9413', 'Oksibil', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (450, 34, '9414', 'Karubaga', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (451, 34, '9415', 'Sarmi', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (452, 34, '9416', 'Waris', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (453, 34, '9417', 'Botawa', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (454, 34, '9418', 'Sorendiweri', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (455, 34, '9419', 'Kota Jayapura', 1);
				insert into `city` (`cityid`, `provinceid`, `citycode`, `cityname`, `recordstatus`) VALUES (456, 4, '9420', 'Selat Panjang', 1);";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `catalogsys` (
					`catalogsysid` INT(11) NOT NULL AUTO_INCREMENT,
					`languageid` INT(11) NOT NULL,
					`catalogname` VARCHAR(25) NOT NULL,
					`description` VARCHAR(40) NOT NULL,
					`catalogval` VARCHAR(80) NOT NULL,
					PRIMARY KEY (`catalogsysid`),
					UNIQUE INDEX `uq_catalogsy` (`languageid`, `catalogname`),
					INDEX `fk_catalogsys_lang` (`languageid`),
					CONSTRAINT `fk_catalogsys_lang` FOREIGN KEY (`languageid`) REFERENCES `language` (`languageid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'readmore', 'Read More', 'Baca ...');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'readmore', 'Read more', 'More ...');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'leaveareply', 'Leave a Reply', 'Komentar ...');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'leaveareply', 'Leave a Reply', 'Leave a Reply');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'useraccess', 'User Access', 'Akses User');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'useraccess', 'User Access', 'User Access');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'new', 'New', 'Baru');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'new', 'New', 'New');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'edit', 'Edit', 'Ubah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'edit', 'Edit', 'Edit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'groupaccess', 'Group Access', 'Akses Grup');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'groupaccess', 'Group Access', 'Group Access');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'groupname', 'Group Name', 'Nama Grup');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'groupname', 'Group Name', 'Group Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'description', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'description', 'Description', 'Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'realname', 'Real Name', 'Nama Sebenarnya');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'realname', 'Real Name', 'Real Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'email', 'Email', 'Email');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'email', 'Email', 'Email');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'phoneno', 'Phone No', 'No Telp');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'phoneno', 'Phone No', 'Phone No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'language', 'Language', 'Bahasa');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'language', 'Language', 'Language');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'theme', 'Theme', 'Tema');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'theme', 'Theme', 'Theme');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'themeadmin', 'Theme Admin', 'Tema Admin');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'themeadmin', 'Theme Admin', 'Admin Theme');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'installdate', 'Install Date', 'Tgl Instal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'installdate', 'Install Date', 'Install Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptygroupaccessid', 'Group Access ID Text validation', 'Group Access ID harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptygroupaccessid', 'Group Access ID', 'Invalid Group Access ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptygroupname', 'Group Name', 'Nama Grup harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptygroupname', 'Group Name', 'Invalid Group Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'alreadysaved', 'Data Already Saved', 'Data Telah Tersimpan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'alreadysaved', 'Data Already Saved', 'Data Telah Tersimpan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'delete', 'Active/Non Active', 'Aktif / Not Aktif');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'delete', 'Active/Non Active', 'Active / Not Active');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'purge', 'Purge', 'Hapus');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'purge', 'Purge', 'Purge');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'status', 'Status', 'Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'status', 'Status', 'Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'search', 'Search', 'Cari');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'search', 'Search', 'Search');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'upload', 'Upload', 'Unggah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'upload', 'Upload', 'Upload');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'help', 'Help', 'Help');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'help', 'Help', 'Help');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'download', 'Download', 'Unduh');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'download', 'Download', 'Download');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptydescription', 'Description', 'Keterangan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptydescription', 'Description', 'Invalid Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyusername', 'User Name', 'Nama User harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyusername', 'User Name', 'Invalid User Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'username', 'User Name', 'Nama User');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'username', 'User Name', 'User Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'password', 'Password', 'Password');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'password', 'Password', 'Password');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'catalogsys', 'Catalog Translation System', 'Kamus');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'catalogsys', 'Catalog Translation System', 'Catalog Translation System');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'catalogname', 'Catalog Name', 'Kata');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'catalogname', 'Catalog Name', 'Catalog Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'catalogval', 'Catalog Value', 'Nilai Kata');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'catalogval', 'Catalog Value', 'Catalog Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'paramvalue', 'Param Value', 'Nilai Parameter');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'paramvalue', 'Param Value', 'Parameter Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'parameter', 'Parameter', 'Parameter');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'parameter', 'Parameter', 'Parameter');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'paramname', 'Parameter Name', 'Parameter');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'paramname', 'Parameter Name', 'Parameter');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'parent', 'parent', 'Induk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'parent', 'parent', 'Parent');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptycategoryid', 'Empty Category ID', 'Category ID harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptycategoryid', 'Empty Category ID', 'Invalid Category ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptytitle', 'Empty Title', 'Title harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptytitle', 'Empty Title', 'Invalid Title');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'title', 'Title', 'Judul');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'title', 'Title', 'Title');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slug', 'Slug', 'Slug');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'slug', 'Slug', 'Slug');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'category', 'Category', 'Kategori');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'category', 'Category', 'Category');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'post', 'Post', 'Artikel');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'post', 'Post', 'Post');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'metatag', 'Meta Tag', 'Meta Tag');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'metatag', 'Meta Tag', 'Meta Tag');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'companyname', 'Company Name', 'Perusahaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'companyname', 'Company Name', 'Company Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'companycode', 'Company Code', 'Kode Perusahaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'companycode', 'Company Code', 'Company Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'address', 'Address', 'Alamat');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'address', 'Address', 'Address');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cityname', 'City Name', 'Kota');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'cityname', 'City Name', 'City Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'zipcode', 'Zip Code', 'Kode Pos');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'zipcode', 'Zip Code', 'Zip Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxno', 'Tax No', 'NPWP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'taxno', 'Tax No', 'Tax No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'currencyname', 'Currency Name', 'Mata Uang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'currencyname', 'Currency Name', 'Currency Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'faxno', 'Fax No', 'No Fax');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'faxno', 'Fax No', 'Fax No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'webaddress', 'Web Address', 'Website');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'webaddress', 'Web Address', 'Website');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'billto', 'Bill To', 'Alamat Tagihan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'billto', 'Bill To', 'Bill To');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isholding', 'Is Holding', 'Perusahaan Induk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isholding', 'Is Holding', 'Is Holding');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'city', 'City', 'Kota');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'city', 'City', 'City');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'currency', 'Currency', 'Mata Uang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'currency', 'Currency', 'Currency');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'leftlogofile', 'Left Logo File', 'Logo (Kiri)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'leftlogofile', 'Left Logo File', 'Left Logo File');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'rightlogofile', 'Right Logo File', 'Logo (Kanan)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'rightlogofile', 'Right Logo File', 'Right Logo File');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'company', 'Company', 'Perusahaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'company', 'Company', 'Company');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'survpro', 'Surveyor Project Management', 'Surveyor Project Management');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'survpro', 'Surveyor Project Management', 'Surveyor Project Management');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectname', 'Project Name', 'Nama Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectname', 'Project Name', 'Project Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'customer', 'Customer', 'Customer');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'customer', 'Customer', 'Customer');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectphase', 'Project Phase', 'Fase Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectphase', 'Project Phase', 'Project Phase');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'contractno', 'Contract No', 'No Kontrak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'contractno', 'Contract No', 'Contract No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'shiptype', 'Ship Type', 'Jenis Kapal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'shiptype', 'Ship Type', 'Ship Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gmerp', 'Group Menu', 'Otorisasi Grup dan Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'gmerp', 'Group Menu', 'Group Menu Authorization');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'iscustomer', 'Is Customer', 'Pelanggan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'iscustomer', 'Is Customer', 'Is Customer');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isemployee', 'Is Employee', 'Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isemployee', 'Is Employee', 'Is Employee');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isvendor', 'Is Vendor', 'Supplier');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isvendor', 'Is Vendor', 'Is Vendor');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bankname', 'Bank Name', 'Nama Bank');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bankname', 'Bank Name', 'Bank Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bankaccountno', 'Bank Account No', 'No Rekening');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bankaccountno', 'Bank Account No', 'Bank Account No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accountowner', 'Account Owner', 'Pemilik Rekening');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'accountowner', 'Account Owner', 'Account Owner');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'logo', 'Logo', 'Logo');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'logo', 'Logo', 'Logo');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'url', 'Url', 'Url');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'url', 'Url', 'Url');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fullname', 'Full Name', 'Nama');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fullname', 'Full Name', 'Full Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyfullname', 'Empty Full Name', 'Nama harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyfullname', 'Empty Full Name', 'Full Name invalid');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addressbook', 'Address Book', 'Buku Alamat');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addressbook', 'Address Book', 'Address Book');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuaccess', 'Menu Access', 'Akses Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuaccess', 'Menu Access', 'Menu Access');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isread', 'Is Read', 'Baca');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isread', 'Is Read', 'Is Read');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'iswrite', 'Is Write', 'Tulis');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'iswrite', 'Is Write', 'Is Write');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ispost', 'Is Post', 'Approval');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'ispost', 'Is Post', 'Is Post');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isupload', 'Is Upload', 'Unggah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isupload', 'Is Upload', 'Is Upload');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isreject', 'Is Reject', 'Aktif / Non Aktif');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isreject', 'Is Reject', 'Is Reject');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ispurge', 'Is Purge', 'Hapus');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'ispurge', 'Is Purge', 'Is Purge');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isdownload', 'Is Download', 'Unduh');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isdownload', 'Is Download', 'Is Download');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuname', 'Menu Name', 'Nama Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuname', 'Menu Name', 'Menu Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptymenuobject', 'Empty Menu Object', 'Menu Object harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptymenuobject', 'Empty Menu Object', 'Empty Menu Object');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuobject', 'Menu Object', 'Obyek Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuobject', 'Menu Object', 'Menu Object');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuauth', 'Menu Object', 'Obyek Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuauth', 'Menu Object', 'Menu Object');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuvalue', 'Menu Value', 'Nilai Otorisasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuvalue', 'Menu Value', 'Menu Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'groupmenuauth', 'Group Menu Auth', 'Grup Menu Otorisasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'groupmenuauth', 'Group Menu Auth', 'Group Menu Authorization');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'videocategory', 'Video Category', 'Kategori Video');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'videocategory', 'Video Category', 'Video Category');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptycategory', 'Empty Category', 'Kategori harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptycategory', 'Empty Category', 'Empty Category');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'newchannel', 'New Channel', 'Tambah Channel');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'newchannel', 'New Channel', 'New Channel');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addvideo', 'Add Video', 'Tambah Video');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addvideo', 'Add Video', 'Add Video');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'register', 'Register', 'Daftar Baru');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'register', 'Register', 'Register');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'submit', 'Submit', 'Submit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'submit', 'Submit', 'Submit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'rememberme', 'Remember Me', 'Ingat Saya');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'rememberme', 'Remember Me', 'Remember Me');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'save', 'Save', 'Simpan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'save', 'Save', 'Save');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'close', 'Close', 'Tutup');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'close', 'Close', 'Close');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'duplicateentry', 'Duplicate Entry', 'Data yang dimasukkan sudah tersedia');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'duplicateentry', 'Duplicate Entry', 'Duplicate Entry');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'birthdate', 'Birth Date', 'Tgl Lahir');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'birthdate', 'Birth Date', 'Birth Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'useraddress', 'User Address', 'Alamat');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'useraddress', 'User Address', 'Address');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'snro', 'Specific Number Range Object', 'Sistem Penomoran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'snro', 'Specific Number Range Object', 'Specific Number Range Object');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'formatdoc', 'Format Document', 'Format Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'formatdoc', 'Format Document', 'Format Document');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'formatno', 'Format No', 'No Format');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'formatno', 'Format No', 'Format No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'repeatby', 'Repeat By', 'Perulangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'repeatby', 'Repeat By', 'Repeat By');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'curdd', 'Current Day', 'Tanggal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'curdd', 'Current Day', 'Current Day');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'curmm', 'Current Month', 'Bulan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'curmm', 'Current Month', 'Current Month');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'curyy', 'Current Year', 'Tahun');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'curyy', 'Current Year', 'Current Year');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfname', 'Workflow', 'Nama Workflow');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfname', 'Workflow', 'Workflow');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfdesc', 'Workflow Description', 'Keterangan Workflow');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfdesc', 'Workflow Description', 'Workflow Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfminstat', 'Min Status', 'Status Min');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfminstat', 'Min Status', 'Min Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfmaxstat', 'Max STatus', 'Status Maks');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfmaxstat', 'Max Status', 'Max Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'workflow', 'Workflow', 'Alur Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'workflow', 'Workflow', 'Workflow');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfgroup', 'Workflow Group', 'Grup Alur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfgroup', 'Workflow Group', 'Workflow Group');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfbefstat', 'Before Action Status', 'Sebelum');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfbefstat', 'Before Action Status', 'Before Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfrecstat', 'After Action Status', 'Sesudah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfrecstat', 'After Action Status', 'After Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfstat', 'Wf Status (Int)', 'Status Workflow (Angka)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfstat', 'Wf Status (Int)', 'Workflow Status (Number)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfstatusname', 'Wf Status ', 'Status Workflow (huruf)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfstatusname', 'Wf Status', 'Workflow Status (string)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addresstype', 'Address Type', 'Jenis Alamat');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addresstype', 'Address Type', 'Address Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addresstypename', 'Address Type', 'Jenis Alamat');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addresstypename', 'Address Type', 'Address Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'contacttype', 'Contact Type', 'Jenis Kontak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'contacttype', 'Contact Type', 'Contact Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'contacttypename', 'Contact Type', 'Jenis Kontak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'contacttypename', 'Contact Type', 'Contact Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tryagain', 'Try Again', 'Silahkan coba lagi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'tryagain', 'Try Again', 'Please, Try Again');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'identitytype', 'Identity Type', 'Jenis Identitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'identitytype', 'Identity Type', 'Identity Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'identitytypename', 'Identity Type', 'Jenis Identitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'identitytypename', 'Identity Type', 'Identity Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'romawi', 'Romawi', 'Romawi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'romawi', 'Romawi', 'Romawi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'monthcal', 'Month in Calendar', 'Bulan Kalendar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'monthcal', 'Month in Calendar', 'Month in Calendar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'monthrm', 'Month in Rome', 'Bulan Romawi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'monthrm', 'Month in Rome', 'Month in Rome');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'plantcode', 'Plant Code', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'plantcode', 'Plant Code', 'Plant Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'plant', 'Plant', 'Cabang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'plant', 'Plant', 'Plant');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'dashboard', 'Dashboard', 'Dashboard');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'dashboard', 'Dashboard', 'Dashboard');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'modules', 'Modules', 'Modules');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'modules', 'Modules', 'Modules');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'widget', 'Widget', 'Widget');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'widget', 'Widget', 'Widget');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'translog', 'Transaction Log', 'Catatan Transaksi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'translog', 'Transaction Log', 'Transaction Log');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'country', 'Country', 'Negara');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'country', 'Country', 'Country');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'province', 'Province', 'Provinsi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'province', 'Province', 'Province');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'mediamgr', 'Media Manager', 'Alat Media');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'mediamgr', 'Media Manager', 'Media Manager');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'snrodet', 'SNRO Detail', 'Penomoran Terakhir');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'snrodet', 'SNRO Detail', 'Penomoran Terakhir');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfstatus', 'Workflow Status', 'Status Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfstatus', 'Workflow Status', 'Workflow Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'videoclip', 'Video Clip', 'Video Clip');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'videoclip', 'Video Clip', 'Video Clip');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'storagebin', 'Storage Bin', 'Rak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'storagebin', 'Storage Bin', 'Storage Bin');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'unitofmeasure', 'Unit of Measure', 'Satuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'unitofmeasure', 'Unit of Measure', 'Unit of Measure');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'pricecategory', 'Price Category', 'Kategori Harga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'pricecategory', 'Price Category', 'Price Category');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'salesarea', 'Sales Area', 'Area Penjualan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'salesarea', 'Sales Area', 'Sales Area');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialtype', 'Material Type', 'Jenis Material');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'materialtype', 'Material Type', 'Material Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialgroup', 'Material Group', 'Grup Material');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'materialgroup', 'Material Group', 'Material Group');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'supplier', 'Supplier', 'Supplier');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'supplier', 'Supplier', 'Supplier');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialstatus', 'Material Status', 'Status Material');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'materialstatus', 'Material Status', 'Material Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ownership', 'Ownership', 'Kepemilikan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'ownership', 'Ownership', 'Ownership');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'product', 'Product', 'Material / Service');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'product', 'Product', 'Material / Service');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productplant', 'Product Plant', 'Material / Service Gudang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productplant', 'Product Plant', 'Material / Service Storage');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productsales', 'Product Sales', 'Material / Service Penjualan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productsales', 'Product Sales', 'Material / Service Sales');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sloc', 'Storage Location', 'Gudang / Dept');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sloc', 'Storage Location', 'Storage Location');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sloccode', 'Storage Location Code', 'Kode Gudang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sloccode', 'Storage Location Code', 'Sloc Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'qtymax', 'Qty Max', 'Maks Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'qtymax', 'Qty Max', 'Qty Max');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'uomcode', 'UOM Code', 'Kode Satuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'uomcode', 'UOM Code', 'UOM Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'newdata', 'New Data', 'Data');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'newdata', 'New Data', 'Data');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'createddate', 'Created Date', 'Tanggal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'createddate', 'Created Date', 'Created Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'useraction', 'User Action', 'Action');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'useraction', 'User Action', 'Action');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'categoryname', 'Category Name', 'Kategori');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'categoryname', 'Category Name', 'Category');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'areaname', 'Area Name', 'Area');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'areaname', 'Area Name', 'Sales Area');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialtypecode', 'Material Type Code', 'Kode Jenis Material');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'materialtypecode', 'Material Type Code', 'Material Type Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productname', 'Product Name', 'Material / Service');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productname', 'Product Name', 'Product Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isstock', 'Is Stock', 'Stock');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isstock', 'Is Stock', 'Is Stock');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productpic', 'Material Picture', 'Gambar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productpic', 'Material Picture', 'Picture');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'barcode', 'Barcode', 'Barcode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'barcode', 'Barcode', 'Barcode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialstatusname', 'Material Status', 'Status Material');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'materialstatusname', 'Material Status', 'Material Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ownershipname', 'Ownership', 'Kepemilikan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'ownershipname', 'Ownership', 'Ownership');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialgroupcode', 'Material Group Code', 'Kode Grup Material');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'materialgroupcode', 'Material Group Code', 'Material Group Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'channeltitle', 'Channel Title', 'Judul');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'channeltitle', 'Channel Title', 'Title');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'channeldesc', 'Channel Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'channeldesc', 'Channel Description', 'Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'unknowncolumn', 'Unknown Column', 'Kesalahan SQL, cek kembali');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'unknowncolumn', 'Unknown Column', 'Unknown Column');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'uom', 'Unit of Measure', 'Satuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'uom', 'Unit of Measure', 'Unit of Measure');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isautolot', 'Is Auto Lot', 'Lot ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isautolot', 'Is Auto Lot', 'Is Lot ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sled', 'SLED', 'Masa Garansi / Expire');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sled', 'SLED', 'SLED');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'issource', 'Is Source', 'Sumber ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'issource', 'Is Source', 'Is Source ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slocdesc', 'Sloc Description', 'Keterangan Gudang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'slocdesc', 'Sloc Description', 'Sloc Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invalidmethod', 'Invalid Method', 'Parameter Tidak Dikenal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invalidmethod', 'Invalid Method', 'Invalid Parameter or Method');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'logout', 'Logout', 'Keluar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'logout', 'Logout', 'Log Out');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'updateprofile', 'Update Profile', 'Update Profile');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'updateprofile', 'Update Profile', 'Update Profile');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'currencyvalue', 'Currency Value', 'Nilai');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'currencyvalue', 'Currency Value', 'Currency Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'welcomeaboard', 'Welcome Aboard', 'Selamat Datang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'welcomeaboard', 'Welcome Aboard', 'Welcome Aboard');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'paymentmethod', 'Payment Method', 'Metode Pembayaran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'paymentmethod', 'Payment Method', 'Payment Method');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'paycode', 'Pay Code', 'Kode Pembayaran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'paycode', 'Pay Code', 'Pay Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'paydays', 'Pay Days', 'Jumlah Hari');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'paydays', 'Pay Days', 'Pay Days');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'paymentname', 'Payment Name', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'paymentname', 'Payment Name', 'Payment Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tododate', 'Todo Date', 'Tanggal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'tododate', 'Todo Date', 'Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'docno', 'Doc No', 'No Dok');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'docno', 'Doc No', 'Doc No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptymaterialgroup', 'Empty Material Group', 'Material Grup harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptymaterialgroup', 'Empty Material Group', 'Empty Material Group');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'parentmatgroup', 'Parent Material Group', 'Induk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'parentmatgroup', 'Parent Material Group', 'Parent');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productid', 'Product ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productid', 'Product ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'currencyid', 'Currency ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'currencyid', 'Currency ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'symbol', 'Symbol', 'Symbol');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'symbol', 'Symbol', 'Symbol');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'groupaccessid', 'Group Access ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'groupaccessid', 'Group Access ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'themes', 'Themes', 'Tema');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'themes', 'Themes', 'Tema');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'useraccessid', 'User Access ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'useraccessid', 'User Access ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'languageid', 'Language ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'languageid', 'Language ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'languagename', 'Language Name', 'Bahasa');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'languagename', 'Language Name', 'Language Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'catalogsysid', 'Catalog System ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'catalogsysid', 'Catalog System ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'paramid', 'Param ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'paramid', 'Param ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'unitofmeasureid', 'UOM ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'unitofmeasureid', 'UOM ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'pricecategoryid', 'Price Category ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'pricecategoryid', 'Price Category ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyuom', 'Empty UOM', 'UOM harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyuom', 'Empty UOM', 'Empty UOM');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'paymentmethodid', 'Payment Method ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'paymentmethodid', 'Payment Method ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tax', 'Tax', 'Pajak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'Tax', 'Tax', 'Tax');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxid', 'Tax ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'taxid', 'Tax ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxcode', 'Tax Code', 'Kode Pajak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'taxcode', 'Tax Code', 'Tax Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxvalue', 'Tax Value', 'Nilai Pajak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'taxvalue', 'Tax Value', 'Tax Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'currentlimit', 'Current Limit', 'Current Limit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'currentlimit', 'Current Limit', 'Current Limit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'creditlimit', 'Credit Limit', 'Credit Limit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'creditlimit', 'Credit Limit', 'Credit Limit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addressname', 'Address Name', 'Alamat');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addressname', 'Address Name', 'Address Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'rt', 'RT', 'RT');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'rt', 'RT', 'RT');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'rw', 'RW', 'RW');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'rw', 'RW', 'RW');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isstrictlimit', 'Is Strict Limit', 'Limit Kaku ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isstrictlimit', 'Is Strict Limit', 'Is Strict Limit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'npwp', 'NPWP', 'NPWP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'npwp', 'NPWP', 'NPWP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'overdue', 'Overdue', 'Batas Waktu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'overdue', 'Overdue', 'Overdue');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptypricecategory', 'Empty Price Category', 'Kategori Harga harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptypricecategory', 'Empty Price Category', 'Empty Price Category');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptysalesarea', 'Empty Sales Area', 'Sales Area harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptysalesarea', 'Empty Sales Area', 'Empty Sales Area');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'latitude', 'Latitude', 'Latitude');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'latitude', 'Latitude', 'Latitude');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'longitude', 'Longitude', 'Longitude');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'longitude', 'Longitude', 'Longitude');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cityid', 'City ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'cityid', 'City ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'countryid', 'Country ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'countryid', 'Country ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'countrycode', 'Country Code', 'Kode Negara');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'countrycode', 'Country Code', 'Kode Negara');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'countryname', 'Country Name', 'Nama Negara');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'countryname', 'Country Name', 'Nama Negara');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'provinceid', 'Province ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'provinceid', 'Province ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'provincename', 'Province Name', 'Nama Provinsi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'provincename', 'Province Name', 'Province Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'provincecode', 'Province Code', 'Kode Provinsi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'provincecode', 'Province Code', 'Province Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'citycode', 'City Code', 'Kode Kota');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'citycode', 'City Code', 'City Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'recordstatus', 'Status', 'Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'recordstatus', 'Status', 'Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'contacttypeid', 'Contact Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'contacttypeid', 'Contact Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addresscontactname', 'Address Contact Name', 'Kontak Person');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addresscontactname', 'Address Contact Name', 'Contact Person');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'mobilephone', 'Mobile Phone', 'No HP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'mobilephone', 'Mobile Phone', 'Mobile Ph');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emailaddress', 'Email Address', 'Email');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emailaddress', 'Email Address', 'Email');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addresscontact', 'Address Contact', 'Contact');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addresscontact', 'Address Contact', 'Contact');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addressbookid', 'Address Book ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addressbookid', 'Address Book ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'areyousure', 'Are you sure', 'Apakah anda yakin ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'areyousure', 'Are you sure', 'Apakah anda yakin ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'positionid', 'Position ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'positionid', 'Position ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'positionname', 'Position Name', 'Posisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'positionname', 'Position Name', 'Position Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'position', 'Position', 'Posisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'position', 'Position', 'Posisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'orgstructure', 'Organization Structure', 'Struktur Organisasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'orgstructure', 'Organization Structure', 'Organization Structure');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'structurename', 'Structure Name', 'Struktur Organisasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'structurename', 'Structure Name', 'Structure Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'orgstructureid', 'Organization Structure ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'orgstructureid', 'Organization Structure ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'levelorg', 'Level Organization', 'Level');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'levelorg', 'Level Organization', 'Level');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'levelorgid', 'Level ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'levelorgid', 'Level ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'levelorgname', 'Level Organization', 'Level Organisasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'levelorgname', 'Level Organization', 'Level Organization');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'content', 'Content', 'Content');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'content', 'Content', 'Content');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeid', 'Employee ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeid', 'Employee ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employee', 'Employee', 'Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employee', 'Employee', 'Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'currentdebt', 'Current Debt', 'Hutang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'currentdebt', 'Current Debt', 'Hutang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'oldnik', 'Old Nik', 'NIK');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'oldnik', 'Old Nik', 'Employee No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'jobs', 'Jobs', 'Deskripsi Pekerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'jobs', 'Jobs', 'Job Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'jobdesc', 'Job Description', 'Deskripsi Pekerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'jobdesc', 'Job Description', 'Deskripsi Pekerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'qualification', 'Qualification', 'Qualification');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'qualification', 'Qualification', 'Qualification');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeetype', 'Employee Type', 'Jenis Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeetype', 'Employee Type', 'Jenis Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sex', 'Sex', 'Jenis Kelamin');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sex', 'Sex', 'Sex');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'maritalstatus', 'Marital Status', 'Status Pernikahan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'maritalstatus', 'Marital Status', 'Marital Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeestatus', 'Employee Status', 'Status Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeestatus', 'Employee Status', 'Employee Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'religion', 'Religion', 'Agama');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'religion', 'Religion', 'Religion');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'education', 'Education', 'Pendidikan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'education', 'Education', 'Education');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'educationmajor', 'Education Major', 'Jurusan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'educationmajor', 'Education Major', 'Education Major');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'familyrelation', 'Family Relation', 'Hubungan Keluarga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'familyrelation', 'Family Relation', 'Family Relation');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'occupation', 'Occupation', 'Jenis Pekerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'occupation', 'Occupation', 'Jenis Pekerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'languagevalue', 'Language Value', 'Nilai Kemampuan Bahasa');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'languagevalue', 'Language Value', 'Language Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'referenceby', 'Reference By', 'Referensi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'referenceby', 'Reference By', 'Reference By');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'joindate', 'Join Date', 'Tgl Gabung');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'joindate', 'Join Date', 'Join Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'istrial', 'Is Trial', 'Percobaan ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'istrial', 'Is Trial', 'Is Trial ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'resigndate', 'Resign Date', 'Tgl Resign');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'resigndate', 'Resign Date', 'Resign Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'photo', 'Photo', 'Photo');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'photo', 'Photo', 'Photo');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'birthcity', 'Birth City', 'Tempat Lahir');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'birthcity', 'Birth City', 'Birth City');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeetypeid', 'Employee Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeetypeid', 'Employee Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeetypename', 'Employee Type Name', 'Jenis Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeetypename', 'Employee Type Name', 'Jenis Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sicksnro', 'Sick SNRO', 'Penomoran Surat Sakit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sicksnro', 'Sick SNRO', 'Numbering Sickness Letter');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sexid', 'Sex ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sexid', 'Sex ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sexname', 'Sex Name', 'Sex');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sexname', 'Sex Name', 'Sex');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'maritalstatusid', 'Marital Status ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'maritalstatusid', 'Marital Status ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'maritalstatusname', 'Marital Status Name', 'Jenis Pernikahan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'maritalstatusname', 'Marital Status Name', 'Jenis Pernikahan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'religionid', 'Religion ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'religionid', 'Religion ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'religionname', 'Religion Name', 'Agama');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'religionname', 'Religion Name', 'Religion');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'educationid', 'Education ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'educationid', 'Education ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'educationname', 'Education', 'Pendidikan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'educationname', 'Education', 'Education');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'educationmajorid', 'Education Major ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'educationmajorid', 'Education Major ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'educationmajorname', 'Education Major Name', 'Jurusan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'educationmajorname', 'Education Major Name', 'Jurusan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'familyrelationid', 'Family Relation ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'familyrelationid', 'Family Relation ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'familyrelationname', 'Family Relation', 'Hubungan Keluarga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'familyrelationname', 'Family Relation', 'Hubungan Keluarga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'occupationid', 'Occupation ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'occupationid', 'Occupation ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'occupationname', 'Occupation', 'Jenis Pekerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'occupationname', 'Occupation', 'Jenis Pekerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'languagevalueid', 'Language Value ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'languagevalueid', 'Language Value ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'languagevaluename', 'Language Value Name', 'Nilai Kemampuan Bahasa');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'languagevaluename', 'Language Value Name', 'Nilai Kemampuan Bahasa');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyorgstructure', 'Empty Orgstructure', 'Struktur Organisasi harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyorgstructure', 'Empty Orgstructure', 'Empty Organization Structure');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyoldnik', 'Empty Old Nik', 'NIK harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyoldnik', 'Empty Old Nik', 'Empty NIK');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyemployeetype', 'Empty Employee Type', 'Jenis Karyawan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyemployeetype', 'Empty Employee Type', 'Empty Employee Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptylevelorg', 'Empty Level', 'Level harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptylevelorg', 'Empty Level', 'Empty Level Org');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptysex', 'Empty Sex', 'Jenis Kelamin harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptysex', 'Empty Sex', 'Empty Sex');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptymaritalstatus', 'Empty Marital Status', 'Status Pernikahan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptymaritalstatus', 'Empty Marital Status', 'Empty Marital Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'familyname', 'Family Name', 'Keluarga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'familyname', 'Family Name', 'Family Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeefamily', 'Employee Family', 'Keluarga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeefamily', 'Employee Family', 'Keluarga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'identityname', 'Identity Name', 'No Identitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'identityname', 'Identity Name', 'No Identitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeidentity', 'Employee Identity', 'Identitas Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeidentity', 'Employee Identity', 'Employee Identity');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'soheader', 'Sales Order', 'Sales Order');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'soheader', 'Sales Order', 'Sales Order');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'pos', 'Point of Sales', 'Kasir');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'pos', 'Point of Sales', 'Point of Sales');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reject', 'Reject', 'Tolak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reject', 'Reject', 'Reject');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'approve', 'Approve', 'Setuju');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'approve', 'Approve', 'Approve');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'soheaderid', 'SO Header ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'soheaderid', 'SO Header ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'docdate', 'Document Date', 'Tgl Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'docdate', 'Document Date', 'Tgl Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuvalueid', 'Value ID', 'Data ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuvalueid', 'Value ID', 'ID Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'companyid', 'Company ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'companyid', 'Company ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'qty', 'Qty', 'Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'qty', 'Qty', 'Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'currencyrate', 'Currency Rate', 'Kurs');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'currencyrate', 'Currency Rate', 'Rate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'itemnote', 'Item Note', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'itemnote', 'Item Note', 'Note');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sales', 'Sales', 'Sales');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sales', 'Sales', 'Sales');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'pocustno', 'PO Customer No', 'No PO / SPK Customer');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'pocustno', 'PO Customer No', 'No PO / SPK Customer');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'discpersen', 'Discount (%)', 'Disc (%)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'discpersen', 'Discount (%)', 'Disc (%)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'discvalue', 'Discount', 'Disc');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'discvalue', 'Discount', 'Disc');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'headernote', 'Header Note', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'headernote', 'Header Note', 'Header Note');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'shipto', 'Ship To', 'Alamat Kirim');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'shipto', 'Ship To', 'Ship To');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'docreachmaxstatus', 'Document Reached Max Status', 'Dokumen sudah status approval terakhir, tidak bisa di edit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'docreachmaxstatus', 'Document Reached Max Status', 'Document Reached Max Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addressid', 'Address ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addressid', 'Address ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'price', 'Price', 'Harga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'price', 'Price', 'Harga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'total', 'Total', 'Total');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'total', 'Total', 'Total');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sodetail', 'SO Detail', 'Detail SO');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sodetail', 'SO Detail', 'SO Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slocid', 'Sloc ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'slocid', 'Sloc ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'flowapp', 'Approval Workflow Mis Configuration', 'Workflow tidak sesuai, silahkan kontak Admin');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'flowapp', 'Approval Workflow Mis Configuration', 'Approval Workflow Mis Configuration');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'periodover', 'Accounting Period Not Open yet', 'Periode Akuntansi belum dibuka, silahkan kontak Accounting');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'periodover', 'Accounting Period Not Open yet', 'Accounting Period Not Open yet');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detailempty', 'Detail Empty', 'Detail tidak boleh kosong');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'detailempty', 'Detail Empty', 'Detail Empty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'falsesloc', 'False SLOC', 'Pemilihan Sloc tidak sesuai Material / Service Data Gudang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'falsesloc', 'False SLOC', 'Incorrect Sloc at Product Plant');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accperiod', 'Accounting Period', 'Periode Akuntansi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'accperiod', 'Accounting Period', 'Accounting Period');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'period', 'Periode', 'Periode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'period', 'Periode', 'Periode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'totalbefdisc', 'Total Before Disc', 'Total Sebelum Disc');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'totalbefdisc', 'Total Before Disc', 'Total Bef Disc');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'totalaftdisc', 'Total After Disc', 'Total Sesudah Disc');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'totalaftdisc', 'Total After Disc', 'Total After Disc');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projecttype', 'Project Type', 'Jenis Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projecttype', 'Project Type', 'Project Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectstatus', 'Project Status', 'Status Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectstatus', 'Project Status', 'Project Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'project', 'Project', 'Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'project', 'Project', 'Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projecttypeid', 'Project Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projecttypeid', 'Project Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projecttypename', 'Project Type Name', 'Jenis Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projecttypename', 'Project Type Name', 'Jenis Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectstatusid', 'Project Status ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectstatusid', 'Project Status ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectstatusname', 'Project Status Name', 'Status Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectstatusname', 'Project Status Name', 'Status Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectid', 'Project ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectid', 'Project ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectboq', 'BOQ', 'Bill of Quantity');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectboq', 'BOQ', 'Bill of Quantity');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectmpp', 'Man Power', 'Man Power');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectmpp', 'Man Power', 'Man Power');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productplantid', 'Product Plant ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productplantid', 'Product Plant ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialtypeid', 'Material Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'materialtypeid', 'Material Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialgroupid', 'Material Group ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'materialgroupid', 'Material Group ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productsalesid', 'Product Sales ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productsalesid', 'Product Sales ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bobot', 'Bobot', 'Bobot');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bobot', 'Bobot', 'Bobot');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectvalue', 'Project Value', 'Nilai Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectvalue', 'Project Value', 'Nilai Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyprice', 'Empty Price', 'Harga harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyprice', 'Empty Price', 'Empty Price');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptybobot', 'Empty Bobot', 'Bobot harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptybobot', 'Empty Bobot', 'Bobot harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'subject', 'Subject', 'Subyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'subject', 'Subject', 'Subyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'location', 'Location', 'Lokasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'location', 'Location', 'Location');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'starttime', 'Start Time', 'Tgl Awal Pengerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'starttime', 'Start Time', 'Start Time');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'endtime', 'End Time', 'Tgl Akhir Pengerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'endtime', 'End Time', 'End Time');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'doctype', 'Document Type', 'Jenis Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'doctype', 'Document Type', 'Document Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'doctypeid', 'Document Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'doctypeid', 'Document Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'doctypename', 'Document Type', 'Jenis Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'doctypename', 'Document Type', 'Document Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'detail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'smkelas', 'SM Kelas', 'Kelas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'smkelas', 'SM Kelas', 'Class');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'smguru', 'SM Guru', 'Guru');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'smguru', 'SM Guru', 'Teacher');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'smmurid', 'SM Murid', 'Murid');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'smmurid', 'SM Murid', 'Student');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'smcabang', 'SM Cabang', 'Cabang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'smcabang', 'SM Cabang', 'Cabang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'smibadah', 'SM Ibadah', 'Ibadah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'smibadah', 'SM Ibadah', 'Ibadah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'smkelasid', 'SM Kelas ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'smkelasid', 'SM Kelas ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'namakelas', 'Nama Kelas', 'Nama Kelas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'namakelas', 'Nama Kelas', 'Nama Kelas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'smcabangid', 'SM Cabang ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'smcabangid', 'SM Cabang ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cabang', 'Cabang', 'Cabang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'cabang', 'Cabang', 'Cabang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'smibadahid', 'SM Ibadah ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'smibadahid', 'SM Ibadah ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ibadah', 'Ibadah', 'Ibadah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'ibadah', 'Ibadah', 'Ibadah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'jam', 'Jam', 'Jam');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'jam', 'Jam', 'Jam');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeinformal', 'Employee Informal', 'Pendidikan Non Formal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeinformal', 'Employee Informal', 'Employee Informal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeforeignlanguage', 'Employee Foreign Language', 'Kemampuan Bahasa');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeforeignlanguage', 'Employee Foreign Language', 'Employee Foreign Language');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeeducation', 'Employee Education', 'Pendidikan Formal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeeducation', 'Employee Education', 'Employee Education');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeewo', 'Employee Working Experience', 'Pengalaman Kerja');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeewo', 'Employee Working Experience', 'Pengalaman Kerja');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'usertodo', 'User TODO', 'Daftar Pekerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'usertodo', 'User TODO', 'Daftar Pekerjaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'usermenu', 'User Menu', 'User Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'usermenu', 'User Menu', 'User Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'userinbox', 'Inbox', 'Inbox');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'userinbox', 'Inbox', 'Inbox');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'schoolname', 'School Name', 'Nama Sekolah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'schoolname', 'School Name', 'School Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'yeargraduate', 'Year Graduate', 'Tahun Kelulusan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'yeargraduate', 'Year Graduate', 'Year Graduate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isdiploma', 'Is Diploma', 'Diploma ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isdiploma', 'Is Diploma', 'Diploma ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptycity', 'Empty City', 'Kota harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptycity', 'Empty City', 'Empty City');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyeducation', 'Empty Education', 'Pendidikan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyeducation', 'Empty Education', 'Pendidikan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyschoolname', 'Empty School Name', 'Nama Sekolah harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyschoolname', 'Empty School Name', 'Nama Sekolah harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyyeargraduate', 'Empty Year Graduate', 'Tahun Kelulusan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyyeargraduate', 'Empty Year Graduate', 'Empty Year Graduate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyeducationmajor', 'Empty Education Major', 'Jurusan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyeducationmajor', 'Empty Education Major', 'Empty Education Major');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'qtyinprogress', 'Qty In Progress', 'Qty In Progress');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'qtyinprogress', 'Qty In Progress', 'Qty In Progress');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productstock', 'Product Stock', 'Stock Barang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productstock', 'Product Stock', 'Material Stock Overview');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'storagebinid', 'Storage Bin ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'storagebinid', 'Storage Bin ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productdetail', 'Product Detail', 'Detail Stok Barang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productdetail', 'Product Detail', 'Material Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'referenceno', 'Reference No', 'No Referensi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'referenceno', 'Reference No', 'No Referensi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transdate', 'Trans Date', 'Tgl Transaksi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'transdate', 'Trans Date', 'Transaction Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'buydate', 'Buy Date', 'Tgl Beli / Produksi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'buydate', 'Buy Date', 'Buy Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'buyprice', 'Buy Price', 'Harga Beli / Produksi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'buyprice', 'Buy Price', 'Buy Price');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'expiredate', 'Expire Date', 'Tgl Kadaluarsa');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'expiredate', 'Expire Date', 'Expire Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'locationdate', 'Location Date', 'Tgl Pindah Lokasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'locationdate', 'Location Date', 'Location Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialcode', 'Material Code', 'Kode Material');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'materialcode', 'Material Code', 'Material Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'serialno', 'Serial No', 'No Serial');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'serialno', 'Serial No', 'Serial No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'stockopname', 'Stock Opname', 'Stok Opname');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'stockopname', 'Stock Opname', 'Stok Opname');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detailstok', 'Detail Stok', 'Stok Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'detailstok', 'Detail Stok', 'Stok Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accounttypeid', 'Account Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'accounttypeid', 'Account Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accounttypename', 'Account Type Name', 'Jenis Akun');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'accounttypename', 'Account Type Name', 'Jenis Akun');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accounttype', 'Account Type', 'Jenis Akun');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'accounttype', 'Account Type', 'Jenis Akun');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accountid', 'Account ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'accountid', 'Account ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accountname', 'Account Name', 'Akun');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'accountname', 'Account Name', 'Account Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'account', 'Account', 'Akun');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'account', 'Account', 'Akun');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accountcode', 'Account Code', 'Kode Akun');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'accountcode', 'Account Code', 'Account Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'parentaccount', 'Parent Account ID', 'Induk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'parentaccount', 'Parent Account ID', 'Induk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'genjournal', 'General Journal', 'Jurnal Umum');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'genjournal', 'General Journal', 'General Journal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'genledger', 'General Ledger', 'General Ledger');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'genledger', 'General Ledger', 'General Ledger');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'profitloss', 'Profit Loss', 'Laporan Laba / Rugi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'profitloss', 'Profit Loss', 'Profit Loss');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'balancesheet', 'Balance Sheet', 'Laporan Neraca');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'balancesheet', 'Balance Sheet', 'Balance Sheet');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'smtrans', 'Transaction', 'Daftar Absensi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'smtrans', 'Transaction', 'Transaction');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tglabsen', 'Tgl Absen', 'Tgl Absen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'tglabsen', 'Tgl Absen', 'Tgl Absen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'stockopnameid', 'Stock Opname ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'stockopnameid', 'Stock Opname ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'stockopnamedet', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'stockopnamedet', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'relationerror', 'Relation Error', 'Terjadi kesalahan penyimpanan relasi tabel');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'relationerror', 'Relation Error', 'Relation Error');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'maintaindb', 'Maintain DB', 'Backup / Restore');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'maintaindb', 'Maintain DB', 'Maintain DB');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'absstatusid', 'Abs Status ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'absstatusid', 'Abs Status ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'shortstat', 'Short Status', 'Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'shortstat', 'Short Status', 'Short Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'longstat', 'Long Status', 'Status Panjang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'longstat', 'Long Status', 'Long Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isin', 'Is In', 'Masuk ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isin', 'Is In', 'Is In ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'priority', 'Priority', 'Prioritas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'priority', 'Priority', 'Priority ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'absstatus', 'Absence Status', 'Absence Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'absstatus', 'Absence Status', 'Absence Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'absscheduleid', 'Absence Schedule ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'absscheduleid', 'Absence Schedule ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'absschedulename', 'Absence Schedule Name', 'Schedule');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'absschedulename', 'Absence Schedule Name', 'Schedule');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'absin', 'Absence In', 'Jam Masuk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'absin', 'Absence In', 'Absence In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'absschedule', 'Absence Schedule', 'Schedule Absen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'absschedule', 'Absence Schedule', 'Absence Schedule');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'absout', 'Absence Out', 'Jam Keluar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'absout', 'Absence Out', 'Jam Keluar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'absrule', 'Absence Rule', 'Aturan Absensi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'absrule', 'Absence Rule', 'Absence Rule');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'difftimein', 'Time In', 'Time In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'difftimein', 'Time In', 'Time In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'difftimeout', 'Time Out', 'Time Out');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'difftimeout', 'Time Out', 'Time Out');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'timein', 'Time In', 'Time In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'timein', 'Time In', 'Time In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'timeout', 'Time Out', 'Time Out');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'timeout', 'Time Out', 'Time Out');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tablename', 'Table Name', 'Nama Tabel');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'tablename', 'Table Name', 'Table Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'controllername', 'Controller Name', 'Nama Controller');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'controllername', 'Controller Name', 'Controller Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'modulename', 'Module Name', 'Nama Module');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'modulename', 'Module Name', 'Module Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menugenerator', 'Menu Generator', 'Menu Generator');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menugenerator', 'Menu Generator', 'Menu Generator');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptytablename', 'Empty Table Name', 'Nama Tabel harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptytablename', 'Empty Table Name', 'Empty Table Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptymodulename', 'Empty Module Name', 'Nama Modul harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptymodulename', 'Empty Module Name', 'Empty Module Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptycontroller', 'Empty Controller', 'Controller harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptycontroller', 'Empty Controller', 'Empty Controller');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'notallowtabclick', 'Not Allow Tab Click', 'Tidak boleh click tab, klik tombol Next');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'notallowtabclick', 'Not Allow Tab Click', 'Not Allow Tab Click');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'namafield', 'Nama Field', 'Nama Field');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'namafield', 'Nama Field', 'Nama Field');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isview', 'Is View', 'View ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isview', 'Is View', 'Is View');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'widgetrelation', 'Widget Relation', 'Widget Pop Up');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'widgetrelation', 'Widget Relation', 'Widget Pop Up');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'relationname', 'Relation Name', 'Nama Relasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'relationname', 'Relation Name', 'Relation Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'finishmenu', 'Finish Menu', 'Generator sudah selesai, silahkan ke menu tersebut');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'finishmenu', 'Finish Menu', 'Generator sudah selesai, silahkan ke menu tersebut');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'read', 'Read', 'Baca');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'read', 'Read', 'Read');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tipefield', 'Field Type', 'Jenis Field');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'tipefield', 'Field Type', 'Jenis Field');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'masterdetail', 'Master Detail', 'Master Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'masterdetail', 'Master Detail', 'Master Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tablerelation', 'Table Relation', 'Relasi Tabel');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'tablerelation', 'Table Relation', 'Relasi Tabel');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tablefkname', 'Table FK Name', 'Foreign Key');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'tablefkname', 'Table FK Name', 'Foreign Key');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'issearch', 'Is Search', 'Daftar Cari ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'issearch', 'Is Search', 'Is Search ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isvalidate', 'Is Validate', 'Validasi ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isvalidate', 'Is Validate', 'Is Validate ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'groupmenuid', 'Group Menu ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'groupmenuid', 'Group Menu ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isonline', 'Is Online', 'Is Online');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isonline', 'Is Online', 'Is Online');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isinput', 'Is Input', 'Input ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isinput', 'Is Input', 'Input ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'translogid', 'Transaction Log ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'translogid', 'Transaction Log', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tableid', 'Table ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ishospital', 'Is Hospital', 'Rumah Sakit ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'ishospital', 'Is Hospital', 'Is Hospital');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addresstypeid', 'Address Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addresstypeid', 'Address Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accperiodid', 'Acc Period ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'accperiodid', 'Acc Period ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'identitytypeid', 'Identity Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'identitytypeid', 'Identity Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'romawiid', 'Romawi ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'romawiid', 'Romawi ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'plantid', 'Plant ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'plantid', 'Plant ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'plantaddress', 'Plant Address', 'Alamat');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'plantaddress', 'Plant Address', 'Address');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'lat', 'Latitude', 'Latitude');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'lat', 'Latitude', 'Latitude');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'lng', 'Longitude', 'Longitude');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'lng', 'Longitude', 'Longitude');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ismultiproduct', 'Is Multi Product', 'Is Multi Product');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'ismultiproduct', 'Is Multi Product', 'Is Multi Product');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'salesareaid', 'Sales Area ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'salesareaid', 'Sales Area ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialstatusid', 'Material Status ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'materialstatusid', 'Material Status ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ownershipid', 'Ownership ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'ownershipid', 'Ownership ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'unitofiss', 'Unit of Issue', 'UOM');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'unitofiss', 'Unit of Issue', 'UOM');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wirelsource', 'Widget Souce', 'Sumber Data ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wirelsource', 'Widget Souce', 'Widget Source ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'defaultvalue', 'Default Value', 'Nilai Awal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'defaultvalue', 'Default Value', 'Default Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gettable', 'Get Table', 'Baca Tabel');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'gettable', 'Get Table', 'Get Table');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isprint', 'Is Print', 'Cetak ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isprint', 'Is Print', 'Cetak ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addresscontactid', 'Address Contact ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addresscontactid', 'Address Contact ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuauthid', 'Menu Auth ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuauthid', 'Menu Auth ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'groupmenuauthid', 'Group Menu Auth ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'groupmenuauthid', 'Group Menu Auth ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'snroid', 'Snro ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'snroid', 'Snro ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'snrodid', 'Snro Detail ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'snrodid', 'Snro Detail ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'workflowid', 'Workflow ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'workflowid', 'Workflow ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfgroupid', 'Wf Group ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfgroupid', 'Wf Group ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wfstatusid', 'Wf Status ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'wfstatusid', 'Wf Status ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'jobsid', 'Jobs ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'jobsid', 'Jobs ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'popupname', 'Pop Up Name', 'Relasi Pop Up');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'popupname', 'Pop Up Name', 'Pop Up Relation');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sickstatus', 'Sick Status', 'Status Sakit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sickstatus', 'Sick Status', 'Sickness Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeestatustype', 'Employee Status Type', 'Status Karyawan Sakit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeestatustype', 'Employee Status Type', 'Employee Status Type');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'approvewf', 'Approval Workflow', 'Alur Approve');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'approvewf', 'Approval Workflow', 'Approval Workflow');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'rejectwf', 'Reject Workflow', 'Alur Reject');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'rejectwf', 'Reject Workflow', 'Reject Workflow');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'insertwf', 'Insert Workflow', 'Alur Insert');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'insertwf', 'Insert Workflow', 'Insert Workflow');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeescheduleid', 'Employee Schedule ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeescheduleid', 'Employee Schedule ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'month', 'Month', 'Bulan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'month', 'Month', 'Bulan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'year', 'Year', 'Tahun');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'year', 'Year', 'Year');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd1', 'Day 1', '1');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd1', 'Day 1', '1');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd2', 'Day 2', '2');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd2', 'Day 2', '2');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd3', 'Day 3', '3');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd3', 'Day 3', '3');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd4', 'Day 4', '4');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd4', 'Day 4', '4');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd5', 'Day 5', '5');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd5', 'Day 5', '5');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd6', 'Day 6', '6');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd6', 'Day 6', '6');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd7', 'Day 7', '7');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd7', 'Day 7', '7');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd8', 'Day 8', '8');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd8', 'Day 8', '8');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd9', 'Day 9', '9');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd10', 'Day 10', '10');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd10', 'Day 10', '10');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd11', 'Day 11', '11');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd12', 'Day 12', '12');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd13', 'Day 13', '13');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd13', 'Day 13', '13');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd14', 'Day 14', '14');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd14', 'Day 14', '14');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd15', 'Day 15', '15');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd15', 'Day 15', '15');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd16', 'Day 16', '16');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd16', 'Day 16', '16');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd17', 'Day 17', '17');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd17', 'Day 17', '17');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd18', 'Day 18', '18');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd18', 'Day 18', '18');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd19', 'Day 19', '19');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd19', 'Day 19', '19');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd20', 'Day 20', '20');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd20', 'Day 20', '20');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd21', 'Day 21', '21');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd21', 'Day 21', '21');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd22', 'Day 22', '22');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd22', 'Day 22', '22');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd23', 'Day 23', '23');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd23', 'Day 23', '23');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd24', 'Day 24', '24');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd24', 'Day 24', '24');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd25', 'Day 25', '25');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd25', 'Day 25', '25');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd26', 'Day 26', '26');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd26', 'Day 26', '26');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd27', 'Day 27', '27');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd27', 'Day 27', '27');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd28', 'Day 28', '28');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd28', 'Day 28', '28');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd29', 'Day 29', '29');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd29', 'Day 29', '29');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd30', 'Day 30', '30');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd30', 'Day 30', '30');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd31', 'Day 31', '31');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sd31', 'Day 31', '31');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeschedule', 'Employee Schedule', 'Jadwal Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeschedule', 'Employee Schedule', 'Employee Schedule');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sd12', 'Day 12', '12');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'genjournalid', 'General Journal ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'genjournalid', 'General Journal ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'journalno', 'Journal No', 'No Journal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'journalno', 'Journal No', 'No Journal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'journalnote', 'Journal Note', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'journalnote', 'Journal Note', 'Journal Note');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'debit', 'Debit', 'Debit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'debit', 'Debit', 'Debit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'credit', 'Credit', 'Credit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'credit', 'Credit', 'Credit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detailnote', 'Detail Note', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'detailnote', 'Detail Note', 'Detail Note');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'permitin', 'Permit In', 'Jenis Ijin Masuk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'permitin', 'Permit In', 'Permit In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'permitinid', 'Permit In ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'permitinid', 'Permit In ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'permitinname', 'Permit In Name', 'Jenis Ijin Masuk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'permitinname', 'Permit In Name', 'Permit In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'permitexitid', 'Permit Exit ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'permitexitid', 'Permit Exit ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'permitexitname', 'Permit Exit Name', 'Jenis Ijin Keluar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'permitexitname', 'Permit Exit Name', 'Permit Exit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'permitexit', 'Permit Exit', 'Jenis Ijin Keluar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'permitexit', 'Permit Exit', 'Permit Exit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeename', 'Employee Name', 'Nama Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeename', 'Employee Name', 'Employee Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'permitindate', 'Permit In Date', 'Tgl Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'permitindate', 'Permit In Date', 'Tgl Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'permitintransid', 'Permit In Trans ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'permitintransid', 'Permit In Trans ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'nodocument', 'No Document', 'No Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'nodocument', 'No Document', 'Doc No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'permitintrans', 'Permit In Trans', 'Transaksi Ijin Masuk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'permitintrans', 'Permit In Trans', 'Permit In Transaction');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'approvedoc', 'Approve Doc', 'Dokumen telah disetujui oleh Atasan / Bagian Terkait');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'approvedoc', 'Approve Doc', 'Document has been approved by your superior');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'rejectdoc', 'Reject Doc', 'Dokumen telah ditolak oleh Atasan / Bagian Terkait');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'rejectdoc', 'Reject Doc', 'Document has been denied by your superior');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transinid', 'Trans In ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'transinid', 'Trans In ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addonwhere', 'Add On Where', 'Where');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'addonwhere', 'Add On Where', 'Where');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'permitexittrans', 'Permit Exit', 'Transaksi Ijin Keluar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'permitexittrans', 'Permit Exit', 'Permit Exit Trans');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transoutid', 'Transaction Out ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'transoutid', 'Transaction Out ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'startdate', 'Start Date', 'Tgl Mulai');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'startdate', 'Start Date', 'Start Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'enddate', 'End Date', 'Tgl Selesai');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'enddate', 'End Date', 'Tgl Selesai');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'onleavetype', 'Onleave Type', 'Jenis Cuti');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'onleavetype', 'Onleave Type', 'Jenis Cuti');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'onleavetypeid', 'Onleave Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'onleavetypeid', 'Onleave Type ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'onleavename', 'Onleave Name', 'Jenis Cuti');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'onleavename', 'Onleave Name', 'Jenis Cuti');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cutimax', 'Cuti Max', 'Maksimum Cuti');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'cutimax', 'Cuti Max', 'Cuti Max');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cutistart', 'Cuti Start', 'Masa Mulai Berlaku');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'cutistart', 'Cuti Start', 'Cuti Start');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeonleave', 'Employee Onleave', 'Karyawan Cuti');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeonleave', 'Employee Onleave', 'Employee Onleave');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeonleaveid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeonleaveid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'periodefrom', 'Periode From', 'Tgl Mulai Periode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'periodefrom', 'Periode From', 'Periode From');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'periodeto', 'Periode To', 'Tgl Akhir Periode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'periodeto', 'Periode To', 'Periode To');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'lastvalue', 'Last Value', 'Nilai Akhir');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'lastvalue', 'Last Value', 'Last Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportinid', 'Report In ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reportinid', 'Report In ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportin', 'Report In', 'Absen Masuk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reportin', 'Report In', 'Report In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportout', 'Report Out', 'Absen Pulang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reportout', 'Report Out', 'Report Out');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportoutid', 'Report Out ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reportoutid', 'Report Out ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportperday', 'Report Per Day', 'Absen Harian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reportperday', 'Report Per Day', 'Report Per Day');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportperdayid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reportperdayid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hourin', 'Hour In', 'Jam Masuk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hourin', 'Hour In', 'Hour In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hourout', 'Hour Out', 'Jam Pulang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hourout', 'Hour Out', 'Hour Out');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'absdate', 'Absence Date', 'Tgl Absen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'absdate', 'Absence Date', 'Absence Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'statusin', 'Status In', 'Status Masuk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'statusin', 'Status In', 'Status In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'statusout', 'Status Out', 'Status Pulang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'statusout', 'Status Out', 'Status Out');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reason', 'Reason', 'Alasan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reason', 'Reason', 'Reason');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'abstrans', 'Absence Transaction', 'Transaksi Absen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'abstrans', 'Absence Transaction', 'Transaksi Absen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'abstransid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'abstransid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'datetimeclock', 'Date Time Clock', 'Tgl dan Jam Absen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'datetimeclock', 'Date Time Clock', 'Tgl dan Jam Absen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'onleavetransid', 'Onleave Trans ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'onleavetransid', 'Onleave Trans ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'onleavetrans', 'Onleave Trans', 'Transaksi Cuti');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'onleavetrans', 'Onleave Trans', 'Onleave Transaction');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeovertime', 'Employee Overtime', 'Surat Perintah Kerja Lembur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeovertime', 'Employee Overtime', 'Surat Perintah Kerja Lembur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'overtimeno', 'Overtime No', 'No Dok');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'overtimeno', 'Overtime No', 'No Dok');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'overtimedate', 'Overtime Date', 'Tgl Dok');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'overtimedate', 'Overtime Date', 'Tgl Dok');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'overtimestart', 'Overtime Start', 'Tgl & Jam Mulai Lembur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeoverdet', 'Employee Overtime Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeoverdet', 'Employee Overtime Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'overtimestart', 'Overtime Start', 'Overtime Start');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'overtimeend', 'Overtime End', 'Tgl & Jam Akhir Lembur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'overtimeend', 'Overtime End', 'Overtime End');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeoverdetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeeoverdetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transsickness', 'Sickness Transaction', 'Transaksi Sakit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'transsickness', 'Sickness Transaction', 'Transaksi Sakit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transsicknessid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'transsicknessid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxwageprogressif', 'Tax Wage Progressif', 'Pajak Progressif');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'taxwageprogressif', 'Tax Wage Progressif', 'Tax Wage Progressif');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxwageprogressifid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'taxwageprogressifid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'minvalue', 'Min Value', 'Nilai Min');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'minvalue', 'Min Value', 'Min Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'maxvalue', 'Max Value', 'Nilai Max');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'maxvalue', 'Max Value', 'Max Value');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'valuepercent', 'Value Percent', 'Nilai (%)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'valuepercent', 'Value Percent', 'Value (%)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'billofmaterial', 'Bill of Material', 'Komposisi Bahan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'billofmaterial', 'Bill of Material', 'Komposisi Bahan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bomversion', 'Bom Version', 'Versi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bomversion', 'Bom Version', 'Versi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bomdate', 'Bom Date', 'Tanggal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bomdate', 'Bom Date', 'Tanggal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bomdetail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bomdetail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bomdetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bomdetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productbom', 'BOM', 'BOM');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productbom', 'BOM', 'BOM');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detailproduct', 'Detail Product', 'Detail Material / Service');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'detailproduct', 'Detail Product', 'Detail Material / Service');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detailuom', 'Detail UOM', 'Satuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'detailuom', 'Detail UOM', 'Satuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productplan', 'Production Planning', 'Surat Perintah Produksi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productplan', 'Production Planning', 'Production Planning');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hjeniskamar', 'Room Type', 'Jenis Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hjeniskamar', 'Room Type', 'Jenis Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hjeniskamarid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hjeniskamarid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomcode', 'Kode', 'Kode Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomcode', 'Kode', 'Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomname', 'Nama', 'Nama Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomname', 'Nama', 'Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hbedroomtypeid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hbedroomtypeid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hbedroomtype', 'Bed Room Type', 'Jenis Tempat Tidur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hbedroomtype', 'Bed Room Type', 'Jenis Tempat Tidur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bedcode', 'Bed Code', 'Kode Tempat Tidur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bedcode', 'Bed Code', 'Kode Tempat Tidur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bedname', 'Bed Name', 'Nama Tempat Tidur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bedname', 'Bed Name', 'Nama Tempat Tidur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hotelsetup', 'Hotel Setup', 'Hotel Setup');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hotelsetup', 'Hotel Setup', 'Hotel Setup');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomfacility', 'Room Facility', 'Fasilitas Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomfacility', 'Room Facility', 'Room Facility');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomfacilityid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomfacilityid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomfaccode', 'Room Facility Code', 'Kode Fasilitas Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomfaccode', 'Room Facility Code', 'Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomfacname', 'Room Facility Name', 'Nama Fasilitas Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomfacname', 'Room Facility Name', 'Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomdesc', 'Room Description', 'Deskripsi Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomdesc', 'Room Description', 'Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomdescid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomdescid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomdesccode', 'Room Description Code', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomdesccode', 'Room Description Code', 'Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomdescname', 'Room Description Name', 'Nama');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomdescname', 'Room Description Name', 'Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hagen', 'Agen', 'Agen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hagen', 'Agen', 'Agen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hagenid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hagenid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'agencode', 'Code', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'agencode', 'Code', 'Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'agenname', 'Name', 'Nama Agen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'agenname', 'Name', 'Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroom', 'Kamar', 'Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroom', 'Kamar', 'Room');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'anak', 'Anak', 'Jumlah Anak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'anak', 'Anak', 'Jumlah Anak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'dewasa', 'Dewasa', 'Jumlah Dewasa');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'dewasa', 'Dewasa', 'Dewasa');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'lantai', 'Lantai', 'Lantai');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'lantai', 'Lantai', 'Lantai');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomno', 'Room No', 'No Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomno', 'Room No', 'Room No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menugenid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menugenid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomprice', 'Room Price', 'Harga Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomprice', 'Room Price', 'Room Price');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroompriceID', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroompriceID', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roompricenominal', 'Nominal', 'Nominal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roompricenominal', 'Nominal', 'Nominal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomagenid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomagenid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomagennominal', 'Nominal', 'Harga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomagennominal', 'Nominal', 'Nominal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomagen', 'Room Agen', 'Harga Agen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomagen', 'Room Agen', 'Room Agen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomday', 'Room Day', 'Hari');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomday', 'Room Day', 'Day');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'room', 'Room', 'Tempat Tidur Tambahan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'room', 'Room', 'Bed Room');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'breakfast', 'Breakfast', 'Makan Pagi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'breakfast', 'Breakfast', 'Makan Pagi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hfacility', 'Room Facility', 'Fasilitas Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hfacility', 'Room Facility', 'Fasilitas Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hfacilityid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hfacilityid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'faccode', 'Code Facility', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'faccode', 'Code Facility', 'Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'facname', 'Name', 'Fasilitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'facname', 'Name', 'Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'plantname', 'Plant Name', 'Gedung');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'plantname', 'Plant Name', 'Gedung');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'filelayout', 'File Layout', 'Layout');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'filelayout', 'File Layout', 'Layout');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomsdesc', 'Rooms Description', 'Deskripsi Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomsdesc', 'Rooms Description', 'Rooms Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomsdescid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomsdescid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roompic', 'Room Pic', 'Gambar Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roompic', 'Room Pic', 'Room Pic');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hbanquetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hbanquetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hbanquet', 'Banquet', 'Banquet');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hbanquet', 'Banquet', 'Banquet');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'banqcode', 'Code', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'banqcode', 'Code', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'banqname', 'Name', 'Banquet');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'banqname', 'Name', 'Nama');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'banqluas', 'Luas', 'Luas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'banqluas', 'Luas', 'Luas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'banqopt', 'Optimal', 'Optimal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'banqopt', 'Optimal', 'Optimal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'banqmax', 'Max', 'Maks');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'banqmax', 'Max', 'Maks');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'banqsetup', 'Setup', 'Setup');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'banqsetup', 'Setup', 'Setup');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'banqclean', 'Clean', 'Clean');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'banqclean', 'Clean', 'Clean');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'banqnominal', 'Nominal', 'Nominal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'banqnominal', 'Nominal', 'Nominal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'banqdesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'banqdesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'banqpic', 'Picture', 'Gambar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'banqpic', 'Picture', 'Gambar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomperiodik', 'Room Periodik', 'Harga Kamar Periodik');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomperiodik', 'Room Periodik', 'Periodic Room Price');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hroomperiodikid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hroomperiodikid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'periodstart', 'Start Peiod', 'Mulai Tgl');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'periodstart', 'Start Peiod', 'Start');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'periodend', 'End Period', 'Akhir Tgl');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'periodend', 'End Period', 'End');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'perioddesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'perioddesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'nominalup', 'Nominal Up', 'Naik');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'nominalup', 'Nominal Up', 'Up');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'diskon', 'Diskon', 'Diskon (%)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'diskon', 'Diskon', 'Discount (%)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hstatusroom', 'Room Status', 'Status Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hstatusroom', 'Room Status', 'Status Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hstatusroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hstatusroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'statroomcode', 'Kode', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'statroomcode', 'Kode', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'statroomdesc', 'Keterangan', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'statroomdesc', 'Keterangan', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hgueststatusid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hgueststatusid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hgueststatus', 'Guest Status', 'Status Tamu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hgueststatus', 'Guest Status', 'Status Tamu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'guestcode', 'Kode', 'Kode Tamu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'guestcode', 'Kode', 'Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'guestname', 'Name', 'Status Tamu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'guestname', 'Name', 'Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hperusahaan', 'Perusahaan', 'Perusahaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hperusahaan', 'Perusahaan', 'Perusahaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hperusahaanid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hperusahaanid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'percode', 'Kode', 'Kode Perusahaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'percode', 'Kode', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'pername', 'Name', 'Nama Perusahaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'pername', 'Name', 'Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'frontdesk', 'Front Desk', 'Front Desk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'frontdesk', 'Front Desk', 'Front Desk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hcontract', 'Contract', 'Contract');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hcontract', 'Contract', 'Contract');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hcontractid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hcontractid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'contractdate', 'Contract Date', 'Tgl Kontrak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'contractdate', 'Contract Date', 'Tgl Kontrak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'checkindate', 'Check In', 'Tgl Check In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'checkindate', 'Check In', 'Check In Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'checkoutdate', 'Check Out', 'Tgl Check Out');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'checkoutdate', 'Check Out', 'Check Out Date Time');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'contractdesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'contractdesc', 'Description', 'Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fee3', 'Fee', 'Fee Pihak 3');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fee3', 'Fee', 'Fee Pihak 3');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hcontractroom', 'Contract Room', 'Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hcontractroom', 'Contract Room', 'Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hcontractroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hcontractroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomprice', 'Room Price', 'Harga Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomprice', 'Room Price', 'Harga Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hwaitlist', 'Waiting List', 'Daftar Tunggu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hwaitlist', 'Waiting List', 'Daftar Tunggu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hwaitlistid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hwaitlistid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'waitlistno', 'Waiting List No', 'No Tunggu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'waitlistno', 'Waiting List No', 'Waiting List No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'waitlistdate', 'Waiting List Date', 'Tgl Dok');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'waitlistdate', 'Waiting List Date', 'Tgl Dok');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'waitdesc', 'Waiting Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'waitdesc', 'Waiting Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hwaitroomlist', 'Room Waiting List', 'Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hwaitroomlist', 'Room Waiting List', 'Room');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hwaitroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hwaitroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'creditcard', 'Credit Card', 'Kartu Kredit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'creditcard', 'Credit Card', 'Kartu Kredit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'creditcardid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'creditcardid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'creditcardname', 'Credit Card Name', 'Kartu Kredit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'creditcardname', 'Credit Card Name', 'Credit Card Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hreservation', 'Reservation', 'Reservation');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hreservation', 'Reservation', 'Reservation');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reservedate', 'Reservation Date', 'Tgl Reservasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reservedate', 'Reservation Date', 'Tgl Reservasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'creditcardno', 'Credit Card No', 'No Kartu Kredit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'creditcardno', 'Credit Card No', 'No Kartu Kredit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'creditcarddate', 'Credit Card Date', 'Tgl Berlaku KK');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'creditcarddate', 'Credit Card Date', 'Tgl Berlaku KK');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reservdesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reservdesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reservdescin', 'Description', 'Keterangan Internal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reservdescin', 'Description', 'Keterangan Internal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isconfirm', 'Is Confirm', 'Konfirmasi ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isconfirm', 'Is Confirm', 'Confirm ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'iscancel', 'Is Cancel', 'Batal ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'iscancel', 'Is Cancel', 'Cancel ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'confirmdate', 'Confirm Date', 'Tgl Konfirmasi ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'confirmdate', 'Confirm Date', 'Confirm Date ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'confirmname', 'Confirmation Employee', 'Konfirmasi via Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'confirmname', 'Confirmation Employee', 'Konfirmasi via Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hreservationid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hreservationid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reserveno', 'No', 'No Reservasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'reserveno', 'No', 'No Reservasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'canceldate', 'Cancel Date', 'Tgl Batal ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'canceldate', 'Cancel Date', 'Cancel Date ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hreservroom', 'Room', 'Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hreservroom', 'Room', 'Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hreservroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hreservroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hcheckin', 'Check In', 'Check In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hcheckin', 'Check In', 'Check In');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hcheckinid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hcheckinid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'checkinno', 'No', 'No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'checkinno', 'No', 'No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'marketing', 'Marketing', 'Marketing');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'marketing', 'Marketing', 'Marketing');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hcheckindepositid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hcheckindepositid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hcheckindeposit', 'Deposit', 'Deposit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hcheckindeposit', 'Deposit', 'Deposit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hdepositdate', 'Deposit Date', 'Tgl Deposit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hdepositdate', 'Deposit Date', 'Deposit Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'jatuhtempo', 'Jatuh Tempo', 'Jatuh Tempo');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'jatuhtempo', 'Jatuh Tempo', 'Jatuh Tempo');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hcheckdesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hcheckdesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'deposit', 'Deposit', 'Deposit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'deposit', 'Deposit', 'Deposit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hfasilitastype', 'Facility Type', 'Jenis Fasilitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hfasilitastype', 'Facility Type', 'Jenis Fasilitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hfasilitastypeid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hfasilitastypeid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fastypecode', 'Facility Type Code', 'Kode Jenis Fasilitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fastypecode', 'Facility Type Code', 'Facility Type Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fastypename', 'Facility Type Name', 'Nama Jenis Fasilitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fastypename', 'Facility Type Name', 'Facility Type Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hfastarif', 'Tarif', 'Tarif Fasilitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hfastarif', 'Tarif', 'Tarif');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hfastarifid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hfastarifid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fastarifcode', 'Code', 'Kode Tarif');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fastarifcode', 'Code', 'Tarif Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fastarifname', 'Fasilitas Tarif Name', 'Nama Tarif Fasilitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fastarifname', 'Fasilitas Tarif Name', 'Nama Tarif Fasilitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fastarif', 'Tarif', 'Tarif');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fastarif', 'Tarif', 'Tarif');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hfasmember', 'Member', 'Member');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hfasmember', 'Member', 'Member');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hfasmemberid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hfasmemberid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fasmemberno', 'No Member', 'No Member');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fasmemberno', 'No Member', 'Member No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fasmembernama', 'Nama', 'Nama Member');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fasmembernama', 'Nama', 'Member Name');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fasmemberaddress', 'Alamat Member', 'Alamat Member');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fasmemberaddress', 'Alamat Member', 'Alamat Member');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fasmembertelp', 'Telp', 'Telp Member');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fasmembertelp', 'Telp', 'Phone');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'identityno', 'No Identitas', 'No Identitas');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'identityno', 'No Identitas', 'Identity No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fasmemberdesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'fasmemberdesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hgenre', 'Genre', 'Genre');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hgenre', 'Genre', 'Genre');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hgenreid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hgenreid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'genrename', 'Genre Name', 'Genre');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'genrename', 'Genre Name', 'Genre');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hlagu', 'Lagu', 'Lagu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hlagu', 'Lagu', 'Song');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hlaguid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hlaguid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'judul', 'Judul', 'Judul');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'judul', 'Judul', 'Title');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'penyanyi', 'Penyanyi', 'Penyanyi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'penyanyi', 'Penyanyi', 'Singer');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'lagufile', 'Lagu File', 'File Lagu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'lagufile', 'Lagu File', 'File Lagu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hkroom', 'Karaoke Room', 'Ruang Karaoke');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hkroom', 'Karaoke Room', 'Ruang Karaoke');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hkroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hkroomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hkroomname', 'Karaoke Room Name', 'Ruang Karaoke');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hkroomname', 'Karaoke Room Name', 'Ruang Karaoke');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'kroomprice', 'Room Price', 'Biaya Karaoke (Jam)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'kroomprice', 'Room Price', 'Karaoke Cost (Hour)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grqty', 'GR Qty', 'GR Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'grqty', 'GR Qty', 'GR Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'selisih', 'Selisih', 'Selisih');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'selisih', 'Selisih', 'Selisih');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectformrequest', 'Project Form Request', 'Permintaan Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectformrequest', 'Project Form Request', 'Permintaan Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'formrequestid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'formrequestid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'frno', 'FR No', 'No Dok');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'frno', 'FR No', 'Doc No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'frdate', 'FR Date', 'Tgl FR');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'frdate', 'FR Date', 'Tgl FR');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectno', 'No Doc', 'No Dok');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectno', 'No Doc', 'Doc No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'formrequestdet', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'formrequestdet', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'projectdate', 'Project Date', 'Tgl Dok Proyek');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'projectdate', 'Project Date', 'Project Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'formrequestdetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'formrequestdetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'poqty', 'PO Qty', 'PO Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'poqty', 'PO Qty', 'PO Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tsqty', 'TS Qty', 'TS Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'tsqty', 'TS Qty', 'TS Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrmenucat', 'Menu Category', 'Jenis Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrmenucat', 'Menu Category', 'Jenis Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrmenucatid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrmenucatid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menucatname', 'Menu Category Name', 'Kategori Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menucatname', 'Menu Category Name', 'Menu Category');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrmenu', 'Menu', 'Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrmenu', 'Menu', 'Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrmenuid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrmenuid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menucode', 'Kode', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menucode', 'Kode', 'Kode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuprosesvid', 'Video', 'Video');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuprosesvid', 'Video', 'Video');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuproses', 'Proses', 'Proses Pembuatan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuproses', 'Proses', 'Menu Proses');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuphoto', 'Photo', 'Photo');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuphoto', 'Photo', 'Photo');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrmenubom', 'Bahan', 'Bahan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrmenubom', 'Bahan', 'Bahan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrmenubomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrmenubomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrmeja', 'Meja', 'Meja');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrmeja', 'Meja', 'Table');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrmejaid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrmejaid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'mejacode', 'Meja Code', 'Kode Meja');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'mejacode', 'Desk Code', 'Table Code');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'mejacapacity', 'Meja Capacity', 'Kapasitas Meja');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'mejacapacity', 'Meja Capacity', 'Table Capacity');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'mejadesc', 'Meja Desc', 'Keterangan Meja');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'mejadesc', 'Meja Desc', 'Table Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'mejafill', 'Meja Fill', 'Terisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'mejafill', 'Meja Fill', 'Fill');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isbooked', 'Is Booked', 'Terpesan ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isbooked', 'Is Booked', 'Booked ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuprice', 'Price', 'Harga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuprice', 'Price', 'Harga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrpesanan', 'Pesanan', 'Pesanan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrpesanan', 'Pesanan', 'Pesanan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrpesananid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrpesananid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'pesananno', 'No Pesanan', 'No Pesanan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'pesananno', 'No Pesanan', 'Order No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'pesanandesc', 'Pesanan Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'pesanandesc', 'Pesanan Description', 'Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrpesananmenu', 'Menu', 'Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrpesananmenu', 'Menu', 'Menu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrpesananmenuid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrpesananmenuid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hrroom', 'Room', 'Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'hrroom', 'Room', 'Kamar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'roomorcustmustfill', 'Room or Customer Empty', 'Kamar / Pelanggan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'roomorcustmustfill', 'Room or Customer Empty', 'Room / Customer must fill');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'pesanandate', 'Pesanan Date', 'Tgl Pesan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'pesanandate', 'Pesanan Date', 'Tgl Pesan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'usergroup', 'User Group', 'Otorisasi User dan Grup');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'usergroup', 'User Group', 'Otorisasi User dan Grup');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'usergroupid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'usergroupid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuaccessid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'menuaccessid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'genledgerid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'genledgerid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'postdate', 'Post Date', 'Tgl Posting');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'postdate', 'Post Date', 'Tgl Posting');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ratevalue', 'Rate Value', 'Rate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'ratevalue', 'Rate Value', 'Rate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'repsmgbi', 'Report SMGBI', 'Laporan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'repsmgbi', 'Report SMGBI', 'Report');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tablenamemustentry', 'Table Must No Be Empty', 'Nama Tabel harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'tablenamemustentry', 'Table Must No Be Empty', 'Table Must filled');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isreport', 'Is Report', 'Report');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isreport', 'Is Report', 'Report');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'userinput', 'User Input', 'User Input');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'userinput', 'User Input', 'User Input');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'namamurid', 'Nama Murid', 'Nama Murid');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'namamurid', 'Nama Murid', 'Nama Murid');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'namaayah', 'Nama Ayah', 'Nama Ayah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'namaayah', 'Nama Ayah', 'Father');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'namaibu', 'Nama Ibu', 'Nama Ibu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'namaibu', 'Nama Ibu', 'Mother');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'notelp', 'No Telp', 'No Telp');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'notelp', 'No Telp', 'Ph No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'jamibadah', 'Jam', 'Jam');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'jamibadah', 'Jam', 'Hour');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'telat', 'Telat', 'Telat');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'telat', 'Telat', 'Late');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'keterangan', 'Keterangan', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'keterangan', 'Keterangan', 'Description');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'namaortu/telp', 'Nama Ortu / Telp', 'Nama Ortu / Telp');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payrollperiod', 'Payroll Period', 'Periode Penggajian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'payrollperiod', 'Payroll Period', 'Payroll Period');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeebenefit', 'Employee Benefit', 'Pendapatan karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeebenefit', 'Employee Benefit', 'Employee Benefit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payrollprocess', 'Payroll Process', 'Proses Penggajian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'payrollprocess', 'Payroll Process', 'Proses Penggajian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeewage', 'Employee Wage', 'Gaji Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeewage', 'Employee Wage', 'Gaji Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeetax', 'Employee Tax', 'Pajak PPh21');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'employeetax', 'Employee Tax', 'Pajak PPh21');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'repneracaid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'repneracaid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isdebet', 'Is Debet', 'Is Debet');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'isdebet', 'Is Debet', 'Is Debet');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sinvoicear', 'Simple Invoice AR', 'Invoice Pelanggan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sinvoicear', 'Simple Invoice AR', 'Invoice Customer');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invoiceid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'customername', 'Customer Name', 'Pelanggan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'customername', 'Customer Name', 'Customer');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'simpleaccounting', 'Simple Accounting', 'Akuntansi Sederhana');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'simpleaccounting', 'Simple Accounting', 'Simple Accounting');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicedet', 'Invoice Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invoicedet', 'Invoice Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptycompanyid', 'Company', 'Perusahaan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptycompanyid', 'Company', 'Company must be filled');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyaddressbookid', 'Empty Address Book', 'Pihak ke-3 harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptyaddressbookid', 'Empty Address Book', 'Address Book must be filled');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'rate', 'Rate', 'Rate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'rate', 'Rate', 'Rate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'amount', 'Amount', 'Amount');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'amount', 'Amount', 'Amount');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicedetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invoicedetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicedetcur', 'Currency', 'Mata Uang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invoicedetcur', 'Currency', 'Currency');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicedetrate', 'Rate', 'Rate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invoicedetrate', 'Rate', 'Rate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicedisc', 'Discount', 'Discount');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invoicedisc', 'Discount', 'Discount');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicediscid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invoicediscid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicejurnalid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invoicejurnalid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicejurnal', 'Jurnal', 'Jurnal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invoicejurnal', 'Jurnal', 'Jurnal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'debet', 'Debet', 'Debet');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'debet', 'Debet', 'Debet');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'jurnalcur', 'Currency', 'Mata Uang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'jurnalcur', 'Currency', 'Currency');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'jurnalrate', 'Rate', 'Rate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'jurnalrate', 'Rate', 'Rate');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'notbalance', 'Not Balance', 'Jurnal Tidak Balance');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'notbalance', 'Not Balance', 'Unbalance');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'scashbankar', 'Cash Bank AR', 'Penerimaan Kas / Bank');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'scashbankar', 'Cash Bank AR', 'Cash Bank AR');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'cashbankid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceno', 'Invoice No', 'No Invoice');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'invoiceno', 'Invoice No', 'Invoice No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'chequegirono', 'Check/Giro No', 'No Cek / Giro');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'chequegirono', 'Check/Giro No', 'Cheque / Giro No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accountno', 'Account No', 'No Rekening');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'accountno', 'Account No', 'Account No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankjurnalid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'cashbankjurnalid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cbjurnalcur', 'Currency', 'Mata Uang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'cbjurnalcur', 'Currency', 'Mata Uang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productstockid', 'Product STock ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productdetailid', 'Product Detail', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeoverid', 'Employee Overtime', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptydetailproductid', 'Empty Detail Product', 'Material / Service harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'emptydetailproductid', 'Empty Detail Product', 'Material / Service can not empty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productplanid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productplanid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productplanno', 'No', 'No SPP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productplanno', 'No', 'No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productplandate', 'Date', 'Tgl SPP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productplandate', 'Date', 'Production Planning Date');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sono', 'SO No', 'No SO');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'sono', 'SO No', 'SO No');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'alreadylogout', 'User Already Logout', 'Anda sudah logout, silahkan login kembali');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slideshowid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slidepic', 'Slide Picture', 'Gambar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slidetitle', 'Title Slide', 'Judul');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slidedesc', 'Slide Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmateri', 'Materi', 'Materi / Quiz');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'simplelearning', 'Simple Learning', 'Pembelajaran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterislideid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silslidetitle', 'Slide Title', 'Judul Slide');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmateriid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterititle', 'Materi Title', 'Judul');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isquiz', 'Is Quiz', 'Quiz ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmateridesc', 'Materi Description', 'Keterangan Materi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterifile', 'Materi File', 'File Materi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterislide', 'Materi Slide', 'Slide');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterigroup', 'Materi Group', 'Grup Materi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterioption', 'Materi Option', 'Opsi Jawaban');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterivideo', 'Video', 'Video');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silslidedesc', 'Description', 'Keterangan Slide');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silslidepic', 'Slide Picture File', 'Gambar Slide');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterigroupid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterioptionid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'optiona', 'Option A', 'A');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'optionb', 'Option B', 'B');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'optionc', 'Option C', 'C');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'optiond', 'Option D', 'D');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterioptdesc', 'Description', 'Soal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmaterivideoid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'videofile', 'File Video', 'Video');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silvideodesc', 'Video Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'Answer', 'Answer', 'Jawaban');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silmateripic', 'Materi Picture', 'Gambar Sampul');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'siluserquiz', 'User Quiz', 'User Quiz');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'siluserquizid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silquizdate', 'Quiz Date', 'Tgl Quiz');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silquiztimestart', 'Time Start', 'Jam Mulai');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'silquiztimeend', 'Time End', 'Jam Akhir');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'siluserscore', 'User Score', 'Nilai');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'siluseransid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'siluserans', 'User Answer', 'Jawaban');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'biodata', 'biodata', 'Data Karyawan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'biodataid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'buku', 'Buku', 'Buku');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'formrequest', 'Form Request', 'Form Permintaan Barang (FPB)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'itemtext', 'Item Text', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptypositionid', 'Empty Position', 'Posisi harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'downpdf', 'Download PDF', 'PDF');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'downxls', 'Download XLS', 'XLS');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'parentid', 'Parent', 'Induk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menuurl', 'Menu Url', 'Url');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sortorder', 'Sort Order', 'Sort Order');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'menutitle', 'Menu Title', 'Judul');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'snrodesc', 'SNRO', 'Penomoran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productstockdetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'storagedesc', 'Storage Description', 'Rak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bukuid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'namabuku', 'Judul', 'Judul Buku');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'penulis', 'Penulis', 'Penulis');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'curvalue', 'Current Value', 'Nomor Terakhir');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detailqty', 'Detail Qty', 'Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detaildescription', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'notallownull', 'Not Allow Null', 'Field harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'stockopnamedetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'vrqty', 'Qty Booked', 'Qty Terpesan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'parentstructure', 'Parent Structure', 'Struktur Induk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'parentname', 'Parent Structure', 'Struktur Induk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyreligion', 'Empty Religion', 'Agama harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sodetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sodiscid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyemail', 'Empty Email', 'Email harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyphoneno', 'Empty Phone No', 'No Telp harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'storagebindesc', 'Storage Bin', 'Rak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sodate', 'SO Date', 'Tgl SO');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productplanfg', 'FG Planning', 'Finish Goods');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productplandetail', 'Production Planning Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bom', 'Bill of Material', 'BOM');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productplanfgid', 'ID Production Planning', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productplandetailid', 'ID Production Planning Detail', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fromsloc', 'From Sloc', 'Gudang Asal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tosloc', 'To Sloc', 'Gudang Tujuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reqdate', 'Request Date', 'Tgl Butuh');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'formreqpp', 'Form Request Production Planning', 'FPB - SPP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'datagenerated', 'Data Generated', 'Data telah dihasilkan dari dokumen asal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'mrpid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'minstock', 'Min Stock', 'Min Stock');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reordervalue', 'Reorder Value', 'Nilai Perlu Order');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'mrp', 'Material Requirement Planning', 'Material Requirement Planning');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'leadtime', 'Lead Time', 'Waktu Kedatangan (hari)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'stockopnameno', 'Stock Opname No', 'No Stock Opname');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportorder', 'Order Report', 'Laporan Penjualan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reporttype', 'Report Type', 'Jenis Laporan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'date', 'Date', 'Tanggal Order');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptysalesareaid', 'Empty Sales Area', 'Sales Area harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'deliverydate', 'Delivery Date', 'Tgl Pengiriman');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productoutput', 'Output Production', 'Hasil Produksi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productdetailhistid', 'Product Detail History ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productdetailhistid', 'Product Detail History ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bsheader', 'Stock Opname', 'Stock Opname');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'bsheader', 'Stock Opname', 'Stock Opname');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productdet', 'Product', 'Material / Service');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productdet', 'Product', 'Material / Service');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'qtydet', 'Qty', 'Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'qtydet', 'Qty', 'Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'uomdet', 'UOM', 'Satuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'uomdet', 'UOM', 'Satuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slocdet', 'Sloc', 'Gudang/Dept');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'slocdet', 'Sloc', 'Sloc');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'storagebindet', 'Storage Bin', 'Rak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'storagebindet', 'Storage Bin', 'Rak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productstockdet', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productstockdet', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productdetailhist', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (2, 'productdetailhist', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productoutputid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productoutputno', 'Product Output No', 'No Produksi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productoutputdate', 'Production Output Date', 'Tgl Produksi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productoutputfgid', 'Product Output FG ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productoutputfg', 'Product Output FG', 'Finish Goods');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productoutputdetail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'qtyoutput', 'Qty Output', 'Qty');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'outputdate', 'Output Date', 'Tgl Output');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fgdescription', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'productoutputdetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'storagebindetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detailbomid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detaildesc', 'Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'storagebindetail', 'Storage Bin', 'Rak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'detailbom', 'Detail BOM', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyheadernote', 'Empty Header Note', 'Keterangan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'purchinforec', 'Purchasing Info Record', 'Sejarah Pembelian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'purchinforecid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'deliverytime', 'Delivery Time', 'Waktu Kedatangan (hari)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'underdelvtol', 'Under Delivery Tolerance', 'Batas Bawah (%)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'overdelvtol', 'Over Delivery Tolerance', 'Batas Atas (%)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'biddate', 'Bid Date', 'Tgl Penawaran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'prheader', 'Purchase Requisition', 'Permohonan Pembelian (FPP)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grheader', 'Goods Receipt', 'Tanda Terima Barang (TTB)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'giheader', 'Goods Issue', 'Surat Jalan (SJ)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grretur', 'Purchase Retur', 'Retur Pembelian (RPEM)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'giretur', 'Order Retur', 'Retur Penjualan (RPEN)');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transferout', 'Transfer Out', 'Transfer Keluar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transferin', 'Transfer In', 'Transfer Masuk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'prheaderid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'prdate', 'PR Date', 'Tgl FPP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'prno', 'PR No', 'No FPP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'prmaterialid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportwrh', 'Warehouse Report', 'Laporan Gudang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportprd', 'Production Report', 'Laporan Produksi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'prmaterial', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'poheader', 'Purchase Order', 'Purchase Order');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportpur', 'Purchasing Report', 'Laporan Pembelian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'poheaderid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'suppliername', 'Supplier', 'Supplier');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'pono', 'PO No', 'No PO');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payamount', 'pay', 'Pembayaran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'podetail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'delvdate', 'Delivery Date', 'Tgl Kirim');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'podetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'netprice', 'Price', 'Harga');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'qtyres', 'Qty Res', 'Qty Terima');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'popr', 'PO PR', 'FPP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'poprid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'prqty', 'Qty PR', 'Qty FPP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'giqty', 'GI Qty', 'Qty TS');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ipoover', 'PO Over PR', 'Qty PO tidak boleh melebihi Qty PR');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grheaderid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grdate', 'GR Date', 'Tgl TTB');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grno', 'GR No', 'No TTB');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grdetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grdetail', 'GR Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'giheaderid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gino', 'GI No', 'No SJ');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gidate', 'GI Date', 'Tgl SJ');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gidetail', 'GI Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gidetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'stockemptyatfg', 'Stock Empty at FG', 'Stok tidak cukup di Gudang FG');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gireturid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gireturno', 'GI Retur No', 'No Retur Pembelian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gireturdate', 'GI Retur Date', 'Tgl Retur Pembelian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gireturdetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'gireturdetail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyitemnote', 'Empty Item Note', 'Keterangan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grreturid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grreturdetail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grreturno', 'GR Retur No', 'No RPEM');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grreturdate', 'GR Retur Date', 'Tgl RPEM');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'grreturdetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transstockid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transstockno', 'Transfer Stock No', 'No Transfer');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyunitofissue', 'Empty Unit of Issue', 'Satuan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slocfrom', 'slocfrom', 'Gudang Asal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slocto', 'slocto', 'Gudang Tujuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transstockdet', 'transstockdet', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transstockdetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'storagebinto', 'Storage Bin To', 'Rak Tujuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'stockemptyatrm', 'Stock Empty', 'Stok tidak mencukupi di gudang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tsoverqty', 'TS Qty over FR Qty', 'Qty kirim melebihi Qty permintaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'qtynotenough', 'Qty not Enough', 'Qty tidak mencukupi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slocfromcode', 'Sloc From Code', 'Gudang Asal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sloctocode', 'Sloc To code', 'Gudang Tujuan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'qtyreceipt', 'Qty Receipt', 'Qty Terima');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'igrover', 'GR Over PO', 'Qty TTB melebihi Qty PO');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'qtystock', 'Qty Stock', 'Qty Stock');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transferfg', 'FG Transfer', 'Transfer Hasil Produksi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceap', 'Invoice AP', 'Tagihan dari Supplier');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceapid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicedate', 'Invoice Date', 'Tgl Invoice');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'receiptdate', 'Receipt Date', 'Tgl Terima');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxdate', 'Tax Date', 'Tgl Faktur Pajak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceapmat', 'GR Material', 'Tanda Terima Barang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceapmatid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceapjurnalid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'currencydet', 'Currency', 'Mata Uang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'currencydetrate', 'Rate', 'Kurs');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceapjurnal', 'Jurnal', 'Jurnal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slocacc', 'Sloc Accounting', 'Sloc Accounting');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slocaccid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'materialgroupdesc', 'Material Group Description', 'Keterangan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accaktivaname', 'Account Activa', 'Akun Aktiva');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accakumatname', 'Account Akumulasi', 'Akun Akumulasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accbiayaatname', 'Account Biaya', 'Akun Biaya');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accpersediaanname', 'Account Persediaan', 'Akun Persediaan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accreturpembelianname', 'Account Retur Pembelian', 'Akun Retur Pembelian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accdiscbeliname', 'Account Disc Beli', 'Akun Diskon Beli');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accpenjualanname', 'Account Penjualan', 'Akun Penjualan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accbiayaname', 'Account Biaya', 'Akun Biaya');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accreturjualname', 'Account Retur Jual', 'Akun Retur Jual');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accspsiname', 'Account SPSI', 'Akun SPSI');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'hppname', 'Account HPP', 'HPP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accupahlemname', 'Account Upah Lembur', 'Akun Upah Lembur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fohname', 'Account FOH', 'Akun FOH');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accexpedisiname', 'Account Expedisi', 'Akun Expedisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'totalpajak', 'Total Pajak', 'Total Setelah Pajak');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'acchutangname', 'Account Hutang', 'Akun Hutang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accpiutangname', 'Account Piutang', 'Akun Piutang');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addressacc', 'Address Accounting', 'Akun Alamat');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxacc', 'Account Tax', 'Akun Tax');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxaccid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accmasukname', 'Account Pajak Masukan', 'Akun Pajak Masukan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'acckeluarname', 'Account Pajak Keluaran', 'Akun Pajak Keluaran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'addressaccid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceempty', 'Invoice Empty', 'No Invoice harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payrequest', 'Payment Request', 'Permohonan Pembayaran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payrequestid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payreqno', 'Payment Request No', 'No Mohon Bayar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payreqdate', 'Payment Request Date', 'Tgl Mohon Bayar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payrequestdet', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payrequestdetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'paydate', 'Pay Date', 'Tgl Bayar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicear', 'Invoice AR', 'Tagihan ke Customer');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicearid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceardate', 'Invoice AR Date', 'Tgl Invoice AR');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoicearno', 'Invoice AR No', 'No Invoice AR');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceardet', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invoiceardetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'invamount', 'Invoice Amount', 'Tertagih');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptybillto', 'Empty Bill To', 'Alamat Tagihan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptyinvoicearno', 'Empty Invoice AR No', 'No Invoice harus diisi, jika dari E-TAX, masukkan terlebih dahulu via E-TAX');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fixasset', 'Fix Asset', 'Fix Asset');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'nilairesidu', 'Nilai Residu', 'Nilai Residu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fixassetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accakum', 'Account Akumulasi', 'Akun Akumulasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accbiaya', 'Account Biaya', 'Akun Biaya');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accperolehan', 'Account Perolehan', 'Akun Perolehan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'acckorpem', 'Account Koreksi Pembelian', 'Akun Koreksi Pembelian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'umur', 'Umur', 'Umur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'journaldate', 'Journal Date', 'Tgl Jurnal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'journaldetail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'journaldetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptydetailnote', 'Empty Detail Note', 'Keterangan harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'junbalance', 'Jurnal Not Balance', 'Jurnal Belum Balance, silahkan cek detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'budget', 'Budget', 'Budget');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'budgetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'budgetdate', 'Budget Date', 'Tgl Budget');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'budgetamount', 'Budget Amount', 'Nilai Budget');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'repprofitlossid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankap', 'Cash Bank AP', 'Pembayaran ke Supplier');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankapid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankapno', 'Cash Bank AP No', 'No Cash Bank AP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payreqid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankapinv', 'Invoice', 'Invoice');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankappay', 'Payment', 'Pembayaran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankapinvid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankappayid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'amountdet', 'Amount', 'Nilai Pembayaran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankapdate', 'Cash Bank AP Date', 'Tgl Cash Bank AP');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'appayinvnotbalance', 'Invoice and Pay Not Balance', 'Invoice dan Pembayaran Tidak Balance');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'apaging', 'AP Aging', 'AP Aging');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reportacc', 'Report Accounting', 'Laporan Akunting');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankumum', 'General Cash Bank', 'Kas/Bank Umum');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankuid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankuno', 'General Cash Bank No', 'No Kas/Bank Umum');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankudate', 'General Cash Bank Date', 'Tgl Kas/Bank Umum');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankudet', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankudetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'debetcreditnotbalance', 'Debet Credit Not Balance', 'Debet Credit Tidak Balance');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbanku', 'General Cash Bank', 'Cash Bank Umum');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'widgetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'widgetname', 'Widget Name', 'Nama Widget');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'widgettitle', 'Widget Title', 'Judul Widget');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'widgetversion', 'Widget Version', 'Versi Widget');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'widgetby', 'Widget By', 'Pembuat');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'widgeturl', 'Widget Url', 'Alamat Widget');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'userdash', 'User Dashboard', 'User Dashboard');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'userdashid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'webformat', 'Web Format', 'Web Format');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'dashgroup', 'Dash Group', 'Grup Dashboard');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankar', 'Cash Bank AR', 'Kas/Bank AR');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankarid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankarno', 'Cash Bank AR No', 'No Kas/Bank AR');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankardate', 'Cash Bank AR Date', 'Tgl Kas/Bank AR');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'emptymenuvalueid', 'Empty Menu Value ID', 'Nilai ID harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankardet', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'cashbankardetid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'famethod', 'FA Method', 'Metode Penyusutan Asset');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'famethodid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'methodname', 'Method Name', 'Metode Penyusutan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fahistory', 'FA History', 'Data Penyusunan Aset');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'assetno', 'Asset No', 'No Asset');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accakumcode', 'Account Akumulasi', 'Akun Akumulasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accbiayacode', 'Account Biaya', 'Akun Biaya Penyusutan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'accperolehancode', 'Account Perolehan', 'Akun Perolehan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'acckorpemcode', 'Account Koreksi Perolehan', 'Akun Koreksi Perolehan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'nilaibuku', 'Nilai Buku', 'Nilai Buku');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'fahistoryid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'bulanke', 'Bulan Ke', 'Bulan ke');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'susutdate', 'Susut Date', 'Tgl Penyusutan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'nilai', 'Nilai Perolehan', 'Nilai Perolehan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'beban', 'Beban Penyusutan', 'Beban Penyusutan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'nilaiakum', 'Nilai Akumulasi', 'Nilai Akumulasi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd1name', 'Day 1', 'Hari ke-1');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd2name', 'Day 2', 'Hari ke-2');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd3name', 'Day 3', 'Hari ke-3');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd4name', 'Day 4', 'Hari ke-4');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd5name', 'Day 5', 'Hari ke-5');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd6name', 'Day 6', 'Hari ke-6');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd7name', 'Day 7', 'Hari ke-7');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd8name', 'Day 8', 'Hari ke-8');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd9name', 'Day 9', 'Hari ke-9');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd10name', 'Day 10', 'Hari ke-10');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd11name', 'Day 11', 'Hari ke-11');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd12name', 'Day 12', 'Hari ke-12');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd13name', 'Day 13', 'Hari ke-13');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd14name', 'Day 14', 'Hari ke-14');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd15name', 'Day 15', 'Hari ke-15');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd16name', 'Day 16', 'Hari ke-16');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd17name', 'Day 17', 'Hari ke-17');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd18name', 'Day 18', 'Hari ke-18');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd19name', 'Day 19', 'Hari ke-19');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd20name', 'Day 20', 'Hari ke-20');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd21name', 'Day 21', 'Hari ke-21');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd22name', 'Day 22', 'Hari ke-22');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd23name', 'Day 23', 'Hari ke-23');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd24name', 'Day 24', 'Hari ke-24');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd25name', 'Day 25', 'Hari ke-25');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd26name', 'Day 26', 'Hari ke-26');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd27name', 'Day 27', 'Hari ke-27');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd28name', 'Day 28', 'Hari ke-28');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd29name', 'Day 29', 'Hari ke-29');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd30name', 'Day 30', 'Hari ke-30');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'd31name', 'Day 31', 'Hari ke-31');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wagetype', 'Wage Type', 'Jenis Penggajian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wagetypeid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wagename', 'Wage Name', 'Jenis Gaji');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ispph', 'Is PPH', 'PPH ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ispayroll', 'Is Payroll', 'Payroll ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'percentage', 'Percentage', 'Persentase');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isrutin', 'Is Rutin', 'Rutin ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'paidbycompany', 'Paid By Company', 'Dibayar Perusahaan ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'pphbycompany', 'Tax By Company', 'Pajak dibayar Perusahaan ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'schedulename', 'Schedule', 'Schedule');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeebenefitid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeebenefitdetail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeebenefitdetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'isfinal', 'Is Final', 'Final ?');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeewageid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wagestartperiod', 'Start Period', 'Awal Periode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wagevalue', 'Wage Value', 'Nilai Pendapatan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'wageendperiod', 'Wage End Period', 'Akhir Periode');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payrollperiodid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payrollperiodname', 'payrollperiodname', 'Periode Penggajian');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'payrollparentname', 'Payroll Parent Name', 'Payroll Induk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeestatusid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeestatusname', 'Employee Status', 'Employee Status');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeewstatus', 'Employee With Status', 'Karyawan Status Pernikahan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeewstatusid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeetaxid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeetaxdetail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxstartperiod', 'Tax Start Period', 'Periode Awal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'taxendperiod', 'Tax End Period', 'Periode Akhir');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeetaxdetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transin', 'Permit In Transaction', 'Transaksi Ijin Masuk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transout', 'Permit Exit Transaction', 'Transaksi Ijin Keluar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'onleavetransno', 'Onleave Transaction No', 'No Cuti');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transinno', 'Permit In Transaction', 'No Ijin Masuk');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transoutno', 'Transaction Out No', 'No Ijin Keluar');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'transsickno', 'Sickness Transaction No', 'No Ijin Sakit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeetypesick', 'Employee Type Sickness', 'Jenis Sakit Karyawan ');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'sickdesc', 'Sickness Description', 'Keterangan Sakit');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'employeeover', 'Employee Overtime', 'Transaksi Lembur');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'absruleid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'legaldocid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'legaldoctitle', 'Title', 'Judul Dokumen');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'legaldocfile', 'File', 'File');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'legaldoc', 'Legal Document', 'Dokumen Legal');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'genaffair', 'General Affair', 'General Affair');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tickettype', 'Ticket Type', 'Jenis Kegiatan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tickettypeid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tickettypecode', 'Ticket Type Code', 'Kode Kegiatan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tickettypedesc', 'Ticket Type Description', 'Keterangan Kegiatan');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'troublelevelid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'levelcode', 'Level Code', 'Kode Level Trouble');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'leveldesc', 'Trouble Level Description', 'Keterangan Level Trouble');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'troubleminvalue', 'Trouble Level Min Value', 'Nilai Min Level Trouble');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'troublemaxvalue', 'Trouble Level Max Value', 'Nilai Max Level Trouble');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'troublelevel', 'Trouble Level', 'Level Trouble');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'machine', 'Machine', 'Mesin / Tools / IT');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'machineid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ticket', 'Troubleshooting Ticket', 'Troubleshooting');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ticketid', 'Troubleshooting Ticket ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'ticketdate', 'Ticket Date', 'Tgl Ticket');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'reporter', 'Reporter', 'Pelapor');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'tickettitle', 'Ticket Title', 'Judul Tiket');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'problemdesc', 'Problem Description', 'Keterangan Masalah');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'currentyear', 'Current Year', 'Tahun Ini');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'lastyear', 'Last Year', 'Tahun Lalu');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'biddoc', 'Bidding Document', 'Dok Penawaran');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'suratdokter', 'Surat Dokter', 'Surat Dokter');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'loidate', 'LOI Date', 'Tgl Letter of Intent');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'letterofintent', 'Letter of Intent', 'Letter of Intent');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'letterofintentid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'loidoc', 'LOI Doc', 'Dokumen LOI');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'loidetail', 'Detail', 'Detail');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'loidetailid', 'ID', 'ID');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'poempty', 'PO Empty', 'PO harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'storagebinempty', 'Storage Bin Empty', 'Rak harus diisi');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'slideshow', 'Slide Show', 'Slide Show');
				insert into `catalogsys` (`languageid`, `catalogname`, `description`, `catalogval`) VALUES (1, 'groupmenu', 'Group Menu', 'Otorisasi Group Menu');
			";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `currency` (
					`currencyid` INT(11) NOT NULL AUTO_INCREMENT,
					`countryid` INT(11) NOT NULL,
					`currencyname` VARCHAR(50) NOT NULL,
					`symbol` VARCHAR(3) NOT NULL,
					`i18n` VARCHAR(5) NOT NULL DEFAULT 'id_id',
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`currencyid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (1, 1, 'Andoran peseta', 'ADP', '', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (2, 2, 'United Arab Emirates Dirham', 'AED', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (3, 3, 'Afghani (Old)', 'AFA', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (4, 3, 'Afghani', 'AFN', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (5, 6, 'Albanian Lek', 'ALL', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (6, 7, 'Armenian Dram', 'AMD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (7, 9, 'Angolanische Kwanza', 'AOA', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (8, 9, 'Angolan New Kwanza (Old)', 'AON', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (9, 9, 'Angolan Kwanza Reajustado (Old)', 'AOR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (10, 11, 'Argentine Peso', 'ARS', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (11, 13, 'Austrian Schilling', 'ATS', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (12, 14, 'Australian Dollar', 'AUD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (13, 15, 'Aruban Guilder', 'AWG', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (14, 16, 'Azerbaijan Manat', 'AZM', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (15, 17, 'Bosnia and Herzegovina Convertible Mark', 'BAM', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (16, 18, 'Barbados Dollar', 'BBD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (17, 19, 'Bangladesh Taka', 'BDT', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (18, 20, 'Belgian Franc', 'BEF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (19, 22, 'Bulgarian Lev', 'BGN', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (20, 23, 'Bahrain Dinar', 'BHD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (21, 24, 'Burundi Franc', 'BIF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (22, 26, 'Bermudan Dollar', 'BMD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (23, 27, 'Brunei Dollar', 'BND', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (24, 28, 'Boliviano', 'BOB', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (25, 12, 'American Samoa', 'USD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (26, 21, 'Communaute Financiere Africaine franc CFA Franc', 'XOF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (27, 25, 'Communaute Financiere Africaine franc', 'XOF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (28, 29, 'Real', 'BRL', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (29, 4, 'East Caribbean dollar', 'XCD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (30, 5, 'East Caribbean dollar', 'XCD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (31, 30, 'Bahamian dollar', 'BSD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (32, 31, 'ngultrum', 'BTN', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (33, 31, 'Indian Rupee', 'INR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (34, 33, 'Pula', 'BWP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (35, 34, 'Belarusian ruble', 'BYB', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (36, 35, 'Belizean dollar', 'BZD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (37, 36, 'Canadian Dollar', 'CAD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (38, 37, 'Australian dollar', 'AUD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (39, 38, 'Congolese franc', 'CDF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (40, 92, 'Rupiah', 'Rp.', 'id_id', 1);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (41, 209, 'United States Dollar', 'USD', 'id_id', 1);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (42, 208, 'Poundsterling', 'GBP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (43, 179, 'Singapore Dollar', 'SGD', 'id_id', 1);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (44, 39, 'Communaute Financiere Africaine franc', 'XAF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (45, 40, 'Communaute Financiere Africaine franc', 'XAF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (46, 41, 'Swiss franc', 'CHF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (47, 42, 'Communaute Financiere Africaine franc', 'XAF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (48, 43, 'New Zealand dollar', 'NZD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (49, 44, 'Chilean peso', 'CLP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (50, 45, 'Communaute Financiere Africaine franc', 'XAF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (51, 46, 'Yuan', 'CNY', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (52, 47, 'Colombian peso', 'COP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (53, 48, 'Costa Rican colon', 'CRC', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (54, 49, 'Cuban peso', 'CUP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (55, 50, 'Cape Verdean escudo', 'CVE', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (56, 51, 'Australian dollar', 'AUD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (57, 52, 'Cypriot pound', 'CYP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (58, 52, 'Turkish lira', 'TRL', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (59, 53, 'Czech Koruna', 'CZK', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (60, 54, 'Euro', 'ÃƒÆ', 'id_id', 1);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (61, 55, 'Djiboutian franc', 'DJF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (62, 56, 'Danish krone', 'DKK', 'id_id', 1);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (63, 57, 'East Caribbean dollar', 'XCD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (64, 58, 'Dominican peso', 'DOP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (65, 59, 'Algerian dinar', 'DZD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (66, 60, 'United States Dollar', 'USD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (67, 61, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (68, 62, 'Egyptian pound', 'EGP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (69, 63, 'Nakfa', 'ERN', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (70, 64, 'Euro', 'ÃƒÆ', 'id_id', 1);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (71, 65, 'Birr', 'ETB', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (72, 66, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (73, 67, 'Fijian dollar', 'FJD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (74, 68, 'United States Dollar', 'USD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (75, 70, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (76, 71, 'Communaute Financiere Africaine franc', 'XAF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (77, 72, 'East Caribbean dollar', 'XCD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (78, 73, 'Lari', 'GEL', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (79, 74, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (80, 75, 'Cedi', 'GHS', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (81, 76, 'Gibraltar pound', 'GIP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (82, 77, 'Danish krone', 'DKK', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (83, 78, 'Dalasi', 'GMD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (84, 79, 'Guniean Franc', 'GNF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (85, 80, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (86, 81, 'Communaute Financiere Africaine franc', 'XAF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (87, 82, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (88, 83, 'quetzal', 'GTQ', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (89, 83, 'United States Dollar', 'USD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (90, 84, 'United States Dollar', 'USD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (91, 85, 'Communaute Financiere Africaine franc', 'XOF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (92, 86, 'Guyanese dollar', 'GYD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (93, 87, 'Yuan', 'CNY', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (94, 88, 'Lempira', 'NHL', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (95, 89, 'Kuna', 'HRK', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (96, 90, 'Gourde', 'HTG', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (97, 91, 'Forint', 'HUF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (98, 93, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (99, 94, 'new Israeli shekel', 'ILS', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (100, 95, 'Indian rupee', 'INR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (101, 96, 'British Poundsterling', 'GBP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (102, 96, 'United States Dollar', 'USD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (103, 97, 'Iraqi dinar', 'IQD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (104, 98, 'Iranian rial', 'IRR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (105, 99, 'Icelandic krona', 'ISK', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (106, 100, 'Euro', 'ÃƒÆ', 'id_id', 1);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (107, 101, 'Jamaican dollar', 'JMD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (108, 102, 'Jordanian dinar', 'JOD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (109, 103, 'Yen', 'JPY', 'id_id', 1);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (110, 104, 'Kenyan shilling', 'KES', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (111, 105, 'Kyrgyzstani som', 'KGS', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (112, 106, 'Riel', 'KHR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (113, 107, 'Australian dollar', 'AUD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (114, 108, 'Comoran franc', 'KMF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (115, 109, 'North Korean won', 'KPW', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (116, 110, 'South Korean won', 'KRW', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (117, 111, 'Kuwaiti dinar', 'KWD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (118, 112, 'Caymanian dollar', 'KYD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (119, 113, 'Tenge', 'KZT', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (120, 114, 'Kip', 'LAK', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (121, 115, 'Lebanese pound', 'LBP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (122, 116, 'Swiss franc', 'CHF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (123, 117, 'Sri Lankan rupee', 'LKR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (124, 118, 'Liberian dollar', 'LRD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (125, 119, 'Loti', 'LSL', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (126, 120, 'South African Rand', 'ZAR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (127, 121, 'Litas', 'LTL', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (128, 122, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (129, 123, 'Libyan dinar', 'LYD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (130, 124, 'Moroccan dirham', 'MAD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (131, 125, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (132, 126, 'Moldovan Leu', 'MDL', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (133, 127, 'Ariary', 'MGA', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (134, 128, 'United States Dollar', 'USD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (135, 129, 'Macedonian denar', 'MKD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (136, 130, 'Communaute Financiere Africaine franc', 'XOF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (137, 132, 'togrog/tugrik', 'MNT', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (138, 133, 'Yuan', 'CNY', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (139, 134, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (140, 135, 'ouguiya', 'MRO', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (141, 136, 'East Caribbean dollar', 'XCD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (142, 137, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (143, 138, 'Mauritian rupee', 'MUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (144, 139, 'rufiyaa', 'MVR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (145, 140, 'Malawian kwacha', 'MWK', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (146, 141, 'Mexican peso', 'MXN', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (147, 142, 'Ringgit', 'MYR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (148, 143, 'Metical', 'MZM', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (149, 144, 'Namibian dollar', 'NAD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (150, 144, 'South African Rand', 'ZAR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (151, 145, 'Comptoirs Francais du Pacifique franc', 'XPF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (152, 146, 'Communaute Financiere Africaine franc', 'XOF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (153, 147, 'Australian dollar', 'AUD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (154, 148, 'naira', 'NGN', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (155, 149, 'gold cordoba', 'NIO', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (156, 150, 'Euro', 'EUR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (157, 151, 'Norwegian krone', 'NOK', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (158, 152, 'Nepalese rupee', 'NPR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (159, 153, 'Austrailian Dollar', 'AUD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (160, 154, 'New Zealand dollar', 'NZD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (161, 155, 'New Zealand dollar', 'NZD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (162, 156, 'Omani rial', 'OMR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (163, 157, 'Panama Balboa', 'PAB', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (164, 157, 'United States Dollar', 'USD', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (165, 158, 'nuevo sol', 'PEN', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (166, 159, 'Comptoirs Francais du Pacifique franc', 'XPF', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (167, 160, 'kina', 'PGK', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (168, 161, 'Philippine peso', 'PHP', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (169, 162, 'Pakistani rupee', 'PKR', 'id_id', 0);
				insert into `currency` (`currencyid`, `countryid`, `currencyname`, `symbol`, `i18n`, `recordstatus`) VALUES (170, 163, 'zloty', 'PLN', 'id_id', 0);";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `theme` (
					`themeid` INT(11) NOT NULL AUTO_INCREMENT,
					`themename` VARCHAR(10) NOT NULL,
					`description` TEXT NOT NULL,
					`isadmin` TINYINT(4) NOT NULL DEFAULT '0',
					`createdby` VARCHAR(30) NOT NULL,
					`themeversion` VARCHAR(3) NOT NULL,
					`installdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`themeid`),
					UNIQUE INDEX `uq_theme` (`themename`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `theme` (`themeid`, `themename`, `description`, `isadmin`, `createdby`, `themeversion`, `installdate`, `recordstatus`) VALUES (1, 'bos', 'Bos Theme', 0, 'Prisma Data Abadi', '1.0', '0000-00-00 00:00:00', 1);
				insert into `theme` (`themeid`, `themename`, `description`, `isadmin`, `createdby`, `themeversion`, `installdate`, `recordstatus`) VALUES (2, 'blue', 'Blue Theme', 1, 'Prisma Data Abadi', '1.0', '0000-00-00 00:00:00', 1);";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `modules` (
					`moduleid` INT(11) NOT NULL AUTO_INCREMENT,
					`modulename` VARCHAR(50) NOT NULL,
					`description` VARCHAR(50) NOT NULL,
					`createdby` VARCHAR(20) NOT NULL,
					`moduleversion` VARCHAR(3) NOT NULL DEFAULT '0',
					`installdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`themeid` INT(11) NOT NULL DEFAULT 2,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`moduleid`),
					UNIQUE INDEX `uq_module_name` (`modulename`),
					INDEX `fk_module_theme` (`themeid`),
					CONSTRAINT `fk_module_theme` FOREIGN KEY (`themeid`) REFERENCES `theme` (`themeid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `modules` (`moduleid`, `modulename`, `description`, `createdby`, `moduleversion`, `installdate`, `themeid`, `recordstatus`) VALUES (1, 'admin', 'Administration Module', 'Prisma Data Abadi', '1.0', now(), 2, 1);
				insert into `modules` (`moduleid`, `modulename`, `description`, `createdby`, `moduleversion`, `installdate`, `themeid`, `recordstatus`) VALUES (2, 'media', 'Media Module', 'Prisma Data Abadi', '1.0', now(), 2, 1);";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `modulerelation` (
					`modulerelationid` INT(11) NOT NULL AUTO_INCREMENT,
					`moduleid` INT(11) NOT NULL,
					`relationid` INT(11) NOT NULL,
					PRIMARY KEY (`modulerelationid`),
					INDEX `fk_modulerel_module` (`moduleid`),
					INDEX `fk_modulerel_rel` (`relationid`),
					CONSTRAINT `fk_modulerel_module` FOREIGN KEY (`moduleid`) REFERENCES `modules` (`moduleid`),
					CONSTRAINT `fk_modulerel_rel` FOREIGN KEY (`relationid`) REFERENCES `modules` (`moduleid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `modulerelation` (`modulerelationid`, `moduleid`, `relationid`) VALUES (1, 2, 1);";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `translog` (
					`translogid` INT(11) NOT NULL AUTO_INCREMENT,
					`username` VARCHAR(30) NULL DEFAULT NULL,
					`createddate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`useraction` VARCHAR(20) NULL DEFAULT NULL,
					`newdata` TEXT NULL,
					`menuname` VARCHAR(30) NULL DEFAULT NULL,
					`tableid` INT(11) NULL DEFAULT NULL,
					PRIMARY KEY (`translogid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `groupaccess` (
					`groupaccessid` INT(11) NOT NULL AUTO_INCREMENT,
					`groupname` VARCHAR(30) NOT NULL,
					`description` VARCHAR(50) NOT NULL,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`groupaccessid`),
					UNIQUE INDEX `uq_groupname` (`groupname`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `groupaccess` (`groupaccessid`, `groupname`, `description`, `recordstatus`) VALUES (1, 'administrator', 'Administrator', 1);";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `menuaccess` (
					`menuaccessid` INT(11) NOT NULL AUTO_INCREMENT,
					`menuname` VARCHAR(20) NOT NULL,
					`menutitle` VARCHAR(50) NOT NULL,
					`description` VARCHAR(50) NOT NULL,
					`moduleid` INT(11) NOT NULL,
					`parentid` INT(11) NULL DEFAULT NULL,
					`menuurl` VARCHAR(30) NULL DEFAULT NULL,
					`sortorder` INT(11) NOT NULL DEFAULT '1',
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`menuaccessid`),
					UNIQUE INDEX `uq_menuaccess_menuname` (`menuname`),
					INDEX `fk_menuaccess_module` (`moduleid`),
					CONSTRAINT `fk_menuaccess_module` FOREIGN KEY (`moduleid`) REFERENCES `modules` (`moduleid`) ON DELETE CASCADE 
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (1, 'admin', 'Admin', 'Admin Menu', 1, NULL, null, 1, 1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (2, 'dashboard', 'Dashboard', 'Dashboard Menu', 1, 1, null, 1, 1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (3, 'modules', 'Module', 'Module Menu', 1, 1, 'modules', 2, 1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (4, 'widget', 'Widget', 'Widget Menu', 1, 1, 'widget', 3, 1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (5, 'menuaccess', 'Menu Access', 'Menu Access Menu', 1, 1, 'menuaccess', 4, 1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (6, 'groupaccess', 'Group Access', 'Group Access Menu', 1, 1, 'groupaccess', 5, 1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (7, 'theme', 'Theme', 'Theme Menu', 1, 1, 'theme', 6,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (8, 'language', 'Language', 'Language Menu', 1, 1, 'language', 7,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (9, 'useraccess', 'User Access', 'User Access Menu', 1, 1, 'useraccess', 8,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (10, 'catalogsys', 'Catalog Translation System', 'Catalog Translation System', 1, 1, 'catalogsys', 9,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (11, 'parameter', 'Parameter', 'Parameter', 1, 1, 'parameter', 10,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (12, 'translog', 'Transaction Log', 'Transaction Log', 1, 1, 'translog', 11,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (13, 'country', 'Country', 'Country', 1, 1, 'country', 12,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (14, 'province', 'Province', 'Province', 1, 1, 'province', 13,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (15, 'currency', 'Currency', 'Currency', 1, 1, 'currency', 14,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (16, 'city', 'City', 'City', 1, 1, 'city', 15,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (17, 'company', 'Company', 'Company', 1, 1, 'company', 16,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (18, 'addressbook', 'Address Book', 'Address Book', 1, 1, 'addressbook', 17,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (19, 'menugenerator', 'Menu Generator', 'Menu Generator', 1, 1, 'menugenerator', 18,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (20, 'media', 'Media', 'Media', 2, NULL, null, 2,1);
				insert into `menuaccess` (`menuaccessid`, `menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) VALUES (21, 'mediamgr', 'Media Manager', 'Media Manager', 2, 20, 'mediamgr', 1,1);
			";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `groupmenu` (
					`groupmenuid` INT(11) NOT NULL AUTO_INCREMENT,
					`groupaccessid` INT(11) NOT NULL,
					`menuaccessid` INT(11) NOT NULL,
					`isread` TINYINT(4) NOT NULL DEFAULT '0',
					`iswrite` TINYINT(4) NOT NULL DEFAULT '0',
					`ispost` TINYINT(4) NOT NULL DEFAULT '0',
					`isreject` TINYINT(4) NOT NULL DEFAULT '0',
					`ispurge` TINYINT(4) NOT NULL DEFAULT '0',
					`isupload` TINYINT(4) NOT NULL DEFAULT '0',
					`isdownload` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`groupmenuid`),
					UNIQUE INDEX `uq_groupmenu` (`groupaccessid`, `menuaccessid`),
					INDEX `fk_groupmenu_group` (`groupaccessid`),
					INDEX `fk_groupmenu_menu` (`menuaccessid`),
					CONSTRAINT `fk_groupmenu_menu` FOREIGN KEY (`menuaccessid`) REFERENCES `menuaccess` (`menuaccessid`) ON DELETE CASCADE
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (1, 1, 1, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (2, 1, 2, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (3, 1, 3, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (4, 1, 4, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (5, 1, 5, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (6, 1, 6, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (7, 1, 7, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (8, 1, 8, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (9, 1, 9, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (10, 1, 10, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (11, 1, 11, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (12, 1, 12, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (13, 1, 13, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (14, 1, 14, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (15, 1, 15, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (16, 1, 16, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (17, 1, 17, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (18, 1, 18, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (19, 1, 19, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (20, 1, 20, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (21, 1, 21, 1, 1, 0, 1, 1, 1, 1);
				insert into `groupmenu` (`groupmenuid`, `groupaccessid`, `menuaccessid`, `isread`, `iswrite`, `ispost`, `isreject`, `ispurge`, `isupload`, `isdownload`) VALUES (22, 1, 22, 1, 1, 0, 1, 1, 1, 1);";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `useraccess` (
					`useraccessid` INT(11) NOT NULL AUTO_INCREMENT,
					`username` VARCHAR(30) NOT NULL,
					`realname` VARCHAR(50) NOT NULL,
					`password` VARCHAR(50) NULL DEFAULT NULL,
					`email` VARCHAR(30) NULL DEFAULT NULL,
					`phoneno` VARCHAR(20) NULL DEFAULT NULL,
					`languageid` INT(11) NOT NULL,
					`isonline` TINYINT(4) NOT NULL DEFAULT '0',
					`joindate` DATETIME NOT NULL,
					`authkey` VARCHAR(30) NULL,
					`userphoto` VARCHAR(50) NULL,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`useraccessid`),
					UNIQUE INDEX `uq_username` (`username`),
					UNIQUE INDEX `uq_useremail` (`email`),
					INDEX `fk_user_lang` (`languageid`),
					CONSTRAINT `fk_user_lang` FOREIGN KEY (`languageid`) REFERENCES `language` (`languageid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `useraccess` (`useraccessid`, `username`, `realname`, `password`, `email`, `phoneno`, `languageid`, `isonline`, `joindate`, `userphoto`,`recordstatus`) VALUES (1, 'guest', 'Guest', '', 'guest@prismagrup.com', '', 1, 0, '0000-00-00 00:00:00', 'guest.jpg',1);
				insert into `useraccess` (`useraccessid`, `username`, `realname`, `password`, `email`, `phoneno`, `languageid`, `isonline`, `joindate`, `userphoto`,`recordstatus`) VALUES (2, 'admin', 'Romy Andre', 'e10adc3949ba59abbe56e057f20f883e', 'admin@prismagrup.com', '234234', 1, 1, '0000-00-00 00:00:00', 'admin.png',1);";
			$connection->createCommand($sql)->execute();

			$sql = "
				CREATE TABLE `usergroup` (
					`usergroupid` INT(11) NOT NULL AUTO_INCREMENT,
					`useraccessid` INT(11) NOT NULL,
					`groupaccessid` INT(11) NOT NULL,
					PRIMARY KEY (`usergroupid`),
					UNIQUE INDEX `uq_groupmenu` (`groupaccessid`, `useraccessid`),
					INDEX `fk_usergroup_user` (`useraccessid`),
					INDEX `fk_usergroup_group` (`groupaccessid`),
					CONSTRAINT `fk_usergroup_group` FOREIGN KEY (`groupaccessid`) REFERENCES `groupaccess` (`groupaccessid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;
				";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `usergroup` (`usergroupid`, `useraccessid`, `groupaccessid`) VALUES (1, 2, 1);";
			$connection->createCommand($sql)->execute();

			$sql = "
				CREATE TABLE `widget` (
					`widgetid` INT(11) NOT NULL AUTO_INCREMENT,
					`widgetname` VARCHAR(20) NOT NULL,
					`widgettitle` VARCHAR(40) NOT NULL,
					`widgetversion` VARCHAR(3) NOT NULL DEFAULT '0',
					`widgetby` VARCHAR(20) NOT NULL,
					`description` VARCHAR(30) NOT NULL,
					`widgeturl` VARCHAR(40) NOT NULL,
					`moduleid` INT(11) NULL DEFAULT NULL,
					`installdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`widgetid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				insert into `widget` (`widgetname`, `widgettitle`, `widgetversion`, `widgetby`, `description`, `widgeturl`, `moduleid`, `installdate`, `recordstatus`) VALUES ('usertodo', 'User To Do', '1.0', 'Prisma Data Abadi', 'User To Do', 'admin.components.Usertodo', 1, now(), 1);
				insert into `widget` (`widgetname`, `widgettitle`, `widgetversion`, `widgetby`, `description`, `widgeturl`, `moduleid`, `installdate`, `recordstatus`) VALUES ('userprofile', 'User Profile', '1.0', 'Prisma Data Abadi', 'User Profile', 'admin.components.Userprofile', 1, now(), 1);
				insert into `widget` (`widgetname`, `widgettitle`, `widgetversion`, `widgetby`, `description`, `widgeturl`, `moduleid`, `installdate`, `recordstatus`) VALUES ('useronline', 'User Online', '1.0', 'Prisma Data Abadi', 'User Online', 'admin.components.Useronline', 1, now(), 1);
			";
			$connection->createCommand($sql)->execute();

			$sql = "
				CREATE TABLE `usertodo` (
					`usertodoid` INT(11) NOT NULL AUTO_INCREMENT,
					`username` VARCHAR(50) NULL DEFAULT NULL,
					`tododate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					`menuname` VARCHAR(50) NULL DEFAULT NULL,
					`docno` VARCHAR(200) NULL DEFAULT NULL,
					`description` VARCHAR(400) NULL DEFAULT NULL,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '1',
					PRIMARY KEY (`usertodoid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;
			";
			$connection->createCommand($sql)->execute();

			$sql = "
				CREATE TABLE `userdash` (
					`userdashid` INT(11) NOT NULL AUTO_INCREMENT,
					`groupaccessid` INT(11) NOT NULL,
					`widgetid` INT(11) NOT NULL,
					`menuaccessid` INT(11) NOT NULL,
					`position` TINYINT(4) NOT NULL DEFAULT '0',
					`webformat` VARCHAR(20) NOT NULL DEFAULT '0',
					`dashgroup` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`userdashid`),
					INDEX `fk_userwidget_group` (`groupaccessid`),
					INDEX `fk_userwdget_menu` (`menuaccessid`),
					INDEX `fk_userwidge_widget` (`widgetid`),
					CONSTRAINT `fk_userwdget_menu` FOREIGN KEY (`menuaccessid`) REFERENCES `menuaccess` (`menuaccessid`),
					CONSTRAINT `fk_userwidge_widget` FOREIGN KEY (`widgetid`) REFERENCES `widget` (`widgetid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				replace into userdash (groupaccessid,widgetid,menuaccessid,position,webformat,dashgroup) values (1,1,2,0,'col-md-6',0);
				replace into userdash (groupaccessid,widgetid,menuaccessid,position,webformat,dashgroup) values (1,2,2,1,'col-md-6',0);
			";
			$connection->createCommand($sql)->execute();

			$sql = "
				CREATE TABLE `userinbox` (
					`userinboxid` INT(11) NOT NULL AUTO_INCREMENT,
					`useraccessid` INT(11) NOT NULL,
					`fromuserid` INT(11) NOT NULL,
					`subject` VARCHAR(150) NOT NULL,
					`description` TEXT NOT NULL,
					`senddate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '1',
					PRIMARY KEY (`userinboxid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
				CREATE TABLE `addressbook` (
					`addressbookid` INT(11) NOT NULL AUTO_INCREMENT,
					`fullname` VARCHAR(50) NULL DEFAULT NULL,
					`iscustomer` TINYINT(4) NULL DEFAULT '0',
					`isemployee` TINYINT(4) NULL DEFAULT '0',
					`isvendor` TINYINT(4) NULL DEFAULT '0',
					`ishospital` TINYINT(4) NULL DEFAULT '0',
					`currentlimit` DECIMAL(30,6) NULL DEFAULT '0.0000',
					`currentdebt` DECIMAL(30,6) NULL DEFAULT '0.0000',
					`taxno` VARCHAR(50) NULL DEFAULT NULL,
					`accpiutangid` INT(10) NULL DEFAULT NULL,
					`acchutangid` INT(10) NULL DEFAULT NULL,
					`creditlimit` DECIMAL(30,6) NULL DEFAULT '0.0000',
					`isstrictlimit` TINYINT(4) NULL DEFAULT '0',
					`bankname` VARCHAR(50) NULL DEFAULT NULL,
					`bankaccountno` VARCHAR(50) NULL DEFAULT NULL,
					`accountowner` VARCHAR(50) NULL DEFAULT NULL,
					`salesareaid` INT(11) NULL DEFAULT NULL,
					`pricecategoryid` INT(11) NULL DEFAULT NULL,
					`overdue` INT(11) NULL DEFAULT '60',
					`invoicedate` DATE NULL DEFAULT NULL,
					`logo` VARCHAR(50) NULL DEFAULT NULL,
					`url` VARCHAR(50) NULL DEFAULT NULL,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '1',
					PRIMARY KEY (`addressbookid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `address` (
						`addressid` INT(11) NOT NULL AUTO_INCREMENT,
						`addressbookid` INT(11) NOT NULL,
						`addresstypeid` INT(11) NOT NULL,
						`addressname` VARCHAR(200) NOT NULL,
						`rt` VARCHAR(5) NULL DEFAULT NULL,
						`rw` VARCHAR(5) NULL DEFAULT NULL,
						`cityid` INT(11) NOT NULL,
						`phoneno` VARCHAR(50) NULL DEFAULT NULL,
						`faxno` VARCHAR(50) NULL DEFAULT NULL,
						`lat` FLOAT(10,6) NULL DEFAULT NULL,
						`lng` FLOAT(10,6) NULL DEFAULT NULL,
						PRIMARY KEY (`addressid`),
						INDEX `fk_address_city` (`cityid`),
						CONSTRAINT `fk_address_city` FOREIGN KEY (`cityid`) REFERENCES `city` (`cityid`)
					)
					COLLATE='utf8_general_ci'
					ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `addresscontact` (
						`addresscontactid` INT(11) NOT NULL AUTO_INCREMENT,
						`contacttypeid` INT(11) NOT NULL,
						`addressbookid` INT(11) NOT NULL,
						`addresscontactname` VARCHAR(50) NOT NULL,
						`phoneno` VARCHAR(45) NULL DEFAULT NULL,
						`mobilephone` VARCHAR(45) NULL DEFAULT NULL,
						`emailaddress` VARCHAR(45) NULL DEFAULT NULL,
						PRIMARY KEY (`addresscontactid`)
					)
					COLLATE='utf8_general_ci'
					ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "
					CREATE TABLE `company` (
						`companyid` INT(11) NOT NULL AUTO_INCREMENT,
						`companyname` VARCHAR(50) NULL DEFAULT NULL,
						`companycode` VARCHAR(10) NULL DEFAULT NULL,
						`address` VARCHAR(250) NULL DEFAULT NULL,
						`cityid` INT(11) NULL DEFAULT NULL,
						`zipcode` VARCHAR(10) NULL DEFAULT NULL,
						`taxno` VARCHAR(30) NULL DEFAULT NULL,
						`currencyid` INT(11) NULL DEFAULT NULL,
						`faxno` VARCHAR(50) NULL DEFAULT NULL,
						`phoneno` VARCHAR(50) NULL DEFAULT NULL,
						`webaddress` VARCHAR(100) NULL DEFAULT NULL,
						`email` VARCHAR(100) NULL DEFAULT NULL,
						`leftlogofile` VARCHAR(30) NULL DEFAULT NULL,
						`rightlogofile` VARCHAR(30) NULL DEFAULT NULL,
						`isholding` TINYINT(4) NULL DEFAULT '0',
						`billto` VARCHAR(250) NULL DEFAULT NULL,
						`lat` FLOAT(10,6) NULL DEFAULT NULL,
						`lng` FLOAT(10,6) NULL DEFAULT NULL,
						`filelayout` VARCHAR(50) NULL DEFAULT NULL,
						`recordstatus` TINYINT(4) NOT NULL DEFAULT '1',
						PRIMARY KEY (`companyid`),
						UNIQUE INDEX `uq_company` (`companycode`),
						INDEX `fk_company_city` (`cityid`),
						CONSTRAINT `fk_company_city` FOREIGN KEY (`cityid`) REFERENCES `city` (`cityid`)						
					)
					COLLATE='utf8_general_ci'
					ENGINE=InnoDB;
				";
			$connection->createCommand($sql)->execute();

			$sql = "
			CREATE FUNCTION `BIGTIMEDIFF`(`end_time` VARCHAR(64), `start_time` VARCHAR(64))
				RETURNS int(10)
				LANGUAGE SQL
				DETERMINISTIC
				CONTAINS SQL
				SQL SECURITY DEFINER
				COMMENT ''
			BEGIN
			DECLARE ret_val INT(10);

			SELECT
			DATEDIFF(end_time, start_time) * 24
			+EXTRACT(HOUR FROM end_time)
			- EXTRACT(HOUR FROM start_time)
			INTO ret_val
			;

			RETURN ret_val;
			END";
			$connection->createCommand($sql)->execute();

			$sql = "
			CREATE FUNCTION `GetParamValue`(`vParamName` text)
				RETURNS text CHARSET utf8
				LANGUAGE SQL
				NOT DETERMINISTIC
				CONTAINS SQL
				SQL SECURITY DEFINER
				COMMENT ''
			BEGIN
				declare ret text;
				select paramvalue
				into ret
				from parameter
				where lower(paramname) = lower(vParamName);
				return ret;
			END
			";
			$connection->createCommand($sql)->execute();

			$sql = "
			CREATE FUNCTION `Split_Str`(`x` VARCHAR(255), `delim` VARCHAR(12), `pos` INT
				)
					RETURNS varchar(255) CHARSET utf8
					LANGUAGE SQL
					NOT DETERMINISTIC
					CONTAINS SQL
					SQL SECURITY DEFINER
					COMMENT ''
				BEGIN
				RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
							 LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1),
							 delim, '');
				END
			";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE PROCEDURE `InsertTransLog`(IN `vusername` varchar(50), IN `vuseraction` VARCHAR(50), IN `vnewdata` TEXT,  IN `vmenuname` VARCHAR(50), IN `vtableid` INT)
	LANGUAGE SQL
	NOT DETERMINISTIC
	CONTAINS SQL
	SQL SECURITY DEFINER
	COMMENT ''
BEGIN
	declare vlog int;
	select paramvalue
	into vlog
	from parameter
	where upper(paramname) = 'USINGLOG';

	if vlog = 1 then
		insert into translog (username,useraction,newdata,menuname,tableid)
    value (vusername, vuseraction,vnewdata,vmenuname,vtableid);
	end if;
END";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE PROCEDURE `pRaiseError`(IN `sError` TEXT)
	LANGUAGE SQL
	NOT DETERMINISTIC
	CONTAINS SQL
	SQL SECURITY DEFINER
	COMMENT ''
BEGIN
	declare vnewmessage text;
  select catalogval
  into vnewmessage
  from catalogsys
  where lower(catalogname) = lower(sError) limit 1;

	if vnewmessage is null then
	  set vnewmessage = 'ABCD';
	end if;

SIGNAL SQLSTATE
    '45000'
SET
    MESSAGE_TEXT = vnewmessage;
END";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE PROCEDURE `SendNotif`(IN `vwfname` TEXT, IN `vnextstatus` TINYINT, IN `vmenuname` TEXT, IN `vdocno` TEXT, IN `vdescription` TEXT)
	LANGUAGE SQL
	NOT DETERMINISTIC
	CONTAINS SQL
	SQL SECURITY DEFINER
	COMMENT ''
BEGIN
	declare k int;
	select ifnull(count(1),0)
	into k
	from usertodo
	where menuname = vmenuname and docno = vdocno and description = vdescription;
	
	delete from usertodo
	where menuname = vmenuname and docno = vdocno;

	insert into usertodo (username,menuname,docno,description)
	select d.username,vmenuname,vdocno,vdescription
	from workflow a
	inner join wfgroup b on b.workflowid = a.workflowid
	inner join usergroup c on c.groupaccessid = b.groupaccessid
	inner join useraccess d on d.useraccessid = c.useraccessid
	where lower(a.wfname) = vwfname and b.wfbefstat = vnextstatus;
	
	if getparamvalue('sendnotifsms') = 1 then
		insert into userinbox (username,userfrom,usermessages,userhp)
		select d.username,'capella',concat(vdescription,' ',vdocno),d.phoneno
		from workflow a
		inner join wfgroup b on b.workflowid = a.workflowid
		inner join usergroup c on c.groupaccessid = b.groupaccessid
		inner join useraccess d on d.useraccessid = c.useraccessid
		where lower(a.wfname) = vwfname and b.wfbefstat = vnextstatus;
	end if;
	
	if getparamvalue('sendnotifemail') = 1 then
		insert into userinbox (username,userfrom,usermessages,useremail)
		select d.username,'capella',concat(vdescription,' ',vdocno),d.email
		from workflow a
		inner join wfgroup b on b.workflowid = a.workflowid
		inner join usergroup c on c.groupaccessid = b.groupaccessid
		inner join useraccess d on d.useraccessid = c.useraccessid
		where lower(a.wfname) = vwfname and b.wfbefstat = vnextstatus;
	end if;
END";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE `menugen` (
	`menugenid` INT(11) NOT NULL AUTO_INCREMENT,
	`useraccessid` INT(11) NOT NULL,
	`tablename` VARCHAR(50) NOT NULL,
	`namafield` VARCHAR(50) NOT NULL,
	`defaultvalue` VARCHAR(700) NULL DEFAULT NULL,
	`tipefield` VARCHAR(50) NOT NULL,
	`isview` TINYINT(4) NOT NULL DEFAULT '1',
	`issearch` TINYINT(4) NOT NULL DEFAULT '0',
	`isvalidate` TINYINT(4) NOT NULL DEFAULT '0',
	`isinput` TINYINT(4) NOT NULL DEFAULT '1',
	`isprint` TINYINT(4) NOT NULL DEFAULT '1',
	`query` VARCHAR(250) NOT NULL DEFAULT '1',
	`widgetrelation` VARCHAR(100) NULL DEFAULT NULL,
	`tablerelation` VARCHAR(50) NULL DEFAULT NULL,
	`tablefkname` VARCHAR(50) NULL DEFAULT NULL,
	`relationname` VARCHAR(50) NULL DEFAULT NULL,
	`wirelsource` VARCHAR(50) NULL DEFAULT NULL,
	`popupname` VARCHAR(50) NULL DEFAULT NULL,
	`sortorder` INT(11) NOT NULL DEFAULT '1',
	PRIMARY KEY (`menugenid`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;
";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE PROCEDURE `InsertUserAccess`(
	IN `vactiontype` TINYINT,
	IN `vuseraccessid` INT,
	IN `vusername` VARCHAR(50),
	IN `vrealname` VARCHAR(50),
	IN `vpassword` VARCHAR(50),
	IN `vemail` VARCHAR(50),
	IN `vphoneno` VARCHAR(50),
	IN `vlanguageid` INT,
	IN `vuserphoto` VARCHAR(50),
	IN `vrecordstatus` TINYINT,
	IN `vcreatedby` VARCHAR(50)
)
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
	declare k,l int;
	declare vpass varchar(50);
	if (vactiontype = 0) then
	  	insert into useraccess (username,realname,`password`,email,phoneno,languageid,joindate,recordstatus,userphoto)
	  	values (vusername,vrealname,md5(vpassword),vemail,vphoneno,vlanguageid,now(),vrecordstatus,vuserphoto);
		set k = last_insert_id();
		call inserttranslog(vcreatedby,'insert',
			concat('username=',vusername,',realname=',vrealname,',password=',vpassword,',email=',vemail,',phoneno=',vphoneno,',
			languageid=',vlanguageid,',userphoto=',vuserphoto),
			'useraccess',k);
	else
		if (vactiontype = 1) then
		   select `password`
		   into vpass
		   from useraccess
		   where useraccessid = vuseraccessid;
		   if (vpass <> vpassword) then 
		   	set vpassword = md5(vpassword);	
		   end if;
	  		update useraccess
	  		set userphoto = vuserphoto,username = vusername,realname = vrealname,password = vpassword,email = vemail,phoneno=vphoneno,languageid=vlanguageid, recordstatus=vrecordstatus
	  		where useraccessid = vuseraccessid;
			set k = vuseraccessid;
			call inserttranslog(vcreatedby,'update',
				concat('username=',vusername,',realname=',vrealname,',password=',vpassword,',email=',vemail,',phoneno=',vphoneno,',
				languageid=',vlanguageid,',userphoto=',vuserphoto),
				'useraccess',k);
		end if;
	end if;
	update usergroup
	set useraccessid = k
	where useraccessid = vuseraccessid;
	
	select ifnull(count(1),0)
	into l
	from usergroup
	where useraccessid = k;
	
	if (l = 0) then
		insert into usertodo (username,tododate,menuname,docno,description)
		select a.username,now(),'useraccess',k,(select catalogval from catalogsys where languageid = (select paramvalue from parameter where paramname = 'language') and catalogname = 'taskusergroupempty')
		from useraccess a 
		join usergroup b on b.useraccessid = a.useraccessid
		join groupaccess c on c.groupaccessid = b.groupaccessid
		where c.groupname = 'administrator';
	else
		delete from usertodo
		where docno = k and menuname = 'useraccess' and username in (
			select a.username
			from useraccess a 
			join usergroup b on b.useraccessid = a.useraccessid
			join groupaccess c on c.groupaccessid = b.groupaccessid
			where c.groupname = 'administrator'
		);
	end if;
END";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE PROCEDURE `InsertGroupAccess`(
	IN `vactiontype` TINYINT,
	IN `vgroupaccessid` INT,
	IN `vgroupname` VARCHAR(50),
	IN `vdescription` VARCHAR(50),
	IN `vrecordstatus` TINYINT,
	IN `vcreatedby` VARCHAR(50)
)
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
	declare k int;
	if (vactiontype = 0) then
	  	insert into groupaccess (groupname,description,recordstatus)
	  	values (vgroupname,vdescription,vrecordstatus);
		set k = last_insert_id();
		call inserttranslog(vcreatedby,'insert',
			concat('groupname=',vgroupname,',description=',vdescription),
			'groupaccess',k);
	else
		if (vactiontype = 1) then
	  		update useraccess
	  		set groupname = vgroupname,description = vdescription,recordstatus = vrecordstatus
	  		where useraccessid = vuseraccessid;
			set k = vgroupaccessid;
			call inserttranslog(vcreatedby,'update',
				concat('groupname=',vusername,',description=',vdescription),
				'groupaccess',k);
		end if;
	end if;
	update groupmenu
	set groupaccessid = k
	where groupaccessid = vgroupaccessid;
	update userdash
	set groupaccessid = k
	where groupaccessid = vgroupaccessid;
END";
			$connection->createCommand($sql)->execute();
			$transaction->commit();
			GetMessage('success', 'Proses Instalasi Selesai');
		} catch (CException $e) {
			$transaction->rollBack();
			GetMessage('failure', 'Proses Instalasi Gagal '.$e->getMessage());
		}
	}
}