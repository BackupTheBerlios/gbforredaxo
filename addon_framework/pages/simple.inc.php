<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: simple.inc.php,v 1.1 2006/06/13 20:31:09 koala_s Exp $
 */

//------------------------------> Eintragsliste

if ($func == '')
{
  /*
   *  Liste anlegen 
   */
  // Standard sortierung nach Name aufsteigend
  // Standard suchspalte ist Name
  $sql = 'SELECT *  FROM rex_article';
  $list = new rexlist($sql, 'Name', 'ASC', 'Name');
  //$list->debug = true;

  /*
   *  Spalten aus dem SQL-ResultSet anlegen 
   */
  $colId = new resultColumn('id', 'ID');
  $colName = new resultColumn('name', 'Name');
  $colPrio = new resultColumn('catprior', 'Prio');
  $colUpdate = new resultColumn('updateuser', 'Aktualisiert von');
  $colCreate = new resultColumn('createuser', 'Erstellt von');

  /*
   *  Statische Spalten anlegen 
   */
  // Lösch link
  $colAction = new staticColumn('Aktion', 'l&ouml;schen');

  /*
   *  Links auf die Spalten legen 
   */
  // Parameter "func" mit dem Wert "edit"
  // Parameter "id" mit dem Wert "id" aus dem Resultset ("%id%") 
  $colName->setParams(array ('func' => 'edit', 'id' => '%id%'));
  // Parameter "func" mit dem Wert "delete"
  // Parameter "id" mit dem Wert "id" aus dem Resultset ("%id%")
  $colAction->setParams(array ('func' => 'delete', 'id' => '%id%'));

  /*
   *  Spalten zur Anzeige hinzufügen 
   */
  $list->addColumn($colId);
  $list->addColumn($colName);
  $list->addColumn($colPrio);
  $list->addColumn($colUpdate);
  $list->addColumn($colCreate);
  $list->addColumn($colAction);

  /*
   *  Tabelle anzeigen 
   */
  $list->show();
}
?>