<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, AssertJson;

    protected $admin;
    protected $notAdmin;

    public function create(string $model, array $attributes = [], $resource = true) {
        $resourceModel = factory("App\\$model")->create($attributes);
        $resourceClass = "App\\Http\\Resources\\$model";

        if(!$resource){
            return $resourceModel;
        }

        return new $resourceClass($resourceModel);
    }

    public function setUp(): void
    {
        $this->hotfixSqlite();
        parent::setUp();
        $this->setUpAssertJson();

        $this->admin = factory(\App\User::class)->states(['admin', 'approved'])->create();
        $this->notAdmin = factory(\App\User::class)->states(['colaborador', 'approved'])->create();

//        Artisan::call('db:seed', array('--class'=>'DatabaseSeeder'));
//        Artisan::call('passport:install');
    }

    /**
     * Fix for: BadMethodCallException : SQLite doesn't support dropping foreign keys (you would need to re-create the table).
     */
    public function hotfixSqlite()
    {
        \Illuminate\Database\Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
            return new class($connection, $database, $prefix, $config) extends \Illuminate\Database\SQLiteConnection {
                public function getSchemaBuilder()
                {
                    if ($this->schemaGrammar === null) {
                        $this->useDefaultSchemaGrammar();
                    }
                    return new class($this) extends \Illuminate\Database\Schema\SQLiteBuilder {
                        protected function createBlueprint($table, \Closure $callback = null)
                        {
                            return new class($table, $callback) extends \Illuminate\Database\Schema\Blueprint {
                                public function dropForeign($index)
                                {
                                    return new \Illuminate\Support\Fluent();
                                }
                            };
                        }
                    };
                }
            };
        });
    }

    public function getTable(string $model): string {
        // could also be used with `with(new Model)->getTable()`
        return resolve('\\App\\'.$model)->getTable();
    }
}
