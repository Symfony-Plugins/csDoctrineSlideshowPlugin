<div class="picture">
	<?php if ($slideshow->width && $slideshow->height): ?>
		<?php use_helper('Thumbnail') ?>
		
			<?php echo thumbnail_tag($slide->getImagepath(),
			 												$slideshow->width, 
															$slideshow->height,
															'normal',
							array('alt' => $slide->getTitle())) ?>	
	
	<?php else: ?>
		<?php echo image_tag($slide->getImagepath(), array('alt' => $slide->getTitle())) ?>	
	<?php endif ?>
	
	<?php if ($slide->hasCaption()): ?>
		<div class="caption">
			<span><?php echo $slide->getCaption() ?> </span>
		</div>
	<?php endif ?>
	
	<?php if ($slide->hasTitle()): ?>
	  <h4> <?php echo $slide->getTitle() ?></h4>                			 		
	<?php endif ?>
	<?php if ($slide->hasDescription()): ?>
	 <div class="item">
		<?php echo $slide->getDescription() ?>
		<?php if ($slide->hasUrl()): ?>
			<?php echo link_to('read mode', $slide->getUrl(), array('class' => 'readmore')) ?>
		<?php endif ?>
	 </div>
	<?php endif ?>
</div>