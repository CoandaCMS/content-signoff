<?php namespace CoandaCMS\ContentSignoff\Controllers\Admin;

use CoandaCMS\ContentSignoff\ContentSignoffManager;
use View;
use Coanda;
use Input;
use CoandaCMS\Coanda\Controllers\BaseController;

class ContentSignoffAdminController extends BaseController {

    /**
     * @var ContentSignoffManager
     */
    private $signoff_manager;

    /**
     * @param ContentSignoffManager $signoff_manager
     */
    public function __construct(ContentSignoffManager $signoff_manager)
    {
        $this->beforeFilter('csrf', array('on' => 'post'));

        $this->signoff_manager = $signoff_manager;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        Coanda::checkAccess('contentsignoff', 'view');

        $requests = $this->signoff_manager->pending(10);

        return View::make('coanda-content-signoff::admin.index', [ 'requests' => $requests ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getRequest($id)
    {
        Coanda::checkAccess('contentsignoff', 'view');

        return View::make('coanda-content-signoff::admin.request', [ 'request' => $this->signoff_manager->getById($id) ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function postRequest($id)
    {
        Coanda::checkAccess('contentsignoff', 'view');

        $request = $this->signoff_manager->getById($id);

        if (!$request)
        {
            \App::abort('404');
        }

        if (Input::has('decline') && Input::get('decline') == 'true')
        {
            $this->signoff_manager->handleDecline($request, Input::all());

            return \Redirect::to(Coanda::adminUrl('contentsignoff/request/' . $request->id))->with('declined', true);
        }

        if (Input::has('accept') && Input::get('accept') == 'true')
        {
            $this->signoff_manager->handleAccept($request, Input::all());

            return \Redirect::to(Coanda::adminUrl('contentsignoff/request/' . $request->id))->with('accepted', true);
        }
    }

    /**
     * @return mixed
     */
    public function getHistory()
    {
        Coanda::checkAccess('contentsignoff', 'view');

        $requests = $this->signoff_manager->history(10);

        return View::make('coanda-content-signoff::admin.history', [ 'requests' => $requests ]);
    }

}