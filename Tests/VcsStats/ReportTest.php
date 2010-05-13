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
 * @subpackage Tests
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2010 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

class VcsStats_ReportTest extends PHPUnit_Framework_TestCase
{
    /* Tests */
    public function testConstructor()
    {
        $vcs            = 'Subversion';
        $repositoryPath = 'http://svn.example.com/repository/';

        $beforeTime = time();
        $report     = new VcsStats_Report($vcs, $repositoryPath);
        $afterTime  = time();

        $this->assertSame($vcs, $report->getVcs());
        $this->assertSame($repositoryPath, $report->getRepositoryPath());
        $this->assertGreaterThanOrEqual($beforeTime, $report->getDate());
        $this->assertLessThanOrEqual($afterTime, $report->getDate());
    }

    public function testAddSection()
    {
        $report = new VcsStats_Report(
            'Subversion',
            'http://svn.example.com/repository/'
        );

        $section = new VcsStats_Report_Section();
        $report->addSection($section);
        $this->assertSame(array($section), $report->getSections());
    }

    public function testAddSectionInvalidClass()
    {
        $report = new VcsStats_Report(
            'Subversion',
            'http://svn.example.com/repository/'
        );

        $this->setExpectedException('PHPUnit_Framework_Error');
        $section = new StdClass();
        $report->addSection($section);
    }

    public function testAddSectionImplementsFluentInterface()
    {
        $report = new VcsStats_Report(
            'Subversion',
            'http://svn.example.com/repository/'
        );

        $section = new VcsStats_Report_Section();
        $this->assertSame($report, $report->addSection($section));
    }

    public function testGetDate()
    {
        $beforeTime = time();
        $report     = new VcsStats_Report(
            'Subversion',
            'http://svn.example.com/repository/'
        );
        $afterTime = time();

        $this->assertGreaterThanOrEqual($beforeTime, $report->getDate());
        $this->assertLessThanOrEqual($afterTime, $report->getDate());
    }

    public function testGetRepositoryPath()
    {
        $repositoryPath = 'http://svn.example.com/repository/';
        $report         = new VcsStats_Report('Subversion', $repositoryPath);

        $this->assertSame($repositoryPath, $report->getRepositoryPath());
    }

    public function testGetSections()
    {
        $report = new VcsStats_Report(
            'Subversion',
            'http://svn.example.com/repository/'
        );

        $section1 = new VcsStats_Report_Section();
        $report->addSection($section1);
        $section2 = new VcsStats_Report_Section();
        $report->addSection($section2);
        $section3 = new VcsStats_Report_Section();
        $report->addSection($section3);

        $this->assertSame(
            array(
                $section1,
                $section2,
                $section3,
            ),
            $report->getSections()
        );
    }

    public function testGetVcs()
    {
        $report = new VcsStats_Report(
            'Subversion',
            'http://svn.example.com/repository/'
        );

        $this->assertSame('Subversion', $report->getVcs());
    }

    public function testSetRepositoryPath()
    {
        $report = new VcsStats_Report('Subversion');
        $this->assertSame('', $report->getRepositoryPath());

        $repositoryPath = 'http://svn.example.com/repository/';
        $report->setRepositoryPath($repositoryPath);
        $this->assertSame($repositoryPath, $report->getRepositoryPath());
    }

    public function testSetRepositoryPathImplementsFluentInterface()
    {
        $report = new VcsStats_Report();

        $repositoryPath = 'http://svn.example.com/repository/';
        $this->assertSame($report, $report->setRepositoryPath($repositoryPath));
    }

    public function testSetVcs()
    {
        $report = new VcsStats_Report();
        $this->assertSame('', $report->getVcs());

        $report->setVcs('Subversion');
        $this->assertSame('Subversion', $report->getVcs());
    }

    public function testSetVcsImplementsFluentInterface()
    {
        $report = new VcsStats_Report();

        $this->assertSame($report, $report->setVcs('Subversion'));
    }

    /* Bugs */
}