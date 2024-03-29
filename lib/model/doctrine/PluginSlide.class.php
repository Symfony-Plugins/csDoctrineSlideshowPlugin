<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginSlide extends BaseSlide
{
	public function hasTitle()
	{
		return $this['title'] != '' && $this['title'] != null;
	}
	public function hasDescription()
	{
		return $this['description'] != '' && $this['description'] != null;
	}
	public function getUploadPath()
	{
		return 'uploads/slideshow';
	}
	public function getImagepath($absolute = false)
	{
	  if ($absolute) 
	  {
	    sfProjectConfiguration::getActive()->loadHelpers(array('Asset', 'Tag', 'Url'));
  		return public_path($this->getUploadPath() .'/'.$this->getImage(), array('absolute'=> 'true'));
	  }
    return '/'.$this->getUploadPath() .'/'.$this->getImage();
	}
}