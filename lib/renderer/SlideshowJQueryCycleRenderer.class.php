<?php

/**
* 
*/
class SlideshowJQueryCycleRenderer extends BaseSlideshowRenderer
{
	public $controller_back 	= '/csDoctrineSlideshowPlugin/images/controller-back.png';
  public $controller_pause 	= '/csDoctrineSlideshowPlugin/images/controller-pause.png';
 	public $controller_play 	=	'/csDoctrineSlideshowPlugin/images/controller-play.png';
  public $controller_next 	= '/csDoctrineSlideshowPlugin/images/controller-forward.png';
	public $slideshow_template = "
		<div class='cs-slideshow'>
			<ul class='slideshow'>
				%%slides%%
			</ul>
			%%controls%%
		</div>
	";	
	
	public $effects = array('blindX', 'growX', 'scrollLeft', 'zoom', 'fade', 'turnLeft', 'turnDown', 'curtainX', 'scrollRight');
	public $defaults = array('fx' => 'fade');
	
	public function __construct()
	{
		$this->addJavascript('/csDoctrineSlideshowPlugin/js/jquery.cycle.all.min.js');
		$this->addStylesheet('/csDoctrineSlideshowPlugin/css/slideshow-default.css');
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
				  fx:     	'%s', 
				  speed:  	'%s', 
				  timeout:	'%s',
				  next:   	'#viewernext', 
				  prev:   	'#viewerprev' 
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
		if($fx = $slideshow->fx)
		{
			if (!in_array($fx, $this->effects)) 
			{
				throw new sfException('This Slideshow Renderer only accepts the following effects: "'.implode(', ', $this->effects).'"');
			}
		}
		else
		{
			$fx = $this->defaults['fx'];
		}
		return sprintf($js, $fx, $slideshow->speed, $slideshow->timeout);
	}
}
