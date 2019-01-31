<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Operation;


/**
 * Class UserController
 * @package App\Controller
 * @Security(name="api_key")
 */
class UserController extends AbstractFOSRestController
{
    private $userRepository;
    private $em;
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository ;
        $this->em=$em;
    }

    /**
     * @Rest\Get("/api/admin/users/{id}")
     * @Rest\View(serializerGroups={"user","article"})
     */
    public function getApiUser(User $user){
        return $this->view($user);
    }

    /**
     * @Rest\Get("/api/users")
     * @Rest\View(serializerGroups={"user","article"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="getUser",
     *     tags={"User"},
     *     summary="Get a collection of `User` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function getApiUserOwn(){

        return $this->view($this->getUser());
    }
    /**
     * @Rest\Get("/api/admin/users")
     * @Rest\View(serializerGroups={"user","article"})
     */
    public function getApiUsers(){
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }
    /**
     * @Rest\Post("/api/admin/users")
     * @Rest\View(serializerGroups={"user"})
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postApiUser(User $user){
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }
    /**
     * @Rest\Patch("/api/users/{id}")
     * @Rest\View(serializerGroups={"user"})
     */
    public function patchApiUser(User $user, Request $request){

        if (!is_null($request->get(('firstname')))){
            $user->setFirstname($request->get(('firstname')));
        }
        if (!is_null($request->get(('lastname')))){
            $user->setLastname($request->get(('lastname')));
        }
        if (!is_null($request->get(('email')))){
            $user->setEmail($request->get(('email')));
        }
        if (!is_null($request->get(('birthday')))){
            $user->setBirthday($request->get(('birthday')));
        }
        if (!is_null($request->get(('roles')))){
            $user->setRoles($request->get(('roles')));
        }
        if (!is_null($request->get(('apiKey')))){
            $user->setApiKey($request->get(('apiKey')));
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
    /**
     * @Rest\Delete("/api/article/{id}")
     * @Rest\View(serializerGroups={"user"})
     */
    public function deleteApiUser(User $user){
        $articles = $user->getArticle();

        foreach ($articles as $article) {
            $article->setUser(null);
        }
        $this->em->remove($user);
        $this->em->flush();
        return true;
    }

}