<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * ADODB database engine
 * 
 * The goal of the class is to provide WOOPS with the functionnalities of
 * ADODB.
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mod.Adodb.Database
 */
final class Woops_Mod_Adodb_Database_Engine extends Woops_Core_Object implements Woops_Database_Engine_Interface
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
     * The ADODB object for the WOOPS database
     */
    private $_adodb           = NULL;
    
    /**
     * The environment object
     */
    private $_env             = NULL;
    
    /**
     * 
     */
    private $_errorReporting  = NULL;
    
    /**
     * 
     */
    private $_oracle          = false;
    
    /**
     * 
     */
    private $_server          = '';
    
    /**
     * 
     */
    private $_database        = '';
    
    /**
     * The WOOPS table prefix
     */
    private $_tablePrefix     = '';
    
    /**
     * 
     */
    private $_hasDrivers      = false;
    
    /**
     * 
     */
    private $_drivers         = array();
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return  void
     */
    private function __construct()
    {
        // Gets the instance of the environment class
        $this->_env            = Woops_Core_Env_Getter::getInstance();
        
        // Gets the instance of the error handler class
        $this->_errorReporting = Woops_Core_Error_Handler::getInstance();
        
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        require_once( $this->_env->getPath( 'woops-mod://Adodb/resources/php/adodb5/adodb.inc.php' ) );
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        // Creates a directory iterator to find the available drivers
        $iterator = new DirectoryIterator( $this->_env->getPath( 'woops-mod://Adodb/resources/php/adodb5/drivers/' ) );
        
        // Process each file
        foreach( $iterator as $file ) {
            
            // Checks if the file is an ADODB driver
            if( substr( $file->getFileName(), -8 ) === '.inc.php' ) {
                
                // Gets the driver name
                $driver = substr( $file->getFileName(), 6, -8 );
                
                // Stores the driver
                $this->_drivers[ $driver ] = true;
            }
        }
        
        // Stores additionnal drivers
        $this->_drivers[ 'ifx' ]    = true;
        $this->_drivers[ 'maxsql' ] = true;
        $this->_drivers[ 'pgsql' ]  = true;
    }
    
    /**
     * PHP method calls overloading
     * 
     * This method will reroute all the call on this object to the ADODB object.
     * 
     * @param   string                                      The name of the called method
     * @param   array                                       The arguments for the called method
     * @return  mixed                                       The result of the ADODB method called
     * @throws  Woops_Mod_Adodb_Database_Engine_Exception   If the called method does not exist
     */
    public function __call( $name, array $args = array() )
    {
        // Checks if the method can be called
        if( !is_callable( array( $this->_adodb, $name ) ) ) {
            
            // Called method does not exist
            throw new Woops_Mod_Adodb_Database_Engine_Exception(
                'The method \'' . $name . '\' cannot be called on the ADODB object',
                Woops_Mod_Adodb_Database_Engine_Exception::EXCEPTION_BAD_METHOD
            );
        }
        
        // Creates a callback
        $callback = new Woops_Core_Callback( array( $this->_adodb, $name ) );
        
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        // Invokes the callback and returns it's result
        $ret = $callback->invoke( $args );
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        return $ret;
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
     * @return  Woops_Mod_Adodb_Database_Engine The unique instance of the class
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
     * Gets the available database drivers
     * 
     * @return  array   An array with the available database drivers as keys
     */
    public function getAvailableDrivers()
    {
        return $this->_drivers;
    }
    
    /**
     * Loads the database engine
     * 
     * @param   string                                      The database driver to use
     * @param   string                                      The database host
     * @param   int                                         The database port
     * @param   string                                      The name of the database to use
     * @param   string                                      The prefix for the database tables
     * @return  void
     * @throws  Woops_Mod_Adodb_Database_Engine_Exception   If the requested driver is not available
     */
    public function load( $driver, $host, $port, $database, $tablePrefix )
    {
        // Checks if ADODB supports the database driver
        if( !isset( $this->_drivers[ $driver ] ) ) {
            
            // Error - Driver not available
            throw new Woops_Mod_Adodb_Database_Engine_Exception(
                'Driver ' . $driver . ' is not available in ADODB',
                Woops_Mod_Adodb_Database_Engine_Exception::EXCEPTION_NO_ADODB_DRIVER
            );
        }
        
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        // Creates the ADODB object
        $db = ADONewConnection( $driver );
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        // Checks if ADODB supports database driver
        if( !$db ) {
            
            // Error - Driver not available
            throw new Woops_Mod_Adodb_Database_Engine_Exception(
                'Error creating the ADODB object with driver ' . $driver,
                Woops_Mod_Adodb_Database_Engine_Exception::EXCEPTION_ADODB_DRIVER_ERROR
            );
        }
        
        // Are we using an Oracle database?
        if( $driver === 'oci8' ) {
            
            // Yes, we'll have to deal with Oracle-like parameters
            $this->_oracle = true;
        }
        
        // Stores the ADODB object
        $this->_adodb       = $db;
        
        // Stores the table prefix
        $this->_tablePrefix = $tablePrefix;
        
        // Stores the connection parameters
        $this->_server      = $host;
        $this->_database    = $database;
        
        // Checks for a port number
        if( $port ) {
            
            // Adds the database port
            $this->_server .= ':' . $port;
        }
    }
    
    /**
     * Database connection
     * 
     * @return  void
     * @throws  Woops_Mod_Adodb_Database_Engine_Exception   If the database connection failed to be established
     */
    public function connect( $user, $pass )
    {
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        // Tries to establish an ADODB connection
        if( !$this->_adodb->Connect( $this->_server, $user, $pass, $this->_database ) ) {
            
            // The ADODB object cannot be created - Reroute the exception
            throw new Woops_Mod_Adodb_Database_Engine_Exception(
                'Impossible to establish an ADODB connection',
                Woops_Mod_Adodb_Database_Engine_Exception::EXCEPTION_NO_CONNECTION
            );
        }
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
    }
    
    /**
     * Database disconnection
     * 
     * @return  void
     */
    public function disconnect()
    {
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        $this->_adodb->Close();
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
    }
    
    /**
     * 
     */
    public function lastInsertId()
    {
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        $id = $this->_adodb->Insert_ID();
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        return $id;
    }
    
    /**
     * 
     */
    public function affectedRows()
    {
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        $count = $this->_adodb->Affected_Rows();
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        return $count;
    }
    
    /**
     * 
     */
    public function query( $sql )
    {
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        // Executes the query
        $res = $this->Execute( $sql );
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        return $res;
    }
    
    /**
     * 
     */
    public function quote( $str )
    {
        return $this->_adodb->qstr( $str );
    }
    
    /**
     * 
     */
    public function rowCount( $res )
    {
        if( $res instanceof ADORecordSet ) {
            
            // Not sure ADODB is completely error free
            $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
            
            $count = $res->RecordCount();
            
            // Resets the error reporting
            $this->_errorReporting->resetErrorReporting();
            
            return $count;
        }
        
        throw new Woops_Mod_Adodb_Database_Engine_Exception(
            'Passed argument is not a valid ADODB record set',
            Woops_Mod_Adodb_Database_Engine_Exception::EXCEPTION_INVALID_RECORD_SET
        );
    }
    
    /**
     * 
     */
    public function fetchAssoc( $res )
    {
        if( $res instanceof ADORecordSet ) {
            
            // Not sure ADODB is completely error free
            $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
            
            if( $res->EOF ) {
                
                return false;
            }
            
            $res->SetFetchMode( ADODB_FETCH_ASSOC );
            
            $rows = array();
            
            foreach( $res->fields as $key => $value ) {
                
                if( is_string( $key ) ) {
                    
                    $rows[ $key ] = $value;
                }
            }
            
            $res->MoveNext();
            
            // Resets the error reporting
            $this->_errorReporting->resetErrorReporting();
            
            return $rows;
        }
        
        throw new Woops_Mod_Adodb_Database_Engine_Exception(
            'Passed argument is not a valid ADODB record set',
            Woops_Mod_Adodb_Database_Engine_Exception::EXCEPTION_INVALID_RECORD_SET
        );
    }
    
    /**
     * 
     */
    public function fetchObject( $res )
    {
        if( $res instanceof ADORecordSet ) {
            
            // Not sure ADODB is completely error free
            $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
            
            if( $res->EOF ) {
                
                return false;
            }
            
            $this->_adodb->SetFetchMode( ADODB_FETCH_ASSOC );
            
            $rows = new StdClass();
            
            foreach( $res->fields as $key => $value ) {
                
                if( is_string( $key ) ) {
                    
                    $rows->$key = $value;
                }
            }
            
            $res->MoveNext();
            
            // Resets the error reporting
            $this->_errorReporting->resetErrorReporting();
            
            return $rows;
        }
        
        throw new Woops_Mod_Adodb_Database_Engine_Exception(
            'Passed argument is not a valid ADODB record set',
            Woops_Mod_Adodb_Database_Engine_Exception::EXCEPTION_INVALID_RECORD_SET
        );
    }
    
    /**
     * 
     */
    public function errorCode()
    {
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        $code = $this->_adodb->ErrorNo();
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        return $code;
    }
    
    /**
     * 
     */
    public function errorMessage()
    {
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        $msg = $this->_adodb->ErrorMsg();
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        return $msg;
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
        
        // Oracle support
        if( $this->_oracle ) {
            
            // Parameters for the ADODB query
            $params = array(
                'id' => ( int )$id
            );
            
            // SQL statement
            $sql = 'SELECT * FROM ' . $this->_tablePrefix . $table
                 . '    WHERE ' . $pKey . ' = :id'
                 . '    LIMIT 1';
            
        } else {
            
            // Parameters for the ADODB query
            $params = array(
                ( int )$id
            );
            
            // SQL statement
            $sql = 'SELECT * FROM ' . $this->_tablePrefix . $table
                 . '    WHERE ' . $pKey . ' = ?'
                 . '    LIMIT 1';
        }
        
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        // Executes the ADODB query
        $query  = $this->Execute( $sql, $params );
        
        // Gets the record
        $record = ( $query ) ? $this->fetchObject( $query ) : false;
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        // Returns the record
        return $record;
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
        
        // Parameters for the ADODB query
        $params = array();
        
        // Oracle support
        if( $this->_oracle ) {
            
            // Process each field to check
            foreach( $fieldsValues as $fieldName => $fieldValue ) {
                
                // Adds the parameter
                $params[ $fieldName ] = $fieldValue;
                
                // Adds the statement
                $sql .= $fieldName . ' = :' . $fieldValue . ' AND ';
            }
            
        } else {
            
            // Process each field to check
            foreach( $fieldsValues as $fieldName => $fieldValue ) {
                
                // Adds the parameter
                $params[] = $fieldValue;
                
                // Adds the statement
                $sql .= $fieldName . ' = ? AND ';
            }
        }
        
        // Removes the last 'AND'
        $sql = substr( $sql, 0, -5 );
        
        // Adds the ORDER BY clause
        $sql .= $orderBy;
        
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        // Executes the ADODB query
        $query = $this->Execute( $sql, $params );
        
        // Storage
        $rows = array();
        
        // Checks the query result
        if( $query ) {
            
            // Process each row
            while( $row = $this->fetchObject( $query ) ) {
                
                // Stores the current row
                $rows[ $row->$pKey ] = $row;
            }
        }
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
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
        
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        // Executes the ADODB query
        $query = $this->Execute( $sql, array() );
        
        // Storage
        $rows = array();
        
        // Process each row
        while( $row = $this->fetchObject( $query ) ) {
            
            // Stores the current row
            $rows[ $row->$pKey ] = $row;
        }
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
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
        
        // Oracle support
        if( $this->_oracle ) {
            
            // Parameters for the PDO query
            $params = array(
                'ctime' => $time,
                'mtime' => $time
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
                $params[ $fieldName ] = $value;
                
                // Adds the update statement for the current value
                $sql .= ' ' . $fieldName . ' = :' . $fieldName . ',';
            }
            
        } else {
            
            // Parameters for the PDO query
            $params = array(
                $time,
                $time
            );
            
            // SQL for the insert statement
            $sql  = 'INSERT INTO ' . $this->_tablePrefix . $table . ' SET';
            
            // Adds the creation date in the SQL query
            $sql .= ' ctime = ?,';
        
            // Adds the modification date in the SQL query
            $sql .= ' mtime = ?,';
            
            // Process each value
            foreach( $values as $fieldName => $value ) {
                
                // Adds the PDO parameter for the current value
                $params[] = $value;
                
                // Adds the update statement for the current value
                $sql .= ' ' . $fieldName . ' = ?,';
            }
        }
        
        // Removes the last comma
        $sql  = substr( $sql, 0, -1 );
        
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        // Executes the ADODB query
        $query = $this->Execute( $sql, $params );
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        // Returns the result of the query
        return $query;
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
        
        // Oracle support
        if( $this->_oracle ) {
            
            // Parameters for the ADODB query
            $params = array(
                $pKey => ( int )$id,
                'mtime'    => $time
            );
            
            // SQL for the update statement
            $sql    = 'UPDATE ' . $this->_tablePrefix . $table . ' SET';
        
            // Adds the modification date in the SQL query
            $sql .= ' mtime = :mtime,';
            
            // Process each value
            foreach( $values as $fieldName => $value ) {
                
                // Adds the ADODB parameter for the current value
                $params[ $fieldName ] = $value;
                
                // Adds the update statement for the current value
                $sql .= ' ' . $fieldName . ' = :' . $fieldName . ',';
            }
            
            // Removes the last comma
            $sql  = substr( $sql, 0, -1 );
            
            // Adds the where clause
            $sql .= ' WHERE ' . $pKey . ' = :' . $pKey;
            
        } else {
            
            // Parameters for the ADODB query
            $params = array(
                $time
            );
            
            // SQL for the update statement
            $sql    = 'UPDATE ' . $this->_tablePrefix . $table . ' SET';
        
            // Adds the modification date in the SQL query
            $sql .= ' mtime = ?,';
            
            // Process each value
            foreach( $values as $fieldName => $value ) {
                
                // Adds the ADODB parameter for the current value
                $params[] = $value;
                
                // Adds the update statement for the current value
                $sql .= ' ' . $fieldName . ' = ?,';
            }
            
            // Removes the last comma
            $sql  = substr( $sql, 0, -1 );
            
            // Adds the primary key parameter
            $params[] = ( int )$id;
            
            // Adds the where clause
            $sql .= ' WHERE ' . $pKey . ' = ?';
        }
        
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        // Executes the ADODB query
        $query = $this->Execute( $sql, $params );
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        // Returns the query result
        return $query;
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
            
            // Oracle support
            if( $this->_oracle ) {
                
                // Parameters for the ADODB query
                $params = array(
                    'id' => $id
                );
                
                // SQL for the delete statement
                $sql = 'DELETE FROM ' . $table . ' WHERE ' . $pKey . ' = :id';
                
            } else {
                
                // Parameters for the ADODB query
                $params = array(
                    $id
                );
                
                // SQL for the delete statement
                $sql = 'DELETE FROM ' . $table . ' WHERE ' . $pKey . ' = ?';
                
            }
            
            // Not sure ADODB is completely error free
            $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
            
            // Prepares the ADODB query
            $query = $this->Execute( $sql, $params );
            
            // Resets the error reporting
            $this->_errorReporting->resetErrorReporting();
            
            // Returns the query result
            return $query;
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
        
        // Not sure ADODB is completely error free
        $this->_errorReporting->disableErrorReporting( E_NOTICE | E_STRICT );
        
        // Executes the ADODB query
        $ret = $this->Execute(
            'DELETE FROM ' . $table . ' WHERE deleted = 1',
            array()
        );
        
        // Resets the error reporting
        $this->_errorReporting->resetErrorReporting();
        
        // Returns the query result
        return $ret;
    }
}
