<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_fieldContainer.inc.php,v 1.1 2006/06/13 20:31:05 koala_s Exp $
 */

/**
 * Basisklasse um rexFormField komponenten zu handeln
 * @access protected
 */

class rexFieldContainer
{
  /**
   * Array von rexFormField-Objekten
   * @var array
   */
  var $fields;

  /**
   * Klassenkonstruktor
   */
  function rexFieldContainer()
  {
    $this->fields = array ();
  }

  /**
   * Fügt dem Container ein Feld hinzu.
   * @param object rexFormField-Objekt des hinzugefügt werden soll
   * @param object rexFormSection-Objekt, mit dem das Feld verknüpft werden soll
   * @access protected 
   */
  function addField(& $field, & $section)
  {
    if (!rexFormField :: isValid($field))
    {
      trigger_error('rexForm: Unexpected type "'.gettype($field).'" for $field! Expecting "rexformfield"-object.', E_USER_ERROR);
    }

    $field->rexsection = & $section;
    $this->fields[] = & $field;
  }

  /**
   * Fügt dem Container mehrere Felder hinzu
   * @param array Array von rexFormField-Objekten
   * @access public 
   */
  function addFields(& $fields, & $section)
  {
    for ($i = 0; $i < count($fields); $i++)
    {
      $this->addField($fields[$i], $section);
    }
  }

  /**
   * Gibt alle Felder des Containers zurück
   * @return array Array von rexFormField-Objekten
   * @access public 
   */
  function & getFields()
  {
    return $this->fields;
  }

  /**
   * Gibt die Werte aller Felder des Containers zurück
   * @return array Die Werte der Felder als Array
   * @access protected 
   */
  function getFieldValues()
  {
    $fields = $this->getFields();
    $values = array ();

    for ($i = 0; $i < $this->numFields(); $i++)
    {
      $values[$fields[$i]->getName()] = $fields[$i]->getValue();
    }

    return $values;
  }

  /**
   * Zählt wieviele Felder sich im Container befinden
   * @return integer Anzahl an Felder im Container
   * @access public 
   */
  function numFields()
  {
    return count($this->getFields());
  }

  /**
   * Durchsucht den Fieldcontainer nach einem Feld
   * @param string Name des Feldes, wonach gesucht werden soll
   * @return object|null Bei erfolgreicher Suche wird ein rexFormField-Objekt zurückgegeben, sonst null 
   * @access public 
   */
  function & searchField($name)
  {
    $fields = $this->getFields();
    if (is_array($fields))
    {
      for ($i = 0; $i < $this->numFields(); $i++)
      {
        $field = & $fields[$i];
        if ($field === null)
        {
          continue;
        }

        if ($field->getName() == $name)
        {
          return $field;
        }
      }
    }
    return null;
  }
}
?>