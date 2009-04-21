<?php if ($slides->count() > 0): ?>
	<?php $sf_response->addJavascript('/csDoctrineSlideshowPlugin/js/jquery.cycle.all.min.js') ?>
	<?php include_partial('csSlideshow/slideshow_js', array('slideshow' => $slideshow)) ?>
	<div class="picture-viewer">
		<?php if (sfConfig::get('app_slideshow_controls')): ?>
			<div class="controls">
				<a href="#"><?php echo image_tag($images['back'], array('id' => 'viewerprev')) ?></a>
				<a href="#"><?php echo image_tag($images['pause'], array('id' => 'viewerpause')) ?></a>
				<a href="#"><?php echo image_tag($images['play'], array('id' => 'viewerplay')) ?></a>
				<a href="#"><?php echo image_tag($images['next'], array('id' => 'viewernext')) ?></a>
			</div>           	
		<?php endif ?>
	  <ul class="viewer">
			<?php foreach ($slides as $slide): ?>
					<li><?php include_partial('csSlideshow/slide', array('slideshow' => $slideshow, 'slide' => $slide)) ?></li>
			<?php endforeach ?>
	 	</ul>                
	</div>
<?php else: ?>
	<!-- <h1>NO SLIDES FOUND</h1>:  There are no slides in your collection -->
<?php endif ?>


