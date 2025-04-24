<?php

return new class implements \App\Commands\Contract\MigrationSample
{
    /**
    * Run migration script 
    * @return string
    */
    public function up(): string
    {
        return 'CREATE TABLE housing(
    id INT  PRIMARY KEY AUTO_INCREMENT,
    users_id INT UNSIGNED,
    type ENUM(\'hotel\', \'villa\', \'apartment\') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    location VARCHAR(255) NOT NULL,
    guests_capacity_max INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )';
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
