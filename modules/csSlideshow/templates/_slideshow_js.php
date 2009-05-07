<script>
$(document).ready(function(){

  //hovers for subnav//viewer for main gallery
	$('.slideshow').cycle({ 
	  fx:     '<?php echo $slideshow->fx ? $slideshow->fx : sfConfig::get("app_slideshow_fx") ?>', 
	  speed:  '<?php echo $slideshow->speed ? $slideshow->speed : sfConfig::get("app_slideshow_speed") ?>', 
	  timeout: '<?php echo $slideshow->timeout ? $slideshow->timeout : sfConfig::get("app_slideshow_timeout") ?>',
	  next:   '#viewernext', 
	  prev:   '#viewerprev' 
	});
	$('#viewerpause').click(function(){ 
	  $('.viewer').cycle('pause');
	return false;
	 });
	$('#viewerplay').click(function(){ 
	  $('.viewer').cycle('resume');
	return false;
	});
});
</script>