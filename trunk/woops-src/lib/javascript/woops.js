// <![CDATA[

//  ############################################################################
//  #                                                                          #
//  #              WOOPS - Web Object Oriented Programming System              #
//  #                                                                          #
//  #                             COPYRIGHT NOTICE                             #
//  #                                                                          #
//  # (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)           #
//  # All rights reserved                                                      #
//  ############################################################################

// $Id$

// Storage for the WOOPS JavaScript library
var woops = null;

/**
 * WOOPS JavaScript library
 * 
 * @param   object  The jQuery object
 * @return  void
 */
( function( $ )
{
    // The unique instance (singleton)
    var _instance = null;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    woops = function woops()
    {
        // Checks if the unique instance already exists
        if( _instance ) {
            
            // Returns the unique instance
            return _instance;
        }
        
        // Storage for the module's JavaScript clases
        var _moduleClasses = new Array();
        
        // Stores the jQuery object
        this.$ = $;
        
        /**
         * Registers a WOOPS module's class
         * 
         * @param   string      The name of the module
         * @param   function    The module class
         * @return  void
         * @throws  Error       If a class for the module has already been registered
         */
        this.registerModuleClass = function( moduleName, moduleClass )
        {
            // Checks if the module class is registered
            if( _moduleClasses[ moduleName ] === undefined ) {
                
                // No - Creates a new instance, and stores it
                _moduleClasses[ moduleName ] = new moduleClass();
                
            } else {
                
                // Error - A class for this module is already registered
                throw new Error( 'Module \'' + moduleName + '\' has already been registered.' );
            }
        }
        
        /**
         * Gets the instance of a WOOPS module's class
         * 
         * @param   string      The name of the module
         * @return  function    The instance of the module class
         * @throws  Error       If the module class has not been registered
         */
        this.getModule = function( moduleName )
        {
            // Checks if the module class is registered
            if( _moduleClasses[ moduleName ] === undefined ) {
                
                // No, throws an error
                // Maybe this should return an empty object instead of throwing an error
                throw new Error( 'Module \'' + moduleName + '\' has not been registered yet.' );
                
            } else {
                
                // Returns the instance of the module class
                return _moduleClasses[ moduleName ];
            }
        }
        
        // Sets the unique instance
        _instance = this;
    }
    
    /**
     * Gets the instance of the WOOPS JavaScript library
     * 
     * @return  function    The instance of the WOOPS JavaScript library
     */
    woops.getInstance = function ()
    {
        // Checks if the unique instance already exists
        if ( _instance === null ) {
            
            // No, creates the unique instance
            _instance = new woops();
        }
        
        // Returns the unique instance
        return _instance;
    }
    
} )( jQuery );

// ]]>
