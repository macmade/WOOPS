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

# $Id: Stream.class.php 637 2009-03-09 09:05:52Z macmade $

/**
 * SWF EnableDebugger tag
 * 
 * The EnableDebugger tag enables debugging. The password in the EnableDebugger
 * tag is encrypted by using the MD5 algorithm, in the same way as the Protect
 * tag.
 * The EnableDebugger tag was deprecated in SWF 6; Flash Player 6 or later
 * ignores this tag because the format of the debugging information required in
 * the ActionScript debugger was changed in SWF 6. In SWF 6 or later, use the
 * EnableDebugger2 tag instead.
 * The minimum and maximum file format version is SWF 5. 
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Swf.Tag.Enable
 */
class Woops_Swf_Tag_Enable_Debugger extends Woops_Swf_Tag_Protect
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF tag type
     */
    protected $_type = 0x3A;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        $this->_password = $stream->nullTerminatedString();
    }
}
