<?php namespace CoandaCMS\ContentSignoff\PublishHandlers;

use App;
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
        $manager->createNewSignoffRequest($version->id);

        $handler_data = [];
        $handler_data = $this->reserveNewSlug($handler_data, $version, $urlRepository);

        $version->publish_handler_data = json_encode($handler_data);
        $version->status = 'pending';
        $version->save();
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
}