<?php 
/****
 * Copyright (C) 2024 Dave Beusing <david.beusing@gmail.com>
 * 
 * MIT License - https://opensource.org/license/mit/
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the “Software”), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished 
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all 
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION 
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 */
/**
 * Error reporting
 */
error_reporting( E_ALL | E_STRICT );
ini_set( 'display_errors', true );
ini_set( 'html_errors', true );
/**
 * Autoload
 **/
require_once 'src/autoload.php';
/**
 * Includes
 */
use app\Config;
use app\Template;
use app\Shortener;
use app\SQLite3PDO;
/**
 * Globals
 */
$short = new Shortener( Config::AppURL, new SQLite3PDO( Config::AppDatabase ) );
$html = new Template();
/**
 * Main
 */
$mode = filter_input( INPUT_GET, 'mode', FILTER_SANITIZE_SPECIAL_CHARS );
switch( $mode ):
	case 'fetch':
		if( !$short->fetch( filter_input( INPUT_GET, 'uid', FILTER_SANITIZE_SPECIAL_CHARS ) ) ){
			header( 'Location: /', true, 301 );
			exit;
		}
	break;
	case 'add':
		$response = false;
		$url = filter_input( INPUT_POST, 'url', FILTER_SANITIZE_ENCODED );
		if( $url ){
			$response = $short->create( urldecode( $url ) );
		}
		$html->view(
			'assets/html/import.html',
			[
				'Title' => Config::AppName,
				'ActionTarget' => '?mode=add',
				'Response' => ( $response ) ? $response : ''
			]
		);
	break;
	default:
		header( 'Location: /', true, 301 );
		exit;
endswitch;
?>