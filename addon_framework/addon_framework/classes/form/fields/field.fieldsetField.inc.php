<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.fieldsetField.inc.php,v 1.1 2006/06/13 19:13:41 koala_s Exp $
 */

class fieldsetField extends rexFormField
{
  var $legend;
  var $container;

  function fieldsetField()
  {
    $this->rexFormField('', '', array(), '');
    $this->container = new rexFieldContainer();
  }

  function setLegend($legend)
  {
    $this->legend = $legend;
  }

  function getLegend()
  {
    return $this->legend;
  }

  /**
   * Fügt dem Container ein Feld hinzu.
   * @param object rexFormField-Objekt des hinzugefügt werden soll
   * @param object rexFormSection-Objekt, mit dem das Feld verknüpft werden soll
   * @access protected
   * @see rexFieldContainer::addField() 
   */
  function addField(& $field)
  {
    $this->container->addField($field, $this->getSection());
  }

  /**
   * Fügt dem Container mehrere Felder hinzu
   * @param array Array von rexFormField-Objekten
   * @access public 
   * @see rexFieldContainer::addFields() 
   */
  function addFields(& $fields)
  {
    $this->container->addFields($fields, $this->getSection());
  }

  /**
   * Gibt alle Felder des Containers zurück
   * @return array Array von rexFormField-Objekten
   * @access public 
   * @see rexFieldContainer::getFields() 
   */
  function & getFields()
  {
    return $this->container->getFields();
  }

  /**
   * Gibt die Werte aller Felder des Containers zurück
   * @return array Die Werte der Felder als Array
   * @access protected 
   * @see rexFieldContainer::getFieldValues() 
   */
  function getFieldValues()
  {
    return $this->container->getFieldValues();
  }

  /**
   * Zählt wieviele Felder sich im Container befinden
   * @return integer Anzahl an Felder im Container
   * @access public 
   * @see rexFieldContainer::numFields() 
   */
  function numFields()
  {
    return $this->container->numFields();
  }

  /**
   * Durchsucht den Fieldcontainer nach einem Feld
   * @param string Name des Feldes, wonach gesucht werden soll
   * @return object|null Bei erfolgreicher Suche wird ein rexFormField-Objekt zurückgegeben, sonst null 
   * @access public 
   * @see rexFieldContainer::searchField() 
   */
  function & searchField($name)
  {
    return $this->container->searchField($name);
  }

  function get()
  {
    $s = '';
    $s .= '<fieldset>';

    $legend = $this->getLegend();
    if ($legend != '')
    {
      $s .= '<legend>'.$legend.'</legend>';
    }

    $fields = & $this->getFields();
    for ($i = 0; $i < $this->numFields(); $i++)
    {
      $s .= $fields[$i]->get();
    }

    $s .= '</fieldset>';
    return $s;
  }
}
?>