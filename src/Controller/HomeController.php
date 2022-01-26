<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Form\ProfileEditFormType;
use Symfony\Component\Mime\Email;
use App\Repository\TurnRepository;
use App\Service\HandleUserStats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{
    private const LEADERBOARD_SIZE = 20;
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/leaderboard", name="leaderboard")
     */
    public function leaderboard(TurnRepository $turnRepository): Response
    {
        $user = $this->getUser();

        $leaderboard = $turnRepository->getLeaderBoard();

        $parameters = ['leaderboard' => $leaderboard];
        $parameters['leaderboardSize'] = self::LEADERBOARD_SIZE;

        if (null !== $user) {
            if (!$user instanceof User) {
                throw new Exception('User not authenticated');
            }

            $isInLeaderBoard = false;
            $leaderboardPosition = 0;

            foreach ($leaderboard as $key => $entry) {
                if ($key < self::LEADERBOARD_SIZE && $entry['id'] === $user->getId()) {
                    $isInLeaderBoard = true;
                } elseif ($entry['id'] === $user->getId()) {
                    $leaderboardPosition = $key + 1;
                    $turns = $entry['turns'];
                    $accuracy = $entry['meanAccuracy'];

                    $parameters['leaderboardPosition'] = $leaderboardPosition;
                    $parameters['userTurns'] = $turns;
                    $parameters['userAccuracy'] = $accuracy;
                }

                $parameters['isInLeaderBoard'] = $isInLeaderBoard;
            }

            return $this->render('home/leaderboard.html.twig', $parameters);
        }


        return $this->render('home/leaderboard.html.twig', $parameters);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(TurnRepository $turnRepository, HandleUserStats $handleUserStats): Response
    {
        $user = $this->getUser();

        if (null === $user) {
            return $this->redirectToRoute('login');
        }

        if (!$user instanceof User) {
            throw new Exception('User not authenticated');
        }

        $userStats = $turnRepository->getStatsAllTime($user);

        $userData = $handleUserStats->getUserData($userStats);

        return $this->render('home/profile.html.twig', [
            'user' => $user,
            'stats' => $userData,
        ]);
    }

    /**
     * @Route("/profile/edit", name="profile_edit")
     */
    public function profileEdit(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $user = $this->getUser();

        if (null === $user) {
            return $this->redirectToRoute('login');
        }

        if (!$user instanceof User) {
            throw new Exception('User not authenticated');
        }

        $form = $this->createForm(ProfileEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $currentPassword = $form->get('confirmPassword')->getData();

            $isPasswordValid = $this->checkCurrentPassword($currentPassword, $user, $userPasswordHasher);

            if (!$isPasswordValid) {
                $errorConfirmation = 'Wrong password';
                return $this->render('home/profile_edit.html.twig', [
                    'registrationForm' => $form->createView(),
                    'errorConfirmation' => $errorConfirmation
                ]);
            }

            $plainPassword = $form->get('plainPassword')->getData();
            $this->handleNewPasswodRequest($plainPassword, $user, $userPasswordHasher);

            $entityManager->flush();

            $pseudo = $user->getPseudo();
            $emailUser = $user->getEmail();
            if (!is_string($emailUser) || !is_string($this->getParameter('mailer_from'))) {
                throw new Exception('Email is not of type string');
            }

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($emailUser)
                ->subject("Profile Update - Aest")
                ->html($this->renderView('updateEmail.html.twig', [
                    'pseudo' => $pseudo
                ]));

            $mailer->send($email);

            $this->addFlash('green', 'Your profile has been updated');

            return $this->redirectToRoute('profile');
        }

        return $this->render('home/profile_edit.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Profile/{id}", name="profile_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (null === $user || !$user instanceof User) {
            throw new Exception('User not authenticated');
        }

        if (!is_string($request->request->get('_token'))) {
            throw new Exception('Token not available');
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $session = new Session();
        $session->invalidate();

        return $this->redirectToRoute('home');
    }

    private function checkCurrentPassword(
        mixed $currentPassword,
        User $user,
        UserPasswordHasherInterface $userPasswordHasher
    ): bool {
        if (!is_string($currentPassword)) {
            throw new Exception('Password is not of type string');
        }

        return $userPasswordHasher->isPasswordValid($user, $currentPassword);
    }

    private function handleNewPasswodRequest(
        mixed $plainPassword,
        User $user,
        UserPasswordHasherInterface $userPasswordHasher
    ): void {
        if (null != $plainPassword) {
            if (!is_string($plainPassword)) {
                throw new Exception('Password is not of type string');
            }
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $plainPassword
                )
            );
        }
    }
}
