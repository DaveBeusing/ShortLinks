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

namespace App;

class Shortener {
	/**
	 * 
	 */
	private $hostname = '';
	private $connection = null;
	private $UIDchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	private $UIDlength = 5;
	/**
	 * 
	 */
	public function __construct( $hostname, $connection ){
		$this->hostname = $hostname;
		$this->connection = $connection;
	}
	/**
	 * 
	 */
	public function create( $url ){
		$uid = $this->generateUID();
		$date = date( 'Y-m-d H:i:s' );
		$stmt = $this->connection->prepare( 'INSERT INTO url ( uid, url, created ) VALUES ( :uid, :url, :created ) ' );
		$stmt->bindParam( ':uid', $uid, \PDO::PARAM_STR );
		$stmt->bindParam( ':url', $url, \PDO::PARAM_STR );
		$stmt->bindParam( ':created', $date, \PDO::PARAM_STR );
		if( $stmt->execute() ){
			return $this->hostname.$uid;
		}
		return false;
	}
	/**
	 * 
	 */
	public function fetch( $uid ){
		if( strlen( $uid ) != $this->UIDlength ){
			return false;
		}
		$stmt = $this->connection->prepare( 'SELECT * FROM url WHERE uid = :uid LIMIT 0,1' );
		$stmt->bindParam( ':uid', $uid, \PDO::PARAM_STR );
		if( $stmt->execute() ){
			$url = $stmt->fetch( \PDO::FETCH_ASSOC );
			if( $url ){
				$this->update( $uid );
				$this->redirect( $url['url'] );
				exit;
			}
		}
		return false;
	}
	/**
	 * 
	 */
	private function update( $uid ){
		$date = date( 'Y-m-d H:i:s' );
		$stmt = $this->connection->prepare( 'UPDATE url SET views = views + 1, accessed = :date WHERE uid = :uid' );
		$stmt->bindParam( ':uid', $uid, \PDO::PARAM_STR );
		$stmt->bindParam( ':date', $date, \PDO::PARAM_STR );
		$stmt->execute();
	}
	/**
	 * 
	 */
	private function generateUID(){
		for($i=0, $z=strlen($this->UIDchars)-1, $s=$this->UIDchars[rand(0,$z)], $i=1; $i!=$this->UIDlength; $x=rand(0,$z), $s.=$this->UIDchars[$x], $s=($s[$i]==$s[$i-1]?substr($s,0,-1):$s),$i=strlen($s));
		return $s;
	}
	/**
	 * 
	 */
	private function redirect( $url ){
		header( 'HTTP/1.1 301 Moved Permanently', true, 301 );
		header( 'Location: '.$url, true, 301 );
		exit;
	}
}
?>