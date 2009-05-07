<?php

/**
 * PluginSlideshow form.
 *
 * @package    form
 * @subpackage Slideshow
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginSlideshowForm extends BaseSlideshowForm
{
	public function setup()
	{
		unset($this['created_at'], $this['updated_at']);
	}
}