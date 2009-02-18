<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * ADODB database engine
 * 
 * The goal of the class is to provide WOOPS with the functionnalities of
 * ADODB.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.Adodb.Database
 */
final class Woops_Mod_Adodb_Database_Engine implements Woops_Database_Engine_Interface, Woops_Core_Singleton_Interface
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
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return  NULL
     */
    private function __construct()
    {
        require_once( Woops_Core_Env_Getter::getInstance()->getPath( 'woops-mod://Adodb/ressources/adodb5/adodb.inc.php' ) );
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
        $callback = new Woops_Core_Callback_Helper( array( $this->_adodb, $name ) );
        
        // Invokes the callback and returns it's result
        return $callback->invoke( $args );
    }
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  NULL
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
     * Loads the database engine
     * 
     * @param   string                                      The database driver to use
     * @param   string                                      The database host
     * @param   int                                         The database port
     * @param   string                                      The name of the database to use
     * @param   string                                      The prefix for the database tables
     * @return  NULL
     * @throws  Woops_Mod_Adodb_Database_Engine_Exception   If the requested driver is not available
     */
    public function load( $driver, $host, $port, $database, $tablePrefix )
    {
        // Creates the ADODB object
        $db = ADONewConnection( $driver );
        
        // Checks if ADODB supports database driver
        if( !$db ) {
            
            // Error - Driver not available
            throw new Woops_Mod_Adodb_Database_Engine_Exception(
                'Driver ' . $driver . ' is not available in ADODB',
                Woops_Mod_Adodb_Database_Engine_Exception::EXCEPTION_NO_ADODB_DRIVER
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
        $this->_server      = $host . ':' . $port;
        $this->_database    = $database;
    }
    
    /**
     * Database connection
     * 
     * @return  NULL
     * @throws  Woops_Mod_Adodb_Database_Engine_Exception   If the database connection failed to be established
     */
    public function connect( $user, $pass )
    {
        // Tries to establish an ADODB connection
        if( !$this->_adodb->Connect( $this->_server, $user, $pass, $this->_database ) ) {
            
            // The ADODB object cannot be created - Reroute the exception
            throw new Woops_Mod_Adodb_Database_Engine_Exception(
                $e->getMessage(),
                Woops_Mod_Adodb_Database_Engine_Exception::EXCEPTION_NO_CONNECTION
            );
        }
    }
    
    /**
     * Database disconnection
     * 
     * @return  NULL
     */
    public function disconnect()
    {
        $this->_adodb->Close();
    }
    
    /**
     * 
     */
    public function lastInsertId()
    {
        return $this->_adodb->Insert_ID();
    }
    
    /**
     * 
     */
    public function fetchAssoc( $res )
    {
        if( $res instanceof ADORecordSet ) {
            
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
        return $this->_adodb->ErrorNo();
    }
    
    /**
     * 
     */
    public function errorMessage()
    {
        return $this->_adodb->ErrorMsg();
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
        
        // Executes the ADODB query
        $query = $this->Execute( $sql, $params );
        
        // Returns the record
        return ( $query ) ? $this->fetchObject( $query ) : false;
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
        
        // Returns the rows
        return $rows;
    }
    
    /**
     * 
     */
    public function getRelatedRecords( $id, $localTable, $foreignTable, $relationTable, $orderBy = '' )
    {}
    
    /**
     * 
     */
    public function insertRecord( $table, array $values )
    {}
    
    /**
     * 
     */
    public function updateRecord( $table, $id, array $values )
    {}
    
    /**
     * 
     */
    public function deleteRecord( $table, $id, $deleteFromTable = false )
    {}
    
    /**
     * 
     */
    public function removeDeletedRecords( $table )
    {}
}
