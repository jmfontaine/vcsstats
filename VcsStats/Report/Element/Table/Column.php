<?php
/**
 * Copyright (c) 2010, Jean-Marc Fontaine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the <organization> nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package VcsStats
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2010 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Report table column
 */
class VcsStats_Report_Element_Table_Column
{
    /**
     *  Aligment types
     */
    const ALIGNMENT_CENTER = 'center';
    const ALIGNMENT_LEFT   = 'left';
    const ALIGNMENT_RIGHT  = 'right';

    /**
     * Column alignment
     *
     * @var string
     */
    protected $_alignment;

    /**
     * Column code
     *
     * @var string
     */
    protected $_code;

    /**
     * Column title
     *
     * @var string
     */
    protected $_title;

    /**
     * Constructor
     *
     * @param string $title     Column title
     * @param string $code      Column code
     * @param string $alignment Column alignment
     */
    public function __construct($title, $code,
        $alignment = self::ALIGNMENT_LEFT)
    {
        $this->setAlignment($alignment);
        $this->setCode($code);
        $this->setTitle($title);
    }

    /**
     * Returns column alignment
     *
     * @return string
     */
    public function getAlignment()
    {
        return $this->_alignment;
    }

    /**
     * Returns column code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Returns column title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Defines column alignment
     *
     * @param string $alignment
     * @return The current instance of the report
     */
    public function setAlignment($alignment)
    {
        $this->_alignment = (string) $alignment;
        return $this;
    }

    /**
     * Defines column code
     *
     * @param string $code
     * @return The current instance of the report
     */
    public function setCode($code)
    {
        $this->_code = (string) $code;
        return $this;
    }

    /**
     * Defines column title
     *
     * @param string $title
     * @return The current instance of the report
     */
    public function setTitle($title)
    {
        $this->_title = (string) $title;
        return $this;
    }
}
