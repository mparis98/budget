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
     */
    public function postApiCard(Card $card){
        $this->em->persist($card);
        $this->em->flush();
        return $this->view($card);
    }
    /**
     * @Rest\Patch("/api/users")
     * @Rest\View(serializerGroups={"user_light","card_full","sub_full"})
     */
    public function patchApiUser(Request $request){
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
            $user->setApiKey($request->get(('subscription')));
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
    /**
     * @Rest\Delete("/api/card/{id}")
     * @Rest\View(serializerGroups={"card_full"})
     */
    public function deleteApiCard(Card $card){

        $this->em->remove($card);
        $this->em->flush();
        return true;
    }

    /**
     * @Rest\Get("/api/card")
     * @Rest\View(serializerGroups={"user_light","card_full","sub_full"})
     */
    public function getFullCardUser(){
        $cards = $this->getUser()->getCards();
        return $this->view($cards);
    }

    /**
     * @Rest\Get("/api/card/{id}")
     * @Rest\View(serializerGroups={"user_light","card_full","sub_full"})
     */
    public function getCardUser(Card $card){
        return $this->view($card);
    }

    /**
     * @Rest\Patch("/api/card/{id}")
     * @Rest\View(serializerGroups={"user_light","card_full","sub_full"})
     */
    public function patchApiCard(Card $card, Request $request){

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

        $this->em->persist($card);
        $this->em->flush();

        return $card;
    }

    /**
     * @Rest\Post("/api/admin/subscription")
     * @Rest\View(serializerGroups={"sub_full"})
     * @ParamConverter("subscription", converter="fos_rest.request_body")
     */
    public function postApiSub(Subscription $subscription){
        $this->em->persist($subscription);
        $this->em->flush();
        return $this->view($subscription);
    }


}