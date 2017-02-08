<?php

namespace AttendCheck\Api;

use Illuminate\Http\Request;
use GuzzleHttp\Client as HttpClient;
use AttendCheck\Api\QueryStringBuilder;

class Requestor
{
    const API_COURSE_ENDPOINT = "http://tqf.ubu.ac.th/another/search_class.php";

    /**
     * Instance of GuzzleHttp\Client
     * 
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Instance of \AttendCheck\Api\QueryStringBuilder
     * 
     * @var \AttendCheck\Api\QueryStringBuilder
     */
    protected $queryStringBuilder;

    /**
     * List of parameters to send to the TQF Server.
     * 
     * @var array
     */
    protected $parameters = [
        'course', 'section', 'semester', 'year',
    ];

    /**
     * Create new instance of class.
     *
     * @return void
     */
    public function __construct(HttpClient $client, QueryStringBuilder $queryStringBuilder)
    {
        $this->client = $client;
        $this->queryStringBuilder = $queryStringBuilder;
    }

    public function searchCourse(Request $request)
    {
        $request = $request->only($this->parameters);

        $response = $this->client->request('GET', self::API_COURSE_ENDPOINT, [
            'query' => $this->queryStringBuilder->build(
                $request, env('TQF_PRIVATEKEY')
            )
        ]);

        return $this->removeHiddenChars($response->getBody());
    }

    private function removeHiddenChars($jsonString)
    {
        for ($i = 0; $i <= 31; ++$i) { 
            $jsonString = str_replace(chr($i), "", $jsonString); 
        }
        $jsonString = str_replace(chr(127), "", $jsonString);

        // This is the most common part
        // Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
        // here we detect it and we remove it, basically it's the first 3 characters 
        if (0 === strpos(bin2hex($jsonString), 'efbbbf')) {
            $jsonString = substr($jsonString, 3);
        }

        return $jsonString;
    }
}