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

/**
 * WOOPS installation form
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.Install
 */
class Woops_Mod_Install_Form extends Woops_Core_Module_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Whether the static variables are set or not
     */
    private static $_hasStatic   = false;
    
    /**
     * The available modules
     */
    protected static $_modules   = array();
    
    /**
     * The available timezones
     */
    protected static $_timezones = array();
    
    /**
     * The available languages
     */
    protected static $_languages = array();
    
    /**
     * The available database engines
     */
    protected static $_engines   = array();
    
    /**
     * The module's content
     */
    protected $_content          = NULL;
    
    /**
     * The INI file parser (for the default configuration file)
     */
    protected $_ini              = NULL;
    
    /**
     * The INI values (for the default configuration file)
     */
    protected $_iniValues        = array();
    
    /**
     * The available database drivers
     */
    protected $_drivers          = array();
    
    /**
     * Whether the first step has been completed
     */
    protected $_step1            = false;
    
    /**
     * Whether the second step has been completed
     */
    protected $_step2            = false;
    
    /**
     * Whether the third step has been completed
     */
    protected $_step3            = false;
    
    /**
     * Whether the fourth step has been completed
     */
    protected $_step4            = false;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        // Calls the parent constructor
        parent::__construct();
        
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Creates an INI file parser with the default configuration file
        $this->_ini       = new Woops_File_Ini_Parser( self::$_env->getSourcePath( 'config.ini.php' ) );
        
        // Gets the ini values
        $this->_iniValues = $this->_ini->getIniArray();
        
        // Creates the base form tag
        $this->_content              = new Woops_Xhtml_Tag( 'form' );
        $this->_content[ 'action' ]  = Woops_Core_Env_Getter::getInstance()->getSourceWebPath( 'scripts/install/' );
        $this->_content[ 'method' ]  = 'POST';
        
        // Creates the container for the menu
        $menu                        = $this->_content->div;
        
        // Current installation step
        $step = $this->_getModuleVar( 'install-step' );
        
        switch( $step ) {
            
            // Welcome
            case false:
                
                // Creates the welcome screen
                $this->_welcomeScreen();
                break;
            
            // General configuration
            case 1:
                
                // Install step 1
                $this->_installStep1();
                
                // Creates the menu
                $this->_createMenu( $menu );
                break;
            
            // Database engine
            case 2:
                
                // Steps before are completed
                $this->_step1 = true;
                
                // Install step 2
                $this->_installStep2();
                
                // Creates the menu
                $this->_createMenu( $menu );
                break;
            
            // Database parameters
            case 3:
                
                // Steps before are completed
                $this->_step1 = true;
                $this->_step2 = true;
                
                $this->_installStep3();
                
                // Creates the menu
                $this->_createMenu( $menu );
                break;
            
            // Database parameters
            case 4:
                
                // Steps before are completed
                $this->_step1 = true;
                $this->_step2 = true;
                $this->_step3 = true;
                
                $this->_installStep4();
                
                // Creates the menu
                $this->_createMenu( $menu );
                break;
        }
    }
    
    /**
     * Gets the install form
     * 
     * @return  string  The install form
     */
    public function __toString()
    {
        return ( string )$this->_content;
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the available modules
        self::$_modules   = Woops_Core_Module_Manager::getInstance()->getAvailableModules();
        
        // Gets the available timezones
        self::$_timezones = Woops_Time_Utils::getInstance()->getTimezones();
        
        // Gets the available languages
        self::$_languages = Woops_Locale_Helper::getInstance()->getLanguages();
        
        // Gets the available database engines
        self::$_engines   = Woops_Database_Layer::getInstance()->getRegisteredEngines();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Creates the install menu
     * 
     * @param   Woops_Xhtml_Tag The menu container
     * @return  void
     */
    protected function _createMenu( Woops_Xhtml_Tag $container )
    {
        // Adds the CSS class to the container
        $container[ 'class' ] = 'menu';
        
        // Number of steps
        $steps = 4;
        
        // Creates the list
        $list = $container->ul;
        
        // Adds the menu title
        $process            = $list->li->div;
        $process[ 'class' ] = 'process';
        $process->addTextData( $this->_lang->process );
        
        // Process each step
        for( $i = 1; $i < $steps + 1; $i++ ) {
            
            // Adds the item div
            $item = $list->li->div;
            
            // Adds the step title
            $item->addTextData( $this->_lang->getLabel( 'step' . $i ) );
            
            // Name of the property to check
            $stepStatus = '_step' . $i;
            
            // Checks if the step is completed
            if( $this->$stepStatus ) {
                
                // Adds the CSS class for the completed step
                $item[ 'class' ] = 'completed';
            }
        }
    }
    
    /**
     * Creates the installer welcome screen
     * 
     * @return  void
     */
    protected function _welcomeScreen()
    {
        // Creates the about box
        $about                 = $this->_content->div;
        $about[ 'class' ]      = 'box-infos';
        $about->h4             = $this->_lang->aboutTitle;
        $about->div            = sprintf(
            $this->_lang->aboutText,
            '<a href="' . self::$_env->getSourceWebPath( 'scripts/install-check/' ) . '">',
            '</a>'
        );
        
        // Creates the steps box
        $steps                 = $this->_content->div;
        $steps[ 'class' ]      = 'box';
        $steps->h4             = $this->_lang->stepsTitle;
        $steps->div            = $this->_lang->stepsText;
        
        // Creates the security box
        $security              = $this->_content->div;
        $security[ 'class' ]   = 'box-warning';
        $security->h4          = $this->_lang->securityTitle;
        $security->div         = $this->_lang->securityText;
        
        // Adds the steps hidden input
        $hidden                = $this->_content->input;
        $hidden[ 'type' ]      = 'hidden';
        $hidden[ 'name' ]      = 'woops[mod][Install][install-step]';
        $hidden[ 'value' ]     = 1;
        
        // Adds the submit button
        $submitDiv             = $this->_content->div;
        $submitDiv[ 'class' ]  = 'submit';
        $submit                = $submitDiv->input;
        $submit[ 'type' ]      = 'submit';
        $submit[ 'class' ]     = 'submit-step';
        $submit[ 'name' ]      = 'woops[mod][Install][submit]';
        $submit[ 'value' ]     = $this->_lang->submitStep1;
    }
    
    /**
     * Creates the first install step
     * 
     * @return  void
     */
    protected function _installStep1()
    {
        // Has the form been submitted?
        if( $this->_getModuleVar( 'submit-write' ) ) {
            
            // Step is complete
            $this->_step1 = true;
            
            // Writes the INI file
            $this->_writeGeneralConfiguration();
            
            // Adds the infos text
            $confirm              = $this->_content->div;
            $confirm[ 'class' ]   = 'box-success';
            $confirm->h4          = $this->_lang->step1ConfirmTitle;
            $confirm->div         = $this->_lang->step1ConfirmText;
            
            // Adds the steps hidden input
            $hidden               = $this->_content->input;
            $hidden[ 'type' ]     = 'hidden';
            $hidden[ 'name' ]     = 'woops[mod][Install][install-step]';
            $hidden[ 'value' ]    = 2;
            
            // Adds the submit button
            $submitDiv            = $this->_content->div;
            $submitDiv[ 'class' ] = 'submit';
            $submit               = $submitDiv->input;
            $submit[ 'type' ]     = 'submit';
            $submit[ 'class' ]    = 'submit-step';
            $submit[ 'name' ]     = 'woops[mod][Install][submit]';
            $submit[ 'value' ]    = $this->_lang->submitStep2;
            
        } else {
            
            // Removes the database section, as it will be configured later
            unset( $this->_iniValues[ 'database' ] );
            
            // Creates the configuration form
            $this->_createForm( $this->_iniValues );
        }
    }
    
    /**
     * Writes the INI file for the first install step
     * 
     * @return  void
     */
    protected function _writeGeneralConfiguration()
    {
        // Gets the INI file object
        $iniFile = $this->_ini->getIniObject();
        
        // Removes the database section, as it will be configured later
        unset( $iniFile->database );
        
        // Gets the incoming data from the form
        $time       = $this->_getModuleVar( 'time' );
        $lang       = $this->_getModuleVar( 'lang' );
        $xhtml      = $this->_getModuleVar( 'xhtml' );
        $xml        = $this->_getModuleVar( 'xml' );
        $classCache = $this->_getModuleVar( 'classCache' );
        $aop        = $this->_getModuleVar( 'aop' );
        $error      = $this->_getModuleVar( 'error' );
        $modules    = $this->_getModuleVar( 'modules' );
        
        // Boolean values
        $xhtmlFormat        = ( isset( $xhtml[ 'format' ] ) )        ? true : false;
        $xmlFormat          = ( isset( $xml[ 'format' ] ) )          ? true : false;
        $classCacheEnable   = ( isset( $classCache[ 'enable' ] ) )   ? true : false;
        $classCacheOptimize = ( isset( $classCache[ 'optimize' ] ) ) ? true : false;
        $aopEnable          = ( isset( $aop[ 'enable' ] ) )          ? true : false;
        
        // Sets the incoming values
        $iniFile->time->timezone->setValue(        $time[ 'timezone' ] );
        $iniFile->lang->defaultLanguage->setValue( $lang[ 'defaultLanguage' ] );
        $iniFile->xhtml->format->setValue(         $xhtmlFormat );
        $iniFile->xml->format->setValue(           $xmlFormat );
        $iniFile->classCache->enable->setValue(    $classCacheEnable );
        $iniFile->classCache->optimize->setValue(  $classCacheOptimize );
        $iniFile->aop->enable->setValue(           $aopEnable );
        $iniFile->error->report->setValue(         $error[ 'report' ] );
        
        // Removes the loaded module's section as it will be re-created
        unset( $iniFile->modules->loaded );
        
        // Creates the loaded modules array
        $loaded = $iniFile->modules->newArrayItem( 'loaded' );
        
        // Adds the install module, which need to be loaded in order to continue using this script
        $loaded->addValue( 'Install' );
        
        // Checks if we have modules to activate
        if( isset( $modules[ 'loaded' ] ) && is_array( $modules[ 'loaded' ] ) ) {
            
            // Process each module that needs to be activated
            foreach( $modules[ 'loaded' ] as $modName ) {
                
                // Checks if the module is available
                if( isset( self::$_modules[ $modName ] ) ) {
                    
                    // Adds the current module to the loaded modules' list
                    $loaded->addValue( $modName );
                }
            }
        }
        
        // Writes the INI file to the config directory
        $iniFile->toFile(
            'woops.ini.php',
            self::$_env->getPath( 'config' ),
            true
        );
    }
    protected function _installStep2()
    {
        // Checks if database engines are availables
        if( !count( self::$_engines ) ) {
            
            $error            = $this->_content->div;
            $error[ 'class' ] = 'box-error';
            $error->h4        = $this->_lang->engineErrorTitle;
            $error->div       = $this->_lang->engineErrorText;
            
        } elseif( $this->_getModuleVar( 'submit-write' ) ) {
            
            // Writes the INI file
            $this->_writeEngineConfiguration();
            
            // Step is complete
            $this->_step2         = true;
            
            // Adds the infos text
            $confirm              = $this->_content->div;
            $confirm[ 'class' ]   = 'box-success';
            $confirm->h4          = $this->_lang->step2ConfirmTitle;
            $confirm->div         = $this->_lang->step2ConfirmText;
            
            // Adds an hidden input
            $hidden               = $this->_content->input;
            $hidden[ 'type' ]     = 'hidden';
            $hidden[ 'name' ]     = 'woops[mod][Install][install-step]';
            $hidden[ 'value' ]    = 3;
            
            // Adds the submit button
            $submitDiv            = $this->_content->div;
            $submitDiv[ 'class' ] = 'submit';
            $submit               = $submitDiv->input;
            $submit[ 'type' ]     = 'submit';
            $submit[ 'class' ]    = 'submit-step';
            $submit[ 'name' ]     = 'woops[mod][Install][submit]';
            $submit[ 'value' ]    = $this->_lang->submitStep3;
        
        } else {
            
            // Creates the configuration form (with only the database engine setting)
            $this->_createForm(
                array(
                    'database' => array(
                        'engine' => $this->_iniValues[ 'database' ][ 'engine' ]
                    )
                )
            );
        }
    }
    
    /**
     * Writes the INI file for the second install step
     * 
     * @return  void
     */
    protected function _writeEngineConfiguration()
    {
        // Gets the INI file
        $iniParser = new Woops_File_Ini_Parser( self::$_env->getPath( 'config/woops.ini.php' ) );
        $iniFile   = $iniParser->getIniObject();
        
        // Gets the incoming variables
        $vars      = $this->_getModuleVar( 'database' );
        
        // Adds the database section, and adds the default engine
        $database  = $iniFile->newSectionItem( 'database' );
        $database->newValueItem( 'engine', $vars[ 'engine' ] );
        
        // Writes the INI file to the config directory
        $iniFile->toFile(
            'woops.ini.php',
            self::$_env->getPath( 'config' ),
            true
        );
    }
    
    /**
     * Writes the INI file for the third install step
     * 
     * @return  void
     */
    protected function _installStep3()
    {
        // Gets the class name of the database engine (we won't use the getEngine() method, as it will tries to connect the engine with an incomplete configuration)
        $engineClass    = Woops_Database_Layer::getInstance()->getEngineClass();
        
        // Gets the engine instance
        $engine         = Woops_Core_Class_Manager::getInstance()->getSingleton( $engineClass );
        
        // Stores the available database drivers
        $this->_drivers = $engine->getAvailableDrivers();
        
        // Checks if database drivers are available
        if( !count( $this->_drivers ) ) {
            
            $error            = $this->_content->div;
            $error[ 'class' ] = 'box-error';
            $error->h4        = $this->_lang->driverErrorTitle;
            $error->div       = $this->_lang->driverErrorText;
            
        } else {
            
            // Storage for the error messages
            $errors = array();
            
            // Has the form been submitted?
            if( $this->_getModuleVar( 'submit-write' ) ) {
                
                // Checks for errors in the submitted values
                $errors = $this->_checkInstallStep3();
                
                // If no errors, we can check the database connection
                if( !count( $errors ) ) {
                    
                    // Tries to connect to the database
                    if( $databaseError = $this->_checkDatabaseConnection() ) {
                        
                        // Error connecting to the database
                        $errorBox            = $this->_content->div;
                        $errorBox[ 'class' ] = 'box-error';
                        $errorBox->h4        = $this->_lang->errorDatabaseConnection;
                        $errorBox->div       = $databaseError;
                        
                    } else {
                        
                        // Writes the INI file
                        $this->_writeDatabaseConfiguration();
                        
                        // Step is complete
                        $this->_step3         = true;
                        
                        // Adds the infos text
                        $confirm              = $this->_content->div;
                        $confirm[ 'class' ]   = 'box-success';
                        $confirm->h4          = $this->_lang->step3ConfirmTitle;
                        $confirm->div         = $this->_lang->step3ConfirmText;
                        
                        // Adds an hidden input
                        $hidden               = $this->_content->input;
                        $hidden[ 'type' ]     = 'hidden';
                        $hidden[ 'name' ]     = 'woops[mod][Install][install-step]';
                        $hidden[ 'value' ]    = 4;
                        
                        // Adds the submit button
                        $submitDiv            = $this->_content->div;
                        $submitDiv[ 'class' ] = 'submit';
                        $submit               = $submitDiv->input;
                        $submit[ 'type' ]     = 'submit';
                        $submit[ 'class' ]    = 'submit-step';
                        $submit[ 'name' ]     = 'woops[mod][Install][submit]';
                        $submit[ 'value' ]    = $this->_lang->submitStep4;
                        
                        // Nothing else to display
                        return;
                    }
                }
            }
            
            // Checks if we have error messages to display
            if( count( $errors ) ) {
                
                // Creates the error box
                $errorBox            = $this->_content->div;
                $errorBox[ 'class' ] = 'box-error';
                $errorBox->h4        = $this->_lang->errors;
                $errorList           = $errorBox->ul;
                
                // Process each error message
                foreach( $errors as $message ) {
                    
                    // Adds the error message
                    $errorList->li = $message;
                }
            }
            
            // Removes the already configured parameters
            unset( $this->_iniValues[ 'time' ] );
            unset( $this->_iniValues[ 'lang' ] );
            unset( $this->_iniValues[ 'xhtml' ] );
            unset( $this->_iniValues[ 'xml' ] );
            unset( $this->_iniValues[ 'classCache' ] );
            unset( $this->_iniValues[ 'aop' ] );
            unset( $this->_iniValues[ 'error' ] );
            unset( $this->_iniValues[ 'modules' ] );
            unset( $this->_iniValues[ 'database' ][ 'engine' ] );
            
            // Creates the configuration form
            $this->_createForm( $this->_iniValues );
        }
    }
    
    /**
     * Checks the submitted data for the third installation step
     * 
     * @return  array   An array with the error messages, if any
     */
    protected function _checkInstallStep3()
    {
        // Storage for the error messages
        $errors   = array();
        
        // Gets the incoming data
        $database = $this->_getModuleVar( 'database' );
        
        // Host must be specified
        if( !isset( $database[ 'host' ] ) || !$database[ 'host' ] ) {
            
            // No host
            $errors[] = $this->_lang->errorDatabaseHostRequired;
        }
        
        // Port must be numeric, if sepcified
        if( isset( $database[ 'port' ] ) && $database[ 'port' ] && !is_numeric( $database[ 'port' ] ) ) {
            
            // Port not numeric
            $errors[] = $this->_lang->errorDatabasePortNotNumeric;
        }
        
        // Database name must be specified
        if( !isset( $database[ 'database' ] ) || !$database[ 'database' ] ) {
            
            // No database name
            $errors[] = $this->_lang->errorDatabaseNameRequired;
        }
        
        // Returns the error messages
        return $errors;
    }
    
    /**
     * Tries to establish a database connection
     * 
     * @return  mixed   False is the connection is OK, otherwise the error message
     */
    protected function _checkDatabaseConnection()
    {
        // Gets the incoming data
        $database       = $this->_getModuleVar( 'database' );
        
        // Gets the class name of the database engine (we won't use the getEngine() method, as it will tries to connect the engine with an incomplete configuration)
        $engineClass    = Woops_Database_Layer::getInstance()->getEngineClass();
        
        // Gets the engine instance
        $engine         = Woops_Core_Class_Manager::getInstance()->getSingleton( $engineClass );
        
        // We don't want any error here, we are just testing the database settings
        try {
            
            // Database settings
            $driver   = $database[ 'driver' ];
            $host     = $database[ 'host' ];
            $port     = ( isset( $database[ 'port' ] )        && $database[ 'port' ] )        ? $database[ 'port' ]        : false;
            $user     = ( isset( $database[ 'user' ] )        && $database[ 'user' ] )        ? $database[ 'user' ]        : false;
            $password = ( isset( $database[ 'password' ] )    && $database[ 'password' ] )    ? $database[ 'password' ]    : false;
            $name     = ( isset( $database[ 'database' ] )    && $database[ 'database' ] )    ? $database[ 'database' ]    : false;
            $prefix   = ( isset( $database[ 'tablePrefix' ] ) && $database[ 'tablePrefix' ] ) ? $database[ 'tablePrefix' ] : false;
            
            // Loads and connect the engine - If this fail, we'll should get an exception
            $engine->load( $driver, $host, $port, $name, $prefix );
            $engine->connect( $user, $password );
            
        } catch( Exception $e ) {
            
            // Returns the error message
            return $e->getMessage();
        }
        
        // Database connection is OK
        return false;
    }
    
    /**
     * Writes the INI file for the third install step
     * 
     * @return  void
     */
    protected function _writeDatabaseConfiguration()
    {
        // Gets the INI file
        $iniParser = new Woops_File_Ini_Parser( self::$_env->getPath( 'config/woops.ini.php' ) );
        $iniFile   = $iniParser->getIniObject();
        
        // Gets the incoming variables
        $vars      = $this->_getModuleVar( 'database' );
        
        // Gets the database section
        $dbSection = $iniFile->getItem( 'database' );
        
        // Gets the configured values
        $driver      = $vars[ 'driver' ];
        $host        = $vars[ 'host' ];
        $database    = $vars[ 'database' ];
        $port        = ( $vars[ 'port' ] )        ? $vars[ 'port' ]        : '';
        $user        = ( $vars[ 'user' ] )        ? $vars[ 'user' ]        : '';
        $password    = ( $vars[ 'password' ] )    ? $vars[ 'password' ]    : '';
        $tablePrefix = ( $vars[ 'tablePrefix' ] ) ? $vars[ 'tablePrefix' ] : '';
        
        // Writes the configured values
        $dbSection->newValueItem( 'driver',      $driver );
        $dbSection->newValueItem( 'host',        $host );
        $dbSection->newValueItem( 'database',    $database );
        $dbSection->newValueItem( 'port',        $port );
        $dbSection->newValueItem( 'user',        $user );
        $dbSection->newValueItem( 'password',    $password );
        $dbSection->newValueItem( 'tablePrefix', $tablePrefix );
        
        // Writes the INI file to the config directory
        $iniFile->toFile(
            'woops.ini.php',
            self::$_env->getPath( 'config' ),
            true
        );
    }
    
    /**
     * Creates the fourth install step
     * 
     * @return  void
     */
    protected function _installStep4()
    {
        // Paths to the SQL files
        $dropFile   = self::$_env->getSourcePath( 'database/drop-tables.sql' );
        $createFile = self::$_env->getSourcePath( 'database/structure.sql' );
        $importFile = self::$_env->getSourcePath( 'database/data.sql' );
        
        // Storage for the error messages
        $errors = array();
        
        // Has the form been submitted?
        if( $this->_getModuleVar( 'submit-import' ) ) {
            
            // Gets the incoming data
            $dropTables   = $this->_getModuleVar( 'drop' );
            $createTables = $this->_getModuleVar( 'create' );
            $importTables = $this->_getModuleVar( 'import' );
            
            // Checks if we have tables to drop
            if( is_array( $dropTables ) && count( $dropTables ) ) {
                
                // Tries to drop the selected tables
                if( $error = $this->_databaseQuery( $dropTables, $dropFile, 'DROP TABLE IF EXISTS ' ) ) {
                    
                    // We've got an error message - Stores it
                    $errors[] = $error;
                }
            }
            
            // Checks if we have tables to create
            if( is_array( $createTables ) && count( $createTables ) ) {
                
                // Tries to create the selected tables
                if( $error = $this->_databaseQuery( $createTables, $createFile, 'CREATE TABLE IF NOT EXISTS ' ) ) {
                    
                    // We've got an error message - Stores it
                    $errors[] = $error;
                }
            }
            
            // Checks if we have tables to import
            if( is_array( $importTables ) && count( $importTables ) ) {
                
                // Tries to import the selected tables
                if( $error = $this->_databaseQuery( $importTables, $importFile, 'INSERT INTO ' ) ) {
                    
                    // We've got an error message - Stores it
                    $errors[] = $error;
                }
            }
            
            // Checks if we have error messages to display
            if( count( $errors ) ) {
                
                // Creates the error box
                $errorBox            = $this->_content->div;
                $errorBox[ 'class' ] = 'box-error';
                $errorBox->h4        = $this->_lang->errors;
                $errorList           = $errorBox->ul;
                
                // Process each error message
                foreach( $errors as $message ) {
                    
                    // Adds the error message
                    $errorList->li = $message;
                }
                
            } else {
                
                // Step is complete
                $this->_step4 = true;
                
                // Installation is complete
                $this->_installComplete();
                
                // Nothing else to display
                return;
            }
        }
        
        // Creates the containers
        $drop              = $this->_content->div;
        $create            = $this->_content->div;
        $import            = $this->_content->div;
        $drop[ 'class' ]   = 'box';
        $create[ 'class' ] = 'box';
        $import[ 'class' ] = 'box';
        $drop->h4          = $this->_lang->dropTables;
        $create->h4        = $this->_lang->createTables;
        $import->h4        = $this->_lang->importTables;
        
        // Creates the table lists
        $this->_tableList( 'drop',   $dropFile,   'DROP TABLE IF EXISTS ',       $drop->div );
        $this->_tableList( 'create', $createFile, 'CREATE TABLE IF NOT EXISTS ', $create->div );
        $this->_tableList( 'import', $importFile, 'INSERT INTO ',                $import->div );
        
        // Adds an hidden input for the current install step
        $hidden               = $this->_content->input;
        $hidden[ 'type' ]     = 'hidden';
        $hidden[ 'name' ]     = 'woops[mod][Install][install-step]';
        $hidden[ 'value' ]    = 4;
        
        // Adds the submit button
        $submitDiv            = $this->_content->div;
        $submitDiv[ 'class' ] = 'submit';
        $submit               = $submitDiv->input;
        $submit[ 'type' ]     = 'submit';
        $submit[ 'class' ]    = 'submit-write';
        $submit[ 'value' ]    = $this->_lang->importDatabase;
        $submit[ 'name'  ]    = 'woops[mod][Install][submit-import]';
    }
    
    /**
     * Creates a list of tables
     * 
     * @param   string          The action to perform (drop, create or import)
     * @param   string          The path to the SQL file
     * @param   string          The prefix to detect the table names
     * @param   Woops_Xhtml_Tag The container in which to place the table list
     * return   void
     */
    protected function _tableList( $action, $filePath, $detectPrefix, Woops_Xhtml_Tag $container )
    {
        // Gets the file content
        $file    = file_get_contents( $filePath );
        
        // Storage for the matches
        $matches = array();
        
        // Finds all table names
        $tables  = preg_match_all(
            '/^\s*' . $detectPrefix . '`([^`]+)`/m',
            $file,
            $matches
        );
        
        // Checks for matches
        if( isset( $matches[ 1 ] ) && is_array( $matches[ 1 ] ) && count( $matches[ 1 ] ) ) {
            
            // Removes duplicates
            $matches[ 1 ] = array_flip( $matches[ 1 ] );
            
            // Process each table name
            foreach( $matches[ 1 ] as $tableName => $void ) {
                
                // Real name of the table
                $tableName = str_replace(
                    '{$PREFIX}',
                    self::$_conf->getVar( 'database', 'tablePrefix' ),
                    $tableName
                );
                
                // ID for the checkbox
                $id                 = $action . '-' . $tableName;
                
                // Creates the container
                $div                = $container->div;
                
                // Creates the checkbox
                $check              = $div->input;
                $check[ 'type' ]    = 'checkbox';
                $check[ 'checked' ] = 'checked';
                $check[ 'id' ]      = $id;
                $check[ 'name' ]    = 'woops[mod][Install][' . $action . '][]';
                $check[ 'value' ]   = $tableName;
                
                // Creates the label
                $label            = $div->label;
                $label[ 'class' ] = 'tableName';
                $label[ 'for' ]   = $id;
                $label->addTextData( $tableName );
            }
        }
    }
    
    /**
     * Executes the needed database queries
     * 
     * @param   array           The selected tables
     * @param   string          The path to the SQL file
     * @param   string          The prefix to detect the table names
     * return   void
     */
    protected function _databaseQuery( array $tableNames, $filePath, $detectPrefix )
    {
        // Tags to replace
        $tags = array(
            '/{\$PREFIX}/',
            '/{\$DEFAULT_LANGUAGE}/'
        );
        
        // Replacement values
        $replace = array(
            self::$_conf->getVar( 'database', 'tablePrefix' ),
            self::$_conf->getVar( 'lang', 'defaultLanguage' )
        );
        
        // Gets the table names as keys
        $tableNames  = array_flip( $tableNames );
        
        // Gets the file lines
        $lines       = file( $filePath );
        
        // Replaces the tags
        $lines       = preg_replace( $tags, $replace, $lines );
        
        // Storage for the queries to perform
        $queries     = array();
        
        // Flag to know if we are in a multiline SQL statement
        $inStatement = false;
        
        // Number of queries
        $queryCount  = 0;
        
        // Process each line
        foreach( $lines as $line ) {
            
            // Removes unneeded whitespace
            $line = trim( $line );
            
            // Are we in a multiline SQL statement?
            if( $inStatement ) {
                
                // Yes, adds the current line to the query
                $queries[ $queryCount - 1 ] .= $line . self::$_str->NL;
                
                // Does the current line end the multiline SQL statement?
                if( substr( $line, -1 ) === ';' ) {
                    
                    // Yes, resets the flag
                    $inStatement = false;
                }
                
            } else {
                
                // Storage
                $matches = array();
                
                // Finds the table instructions
                preg_match( '/^' . $detectPrefix . '`([^`]+)`/', $line, $matches );
                
                // Checks if we have an instruction
                if( isset( $matches[ 1 ] ) && !is_array( $matches[ 1 ] ) && isset( $tableNames[ $matches[ 1 ] ] ) ) {
                    
                    // Yes, adds the current line to the query
                    $queries[] = $line . self::$_str->NL;
                    
                    // Checks if the statement is ended
                    if( substr( $line, -1 ) !== ';' ) {
                        
                        // No, we are now in a multiline SQL statement
                        $inStatement = true;
                    }
                    
                    // Increases the query counter
                    $queryCount++;
                }
            }
        }
        
        // We don't want any errors here
        try {
            
            // Database engine object
            static $engine;
            
            // Have we already the instance of the database engine
            if( !is_object( $engine ) ) {
                
                // Gets the database engine
                $engine = Woops_Database_Layer::getInstance()->getEngine();
            }
            
            // Process each query
            foreach( $queries as $query ) {
                
                // Executes the query
                $res = $engine->query( $query );
                
                // Checks the query result
                if( !$res ) {
                    
                    // No result, returns the error message
                    return $engine->errorMessage();
                }
            }
            
        } catch( Exception $e ) {
            
            // Returns the exception message
            return $e->getMessage();
        }
    }
    
    /**
     * Writes the installation success message
     * 
     * @return  void
     */
    protected function _installComplete()
    {
        $box            = $this->_content->div;
        $box[ 'class' ] = 'box-success';
        $box->h4        = $this->_lang->successTitle;
        $box->div       = $this->_lang->successText;
    }
    
    /**
     * Creates form elements from an INI file
     * 
     * @param   array   An multi-dimmensionnal array with the INI sections and values
     * @return  void
     */
    protected function _createForm( array $ini )
    {
        // Process each section of the INI file
        foreach( $ini as $section => $items ) {
            
            // Section container
            $container               = $this->_content->div;
            $container[ 'class' ]    = 'form-elements';
            
            // Section title
            $sectionTitle            = $container->h3;
            $sectionTitle[ 'class' ] = $section;
            $sectionTitle->addTextData( $this->_lang->$section );
            
            // Creates the section items form elements
            $this->_createSectionItems( $section, $items, $container );
        }
        
        // Adds an hidden input for the current install step
        $hidden               = $this->_content->input;
        $hidden[ 'type' ]     = 'hidden';
        $hidden[ 'name' ]     = 'woops[mod][Install][install-step]';
        $hidden[ 'value' ]    = $this->_getModuleVar( 'install-step' );
        
        // Adds the submit button
        $submitDiv            = $this->_content->div;
        $submitDiv[ 'class' ] = 'submit';
        $submit               = $submitDiv->input;
        $submit[ 'type' ]     = 'submit';
        $submit[ 'class' ]    = 'submit-write';
        $submit[ 'value' ]    = $this->_lang->writeConfValues;
        $submit[ 'name'  ]    = 'woops[mod][Install][submit-write]';
    }
    
    /**
     * Creates the form items for an INI section
     * 
     * @param   string          The name of the section
     * @param   array           The INI item array
     * @param   Woops_Xhtml_Tag The form element container in which to place the elements
     * @return  void
     */
    public function _createSectionItems( $section, array $items, Woops_Xhtml_Tag $container )
    {
        // Counter variables
        $counter    = 0;
        $itemsCount = count( $items );
        
        // Class for the item boxes
        $class      = 'left';
        
        // Process each item in the current section
        foreach( $items as $name => $item ) {
            
            // Creates the container
            $itemContainer            = $container->div;
            
            // Checks if the element must float or not
            if( $itemsCount > 1 && ( $counter < $itemsCount - 1 || $class === 'right' ) ) {
                
                // Adds a CSS class (left or right)
                $itemContainer[ 'class' ] = $class;
            }
            
            // Creates the box
            $box                      = $itemContainer->div;
            $box[ 'class' ]           = 'box';
            $box->h4                  = $this->_lang->getLabel( $section . '-' . $name );
            
            // Do we have a title comment in the INI file?
            if( isset( $item[ 'comments' ][ 'title' ] ) ) {
                
                // Adds the title
                $title            = $box->div;
                $title[ 'class' ] = 'title';
                $title->span      = $item[ 'comments' ][ 'title' ];
            }
            
            // Do we have a description comment in the INI file?
            if( isset( $item[ 'comments' ][ 'description' ] ) ) {
                
                // Adds the description
                $title            = $box->div;
                $title[ 'class' ] = 'description';
                $title->addTextData( nl2br( $item[ 'comments' ][ 'description' ] ) );
            }
            
            // Creates the container for the form element
            $formElement            = $box->div;
            $formElement[ 'class' ] = 'form-element';
            
            // Special processing for some items
            if( $section === 'modules' && $name === 'loaded' ) {
                
                // List of the modules
                $this->_createModuleList( $section, $name, $item, $formElement );
                
            } elseif( $section === 'time' && $name === 'timezone' ) {
                
                // List of the timezones
                $this->_createTimezoneList( $section, $name, $item, $formElement );
                
            } elseif( $section === 'lang' && $name === 'defaultLanguage' ) {
                
                // List of the languages
                $this->_createLanguageList( $section, $name, $item, $formElement );
                
            } elseif( $section === 'database' && $name === 'engine' ) {
                
                // List of the database engines
                $this->_createDatabaseEngineList( $section, $name, $item, $formElement );
                
            } elseif( $section === 'database' && $name === 'driver' ) {
                
                // List of the database drivers
                $this->_createDatabaseDriverList( $section, $name, $item, $formElement );
                
            } else {
                
                // Normal form item
                $this->_createFormItem( $section, $name, $item, $formElement );
            }
            
            // Increases the counter
            $counter++;
            
            // Checks the element class
            if( $class === 'right' ) {
                
                // Adds a clearer div
                $clearer            = $container->div;
                $clearer[ 'class' ] = 'clearer';
                
                // Next element will be placed on the left
                $class = 'left';
                
            } else {
                
                // Next element will be placed on the right
                $class = 'right';
            }
        }
    }
    
    /**
     * Creates the list of the modules
     * 
     * @param   string          The name of the INI section
     * @param   string          The name of the INI item in the section
     * @param   array           The INI item array
     * @param   Woops_Xhtml_Tag The form element container in which to place the element
     * @return  void
     */
    protected function _createModuleList( $section, $itemName, array $item, Woops_Xhtml_Tag $container )
    {
        // Process each module
        foreach( self::$_modules as $modName => $modPath ) {
            
            // Creates the container
            $module            = $container->div;
            $module[ 'class' ] = 'module';
            
            // Creates the checkbox
            $check             = $module->input;
            $check[ 'type' ]   = 'checkbox';
            $check[ 'value' ]  = $modName;
            $check[ 'id' ]     = 'module-' . $modName;
            $check[ 'name'   ] = 'woops[mod][Install][' . $section . '][' . $itemName . '][]';
            
            // Creates the module label
            $label             = $module->label;
            $label[ 'for' ]    = 'module-' . $modName;
            $label->addTextData( $modName );
            
            // Checks if the module must be selected by default
            if( in_array( $modName, $item[ 'value' ] ) ) {
                
                // Checks the checkbox
                $check[ 'checked' ] = 'checked';
                $module[ 'class' ] = 'module-loaded';
            }
            
            // The "Install" module must not be unloaded
            if( $modName === 'Install' ) {
                
                // Disables the checkbox for the "Install" module
                $check[ 'disabled' ] = 'disabled';
            }
        }
    }
    
    /**
     * Creates the list of the timezones
     * 
     * @param   string          The name of the INI section
     * @param   string          The name of the INI item in the section
     * @param   array           The INI item array
     * @param   Woops_Xhtml_Tag The form element container in which to place the element
     * @return  void
     */
    protected function _createTimezoneList( $section, $itemName, array $item, Woops_Xhtml_Tag $container )
    {
        // Creates the select box
        $select           = $container->select;
        $select[ 'name' ] = 'woops[mod][Install][' . $section . '][' . $itemName . ']';
        
        // Process each timezone
        foreach( self::$_timezones as $key => $value ) {
            
            // Adds the current timezone
            $option            = $select->option;
            $option[ 'value' ] = $key;
            $option->addTextData( $key );
            
            // Checks if the timezone must be selected
            if( $key === $item[ 'value' ] ) {
                
                // Selects the option
                $option[ 'selected' ] = 'selected';
            }
        }
    }
    
    /**
     * Creates the list of the languages
     * 
     * @param   string          The name of the INI section
     * @param   string          The name of the INI item in the section
     * @param   array           The INI item array
     * @param   Woops_Xhtml_Tag The form element container in which to place the element
     * @return  void
     */
    protected function _createLanguageList( $section, $itemName, array $item, Woops_Xhtml_Tag $container )
    {
        // Creates the select box
        $select           = $container->select;
        $select[ 'name' ] = 'woops[mod][Install][' . $section . '][' . $itemName . ']';
        
        // Process each language
        foreach( self::$_languages as $key => $value ) {
            
            // Adds the current language
            $option            = $select->option;
            $option[ 'value' ] = $key;
            $option->addTextData( $key );
            
            // Checks if the language must be selected
            if( $key === $item[ 'value' ] ) {
                
                // Selects the option
                $option[ 'selected' ] = 'selected';
            }
        }
    }
    
    /**
     * Creates the list of the database engines
     * 
     * @param   string          The name of the INI section
     * @param   string          The name of the INI item in the section
     * @param   array           The INI item array
     * @param   Woops_Xhtml_Tag The form element container in which to place the element
     * @return  void
     */
    protected function _createDatabaseEngineList( $section, $itemName, array $item, Woops_Xhtml_Tag $container )
    {
        // Creates the select box
        $select           = $container->select;
        $select[ 'name' ] = 'woops[mod][Install][' . $section . '][' . $itemName . ']';
        
        // Process each engine
        foreach( self::$_engines as $key => $value ) {
            
            // Adds the current engine
            $option            = $select->option;
            $option[ 'value' ] = $key;
            $option->addTextData( $key );
            
            // Checks if the engine must be selected
            if( $key === $item[ 'value' ] ) {
                
                // Selects the option
                $option[ 'selected' ] = 'selected';
            }
        }
    }
    
    /**
     * Creates the list of the database drivers
     * 
     * @param   string          The name of the INI section
     * @param   string          The name of the INI item in the section
     * @param   array           The INI item array
     * @param   Woops_Xhtml_Tag The form element container in which to place the element
     * @return  void
     */
    protected function _createDatabaseDriverList( $section, $itemName, array $item, Woops_Xhtml_Tag $container )
    {
        // Creates the select box
        $select           = $container->select;
        $select[ 'name' ] = 'woops[mod][Install][' . $section . '][' . $itemName . ']';
        
        // Gets the incoming data
        $postData  = $this->_getModuleVar( $section );
        $postValue = false;
        
        // Chekcs if a correct driver has been submitted
        if( $postData && isset( $postData[ $itemName ] ) ) {
            
            // Sets the driver name
            $postValue = $postData[ $itemName ];
        }
        
        // Process each driver
        foreach( $this->_drivers as $key => $value ) {
            
            // Adds the current driver
            $option            = $select->option;
            $option[ 'value' ] = $key;
            $option->addTextData( $key );
            
            // Checks if the driver must be selected
            if( $postValue !== false && $key === $postValue ) {
                
                // Selects the option
                $option[ 'selected' ] = 'selected';
                
            } elseif( $postValue === false && $key === $item[ 'value' ] ) {
                
                // Selects the option
                $option[ 'selected' ] = 'selected';
            }
        }
    }
    
    /**
     * Creates a form item
     * 
     * @param   string          The name of the INI section
     * @param   string          The name of the INI item in the section
     * @param   array           The INI item array
     * @param   Woops_Xhtml_Tag The form element container in which to place the element
     * @return  void
     */
    protected function _createFormItem( $section, $itemName, array $item, Woops_Xhtml_Tag $container )
    {
        // Type of the element (default is string)
        $type      = ( isset( $item[ 'comments' ][ 'type' ] ) ) ? $item[ 'comments' ][ 'type' ] : 'string';
        
        // Gets the incoming data
        $postData  = $this->_getModuleVar( $section );
        $postValue = false;
        
        // Checks if data was submitted
        if( $postData && isset( $postData[ $itemName ] ) ) {
            
            // Sets the submitted data
            $postValue = $postData[ $itemName ];
        }
        
        // Checks the type
        switch( $type ) {
            
            // String value - Text input
            case 'string':
                
                // Checks for a value to add
                if( $postValue === false ) {
                    
                    // Adds the value form the INI file
                    $value = ( is_array( $item[ 'value' ] ) ) ? implode( ', ', $item[ 'value' ] ) : $item[ 'value' ];
                    
                } else {
                    
                    // Adds the value from the incoming data
                    $value = $postValue;
                }
                
                // Creates the text input
                $input            = $container->input;
                $input[ 'type' ]  = 'text';
                $input[ 'size' ]  = 30;
                $input[ 'value' ] = $value;
                $input[ 'name' ]  = 'woops[mod][Install][' . $section . '][' . $itemName . ']';
                break;
            
            // Integer value - Text input
            case 'int':
                
                // Value of the input
                $value = ( $postValue === false ) ? $item[ 'value' ] : $postValue;
                
                // Creates the text input
                $input            = $container->input;
                $input[ 'type' ]  = 'text';
                $input[ 'size' ]  = 30;
                $input[ 'value' ] = $value;
                $input[ 'name' ]  = 'woops[mod][Install][' . $section . '][' . $itemName . ']';
                break;
            
            // Boolean value - Checkbox
            case 'boolean':
                
                // Creates the checkbox
                $input            = $container->input;
                $input[ 'type' ]  = 'checkbox';
                $input[ 'name' ]  = 'woops[mod][Install][' . $section . '][' . $itemName . ']';
                
                // Checks if the checkbox must be checked by default
                if( $postValue !== false && $postValue ) {
                    
                    // Checkbox is checked
                    $input[ 'checked' ] = 'checked';
                    
                } elseif( $postValue === false && $item[ 'value' ] ) {
                    
                    // Checkbox is checked
                    $input[ 'checked' ] = 'checked';
                }
                break;
            
            // Selector box
            case 'select':
                
                // Creates the select
                $select           = $container->select;
                $select[ 'name' ] = 'woops[mod][Install][' . $section . '][' . $itemName . ']';
                
                // Checks if the element is required or not
                if( !isset( $item[ 'comments' ][ 'required' ] ) ) {
                    
                    // Adds an empty option
                    $option            = $select->option;
                    $option[ 'value' ] = '';
                }
                
                // Checks if we have options to display
                if( isset( $item[ 'comments' ][ 'options' ] ) ) {
                    
                    // Process each option
                    foreach( $item[ 'comments' ][ 'options' ] as $key => $value ) {
                        
                        // Creates the option tag
                        $option            = $select->option;
                        $option[ 'value' ] = $value;
                        $option->addTextData( $value );
                        
                        // Checks if the option must be selected
                        if( $postValue !== false && $value === $postValue ) {
                            
                            // Selects the current option
                            $option[ 'selected' ] = 'selected';
                            
                        } elseif( $postValue === false && $value === $item[ 'value' ] ) {
                            
                            // Selects the current option
                            $option[ 'selected' ] = 'selected';
                        }
                    }
                }
                break;
        }
    }
}
