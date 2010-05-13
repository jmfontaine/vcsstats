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
 * Report class
 */
class VcsStats_Report
{
    /**
     * Unix timestamp of the report creation
     * @var int
     */
    protected $_date;

    /**
     * Path to the repository
     * @var string
     */
    protected $_repositoryPath = '';

    /**
     * Report sections
     * @var array
     */
    protected $_sections = array();

    /**
     * Name of the VCS
     * @var string
     */
    protected $_vcs = '';

    /**
     * Constructor
     *
     * @param string $vcs            Name of the VCS
     * @param string $repositoryPath Path to the repository
     * @return void
     */
    public function __construct($vcs = null, $repositoryPath = null)
    {
        $this->_date = time();

        if (null !== $repositoryPath) {
            $this->setRepositoryPath($repositoryPath);
        }
        if (null !== $vcs) {
            $this->setVcs($vcs);
        }
    }

    /**
     * Adds a section to the report
     *
     * @param VcsStats_Report_Section $section Section to be added
     * @return The current instance of the report
     */
    public function addSection(VcsStats_Report_Section $section)
    {
        $this->_sections[] = $section;
        return $this;
    }

    /**
     * Returns the Unix timestamp of the creation of the report
     *
     * @return int
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * Returns the path to the repository
     *
     * @return string
     */
    public function getRepositoryPath()
    {
        return $this->_repositoryPath;
    }

    /**
     * Returns the report sections
     *
     * @return array
     */
    public function getSections()
    {
        return $this->_sections;
    }

    /**
     * Returns VCS name
     *
     * @return string
     */
    public function getVcs()
    {
        return $this->_vcs;
    }

    /**
     * Defines the path to the repository
     *
     * @param string $repositoryPath Path to the repository
     * @returns The current instance of the report
     */
    public function setRepositoryPath($repositoryPath)
    {
        $this->_repositoryPath = (string) $repositoryPath;
        return $this;
    }

    /**
     * Returns the name of the VCS
     *
     * @param string $vcs
     * @returns The current instance of the report
     */
    public function setVcs($vcs)
    {
        $this->_vcs = (string) $vcs;
        return $this;
    }
}
