<?php

namespace Test;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TestCase extends BaseTestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $token;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        parent::setUp();
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * @return mixed
     */
    public function login()
    {
        $response = $this->post('/api/login', ['email' => 'bertrand.kintanar@gmail.com', 'password' => 'retardko'])->response;

        $content = $response->getContent();

        $content_array = json_decode($content, true);
        $this->token = $content_array['token'];

        JWTAuth::setToken($this->token);
        $this->user = JWTAuth::toUser();

        return $content_array;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $cookies
     * @param array  $file
     * @param array  $server
     * @param null   $content
     *
     * @return \Illuminate\Http\Response
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $file = [], $server = [], $content = null)
    {
        if (empty($server)) {
            $server = ['HTTP_Authorization' => 'Bearer '.$this->token];
        }

        return parent::call($method, $uri, $parameters, $cookies, $file, $server, $content);
    }
}
