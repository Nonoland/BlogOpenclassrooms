<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Database\Configuration;
use Nolandartois\BlogOpenclassrooms\Core\Database\Db;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;
use Symfony\Component\HttpFoundation\Response;

class AdminSettingsController extends AdminController
{

    #[Route(['GET', 'POST'], '/admin/settings'), RouteAccess('admin')]
    public function indexSettings(): Response
    {
        $request = $this->getRequest();
        $dbInstance = Db::getInstance();

        $configurations = $dbInstance->select('configuration');

        $userSubmit = $request->request->has('user_submit');
        if ($userSubmit) {
            foreach ($configurations as &$configuration) {
                if ($request->request->has($configuration['name'])) {
                    $configuration['value'] = $request->request->get($configuration['name'], "");
                    Configuration::updateConfiguration($configuration['name'], $configuration['value']);
                }
            }
        }

        $values = [];
        foreach ($configurations as &$configuration) {
            $values[$configuration['name']] = $configuration['value'];
        }

        $template = $this->getTwig()->load('admin/settings/settings.twig');
        $content = $template->render($values);

        return new Response($content);
    }

}
