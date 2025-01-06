<?php

return new class implements \App\Commands\Contract\MigrationSample
{
    /**
    * Run migration script 
    * @return string
    */
    public function up(): string
    {
        return 'CREATE TABLE hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,       
    name VARCHAR(150) NOT NULL,            
    location VARCHAR(255) NOT NULL,          
    description TEXT,                        
    price_per_night DECIMAL(10, 2) NOT NULL, 
    rating DECIMAL(3, 2),                    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
);
';
    }

    /**
    * Rollback migration script
    * @return string
    */
    public function down(): string
    {
        return 'DROP TABLE hotels';
    }
};
