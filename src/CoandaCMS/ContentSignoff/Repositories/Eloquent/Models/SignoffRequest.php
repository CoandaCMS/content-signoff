<?php namespace CoandaCMS\ContentSignoff\Repositories\Eloquent\Models;

use CoandaCMS\Coanda\Pages\Exceptions\PageVersionNotFound;
use CoandaCMS\Coanda\Users\Exceptions\UserNotFound;
use Eloquent;
use App;

class SignoffRequest extends Eloquent {

    /**
     * @var array
     */
    protected $fillable = ['version_id', 'requested_by', 'version', 'page_id', 'page_name', 'status'];

    /**
     * @var string
     */
    protected $table = 'coanda_signoffrequests';

    /**
     * @var
     */
    private $cached_version;

    /**
     * @return bool
     */
    public function getVersionObjectAttribute()
    {
        if (!$this->cached_version)
        {
            $page_manager = App::make('CoandaCMS\Coanda\Pages\PageManager');

            try
            {
                $this->cached_version = $page_manager->getVersionById($this->version_id);
            }
            catch (PageVersionNotFound $exception)
            {
                return false;
            }
        }

        return $this->cached_version;
    }

    /**
     * @param $id
     * @return mixed
     */
    private function getUser($id)
    {
        $user_manager = \App::make('CoandaCMS\Coanda\Users\UserManager');

        try
        {
            $user = $user_manager->getUserById($id);
        }
        catch (UserNotFound $exception)
        {
            $user = $user_manager->getArchivedUserById($id);
        }

        return $user;
    }

    /**
     * @return string
     */
    public function actioner_name()
    {
        $user = $this->getUser($this->actioned_by);

        if ($user)
        {
            return $user->full_name;
        }

        return 'Unknown';
    }

    /**
     * @return string
     */
    public function requester_name()
    {
        $user = $this->getUser($this->requested_by);

        if ($user)
        {
            return $user->full_name;
        }

        return 'Unknown';
    }

    /**
     * @return bool
     */
    public function requester_email()
    {
        $user = $this->getUser($this->requested_by);

        if ($user)
        {
            return $user->email;
        }

        return false;
    }
}