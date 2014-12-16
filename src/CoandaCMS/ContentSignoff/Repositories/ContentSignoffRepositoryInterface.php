<?php namespace CoandaCMS\ContentSignoff\Repositories;

interface ContentSignoffRepositoryInterface {

    public function createNewSignoffRequest($version_id);

    public function getById($id);

    public function pending($per_page);

    public function history($per_page);

}
