<?php namespace CoandaCMS\ContentSignoff\Repositories\Eloquent\Models;

use CoandaCMS\Coanda\Users\Exceptions\UserNotFound;
use Eloquent;
use App;

class SignoffRequest extends Eloquent {

    protected $fillable = ['version_id', 'status'];

    protected $table = 'coanda_signoffrequests';

    private $cached_version;

    public function getVersionAttribute()
    {
        if (!$this->cached_version)
        {
            $page_manager = App::make('CoandaCMS\Coanda\Pages\PageManager');

            $this->cached_version = $page_manager->getVersionById($this->version_id);
        }

        return $this->cached_version;
    }

    private function actioner()
    {
        $user_manager = \App::make('CoandaCMS\Coanda\Users\UserManager');
        $user = false;

        try
        {
            $user = $user_manager->getUserById($this->actioned_by);
        }
        catch (UserNotFound $exception)
        {
            $user = $user_manager->getArchivedUserById($this->user_id);
        }

        return $user;
    }

    public function actioner_name()
    {
        $user = $this->actioner();

        if ($user)
        {
            return $user->full_name;
        }

        return 'Unknown';
    }
}