<?php
namespace tests\components;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

/**
 * Class LinguaLeoTest
 */
class LinguaLeoTest extends TestCase
{
    /**
     * Tests exception rethrowing in case of Guzzle throw RequestException
     *
     * @covers LinguaLeo::getResponse
     * @expectedException libs\components\LinguaLeoApiException
     */
    public function testLinguaLeoApiExceptionThrowsInGetResponse()
    {
        $reflection = new \ReflectionClass('libs\components\LinguaLeo');
        $method = $reflection->getMethod('getResponse');
        $method->setAccessible(true);

        $stub = $this->getMockBuilder('libs\components\LinguaLeo')
            ->setMethods(['getContentByUrl'])
            ->disableOriginalConstructor()
            ->getMock();

        $dummyUrl = 'dummy_uri';

        $stub->method('getContentByUrl')
            ->willReturn('dummy_content')
            ->willThrowException(new RequestException('Dummy request exception', new Request('GET', $dummyUrl)));

        $method->invoke($stub, $dummyUrl);
    }
}