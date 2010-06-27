<?php
/******
* 	Cafepress API PHP
*    Copyright (C) 2010 Chad Albers <calbers@neomantic.com>
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
**/

require_once 'Store.php';

class Cafepress_User {

	protected	$__email		= '';
	protected	$__password		= '';
	protected   $__store		= null;
	protected	$__token		= '';


	public function __construct( $email, $password, $store = null ) {
		$this->__email = $email;
		$this->__password = $password;
		$this->__store = $store
	}

	public function setAccountCredentials( $email, $password, $store ) {
		$this->setEmail( $email );
		$this->setPassword( $password );
		$this->setStore( $store );
	}

	public function setEmail( $email ) {
		if ( $email != $this->__email ) {
			$this->__email = $email;
			$this->__revokeToken();
		}
	}

	public function setPassword( $password ) {
		if ( $password != $this->__password ) {
			$this->__password = $password;
			$this->__revokeToken();
		}
	}

	public function setStore( $store ) {
		$this->__store = $store;
		$this->__revokeToken();
	}

	protected function __revokeToken() {
		$this->__token = '';
	}

	public function isAuthenticated() {
		return !empty( $this->__token );
	}

	public function authenticate( $email = '', $password = '', $store = null ) {

		$this->setAccountCredentials( $email, $password, $store );

		if ( ( $this->__email != '' ) &&
			 ( $this->__password != '' ) &&
			 ( $this->__store != null ) ) {

			$request = new Cafepress_UserRequest(
										$this->__email,
										$this->__password,
										$this->__store->appKey
										 );

			$response = $request->response();

			if ( !$response->hasError() ) {
				$this->__token = $response->queryToken();
			}

		}

		return $this->getUserToken();
	}

	public function getUserToken(){
		return $this->__token;
	}
}
