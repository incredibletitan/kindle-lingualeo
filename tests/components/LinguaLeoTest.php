<?php
namespace tests\components;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use libs\components\LinguaLeo;

/**
 * Class LinguaLeoTest
 */
class LinguaLeoTest extends TestCase
{
    /**
     * Tests exception rethrowing in case of Guzzle throw RequestException
     *
     * @covers LinguaLeo::getResponse
     * @expectedException \libs\components\LinguaLeoApiException
     */
    public function testLinguaLeoApiExceptionThrowsInCaseOfRequestExceptionThrewInGetResponse()
    {
        $method = $this->getReflectedMethod('getResponse');
        $stub = $this->getMockedObject(['getContentByUrl']);

        $dummyUrl = 'dummy_url';

        $stub->method('getContentByUrl')
            ->willReturn('dummy_content')
            ->willThrowException(new RequestException('Dummy request exception', new Request('GET', $dummyUrl)));

        $method->invoke($stub, $dummyUrl);
    }

    /**
     * @covers LinguaLeo::getTranslations
     * @expectedException \InvalidArgumentException
     */
    public function testGetTranslationsThrowsInvalidArgumentException()
    {
        $stub = $this->getMockedObject(['getResponse']);
        $stub->method('getResponse')
            ->willReturn('Dummy response which doesn\'t contain needed element');
        $stub->getTranslations('dummy_translation');
    }

    /**
     * @covers LinguaLeo::getTranslations
     * @expectedException \libs\components\LinguaLeoApiException
     */
    public function testGetTranslationsThrowsLinguaLeoApiException()
    {
        $stub = $this->getMockedObject(['getResponse']);
        $stub->method('getResponse')
            ->willReturn(['translate' => ['a' => 'a'], 'error_msg' => 'Bad translation']);
        $stub->getTranslations('dummy_tranlation');
    }

    /**
     * Tests exception rethrowing in case of Guzzle throw RequestException
     *
     * @covers LinguaLeo::getResponse
     * @expectedException \libs\components\LinguaLeoApiException
     */
    public function testLinguaLeoApiExceptionThrowsInCaseOfInvalidJSONInGetResponse()
    {
        $method = $this->getReflectedMethod('getResponse');
        $stub = $this->getMockedObject(['getContentByUrl']);

        //Try to convert not valid JSON must throw LinguaLeoApiException
        $stub->method('getContentByUrl')
            ->willReturn('[{test1": "1","test2": "2"}]');

        $method->invoke($stub, 'test.cc');
    }

    /**
     * @covers LinguaLeo::getResponse
     */
    public function testGetResponse()
    {
        $method = $this->getReflectedMethod('getResponse');
        $stub = $this->getMockedObject(['getContentByUrl']);

        $dummyUrl = 'dummy_url';

        //Without JSON encode
        $stub->expects($this->at(0))
            ->method('getContentByUrl')
            ->willReturn('dummy_content');

        $this->assertEquals('dummy_content', $method->invoke($stub, $dummyUrl, [], false));

        //With JSON encode
        $stub->expects($this->at(0))
            ->method('getContentByUrl')
            ->willReturn('[{"test1": "1","test2": "2"}]');

        $this->assertEquals([["test1" => '1', "test2" => '2']], $method->invoke($stub, $dummyUrl));
    }

    /**
     * Returns private or protected method got by ReflectionClass
     *
     * @param string $method
     *
     * @return \ReflectionMethod|null
     */
    private function getReflectedMethod($method)
    {
        $reflection = new \ReflectionClass(LinguaLeo::class);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * Get mock of LinguaLeo class
     *
     * @param array $allowedMethods - Methods for mocking
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockedObject(array $allowedMethods)
    {
        return $this->getMockBuilder(LinguaLeo::class)
            ->setMethods($allowedMethods)
            ->disableOriginalConstructor()
            ->getMock();
    }
}