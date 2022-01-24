<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Form\ProfileEditFormType;
use Symfony\Component\Mime\Email;
use App\Repository\TurnRepository;
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
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(TurnRepository $turnRepository): Response
    {
        $user = $this->getUser();

        if (null === $user) {
            return $this->redirectToRoute('login');
        }

        if (!$user instanceof User) {
            throw new Exception('User not authenticated');
        }

        $userStats = $turnRepository->getStatsAllTime($user);

        $turns = 0;
        $totalAccuracy = 0;

        foreach ($userStats as $precision) {
            $turns += $precision['count'];
            $totalAccuracy += $precision['sumAccuracy'];
        }

        $meanAccuracy = $totalAccuracy / $turns;

        return $this->render('home/profile.html.twig', [
            'user' => $user,
            'stats' => $userStats,
            'turns' => $turns,
            'accuracy' => $meanAccuracy
        ]);
    }

    /**
     * @Route("/profile/{id}/edit", name="profile_edit")
     */
    public function profileEdit(
        Request $request,
        User $user,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ProfileEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $currentPassword = $form->get('confirmPassword')->getData();
            if (!is_string($currentPassword)) {
                throw new Exception('Password is not of type string');
            }

            $isPasswordValid = $userPasswordHasher->isPasswordValid($user, $currentPassword);

            if (!$isPasswordValid) {
                $errorConfirmation = 'Wrong password';
                return $this->render('home/profile_edit.html.twig', [
                    'registrationForm' => $form->createView(),
                    'errorConfirmation' => $errorConfirmation
                ]);
            }

            $plainPassword = $form->get('plainPassword')->getData();
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
}
