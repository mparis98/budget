<?php

namespace App\Controller;

use App\Entity\Subscription;
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
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class HomeController extends AbstractFOSRestController
{
    private $userRepository;
    private $em;
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository ;
        $this->em=$em;
    }

    /**
     * @Rest\Get("/users/{id}")
     * @Rest\View(serializerGroups={"user_light","card_light","sub_full"})
     * @Operation(
     *     operationId="getUser",
     *     tags={"Anonymous"},
     *     summary="Get a collection of `User` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Authentication Required (no usertoken)"
     *
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Invalid Credentials (wrong usertoken)"
     *
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found"
     *
     *     )
     * )
     */
    public function getApiUser(User $user){
        return $this->view($user, 200);
    }

    /**
     * @Rest\Get("/users")
     * @Rest\View(serializerGroups={"user_light","card_light","sub_full"})
     * @Operation(
     *     operationId="getAllUser",
     *     tags={"Anonymous"},
     *     summary="Get a collection of `User` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Authentication Required (no usertoken)"
     *
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Invalid Credentials (wrong usertoken)"
     *
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found"
     *
     *     )
     * )
     */
    public function getFullApiUser(){
        $users = $this->userRepository->findAll();
        return $this->view($users,200);
    }

    /**
     * @Rest\Get("/subscription/{id}")
     * @Rest\View(serializerGroups={"sub_full"})
     * @Operation(
     *     operationId="getSubscription",
     *     tags={"Anonymous"},
     *     summary="Get a collection of `Subscription` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Authentication Required (no usertoken)"
     *
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Invalid Credentials (wrong usertoken)"
     *
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found"
     *
     *     )
     * )
     */
    public function getSub(Subscription $subscription){
        return $this->view($subscription,200);
    }

    /**
     * @Rest\Get("/subscription")
     * @Rest\View(serializerGroups={"sub_full"})
     * @Operation(
     *     operationId="getSubscription",
     *     tags={"Anonymous"},
     *     summary="Get a collection of `Subscription` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Authentication Required (no usertoken)"
     *
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Invalid Credentials (wrong usertoken)"
     *
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found"
     *
     *     )
     * )
     */
    public function getFullSub(){
        $subscriptions = $this->getDoctrine()->getRepository(Subscription::class)->findAll();
        return $this->view($subscriptions,200);
    }

    /**
     * @Rest\Post("/users")
     * @Rest\View(serializerGroups={"user_light","card_light","sub_full"})
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @Operation(
     *     operationId="addUser",
     *     tags={"Anonymous"},
     *     summary="Add a collection of `User` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Authentication Required (no usertoken)"
     *
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="Invalid Credentials (wrong usertoken)"
     *
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found"
     *
     *     )
     * )
     */
    public function postApiUser(User $user, ConstraintViolationListInterface $validationErrors){

        $errors = array();
        if ($validationErrors->count() > 0) {
            foreach ($validationErrors as $constraintViolation){
                $message = $constraintViolation->getMessage();
                $propertyPath = $constraintViolation->getPropertyPath();
                $errors[] = ['message' => $message, 'propertyPath' => $propertyPath];
            }
        }
        if (!empty($errors)) {
            throw new BadRequestHttpException(\json_encode($errors));
        }
        else{
            $this->em->persist($user);
            $this->em->flush();
            return $this->view($user,201);
        }
    }

}