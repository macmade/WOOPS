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
 * SWF EnableDebugger2 tag
 * 
 * The EnableDebugger2 tag enables debugging. The Password field is encrypted by
 * using the MD5 algorithm, in the same way as the Protect tag.
 * The minimum file format version is SWF 6.
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag.Enable.Debugger
 */
class Woops_Swf_Tag_Enable_Debugger_2 extends Woops_Swf_Tag_Enable_Debugger
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF tag type
     */
    protected $_type = 0x40;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        // Skips the reserved bits
        $stream->seek( 2, Woops_Swf_Binary_Stream::SEEK_CUR );
        
        // Calls the parent method
        parent::processData( $stream );
    }
}
