<?php

/**
 * Example module.
 */

declare(strict_types=1);

namespace TestPdo8;

use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use Fisharebest\Webtrees\Schema\MigrationInterface;
use Fisharebest\Webtrees\Site;
use Fisharebest\Webtrees\Services\MigrationService;

class Migration0 implements MigrationInterface
{
    public function upgrade(): void
    {
        if(DB::schema()->hasTable('maj_transaction_test')) {
            DB::schema()->drop('maj_transaction_test');
        }
        else {
            //var_dump(DB::connection()->getPdo()->inTransaction());
            
            DB::schema()->create('maj_transaction_test', function(Blueprint $builder) {
                    $builder->integer('test_col');
                });
            
            //var_dump(DB::connection()->getPdo()->inTransaction());
        }
    }
}


return new class extends AbstractModule implements ModuleCustomInterface {
    use ModuleCustomTrait;

    /**
     * How should this module be identified in the control panel, etc.?
     *
     * @return string
     */
    public function title(): string
    {
        return 'PHP8 Transactions';
    }

    /**
     * A sentence describing what this module does.
     *
     * @return string
     */
    public function description(): string
    {
        return 'Test Transaction PHP 8';
    }
    
    public function getTestAction(ServerRequestInterface $request): ResponseInterface
    {
        Site::setPreference('MAJ_SCHEMA_TEST_PDO', '0');
        app(MigrationService::class)->updateSchema('TestPdo8', 'MAJ_SCHEMA_TEST_PDO', 1);
        
        return response(DB::schema()->hasTable('maj_transaction_test') ? 'TABLE CREATED' : 'TABLE DROPPED');
    }
};
