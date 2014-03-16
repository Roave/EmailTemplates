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

namespace Roave\EmailTemplates\Entity;

use DateTime;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class TemplateEntity
 *
 * The basic data structure used to store email templates in the database
 */
class TemplateEntity
{
    /**
     * @var string
     */
    protected $id;

    /**
     * If the example parameters should be updated the next time it's used.
     *
     * @var bool
     */
    protected $updateParameters = false;

    /**
     * @var string
     */
    protected $locale = null;

    /**
     * A set of example parameters
     *
     * @var array
     */
    protected $parameters;

    /**
     * @var string|null
     */
    protected $subject;

    /**
     * @var string|null
     */
    protected $textBody;

    /**
     * @var string|null
     */
    protected $htmlBody;

    /**
     * A description from the developers so the editors know what they are doing.
     *
     * @var string|null
     */
    protected $description;

    /**
     * The last time the parameters where updated at
     *
     * @var \DateTime|null
     */
    protected $paramsUpdatedAt;

    /**
     * @var DateTime|null
     */
    protected $createdAt;

    /**
     * @var DateTime|null
     */
    protected $updatedAt;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = (string) $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = (string) $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $template
     */
    public function setTextBody($template)
    {
        $this->textBody = (string) $template;
    }

    /**
     * @return string
     */
    public function getTextBody()
    {
        return $this->textBody;
    }

    /**
     * @param string $htmlBody
     */
    public function setHtmlBody($htmlBody)
    {
        $this->htmlBody = (string) $htmlBody;
    }

    /**
     * @return null|string
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * @param array $variables
     */
    public function setParameters(array $variables)
    {
        $this->parameters = $variables;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = (string) $subject;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = (string) $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param \DateTime|null $paramsUpdatedAt
     */
    public function setParamsUpdatedAt(DateTime $paramsUpdatedAt = null)
    {
        $this->paramsUpdatedAt = $paramsUpdatedAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getParamsUpdatedAt()
    {
        return $this->paramsUpdatedAt;
    }

    /**
     * @param boolean $updatedParams
     */
    public function setUpdateParameters($updatedParams)
    {
        $this->updateParameters = (bool) $updatedParams;
    }

    /**
     * @return boolean
     */
    public function getUpdateParameters()
    {
        return $this->updateParameters;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
