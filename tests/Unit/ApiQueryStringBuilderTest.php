<?php

namespace Tests\Unit;

use ReflectionMethod;
use Tests\BrowserKitTest;
use AttendCheck\Api\QueryStringBuilder;

class ApiQueryStringBuilderTest extends BrowserKitTest
{
    private $expectedResult = "mJGWmqeYm29kaGZqZGhnY52TqpafmKqsmahvZWOdk6OxmJiocWlmZ2tjn5Opl5arn6OgdWVjgZmwbmRsb2trampmb29oaWpram9ra2lr";

    /** @test */
    function it_can_build_data_string()
    {
        $data = [
            'course' => '1106204',
            'section' => '2',
            'semester' => '2',
            'year' => '2558',
            'Key' => '12345678',
        ];

        $queryStringBuilder = new QueryStringBuilder;

        $this->assertEquals(
            'g_ccode=1106204,g_section=2,g_semester=2,g_qyear=2558,Key=12345678',
            $queryStringBuilder->buildDataString($data) 
        );
    }

    /** @test */
    function it_can_encrypt_string()
    {
        $publicKey = '29837483894728397489';

        $privateKey = "2378462376428376471";

        $string = "g_ccode=1106204,g_semester=2,g_qyear=2558,g_section=2,Key=$publicKey";

        $queryStringBuilder = new QueryStringBuilder;

        $this->assertEquals($this->expectedResult, 
            $queryStringBuilder->encrypt($string, $privateKey)
        );
    }

    /** @test */
    function it_can_build_query_string_from_array()
    {
        $queryStringBuilder = new QueryStringBuilder;

        $privateKey = "2378462376428376471";

        //course=1106204&section=2&semester=2&year=2559
        $queryString = $queryStringBuilder->build([
            'course' => '1106204',
            'semester' => '2',
            'year' => '2558',
            'section' => '2',
        ], $privateKey);

        $this->assertEquals($queryString, 
            ['q' => $this->expectedResult]
        );
    }
}
