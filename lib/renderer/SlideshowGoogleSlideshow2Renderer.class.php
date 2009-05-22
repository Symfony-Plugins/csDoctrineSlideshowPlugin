<?php

/**
* 
*/
class SlideshowGoogleSlideshow2Renderer extends BaseSlideshowRenderer
{
  public $slideshow_template = "
   <div id='slideshow' class='cs-slideshow'>
     %%slides%%
   </div>
  ";
  public $slide_template = "
    '%%image_path%%' : { caption: '%%caption%%', thumbnail: '%%thumb_path%%' }
  ";
	
	public function __construct()
	{
		$this->addJavascript('/csDoctrineSlideshowPlugin/js/mootools.js');
  	$this->addJavascript('/csDoctrineSlideshowPlugin/js/slideshow.min.js');
		$this->addStylesheet('/csDoctrineSlideshowPlugin/css/google-slideshow.css');
		$this->addOption('thumbnails', array('true', 'false'), 'true');
		$this->addOption('paused', array('true', 'false'), 'false');
	}
	public function render($slideshow)
	{
		echo $this->getSlideshow($slideshow);
		echo $this->renderCustomJavascript($slideshow);
	}
	public function getSlideshow($slideshow)
	{
    return str_replace('%%slides%%', $this->getSlideImage($slideshow['Slides'][0]), $this->slideshow_template);
	}
	public function getThumbnails($slideshow)
	{
	  $ret = array();
    foreach ($slideshow['Slides'] as $slide) 
    {
      $slide_js = str_replace('%%image_path%%', $slide->getImagePath(true), $this->slide_template);
      $slide_js = str_replace('%%thumb_path%%', $slide->getImagePath(true), $slide_js);
      $ret[] = str_replace('%%caption%%', $slide['description'], $slide_js);
    }
    return implode(", \n", $ret);
	}
	public function renderCustomJavascript($slideshow)
	{
		$js = <<<EOF
		<script type='text/javascript'>
		window.addEvent('domready', function(){
           var data = {
             %s
           };
           
			 var myShow = new Slideshow('slideshow', data, {controller: false,
      height: %s, width: %s, thumbnails: %s, paused: %s});

             if (myShow.options.thumbnails){
               ['a', 'b'].each(function(p){
                 new Element('div', { 'class': 'overlay ' + p
      }).inject(myShow.slideshow.retrieve('thumbnails'));
               }, myShow);
             }
           });
		</script>
EOF;

		return sprintf($js, 
		    $this->getThumbnails($slideshow), 
		    $slideshow->height, 
		    $slideshow->width,
		    $this->getOption('thumbnails', $slideshow),
		    $this->getOption('paused', $slideshow)
		  );
	}
}
