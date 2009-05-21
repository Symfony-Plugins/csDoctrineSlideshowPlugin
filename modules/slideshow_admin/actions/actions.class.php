<?php

require_once dirname(__FILE__).'/../lib/slideshow_adminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/slideshow_adminGeneratorHelper.class.php';

/**
 * sympal_slideshow_admin actions.
 *
 * @package    sympal
 * @subpackage sympal_slideshow_admin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class slideshow_adminActions extends autoSlideshow_adminActions
{
	protected function processForm(sfWebRequest $request, sfForm $form)
  {
		$positions = $request->getParameter('slideshow_slide_position', array());
		Doctrine::getTable('SlideshowSlide')->sort($positions);
		return parent::processForm($request, $form);
	}
}
