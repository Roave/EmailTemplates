<?php
/**
 * @author Jonas Rosenlind <rosenlindjonas@gmail.com>
 *
 * @copyright LokalByg APS
 */

namespace EmailTemplatesTest\Service\Template\Engine;


use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Entity\TemplateEntity;
use Roave\EmailTemplates\Options\Template\Engine\TwigOptions;
use Roave\EmailTemplates\Service\Template\Engine\Twig;

/**
 * Class TwigTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Service\Template\Engine\Twig
 */
class TwigTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Twig
     */
    protected $twig;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
   protected  $options;

    /**
     * @covers ::__construct
     */
    public function setUp()
    {
        $this->options = new TwigOptions();
        $this->twig = new Twig($this->options);
    }

    /**
     * @covers ::render
     */
    public function testRender()
    {
        $template = '{{name}}, I am your father';
        $params   = [
            'name' => 'Luke'
        ];

        $expectedResponse = 'Luke, I am your father';

        $this->assertSame($expectedResponse, $this->twig->render($template, $params));
    }
} 
