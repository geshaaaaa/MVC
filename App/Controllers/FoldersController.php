<?php

namespace App\Controllers;

use App\Enums\Http\Status;
use App\Enums\SQL\CommandsSQL;
use App\Models\Folder;
use App\Validators\FolderVal\FolderValidator;
use Core\Controller;
use App\Controllers\BaseApiController;

class FoldersController extends BaseApiController
{

    public function index()
    {
       $folders = Folder::where('user_id', value: getAuthId())
            ->or('user_id', CommandsSQL::IS, null)
            ->orderBy([
                'updated_at' => 'DESC'
            ])
            ->get();


        return $this->response(Status::OK, $folders);
    }

    public function show(int $id)
    {
        return $this->response(Status::OK, Folder::find($id)?->toArray());
    }

    public function store()
    {
        $fields = requestBody();

        if (FolderValidator::validate($fields) && $folder = Folder::createAndReturn([...$fields, 'user_id' => getAuthId()])) {
            return $this->response(Status::OK, $folder->toArray());
        }

        return $this->response(Status::UNPROCESSABLE_ENTITY, $fields, FolderValidator::getErrors());
    }

    public function update(int $id)
    {
        $fields = [
            ...requestBody(),
                'updated_at' => date('Y-m-d H:i:s')
        ];

        if (FolderValidator::validate($fields) && $folder = $this->model->update($fields))
        {
            return $this->response(Status::OK, $folder->toArray());
        }

        return $this->response(Status::UNPROCESSABLE_ENTITY, $fields, FolderValidator::getErrors());
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

    protected function getModel(): string
    {
        return Folder::class;
    }

}