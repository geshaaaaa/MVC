<?php

namespace App\Validators\NoteValidation;

use App\Enums\SQL\CommandsSQL;
use App\Models\Note;

class UpdateNoteValidator extends Base
{

    static protected ?int $id;
    public static function validate(array $fields = []): bool
    {
        static::$id = $fields['id'];

        $result =  [
            parent::validate($fields),
            static::validateFolderId($fields['folder_id']),
            !static::checkTitleOnDuplicate($fields['title'], $fields['folder_id']),
            static::isBoolean($fields, 'pinned'),
            static::isBoolean($fields, 'completed'),
        ];

        return !in_array(false, $result);
    }

    protected static function checkTitleOnDuplicate(string $title, int $folderId): bool
    {
        $isExists = Note::where('title', value: $title)
            ->and('user_id', value: getAuthId())
            ->and('folder_id', value: $folderId)
            ->and('id', CommandsSQL::NOT_EQUAL, static::$id)
            ->exists();

        if ($isExists) {
            static::setError('title', "Note with title '$title' already exists");
        }

        return $isExists;
    }


}