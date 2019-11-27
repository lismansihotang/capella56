<script>
	function install() {
		var x;
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('install/default/save') ?>',
			'data': {
				'sitename': $("input[name='sitename']").val(),
				'sitetitle': $("input[name='sitetitle']").val(),
				'tagline': $("input[name='tagline']").val(),
				'email': $("input[name='email']").val(),
				'dateformat': $("input[name='dateformat']").val(),
				'timeformat': $("input[name='timeformat']").val(),
				'datetimeformat': $("input[name='datetimeformat']").val(),
				'weekstartson': $("input[name='weekstartson']").val(),
				'defaultpagesize': $("input[name='defaultpagesize']").val(),
				'decimalseparator': $("input[name='decimalseparator']").val(),
				'groupseparator': $("input[name='groupseparator']").val(),
				'defaultnumberqty': $("input[name='defaultnumberqty']").val(),
				'defaultnumberprice': $("input[name='defaultnumberprice']").val(),
				'smtpserver': $("input[name='smtpserver']").val(),
				'smtpport': $("input[name='smtpport']").val(),
				'fromemail': $("input[name='fromemail']").val(),
				'reportengine': $("input[name='reportengine']").val(),
				'dbserver': $("input[name='dbserver']").val(),
				'dbport': $("input[name='dbport']").val(),
				'dbname': $("input[name='dbname']").val(),
				'dbuser': $("input[name='dbuser']").val(),
				'dbpass': $("input[name='dbpass']").val(),
			},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status == "success") {
					$('#rootwizard').find("a[href*='tab6']").trigger('click');
				} else {
					$('#rootwizard').find("a[href*='tab5']").trigger('click');
				}
				alert(data.msg);
			},
			'cache': false});
		return false;
	}
	$(function () {
		$('#rootwizard').bootstrapWizard({onTabShow: function (tab, navigation, index) {
				var $total = navigation.find('li').length;
				var $current = index + 1;
				var $percent = ($current / $total) * 100;
				$('#wizprogress').css({width: $percent + '%'});
				$('#wizprogress').attr({'aria-valuenow': $percent});
				$('#wizprogress').html(Math.round($percent) + '%');

				// If it's the last tab then hide the last button and show the finish instead
				if ($current >= $total) {
					$('#rootwizard').find('.pager .next').hide();
					$('#rootwizard').find('.pager .finish').show();
					$('#rootwizard').find('.pager .finish').removeClass('disabled');
				} else {
					$('#rootwizard').find('.pager .next').show();
					$('#rootwizard').find('.pager .finish').hide();
				}
			},
		});
		$('#rootwizard .finish').click(function () {
			alert('Instalasi sudah selesai, silahkan menggunakan software Capella CMS');
			location.reload();
		});
	});
</script>
<div id="rootwizard">
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container">
				<ul>
					<li><a href="#tab1" data-toggle="tab">Perkenalan</a></li>
					<li><a href="#tab2" data-toggle="tab">Konfigurasi</a></li>
					<li><a href="#tab3" data-toggle="tab">Setting Database</a></li>
					<li><a href="#tab4" data-toggle="tab">Setting Site</a></li>
					<li><a href="#tab5" data-toggle="tab">Instalasi</a></li>
					<li><a href="#tab6" data-toggle="tab">Selesai</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container">
    <div class="progress">
			<div id="wizprogress" class="progress-bar progress-bar-striped active" role="progressbar"
					 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
				0%
			</div>
		</div>
	</div>
	<div class="tab-content">
		<div class="tab-pane" id="tab1">
			<div class="container">
				<h1>Selamat Datang di Capella CMS</h1>
				<p>Modul ini akan melakukan instalasi langkah demi langkah ke Server</p> 
				<div id="myCarousel" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
						<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
						<li data-target="#myCarousel" data-slide-to="1"></li>
						<li data-target="#myCarousel" data-slide-to="2"></li>
						<li data-target="#myCarousel" data-slide-to="3"></li>
					</ol>

					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						<div class="item active">
							<img src="<?php echo Yii::app()->baseUrl.'/images/install/cms1.png' ?>" alt="Front End">
							<div class="carousel-caption">
								<h3>Front End</h3>
								<p>Capella CMS menggunakan konsep CMS Content Management System, didukung oleh Yii Framework, Bootstrap</p>
							</div>
						</div>

						<div class="item">
							<img src="<?php echo Yii::app()->baseUrl.'/images/install/cms2-4.png' ?>" alt="List Modules">
							<div class="carousel-caption">
								<h3>Daftar Modules</h3>
								<p>Capella CMS, di web http://www.prismagrup.com, menyediakan berbagai jenis modules yang dapat dipergunakan</p>
							</div>
						</div>

						<div class="item">
							<img src="<?php echo Yii::app()->baseUrl.'/images/install/cms3.png' ?>" alt="Built With">
							<div class="carousel-caption">
								<h3>Built With</h3>
								<p>Capella CMS, berdasarkan test builtwith.com, dengan trik tertentu, terbaca menggunakan Joomla dan Wordpress sekaligus</p>
							</div>
						</div>

						<div class="item">
							<img src="<?php echo Yii::app()->baseUrl.'/images/install/cms1516.png' ?>" alt="User Otorisasi dan User Profile">
							<div class="carousel-caption">
								<h3>User Profile dan User Otorisasi</h3>
								<p>Capella CMS memiliki User Profile dan User Otorisasi setara dengan software ERP SAP, password terenkripsi, mendukung hingga object data</p>
							</div>
						</div>

						<div class="item">
							<img src="<?php echo Yii::app()->baseUrl.'/images/install/cms5.png' ?>" alt="User Profile">
							<div class="carousel-caption">
								<h3>User Profile dan User Otorisasi</h3>
								<p>Capella CMS memiliki User Profile dan User Otorisasi setara dengan software ERP SAP, password terenkripsi, mendukung hingga object data</p>
							</div>
						</div>

						<div class="item">
							<img src="<?php echo Yii::app()->baseUrl.'/images/install/cms7.png' ?>" alt="Theme">
							<div class="carousel-caption">
								<h3>Theme</h3>
								<p>Capella CMS memiliki User Profile dan User Otorisasi setara dengan software ERP SAP, password terenkripsi, mendukung hingga object data</p>
							</div>
						</div>

					</div>

					<!-- Left and right controls -->
					<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="tab2">
			<div class="container">
				<div class="jumbotron">
					<h1>File Config</h1>
					<p>Lakukan edit di file protected/config/main.php, edit bagian db, sesuaikan dengan database anda<br/>
						misalkan : <br/>'db'=>array(<br/>
						'connectionString' => 'mysql:host=localhost;dbname=capellacms',<br/>
						'emulatePrepare' => true,<br/>
						'username' => 'capellacms',<br/>
						'password' => 'capellacms',<br/>
						'charset' => 'utf8',<br/>
						'initSQLs'=>array('set names utf8'),<br/>
						'schemaCachingDuration' => 3600,<br/>
						),</p>
				</div>
				<?php
				$this->widget('ext.elFinder.ElFinderWidget',
					array(
					'connectorRoute' => 'install/default/connector',
					)
				);
				?>
			</div>
		</div>
		<div class="tab-pane" id="tab3">
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label class="control-label col-sm-2" for="dbserver">Database Server</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="dbserver" value="localhost" placeholder="Database Server">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="dbport">Database Port</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="dbport" value="3306" placeholder="Database Port">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="dbname">Database Name</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="dbname" value="cms" placeholder="Database Name">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="dbuser">Database User</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="dbuser" value="root" placeholder="Database User">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="dbpass">Database Password</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="dbpass" value="123456" placeholder="Database Password">
					</div>
				</div>
			</form>
		</div>
		<div class="tab-pane" id="tab4">
			<form class="form-horizontal" role="form">
				<div class="form-group">
					<label class="control-label col-sm-2" for="sitename">Site Name</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="sitename" value="Capella" placeholder="Site Name">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="sitetitle">Site Title</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="sitetitle" value="Capella CMS" placeholder="Site Title">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="tagline">Tag Line</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="tagline" value="Your Total Solution" placeholder="Tag Line">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="email">Email</label>
					<div class="col-sm-8">
						<input type="email" class="form-control" name="email" value="system@capellacms.com" placeholder="Email">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="dateformat">Date Format</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="dateformat" value="d/m/Y" placeholder="Date Format">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="timeformat">Time Format</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="timeformat" value="H:i:s" placeholder="Time Format">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="datetimeformat">Date Time Format</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="datetimeformat" value="d/m/Y H:i:s">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="weekstartson">Week Starts On</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="weekstartson" value="Monday" placeholder="Week Starts On">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="defaultpagesize">Default Page Size</label>
					<div class="col-sm-8">
						<input type="number" class="form-control" name="defaultpagesize" value="10">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="decimalseparator">Decimal Separator</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="decimalseparator" value=",">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="groupseparator">Group Separator</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="groupseparator" value=".">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="defaultnumberqty">Decimal for Qty</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="defaultnumberqty" value="4">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="defaultnumberprice">Decimal for Price</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="defaultnumberprice" value="2">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="smtpserver">SMTP Server</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="smtpserver" value="localhost" placeholder="SMTP Server">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="smtpport">SMTP Port</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="smtpport" value="25" placeholder="SMTP Port">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="fromemail">From Email Notification</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="fromemail" value="admin@capellacms.com" placeholder="Email Notification">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="reportengine">Report Engine</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="reportengine" value="pdf" placeholder="Report Engine">
					</div>
				</div>
			</form>
		</div>
		<div class="tab-pane" id="tab5">
			<div class="container">
				<div class="jumbotron">
					<h1>Hampir Selesai .... </h1> 
					<p>Klik tombol Install untuk melakukan instalasi</p> 
				</div>
				<a class="btn btn-info" href="#" onclick="install()">Install</a>
			</div>
		</div>
		<div class="tab-pane" id="tab6">
			<div class="container">
				<div class="jumbotron">
					<h1>Selesai .... </h1> 
					<p>Setelah instalasi selesai, <br/>
						1. buka file protected/config/main.php, setting di bagian params untuk data install menjadi false<br/>
						'params'=>array(<br/>
						'install'=>false<br/>
						),<br/>
						2. jalankan aplikasi anda, misalkan : http://localhost/capellacms, <br/>
						login dengan user: admin, pass: 123456, masuk ke administrator module, <br/>
						dan lakukan install module lain via menu Modules<br/><br/>
						Jika sudah selesai, klik tombol finish</p> 
				</div>
				<?php
				$this->widget('ext.elFinder.ElFinderWidget',
					array(
					'connectorRoute' => 'install/default/connector',
					)
				);
				?>
			</div>
		</div>
		<ul class="pager wizard">
			<li class="previous first" style="display:none;"><a href="javascript:;">First</a></li>
			<li class="previous"><a href="javascript:;">Previous</a></li>
			<li class="next last" style="display:none;"><a href="javascript:;">Last</a></li>
			<li class="next"><a href="javascript:;">Next</a></li>
			<li class="next finish" style="display:none;"><a href="javascript:;">Finish</a></li>
		</ul>
	</div>
</div>