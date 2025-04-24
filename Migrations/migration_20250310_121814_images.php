<?php

return new class implements \App\Commands\Contract\MigrationSample
{
    /**
    * Run migration script 
    * @return string
    */
    public function up(): string
    {
        return 'CREATE TABLE images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    housing_id INT,
    user_id INT UNSIGNED,
    image_url VARCHAR(255), -- Ссылка на изображение
    FOREIGN KEY (housing_id) REFERENCES housing(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);';
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
