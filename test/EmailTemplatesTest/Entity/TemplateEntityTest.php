<?php
/**
 * Copyright (c) 2014 Roave, LLC.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author    Antoine Hedgecock
 * @author    Jonas Rosenlind
 *
 * @copyright 2014 Roave, LLC
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace EmailTemplatesTest\Entity;

use DateTime;
use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Entity\TemplateEntity;

/**
 * Class TemplateEntityTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Entity\TemplateEntity
 * @covers ::<!public>
 *
 * @group entity
 */
class TemplateEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateEntity
     */
    private $template;

    protected function setUp()
    {
        $this->template = new TemplateEntity();
    }

    /**
     * @covers ::setId
     * @covers ::getId
     */
    public function testSetGetId()
    {
        $this->assertEmpty($this->template->getId());
        $this->template->setId(1);
        $this->assertEquals(1, $this->template->getId());
    }

    /**
     * @covers ::setUuid
     * @covers ::getUuid
     */
    public function testSetGetUuid()
    {
        $this->assertEmpty($this->template->getUuid());
        $this->template->setUuid(1);
        $this->assertEquals(1, $this->template->getUuid());
    }

    /**
     * @covers ::setLocale
     * @covers ::getLocale
     */
    public function testSetGetLocale()
    {
        $this->assertEmpty($this->template->getLocale());
        $this->template->setLocale('en-US');
        $this->assertEquals('en-US', $this->template->getLocale());
    }

    /**
     * @covers ::getTextBody
     * @covers ::setTextBody
     */
    public function testSetGetTextBody()
    {
        $this->assertEmpty($this->template->getTextBody());
        $this->template->setTextBody('Foobar');
        $this->assertEquals('Foobar', $this->template->getTextBody());
    }

    /**
     * @covers ::getHtmlBody
     * @covers ::setHtmlBody
     */
    public function testSetGetHtmlBody()
    {
        $this->assertEmpty($this->template->getHtmlBody());
        $this->template->setHtmlBody('f00bar');
        $this->assertEquals('f00bar', $this->template->getHtmlBody());
    }

    /**
     * @covers ::setParameters
     * @covers ::getParameters
     */
    public function testSetGetParameters()
    {
        $this->assertEmpty($this->template->getParameters());
        $this->template->setParameters(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $this->template->getParameters());
    }

    /**
     * @covers ::getSubject
     * @covers ::setSubject
     */
    public function testSetGetSubject()
    {
        $this->assertEmpty($this->template->getSubject());
        $this->template->setSubject('Foobar');
        $this->assertEquals('Foobar', $this->template->getSubject());
    }

    /**
     * @covers ::getDescription
     * @covers ::setDescription
     */
    public function testSetGetDescription()
    {
        $this->assertEmpty($this->template->getDescription());
        $this->template->setDescription('bar');
        $this->assertEquals('bar', $this->template->getDescription());
    }

    /**
     * @covers ::setParametersUpdatedAt
     * @covers ::getParametersUpdatedAt
     */
    public function testSetGetParamsUpdatedAt()
    {
        $date = new DateTime();
        $this->assertNull($this->template->getParametersUpdatedAt());
        $this->template->setParametersUpdatedAt($date);
        $this->assertEquals($date, $this->template->getParametersUpdatedAt());
    }

    /**
     * @covers ::setUpdateParameters
     * @covers ::getUpdateParameters
     */
    public function testSetGetUpdateParameters()
    {
        $this->assertFalse($this->template->getUpdateParameters());
        $this->template->setUpdateParameters(true);
        $this->assertEquals(true, $this->template->getUpdateParameters());
    }

    /**
     * @covers ::getCreatedAt
     * @covers ::setCreatedAt
     */
    public function testSetGetCreatedAt()
    {
        $date = new DateTime();
        $this->assertNull($this->template->getCreatedAt());
        $this->template->setCreatedAt($date);
        $this->assertEquals($date, $this->template->getCreatedAt());
    }

    /**
     * @covers ::getUpdatedAt
     * @covers ::setUpdatedAt
     */
    public function testSetGetUpdatedAt()
    {
        $date = new DateTime();
        $this->assertNull($this->template->getUpdatedAt());
        $this->template->setUpdatedAt($date);
        $this->assertEquals($date, $this->template->getUpdatedAt());
    }
}
