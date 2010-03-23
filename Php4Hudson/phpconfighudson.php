<?php
/**
 * Php4Hudson
 *
 * @version $Id: phpconfighudson.php,v 0b29ee27093c 2009/07/20 13:16:04 ladislav $
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @package php4hudson
 * @category php4hudson
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 * @license  MIT http://www.opensource.org/licenses/mit-license.php
 * @link http://code.google.com/p/php4hudson/
 * @filesource
 */
/**
 * Php config class for Hudson management
 * @package php4hudson
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 */
class Php4Hudson_ConfigHudson
{
    /**
     * loadConfigFronDir
     * @todo  wait for implementation
     */
    public function loadConfigsFromDir ()
    {}
    /**
     * Add task ant, shell
     * @todo wait for implementation
     */
    public function getBuilders ()
    {}
    /**
     * @todo wait for implementation
     *
     */
    public function setBuilders ()
    {}
    /**
     * @todo wait for implementation
     *
     */
    public function removeBuilders ()
    {}
    /**
     * Add JavadocArchiver, junit.JUnitResultArchiver
     * @todo wait for implementation
     */
    public function getPublishers ()
    {}
    /**
     * @todo wait for implementation
     *
     */
    public function setPublishers ()
    {}
    /**
     * @todo wait for implementation
     *
     */
    public function removePublishers ()
    {}
    /**
     * get SCM remote urls
     * @param string $config filename
     * @return array
     */
    public function getScmRemoteUrls ($config)
    {
        $xml = simplexml_load_file($config);
        $remotes = $xml->xpath("//scm/locations/hudson.scm.SubversionSCM_-ModuleLocation/remote");
        return (array) $remotes;        
    }
    /**
     * replace Config options 
     * @param string $config in this file
     * @param string $options this type
     * @param string $value replace with this value
     */
    public function replaceConfigOptions($config, $options, $value) {
        switch ($options) {
            // Replace first location in SVN with this value
            case "SubversionSCM": {
                $xml = simplexml_load_file($config);
                $remotes = $xml->xpath("//scm/locations/hudson.scm.SubversionSCM_-ModuleLocation/remote");
                $remotes[0][0] = $value;
                $xml->asXML($config);
            }
            break;
        }
    }
}