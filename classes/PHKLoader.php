<?php
##########################################################################
#                                                                        #
# Cristòfol Torrens Morell <piffall@gmail.com>                           #
#                                                                        #
# Released under the GNU General Public License WITHOUT ANY WARRANTY.    #
# See LICENSE file for more information                                  #
#                                                                        #
##########################################################################

class PHKLoader
{
    /**
     * Unique instance
     */
    protected static $Instance = null;

    /**
     * Packages
     */
    protected $Packages = array();

    /**
     * Constructor
     * Registers autoload method
     */
    protected function __construct()
    {
        spl_autoload_register(array($this,'getPackage'));
    }

    /**
     * Initialize method
     */
    public static function registerAutoloader()
    {
        static::getInstance()->addPackage(__DIR__);
    }

    /**
     * Get the instance
     */
    public static function &getInstance()
    {
        if(is_null(static::$Instance)){
            static::$Instance = new static();
        }
        return self::$Instance;
    }

    /**
     * Add Package to array
     * @param String Path
     * @param String Prefix
     * @param String Sufix
     */
    public function &addPackage($Path = null, $Prefix = '', $Sufix = '')
    {
        $Package = is_null($Path) ? dirname(__FILE__) . '/' : preg_replace('/\/$/','',$Path) . '/';
        $Package = $Package . $Prefix . '{class}' . $Sufix . '.php';
        $this->Packages[] = $Package;
        return $this;
    }

    /**
     * Get Package
     * Includes the file
     * @param String Classname
     */
    public function &getPackage($Classname)
    {
        if(class_exists($Classname)){
            return $this;
        }
        foreach($this->Packages as $Package){
            $Package = str_replace('{class}',$Classname,$Package);
            if(file_exists($Package)){
                include_once($Package);
                return $this;
            }
        }
        return $this;
    }
}

?>
