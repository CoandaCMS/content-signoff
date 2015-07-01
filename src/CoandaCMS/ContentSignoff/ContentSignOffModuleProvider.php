<?php namespace CoandaCMS\ContentSignoff;

use Route;

class ContentSignoffModuleProvider implements \CoandaCMS\Coanda\CoandaModuleProvider {

    /**
     * @var string
     */
    public $name = 'contentsignoff';

    /**
     * @param \CoandaCMS\Coanda\Coanda $coanda
     * @return mixed|void
     */
    public function boot(\CoandaCMS\Coanda\Coanda $coanda)
    {
        // Add the permissions
        $permissions = [
        ];

        $coanda->addModulePermissions('contentsignoff', 'Content signoff', $permissions);
    }

    /**
     *
     */
    public function adminRoutes()
    {
        Route::controller('contentsignoff', 'CoandaCMS\ContentSignoff\Controllers\Admin\ContentSignoffAdminController');
    }

    /**
     *
     */
    public function userRoutes()
    {
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return mixed
     */
    public function bindings(\Illuminate\Foundation\Application $app)
    {
        $app->bind('CoandaCMS\ContentSignoff\Repositories\ContentSignoffRepositoryInterface', 'CoandaCMS\ContentSignoff\Repositories\Eloquent\EloquentContentSignoffRepository');
    }

    /**
     * @param $permission
     * @param $parameters
     * @param $user_permissions
     * @return bool
     * @throws PermissionDenied
     */
    public function checkAccess($permission, $parameters, $user_permissions)
    {
        if (in_array('*', $user_permissions))
        {
            return true;
        }

        // If we don't have this permission in the array, the throw right away
        if (!in_array($permission, $user_permissions))
        {
            throw new PermissionDenied('Access denied by content signoff module: ' . $permission);
        }

        return;
    }

    /**
     * @param $coanda
     * @return mixed|void
     */
    public function buildAdminMenu($coanda)
    {
        if ($coanda->canViewModule('contentsignoff'))
        {
            $coanda->addMenuItem('contentsignoff', 'Signoff requests');
        }
    }

    /**
     * @param $coanda
     * @return string
     */
    public function buildAdminDashboard($coanda)
    {
        if ($coanda->canViewModule('contentsignoff'))
        {
            $coanda->addDashBoardWidgetTemplate('coanda-content-signoff::admin.modules.contentsignoff.dashboard.contentsignoff');
        }
    }

    /**
     * @return ContentSignoffManager
     */
    public function manager()
    {
        return \App::make('CoandaCMS\ContentSignoff\ContentSignoffManager');;
    }
    
}