<?php
/**
 * Php4Hudson
 *
 * @version $Id: example2.php,v ea04aacef992 2009/07/20 12:54:26 ladislav $
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @package php4hudson
 * @category php4hudson
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 * @license  MIT http://www.opensource.org/licenses/mit-license.php
 * @link http://code.google.com/p/php4hudson/
 * @filesource
 */
/**
 * Read all config.xml from directory and create jobs in Hudson
 */
require_once ("Php4Hudson/phphudson.php");
$hudson = new Php4Hudson_Hudson('http://localhost:8080/');
$dir = '/tmp/hudson/';
if ($handle = opendir($dir)) {
    echo "Directory handle: $handle\n";
    echo "Files:\n";
    while (false !== ($file = readdir($handle))) {
        echo "$file\n";
        $hudson->createJob(basename(str_replace("-config.xml", "", $file)), file_get_contents($dir . $file));
    }
    closedir($handle);
}
