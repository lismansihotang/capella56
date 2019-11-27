<!-- Slideshow container -->
<div class="slideshow-container">

  <!-- Full-width images with number and caption text -->
  <?php $dir = ROOTPATH . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'wallpaper' . DIRECTORY_SEPARATOR;
    $fi = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
    if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
          if(!is_dir($file)) {?>
    <div class="mySlides fade wallpaper" id ="<?php echo $file?>">
      <img alt="<?php echo $file?>" src="<?php echo Yii::app()->request->baseUrl?>/images/wallpaper/<?php echo $file?>" style="width:100%">
      <div class="text"><?php echo $file?></div>
    </div>
        <?php }
        }
        closedir($dh);
      }
    }?>

  <!-- Next and previous buttons -->
  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>
<br>

<script>
  $(".wallpaper").click(function(){
    wallpaper = $(this).attr("id");
    $("body").css("background-image", "url(<?php echo Yii::app()->request->baseUrl?>/images/wallpaper/"+wallpaper+")");  
    saveWallpaper(wallpaper);
  });
  var slideIndex = 1;
  showSlides(slideIndex);

  // Next/previous controls
  function plusSlides(n) {
    showSlides(slideIndex += n);
  }

  // Thumbnail image controls
  function currentSlide(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex-1].style.display = "block";
  }
  function saveWallpaper(paper) {
    jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('admin/personalization/save')?>',
      'data':{
        'wallpaper' : paper,
      },'type':'post','dataType':'json',
      error: function(xhr,status,error) {
        show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,'1');
      },
      'success':function(data) {
      } ,
      'cache':false});
  }
</script>
