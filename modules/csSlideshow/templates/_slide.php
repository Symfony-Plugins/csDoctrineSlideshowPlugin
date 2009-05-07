<div class="picture">
	<?php if ($slideshow->width || $slideshow->height): ?>
		<?php if (method_exists('thumbnail_tag')): ?>
		<?php use_helper('Thumbnail') ?>
		<!-- Uses thumbnail plugin if available -->
			<?php echo thumbnail_tag($slide->getImagepath(),
			 												$slideshow->width, 
															$slideshow->height,
															'normal', 
															array('alt' => $slide->getTitle())) ?>	
		<?php else: ?>
			<?php echo image_tag($slide->getImagepath(), array('alt' => $slide->getTitle(), 
																												 'width' => $slideshow->width, 
																												 'height' => $slideshow->height)) ?>	
		<?php endif ?>
	
	<?php else: ?>
		<?php echo image_tag($slide->getImagepath(), array('alt' => $slide->getTitle())) ?>	
	<?php endif ?>
	
	<?php if ($slide->hasDescription()): ?>
	 <div class="item">
		<?php echo $slide->getDescription() ?>
	 </div>
	<?php endif ?>
</div>