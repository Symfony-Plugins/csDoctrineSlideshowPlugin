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
    
    $this->addOption('thumbnails', 'true', array('false', 'true'));
    $this->addOption('paused', 'false', array('false', 'true'));
    $this->addOption('controller', 'false', array('false', 'true'));
    $this->addOption('captions', 'false', array('false', 'true'));  
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
    foreach ($slideshow->getOrderedSlides() as $slide) 
    {
      $slide_js = str_replace('%%image_path%%', $slide->getImagePath(true), $this->slide_template);
      $slide_js = str_replace('%%thumb_path%%', $slide->getImagePath(true), $slide_js);
      $ret[] = str_replace('%%caption%%', $this->getSlideDescription($slide), $slide_js);
    }
    return implode(", \n", $ret);
  }
  
  public function getSlideDescription($slide)
  {
    return addslashes($slide['description']);
  }
  
  public function getCustomJavascript()
  {
    $js = <<<EOF
    <script type='text/javascript'>
    window.addEvent('domready', function(){
           var data = {
             %%thumbnails%%
           };
           
       var myShow = new Slideshow('slideshow', data, {
          controller: %%controller%%,
          height: %%height%%, 
          width: %%width%%, 
          thumbnails: %%has_thumbnails%%, 
          paused: %%paused%%,
          captions: %%captions%%
        });

             if (myShow.options.thumbnails){
               ['a', 'b'].each(function(p){
                 new Element('div', { 'class': 'overlay ' + p
      }).inject(myShow.slideshow.retrieve('thumbnails'));
               }, myShow);
             }
           });
    </script>
EOF;
  return $js;
}

  public function renderCustomJavascript($slideshow)
  {
    return strtr($this->getCustomJavascript(), array(
        '%%thumbnails%%' => $this->getThumbnails($slideshow), 
        '%%controller%%' => $this->getOption('controller', $slideshow),
        '%%height%%' => $slideshow->height, 
        '%%width%%' => $slideshow->width,
        '%%has_thumbnails%%' => $this->getOption('thumbnails', $slideshow),
        '%%paused%%' => $this->getOption('paused', $slideshow),
        '%%captions%%' => $this->getOption('captions', $slideshow)    
      ));
  }
}
