<?php if ($slides->count() > 0): ?>
	<?php foreach ($renderer->javascripts as $javascript): ?>
		<?php use_javascript($javascript, 'last') ?>
	<?php endforeach ?>
	<?php foreach ($renderer->stylesheets as $stylesheet): ?>
		<?php use_stylesheet($stylesheet, 'last') ?>
	<?php endforeach ?>
	<?php $renderer->render($slideshow) ?>
<?php else: ?>
  <h1>NO SLIDES FOUND</h1>  There are no slides in your collection
<?php endif ?>


