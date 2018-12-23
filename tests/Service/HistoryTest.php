<?php
namespace App\Tests\Service;

use App\Entity\Client;
use App\Service\Common;
use App\Service\CommonService;
use App\Service\History;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class HistoryTest extends TestCase
{


    public function testQuestionsNotPresents()
    {
        $objectManager = $this->createMock(ObjectManager::class);
$c = new CommonService();
$client = new Client();
$c->setClient ($client);
        $h = new History($objectManager);

        $result = $h->getHistory ();
        $this->assertEquals(count($result), 0);
    }

   public function testCommonSetClient()

   {

      // $request->query= new ParameterBag(['device'=>$device]);
       $em = $this->createMock(ObjectManager::class);

       // you can overwrite any value you want through the constructor if you need more control
       $fakeRequest = Request::create('/mail/place', 'GET');
$params = new ParameterBag(['clientName' => 'place']);
$fakeRequest->attributes->add(['clientName' => 'place']);
       //$fakeRequest->setSession(new Session(new MockArraySessionStorage()));
       $requestStack = new RequestStack();

       $requestStack->push($fakeRequest);


       $expeted = 'place';
 $actual =      $requestStack->getCurrentRequest ()->get('clientName');
Common::setClient ($em, $requestStack);
$client = Common::getClient ();
       $this->assertEquals($expeted, $actual);

   }



}