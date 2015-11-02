<?php
/**
 *  
 *
 *  Copyright (c) 2006 - 2013 
 *
 *  This library is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2.1 of the License, or (at your option) any later version.
 *
 *  This library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public
 *  License along with this library; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *  @category    
 *  @package     _Writer_PDF
 *  @copyright   Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 *  @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 *  @version     1.7.9, 2013-06-02
 */


/**
 *  _Writer_PDF
 *
 *  @category    
 *  @package     _Writer_PDF
 *  @copyright   Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 */
class _Writer_PDF
{

    /**
     * The wrapper for the requested PDF rendering engine
     *
     * @var _Writer_PDF_Core
     */
    private $_renderer = NULL;

    /**
     *  Instantiate a new renderer of the configured type within this container class
     *
     *  @param     $phpExcel          object
     *  @throws _Writer_Exception    when PDF library is not configured
     */
    public function __construct( $phpExcel)
    {
        $pdfLibraryName = _Settings::getPdfRendererName();
        if (is_null($pdfLibraryName)) {
            throw new _Writer_Exception("PDF Rendering library has not been defined.");
        }

        $pdfLibraryPath = _Settings::getPdfRendererPath();
        if (is_null($pdfLibraryName)) {
            throw new _Writer_Exception("PDF Rendering library path has not been defined.");
        }
        $includePath = str_replace('\\', '/', get_include_path());
        $rendererPath = str_replace('\\', '/', $pdfLibraryPath);
        if (strpos($rendererPath, $includePath) === false) {
            set_include_path(get_include_path() . PATH_SEPARATOR . $pdfLibraryPath);
        }

        $rendererName = '_Writer_PDF_' . $pdfLibraryName;
        $this->_renderer = new $rendererName($phpExcel);
    }


    /**
     *  Magic method to handle direct calls to the configured PDF renderer wrapper class.
     *
     *  @param   string   $name        Renderer library method name
     *  @param   mixed[]  $arguments   Array of arguments to pass to the renderer method
     *  @return  mixed    Returned data from the PDF renderer wrapper method
     */
    public function __call($name, $arguments)
    {
        if ($this->_renderer === NULL) {
            throw new _Writer_Exception("PDF Rendering library has not been defined.");
        }

        return call_user_func_array(array($this->_renderer, $name), $arguments);
    }

}
