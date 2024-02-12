<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(ActivityRepository $activityRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $userActivities = $user->getActivities();

        $activities = $activityRepository->findAllUnderAge($user->getAge());


        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'activities' => $activities,
            'userActivities' => $userActivities,
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/subscribe/{id}', name: 'app_subscribe', methods: ['POST'])]
    public function subscribe(Activity $activity, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $user->addActivity($activity);
        $activity->addUser($user);

        $entityManager->flush();

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/unsubscribe/{id}', name: 'app_unsubscribe', methods: ['POST'])]
    public function unsubscribe(Activity $activity, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $user->removeActivity($activity);
        $activity->removeUser($user);

        $entityManager->flush();

        return $this->redirectToRoute('app_profile');
    }
}
