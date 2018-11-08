<?php
namespace App\Tests\Controller;

use App\Controller\QuizController;
use PHPUnit\Framework\TestCase;

class QuizControllerTest extends TestCase
{

    public function testIndex ()
    {


        $container = $this
            ->createMock("Symfony\Component\DependencyInjection\ContainerInterface", ['getClass']);

        $service = $this
            ->createMock("App\Service\questions", []);

        $response = ['test'];

        $service->expects($this->any())
            ->method("getQuestions")
            ->with($this->equalTo(null))
            ->will($this->returnValue($response));


        $container->expects($this->any())
            ->method("get")
            ->with($this->equalTo('Questions'))
            ->will($this->returnValue($service));



        $controller = new QuizController();


        $controller->setContainer($container);
        $ret = $controller->index(null);

        $this->assertTrue($ret->getContent() === '["test"]');

    }
}
