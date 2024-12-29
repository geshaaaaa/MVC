<?php

namespace App\Commands\Contract;

interface MigrationSample
{
    const TEMPLATE = "<?php

return new class implements \\App\\Commands\\Contract\\MigrationSample
{
    /**
    * Run migration script 
    * @return string
    */
    public function up(): string
    {
        return '';
    }

    /**
    * Rollback migration script
    * @return string
    */
    public function down(): string
    {
        return '';
    }
};
";

    public function up(): string;

    public function down(): string;

}