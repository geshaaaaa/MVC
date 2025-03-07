<?php

return new class implements \App\Commands\Contract\MigrationSample
{
    /**
    * Run migration script 
    * @return string
    */
    public function up(): string
    {
        return "CREATE TABLE notes(
            id INT  AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED, 
            folder_id INT  NOT NULL , 
            title varchar(250) NOT NULL,
            content varchar(250),
            pinned BOOLEAN NOT NULL,
            completed BOOLEAN NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id)  REFERENCES  users(id) ON DELETE CASCADE,
            FOREIGN KEY (folder_id)  REFERENCES  folders(id) ON DELETE CASCADE
        )";
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
