<?php
namespace Tests;

use Illuminate\Foundation\Testing\TestResponse;
// Laravel 7+
//use Illuminate\Testing\TestResponse;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Assert as PHPUnit;

trait AssertJson
{
    /* @before */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpAssertJson();
    }

    public function setUpAssertJson(): void
    {
        TestResponse::macro('assertJsonStructureExact', function (array $structure = null, $responseData = null) {
            if ($structure === null) {
                return $this->assertJson($this->json());
            }

            if ($responseData === null) {
                $responseData = $this->decodeResponseJson();
            }

            if (!array_key_exists('*', $structure)) {
                $keys = array_map(static function ($value, $key) {
                    return is_array($value) ? $key : $value;
                }, $structure, array_keys($structure));

                PHPUnit::assertEquals(Arr::sortRecursive($keys), Arr::sortRecursive(array_keys($responseData)));
            }

            foreach ($structure as $key => $value) {
                if (is_array($value) && $key === '*') {
                    PHPUnit::assertIsArray($responseData);

                    foreach ($responseData as $responseDataItem) {
                        $this->assertJsonStructureExact($structure['*'], $responseDataItem);
                    }
                } elseif (is_array($value)) {
                    PHPUnit::assertArrayHasKey($key, $responseData);

                    $this->assertJsonStructureExact($structure[$key], $responseData[$key]);
                } else {
                    PHPUnit::assertArrayHasKey($value, $responseData);
                }
            }

            return $this;
        });
    }
}
