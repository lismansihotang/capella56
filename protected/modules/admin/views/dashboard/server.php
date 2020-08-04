<script>
setInterval(GetDataServer,500000);
$(document).ready(function() {
  GetDataServer();
}); 
function GetDataServer() {
  const result = jQuery.ajax({'url':'<?php echo Yii::app()->params['SysInfoServer']?>',
		'type':'post','dataType':'json',
		'success':function(data)
		{
      document.getElementById("hostname").innerHTML	= data.Vitals["@attributes"]["Hostname"];
      document.getElementById("IPAddr").innerHTML	= data.Vitals["@attributes"]["IPAddr"];
      document.getElementById("Kernel").innerHTML	= data.Vitals["@attributes"]["Kernel"];
      document.getElementById("Distro").innerHTML	= data.Vitals["@attributes"]["Distro"];
      document.getElementById("Users").innerHTML	= data.Vitals["@attributes"]["Users"];
      document.getElementById("LoadAvg").innerHTML	= data.Vitals["@attributes"]["LoadAvg"] + "%";
      document.getElementById("SysLang").innerHTML	= data.Vitals["@attributes"]["SysLang"];
      document.getElementById("CodePage").innerHTML	= data.Vitals["@attributes"]["CodePage"];
      document.getElementById("Processes").innerHTML	= data.Vitals["@attributes"]["Processes"];
      var FreeMemory = data.Memory["@attributes"]["Free"] / 1073741824;
      var UsedMemory = data.Memory["@attributes"]["Used"] / 1073741824;
      var TotalMemory = data.Memory["@attributes"]["Total"] / 1073741824;
      document.getElementById("TotalMemory").innerHTML	= TotalMemory.toFixed(2) + " GB";
      document.getElementById("FreeMemory").innerHTML	= FreeMemory.toFixed(2) + " GB";
      document.getElementById("UsedMemory").innerHTML	= UsedMemory.toFixed(2) + " GB";
      document.getElementById("PercentMemory").innerHTML	= parseFloat(data.Memory["@attributes"]["Percent"]).toFixed(2) + "%";
      var FreeMemory = data.Memory["Swap"]["@attributes"]["Free"] / 1073741824;
      var UsedMemory = data.Memory["Swap"]["@attributes"]["Used"] / 1073741824;
      var TotalMemory = data.Memory["Swap"]["@attributes"]["Total"] / 1073741824;
      document.getElementById("SwapTotalMemory").innerHTML	= TotalMemory.toFixed(2) + " GB";
      document.getElementById("SwapFreeMemory").innerHTML	= FreeMemory.toFixed(2) + " GB";
      document.getElementById("SwapUsedMemory").innerHTML	= UsedMemory.toFixed(2) + " GB";
      document.getElementById("SwapPercentMemory").innerHTML	= parseFloat(data.Memory["Swap"]["@attributes"]["Percent"]).toFixed(2) + "%";
      var CpuCore = data.Hardware.CPU.CpuCore;
      var ContentCpu = "";
      for (i = 0; i < CpuCore.length;i++) {
        ContentCpu = ContentCpu + "<tr>"+
          "<th><span>Cpu "+ i + "</span></th>"+
          "<td><span>"+CpuCore[i]["@attributes"].Model+"</span></td>"+
          "<td><span>"+parseFloat(CpuCore[i]["@attributes"].Load).toFixed(2)+"%</span></td>"+
        "</tr>";
      };
      document.getElementById("cpucore").innerHTML	= ContentCpu;
      var FileCore = data.FileSystem.Mount;
      var ContentFile = "";
      for (i = 0; i < FileCore.length;i++) {
        ContentFile = ContentFile + "<tr>"+
          "<th><span>"+FileCore[i]["@attributes"].MountPoint+" </span></th>"+
          "<td><span>Total: "+parseFloat(FileCore[i]["@attributes"].Total / 1073741824).toFixed(2)+" GB</span></td>"+
          "<td><span>Free: "+parseFloat(FileCore[i]["@attributes"].Free / 1073741824).toFixed(2)+" GB</span></td>"+
          "<td><span>Used: "+parseFloat(FileCore[i]["@attributes"].Used / 1073741824).toFixed(2)+" GB</span></td>"+
        "</tr>";
        ContentFile = ContentFile + "<tr>"+
        "<td></td>"+
        "<td><div class='meter'>"+
  "<span style='width: "+parseFloat(FileCore[i]["@attributes"].Percent).toFixed(2) +"%'></div></td>"+
        "</tr>";
      };
      document.getElementById("filesystem").innerHTML	= ContentFile;
		},
    'cache':false});
    return result;
}
</script>
<div class="card">
  <div class="card-header">System Vitals</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-sm noborderattop">
        <tbody>
          <tr>
            <th><span>Host Name</span></th>
            <td><span id="hostname"></span></td>
          </tr>
          <tr>
            <th><span>Listening IP</span></th>
            <td><span id="IPAddr"></span></td>
          </tr>
          <tr>
            <th><span>Kernel Version</span></th>
            <td><span id="Kernel"></span></td>
          </tr>
          <tr>
            <th><span>Distro Name</span></th>
            <td><span id="Distro"></span></td>
          </tr>
          <tr>
            <th><span >Current Users</span></th>
            <td><span id="Users"></span></td>
          </tr>
          <tr>
            <th><span >Load Averages</span></th>
            <td><span id="LoadAvg"></span></td>
          </tr>
          <tr>
            <th><span>System Language</span></th>
            <td><span id="SysLang"></span></td>
          </tr>
          <tr>
            <th><span >Code Page</span></th>
            <td><span id="CodePage"></span></td>
          </tr>
          <tr>
            <th><span >Processes</span></th>
            <td><span id="Processes"></span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>  
</div>
<div class="card">
  <div class="card-header">Processor</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-sm noborderattop">
        <tbody id="cpucore">
        </tbody>
      </table>
    </div>
  </div>  
</div>
<div class="card">
  <div class="card-header">Physical Memory</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-sm noborderattop">
        <tbody>
          <tr>
            <th><span>Total Memory</span></th>
            <td><span id="TotalMemory"></span></td>
          </tr>
          <tr>
            <th><span>Free</span></th>
            <td><span id="FreeMemory"></span></td>
          </tr>
          <tr>
            <th><span>Used Memory</span></th>
            <td><span id="UsedMemory"></span><span id="PercentMemory"></span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>  
</div>
<div class="card">
  <div class="card-header">Swap Memory</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-sm noborderattop">
        <tbody>
          <tr>
            <th><span>Total Memory</span></th>
            <td><span id="SwapTotalMemory"></span></td>
          </tr>
          <tr>
            <th><span>Free</span></th>
            <td><span id="SwapFreeMemory"></span></td>
          </tr>
          <tr>
            <th><span>Used Memory</span></th>
            <td><span id="SwapUsedMemory"></span><span id="SwapPercentMemory"></span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>  
</div>
<div class="card">
  <div class="card-header">File System</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-sm noborderattop">
        <tbody id="filesystem">
        </tbody>
      </table>
    </div>
  </div>  
</div>