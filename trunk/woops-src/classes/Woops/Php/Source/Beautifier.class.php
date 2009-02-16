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
 * PHP source code beautifier
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Php.Source
 */
class Woops_Php_Source_Beautifier
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The optimized PHP code
     */
    protected $_formattedCode = '';
    
    /**
     * Class constructor
     * 
     * @param   string  The PHP code to optimize
     * @return  NULL
     */
    public function __construct( $source )
    {
        // Gets the code tokens
        $tokens      = token_get_all( ( string )$source );
        
        // Process each token
        foreach( $tokens as $key => $token ) {
            
            // Checks if the token is an array
            if( is_array( $token ) ) {
                
                // Stores the code
                $codeLines[] = $token[ 1 ];
                
            } else {
                
                // Stores the code
                $codeLines[] = $token;
            }
            
            // Stores the last token
            $lastToken = $token;
        }
        
        // Stores the optimized code
        $this->_formattedCode = implode( '', $codeLines );
    }
    
    /**
     * Gets the optimized version of the PHP source code
     * 
     * @return  string  The optimized version of the PHP source code
     */
    public function __toString()
    {
        return $this->_formattedCode;
    }
}
