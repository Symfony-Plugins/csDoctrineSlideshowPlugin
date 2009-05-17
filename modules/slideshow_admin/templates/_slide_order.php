<?php use_javascript('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js') ?>
<?php use_helper('Form', 'Javascript') ?>

<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_slide_order">
	<div>
		<label style='float:none'>Slide Order</label>
		<?php if ($form->getObject()->getSlideshowSlides()->count()): ?>
		<ul id="slideorder">
		<?php foreach ($form->getObject()->getOrderedSlideshowSlides() as $slideshow_slide): ?>
			<li id="slideshow_slide_<?php echo $slideshow_slide->id ?>">
				<input type='hidden' name='slideshow_slide_position[<?php echo $slideshow_slide->id ?>]' value='<?php echo $slideshow_slide->position ?>' />
				<strong><?php echo $slideshow_slide->getSlide()->getTitle() ?></strong>
				<?php echo link_to_function('Up', 'javascript:up(this)') ?>
				<?php echo link_to_function('Down', 'javascript:down(this)') ?>
			</li>
		<?php endforeach ?>
		</ul>
		<?php else: ?>
			<b>No Slides Added</b>
		<?php endif ?>
	</div>
</div>
<script type='text/javascript'>
function up(e)
{
	var pos = parseInt($(e).parent().find('input:first').attr('value'));
	
	if(pos == 1) return;
	$("#slideorder input").each(function(e) {
		if ($(this).attr('value') == pos-1) {
			$(this).attr('value', pos);
		}
		else if($(this).attr('value') == pos)
		{
			$(this).attr('value', pos-1);
			$(this).parent().prev().before($(this).parent());
		}
	});
}
function down(e)
{
	var pos = parseInt($(e).parent().find('input:first').attr('value'));
	var last = parseInt($("#slideorder input:last").attr('value'));
	
	if(pos == last) return;
	$("#slideorder input").each(function(e) {
		if ($(this).attr('value') == pos+1) {
			$(this).attr('value', pos);
		}
		else if($(this).attr('value') == pos)
		{
			$(this).attr('value', pos+1);
			$(this).parent().next().after($(this).parent());
		}
	});
}
</script>