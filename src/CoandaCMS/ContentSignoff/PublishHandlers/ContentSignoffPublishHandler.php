<?php namespace CoandaCMS\ContentSignoff\PublishHandlers;

use App;
use Mail;
use Config;
use Redirect;
use Coanda;
use CoandaCMS\Coanda\Pages\PublishHandlers\PublishHandlerInterface;

class ContentSignoffPublishHandler implements PublishHandlerInterface {

    /**
     * @var string
     */
    public $identifier = 'signoff';

    /**
     * @var string
     */
    public $name = 'Request that the content be signed off';

    /**
     * @var string
     */
    public $template = 'coanda-content-signoff::admin.publishoptions.signoff';

    public function display($data)
    {
        return ' awaiting signoff';
    }

    public function validate($version, $data)
    {
    }

    public function execute($version, $data, $pageRepository, $urlRepository)
    {
        // Create new signoff request...
        $manager = App::make('CoandaCMS\ContentSignoff\ContentSignoffManager');
        $request = $manager->createNewSignoffRequest($version, \Coanda::currentUserId());

        $this->sendNotifications($request);

        $handler_data = [];
        $handler_data = $this->reserveNewSlug($handler_data, $version, $urlRepository);

        $version->publish_handler_data = json_encode($handler_data);
        $version->status = 'pending';
        $version->save();

        return Redirect::to(Coanda::adminUrl('pages/view/' . $version->page_id . '?tab=versions'))->with('info_message', 'Your request for sign off has been sent. Until then your version will remain pending.');
    }

    private function reserveNewSlug($handler_data, $version, $urlRepository)
    {
        $current_slug = $version->page->slug;

        if ($version->full_slug !== $current_slug)
        {
            $url = $urlRepository->register($version->full_slug, 'pendingversion', $version->id);

            $handler_data['reserved_url'] = $url->id;
        }

        return $handler_data;
    }

    private function sendNotifications($request)
    {
        $groups = App::make('CoandaCMS\Coanda\Users\UserManager')->getAllGroups();
        $notify_email_list = [];

        foreach ($groups as $group)
        {
            $permissions = $group->access_list;

            if ((isset($permissions['everything']) && $permissions['everything'][0] == '*') || (isset($permissions['contentsignoff']) && $permissions['contentsignoff'][0] == '*'))
            {
                foreach ($group->users as $user)
                {
                    $notify_email_list[] = $user->email;
                }
            }
        }

        foreach ($notify_email_list as $notify_email)
        {
            Mail::send('coanda-content-signoff::admin.emails.requestforsignoff', ['request' => $request ], function($message) use ($notify_email, $request)
            {
                $message->from(Config::get('coanda::coanda.site_admin_email'), Config::get('coanda::coanda.site_name'));
                $message->to($notify_email)->subject('Request to signoff ' . $request->version . ' of ' . $request->page_name);
            });
        }
    }
}