<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.oosql.inc.php,v 1.1 2006/06/13 19:13:40 koala_s Exp $
 */

/**
 * ObjectOrientierter SQL Builder
 */
 
class OOSql
{
  var $qry;

  var $where;
  var $order_by;
  var $group_by;
  var $having;
  var $limit;

  function OOSql($qry)
  {
    $this->qry = $qry;

    $this->where = array ();
    $this->order_by = array ();
    $this->group_by = array ();
    $this->having = array ();
    $this->limit = array ();
  }

  function addLimit($start, $offset = '')
  {
      // Limit darf es nur einmal geben, deshalb immer überschreiben
  $this->limit = array ('start' => $start, 'offset' => $offset);
  }

  function addWhere($cond, $op = 'AND')
  {
    $this->where[] = array ('cond' => $cond, 'op' => $op);
  }

  function addOrderBy($column, $direction = 'ASC')
  {
    $this->order_by[] = array ('column' => $column, 'direction' => $direction);
  }

  function addGroupBy($column)
  {
    $this->group_by[] = array ('column' => $column);
  }

  function addHaving($cond)
  {
    $this->having[] = array ('cond' => $cond);
  }

  function getQry()
  {
    $qry = $this->qry;

    // WHERE String
    for ($i = 0; $i < count($this->where); $i ++)
    {
      $where = $this->where[$i];
      if ($i == 0 && !preg_match('/WHERE/i', $qry))
      {
        $qry .= ' WHERE '.$where['cond'];
      }
      else
      {
        $qry .= ' '.$where['op'].' '.$where['cond'];
      }
    }

    // ORDER BY String      
    for ($i = 0; $i < count($this->order_by); $i ++)
    {
      $order_by = $this->order_by[$i];

      if ($i == 0)
      {
        $qry .= ' ORDER BY ';
      }
      else
      {
        $qry .= ', ';
      }
      $qry .= $order_by['column'].' '.$order_by['direction'];
    }

    // GROUP BY String  
    for ($i = 0; $i < count($this->group_by); $i ++)
    {
      $group_by = $this->group_by[$i];

      if ($i == 0)
      {
        $qry .= ' GROUP BY';
      }
      $qry .= ' '.$group_by['column'];
    }

    // HAVING String  
    for ($i = 0; $i < count($this->having); $i ++)
    {
      $having = $this->having[$i];

      if ($i == 0)
      {
        $qry .= ' HAVING';
      }
      $qry .= ' '.$having['cond'];
    }

    // LIMIT String  
    if (!empty ($this->limit))
    {
      $limit = $this->limit;

      $qry .= ' LIMIT '.$limit['start'];

      if ($limit['offset'] != '')
      {
        $qry .= ', '.$limit['offset'];
      }
    }

    return $qry;
  }
}
?>