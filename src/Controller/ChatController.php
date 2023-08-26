<?php

namespace App\Controller;

use App\Form\SendMessageForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'app_chat')]
    public function index(Request $request): Response
    {
        $sendForm = $this->createForm(SendMessageForm::class);
        $sendForm->handleRequest($request);
        $user = $this->getUser();
        return $this->render('pages/chat_page.html.twig', [
            'user' => $user,
            'sendForm' => $sendForm
        ]);
    }
}
