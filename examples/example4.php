<?php
/**
 * Php4Hudson
 *
 * @version $Id: example4.php,v ea04aacef992 2009/07/20 12:54:26 ladislav $
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @package php4hudson
 * @category php4hudson
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 * @license  MIT http://www.opensource.org/licenses/mit-license.php
 * @link http://code.google.com/p/php4hudson/
 * @filesource
 */
/**
 * Replace SVN address in config file
 */
require_once 'Php4Hudson/phpconfighudson.php';

$hudson = new Php4Hudson_ConfigHudson();
$hudson->replaceConfigOptions('config.xml', 'SubversionSCM', 'https://svn.firma.cz/svn/test');