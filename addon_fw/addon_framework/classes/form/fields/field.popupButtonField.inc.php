<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.popupButtonField.inc.php,v 1.1 2006/06/24 11:04:15 koala_s Exp $
 */

class popupButtonField extends readOnlyTextField
{
  var $buttons;

  function popupButtonField($name, $label, $attributes = array (), $id = '')
  {
    if (empty ($attributes['class']))
    {
      $attributes['class'] = 'inpgrey100';
    }
    $this->readOnlyTextField($name, $label, $attributes, $id);
    $this->buttons = array ();
  }

  /**
   * Fügt dem ButtonField einen Button hinzu
   * @param $title Titel des Buttons 
   * @param $href Link des Buttons 
   * @param $title Dateiname des Bilder für den Button 
   */
  function addButton($title, $href, $image = 'file_open.gif')
  {
    $this->buttons[] = array (
      'title' => $title,
      'href' => $href,
      'image' => $image
    );
  }

  function getInputFields()
  {
    return parent :: get();
  }

  function getInsertValue()
  {
    return $this->_getInsertValue();
  }

  function getButtons()
  {
    $s = '';

    $id = $this->getId();
    foreach ($this->buttons as $button)
    {
      foreach ($button as $attr_name => $attr_value)
      {
        $button[$attr_name] = str_replace('%id%', $id, $attr_value);
      }
      $s .= sprintf('      <td class="inpicon"><a href="%s" tabindex="%s"><img src="pics/%s" width="16" height="16" alt="%s" title="%s" /></a></td>'."\n", $button['href'], rex_a22_nextTabindex(), $button['image'], $button['title'], $button['title']);
    }

    return $s;
  }

  function get()
  {

    $s = '';
    $s .= '<table class="rexbutton">'."\n";
    $s .= '    <tr>'."\n";
    $s .= '      <td>'.$this->getInputFields().'</td>'."\n";
    $s .= '      '.$this->getButtons();
    $s .= '    </tr>'."\n";
    $s .= '  </table>';

    return $s;
  }
}
?>