<?php
/* Copyright (C) 2013-2014  Stephan Kreutzer
 *
 * This file is part of automated_digital_publishing_server.
 *
 * automated_digital_publishing_server is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License version 3 or any later version,
 * as published by the Free Software Foundation.
 *
 * automated_digital_publishing_server is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License 3 for more details.
 *
 * You should have received a copy of the GNU Affero General Public License 3
 * along with automated_digital_publishing_server. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * @file $/web/libraries/database.inc.php
 * @author Stephan Kreutzer
 * @since 2013-09-19
 */



/**
 * @class Database
 */
class Database
{
    static public function Get()
    {
        if (self::$instance == NULL)
        {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->lastErrorMessage = "";
        $this->pdo = false;
        $this->prefix = "";

        if (file_exists(dirname(__FILE__)."/database_connect.inc.php") === true)
        {
            require_once(dirname(__FILE__)."/database_connect.inc.php");

            if ($exceptionConnectFailure === NULL)
            {
                $this->pdo = $pdo;
                // Will print warnings, if @ gets removed.
                //$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }
            else
            {
                $this->pdo = false;
                $this->lastErrorMessage = $exceptionConnectFailure->getMessage();
            }

            $this->prefix = $db_table_prefix;
        }
    }

    public function __destruct()
    {
        unset($this->pdo);
    }


    const TYPE_BOOL = PDO::PARAM_BOOL;
    const TYPE_NULL = PDO::PARAM_NULL;
    const TYPE_INT = PDO::PARAM_INT;
    const TYPE_STRING = PDO::PARAM_STR;


    public function IsConnected()
    {
        if ($this->pdo == false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function GetPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param[in] $sql 
     */
    public function Query($sql, $arguments, $parameterTypes)
    {
        if ($this->IsConnected() !== true)
        {
            return -1;
        }

        $stmt = false;

        try
        {
            $stmt = $this->pdo->prepare($sql);
        }
        catch (PDOException $ex)
        {
            return -2;
        }

        if ($stmt == false)
        {
            return -3;
        }

        if (is_array($arguments) !== true)
        {
            return -4;
        }

        $argumentsCount = count($arguments);

        if ($argumentsCount <= 0)
        {
            return -5;
        }

        if (is_array($parameterTypes) !== true)
        {
            return -6;
        }

        $parameterTypesCount = count($parameterTypes);

        if ($parameterTypesCount <= 0)
        {
            return -7;
        }

        if ($argumentsCount != $parameterTypesCount)
        {
            return -8;
        }

        for ($i = 0; $i < $argumentsCount; $i++)
        {
            if ($stmt->bindParam($i+1, $arguments[$i], $parameterTypes[$i]) == false)
            {
                return -9;
            }
        }

        if (@$stmt->execute() == false)
        {
            if (isset($this->pdo->errorInfo()[2]) === true)
            {
                $this->lastErrorMessage = $this->pdo->errorInfo()[2];
            }

            return -10;
        }

        $result = $stmt->fetchAll();

        $stmt->closeCursor();

        return $result;
    }

    public function Execute($sql, $arguments, $parameterTypes)
    {
        if ($this->IsConnected() !== true)
        {
            return -1;
        }

        $stmt = false;

        try
        {
            $stmt = $this->pdo->prepare($sql);
        }
        catch (PDOException $ex)
        {
            return -2;
        }

        if ($stmt == false)
        {
            return -3;
        }

        if (is_array($arguments) !== true)
        {
            return -4;
        }

        $argumentsCount = count($arguments);

        if ($argumentsCount <= 0)
        {
            return -5;
        }

        if (is_array($parameterTypes) !== true)
        {
            return -6;
        }

        $parameterTypesCount = count($parameterTypes);

        if ($parameterTypesCount <= 0)
        {
            return -7;
        }

        if ($argumentsCount != $parameterTypesCount)
        {
            return -8;
        }

        for ($i = 0; $i < $argumentsCount; $i++)
        {
            if ($stmt->bindParam($i+1, $arguments[$i], $parameterTypes[$i]) == false)
            {
                return -9;
            }
        }

        if (@$stmt->execute() == false)
        {
            if (isset($this->pdo->errorInfo()[2]) === true)
            {
                $this->lastErrorMessage = $this->pdo->errorInfo()[2];
            }

            return -10;
        }

        $stmt->closeCursor();

        return true;
    }

    /**
     * @attention This overloaded method should be used only, if the argument
     *     for \p $sql is a hard-coded string from a PHP-file.
     * @see Execute($sql, $arguments, $parameterTypes)
     */
    public function ExecuteUnsecure($sql)
    {
        if ($this->IsConnected() !== true)
        {
            return -1;
        }

        $result = $this->pdo->query($sql);

        if ($result == false)
        {
            if (isset($this->pdo->errorInfo()[2]) === true)
            {
                $this->lastErrorMessage = $this->pdo->errorInfo()[2];
            }

            return false;
        }

        if (get_class($result) != "PDOStatement")
        {
            if (isset($this->pdo->errorInfo()[2]) === true)
            {
                $this->lastErrorMessage = $this->pdo->errorInfo()[2];
            }

            return false;
        }

        return true;
    }

    public function Insert($sql, $arguments, $parameterTypes)
    {
        if ($this->IsConnected() !== true)
        {
            return -1;
        }

        $result = $this->Execute($sql, $arguments, $parameterTypes);

        if ($result !== true)
        {
            return $result;
        }

        $result = $this->pdo->lastInsertId();

        if (is_numeric($result) === true)
        {
            if ($result == 0)
            {
                return true;
            }

            if ($result > 0)
            {
                return $result;
            }
        }

        return -3;
    }

    /**
     * @attention This overloaded method should be used only, if the argument
     *     for \p $sql is a hard-coded string from a PHP-file.
     * @see Insert($sql, $arguments, $parameterTypes)
     */
    public function InsertUnsecure($sql)
    {
        if ($this->IsConnected() !== true)
        {
            return -1;
        }

        $result = $this->ExecuteUnsecure($sql);

        if ($result !== true)
        {
            return $result;
        }

        $result = $this->pdo->lastInsertId();

        if (is_numeric($result) === true)
        {
            if ($result >= 0)
            {
                return $result;
            }
        }

        return -3;
    }

    public function BeginTransaction()
    {
        if ($this->IsConnected() !== true)
        {
            return -1;
        }

        if ($this->pdo->beginTransaction() === true)
        {
            return true;
        }

        return false;
    }

    public function CommitTransaction()
    {
        if ($this->IsConnected() !== true)
        {
            return -1;
        }

        if ($this->pdo->commit() === true)
        {
            return true;
        }

        return false;
    }

    public function RollbackTransaction()
    {
        if ($this->IsConnected() !== true)
        {
            return -1;
        }

        if ($this->pdo->rollBack() === true)
        {
            return true;
        }

        return false;
    }

    public function GetLastErrorMessage()
    {
        return $this->lastErrorMessage;
    }

    public function ClearLastErrorMessage()
    {
        $this->lastErrorMessage = "";
    }

    private static $instance = NULL;

    private $pdo;
    private $prefix;

    private $lastErrorMessage;
}



?>
