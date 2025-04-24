<?php

return new class implements \App\Commands\Contract\MigrationSample
{
    /**
    * Run migration script 
    * @return string
    */
    public function up(): string
    {
        return 'CREATE TABLE reservations(
    ID INT PRIMARY KEY AUTO_INCREMENT,
    users_id INT UNSIGNED,
    HOUSING_ID INT NOT NULL,
    CHECK_IN DATE NOT NULL,
    CHECK_OUT DATE NOT NULL,
    STATUS ENUM(\'pending\', \'confirmed\',\'cancelled\') DEFAULT \'pending\',
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    notes TEXT,
    FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (Housing_id) REFERENCES housing(id) ON DELETE CASCADE
 
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
