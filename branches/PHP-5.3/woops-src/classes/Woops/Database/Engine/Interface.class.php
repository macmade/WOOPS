<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)                 #
# All rights reserved                                                          #
################################################################################

# $Id$

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Database\Engine;

/**
 * Interface for the database engine classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Database.Engine
 */
interface Interface extends \Woops\Core\Singleton\Interface
{
    /**
     * 
     */
    public function getAvailableDrivers();
    
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
    public function affectedRows();
    
    /**
     * 
     */
    public function query( $sql );
    
    /**
     * 
     */
    public function quote( $str );
    
    /**
     * 
     */
    public function rowCount( $res );
    
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
