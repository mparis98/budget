<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\Tests\Util\Validator;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;



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
     * @Rest\Get("/api/users")
     * @Rest\View(serializerGroups={"user_full","card_full","sub_full"})
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
     * @Rest\Post("/api/card")
     * @Rest\View(serializerGroups={"card_full"})
     * @ParamConverter("card", converter="fos_rest.request_body")
     * @Security(name="api_key")
     * @Operation(
     *     operationId="addUser",
     *     tags={"User"},
     *     summary="Add a collection of `Card` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function postApiCard(Card $card,ConstraintViolationListInterface $validationErrors){
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
        else {
            $card->setUser($this->getUser());
            $this->em->persist($card);
            $this->em->flush();
            return $this->view($card);
        }
    }
    /**
     * @Rest\Patch("/api/users")
     * @Rest\View(serializerGroups={"user_light","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="updateUser",
     *     tags={"User"},
     *     summary="Edit a collection of `User` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function patchApiUser(Request $request, ValidatorInterface $validator){
        $user= $this->getUser();

        if (!is_null($request->get(('firstname')))){
            $user->setFirstname($request->get(('firstname')));
        }
        if (!is_null($request->get(('lastname')))){
            $user->setLastname($request->get(('lastname')));
        }
        if (!is_null($request->get(('address')))){
            $user->setEmail($request->get(('address')));
        }
        if (!is_null($request->get(('country')))){
            $user->setBirthday($request->get(('country')));
        }
        if (!is_null($request->get(('subscription')))){
            $user->setSubscription($request->get(('subscription')));
        }

        $validationErrors = $validator->validate($user);
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
        else {
            $this->em->persist($user);
            $this->em->flush();

            return $user;
        }
    }
    /**
     * @Rest\Delete("/api/card/{id}")
     * @Rest\View(serializerGroups={"card_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="deleteCardUser",
     *     tags={"User"},
     *     summary="Delete a collection of `Card` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function deleteApiCard(Card $card){

        $this->em->remove($card);
        $this->em->flush();
        return true;
    }

    /**
     * @Rest\Get("/api/card")
     * @Rest\View(serializerGroups={"user_light","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="getFullCardUser",
     *     tags={"User"},
     *     summary="Get a collection of `Card` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function getFullCardUser(){
        $cards = $this->getUser()->getCards();
        return $this->view($cards);
    }

    /**
     * @Rest\Get("/api/card/{id}")
     * @Rest\View(serializerGroups={"user_light","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="getOneCardUser",
     *     tags={"User"},
     *     summary="Get a collection of `Card` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function getCardUser(Card $card){
        return $this->view($card);
    }

    /**
     * @Rest\Patch("/api/card/{id}")
     * @Rest\View(serializerGroups={"user_light","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="editCardUser",
     *     tags={"User"},
     *     summary="Edit a collection of `Card` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function patchApiCard(Card $card, Request $request, ValidatorInterface $validator){

        if (!is_null($request->get(('name')))){
            $card->setName($request->get(('name')));
        }
        if (!is_null($request->get(('creditCardType')))){
            $card->setCreditCardType($request->get(('creditCardType')));
        }
        if (!is_null($request->get(('creditCardNumber')))){
            $card->setCreditCardNumber($request->get(('creditCardNumber')));
        }
        if (!is_null($request->get(('currencyCode')))){
            $card->setCurrencyCode($request->get(('currencyCode')));
        }
        if (!is_null($request->get(('value')))){
            $card->setValue($request->get(('value')));
        }

        $validationErrors = $validator->validate($card);
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
        else {
            $this->em->persist($card);
            $this->em->flush();

            return $card;
        }
    }

    /**
     * @Rest\Post("/api/admin/subscription")
     * @Rest\View(serializerGroups={"sub_full"})
     * @ParamConverter("subscription", converter="fos_rest.request_body")
     * @Security(name="api_key")
     * @Operation(
     *     operationId="addSubscription",
     *     tags={"Admin"},
     *     summary="Add a collection of `Subscription` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function postApiSub(Subscription $subscription, ConstraintViolationListInterface $validationErrors){
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
        else {
            $this->em->persist($subscription);
            $this->em->flush();
            return $this->view($subscription);
        }
    }

    /**
     * @Rest\Get("/api/admin/users/{id}")
     * @Rest\View(serializerGroups={"user_full","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="getUser",
     *     tags={"Admin"},
     *     summary="Get a collection of `User` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function getApiUser(User $user){
        return $this->view($user);
    }

    /**
     * @Rest\Get("/api/admin/users")
     * @Rest\View(serializerGroups={"user_full","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="getFullUsers",
     *     tags={"Admin"},
     *     summary="Get a collection of `User` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function getFullApiUser(){
        $users = $this->userRepository->findAll();
        return $this->view($users);
    }

    /**
     * @Rest\Patch("/api/admin/users/{id}")
     * @Rest\View(serializerGroups={"user_full","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="editSubscription",
     *     tags={"Admin"},
     *     summary="Edit a collection of `User` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function patchApiAdminUser(User $user,Request $request, ValidatorInterface $validator){

        if (!is_null($request->get(('firstname')))){
            $user->setFirstname($request->get(('firstname')));
        }
        if (!is_null($request->get(('lastname')))){
            $user->setLastname($request->get(('lastname')));
        }
        if (!is_null($request->get(('address')))){
            $user->setEmail($request->get(('address')));
        }
        if (!is_null($request->get(('country')))){
            $user->setBirthday($request->get(('country')));
        }
        if (!is_null($request->get(('subscription')))){
            $user->setSubscription($request->get(('subscription')));
        }

        $validationErrors = $validator->validate($user);
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
        else {
            $this->em->persist($user);
            $this->em->flush();

            return $user;
        }
    }

    /**
     * @Rest\Delete("/api/admin/users/{id}")
     * @Rest\View(serializerGroups={"user_full","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="deleteUser",
     *     tags={"Admin"},
     *     summary="Delete a collection of `User` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function deleteApiUser(User $user){
        $cards = $user->getCard();
        foreach ($cards as $card)
        {
            $this->em->remove($card);
        }
        $this->em->remove($user);
        $this->em->flush();
        return true;
    }

    /**
     * @Rest\Get("/api/admin/subscription/{id}")
     * @Rest\View(serializerGroups={"sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="getSubscription",
     *     tags={"Admin"},
     *     summary="Get a collection of `Subscription` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function getApiSub(Subscription $subscription){
        return $this->view($subscription);
    }

    /**
     * @Rest\Get("/api/admin/subscription")
     * @Rest\View(serializerGroups={"sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="getAllSubscription",
     *     tags={"Admin"},
     *     summary="Get a collection of `Subscription` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function getFullApiSub(){
        $subscriptions = $this->getDoctrine()->getRepository(Subscription::class)->findAll();
        return $this->view($subscriptions);
    }

    /**
     * @Rest\Delete("/api/admin/subscription/{id}")
     * @Rest\View(serializerGroups={"user_full","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="deleteSubscription",
     *     tags={"Admin"},
     *     summary="Delete a collection of `Subscription` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function deleteApiSub(Subscription $subscription){
        $users = $subscription->getUser();
        if ($users){
            throw new Exception("Un ou plusieurs utilisateurs sont reliés à cette subscription");
        }
        $this->em->remove($subscription);
        $this->em->flush();
        return true;
    }

    /**
     * @Rest\Patch("/api/admin/subscription/{id}")
     * @Rest\View(serializerGroups={"user_full","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="editSubscription",
     *     tags={"Admin"},
     *     summary="Edit a collection of `Subscription` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function patchApiAdminSub(Subscription $subscription,Request $request, ValidatorInterface $validator){

        if (!is_null($request->get(('name')))){
            $subscription->setName($request->get(('name')));
        }
        if (!is_null($request->get(('slogan')))){
            $subscription->setSlogan($request->get(('slogan')));
        }
        if (!is_null($request->get(('url')))){
            $subscription->setUrl($request->get(('url')));
        }

        $validationErrors = $validator->validate($subscription);
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
        $this->em->persist($subscription);
        $this->em->flush();

        return $subscription;
        }
    }

    /**
     * @Rest\Get("/api/admin/card")
     * @Rest\View(serializerGroups={"user_light","card_full","sub_full"})
     * @Security(name="api_key")
     * @Operation(
     *     operationId="getAllCard",
     *     tags={"Admin"},
     *     summary="Get a collection of `Card` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function getFullCardAdmin(){
        $cards = $this->getDoctrine()->getRepository(Card::class)->findAll();
        return $this->view($cards);
    }

    /**
     * @Rest\Post("/api/admin/card")
     * @Rest\View(serializerGroups={"user_full","card_full","sub_full"})
     * @ParamConverter("card", converter="fos_rest.request_body")
     * @Security(name="api_key")
     * @Operation(
     *     operationId="addCard",
     *     tags={"Admin"},
     *     summary="Add a collection of `Card` entities",
     *     @SWG\Response(
     *         response="200",
     *         description="Successful response",
     *
     *     )
     * )
     */
    public function postApiAdminCard(Card $card, ConstraintViolationListInterface $validationErrors){

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
        else {
            $this->em->persist($card);
            $this->em->flush();
            return $this->view($card);
        }
    }

}