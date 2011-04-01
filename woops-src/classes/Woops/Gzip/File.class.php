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

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * GZIP file
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Gzip
 */
class Woops_Gzip_File extends Woops_Core_Object implements Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The GZIP members
     */
    protected $_members     = array();
    
    /**
     * The SPL iterator position
     */
    protected $_iteratorPos = 0;
    
    /**
     * Gets the current member object (SPL Iterator method)
     * 
     * @return  Woops_Gzip_Member   The current member object
     */
    public function current()
    {
        return $this->_members[ $this->_iteratorPos ];
    }
    
    /**
     * Moves to the next member object (SPL Iterator method)
     * 
     * @return  void
     */
    public function next()
    {
        $this->_iteratorPos++;
    }
    
    /**
     * Gets the index of the current member object (SPL Iterator method)
     * 
     * @return  int     The index of the current GZIP member
     */
    public function key()
    {
        return $this->_iteratorPos;
    }
    
    /**
     * Checks if there is a next member object (SPL Iterator method)
     * 
     * @return  boolean True if there is a next GZIP member, otherwise false
     */
    public function valid()
    {
        return $this->_iteratorPos < count( $this->_tags );
    }
    
    /**
     * Rewinds the SPL Iterator pointer (SPL Iterator method)
     * 
     * @return  void
     */
    public function rewind()
    {
        $this->_iteratorPos = 0;
    }
    
    /**
     * Adds a member to the file
     * 
     * @param   Woops_Gzip_Member   The GZIP member object
     * @return  void
     */
    public function addMember( Woops_Gzip_Member $member )
    {
        $this->_members[] = $member;
    }
    
    /**
     * Removes a member from the file
     * 
     * @param   int     The index of the GZIP member
     * @return  void
     */
    public function removeMember( $index )
    {
        unset( $this->_members[ ( int )$index ] );
    }
}
