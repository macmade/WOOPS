<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * PDO database engine
 * 
 * The goal of the class is to provide WOOPS with the functionnalities of
 * PDO (PHP Data Object).
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.Pdo.Database
 */
final class Woops_Mod_Pdo_Database_Engine implements Woops_Database_Engine_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The PDO object for the WOOPS database
     */
    private $_pdo             = NULL;
    
    /**
     * The last PDO statement
     */
    private $_lastStatement   = NULL;
    
    /**
     * The available PDO drivers
     */
    private $_drivers         = array();
    
    /**
     * The distinguised server name for the WOOPS database
     */
    private $_dsn             = '';
    
    /**
     * The WOOPS table prefix
     */
    private $_tablePrefix     = '';
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return  void
     * @throws  Woops_Mod_Pdo_Database_Engine_Exception If PDO is not available
     */
    private function __construct()
    {
        // Checks if PDO is available
        if( !class_exists( 'PDO' ) ) {
            
            // PDO is not available
            throw new Woops_Mod_Pdo_Database_Engine_Exception(
                'PDO is not available',
                Woops_Mod_Pdo_Database_Engine_Exception::EXCEPTION_NO_PDO
            );
        }
        
        // Gets the available PDO drivers
        $this->_drivers = array_flip( PDO::getAvailableDrivers() );
    }
    
    /**
     * PHP method calls overloading
     * 
     * This method will reroute all the call on this object to the PDO object.
     * 
     * @param   string                                  The name of the called method
     * @param   array                                   The arguments for the called method
     * @return  mixed                                   The result of the PDO method called
     * @throws  Woops_Mod_Pdo_Database_Engine_Exception If the called method does not exist
     */
    public function __call( $name, array $args = array() )
    {
        // Checks if the method can be called
        if( !is_callable( array( $this->_pdo, $name ) ) ) {
            
            // Called method does not exist
            throw new Woops_Mod_Pdo_Database_Engine_Exception(
                'The method \'' . $name . '\' cannot be called on the PDO object',
                Woops_Mod_Pdo_Database_Engine_Exception::EXCEPTION_BAD_METHOD
            );
        }
        
        // Creates a callback
        $callback = new Woops_Core_Callback_Helper( array( $this->_pdo, $name ) );
        
        // Invokes the callback and returns it's result
        return $callback->invoke( $args );
    }
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  void
     * @throws  Woops_Core_Singleton_Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Woops_Core_Singleton_Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            Woops_Core_Singleton_Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Mod_Pdo_Database_Engine   The unique instance of the class
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
    /**
     * Loads the database engine
     * 
     * @param   string                                  The database driver to use
     * @param   string                                  The database host
     * @param   int                                     The database port
     * @param   string                                  The name of the database to use
     * @param   string                                  The prefix for the database tables
     * @return  void
     * @throws  Woops_Mod_Pdo_Database_Engine_Exception If the requested driver is not available
     */
    public function load( $driver, $host, $port, $database, $tablePrefix )
    {
        // Checks if PDO supports database driver
        if( !isset( $this->_drivers[ $driver ] ) ) {
            
            // Error - Driver not available
            throw new Woops_Mod_Pdo_Database_Engine_Exception(
                'Driver ' . $driver . ' is not available in PDO',
                Woops_Mod_Pdo_Database_Engine_Exception::EXCEPTION_NO_PDO_DRIVER
            );
        }
        
        // Stores the table prefix
        $this->_tablePrefix = $tablePrefix;
        
        // Stores the full DSN
        $this->_dsn         = $driver . ':host=' . $host . ';port=' .  $port. ';dbname=' . $database;
    }
    
    /**
     * Database connection
     * 
     * @return  void
     * @throws  Woops_Mod_Pdo_Database_Engine_Exception If the database connection failed to be established
     */
    public function connect( $user, $pass )
    {
        // Tries to establish a PDO connection
        try {
            
            // Creates the PDO object
            $this->_pdo = new PDO( $this->_dsn, $user, $pass );
            
        } catch( Exception $e ) {
            
            // The PDO object cannot be created - Reroute the exception
            throw new Woops_Mod_Pdo_Database_Engine_Exception(
                $e->getMessage(),
                Woops_Mod_Pdo_Database_Engine_Exception::EXCEPTION_NO_CONNECTION
            );
        }
    }
    
    /**
     * Database disconnection
     * 
     * @return  void
     */
    public function disconnect()
    {
        $this->_pdo = NULL;
    }
    
    /**
     * 
     */
    public function lastInsertId()
    {
        return $this->_pdo->lastInsertId();
    }
    
    /**
     * 
     */
    public function affectedRows()
    {
        return ( is_object( $this->_lastStatement ) ) ? $this->_lastStatement->rowCount() : 0;
    }
    
    /**
     * 
     */
    public function query( $sql )
    {
        $this->_lastStatement = $this->_pdo->query( $sql );
        return $this->_lastStatement;
    }
    
    /**
     * 
     */
    public function quote( $str )
    {
        return $this->_pdo->quote( $str );
    }
    
    /**
     * 
     */
    public function rowCount( $res )
    {
        if( $res instanceof PDOStatement ) {
            
            return ( array )$res->rowCount();
        }
        
        throw new Woops_Mod_Pdo_Database_Engine_Exception(
            'Passed argument is not a valid PDO statement',
            Woops_Mod_Pdo_Database_Engine_Exception::EXCEPTION_INVALID_STATEMENT
        );
    }
    
    /**
     * 
     */
    public function fetchAssoc( $res )
    {
        if( $res instanceof PDOStatement ) {
            
            return ( array )$res->fetchObject();
        }
        
        throw new Woops_Mod_Pdo_Database_Engine_Exception(
            'Passed argument is not a valid PDO statement',
            Woops_Mod_Pdo_Database_Engine_Exception::EXCEPTION_INVALID_STATEMENT
        );
    }
    
    /**
     * 
     */
    public function fetchObject( $res )
    {
        if( $res instanceof PDOStatement ) {
            
            return $res->fetchObject();
        }
        
        throw new Woops_Mod_Pdo_Database_Engine_Exception(
            'Passed argument is not a valid PDO statement',
            Woops_Mod_Pdo_Database_Engine_Exception::EXCEPTION_INVALID_STATEMENT
        );
    }
    
    /**
     * 
     */
    public function errorCode()
    {
        return $this->_pdo->errorCode();
    }
    
    /**
     * 
     */
    public function errorMessage()
    {
        $infos = $this->_pdo->errorInfo();
        
        return ( is_array( $infos ) && isset( $infos[ 2 ] ) ) ? $infos[ 2 ] : '';
    }
    
    /**
     * 
     */
    public function getRecord( $table, $id )
    {
        // Table names are in uppercase
        $table  = strtoupper( $table );
        
        // Primary key
        $pKey   = 'id_' . strtolower( $table );
        
        // Parameters for the PDO query
        $params = array(
            ':id' => ( int )$id
        );
        
        // Prepares the PDO query
        $this->_lastStatement = $this->prepare(
            'SELECT * FROM ' . $this->_tablePrefix . $table . '
             WHERE ' . $pKey . ' = :id
             LIMIT 1'
        );
        
        // Executes the PDO query
        $this->_lastStatement->execute( $params );
        
        // Returns the record
        return $this->_lastStatement->fetchObject();
    }
    
    /**
     * 
     */
    public function getRecordsByFields( $table, array $fieldsValues, $orderBy = '' )
    {
        // Table names are in uppercase
        $table   = strtoupper( $table );
        
        // Specified ORDER BY clause
        $orderBy = ( $orderBy ) ? ' ORDER BY ' . $orderBy : '';
        
        // Primary key
        $pKey    = 'id_' . strtolower( $table );
        
        // Starts the query
        $sql     = 'SELECT * FROM ' . $this->_tablePrefix . $table . ' WHERE ';
        
        // Parameters for the PDO query
        $params = array();
        
        // Process each field to check
        foreach( $fieldsValues as $fieldName => $fieldValue ) {
            
            // Adds the parameter
            $params[ ':' . $fieldName ] = $fieldValue;
            
            // Adds the statement
            $sql .= $fieldName . ' = :' . $fieldName . ' AND ';
        }
        
        // Removes the last 'AND'
        $sql = substr( $sql, 0, -5 );
        
        // Adds the ORDER BY clause
        $sql .= $orderBy;
        
        // Prepares the PDO query
        $this->_lastStatement = $this->prepare( $sql );
        
        // Executes the PDO query
        $this->_lastStatement->execute( $params );
        
        // Storage
        $rows = array();
        
        // Process each row
        while( $row = $this->_lastStatement->fetchObject() ) {
            
            // Stores the current row
            $rows[ $row->$pKey ] = $row;
        }
        
        // Returns the rows
        return $rows;
    }
    
    /**
     * 
     */
    public function getRelatedRecords( $id, $localTable, $foreignTable, $relationTable, $orderBy = '' )
    {
        // Primary keys
        $pKeyLocal     = 'id_' . strtolower( $localTable );
        $pKeyForeign   = 'id_' . strtolower( $foreignTable );
        
        // Table names are in uppercase
        $localTable    = $this->_tablePrefix . strtoupper( $localTable );
        $foreignTable  = $this->_tablePrefix . strtoupper( $foreignTable );
        $relationTable = $this->_tablePrefix . strtoupper( $relationTable );
        
        // Starts the query
        $sql = 'SELECT DISTINCT '
             . $foreignTable
             . '.* FROM '
             . $localTable
             . ', '
             . $foreignTable
             . ', '
             . $relationTable
             . ' WHERE '
             . $localTable
             . '.'
             . $pKeyLocal
             . ' = '
             . $relationTable
             . '.'
             . $pKeyLocal
             . ' AND '
             . $foreignTable
             . '.'
             . $pKeyForeign
             . ' = '
             . $relationTable
             . '.'
             . $pKeyForeign
             . ' AND '
             . $relationTable
             . '.'
             . $pKeyLocal
             . ' = '
             . $id;
        
        // Checks for an ORDER BY clause
        if( $orderBy ) {
            
            // Adds the order by clause
            $sql .= ' ORDER BY ' . $orderBy;
        }
        
        // Prepares the PDO query
        $this->_lastStatement = $this->prepare( $sql );
        
        // Executes the PDO query
        $this->_lastStatement->execute( $params );
        
        // Storage
        $rows = array();
        
        // Process each row
        while( $row = $this->_lastStatement->fetchObject() ) {
            
            // Stores the current row
            $rows[ $row->$pKey ] = $row;
        }
        
        // Returns the rows
        return $rows;
    }
    
    /**
     * 
     */
    public function insertRecord( $table, array $values )
    {
        // Table names are in uppercase
        $table  = strtoupper( $table );
        
        // Gets the current time
        $time   = time();
        
        // Parameters for the PDO query
        $params = array(
            ':ctime' => $time,
            ':mtime' => $time
        );
        
        // SQL for the insert statement
        $sql  = 'INSERT INTO ' . $this->_tablePrefix . $table . ' SET';
        
        // Adds the creation date in the SQL query
        $sql .= ' ctime = :ctime,';
    
        // Adds the modification date in the SQL query
        $sql .= ' mtime = :mtime,';
        
        // Process each value
        foreach( $values as $fieldName => $value ) {
            
            // Adds the PDO parameter for the current value
            $params[ ':' . $fieldName ] = $value;
            
            // Adds the update statement for the current value
            $sql .= ' ' . $fieldName . ' = :' . $fieldName . ',';
        }
        
        // Removes the last comma
        $sql  = substr( $sql, 0, -1 );
        
        // Prepares the PDO query
        $this->_lastStatement = $this->prepare( $sql );
        
        // Returns the result of the query
        return $this->_lastStatement->execute( $params );
    }
    
    /**
     * 
     */
    public function updateRecord( $table, $id, array $values )
    {
        // Table names are in uppercase
        $table  = strtoupper( $table );
        
        // Primary key
        $pKey   = 'id_' . strtolower( $table );
        
        // Gets the current time
        $time   = time();
        
        // Parameters for the PDO query
        $params = array(
            ':' . $pKey => ( int )$id,
            ':mtime'    => $time
        );
        
        // SQL for the update statement
        $sql    = 'UPDATE ' . $this->_tablePrefix . $table . ' SET';
    
        // Adds the modification date in the SQL query
        $sql .= ' mtime = :mtime,';
        
        // Process each value
        foreach( $values as $fieldName => $value ) {
            
            // Adds the PDO parameter for the current value
            $params[ ':' . $fieldName ] = $value;
            
            // Adds the update statement for the current value
            $sql .= ' ' . $fieldName . ' = :' . $fieldName . ',';
        }
        
        // Removes the last comma
        $sql  = substr( $sql, 0, -1 );
        
        // Adds the where clause
        $sql .= ' WHERE ' . $pKey . ' = :' . $pKey;
        
        // Prepares the PDO query
        $this->_lastStatement = $this->prepare( $sql );
        
        // Executes the PDO query
        return $this->_lastStatement->execute( $params );
    }
    
    /**
     * 
     */
    public function deleteRecord( $table, $id, $deleteFromTable = false )
    {
        // Checks if we should really delete the record, or just set the delete flag
        if( $deleteFromTable ) {
            
            // Table names are in uppercase
            $table  = strtoupper( $table );
            
            // Primary key
            $pKey   = 'id_' . strtolower( $table );
            
            // Parameters for the PDO query
            $params = array(
                ':id' => $id
            );
            
            // SQL for the delete statement
            $sql = 'DELETE FROM ' . $table . ' WHERE ' . $pKey . ' = :id';
            
            // Prepares the PDO query
            $this->_lastStatement = $this->prepare( $sql );
            
            // Executes the PDO query
            return $this->_lastStatement->execute( $params );
        }
        
        // Just sets the delete flag
        return $this->updateRecord(
            $table,
            $id,
            array( 'deleted' => 1 )
        );
    }
    
    /**
     * 
     */
    public function removeDeletedRecords( $table )
    {
        // Table names are in uppercase
        $table  = strtoupper( $table );
        
        // Prepares the PDO query
        $query = $this->prepare(
            'DELETE FROM ' . $table . ' WHERE deleted = 1'
        );
        
        // Executes the PDO query
        $this->_lastStatement = $this->execute( $params );
        return $this->_lastStatement;
    }
}
