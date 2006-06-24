<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: formats.inc.php,v 1.1 2006/06/24 11:04:17 koala_s Exp $
 */

//------------------------------> Eintragsliste

if ($func == '')
{
  /**
   *  Liste anlegen 
   */
  // Standard sortierung nach Name aufsteigend
  // Standard suchspalte ist Name
  $sql = 'SELECT * FROM rex_article';
  $list = new rexlist($sql, 'Name', 'ASC', 'Name');

  /**
   *  Spalten aus dem SQL-ResultSet anlegen 
   */
  $colId = new resultColumn('id', 'ID');
  // String formatiert mit sprintf() im format '-> [%s] <-'
  $colName = new resultColumn('name', 'Name', 'sprintf', '-> [%s] <-');
  $colPrio = new resultColumn('catprior', 'Prio');
  // Datum formatiert mit date() im format 'd.m.Y'
  $colUpdate = new resultColumn('updatedate', 'Aktualisiert am', 'date', 'd.m.Y');
  // Datum formatiert mit strftime() im format '%d.%m.%Y %H-%M'
  $colCreate = new resultColumn('createdate', 'Erstellt am', 'strftime', '%d.%m.%Y %H-%M');

  /**
   *  Statische Spalten anlegen 
   */
  // Lösch link
  $colAction = new staticColumn('Aktion', 'l&ouml;schen');

  /**
   *  Sonder Spalten anlegen 
   */
  // fortlaufende Nr
  $colCount = new countColumn('#');

  /**
   *  Links auf die Spalten legen 
   */
  // Parameter "func" mit dem Wert "edit"
  // Parameter "id" mit dem Wert "id" aus dem Resultset ("%id%") 
  $colName->setParams(array ('func' => 'edit', 'id' => '%id%'));
  // Parameter "func" mit dem Wert "delete"
  // Parameter "id" mit dem Wert "id" aus dem Resultset ("%id%")
  $colAction->setParams(array ('func' => 'delete', 'id' => '%id%'));

  /**
   *  Spalten zur Anzeige hinzufügen 
   */
  $list->addColumn($colCount);
  $list->addColumn($colId);
  $list->addColumn($colName);
  $list->addColumn($colPrio);
  $list->addColumn($colUpdate);
  $list->addColumn($colCreate);
  $list->addColumn($colAction);

  /**
   *  Tabelle anzeigen 
   */
  $list->show();
}
?>