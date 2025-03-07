<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\Note;
use App\Validators\NoteValidation\Base;
use App\Validators\NoteValidation\CreateNoteValidator;
use App\Validators\NoteValidation\UpdateNoteValidator;

class NotesController extends  BaseApiController
{

    public function index()
    {
        $notes = Note::where('user_id', value: getAuthId())
            ->orderBy([
                'pinned' => 'DESC',
                'completed' => 'ASC',
                'updated_at' => 'DESC'
            ])
            ->get();

        return $this->response(Status::OK, $notes);
    }

    public function show(int $id)
    {
        return $this->response(Status::OK, Note::find($id)?->toArray());
    }

    public function store()
    {
        $fields = requestBody();

        if (CreateNoteValidator::validate($fields) && $folder = Note::createAndReturn([...$fields, 'user_id' => getAuthId()])) {
            return $this->response(Status::OK, $folder->toArray());
        }

        return $this->response(Status::UNPROCESSABLE_ENTITY, $fields, CreateNoteValidator::getErrors());
    }

    public function update(int $id)
    {
        $fields = [
            ...$this->model->toArray(),
            ...requestBody(),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (UpdateNoteValidator::validate($fields) && $note = $this->model->update($fields))
        {
            return $this->response(Status::OK, $note->toArray());
        }

        return $this->response(Status::UNPROCESSABLE_ENTITY, $fields, UpdateNoteValidator::getErrors());
    }

    public function destroy(int $id)
    {
        $result = $this->model->destroy();

        if (!$result)
        {
            return $this->response(Status::UNPROCESSABLE_ENTITY, [],[ 'message' => 'Oops, smth went wrong']);
        }

        return $this->response(Status::OK, $this->model->toArray());
    }


    #[\Override] protected function getModel(): string
    {
        return Note::class;
    }
}