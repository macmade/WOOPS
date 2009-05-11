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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Swf\Tag;

/**
 * SWF FrameLabel tag
 * 
 * The FrameLabel tag gives the specified Name to the current frame.
 * ActionGoToLabel uses this name to identify the frame.
 * The minimum file format version is SWF 3.
 * In SWF files of version 6 or later, an extension to the FrameLabel tag called
 * named anchors is available. A named anchor is a special kind of frame label
 * that, in addition to labeling a frame for seeking using ActionGoToLabel,
 * labels the frame for seeking using HTML anchor syntax.
 * The browser plug-in versions of Adobe Flash Player, in version 6 and later,
 * will inspect the URL in the browser’s Location bar for an anchor
 * specification (a trailing phrase of the form #anchorname). If an anchor
 * specification is present in the Location bar, Flash Player will begin
 * playback starting at the frame that contains a FrameLabel tag that specifies
 * a named anchor of the same name, if one exists; otherwise playback will begin
 * at Frame 1 as usual. In addition, when Flash Player arrives at a frame that
 * contains a named anchor, it will add an anchor specification with the given
 * anchor name to the URL in the browser’s Location bar.
 * This ensures that when users create a bookmark at such a time, they can later
 * return to the same point in the SWF file, subject to the granularity at which
 * named anchors are present within the file.
 * To create a named anchor, insert one additional non-null byte after the null
 * terminator of the anchor name. This is valid only for SWF 6 or later.
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Tag
 */
class FrameLabel extends \Woops\Swf\Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The SWF tag type
     */
    protected $_type        = 0x2B;
    
    /**
     * The frame label
     */
    protected $_label       = '';
    
    /**
     * The name anchor flag
     */
    protected $_namedAnchor = false;
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( \Woops\Swf\Binary\Stream $stream )
    {
        // Gets the label
        $this->_label = $stream->nullTerminatedString();
        
        // Checks if we have a named anchor
        if( !$stream->endOfStream() ) {
            
            // Sets the named anchor flag
            $this->_namedAnchor = ( boolean )$stream->unsignedChar();
        }
    }
    
    /**
     * Gets the frame label
     * 
     * @return  string  The frame label
     */
    public function getFrameLabel()
    {
        return $this->_label;
    }
    
    /**
     * Sets the frame label
     * 
     * @param   string  The frame label
     * @return  void
     */
    public function setFrameLabel( $label )
    {
        $this->_label = ( string )$label;
    }
    
    /**
     * Gets the named anchor flag
     * 
     * @return  boolean The named anchor flag
     */
    public function getNamedAnchor()
    {
        return $this->_namedAnchor;
    }
    
    /**
     * Sets the named anchor flag
     * 
     * @param   boolean The named anchor flag
     * @return  void
     */
    public function setNamedAnchor( $value )
    {
        $this->_namedAnchor = ( boolean )$value;
    }
}
