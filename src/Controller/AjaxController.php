<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AjaxController
 * @package App\Controller
 * @Route("/ajax", name="ajax_")
 */
class AjaxController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/ajax", name="check_email", methods={"POST"})
     */
    public function checkEmail(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException("Only XmlHttpRequest accepted");
        }

        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $userRepository->findOneBy([
            'email' => $request->request->get('email')
        ]);

        return new JsonResponse([
            'exists' => $user instanceof User
        ]);
    }
}