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

class VcsStats_Report_SectionTest extends PHPUnit_Framework_TestCase
{
    /* Tests */
    public function testAddElement()
    {
        $element = new VcsStats_Report_Element_Table();
        $section = new VcsStats_Report_Section();
        $section->addElement($element);

        $this->assertSame(array($element), $section->getElements());
    }

    public function testAddElementInvalidClass()
    {
        $element = new StdClass();
        $section = new VcsStats_Report_Section();

        $this->setExpectedException('PHPUnit_Framework_Error');
        $section->addElement($element);
    }

    public function testAddElementImplementsFluentInterface()
    {
        $element = new VcsStats_Report_Element_Table();
        $section = new VcsStats_Report_Section();

        $this->assertSame($section, $section->addElement($element));
    }

    public function testGetCode()
    {
        $section = new VcsStats_Report_Section();
        $this->assertSame('', $section->getCode());

        $section->setCode('dummy');
        $this->assertSame('dummy', $section->getCode());
    }

    public function testGetElements()
    {
        $section = new VcsStats_Report_Section();
        $this->assertSame(array(), $section->getElements());

        $element1 = new VcsStats_Report_Element_Table();
        $section->addElement($element1);
        $element2 = new VcsStats_Report_Element_Table();
        $section->addElement($element2);
        $element3 = new VcsStats_Report_Element_Table();
        $section->addElement($element3);

        $this->assertSame(
            array(
                $element1,
                $element2,
                $element3,
            ),
            $section->getElements()
        );
    }

    public function testGetTitle()
    {
        $section = new VcsStats_Report_Section();
        $this->assertSame('', $section->getTitle());

        $section->setTitle('Dummy');
        $this->assertSame('Dummy', $section->getTitle());
    }

    public function testSetCode()
    {
        $section = new VcsStats_Report_Section();

        $section->setCode('dummy');
        $this->assertSame('dummy', $section->getCode());

        $section->setCode(123456);
        $this->assertSame('123456', $section->getCode());
    }

    public function testSetTitle()
    {
        $section = new VcsStats_Report_Section();

        $section->setTitle('Dummy');
        $this->assertSame('Dummy', $section->getTitle());

        $section->setTitle(123456);
        $this->assertSame('123456', $section->getTitle());
    }

    /* Bugs */
}