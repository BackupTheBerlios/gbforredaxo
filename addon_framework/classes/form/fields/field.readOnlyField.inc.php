<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.readOnlyField.inc.php,v 1.1 2006/06/13 20:31:06 koala_s Exp $
 */

class readOnlyField extends rexFormField
{
  var $format;
  var $format_type;
  var $activateSave;
  
  function readOnlyField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormField($name, $label, $attributes, $id);
    $this->format = '';
    $this->format_type = '';
    // default werden Werte nicht gespeichert
    $this->activateSave(false);
  }

  function setFormat($format)
  {
    $this->format = $format;
  }

  function getFormat()
  {
    return $this->format;
  }

  function setFormatType($format_type)
  {
    $this->format_type = $format_type;
  }

  function getFormatType()
  {
    return $this->format_type;
  }
  
  function formatValue()
  {
    $value = $this->getValue();
    $format_type = $this->getFormatType();
    if ($format_type != '')
    {
      $value = rexFormatter :: format($value, $format_type, $this->getFormat());
    }
    return $value;
  }

  function get()
  {
    $value = $this->formatValue();
    return sprintf('<span id="%s"%s>%s</span>', $this->getId(), $this->getAttributes(), $value);
  }
}
?>