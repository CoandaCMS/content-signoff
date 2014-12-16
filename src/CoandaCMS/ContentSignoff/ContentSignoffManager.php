<?php namespace CoandaCMS\ContentSignoff;

use CoandaCMS\Coanda\Pages\PageManager;
use CoandaCMS\ContentSignoff\Repositories\ContentSignoffRepositoryInterface;

class ContentSignoffManager {

    private $repository;
    private $page_manager;

    public function __construct(ContentSignoffRepositoryInterface $repository, PageManager $page_manager)
    {
        $this->repository = $repository;
        $this->page_manager = $page_manager;
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    public function pending($per_page)
    {
        return $this->repository->pending($per_page);
    }

    public function history($per_page)
    {
        return $this->repository->history($per_page);
    }

    public function createNewSignoffRequest($version_id)
    {
        return $this->repository->createNewSignoffRequest($version_id);
    }

    public function handleDecline($request, $input)
    {
        $this->revertVersionToDraft($request->version);
        $this->updateRequest($request, 'declined', $input);
    }

    private function revertVersionToDraft($version)
    {
        $url_repository = \App::make('CoandaCMS\Coanda\Urls\Repositories\UrlRepositoryInterface');
        $url_repository->delete('pendingversion', $version->id);

        $version->publish_handler_data = '';
        $version->status = 'draft';
        $version->save();
    }

    private function updateRequest($request, $status, $input)
    {
        $request->actioned_by = \Coanda::currentUserId();
        $request->message = isset($input['message']) ? $input['message'] : '';
        $request->status = $status;
        $request->save();
    }

    public function handleAccept($request, $input)
    {
        $this->publishVersion($request->version);
        $this->updateRequest($request, 'declined', $input);
    }

    private function publishVersion($version)
    {
        $page_repository = \App::make('CoandaCMS\Coanda\Pages\Repositories\PageRepositoryInterface');

        $page_repository->publishVersion($version, \Coanda::currentUser()->id, \App::make('CoandaCMS\Coanda\Urls\Repositories\UrlRepositoryInterface'));
    }

}