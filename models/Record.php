<?php
/**
 * Created by PhpStorm.
 * User: Alex1
 * Date: 11.01.2019
 * Time: 0:05
 */

namespace app\models;

use app\interfaces\IRecord;
use app\services\Db;


abstract class Record implements IRecord
{

  protected $db;

  public function __construct()
  {
    $this->db = static::getDb();
  }

  static function getOne($id)
  {
    $tableName = static::getTableName();

    /* id = :id - :-плэйсхолдер, id - имя. Вместо него подстановится значение. Защита от sql инъекции, так как нельзя модифицировать
    sql запрос */
    $sql = "SELECT * FROM {$tableName} WHERE id = :id";
    return static::getDb()->queryObject($sql, get_called_class(), [":id" => $id])[0];
  }

  static function getAll()
  {
    $tableName = static::getTableName();
    $sql = "SELECT * FROM {$tableName}";
    return static::getDb()->queryObject($sql, get_called_class());
  }

  protected static function getDb(){
    return Db::getInstance();
  }

  public function save() {
    $id = $this->id;
    if ($id === null) {
      $this->insert();
    } elseif ($this != $this->getOne($id)) {
      $objFromDb =$this->getOne($id);
      $params = [];
      $expression = [];
      foreach ($this as $key => $value) {
        foreach ($objFromDb as $dbKey => $dbValue) {
         if ($key == $dbKey && $key!= 'db' && $value !=$dbValue) {
          $params[":{$key}"] = $value;
          $expression[] = "$key = :$key";
         }
        }
      }
      $params[":id"] = $id;
      $this->update($params, $expression);
    }
  }

  function insert()
  {
    $params = [];
    $columns = [];
    foreach ($this as $key => $value) {
      if ($key == 'db' ) {
        continue;
      }
      $params[":{$key}"] = $value;
      $columns[] = "`{$key}`";
    }
    $columns = implode(", ", $columns);
    $placeholders = implode(", ", array_keys($params));
    $tableName = static::getTableName();
    $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";
    $this->db->execute($sql, $params);
    $this->id = $this->db->getLastInsertId();
  }

   public function update(array $params, array $expression)
  {
    $tableName = static::getTableName();
    $expression = implode(", ",array_values($expression));
    $sql = "UPDATE {$tableName} SET {$expression} WHERE id= :id";
    var_dump($sql);
    return $this->db->execute($sql, $params);
  }

  public function delete()
  {
    $tableName = static::getTableName();
    $sql = "DELETE FROM {$tableName} WHERE id = :id";
    return $this->db->execute($sql, [":id" => $this->id]);
  }

}