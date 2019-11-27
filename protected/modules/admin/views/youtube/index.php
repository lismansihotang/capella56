<div class="easyui-panel" style="width:100%;min-width:400px;height:100%">
    <input class="easyui-searchbox" data-options="prompt:'Please Input Value',searcher:doSearch" style="width:100%">
</div>
<script>
  function doSearch(value){
    alert('You input: ' + value);
  }
</script>