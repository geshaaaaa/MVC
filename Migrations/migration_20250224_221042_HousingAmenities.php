<?php

return new class implements \App\Commands\Contract\MigrationSample
{
    /**
    * Run migration script 
    * @return string
    */
    public function up(): string
    {
        return ' CREATE TABLE housing_amenities(
    id INT PRIMARY KEY AUTO_INCREMENT,
    housing_id INT NOT NULL,
    amenity_id INT NOT NULL,
    value tinyint (1) NOT NULL DEFAULT 0, 
    FOREIGN KEY (housing_id) REFERENCES housing(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE)';
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
