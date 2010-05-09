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
 * @package vcsstats
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2010 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Report class
 */
class VcsStats_Report
{
    protected $_date;
    protected $_repositoryPath;
    protected $_sections = array();
    protected $_vcs;

    public function __construct($vcs = null, $repositoryPath = null)
    {
        $this->_date = time();

        if (null !== $repositoryPath) {
            $this->_repositoryPath = $repositoryPath;
        }
        if (null !== $vcs) {
            $this->_vcs = $vcs;
        }
    }

    public function addSection(VcsStats_Report_Section $section)
    {
        $this->_sections[] = $section;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function getRepositoryPath()
    {
        return $this->_repositoryPath;
    }

    public function getSections()
    {
        return $this->_sections;
    }

    public function getVcs()
    {
        return $this->_vcs;
    }

    public function setRepositoryPath($repositoryPath)
    {
        $this->_repositoryPath = (string) $repositoryPath;
    }

    public function setVcs($vcs)
    {
        $this->_vcs = (string) $vcs;
    }
}
