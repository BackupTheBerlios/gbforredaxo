<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.passwordField.inc.php,v 1.1 2006/06/13 20:31:06 koala_s Exp $
 */

class passwordField extends rexFormField
{
  function passwordField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormField($name, $label, $attributes, $id);
  }

  function get()
  {
    return sprintf('<input type="password" name="%s" value="%s" id="%s" tabindex="%s"%s />', $this->getName(), $this->getValue(), $this->getId(), rex_a22_nextTabindex(), $this->getAttributes());
  }
}
?>