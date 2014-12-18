<?php namespace CoandaCMS\ContentSignoff\Repositories\Eloquent;

use CoandaCMS\ContentSignoff\Repositories\ContentSignoffRepositoryInterface;
use CoandaCMS\ContentSignoff\Repositories\Eloquent\Models\SignoffRequest;

class EloquentContentSignoffRepository implements ContentSignoffRepositoryInterface {

    /**
     * @var SignoffRequest
     */
    private $signoff_request_model;

    /**
     * @param SignoffRequest $signoff_request_model
     */
    public function __construct(SignoffRequest $signoff_request_model)
    {
        $this->signoff_request_model = $signoff_request_model;
    }

    /**
     * @param $version_id
     * @param $version_number
     * @param $page_id
     * @param $page_name
     * @param $requester_id
     * @return mixed
     */
    public function createNewSignoffRequest($version_id, $version_number, $page_id, $page_name, $requester_id)
    {
        return $this->signoff_request_model->create([
            'version_id' => $version_id,
            'version' => $version_number,
            'page_id' => $page_id,
            'page_name' => $page_name,
            'requested_by' => $requester_id,
            'status' => 'pending'
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->signoff_request_model->find($id);
    }

    /**
     * @param $per_page
     * @return mixed
     */
    public function pending($per_page)
    {
        return $this->signoff_request_model->whereStatus('pending')->paginate($per_page);
    }

    /**
     * @param $per_page
     * @return mixed
     */
    public function history($per_page)
    {
        return $this->signoff_request_model->where('status', '<>', 'pending')->orderBy('updated_at', 'desc')->paginate($per_page);
    }
}