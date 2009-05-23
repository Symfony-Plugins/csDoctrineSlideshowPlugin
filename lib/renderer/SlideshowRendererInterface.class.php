<?php

/**
* 
*/
interface SlideshowRendererInterface
{
	public function render($slideshow);
	public function getSlide($slide);
	public function getSlideImage($slide);
	public function getSlideTitle($slide);
	public function getSlideDescription($slide);
	public function getDefaultOptions();
	public function getOption($option, $slideshow);
}
