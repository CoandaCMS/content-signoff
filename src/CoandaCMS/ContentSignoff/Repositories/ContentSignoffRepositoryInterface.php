<?php namespace CoandaCMS\ContentSignoff\Repositories;

interface ContentSignoffRepositoryInterface {

    /**
     * @param $version_id
     * @param $version_number
     * @param $page_id
     * @param $page_name
     * @param $requester_id
     * @return mixed
     */
    public function createNewSignoffRequest($version_id, $version_number, $page_id, $page_name, $requester_id);

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $per_page
     * @return mixed
     */
    public function pending($per_page);

    /**
     * @param $per_page
     * @return mixed
     */
    public function history($per_page);

}
