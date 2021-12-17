<?php

namespace App\Controller\front;

use App\Repository\CommandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController

{

    #[Route("/front/commands", name: "CommandList")]
    public function commandsList(CommandRepository $commandRepository)
    {
        $commands = $commandRepository->findAll();
        return $this->render('front/commands.html.twig', ['commands' => $commands]);
    }



    // Système des wildcards
    #[Route("/front/command/{id}", name: "CommandShow")]
    public function productShow($id, CommandRepository $commandRepository)
    {

        $command = $commandRepository->find($id);
        if (isset($command)) {
            return $this->render('front/command.html.twig', ['command' => $command]);
        } else {
            throw  new NotFoundHttpException("Erreur 404. La page que vous cherchez n'a pas été trouvée");
        }
    }

}