<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">	
    <meta name="Description" content="Make your business optimize with Capella ERP Indonesia The Best Web ERP Apps">
    <meta name="theme-color" content="#f00">    
    <meta name="msapplication-square310x310logo" content="<?php echo Yii::app()->request->baseUrl;?>/images/launcher-icon-192192.png">
    <link rel="icon" href="<?php echo Yii::app()->request->baseUrl;?>/images/icons/favicon.ico" type="image/x-icon">	
    <link rel="manifest" href="<?php echo Yii::app()->request->baseUrl;?>/manifest.json">
    <link rel="icon" sizes="192x192" href="<?php echo Yii::app()->request->baseUrl;?>/images/launcher-icon-192192.png">
    <link rel="apple-touch-icon" sizes="192x192" href="<?php echo Yii::app()->request->baseUrl;?>/images/launcher-icon-192192.png">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/easyui.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/js/clippy/clippy.css" media="all">    
    <link rel="preconnect" href="https://api.ipify.org">
    <script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl;?>/js/easyui/jquery.easyui.min.all.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl;?>/js/clippy/clippy.min.js"></script>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
  </head>
  <body onload="startup();">
    <script>
      var minwindow = [];
      var openedapp = [];
    </script>
    <div class="loadingScreen">
      <video autoplay loop muted playsinline>
        <source src="<?php echo Yii::app()->request->baseUrl;?>/images/wallpaper/startup.mp4" type="video/mp4">
      </video>
    </div>
    <input type="hidden" id='clientippublic' value=''>
    <input type="hidden" id='clientiplocal' value=''>
    <input type="hidden" id='clientlat' value=''>
    <input type="hidden" id='clientlng' value=''>
    <input type="hidden" id='identityid' value=''>
    <!--Taskbar-->
    <div class="taskbar" id="taskbar">
      <div class="startButton">
        <img alt="Start" draggable="false" src="<?php echo Yii::app()->request->baseUrl;?>/images/icons/windows.png">
      </div>
      <div class="taskbaricon" id="taskbaricon">
      </div>
      <!--Clock and Date-->
      <div class="taskbarRight">
        <div class="taskbarRightIcon" id="clock">
          <div id="time">
            <div id="hour"></div>
            <div id="min"></div>
            <div id="det"></div>
            <div id="ampm"></div>
          </div>
          <div id="date">
            <div id="day"></div>
            <div id="month"></div>
            <div id="year"></div>
          </div>
        </div>
        <div class="taskbarRightIcon" id="tasktilevertical">
          <img alt="Tile Vertical" draggable="false" id="tilevertical" src="<?php echo Yii::app()->request->baseUrl;?>/images/icons/tilevertical.png" style="width:32px;height:32px">
        </div>
        <div class="taskbarRightIcon" id="tasktilehorizontal">
          <img alt="Tile Horizontal" draggable="false" id="tilehorizontal" src="<?php echo Yii::app()->request->baseUrl;?>/images/icons/tilehorizontal.png" style="width:32px;height:32px">
        </div>
        <div class="taskbarRightIcon" id="taskcascadedesktop">
          <img alt="Cascade Desktop" draggable="false" id="cascadedesktop" src="<?php echo Yii::app()->request->baseUrl;?>/images/icons/cascade.png" style="width:32px;height:32px">
        </div>
        <div class="taskbarRightIcon" id="taskrestoredesktop">
          <img alt="Restore Desktop" draggable="false" id="restoredesktop" src="<?php echo Yii::app()->request->baseUrl;?>/images/icons/restoredesktop.png" style="width:32px;height:32px">
        </div>
        <div class="taskbarRightIcon" id="taskshowdesktop">
          <img alt="Show Desktop" draggable="false" id="showdesktop" src="<?php echo Yii::app()->request->baseUrl;?>/images/icons/showdesktop.png" style="width:32px;height:32px">
        </div>
      </div>
    </div> 
    <!-- End Taskbar -->
    <!-- Menu -->
    <div class="cortanaMenu">
      <div class="cortanaSidebar">
        <div class="cortanaSidebarButton" id="1csb">
          <img alt="Menu" src="<?php echo Yii::app()->request->baseUrl;?>/images/icons/menu.png">
        </div>
      </div>
    </div>     
    <!-- Content Start Menu -->
    <div class="startMenu">
      <div class="sidebar">
        <div class="sidebarTop">
          <div class="sidebarButton" id="profile">
            <img alt="Profile" src="<?php echo Yii::app()->request->baseUrl;?>/images/icons/profile.png">
          <p><?php echo Yii::app()->user->getRealName()?></p>
        </div>
        </div>
        <div class="sidebarBottom">
          <div class="sidebarButton" id="logout">
            <img alt="Logout" src="<?php echo Yii::app()->request->baseUrl;?>/images/icons/power.png">
            <p>Logout</p>
          </div>
        </div>
      </div>          
      <div class="appview">
        <?php	$menus = getItems();
        foreach($menus AS $menu) {?>
        <div class="appSection">
          <p><?php echo $menu['label']?></p>
          <div class="appHolder">
            <?php
            $submenus = getSubmenu($menu['parentid']);
            foreach ($submenus as $submenu) {?>
            <a id="link<?php echo $submenu['name']?>" href="#" onclick="openapp('<?php echo $submenu['name']?>','<?php echo $submenu['url']?>',
            '<?php echo $submenu['label']?>','icon-<?php echo $submenu['name']?>')"><li class="app"><img alt="<?php echo $submenu['label']?>" src="<?php echo Yii::app()->request->baseUrl.'/images/icons/'.$submenu['icon']?>"><br><br><span class="labelmenu"><?php echo $submenu['label'] ?></span></li></a>
            <?php }?>
          </div>
        </div>
        <?php }?>                    
      </div>
    </div>
    <!-- End of Content Start Menu -->
    <!-- End of Menu -->

    <!-- Shortcut Menu -->
    <div class="desktop" id="desktop">
      <div class="shortcutShell">
      <?php
      $menus = getUserFavs();
      foreach($menus AS $menu) {  ?>
        <div class="shortcut" id="<?php echo $menu['name'] ?>shortcut">
          <a href="#" onclick="openapp('<?php echo $menu['name']?>','<?php echo $menu['url']?>',
            '<?php echo $menu['label']?>','icon-<?php echo $menu['name']?>')">
            <img alt="<?php echo $menu['label']?>" src="<?php echo Yii::app()->request->baseUrl."/images/icons/".$menu['icon'];?>">
            <p><?php echo $menu['label']?></p>
          </a>
        </div>
      <?php }?>
      </div>
    </div>
    <!-- End of Shortcut Menu -->
    
    <div class="widgets">
      <?php  $widgets = getDashboard();
      foreach($widgets AS $widget) { ?>
        <div class="widget">
          <div id="<?php echo $widget['widgetname']?>" style="padding:5px;" title="<?php echo $widget['widgettitle']?>" class="easyui-panel"
            data-options="
              border: false,
              noheader:false,
              width: '<?php echo $widget['width'] ?>',    
              href: '<?php echo Yii::app()->createUrl('admin/dashboard/'.$widget['widgetname'])?>'
            ">
          </div>
        </div>
      <?php  }?>
    </div>

    <script>
      function startup(){
        $(".startMenu, .cortanaMenu").hide();
        getColor();
        getBackground();
      }            
      function getColor(){
        var getColor = localStorage.getItem("getColor")
        if (getColor!="no"){
          $('.app, .wideapp').addClass("colorScheme");
          $(".colorScheme, .previewApp, .previewApp2").css("background-color",getColor);
        }
      }        
      function getBackground(){
        $("body").css("background-image", "url(<?php echo Yii::app()->request->baseUrl;?>/images/wallpaper/<?php echo Yii::app()->user->getWallpaper()?>)"); 
      }
      function openapp(appname,urlapp,labelapp,iconapp) {
        $(".startMenu, .cortanaMenu").hide();
        jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('site/getcode')?>',
          'type':'post',
          'dataType':'json',
          'data':{
            'menuname':appname,
          },
          'success':function(data)
          {
            if (data.isError == 0) {
              openedapp.push(appname);
              var desktop = document.getElementById("desktop");
              var newapp = document.createElement("div");
              newapp.id = appname+"app";
              desktop.appendChild(newapp);
              $("#"+appname+"app").window({
                width:'600px',
                height:'auto',
                inline:true,
                iconCls:iconapp,
                collapsible:false,
                shadow:false,
                doSize:true,
                top:'10px',
                inline:true,
                openAnimation:'fade',
                closeAnimation:'fade',
                href:urlapp,
                //constrain:true,
                title: labelapp,
                onOpen: function(){

                },
                onMinimize: function() {
                  minwindow.push(appname);
                },
                onClose:function() {
                  try {
                    $('#'+appname+"icon").remove();
                    for( var i = 0; i < openedapp.length; i++){ 
                      if ( openedapp[i] === appname) {
                        delete openedapp[i];
                      }
                    }
                    for( var i = 0; i < minwindow.length; i++){ 
                      if ( minwindow[i] === appname) {
                        delete minwindow[i];
                      }
                    }
                  } 
                  catch(err) {}
                },
              });          
              var ww = $('#'+appname+"app");
              ww.window('resize',{
                width: '50%',
                height: '400px',
              });   
              ww.window('center');
              var icon = document.getElementById("taskbaricon");
              var newnode = document.createElement("div");
              newnode.id=appname+"icon";
              newnode.className="taskbarIcon "+iconapp;
              newnode.setAttribute("onclick", "bringtofrontapp('"+appname+"')");
              icon.appendChild(newnode);	
            }
            else {
              show('Pesan',data.msg,data.isError);
            }
          }, 
          'cache':false});
      }
      function menuHandler(item){
        alert(item.name);
      }
      function bringtofrontapp(appname) {
        var bols = false;
        for( var i = 0; i < minwindow.length; i++){ 
          if ( minwindow[i] === appname) {
            bols = true;
            minwindow.splice(i, 1); 
            i--;
          }
        }
        if (bols === false) {
          $("#"+appname+"app").window('minimize');
          minwindow.push(appname);
        } else {
          $("#"+appname+"app").window('open');
        }
      }
      $(document).on('click', 'div.window', function(){
        $(this).children('div.window-body').window('open')
      })
      $(document).on('click', 'div.datagrid', function(){
        $(this).children('div.datagrid-body').window('open')
      })
      $(".startButton").click(function(){
        $(".startMenu").fadeToggle(100);
        $(".cortanaMenu").fadeOut();
      });
      $(".desktop").click(function(){
        $(".startMenu, .cortanaMenu").fadeOut();
      });
      // Controller for the Date and Time
      function starttime() {
        var month = new Date().getMonth();
        month+=1;
        var day = new Date().getDate();
        var year = new Date().getFullYear();
        var minutes = new Date().getMinutes();
        var detik = new Date().getSeconds();
        var hours = new Date().getHours();
        if (hours == 0){
            hours+=12;
        }
        var ampm = " AM";
        if (hours > 12){
            hours-=12;
            ampm="PM";
        }
        document.getElementById("hour").innerHTML ="<p>"+hours+":</p>";
        
        if (minutes < 10){
          document.getElementById("min").innerHTML = "<p>0"+minutes+":</p>";
        }
        else{
          document.getElementById("min").innerHTML = "<p>"+minutes+":</p>";
        }

        if (detik < 10){
          document.getElementById("det").innerHTML = "<p>0"+detik+"</p>";
        }
        else{
          document.getElementById("det").innerHTML = "<p>"+detik+"</p>";
        }
        document.getElementById("ampm").innerHTML = "<p>"+ampm+"</p>";
        document.getElementById("month").innerHTML = "<p>"+month+"/</p>";
        document.getElementById("day").innerHTML = "<p>"+day+"/</p>";
        document.getElementById("year").innerHTML = "<p>"+year+"</p>";
        var t = setTimeout(starttime, 500);
      }
      $(document).ready(function(){
        $(".loadingScreen").fadeOut();
        $(".startMenu, .cortanaMenu").fadeOut();
        jQuery.ajax({'url':'https://api.ipify.org?format=jsonp&callback=?',
          'type':'post','dataType':'json',
          'success':function(data)
          {
              $('#clientippublic').val(data.ip);	
          } ,
          'cache':false});
        getLocation();
        document.getElementById('logout').addEventListener('click', function() {
          window.location.href = '<?php echo Yii::app()->createurl("site/logout")?>';
        });
        document.getElementById('showdesktop').addEventListener('click', function() {
          showdesktop();
        });
        document.getElementById('restoredesktop').addEventListener('click', function() {
          restoredesktop();
        });
        document.getElementById('cascadedesktop').addEventListener('click', function() {
          cascadedesktop();
        });
        document.getElementById('tilevertical').addEventListener('click', function() {
          tilevertical();
        });
        document.getElementById('tilehorizontal').addEventListener('click', function() {
          tilehorizontal();
        });
        $(".searchbar").click(function(){
          $(".cortanaMenu").fadeToggle("fast");
          $(".startMenu").fadeOut("fast");
        });
        if ('ontouchstart' in document.documentElement) {
          document.addEventListener('touchstart', onTouchStart, {passive: true});
        }
        starttime();
        clippy.load('Links', function(agent) {
          agent.show();
        });
      });
      function openloader() {
        $('#loaderwindow').window('open');
      }
      function closeloader() {
        $('#loaderwindow').window('close');
      }
      function show($title,$msg,$isError="0") {
        var $type = "info";
        if ($isError == "0") {
          $type = "info";
        } else 
        if ($isError == "1") {
          $type = "error";
        }
        $.messager.show({
          title:$title,
          msg:'<div class="messager-icon messager-'+$type+'"></div><div>'+$msg+'</div>',
          showType:'slide',
          timeOut:5000,
          height:'auto',
          width:'auto',
          style:{
            right:'',
            top:document.body.scrollTop+document.documentElement.scrollTop,
            bottom:'',
            zIndex:999999,
          }
        });
      }
      function showdesktop(){
        for(var i=0; i<openedapp.length; i++){
          var appname = openedapp[i];
          $("#"+appname+"app").window('minimize');
        }
      }
      function restoredesktop(){
        for(var i=0; i<openedapp.length; i++){
          var appname = openedapp[i];
          $("#"+appname+"app").window('open');
        }
      }
      function cascadedesktop(){
        var lefts = 50;
        var tops = 50;
        for(var i=0; i<openedapp.length; i++){
          lefts = lefts + 30;
          tops = tops + 40;
          var appname = openedapp[i];
          $("#"+appname+"app").window('move',{
            left: lefts,
            top: tops,
          });
        }
      }
      function tilevertical(){
        var heightavg = (document.documentElement.clientHeight - document.getElementById("taskbar").offsetHeight) / openedapp.length;
        var tops = 0;
        var lefts = 0;
        for(var i=0; i<openedapp.length; i++){
          var appname = openedapp[i];
          $("#"+appname+"app").window('move',{
            left: 0,
            top: tops
          });
          $("#"+appname+"app").window('resize',{
            width: '100%',
            height: heightavg,
          });
          tops = tops + heightavg;
        }
      }
      function tilehorizontal(){
        var widthavg = (document.documentElement.clientWidth) / openedapp.length;
        var heightavg = (document.documentElement.clientHeight - document.getElementById("taskbar").offsetHeight);
        var lefts = 0;
        for(var i=0; i<openedapp.length; i++){
          var appname = openedapp[i];
          $("#"+appname+"app").window('move',{
            left: lefts,
            top: 0
          });
          $("#"+appname+"app").window('resize',{
            height: heightavg,
            width: widthavg,
          });
          lefts = lefts + widthavg;
        }
      }
      $.fn.numberbox.defaults.parser = function(s) {
        s = s + '';
        var opts = $(this).numberbox('options');
        if (parseFloat(s) != s) {
          if (opts.groupSeparator) s = s.replace(new RegExp('\\'+opts.groupSeparator,'g'), '');
          if (opts.decimalSeparator) s = s.replace(new RegExp('\\'+opts.decimalSeparator,'g'), '.');
          if (opts.prefix) s = s.replace(new RegExp('\\'+$.trim(opts.prefix),'g'), '');
          if (opts.suffix) s = s.replace(new RegExp('\\'+$.trim(opts.suffix),'g'), '');
          s = s.replace(/\s/g,'');
        }
        var val = parseFloat(s).toFixed(opts.precision);
        if (isNaN(val)) {
          val = '';
        } else if (typeof(opts.min) == 'number' && val < opts.min) {
          val = opts.min.toFixed(opts.precision);
        } else if (typeof(opts.max) == 'number' && val > opts.max) {
          val = opts.max.toFixed(opts.precision);
        }
        return val;
      };
      function formatnumber(symbol,value,othertext='') {
        if (value != undefined) {
          var ardata = value.split(",");
          if (ardata == undefined) {
            ardata = value.split(".");
          }
          s = '<div align=\"right\" style=\"float:right;display:flex\">'+symbol+' '+ardata[0]+'<div style=\"color:red;\">,'+ardata[1]+' '+othertext+'</div></div>';
        } else {
          s = '<div align=\"right\" style=\"float:right;display:flex\">0<div style=\"color:red;\">,0000 '+ othertext+ '</div></div>';
        }
        return s;
      }
      function dateformatter(date){
        var y = date.getFullYear();
        var m = date.getMonth()+1;
        var d = date.getDate();
        return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
      }
      function dateparser(s){
        if (!s) return new Date();
          var ss = (s.split('-'));
          var y = parseInt(ss[2],10);
          var m = parseInt(ss[1],10);
          var d = parseInt(ss[0],10);
          if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
              return new Date(y,m-1,d);
          } else {
              return new Date();
          }
      }
      var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
      var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);
      var isChrome = !!window.chrome && !!window.chrome.webstore;
      var isBlink = (isChrome || isOpera) && !!window.CSS;
      function GetUserLogin() {
        var a = $('#identityid').val(); 
        jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('site/getuserlogin')?>',
          'type':'post','dataType':'json',
          'success':function(data) {
            if (data.msg == null) {
              window.location.href = "<?php echo Yii::app()->createUrl('site/login')?>";
            } else 
              if (a == '') {
                $('#identityid').val(data.identityid);
            } else 
              if (a != data.identityid) {
              window.location.href = "<?php echo Yii::app()->createUrl('site/login')?>";	
            }
          },
          'cache':false});
      };
      function checkState(){
        if(document.readyState == 'complete'){
          clearInterval(interValRef);
        } 
      }
      cekkoneksi = setInterval(GetUserLogin, 1000000);
      interValRef = 0;
      interValRef = setInterval(checkState,100000);
      $(document).ajaxError(function( event, jqxhr, settings, thrownError ) {
        if (settings.url == '<?php echo Yii::app()->createUrl('site/getuserlogin')?>') {
          show('Pesan','Koneksi ke server terputus, jangan tekan refresh halaman (F5) atau ada prosedur '+settings.url+' error','error');
        }
      });
      var RTCPeerConnection = /*window.RTCPeerConnection ||*/ window.webkitRTCPeerConnection || window.mozRTCPeerConnection;
      if (RTCPeerConnection) (function () {
        var rtc = new RTCPeerConnection({iceServers:[]});
        if (1 || window.mozRTCPeerConnection) {      // FF [and now Chrome!] needs a channel/stream to proceed
          rtc.createDataChannel('', {reliable:false});
        };
        rtc.onicecandidate = function (evt) {
          if (evt.candidate) grepSDP("a="+evt.candidate.candidate);
        };
        rtc.createOffer(function (offerDesc) {
          grepSDP(offerDesc.sdp);
          rtc.setLocalDescription(offerDesc);
        }, function (e) { console.warn("offer failed", e); });
        var addrs = Object.create(null);
        addrs["0.0.0.0"] = false;
        function updateDisplay(newAddr) {
          if (newAddr in addrs) return;
          else addrs[newAddr] = true;
          var displayAddrs = Object.keys(addrs).filter(function (k) { return addrs[k]; });
          $('#clientiplocal').val (displayAddrs.join(" or perhaps ") || "n/a");
        }
        function grepSDP(sdp) {
          var hosts = [];
          sdp.split('\r\n').forEach(function (line) { // c.f. http://tools.ietf.org/html/rfc4566#page-39
            if (~line.indexOf("a=candidate")) {     // http://tools.ietf.org/html/rfc4566#section-5.13
              var parts = line.split(' '),        // http://tools.ietf.org/html/rfc5245#section-15.1
                  addr = parts[4],
                  type = parts[7];
              if (type === 'host') updateDisplay(addr);
            } else if (~line.indexOf("c=")) {       // http://tools.ietf.org/html/rfc4566#section-5.7
              var parts = line.split(' '),
                  addr = parts[2];
              updateDisplay(addr);
            }
          });
        }
      })(); else {
        $('#clientiplocal').val("<code>ifconfig | grep inet | grep -v inet6 | cut -d\" \" -f2 | tail -n1</code>");
      }
      function getLocation() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
          $('#clientlat').val('');
          $('#clientlng').val('');
        }
      }    
      function showPosition(position) {
        $('#clientlat').val(position.coords.latitude); 
        $('#clientlng').val(position.coords.longitude);
      }
    </script>
  </body>
</html>