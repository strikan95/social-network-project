<?php

namespace App\Controller;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\SendMessageForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChatController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/chat', name: 'app_chat')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $conversationRepo = $entityManager->getRepository(Conversation::class); 
        $userConversations = $conversationRepo->createQueryBuilder('conversation')
        ->where("conversation.first_user = :user")
        ->orWhere("conversation.second_user = :user")
        ->setParameter("user", $user)
        ->getQuery()
        ->getResult();


        $sendForm = $this->createForm(SendMessageForm::class);
        $sendForm->handleRequest($request);

        if ($sendForm->isSubmitted()) {
            $message = new Message();
            $message->setContent($sendForm->get('content')->getData());
            $message->addUserId($user);
            $message->addConversationId($userConversations[0]);
            
            $this->entityManager->persist($message);
            $this->entityManager->flush();
            return new JsonResponse(['success' => true]);
        }

        for($i = 0; $i<count($userConversations); $i++){
            if($userConversations[$i]->getFirstUser()!=$user){
                $swap = $userConversations[$i]->getFirstUser();
                $userConversations[$i]->setFirstUser($userConversations[$i]->getSecondUser());
                $userConversations[$i]->setSecondUser($swap);
            } 
        }

        return $this->render('pages/chat_page.html.twig', [
            'user' => $user,
            'sendForm' => $sendForm,
            'conversations' => $userConversations
        ]);
    }

    #[Route('/chat', name: 'app_chat_send')]
    public function send(Request $request): Response
    {
        $sendForm = $this->createForm(SendMessageForm::class);
        $sendForm->handleRequest($request);
        $user = $this->getUser();

        return $this->render('pages/chat_page.html.twig', [
            'user' => $user,
            'sendForm' => $sendForm
        ]); 
    }

    #[Route('/chat/conversation/{$id}', name: 'app_chat_send_profile')]
    public function sendRequest(Request $request, EntityManagerInterface $entityManager): Response
    {
        $conversationId = $request->get('id');
        $currentConversation = $entityManager->getRepository(Conversation::class)->find($conversationId);
        
        $user = $this->getUser();

        $userConversations = $this->getUser()->conversations();


        $sendForm = $this->createForm(SendMessageForm::class);
        $sendForm->handleRequest($request);

        if ($sendForm->isSubmitted()) {
            $message = new Message();
            $message->setContent($sendForm->get('content')->getData());
            $message->addUserId($user);
            $message->addConversationId($userConversations[$conversationId-1]);
            
            $this->entityManager->persist($message);
            $this->entityManager->flush();
            return new JsonResponse(['success' => true]);
        }

        return $this->render('pages/chat_page_show.html.twig', [
            'user' => $user,
            'sendForm' => $sendForm,
            'conversations' => $userConversations,
            'currentConversation' => $currentConversation
        ]);
    }

    #[Route('/chat/makeConversation/{$id}', name: 'app_chat_make_conversation')]
    public function makeConversation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $request->get('id');
        $user = $entityManager->getRepository(User::class)->find($userId);
        $newConversation = new Conversation();
        $newConversation->addUser($user)
        ->addUser($this->getUser());
        $this->entityManager->persist($newConversation);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_chat_send_profile', array('id' => $userId));
        
    }

    #[Route('/chat/getMessages/{$id}', name: 'app_chat_fetch_message')]
    public function fetchMessages(Request $request, EntityManagerInterface $entityManager): Response
    {
    $conversationId = $request->get('id');
    $currentConversation = $entityManager->getRepository(Conversation::class)->find($conversationId);

    $messages = $currentConversation->getMessages();
    $messagesData = [];
    foreach ($messages as $message) {
        $messagesData[] = [
            'id' => $message->getId(),
            'content' => $message->getContent(),
        ];
    }
    $responseData = [
        'messages' => $messagesData,
    ];

    return $this->json($responseData);
    }
}
