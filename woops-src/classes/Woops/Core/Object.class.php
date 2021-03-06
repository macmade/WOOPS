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

# $Id: Informations.class.php 434 2009-02-24 15:19:13Z macmade $

/**
 * Base class for the WOOPS objects
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Core
 */
abstract class Woops_Core_Object extends Woops_Core_Informations
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The object's hash
     */
    private $_hash = '';
    
    /**
     * Object cloning
     * 
     * @return  void
     */
    public function __clone()
    {
        $this->_hash = spl_object_hash( $this );
    }
    
    /**
     * Returns a string representation of the object
     * 
     * @return  string  The string representation of the object
     */
    public function __toString()
    {
        return '[object ' . get_class( $this ) . ']';
    }
    
    /**
     * Gets the object's hash
     * 
     * @return  string  The object's hash
     */
    final public function getObjectHash()
    {
        // Checks if the object's hash has already been computed
        if( !$this->_hash ) {
            
            // Computes the object's hash
            $this->_hash = spl_object_hash( $this );
        }
        
        // Returns the object's hash
        return $this->_hash;
    }
}
