<?php

/**
* Overrides stringToArray issues
*/
class csSlideshowToolkit extends sfToolkit
{
  
  /**
   * Converts string to array
   *
   * @param  string $string  the value to convert to array
   *
   * @return array
   */
  public static function stringToArray($string)
  {
    preg_match_all('/
      \s*(\w+)              # key                               \\1
      \s*=\s*               # =
      (\'|")?               # values may be included in \' or " \\2
      (.*?)                 # value                             \\3
      (?(2) \\2)            # matching \' or " if needed        \\4
      \s*(?:
        (?=\w+\s*=) | \s*$  # followed by another key= or the end of the string
      )
    /x', $string, $matches, PREG_SET_ORDER);

    $attributes = array();
    foreach ($matches as $val)
    {
      $attributes[$val[1]] = self::literalize($val[3]);
    }
    return $attributes;
  }

  public static function literalize($value, $quoted = false)
  {
    // lowercase our value for comparison
    $value  = trim($value);
    $lvalue = strtolower($value);
    
    if (in_array($lvalue, array('null', '~', '')))
    {
      $value = null;
    }
    // Removed so slideshow uses string literals (for javascript purposes)
    // else if (in_array($lvalue, array('true', 'on', '+', 'yes')))  
    // {
    //   $value = true;
    // }
    // else if (in_array($lvalue, array('false', 'off', '-', 'no')))
    // {
    //   $value = false;
    // }
    else if (ctype_digit($value))
    {
      $value = (int) $value;
    }
    else if (is_numeric($value))
    {
      $value = (float) $value;
    }
    else
    {
      $value = self::replaceConstants($value);
      if ($quoted)
      {
        $value = '\''.str_replace('\'', '\\\'', $value).'\'';
      }
    }

    return $value;
  }
}
