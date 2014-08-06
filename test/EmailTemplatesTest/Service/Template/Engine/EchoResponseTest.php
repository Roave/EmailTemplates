<?php
/**
 * @author Jonas Rosenlind <rosenlindjonas@gmail.com>
 *
 * @copyright LokalByg APS
 */

namespace EmailTemplatesTest\Service\Template\Engine;


use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Entity\TemplateEntity;
use Roave\EmailTemplates\Service\Template\Engine\EchoResponse;

/**
 * Class EchoResponseTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Service\Template\Engine\EchoResponse
 */
class EchoResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var EchoResponse
     */
    protected $response;

    public function setUp()
    {
        $this->response = new EchoResponse();
    }

    /**
     * @covers ::render
     */
    public function testRender()
    {
        $template = new TemplateEntity();
        $this->assertSame($template, $this->response->render($template, []));
    }
}
