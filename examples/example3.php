<?php
/**
 * Php4Hudson
 *
 * @version $Id: example3.php,v ea04aacef992 2009/07/20 12:54:26 ladislav $
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @package php4hudson
 * @category php4hudson
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 * @license  MIT http://www.opensource.org/licenses/mit-license.php
 * @link http://code.google.com/p/php4hudson/
 * @filesource
 */
/**
 * Restart Hudson
 */
require_once ("Php4Hudson/phphudson.php");
$hudson = new Php4Hudson_Hudson('http://localhost:8080/');
// good work when hudson run as service
$hudson->restartHudson();
