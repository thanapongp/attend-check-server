<?php

namespace AttendCheck\Api;

use Illuminate\Http\Request;
use GuzzleHttp\Client as HttpClient;

class Requestor
{
    /**
     * Search course API endpoint.
     *
     * @var string
     */
    const API_COURSE_ENDPOINT = "http://tqf.ubu.ac.th/another/search_class.php";

    /**
     * Search course's enrollment API endpoint.
     *
     * @var string
     */
    const API_ENROLLMENT_ENDPOINT = "http://tqf.ubu.ac.th/another/search_enroll.php";

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

    /**
     * Making an API call to search course endpont.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return string JSON Response
     */
    public function searchCourse(Request $request)
    {
        $requestQuery = $this->queryStringBuilder->build(
            $request->only($this->parameters), env('TQF_PRIVATEKEY')
        );

        $response = $this->client->request('GET', self::API_COURSE_ENDPOINT, [
            'query' => $requestQuery
        ]);

        // Remove any hidden characters before returning the response.
        $response = json_decode($this->removeHiddenChars($response->getBody()));

        return $response;
    }

    public function searchEnrollment($course)
    {
        $parameters = [
            'course' => $course->code,
            'section' => $course->section,
            'semester' => $course->semester(),
            'year' => $course->year,
        ];

        $requestQuery = $this->queryStringBuilder->build(
            $parameters, env('TQF_PRIVATEKEY')
        );

        $response = $this->client->request('GET', self::API_ENROLLMENT_ENDPOINT, [
            'query' => $requestQuery
        ]);

        $response = json_decode($this->removeHiddenChars($response->getBody()));

        return $response->STUDENT;
    }

    /**
     * Remove hidden characters that makes PHP see JSON response
     * as an invalid JSON.
     * 
     * @source http://stackoverflow.com/questions/17219916/json-decode-returns-json-error-syntax-but-online-formatter-says-the-json-is-ok
     * @param  string $jsonString
     * @return string
     */
    private function removeHiddenChars($jsonString)
    {
        // This will remove unwanted characters.
        // Check http://www.php.net/chr for details
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
