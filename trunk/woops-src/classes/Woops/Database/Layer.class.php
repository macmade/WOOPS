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
 * WOOPS database layer class
 * 
 * The goal of the class is to provide WOOPS with the functionnalities of
 * PDO (PHP Data Object).
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Database
 */
final class Woops_Database_Layer implements Woops_Core_Singleton_Interface
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
     * The WOOPS cpnfiguration object
     */
    private $_conf            = NULL;
    
    /**
     * The PDO object for the WOOPS database
     */
    private $_pdo             = NULL;
    
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
     * @return  NULL
     * @throws  Woops_Database_Layer_Exception  If the PDO class is not available
     * @throws  Woops_Database_Layer_Exception  If the PDO object cannot be created
     */
    private function __construct()
    {
        // Gets the instance of the configuration object
        $this->_conf = Woops_Core_Config_Getter::getInstance();
        
        // Checks if PDO is available
        if( !class_exists( 'PDO' ) ) {
            
            // PDO is not available
            throw new Woops_Database_Layer_Exception(
                'PDO is not available',
                Woops_Database_Layer_Exception::EXCEPTION_NO_CONNECTION
            );
        }
        
        // Gets the available PDO drivers
        $this->_drivers = array_flip( PDO::getAvailableDrivers() );
        
        // Sets the default connection infos
        $driver = $this->_conf->getVar( 'database', 'driver' );
        $user   = $this->_conf->getVar( 'database', 'user' );
        $pass   = $this->_conf->getVar( 'database', 'password' );
        $host   = $this->_conf->getVar( 'database', 'host' );
        $port   = $this->_conf->getVar( 'database', 'port' );
        $db     = $this->_conf->getVar( 'database', 'name' );
        $prefix = $this->_conf->getVar( 'database', 'tablePrefix' );
        
        // Security - Removes some configuration variables
        $this->_conf->deleteVar( 'database', 'user' );
        $this->_conf->deleteVar( 'database', 'password' );
        
        // Sets the WOOPS table prefix
        $this->_tablePrefix = ( $prefix ) ? ( string )$prefix : '';
        
        // Checks if PDO supports the Drupal database driver
        if( !isset( $this->_drivers[ $driver ] ) ) {
            
            // Error - Driver not available
            throw new Woops_Database_Layer_Exception(
                'Driver ' . $driver . ' is not available in PDO',
                Woops_Database_Layer_Exception::EXCEPTION_NO_PDO_DRIVER
            );
        }
        
        // Stores the full DSN
        $this->_dsn = $driver . ':host=' . $host . ';port=' .  $port. ';dbname=' . $db;
        
        try {
            
            // Creates the PDO object
            $this->_pdo = new PDO( $this->_dsn, $user, $pass );
            
        } catch( Exception $e ) {
            
            // The PDO object cannot be created - Reroute the exception
            throw new Woops_Database_Layer_Exception(
                $e->getMessage(),
                Woops_Database_Layer_Exception::EXCEPTION_NO_CONNECTION
            );
        }
    }
    
    /**
     * Class destructor
     * 
     * This method will close the PDO connection to the TYPO3 database.
     * 
     * @return  NULL
     */
    public function __destruct()
    {
        $this->_pdo = NULL;
    }
    
    /**
     * PHP method calls overloading
     * 
     * This method will reroute all the call on this object to the PDO object.
     * 
     * @param   string                          The name of the called method
     * @param   array                           The arguments for the called method
     * @return  mixed                           The result of the PDO method called
     * @throws  Woops_Database_Layer_Exception  If the called method does not exist
     */
    public function __call( $name, array $args = array() )
    {
        // Checks if the method can be called
        if( !is_callable( array( $this->_pdo, $name ) ) ) {
            
            // Called method does not exist
            throw new Woops_Database_Layer_Exception(
                'The method \'' . $name . '\' cannot be called on the PDO object',
                Woops_Database_Layer_Exception::EXCEPTION_BAD_METHOD
            );
        }
        
        // Gets the number of arguments
        $argCount = count( $args );
        
        // We won't use call_user_func_array, as it cannot return references
        switch( $argCount ) {
            
            case 1:
                
                return $this->_pdo->$name( $args[ 0 ] );
                break;
            
            case 2:
                
                return $this->_pdo->$name( $args[ 0 ], $args[ 1 ] );
                break;
            
            case 3:
                
                return $this->_pdo->$name( $args[ 0 ], $args[ 1 ], $args[ 2 ] );
                break;
            
            case 4:
                
                return $this->_pdo->$name( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ] );
                break;
                break;
            
            case 5:
                
                return $this->_pdo->$name( $args[ 0 ], $args[ 1 ], $args[ 2 ], $args[ 3 ], $args[ 4 ] );
                break;
            
            default:
                
                return $this->_pdo->$name();
                break;
        }
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
     * @return  Woops_Database_Layer    The unique instance of the class
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
        $query = $this->prepare(
            'SELECT * FROM ' . $this->_tablePrefix . $table . '
             WHERE ' . $pKey . ' = :id
             LIMIT 1'
        );
        
        // Executes the PDO query
        $query->execute( $params );
        
        // Returns the record
        return $query->fetchObject();
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
        $query = $this->prepare( $sql );
        
        // Executes the PDO query
        $query->execute( $params );
        
        // Storage
        $rows = array();
        
        // Process each row
        while( $row = $query->fetchObject() ) {
            
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
        $query = $this->prepare( $sql );
        
        // Executes the PDO query
        $query->execute( $params );
        
        // Storage
        $rows = array();
        
        // Process each row
        while( $row = $query->fetchObject() ) {
            
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
        $query = $this->prepare( $sql );
        
        // Executes the PDO query
        $query->execute( $params );
        
        // Returns the insert ID
        return $this->lastInsertId();
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
        $query = $this->prepare( $sql );
        
        // Executes the PDO query
        return $query->execute( $params );
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
            
            // SQL for the update statement
            $sql = 'DELETE FROM ' . $table . ' WHERE ' . $pKey . ' = :id';
            
            // Prepares the PDO query
            $query = $this->prepare( $sql );
            
            // Executes the PDO query
            return $this->execute( $params );
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
        return $this->execute( $params );
    }
}
