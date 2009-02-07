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

var woops = null;

( function()
{
    var _instance = null;
    
    woops = function woops()
    {
        if( _instance ) {
            
            return _instance;
        }
        
        var _moduleClasses = new Array();
        
        this.registerModuleClass = function( moduleName, moduleClass )
        {
            if( _moduleClasses[ moduleName ] === undefined ) {
                
                _moduleClasses[ moduleName ] = new moduleClass();
                
            } else {
                
                throw new Error( 'Module \'' + moduleName + '\' has already been registered.' );
            }
        }
        
        this.getModule = function( moduleName )
        {
            if( _moduleClasses[ moduleName ] === undefined ) {
                
                // Maybe this should return an empty object instead of throwing an error
                throw new Error( 'Module \'' + moduleName + '\' has not been registered yet.' );
                
            } else {
                
                return _moduleClasses[ moduleName ];
            }
        }
        
        _instance = this;
    }
    
    woops.getInstance = function ()
    {
        if ( _instance === null ) {
            
            _instance = new woops();
        }
        
        return _instance;
    }
    
} )();

// ]]>
