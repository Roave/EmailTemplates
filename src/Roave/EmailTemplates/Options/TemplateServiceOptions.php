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
 * @author Antoine Hedgecock
 *
 * @copyright 2014 Roave, LLC
 * @license http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace Roave\EmailTemplates\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class TemplateServiceOptions
 *
 * Contains all the available configuration for the template service
 *
 * @property string $engine
 * @property string $defaultSubject
 * @property string $defaultBody
 */
class TemplateServiceOptions extends AbstractOptions
{
    /**
     * The name of the engine to use for rendering
     *
     * @var string
     */
    protected $engine = 'twig';

    /**
     * Default subject set on all template on initial creation
     *
     * @var string
     */
    protected $defaultSubject = 'Subject has not yet been set';

    /**
     * Default body set on all templates on initial creation
     *
     * @var string
     */
    protected $defaultBody = 'This is the default message for the template with id: %s,locale: %s';

    /**
     * @param string $defaultBody
     */
    public function setDefaultBody($defaultBody)
    {
        $this->defaultBody = (string) $defaultBody;
    }

    /**
     * @return string
     */
    public function getDefaultBody()
    {
        return $this->defaultBody;
    }

    /**
     * @param string $defaultSubject
     */
    public function setDefaultSubject($defaultSubject)
    {
        $this->defaultSubject = (string) $defaultSubject;
    }

    /**
     * @return string
     */
    public function getDefaultSubject()
    {
        return $this->defaultSubject;
    }

    /**
     * @param string $engine
     */
    public function setEngine($engine)
    {
        $this->engine = (string) $engine;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }
}
