<?php
class DbConnect
{
    private static $instance = null;
    private static $connection;

    private static $host = 'localhost';
    private static $dbName = 'trello';
    private static $username = 'root';
    private static $password = '';

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DbConnect();
            self::$instance->connect();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    private static function connect()
    {
        try {
            self::$connection = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbName, self::$username, self::$password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$connection->exec('set names utf8');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getConnection()
    {
        return self::$connection;
    }
}