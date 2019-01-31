<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * Class ArticleController
 * @package App\Controller
 * @Security(name="api_key")
 */

class ArticleController extends AbstractFOSRestController
{
    private $articleRepository;
    private $em;

    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $em)
    {
        $this->articleRepository = $articleRepository;
        $this->em = $em;
    }

    /**
     * @Rest\Get("/api/admin/article/{id}")
     * @Rest\View(serializerGroups={"article"})
     */
    public function getApiArticle(Article $article){
        return $this->view($article);
    }
    /**
     * @Rest\Get("/api/article")
     * @Rest\View(serializerGroups={"article"})
     */
    public function getApiArticleUser(){
        $user = $this->getUser();
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(array('user'=>$user));
        return $this->view($articles);
    }
    /**
     * @Rest\Get("/api/admin/article")
     * @Rest\View(serializerGroups={"article"})
     */
    public function getApiArticles(){
        $articles = $this->articleRepository->findAll();
        return $this->view($articles);
    }
    /**
     * @Rest\Post("/api/admin/article")
     * @Rest\View(serializerGroups={"article"})
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function postApiArticle(Article $article, Request $request){

        //$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('email'=>$request->get(('user'))));
        //$article->setUser($user);
        $this->em->persist($article);
        $this->em->flush();
        return $this->view($article);
    }

    /**
     * @Rest\Post("/api/article")
     * @Rest\View(serializerGroups={"article"})
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function postApiArticleUser(Article $article, Request $request){

        $article->setUser($this->getUser());
        $this->em->persist($article);
        $this->em->flush();
        return $this->view($article);
    }
    /**
     * @Rest\Patch("/api/admin/article/{id}")
     * @Rest\View(serializerGroups={"article"})
     */
    public function patchApiArticle(Article $article, Request $request){

        if (!is_null($request->get(('name')))){
            $article->setName($request->get(('name')));
        }
        if (!is_null($request->get(('description')))){
            $article->setDescription($request->get(('description')));
        }
        if (!is_null($request->get(('createdAt')))){
            $article->setCreatedAt($request->get(('createdAt')));
        }
        if (!is_null($request->get(('user')))){
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('email'=>$request->get(('user'))));
            $article->setUser($user);
        }
        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }
    /**
     * @Rest\Patch("/api/article")
     * @Rest\View(serializerGroups={"article"})
     */
    public function patchApiArticleUser(Article $article, Request $request){

        if (!is_null($request->get(('name')))){
            $article->setName($request->get(('name')));
        }
        if (!is_null($request->get(('description')))){
            $article->setDescription($request->get(('description')));
        }
        if (!is_null($request->get(('createdAt')))){
            $article->setCreatedAt($request->get(('createdAt')));
        }
            $article->setUser($this->getUser());
        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }
    /**
     * @Rest\Delete("/api/users/{id}")
     * @Rest\View(serializerGroups={"article"})
     */
    public function deleteApiUser(Article $article){
        $this->em->remove($article);
        $this->em->flush();
        return true;
    }

}