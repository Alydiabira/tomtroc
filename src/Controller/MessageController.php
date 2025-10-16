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
use Symfony\Component\Routing\Annotation\Route;

final class MessageController extends AbstractController
{
    #[Route('/messagerie', name: 'messagerie')]
    public function messagerie(ConversationRepository $conversationRepo): Response
    {
        $user = $this->getUser();
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
        $user = $this->getUser();

        // Tri des messages par date croissante
        $messages = $conversation->getMessages()->toArray();
        usort($messages, fn($a, $b) => $a->getCreatedAt() <=> $b->getCreatedAt());

        // Formulaire de nouveau message
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($user);
            $message->setRecipient(
                $conversation->getUser1() === $user ? $conversation->getUser2() : $conversation->getUser1()
            );
            $message->setConversation($conversation);
            $message->setCreatedAt(new \DateTimeImmutable());

            $em->persist($message);

            // Mise à jour automatique de la date du dernier message
            $conversation->setLastMessageAt(new \DateTimeImmutable());
            $em->persist($conversation);

            $em->flush();

            return $this->redirectToRoute('conversation_view', ['id' => $conversation->getId()]);
        }

        return $this->render('message/conversation.html.twig', [
            'conversation' => $conversation,
            'messages' => $messages,
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

        $existing = $conversationRepo->findOneByUsers($currentUser, $otherUser);

        if ($existing) {
            return $this->redirectToRoute('conversation_view', ['id' => $existing->getId()]);
        }

        $conversation = new Conversation();
        $conversation->setUser1($currentUser);
        $conversation->setUser2($otherUser);
        $conversation->setLastMessageAt(new \DateTimeImmutable());

        $em->persist($conversation);
        $em->flush();

        return $this->redirectToRoute('conversation_view', ['id' => $conversation->getId()]);
    }
}
