<?php namespace CoandaCMS\ContentSignoff;

use CoandaCMS\Coanda\Pages\PageManager;
use CoandaCMS\ContentSignoff\Repositories\ContentSignoffRepositoryInterface;
use Mail;
use Config;
use Coanda;

class ContentSignoffManager {

    /**
     * @var ContentSignoffRepositoryInterface
     */
    private $repository;
    /**
     * @var PageManager
     */
    private $page_manager;

    /**
     * @param ContentSignoffRepositoryInterface $repository
     * @param PageManager $page_manager
     */
    public function __construct(ContentSignoffRepositoryInterface $repository, PageManager $page_manager)
    {
        $this->repository = $repository;
        $this->page_manager = $page_manager;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    /**
     * @param $per_page
     * @return mixed
     */
    public function pending($per_page)
    {
        return $this->repository->pending($per_page);
    }

    /**
     * @param $per_page
     * @return mixed
     */
    public function history($per_page)
    {
        return $this->repository->history($per_page);
    }

    /**
     * @param $version
     * @param $requester_id
     * @return mixed
     */
    public function createNewSignoffRequest($version, $requester_id)
    {
        return $this->repository->createNewSignoffRequest($version->id, $version->version, $version->page->id, $version->page->name, $requester_id);
    }

    /**
     * @param $request
     * @param $input
     */
    public function handleDecline($request, $input)
    {
        $this->revertVersionToDraft($request->version_object);
        $this->updateRequest($request, 'declined', $input);
    }

    /**
     * @param $version
     */
    private function revertVersionToDraft($version)
    {
        $url_repository = \App::make('CoandaCMS\Coanda\Urls\Repositories\UrlRepositoryInterface');
        $url_repository->delete('pendingversion', $version->id);

        $version->publish_handler_data = '';
        $version->status = 'draft';
        $version->save();
    }

    /**
     * @param $request
     * @param $status
     * @param $input
     */
    private function updateRequest($request, $status, $input)
    {
        $request->actioned_by = \Coanda::currentUserId();
        $request->message = isset($input['message']) ? $input['message'] : '';
        $request->status = $status;
        $request->save();

        $this->sendNotification($request);
    }

    /**
     * @param $request
     * @param $input
     */
    public function handleAccept($request, $input)
    {
        $this->publishVersion($request->version_object);
        $this->updateRequest($request, 'accepted', $input);
    }

    /**
     * @param $version
     */
    private function publishVersion($version)
    {
        $url_repository = \App::make('CoandaCMS\Coanda\Urls\Repositories\UrlRepositoryInterface');
        $url_repository->delete('pendingversion', $version->id);

        $page_repository = \App::make('CoandaCMS\Coanda\Pages\Repositories\PageRepositoryInterface');
        $page_repository->publishVersion($version, \Coanda::currentUser()->id, \App::make('CoandaCMS\Coanda\Urls\Repositories\UrlRepositoryInterface'));
    }

    /**
     * @param $request
     */
    private function sendNotification($request)
    {
        Mail::send('coanda-content-signoff::admin.emails.' . $request->status, ['request' => $request ], function($message) use ($request)
        {
            $message->from(Config::get('coanda::coanda.site_admin_email'), Config::get('coanda::coanda.site_name'));
            $message->to($request->requester_email())->subject(ucfirst($request->status) . ': Version' . $request->version . ' of ' . $request->page_name);
        });
    }
}