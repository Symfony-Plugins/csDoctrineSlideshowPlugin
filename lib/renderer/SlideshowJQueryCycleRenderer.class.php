<?php

/**
* 
*/
class SlideshowJQueryCycleRenderer extends BaseSlideshowRenderer
{
  public $controller_back   = '/csDoctrineSlideshowPlugin/images/controller-back.png';
  public $controller_pause  = '/csDoctrineSlideshowPlugin/images/controller-pause.png';
  public $controller_play   = '/csDoctrineSlideshowPlugin/images/controller-play.png';
  public $controller_next   = '/csDoctrineSlideshowPlugin/images/controller-forward.png';
  public $slideshow_template = "
    <div class='cs-slideshow'>
      <ul class='slideshow'>
        %%slides%%
      </ul>
      %%controls%%
    </div>
  ";  
  
  public function __construct()
  {
    $this->addJavascript('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js');
    $this->addJavascript('/csDoctrineSlideshowPlugin/js/jquery.cycle.all.min.js');
    $this->addStylesheet('/csDoctrineSlideshowPlugin/css/slideshow-default.css');
    
    $this->addOption('fx', 'fade', array('blindX', 'growX', 'scrollLeft', 'zoom', 'fade', 'turnLeft', 'turnDown', 'curtainX', 'scrollRight'));
    $this->addOption('speed', 'slow', array('slow', 'fast'));
    $this->addOption('timeout', '5000');
  }
  
  public function render($slideshow)
  {
    echo str_replace('%%controls%%', $this->getControls(), $this->getSlideshow($slideshow));
    echo $this->renderCustomJavascript($slideshow);
  }
  
  public function getSlideshow($slideshow)
  {
    return str_replace('%%controls%%', $this->getControls(), parent::getSlideshow($slideshow));
  }
  
  public function getControls()
  {
    sfProjectConfiguration::getActive()->loadHelpers(array('Asset', 'Tag', 'Url'));
    $controls = "
      <div class='controls'>        
        <a href='#'>".image_tag($this->controller_back, array('id' => 'viewerprev'))."</a>
        <a href='#'>".image_tag($this->controller_pause, array('id' => 'viewerpause'))."</a>
        <a href='#'>".image_tag($this->controller_play, array('id' => 'viewerplay'))."</a>
        <a href='#'>".image_tag($this->controller_next, array('id' => 'viewernext'))."</a>
      </div>";
    return $controls;
  }
  
  public function renderCustomJavascript($slideshow)
  {
    $js = <<<EOF
    <script type='text/javascript'>
      $(document).ready(function(){

        //hovers for subnav//viewer for main gallery
        $('.slideshow').cycle({ 
          fx:       '%s', 
          speed:    '%s', 
          timeout:  '%s',
          next:     '#viewernext', 
          prev:     '#viewerprev' 
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
EOF;
    return sprintf($js, 
        $this->getOption('fx', $slideshow), 
        $this->getOption('speed', $slideshow), 
        $this->getOption('timeout', $slideshow)
        );
  }
}
