<?php

class csSlideshowComponents extends sfComponents
{
	public function executeSlideshow(sfWebRequest $request)
	{
		if(isset($this->slideshow))
		{
			$this->slides = isset($this->slides) ? $this->slides : $this->slideshow['Slides'];
		}
		elseif (isset($this->id)) 
		{
			$this->slideshow = Doctrine::getTable('Slideshow')->findOneById($this->id);
			$this->slides = $this->slideshow['Slides'];
		}
		else
		{
			$this->slides = isset($this->slides) ? $this->slides : Doctrine::getTable('Slide')->findAll();	
			$this->slideshow = new Slideshow();
		}
		$renderClass = $this->slideshow->getRenderer();
		$this->renderer = new $renderClass();
	}
	
}