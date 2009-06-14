<?php use_javascript('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js') ?>
<?php use_helper('Form', 'Javascript') ?>

<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_slide_order">
  <div>
    <label style='float:none'>Slide Order</label>
<?php $ordered =$form->getObject()->getOrderedSlideshowSlides() ?>
    <ul id="selected-slides">
<?php foreach ($ordered as $slideshow_slide): ?>
      <li id="slideshow_slide_<?php echo $slideshow_slide->id ?>">
        <input id="slideshow_slides_list_<?php echo $slideshow_slide->Slide->id ?>" 
                type="checkbox" checked="checked" 
                name='slideshow[slides_list][]' 
                onClick='javascript:slide_select(this)'
                value="<?php echo $slideshow_slide->Slide->id ?>" />

        <input class='position' type='hidden' name='slideshow_slide_position[<?php echo $slideshow_slide->id ?>]' value='<?php echo $slideshow_slide->position ?>' />
        <strong><?php echo $slideshow_slide->getSlide()->getTitle() ?></strong>
        <?php echo link_to_function('Up', 'javascript:up(this)') ?>
        <?php echo link_to_function('Down', 'javascript:down(this)') ?>          
      </li>
<?php endforeach ?>
    </ul>

<?php $unselected = $form->getObject()->getUnselectedSlides() ?>

    <ul id='unselected-slides'>
<?php foreach ($unselected as $slide): ?>
        <li id="slide_<?php echo $slide->id ?>">
          <input id="slideshow_slides_list_<?php echo $slide->id ?>" 
                  type="checkbox" 
                  name='slideshow[slides_list][]' 
                  onClick='javascript:slide_select(this)'
                  value="<?php echo $slide->id ?>" />

          <input class='position' type='hidden' name='slideshow_slide_position[<?php echo $slide->id ?>]' value='' />
          <strong><?php echo $slide->getTitle() ?></strong>
          <?php echo link_to_function('Up', 'javascript:up(this)', array('class' => 'position-controls', 'style' => 'display: none')) ?>
          <?php echo link_to_function('Down', 'javascript:down(this)', array('class' => 'position-controls', 'style' => 'display: none')) ?>          
        </li>
<?php endforeach ?>
    </ul>

  </div>
</div>

<script type='text/javascript'>
function up(e)
{
  var pos = parseInt($(e).parent().find('input.position:first').attr('value'));
  
  if(pos == 1) return;
  $("#selected-slides input.position").each(function(e) {
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
  var pos = parseInt($(e).parent().find('input.position:first').attr('value'));
  var last = parseInt($("#selected-slides input.position:last").attr('value'));
  if(pos == last) return;
  $("#selected-slides input.position").each(function(e) {
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
function slide_select(e)
{
  if ($(e).attr('checked')) 
  {
    var last_slide = $("#selected-slides input.position:last");
    var last = parseInt(last_slide.attr('value'));
    last = last ? last : 0;
    $("#selected-slides").append($(e).parent()); 
    $(e).parent().find('.position-controls').attr('style', 'display:inline');
    $(e).parent().find('input.position').attr('value', last+1);
  }
  else
  {
    var pos = parseInt($(e).parent().find('input.position').attr('value'));
    $("#selected-slides input.position").each(function(e) {
      if ($(this).attr('value') > pos) {
        $(this).attr('value', parseInt($(this).attr('value'))-1);
      }
    });    
    
    $("#unselected-slides").append($(e).parent());    
    $(e).parent().find('.position-controls').attr('style', 'display:none');    
    $(e).parent().find('input.position').attr('value', '');    
  }
}
</script>