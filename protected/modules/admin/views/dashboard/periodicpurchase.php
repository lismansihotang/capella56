<?php
	$sql = "
		select distinct z.companyid,z.companyname
		from company z 
		where z.companyid in (".GetUserObjectValues('company').") order by z.companyid asc 
	";
	$cmd = Yii::app()->db->createCommand($sql)->queryAll();
	foreach ($cmd as $datacom) {
		$sql = "
			select distinct a.plantid,a.plantcode
			from plant a 
			where a.plantid in (".GetUserObjectValues('plant').") and a.companyid = ".$datacom['companyid']." order by plantid asc";
		$cmd = Yii::app()->db->createCommand($sql)->queryAll();
		$totaljan=0;$totalfeb=0;$totalmar=0;$totalapr=0;$totalmei=0;$totaljun=0;$totaljul=0;$totalagt=0;$totalsep=0;$totalokt=0;$totalnov=0;
		$totaldes=0;
		$totaljan1=0;$totalfeb1=0;$totalmar1=0;$totalapr1=0;$totalmei1=0;$totaljun1=0;$totaljul1=0;$totalagt1=0;$totalsep1=0;$totalokt1=0;
		$totalnov1=0;$totaldes1=0;
		foreach ($cmd as $datas) {
			$data=array();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 1 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$jan = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 2 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$feb = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 3 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$mar = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 4 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$apr = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 5 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$mei = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 6 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$jun = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 7 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$jul = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 8 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$agt = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 9 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$sep = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 10 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$okt = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 11 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$nov = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 12 AND YEAR(z.delvdate) = YEAR(NOW())
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$des = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 1 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$jan1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 2 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$feb1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 3 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$mar1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 4 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$apr1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 5 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$mei1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 6 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$jun1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 7 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$jul1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 8 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$agt1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 9 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$sep1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 10 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$okt1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 11 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$nov1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$sql = "
				SELECT ifnull(sum(jumlah)/1000,0) AS jan
				FROM 
				(
				SELECT a.sono,getamountbyso(a.soheaderid) AS jumlah
				FROM soheader a
				WHERE a.sono IS NOT NULL
				AND a.recordstatus >= 3
				AND a.soheaderid IN
				(
				SELECT distinct z.soheaderid 
				FROM sodetail z
				WHERE month(z.delvdate) = 12 AND YEAR(z.delvdate) = YEAR(NOW())-1
				)
				AND a.plantid = ".$datas['plantid']."
				) z
			";
			$des1 = Yii::app()->db->createCommand($sql)->queryScalar();
			$totaljan += $jan;
			$totalfeb += $feb;
			$totalmar += $mar;
			$totalapr += $apr;
			$totalmei += $mei;
			$totaljun += $jun;
			$totaljul += $jul;
			$totalagt += $agt;
			$totalsep += $sep;
			$totalokt += $okt;
			$totalnov += $nov;
			$totaldes += $des;
		?>
	<script type="text/javascript">
	$(function () { 
		var myPeriodicSales<?php echo $datas['plantcode']?> = Highcharts.chart('periodicsales<?php echo $datas['plantcode']?>', {
			chart: {
					type: 'area'
			},
			title: {
					text: 'Infografis Order Secara Periodik (Basis Tgl Delivery)'
			},
			subtitle: {
					text: 'Tahun Berjalan vs Tahun Sebelumnya - <?php echo $datas['plantcode']?>'
			},
			xAxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
				tickmarkPlacement: 'on',
				title: {
						enabled: false
				}
			},
			yAxis: {
				title: {
						text: '(ribuan) Rupiah'
				},
				labels: {
						formatter: function () {
								return this.value;
						}
				}
			},
			tooltip: {
				split: true,
				valuePrefix: 'Rp '
			},
			plotOptions: {
				area: {
						stacking: 'normal',
						lineColor: '#666666',
						lineWidth: 1,
						marker: {
								lineWidth: 1,
								lineColor: '#666666'
						},
						dataLabels: {
                enabled: true
            },
				}
			},
			series: 
			[
			{
				name: 'Tahun Berjalan',
				data: [<?php echo $jan.','.$feb.','.$mar.','.$apr.','.$mei.','.$jun.','.$jul.','.$agt.','.$sep.','.$okt.','.$nov.','.$des;?>] 
			},
			{
				name: 'Tahun Lalu',
				data: [<?php echo $jan1.','.$feb1.','.$mar1.','.$apr1.','.$mei1.','.$jun1.','.$jul1.','.$agt1.','.$sep1.','.$okt1.','.$nov1.','.$des1;?>] 
			}]
		});
	});
	</script>
		<div id="periodicsales<?php echo $datas['plantcode']?>" style="width:100%; height:450px;"></div>
	<?php }?>
	<script type="text/javascript">
	$(function () { 
		var myPeriodicSalesCom<?php echo $datacom['companyid']?> = Highcharts.chart('periodicsalescom<?php echo $datacom['companyid']?>', {
			chart: {
					type: 'area'
			},
			title: {
					text: 'Infografis Order Secara Periodik (Basis Tgl Delivery)'
			},
			subtitle: {
					text: 'Tahun Berjalan vs Tahun Sebelumnya - <?php echo $datacom['companyname']?>'
			},
			xAxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
				tickmarkPlacement: 'on',
				title: {
						enabled: false
				}
			},
			yAxis: {
				title: {
						text: '(ribuan) Rupiah'
				},
				labels: {
						formatter: function () {
								return this.value;
						}
				}
			},
			tooltip: {
				split: true,
				valuePrefix: 'Rp '
			},
			plotOptions: {
				area: {
						stacking: 'normal',
						lineColor: '#666666',
						lineWidth: 1,
						marker: {
								lineWidth: 1,
								lineColor: '#666666'
						}
				}
			},
			series: [{
				name: 'Tahun Berjalan',
				data: [<?php echo $totaljan.','.$feb.','.$mar.','.$apr.','.$mei.','.$jun.','.$jul.','.$agt.','.$sep.','.$okt.','.$nov.','.$des;?>] 
			},{
				name: 'Tahun Lalu',
				data: [<?php echo $jan1.','.$feb1.','.$mar1.','.$apr1.','.$mei1.','.$jun1.','.$jul1.','.$agt1.','.$sep1.','.$okt1.','.$nov1.','.$des1;?>] 
			}]
		});
	});
	</script>
	<div id="periodicsalescom<?php echo $datacom['companyid']?>" style="width:100%; height:450px;"></div>
	<?php }?>