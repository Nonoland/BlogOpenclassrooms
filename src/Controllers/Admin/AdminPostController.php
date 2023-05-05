<?php

namespace Nolandartois\BlogOpenclassrooms\Controllers\Admin;

use Nolandartois\BlogOpenclassrooms\Controllers\AdminController;
use Nolandartois\BlogOpenclassrooms\Core\Entity\Post;
use Nolandartois\BlogOpenclassrooms\Core\Entity\User;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\Route;
use Nolandartois\BlogOpenclassrooms\Core\Routing\Attributes\RouteAccess;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class AdminPostController extends AdminController
{

    #[Route('GET', '/admin/posts'), RouteAccess('admin')]
    public function indexPosts(): Response
    {
        $posts = Post::getAllPosts();

        $template = $this->getTwig()->load('admin/posts/posts.twig');
        $content = $template->render([
            'posts' => $posts
        ]);

        return new Response($content);
    }

    #[Route(['GET'], '/admin/posts/new'), RouteAccess('admin')]
    public function postNew(): Response
    {
        $template = $this->getTwig()->load('admin/posts/new.twig');
        $content = $template->render();

        return new Response($content);
    }

    #[Route(['POST'], '/admin/ajax/posts/new'), RouteAccess('admin')]
    public function postNewAjax(): Response
    {
        $request = $this->getRequest();

        /** @var User $user */
        $user = (int)$request->getSession()->get('user', 0);
        $postTitle = $request->request->get('post_title', false);
        $postDescription = $request->request->get('post_description', false);
        $postBody = $request->request->get('post_body', false);

        if (!$postTitle || !$postDescription || !$postBody || !$user) {
            return $this->displayAjax(false);
        }

        $user = new User((int)$user);

        $post = new Post();
        $post->setIdUser($user->getId());
        $post->setDescription($postDescription);
        $post->setBody(
            json_decode(
                htmlspecialchars_decode($postBody, ENT_QUOTES),
                true
            )
        );
        $post->setTitle($postTitle);
        $post->add();

        if ($request->files->has('post_image')) {
            /** @var UploadedFile $image */
            $image = $request->files->get('post_image');

            if ($image->getSize() > 500000) {
                return $this->displayAjax(false);
            }

            $path = $_ENV['IMAGE_POST_PATH'] . '/' . $post->getSlug() . '.webp';
            $this->convertImageToWebP($image, $path);
        }

        return $this->displayAjax(true);
    }

    #[Route(['POST'], '/admin/ajax/posts/edit/{id_post:int}'), RouteAccess('admin')]
    public function postEditAjax(array $params): Response
    {
        $request = $this->getRequest();

        /** @var User $user */
        $user = (int)$request->getSession()->get('user', 0);
        $postTitle = $request->request->get('post_title', false);
        $postDescription = $request->request->get('post_description', false);
        $postBody = $request->request->get('post_body', false);

        if (!($post = new Post($params['id_post'])) && (!$postTitle || !$postDescription || $postBody || !$user)) {
            return $this->displayAjax(false);
        }

        $user = new User((int)$user);

        $post->setTitle($postTitle);
        $post->setDescription($postDescription);
        $post->setBody(
            json_decode(
                htmlspecialchars_decode($postBody, ENT_QUOTES),
                true
            )
        );
        $post->update();

        if ($request->files->has('post_image')) {
            /** @var UploadedFile $image */
            $image = $request->files->get('post_image');

            if ($image->getSize() > 500000) {
                return $this->displayAjax(false);
            }

            $path = $_ENV['IMAGE_POST_PATH'] . '/' . $post->getSlug() . '.webp';
            if (file_exists($path)) {
                unlink($path);
            }
            $this->convertImageToWebP($image, $path);
        }

        return $this->displayAjax(true);
    }

    #[Route(['GET', 'POST'], '/admin/posts/edit/{id_post:int}'), RouteAccess('admin')]
    public function postEdit(array $params): Response
    {
        $post = new Post($params['id_post']);

        if ($post->getId() == 0) {
            return self::redirect('/admin/posts');
        }

        $template = $this->getTwig()->load('admin/posts/edit.twig');
        $content = $template->render([
            'post' => $post
        ]);

        return new Response($content);
    }

    #[Route(['GET'], '/admin/posts/delete/{id_post:int}'), RouteAccess('admin')]
    public function postDelete(array $params): Response
    {
        $post = new Post((int)$params['id_post']);
        $post->delete();

        return self::redirect('/admin/posts');
    }

    function convertImageToWebP(UploadedFile $sourceImage, $outputImage, $quality = 80): bool
    {
        $imageInfo = getimagesize($sourceImage->getPathname());
        $imageType = $imageInfo[2];

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($sourceImage->getPathname());
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($sourceImage->getPathname());
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($sourceImage->getPathname());
                break;
            default:
                return false;
        }

        $result = imagewebp($image, $outputImage, $quality);

        imagedestroy($image);

        return $result;
    }
}
