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
        $q = new Question();
        $q->setText('test');
        $questions [] = $q;

        $q = new Question();
        $q->setText('test');
        $questions [] = $q;


        $om = self::getObjectManager ($questions);
        $c = new Questions($om);
        $result = $c->getQuestions ();
        $this->assertEquals(count($result), 2);
    }

    public function testQuestionsNotPresents()
    {
        $questions = [];
        $om = self::getObjectManager ($questions);
        $c = new Questions($om);
        $result = $c->getQuestions ();
        $this->assertEquals(count($result), 0);
    }

    private function getObjectManager($questions){
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

        return $objectManager;
    }

    public function testLabel(){
        $c = new Questions();
        $res = $c->getLabel ('test', 'fr');
        $this->assertEquals($res, 'testtraduction : fr');
    }

    public function testLabelOther(){
        $c = new Questions();
        $res = $c->getLabel ('test', 'en');

        $this->assertEquals($res, 'testtranslate : en');
    }

    public function testPaginate(){
        $c = new Questions();
        $res = $c->page (19, 20);

        $this->assertEquals($res, 1);
    }

    public function testPaginateOne(){
        $c = new Questions();
        $res = $c->page (20, 20);

        $this->assertEquals($res, 1);
    }

    public function testPaginateSecondTwo(){
        $c = new Questions();
        $res = $c->page (40, 20);

        $this->assertEquals($res, 2);
    }

    public function testPaginateTwo(){
        $c = new Questions();
        $res = $c->page (21, 20);

        $this->assertEquals($res, 2);
    }

    public function testPaginateTen(){
        $c = new Questions();
        $res = $c->page (199, 20);

        $this->assertEquals($res, 10);
    }

    public function testPaginateZero(){
        $c = new Questions();
        $res = $c->page (0, 20);

        $this->assertTrue($res === 0);
    }

    public function testPaginateZeroDiv(){
        $c = new Questions();
        $actual = $c->page (10, 0);
        $expected = 0;
        $this->assertEquals($expected, $actual);
    }

    public function testPages(){
        $c = new Questions();
        $actual = $c->paginate (3, 8, 10);

        $expected = [
            1 => false,
            2 => false,
            3 => true,
            4 => false,
            5 => false,
            6 => false,
            7 => false,
            8 => false,
        ];
        $this->assertEquals($expected, $actual);

    }

    public function testPagesTwenty(){
        $c = new Questions();
        $actual = $c->paginate (61, 62, 5);

        $expected = [
            61 => true,
            62 => false,

        ];
        $this->assertEquals($expected, $actual);

    }
}