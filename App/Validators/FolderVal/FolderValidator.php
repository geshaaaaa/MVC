<?php

namespace App\Validators\FolderVal;

use App\Validators\BaseValidator;
use App\Models\Folder;

class FolderValidator extends BaseValidator
{
    protected static array $rules = [
        'title' => '/[\w\d\s\(\)\-]{3,}/i'
    ];

    protected static array $errors = [
        'title' => 'Title should contain only characters, numbers and _-() and has length more than 2 symbols'
    ];

    public static function validate(array $fields = []): bool
    {
        $result =  [
            parent::validate($fields),
            !static::checkOnDuplicate($fields['title'])
        ];

        return !in_array(false, $result);
    }


    static protected function checkOnDuplicate(string $title) : bool
    {
        $error = "The folder with title '$title' already exists";

        if (in_array($title, commonFolders())) {
            static::setError('title', $error);
            return true;
        }

        $isExists = Folder::where('title', value: $title)
            ->and('user_id',value:  getAuthId())
            ->exists();

        if ($isExists){
            static::setError('title', $error);
            return true;
        }

        return $isExists;

    }


}