<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/api/users")
 */
class UserController
{
    /**
     * @Route(name="api_users_list", methods={"GET"})
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Vous n'avez pas les droits nécessaires pour effectuer cette action")
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function list(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($userRepository->findAll(), 'json', ["groups" => "get"]), JsonResponse::HTTP_OK, [], true
        );
    }
}