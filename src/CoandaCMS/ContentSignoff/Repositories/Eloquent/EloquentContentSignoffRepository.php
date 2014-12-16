<?php namespace CoandaCMS\ContentSignoff\Repositories\Eloquent;

use CoandaCMS\ContentSignoff\Repositories\ContentSignoffRepositoryInterface;
use CoandaCMS\ContentSignoff\Repositories\Eloquent\Models\SignoffRequest;

class EloquentContentSignoffRepository implements ContentSignoffRepositoryInterface {

    private $signoff_request_model;

    public function __construct(SignoffRequest $signoff_request_model)
    {
        $this->signoff_request_model = $signoff_request_model;
    }

    public function createNewSignoffRequest($version_id)
    {
        return $this->signoff_request_model->create([
            'version_id' => $version_id,
            'status' => 'pending'
        ]);
    }

    public function getById($id)
    {
        return $this->signoff_request_model->find($id);
    }

    public function pending($per_page)
    {
        return $this->signoff_request_model->whereStatus('pending')->paginate($per_page);
    }

    public function history($per_page)
    {
        return $this->signoff_request_model->where('status', '<>', 'pending')->orderBy('updated_at', 'desc')->paginate($per_page);
    }
}