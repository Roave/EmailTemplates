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

namespace EmailTemplatesTest\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Entity\TemplateEntity;
use Roave\EmailTemplates\Repository\TemplateRepository;

/**
 * Class TemplateRepository
 *
 * @coversDefaultClass \Roave\EmailTemplates\Repository\TemplateRepository
 * @covers ::<!public>
 *
 * @group repository
 */
class TemplateRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectRepository|PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectRepository;

    /**
     * @var templateRepository
     */
    protected $templateRepo;

    /**
     * @covers ::__construct
     */
    protected function setUp()
    {
        $this->objectRepository = $this->getMock(ObjectRepository::class);

        $this->templateRepo = new TemplateRepository(
            $this->objectRepository
        );
    }

    /**
     * @covers ::getById
     */
    public function testGetById()
    {
        $id       = 42;
        $template = new TemplateEntity();

        $this->objectRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['id' => $id])
            ->will($this->returnValue($template));

        $this->assertSame($template, $this->templateRepo->getById($id));
    }

    /**
     * @covers ::getByIdAndLocale
     */
    public function testGetByIdAndLocale()
    {
        $id     = 1337;
        $locale = 'sv_SE';
        $template = new TemplateEntity();


        $this->objectRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $id, 'locale' => $locale])
            ->will($this->returnValue($template));

        $this->assertSame($template, $this->templateRepo->getByIdAndLocale($id, $locale));
    }

    /**
     * @covers ::getById
     */
    public function testGetByWithNullResponse()
    {
        $id = 42;

        $this->objectRepository
            ->expects($this->once())
            ->method('findBy')
            ->with(['id' => $id])
            ->will($this->returnValue(null));

        $this->assertNull($this->templateRepo->getById($id));
    }

    /**
     * @covers ::getByIdAndLocale
     */
    public function testGetByIdAndLocaleWithNullResponse()
    {
        $id     = 1337;
        $locale = 'sv_SE';

        $this->objectRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => $id, 'locale' => $locale])
            ->will($this->returnValue(null));

        $this->assertNull($this->templateRepo->getByIdAndLocale($id, $locale));
    }
}
