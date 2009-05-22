<?php

/**
* 
*/
abstract class BaseSlideshowRenderer implements SlideshowRendererInterface
{
	public $slide_template = "
		<li class='slide'>
			%%image%%
			<div class='item'>
				%%description%%
		 	</div>
		</li>
	";
	
	public $slideshow_template = "
		<div class='cs-slideshow'>
			<ul class='slideshow'>
				%%slides%%
			</ul>
		</div>
	";
	
	public $options = array();
  public $defaults = array();

	public function render($slideshow)
	{
		echo $this->getSlideshow($slideshow);
	}
	public function getSlideshow($slideshow)
	{
		$slides = $this->getSlides($slideshow);
		return str_replace('%%slides%%', implode('', $slides), $this->slideshow_template);
	}
	public function getSlides($slideshow)
	{
		$slides = array();
		foreach ($slideshow['Slides'] as $slide) 
		{
			$slides[] = $this->getSlide($slide);
		}
		return $slides;
	}
	public function addStylesheet($stylesheet)
	{
		$this->stylesheets[] = $stylesheet;
	}
	public function addJavascript($javascript)
	{
		$this->javascripts[] = $javascript;
	}
	public function addOption($option, $choices = null, $default = null)
	{
    $this->options[$option] = $default;
    if ($choices) 
    {
      $this->$option = $choices;
    }
	}
	public function getOption($option, $slideshow)
	{
	  $default = isset($this->defaults[$option]) ? $this->defaults[$option] : null;
    $value = $slideshow->getOption($option, $default); 
    if (isset($this->$option) && is_array($this->$option) && array_key_exists($value, $this->$option)) 
    {
      $options= $this->$option;
      return $options[$value];
    }
    return $value;
	}
	public function getDefaultOptions()
	{
	  $opt = '';
    foreach ($this->options as $option => $default) 
    {
      $opt .= "$option=$default\n";
    }
    return $opt;
	}
	public function getSlide($slide)
	{
		$template = str_replace('%%image%%', $this->getSlideImage($slide), $this->slide_template);
		$template = str_replace('%%description%%', $this->getSlideDescription($slide), $template);
		$template = str_replace('%%title%%', $this->getSlideTitle($slide), $template);				
		return $template;
	}
	public function getSlideTitle($slide)
	{
		return $slide['title'];
	}
	public function getSlideDescription($slide)
	{
		return $slide['description'];
	}
	public function getSlideImage($slide)
	{
		sfProjectConfiguration::getActive()->loadHelpers(array('Asset', 'Tag', 'Url'));
		return image_tag($slide->getImagepath(), array('alt' => $slide['title']));
	}
}
