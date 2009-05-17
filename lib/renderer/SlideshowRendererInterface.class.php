<?php

/**
* 
*/
interface SlideshowRendererInterface
{
	public function render($slideshow);
	public function getSlide($slide);
	public function getSlideImage($slide);
}
