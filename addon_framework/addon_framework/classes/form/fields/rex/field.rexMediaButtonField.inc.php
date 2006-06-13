<?php

/**
 * Addon Framework Classes by <a href='mailto:staab@public-4u.de'>Markus Staab</a>
 * <a href='http://www.public-4u.de'>www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.rexMediaButtonField.inc.php,v 1.1 2006/06/13 19:13:41 koala_s Exp $
 */

class rexMediaButtonField extends popupButtonField
{
  function rexLinkButtonField($name, $label, $attributes = array (), $id = '')
  {
    if ( empty( $attributes['style']))
    {
      $attributes['style'] = 'width: 20%;';
    }
    $this->popupButtonField($name, $label, $attributes, $id);
  }
  
  function get()
  {
    $section =& $this->getSection();
    $form = $section->getForm();
    // Buttons erst hier einfügen, da vorher die ID noch nicht vorhanden ist
    $this->addButton( 'Medienpool öffnen', 'javascript:openMediaPool(\'&amp;opener_form='. $form->getName() .'&amp;opener_input_field='. $this->getId() .'\');');
    $this->addButton( 'Medium entfernen', 'javascript:setValue(\''. $this->getId() .'\',\'\');', 'file_del.gif');
    $this->addButton( 'Medium hinzufügen', 'javascript:openMediaPool(\'&amp;action=media_upload&amp;subpage=add_file&amp;opener_form='. $form->getName() .'&amp;opener_input_field='. $this->getId() .'\');', 'file_add.gif');
    return parent::get();
  }
} 
?>