<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


final class MessageController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    #[Route('/messagerie', name: 'messagerie')]
    public function messagerie(ConversationRepository $conversationRepo): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à la messagerie.');
        }

        $conversations = $conversationRepo->findByUser($user);

        return $this->render('message/messagerie.html.twig', [
            'conversations' => $conversations,
        ]);
    }


    #[Route('/messagerie/{id}', name: 'conversation_view')]
public function viewConversation(
    Conversation $conversation,
    Request $request,
    EntityManagerInterface $em
): Response {
    $message = new Message();
    $form = $this->createForm(MessageType::class, $message);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $message->setSender($this->getUser());
        $message->setRecipient(
            $conversation->getUser1() === $this->getUser()
                ? $conversation->getUser2()
                : $conversation->getUser1()
        );
        $message->setConversation($conversation);
        $message->setCreatedAt(new \DateTimeImmutable());

        $em->persist($message);
        $em->flush();

        return $this->redirectToRoute('conversation_view', ['id' => $conversation->getId()]);
    }

    return $this->render('message/conversation.html.twig', [
        'conversation' => $conversation,
        'form' => $form->createView(),
    ]);
}


    #[Route('/start-conversation/{id}', name: 'start_conversation')]
public function startConversation(
    User $otherUser,
    ConversationRepository $conversationRepo,
    EntityManagerInterface $em
): Response {
    $currentUser = $this->getUser();

    // Vérifie si une conversation existe déjà
    $existing = $conversationRepo->findOneByUsers($currentUser, $otherUser);

    if ($existing) {
        return $this->redirectToRoute('conversation_view', ['id' => $existing->getId()]);
    }

    // Sinon, crée une nouvelle conversation
    $conversation = new Conversation();
    $conversation->setUser1($currentUser);
    $conversation->setUser2($otherUser);
    $conversation->setLastMessageAt(new \DateTimeImmutable());

    $em->persist($conversation);
    $em->flush();

    return $this->redirectToRoute('conversation_view', ['id' => $conversation->getId()]);
}


}
