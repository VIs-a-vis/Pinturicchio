<?php

/**
 * Pinturicchio
 * 
 * @copyright Copyright (c) 2012 Kanat Gailimov (http://kanat.gailimov.kz)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU General Public License v3
 */


namespace pinturicchio\components\loader;

require_once 'SplLoader.php';

/**
 * Class loader
 * 
 * Usage example:
 * 
 *     $loader = new Loader();
 *     $loader->setPath(__DIR__)
 *            ->setPath(__DIR__ . '/vendors')
 *            ->registerAutoload();
 * 
 * @author Kanat Gailimov <gailimov@gmail.com>
 */
class Loader extends SplLoader
{
    /**
     * Paths
     * 
     * @var array
     */
    private $_paths = array();
    
    /**
     * Sets path
     * 
     * @param  string $path Path
     * @return pinturicchio\components\Loader
     */
    public function setPath($path)
    {
        if (!in_array((string) $path, $this->_paths))
            $this->_paths[] = $path;
        return $this;
    }
    
    /**
     * Registers autoload method
     * 
     * @return void
     */
    public function registerAutoload()
    {
        spl_autoload_register(array($this, 'load'));
    }
    
    /**
     * Loads class
     * 
     * @param  string $className Class name
     * @return void
     */
    protected function load($className)
    {
        $pathToClass = str_replace('\\', '/', $className);
        
        foreach ($this->_paths as $path) {
            $file = $path . '/' . $pathToClass . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
        
        // Nothing found - throw exception
        require_once 'Exception.php';
        throw new Exception('Class "' . $className . '" not found');
    }
}
