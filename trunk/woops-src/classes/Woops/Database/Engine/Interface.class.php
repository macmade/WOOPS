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
 * Interface for the database engine classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Database.Engine
 */
interface Woops_Database_Engine_Interface
{
    /**
     * 
     */
    public static function getInstance();
    
    /**
     * 
     */
    public function load( $driver, $host, $port, $database, $tablePrefix );
    
    /**
     * 
     */
    public function connect( $username, $password );
    
    /**
     * 
     */
    public function disconnect();
    
    /**
     * 
     */
    public function lastInsertId();
    
    /**
     * 
     */
    public function query( $sql );
    
    /**
     * 
     */
    public function fetchAssoc( $res );
    
    /**
     * 
     */
    public function fetchObject( $res );
    
    /**
     * 
     */
    public function errorCode();
    
    /**
     * 
     */
    public function errorMessage();
    
    /**
     * 
     */
    public function getRecord( $table, $id );
    
    /**
     * 
     */
    public function getRecordsByFields( $table, array $fieldsValues, $orderBy = '' );
    
    /**
     * 
     */
    public function getRelatedRecords( $id, $localTable, $foreignTable, $relationTable, $orderBy = '' );
    
    /**
     * 
     */
    public function insertRecord( $table, array $values );
    
    /**
     * 
     */
    public function updateRecord( $table, $id, array $values );
    
    /**
     * 
     */
    public function deleteRecord( $table, $id, $deleteFromTable = false );
    
    /**
     * 
     */
    public function removeDeletedRecords( $table );
}
