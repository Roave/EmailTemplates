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
use Roave\EmailTemplates\Options\EmailServiceOptions;
use Zend\Mail\Transport\Sendmail;

/**
 * Class EmailServiceOptionsTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Options\EmailServiceOptions
 */
class EmailServiceOptionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var EmailServiceOptions
     */
    protected $options;

    public function setUP()
    {
        $this->options = new EmailServiceOptions();
    }

    /**
     * @covers ::setDefaultLocale
     * @covers ::getDefaultLocale
     */
    public function testSetGetDefaultLocale()
    {
        $this->assertEquals('en-US',$this->options->getDefaultLocale());
        $this->options->setDefaultLocale('sv-SE');
        $this->assertEquals('sv-SE', $this->options->getDefaultLocale());
    }

    /**
     * @covers ::setBcc
     * @covers ::getBcc
     */
    public function testSetGetBcc()
    {
        $this->assertEmpty($this->options->getBcc());
        $this->options->setBcc(['test' => '42']);
        $this->assertEquals(['test' => '42'], $this->options->getBcc());
    }

    /**
     * @covers ::setDefaultTransport
     * @covers ::getDefaultTransport
     */
    public function testSetGetDefaultTransport()
    {
        $this->assertEquals(Sendmail::class, $this->options->getDefaultTransport());
        $this->options->setDefaultTransport('NotSendMail');
        $this->assertEquals('NotSendMail', $this->options->getDefaultTransport());
    }

    /**
     * @covers ::setEncoding
     * @covers ::getEncoding
     */
    public function testSetGetEncoding()
    {
        $this->assertEquals('utf-8', $this->options->getEncoding());
        $this->options->setEncoding('utf-1337');
        $this->assertEquals('utf-1337', $this->options->getEncoding());
    }

    /**
     * @covers ::setFrom
     * @covers ::getFrom
     */
    public function testSetGetFrom()
    {
        $this->assertEquals('webmaster@localhost', $this->options->getFrom());
        $this->options->setFrom('leetmaster@localhost');
        $this->assertEquals('leetmaster@localhost', $this->options->getFrom());
    }

    /**
     * @covers ::setReplyTo
     * @covers ::getReplyTo
     */
    public function testSetGetReplyTo()
    {
        $this->assertEquals('noreply@localhost', $this->options->getReplyTo());
        $this->options->setReplyTo('l33tmaster@localhost');
        $this->assertEquals('l33tmaster@localhost', $this->options->getReplyTo());
    }
}
