<?php
abstract class Entity
{
    private $msg;

    protected $dbc;
    protected $tableName;
    protected $fields;
    abstract protected function initFields();

    protected function __construct($dbc, $tableName)
    {
        $this->msg = new \Plasticbrain\FlashMessages\FlashMessages();
        $this->dbc = $dbc;
        $this->tableName = $tableName;
        $this->initFields();
    }
    public function addIntoDb($values)
    {

        $fieldsString = implode(', ', $this->fields);
        $placeholders = rtrim(str_repeat('?, ', count($this->fields)), ', ');

        $sql = "INSERT INTO {$this->tableName} ({$fieldsString}) VALUES ({$placeholders})";

        try {
            $stmt = $this->dbc->prepare($sql);
            $stmt->execute($values);
            $this->msg->success('Added successfully');
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function find($conditions = array())
    {
        $whereClause = '';
        $params = array();

        if (!empty($conditions)) {
            $whereClause = 'WHERE ';
            foreach ($conditions as $field => $value) {
                $whereClause .= "$field = ? AND ";
                $params[] = $value;
            }
            $whereClause = rtrim($whereClause, 'AND ');
        }

        $sql = "SELECT * FROM {$this->tableName} {$whereClause}";

        try {
            $stmt = $this->dbc->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();

        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function update($newValues, $conditions = array())
    {
        $setClause = '';
        $whereClause = '';
        $params = array();

        if (!empty($newValues)) {
            $setClause = 'SET ';
            foreach ($newValues as $field => $value) {
                $setClause .= "$field = ?, ";
                $params[] = $value;
            }
            $setClause = rtrim($setClause, ', ');
        }

        if (!empty($conditions)) {
            $whereClause = 'WHERE ';
            foreach ($conditions as $field => $value) {
                $whereClause .= "$field = ? AND ";
                $params[] = $value;
            }
            $whereClause = rtrim($whereClause, 'AND ');
        }

        $sql = "UPDATE {$this->tableName} {$setClause} {$whereClause}";

        try {
            $stmt = $this->dbc->prepare($sql);
            $stmt->execute($params);
            $this->msg->success('Updated successfully');
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function create_slug($string)
    {
        return preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    }
}