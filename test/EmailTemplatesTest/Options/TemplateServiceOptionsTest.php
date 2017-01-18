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

namespace EmailTemplatesTest\Options;


use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Options\TemplateServiceOptions;

/**
 * Class TemplateServiceOptionsTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Options\TemplateServiceOptions
 * @covers ::<!public>
 *
 * @group options
 */
class TemplateServiceOptionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateServiceOptions
     */
    protected $options;

    public function setUp()
    {
        $this->options = new TemplateServiceOptions();
    }

    /**
     * @covers ::setDefaultBody
     * @covers ::getDefaultBody
     */
    public function testSetGetDefaultBody()
    {
        $this->assertEquals('This is the default message for the template with id: %s,locale: %s', $this->options->getDefaultBody());
        $this->options->setDefaultBody('A new, bit thinner body');
        $this->assertEquals('A new, bit thinner body', $this->options->getDefaultBody());
    }

    /**
     * @covers ::getDefaultSubject
     * @covers ::setDefaultSubject
     */
    public function testSetGetDefaultSubject()
    {
        $this->assertEquals('Subject has not yet been set', $this->options->getDefaultSubject());
        $this->options->setDefaultSubject('Subject has been set');
        $this->assertEquals('Subject has been set', $this->options->getDefaultSubject());
    }

    /**
     * @covers ::setEngine
     * @covers ::getEngine
     */
    public function testSetGetEngine()
    {
        $this->assertEquals('twig', $this->options->getEngine());
        $this->options->setEngine('B20');
        $this->assertEquals('B20', $this->options->getEngine());
    }

    /**
     * @covers ::setPredefinedParams
     * @covers ::getPredefinedParams
     */
    public function testSetGetPredefinedParams()
    {
        $this->assertEquals([], $this->options->getPredefinedParams());
        $this->options->setPredefinedParams(['url' => 'http://']);
        $this->assertArrayHasKey('url', $this->options->getPredefinedParams());
    }
}
