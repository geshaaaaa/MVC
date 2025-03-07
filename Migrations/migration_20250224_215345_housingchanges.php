<?php

return new class implements \App\Commands\Contract\MigrationSample
{
    /**
    * Run migration script 
    * @return string
    */
    public function up(): string
    {
        return 'ALTER TABLE housing
        ADD bedrooms tinyint (1) NOT NULL DEFAULT 0,
        ADD bathrooms  tinyint (1) NOT NULL DEFAULT 0 
    ';
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
