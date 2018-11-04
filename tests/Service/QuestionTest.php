<?php
namespace App\Tests\Service;

use App\Service\Questions;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Question;
class QuestionTest extends TestCase
{
    public function testQuestionsPresents()
    {
        // add test
        $q = new Question();
        $q->setText('test');
        $questions [] = $q;

        $q = new Question();
        $q->setText('test');
        $questions [] = $q;

        // Now, mock the repository so it returns the mock of the employee
        $repository = $this->createMock(ObjectRepository::class);
        // use getMock() on PHPUnit 5.3 or below
        // $employeeRepository = $this->getMock(ObjectRepository::class);
        $repository->expects($this->any())
            ->method('findBy')
            ->willReturn($questions);

        // Last, mock the EntityManager to return the mock of the repository
        $objectManager = $this->createMock(ObjectManager::class);
        // use getMock() on PHPUnit 5.3 or below
        // $objectManager = $this->getMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);


        $c = new Questions($objectManager);


        $result = $c->getQuestions ();


        $this->assertEquals(count($result), 2);
    }

    public function testQuestionsNotPresents()
    {

        $questions = [];



        // Now, mock the repository so it returns the mock of the employee
        $repository = $this->createMock(ObjectRepository::class);
        // use getMock() on PHPUnit 5.3 or below
        // $employeeRepository = $this->getMock(ObjectRepository::class);
        $repository->expects($this->any())
            ->method('findBy')
            ->willReturn($questions);

        // Last, mock the EntityManager to return the mock of the repository
        $objectManager = $this->createMock(ObjectManager::class);
        // use getMock() on PHPUnit 5.3 or below
        // $objectManager = $this->getMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);


        $c = new Questions($objectManager);
        $result = $c->getQuestions ();
//var_dump($result);
        $this->assertEquals(count($result), 0);
    }

}