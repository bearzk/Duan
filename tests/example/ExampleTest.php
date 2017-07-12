<?php

namespace Duan\Tests\Example;

use Duan\Tests\BaseTestCase;
use Duan\Tests\WithOutMiddlewares;
use Symfony\Component\HttpFoundation\Request;

class ExampleTest extends BaseTestCase
{
    use WithOutMiddlewares;

    /** @test */
    public function case_can_assert_get_result()
    {

        //Arrange
        $this->app()->get('/a-test-path', function () {
            return "this should be seen";
        });

        // Act
        $this->get('/a-test-path');

        // Assertion
        $this->assertSee('this should be seen')
            ->assertDontSee('this is unexpected');
    }

    /** @test */
    public function can_assert_post_result()
    {
        // Arrange
        $this->app()->post('/test-post-path', function (Request $request) {
            $user = $request->get('user');
            $age = $request->get('age');
            return "$user, $age";
        });

        // Act
        $this->post('/test-post-path', [
            'user' => 'bearzk',
            'age' => '10',
        ]);

        // Assertion
        $this->assertSee('bearzk')
            ->assertSee('10')
            ->assertDontSee('maomao');
    }

    /** @test */
    public function can_assert_get_json_result()
    {
        // Arrange
        $this->app()->get('/test-json-get', function () {
            $arr = [
                "user" => "bearzk",
                "age" => 10,
            ];

            return json_encode($arr);
        });

        // Act
        $this->getJson('/test-json-get');

        // Assertion
        $this->assertSeeJson('{"user":"bearzk","age":"10"}');
    }

    /** @test */
    public function can_assert_post_json_result()
    {
        // Arrange
        $this->app()->get('/test-json-post', function (Request $request) {
            $arr = json_decode($request->getContent());
            // Assertion
            $this->assertEquals('my', $arr['name']);
            $this->assertEquals('9', $arr['age']);
            $this->assertDontSee("unexpected string");
        });

        // Act
        $this->postJson('/test-json-post', '{"name":"my", "age": 9}');
    }
}
