<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ArticleController
 * @package App\Controller
 * @Route("/api/articles")
 */
class ArticleController
{
    /**
     * @Route(name="api_articles_post", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY", statusCode=404, message="Vous devez être connecté pour effectuer cette action")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function post(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $article = $serializer->deserialize($request->getContent(), Article::class, 'json');

        $errors = $validator->validate($article);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager->persist($article);
        $entityManager->flush();

        return new JsonResponse(
            $serializer->serialize($article, 'json'), JsonResponse::HTTP_CREATED, [], true
        );
    }
}