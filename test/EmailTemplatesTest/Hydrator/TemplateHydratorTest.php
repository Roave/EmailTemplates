<?php
/**
 * @author Antoine Hedgcock
 */

namespace EmailTemplatesTest\Hydrator;

use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Entity\TemplateEntity;
use Roave\EmailTemplates\Hydrator\Exception\InvalidArgumentException;
use Roave\EmailTemplates\Hydrator\TemplateHydrator;
use stdClass;

/**
 * Class TemplateHydratorTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Hydrator\TemplateHydrator
 * @covers ::<!public>
 *
 * @group unit
 */
class TemplateHydratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateHydrator
     */
    protected $hydrator;

    protected function setUp()
    {
        $this->hydrator = new TemplateHydrator();
    }

    /**
     * @covers ::hydrate
     */
    public function testHydrateThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            sprintf('Object must be an instance of %s', TemplateEntity::class)
        );

        $this->hydrator->hydrate([], new stdClass());
    }

    /**
     * @covers ::hydrate
     */
    public function testExtractThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            sprintf('Object must be an instance of %s', TemplateEntity::class)
        );

        $this->hydrator->extract(new stdClass());
    }
}
