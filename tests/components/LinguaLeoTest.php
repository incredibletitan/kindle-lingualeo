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
     * @covers LinguaLeo::getResponse
     */
    public function testGetResponse()
    {
        $reflection = new \ReflectionClass('libs\components\LinguaLeo');
        $method = $reflection->getMethod('getResponse');
        $method->setAccessible(true);

        $stub = $this->getMockBuilder('libs\components\LinguaLeo')
            ->setMethods(['getContentByUrl'])
            ->disableOriginalConstructor()
            ->getMock();

        $stub->method('getContentByUrl')
            ->willThrowException(new RequestException('Dummy request exception', new Request('GET', 'dummy_uri')));
    }
}