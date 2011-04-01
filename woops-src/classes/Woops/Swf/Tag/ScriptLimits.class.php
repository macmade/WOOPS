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

# $Id: Stream.class.php 637 2009-03-09 09:05:52Z macmade $

/**
 * SWF ScriptLimits tag
 * 
 * The ScriptLimits tag includes two fields that can be used to override the
 * default settings for maximum recursion depth and ActionScript time-out:
 * MaxRecursionDepth and ScriptTimeoutSeconds.
 * The MaxRecursionDepth field sets the ActionScript maximum recursion limit.
 * The default setting is 256 at the time of this writing. This default can be
 * changed to any value greater than zero (0).
 * The ScriptTimeoutSeconds field sets the maximum number of seconds the player
 * should process ActionScript before displaying a dialog box asking if the
 * script should be stopped.
 * The default value for ScriptTimeoutSeconds varies by platform and is between
 * 15 to 20 seconds. This default value is subject to change.
 * The minimum file format version is SWF 7.
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag
 */
class Woops_Swf_Tag_ScriptLimits extends Woops_Swf_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF tag type
     */
    protected $_type = 0x41;
    
    /**
     * The maximum recursion depth
     */
    protected $_maxRecursionDepth    = 256;
    
    /**
     * The maximum ActionScript processing time before script stuck dialog box displays
     */
    protected $_scriptTimeoutSeconds = 20;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        $this->_maxRecursionDepth    = $stream->littleEndianUnsignedShort();
        $this->_scriptTimeoutSeconds = $stream->littleEndianUnsignedShort();
    }
    
    /**
     * Gets the maximum recursion depth
     * 
     * @return  int     The maximum recursion depth
     */
    public function getMaxRecursionDepth()
    {
        return $this->_maxRecursionDepth;
    }
    
    /**
     * Gets the maximum ActionScript processing time before script stuck dialog box displays
     * 
     * @return  int     The maximum ActionScript processing time before script stuck dialog box displays
     */
    public function getScriptTimeoutSeconds()
    {
        return $this->_scriptTimeoutSeconds;
    }
    
    /**
     * Sets the maximum recursion depth
     * 
     * @param   int     The maximum recursion depth (0 - 0xFFFF)
     * @return  void
     */
    public function setMaxRecursionDepth( $value )
    {
        $this->_maxRecursionDepth = self::$_number->inRange( $value, 0, 0xFFFF );
    }
    
    /**
     * Sets the maximum ActionScript processing time before script stuck dialog box displays
     * 
     * @param   int     The maximum ActionScript processing time before script stuck dialog box displays (0 - 0xFFFF)
     * @return  void
     */
    public function setScriptTimeoutSeconds( $value )
    {
        $this->_scriptTimeoutSeconds = self::$_number->inRange( $value, 0, 0xFFFF );
    }
}
