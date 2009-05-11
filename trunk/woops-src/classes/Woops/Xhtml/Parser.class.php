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
namespace Woops\Xhtml;

/**
 * XHTML parser class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Xhtml
 */
class Parser extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The supported output charsets
     */
    protected static $_charsets   = array(
        'ISO-8859-1' => true,
        'US-ASCII'   => true,
        'UTF-8'      => true
    );
    
    /**
     * The processing instruction handlers
     */
    protected static $_piHandlers = array();
    
    /**
     * The XML parser object
     */
    protected $_parser            = NULL;
    
    /**
     * The root XHTML tag object
     */
    protected $_xhtml             = NULL;
    
    /**
     * The current XHTML tag object
     */
    protected $_currentElement    = NULL;
    
    /**
     * The prefix path to add to all 'src' and 'href' attributes, if relative
     */
    protected $_pathPrefix        = '';
    
    /**
     * Class constructor
     * 
     * @param   string  The path of the file to parse
     * @param   string  A prefix path to add to all 'src' and 'href' attributes, if relative
     * @param   string  The output charset (default is UTF-8)
     * @return  void
     */
    public function __construct( $file, $pathPrefix = '', $charset = 'UTF-8' )
    {
        // Stores the path prefix
        $this->_pathPrefix = $pathPrefix;
        
        // The charset must be uppercase
        $charset           = strtoupper( $charset );
        
        // Checks if the file exists
        if( !file_exists( $file ) || !is_file( $file ) ) {
            
            // The file does not exist
            throw new Parser\Exception(
                'The specified XML file (\'' . $file . '\') does not exist',
                Parser\Exception::EXCEPTION_NO_FILE
            );
        }
        
        // Checks if the file is readable
        if( !is_readable( $file ) ) {
            
            // Cannot read the file
            throw new Parser\Exception(
                'The specified XML file (\'' . $file . '\') is not readable',
                Parser\Exception::EXCEPTION_FILE_NOT_READABLE
            );
        }
        
        // Checks if the charset is supported
        if( !isset( self::$_charsets[ $charset ] ) ) {
            
            // Unsupported charset
            throw new Parser\Exception(
                'The specified charset (' . $charset . ') is not supported',
                Parser\Exception::EXCEPTION_INVALID_CHARSET
            );
        }
        
        // Creates an XML parser
        $this->_parser = xml_parser_create( $charset );
        
        // Sets the current instance as the XML parser object
        xml_set_object( $this->_parser, $this );
        
        // Disables case-folding
        xml_parser_set_option( $this->_parser, XML_OPTION_CASE_FOLDING, false );
        
        // Sets the element handler methods
        xml_set_element_handler( $this->_parser, '_startElementHandler', '_endElementHandler' );
        
        // Sets the character data handler method
        xml_set_character_data_handler( $this->_parser, '_characterDataHandler' );
        
        // Sets the processing instruction handler method
        xml_set_processing_instruction_handler( $this->_parser, '_processingInstructionHandler' );
        
        // Sets the default data handler method
        xml_set_default_handler( $this->_parser, '_defaultHandler' );
        
        // Tries to open a file handler
        if( ( $fileHandler = fopen( $file, 'r' ) ) ) {
            
            // Reads data from the file
            while( $data = fread( $fileHandler, 4096 ) ) {
                
                // Tries to parse the data
                if( !xml_parse( $this->_parser, $data, feof( $fileHandler ) ) ) {
                    
                    // Gets the error string and line number
                    $errorString = xml_error_string(xml_get_error_code( $this->_parser ) );
                    $errorLine   = xml_get_current_line_number( $this->_parser );
                    
                    // Throws an exception, as we have an XML error
                    throw new Parser\Exception(
                        'XML parser error: ' . $errorString . ' at line number ' . $errorLine,
                        Parser\Exception::EXCEPTION_XML_PARSER_ERROR
                    );
                }
            }
            
            // Closes the file handler
            fclose( $fileHandler );
        }
        
        // Frees the parser
        xml_parser_free( $this->_parser );
    }
    
    /**
     * 
     */
    public static function registerProcessingInstructionHandler( $name, $className )
    {
        if( isset( self::$_piHandlers[ $name ] ) ) {
            
            throw new Parser\Exception(
                'The processing instruction \'' . $name . '\' is already registered',
                Parser\Exception::EXCEPTION_PI_EXISTS
            );
        }
        
        if( !class_exists( $className ) ) {
            
            throw new Parser\Exception(
                'Cannot register unexisting class \'' . $className . '\' as a processing instruction handler',
                Parser\Exception::EXCEPTION_NO_PI_CLASS
            );
        }
        
        $interfaces = class_implements( $className );
        
        if( !is_array( $interfaces )
            || !isset( $interfaces[ 'Woops\Xhtml\ProcessingInstruction\Handler\ObjectInterface' ] )
        ) {
            
            throw new Parser\Exception(
                'The class \'' . $className . '\' is not a valid processing instruction handler, since it does not implement the \'Woops\Xhtml\ProcessingInstruction\Handler\ObjectInterface\' interface',
                Parser\Exception::EXCEPTION_INVALID_PI_CLASS
            );
        }
        
        self::$_piHandlers[ $name ] = $className;
    }
    
    /**
     * 
     */
    protected function _startElementHandler( $parser, $name, $attribs )
    {
        if( !is_object( $this->_xhtml ) ) {
            
            $this->_xhtml          = new Tag( $name );
            $this->_currentElement = $this->_xhtml;
            
        } else {
            
            $this->_currentElement = $this->_currentElement->$name;
        }
        
        foreach( $attribs as $key => $value ) {
            
            if( $this->_pathPrefix
                && ( $key === 'src' || $key === 'href' )
                && substr( $value, 0, 1 ) !== '/'
                && substr( $value, 0, 1 ) !== '#'
                && substr( $value, 0, 7 ) !== 'http://'
                && substr( $value, 0, 7 ) !== 'mailto:'
            ) {
                
                $value = $this->_pathPrefix . $value;
            }
            
            $this->_currentElement[ $key ] = $value;
        }
    }
    
    /**
     * 
     */
    protected function _endElementHandler( $parser, $name )
    {
        $this->_currentElement = $this->_currentElement->getParent();
    }
    
    /**
     * 
     */
    protected function _characterDataHandler( $parser, $data )
    {
        if( trim( $data ) !== '' ) {
            
            $this->_currentElement->addTextData( $data );
        }
    }
    
    /**
     * 
     */
    protected function _processingInstructionHandler( $parser, $name, $data )
    {
        if( isset( self::$_piHandlers[ $name ] ) ) {
            
            $handlerClass = self::$_piHandlers[ $name ];
            $handler      = new $handlerClass();
            
            $piParams     = preg_split( '/="|" |"$/', $data );
            
            array_pop( $piParams );
            
            $piParamsLength = count( $piParams );
            
            $options      = new \stdClass();
            
            for( $i = 0; $i < $piParamsLength; $i += 2 ) {
                
                if( isset( $piParams[ $i + 1 ] ) ) {
                    
                    $options->$piParams[ $i ] = $piParams[ $i + 1 ];
                    
                }
            }
            
            $result       = $handler->process( $options );
            
            if( is_object( $result ) && $result instanceof Tag ) {
                
                $this->_currentElement->addChildNode( $result );
                
            } else {
                
                $this->_currentElement->addTextData( $result );
            }
        }
    }
    
    /**
     * 
     */
    protected function _defaultHandler( $parser, $data )
    {
        if( substr( $data, 0, 4 ) === '<!--' && $this->_currentElement ) {
            
            $this->_currentElement->comment( substr( $data, 4, -3 ) );
        }
    }
    
    /**
     * 
     */
    public function getXhtmlObject()
    {
        return $this->_xhtml;
    }
}
