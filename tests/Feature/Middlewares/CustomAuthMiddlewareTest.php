<?php

namespace Feature\Middlewares;

use App\Http\Middleware\CustomAuthMiddleware;
use Illuminate\Http\Request;
use Tests\TestCase;

class CustomAuthMiddlewareTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_middleware_passed()
    {
        $token = config('api.token.delete');
        $request = Request::create('api/delete/1', 'delete');
        $next = function () {
            return response()->json([
                'message' => 'ok'
            ]);
        };
        $request->headers->set('Authorization', sprintf('Bearer %s', $token));

        $middleware = new CustomAuthMiddleware();
        $response = $middleware->handle($request, $next);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"message":"ok"}', $response->getContent());
    }

    public function test_middleware_not_passed(): void
    {
        $request = Request::create('api/delete/1', 'delete');
        $request->headers->set('Authorization', sprintf('Bearer %s', '123456789'));

        $middleware = new CustomAuthMiddleware();
        $response = $middleware->handle($request, function () {});

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('{"message":"Invalid value for bearer token: 123456789"}', $response->getContent());
    }
}
