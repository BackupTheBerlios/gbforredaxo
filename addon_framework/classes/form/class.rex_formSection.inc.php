<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_formSection.inc.php,v 1.1 2006/06/13 20:31:06 koala_s Exp $
 */

class rexFormSection extends rexfieldController
{
  // Section-Label
  var $label;
  // Spaltenanzahl
  var $columns;

  // Anker
  var $anchor;

  /**
   * Klassenkonstruktor
   */
  function rexFormSection($tableName, $label, $whereParams, $columns = 1)
  {
    rex_valid_type($label, 'string', __FILE__, __LINE__);

    $this->label = $label;
    $this->columns = $columns;
    $this->anchor = '';
    
    // Parentkonstruktor aufrufen
    $this->rexfieldController($tableName, $whereParams);
  }

  /**
   * Setzt einen Anker am beginn des Abschnitts
   * @param string Name des Ankers
   */
  function setAnchor($anchor)
  {
    $this->anchor = $anchor;
  }

  /**
   * Gibt den Namen des Ankers zurück
   * @return string Name des Ankers
   */
  function getAnchor()
  {
    return $this->anchor;
  }

  /**
   * Setzt die Überschrift des Formularabschnitts
   * @param string Überschrift des Abschnitts
   */
  function setLabel( $label)
  {
    $this->label = $label;
  }
  
  /**
   * Gibt die Überschrift des Formularabschnitts
   * @return string Überschrift des Abschnitts
   */
  function getLabel()
  {
    return $this->label;
  }
  
  function isValid($section)
  {
    return is_object($section) && is_a($section, 'rexformsection');
  }

  function numColumns()
  {
    return $this->columns;
  }

  function parseLabel($label)
  {
    $label = str_replace(' - ', '-', $label);
    $label = str_replace(' ', '-', $label);
    $label = str_replace('.', '-', $label);
    $label = str_replace('Ä', 'Ae', $label);
    $label = str_replace('Ö', 'Oe', $label);
    $label = str_replace('Ü', 'Ue', $label);
    $label = str_replace('ä', 'ae', $label);
    $label = str_replace('ö', 'oe', $label);
    $label = str_replace('ü', 'ue', $label);
    $label = str_replace('ß', 'ss', $label);
    $label = preg_replace("/[^a-zA-Z\-0-9]/", "", $label);
    return $label;
  }

  function get()
  {
    $s = '';
    $s .= '    <!-- rexSection start -->'. "\n";
    $s .= '    <div class="a22-rexform-section">'. "\n";

    // Abschnittsanker
    $anchor = $this->getAnchor();
    if ($anchor != '')
    {
      $s .= '      <a name="'.$anchor.'"></a>'."\n";
    }
    $s .= '      <fieldset>'."\n";
    
    // Abschnittsüberschirft
    $label = $this->getLabel();
    if ( $label != '')
    {
      $s .= '        <legend>'. $label .'</legend>'. "\n";
    }
    
    // Hidden fields
    $fields = & $this->getFields();
    $numFields = $this->numFields();

    for ($t = 0; $t < $numFields; $t ++)
    {
      if (is_a($fields[$t], 'hiddenfield'))
      {
        $s .= '        '.$fields[$t]->get()."\n";
        unset ($fields[$t]);
      }
    }

    // Enstandene lücken zwischen den Indizes löschen
    $fields = array_resort_keys($fields);
    $numFields = count($fields);

    // Validierungsfehler
    $errors = & $this->getErrors();
    $numErrors = $this->numErrors();

    if ($numErrors > 0)
    {
      // Validierungsfehler anzeigen
      $s .= '        <ul class="validatormessages">'."\n";
      for ($i = 0; $i < $numErrors; $i ++)
      {
        if (!empty ($errors[$i]))
        {
          $s .= '            <li>'.$errors[$i].'</li>'."\n";
        }
      }
      $s .= '        </ul>'."\n";
    }

    // Daten aufbereiten
    $numCols = $this->numColumns();
    $colData = array();
    $fullCols = array();
    $i = 1;
    for ($t = 0; $t < $numFields; $t ++)
    {
      $field = & $fields[$t];
      
      $field_label = $field->getLabel();
      $field_value = $field->get();

      if ($field_label != '')
      {
        $field_label = '<label for="'. $field->getId() .'">'. $field_label .'</label>';
      }
      
      $fieldStr = $field_label . $field_value; 
      
      if($field->needFullColumn())
      {
        $fullCols[] = $fieldStr. "\n";
        continue;
      }
      
      $colData[$i][] = '<p>'. $fieldStr .'</p>'. "\n";
      
      $i++;
      if($i>$numCols)
      {
        $i = 1;
      }
    }
    
    foreach ($colData as $colIndex => $column)
    {
      $colStr = '';
      if(count($column) > 0)
      {
        $colStr .= '        <div class="a22-col'. $colIndex .'">'. "\n";
        foreach($column as $colValue)
        {
          $colStr .= '          ' .$colValue;
        }
        $colStr .= '        </div>'. "\n";
      }
      $s .= $colStr;
    }
    
    $s .= '        <div class="clearer"> </div>'."\n";
    
    foreach($fullCols as $colValue)
    {
      $s .= '        '. $colValue;
    }
    
    $s .= '      </fieldset>'."\n";
    $s .= '    </div>'."\n";
    $s .= '    <!-- rexSection end -->'. "\n";
    
    return $s;
  }

  function show()
  {
    echo $this->get();
  }

  function toString()
  {
    return 'rexFormSection: name: "'.$this->getTableName().'", label: "'.$this->getLabel().'", felder: "'.$this->numFields().'"';
  }
}
?>