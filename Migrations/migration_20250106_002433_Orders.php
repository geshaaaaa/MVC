<?php

return new class implements \App\Commands\Contract\MigrationSample
{
    /**
    * Run migration script 
    * @return string
    */
    public function up(): string
    {
        return "CREATE TABLE orders (
    id INT  AUTO_INCREMENT PRIMARY KEY,       
    user_id INT UNSIGNED NOT NULL,                   
    hotel_id INT NOT NULL,                   
    check_in_date DATE NOT NULL,            
    check_out_date DATE NOT NULL,            
    total_price DECIMAL(10, 2) NOT NULL,     
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending', 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)  REFERENCES  users(id) ON DELETE CASCADE  , 
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE 
    )";
    }

    /**
    * Rollback migration script
    * @return string
    */
    public function down(): string
    {
        return 'DROP TABLE orders';
    }
};
