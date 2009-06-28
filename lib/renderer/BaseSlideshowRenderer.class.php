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
  
  // returns an array of rendered slides
  public function getSlides($slideshow)
  {
    $slides = array();
    foreach ($slideshow->getOrderedSlides() as $slide) 
    {
      $slides[] = $this->getSlide($slide);
    }
    return $slides;
  }
  
  // To be included at runtime
  public function addStylesheet($stylesheet)
  {
    $this->stylesheets[] = $stylesheet;
  }

  // To be included at runtime  
  public function addJavascript($javascript)
  {
    $this->javascripts[] = $javascript;
  }
  
  // Adds an option, default, and adds a dropdown if choices are supplied
  public function addOption($option, $default = null, $choices = null)
  {
    $this->options[$option] = (string)$default;
    if ($choices) 
    {
      $this->$option = $choices;
    }
  }
  
  public function getOption($option, $slideshow)
  {
    $default = isset($this->defaults[$option]) ? $this->defaults[$option] : null;
    
    // get value from slideshow
    $value = (string)$slideshow->getOption($option, $default); 
    
    // if option is from a list of options, return text value
    // from option key
    if (isset($this->$option) && 
          is_array($this->$option) && 
          $value && array_key_exists($value, $this->$option)) 
    {
      $options= $this->$option;
      return $options[$value];
    }
    
    return $value;
  }
  
  // Build default options list (for new objects)
  public function getDefaultOptions()
  {
    $opt = '';
    foreach ($this->options as $option => $default) 
    {
      $opt .= "$option=$default\n";
    }
    return $opt;
  }
  
  // renders the slide object
  public function getSlide($slide)
  {
    $template = str_replace('%%image%%', $this->getSlideImage($slide), $this->slide_template);
    $template = str_replace('%%description%%', $this->getSlideDescription($slide), $template);
    $template = str_replace('%%title%%', $this->getSlideTitle($slide), $template);        
    return $template;
  }
  
  // renders the slide title
  public function getSlideTitle($slide)
  {
    return $slide['title'];
  }
  
  // renders the slide description
  public function getSlideDescription($slide)
  {
    return $slide['description'];
  }
  
  // renders the slide image
  public function getSlideImage($slide)
  {
    sfProjectConfiguration::getActive()->loadHelpers(array('Asset', 'Tag', 'Url'));
    return image_tag($slide->getImagepath(), array('alt' => $slide['title']));
  }
}
