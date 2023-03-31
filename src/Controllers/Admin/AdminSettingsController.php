<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Database\Configuration;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;

class AdminSettingsController extends AdminController
{

    #[Route(['GET', 'POST'], '/admin/settings'), RouteAccess('admin')]
    public function indexSettings(): void
    {
        $request = $this->getRequest();

        if ($request->getIsset('blog_name')
            && $request->getIsset('blog_domain')
            && $request->getIsset('copyright')
            && $request->getIsset('twitter_url')
            && $request->getIsset('facebook_url')
            && $request->getIsset('github_url')) {

            Configuration::updateConfiguration('blog_name', $request->getValuePost('blog_name'));
            Configuration::updateConfiguration('blog_domain', $request->getValuePost('blog_domain'));
            Configuration::updateConfiguration('copyright', $request->getValuePost('copyright'));
            Configuration::updateConfiguration('twitter_url', $request->getValuePost('twitter_url'));
            Configuration::updateConfiguration('facebook_url', $request->getValuePost('facebook_url'));
            Configuration::updateConfiguration('github_url', $request->getValuePost('github_url'));
        }

        $template = $this->getTwig()->load('admin/settings/settings.twig');
        echo $template->render([
            'blog_name' => Configuration::getConfiguration('blog_name'),
            'blog_domain' => Configuration::getConfiguration('blog_domain'),
            'copyright' => Configuration::getConfiguration('copyright'),
            'twitter_url' => Configuration::getConfiguration('twitter_url'),
            'facebook_url' => Configuration::getConfiguration('facebook_url'),
            'github_url' => Configuration::getConfiguration('github_url'),
        ]);
    }

}
