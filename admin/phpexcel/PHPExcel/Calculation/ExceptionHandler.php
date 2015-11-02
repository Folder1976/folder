<?php
/**
 * 
 *
 * Copyright (c) 2006 - 2013 
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   
 * @package    _Calculation
 * @copyright  Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 * @license	http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version	1.7.9, 2013-06-02
 */

/**
 * _Calculation_ExceptionHandler
 *
 * @category   
 * @package    _Calculation
 * @copyright  Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 */
class _Calculation_ExceptionHandler {
	/**
	 * Register errorhandler
	 */
	public function __construct() {
		set_error_handler(array('_Calculation_Exception', 'errorHandlerCallback'), E_ALL);
	}

	/**
	 * Unregister errorhandler
	 */
	public function __destruct() {
		restore_error_handler();
	}
}
