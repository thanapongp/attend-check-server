<?php

namespace AttendCheck\Api;

use Illuminate\Http\Request;
use GuzzleHttp\Client as HttpClient;

class Requestor
{
    const API_PUBLIC_KEY = "29837483894728397489";

    const API_COURSE_ENDPOINT = "http://tqf.ubu.ac.th/another/search_class.php";

    /**
     * Instance of GuzzleHttp\Client
     * 
     * @var \GuzzleHttp\Client
     */
    protected $client;

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
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    public function searchCourse(Request $request)
    {
        $request = $request->only($this->parameters);

        $response = $this->client->request('GET', API_COURSE_ENDPOINT, [
            'query' => $this->buildQueryString($request)
        ]);
    }
}