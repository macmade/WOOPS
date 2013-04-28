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

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * Event object
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Core.Event
 */
class Woops_Core_Event extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The event type
     */
    protected $_type      = 0;
    
    /**
     * The event type
     */
    protected $_target    = NULL;
    
    /**
     * Whether the event should be stopped propagating
     */
    protected $_isStopped = false;
    
    /**
     * Class constructor
     * 
     * @param   int                 The event type
     * @param   Woops_Core_Object   The target object
     * @return  void
     */
    public function __construct( $type, Woops_Core_Object $target )
    {
        $this->_type   = ( int )$type;
        $this->_target = $target;
    }
    
    /**
     * Gets the event type
     * 
     * @return  int     The event type
     */
    public function getType()
    {
        return $this->_type;
    }
    
    /**
     * Gets the event target object
     * 
     * @return  Woops_Core_Object   The event target object
     */
    public function getTarget()
    {
        return $this->_target;
    }
    
    /**
     * Stops the propagation of the event
     * 
     * @return  void
     */
    public function stopPropagation()
    {
        $this->_isStopped = true;
    }
    
    /**
     * Checks if the event is propagating
     * 
     * @return  boolean True if the event is propagating, false if it's not, or if it should stop propagating
     */
    public function isPropagating()
    {
        return ( $this->_isStopped ) ? false : true;
    }
}
