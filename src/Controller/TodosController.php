<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todos')]
class TodosController extends AbstractController
{
    #[Route('/', name: 'todos')]
    public function index(Request $request): Response {
        $session = $request->getSession();
        if (!$session->has('todos')) {
            $todos = [
                'Lundi' => 'Faire du sport',
                'Mardi' => 'Dormir toute la journée',
                'Jeudi' => 'Se presenter au TP Web',
                "Vendredi" => 'Dormir encore'
            ];
            $session->set('todos', $todos);
            if ($request->get('reset') == false)
                $this->addFlash('info', "Le tableau des todos est initialisé");
        }
        return $this->render('todos/listeToDo.html.twig', [
            'controller_name' => 'TodosController',
            'todos' => $session->get('todos')
        ]);
    }

    #[Route(
        '/add/{name}/{content}',
        name: "add.name",
        defaults: ['content' => 'Rien faire']
    )]
    public function addToDo(Request $req, $name, $content): RedirectResponse {
        $session = $req->getSession();

        if (!$session->has("todos")) {
            //show error msg : $todos not initialized
            $this->addFlash('error', "Le tableau todo n'existe pas");
        } else {
            $todos = $session->get('todos');

            if (isset($todos["$name"])) {
                $todos["$name"] = $content;
                //show msg that it has been updated
                $this->addFlash('info', "La todo $name a été modifiée avec succés");

            } else {
                $todos["$name"] = $content;
                $session->set("todos", $todos);
                //show msg that it has been added
                $this->addFlash('info', "La todo $name a été ajoutée avec succés");
            }
        }
        return $this->redirectToRoute("todos");
    }

    #[Route('/delete/{name}', name: "delete.name")]
    public function deleteTodo(Request $req, $name): RedirectResponse {
        $session = $req->getSession();

        if (!$session->has("todos")) {
            //show error msg : $todos not initialized
            $this->addFlash('error', "Le tableau todo n'existe pas");
        } else {
            $todos = $session->get("todos");

            if (!isset($todos["$name"])) {
                //show error msg
                $this->addFlash('error', "La todo $name n'existe pas déja");
            } else {
                unset($todos["$name"]);
                //show success msg
                $this->addFlash('info', "La todo $name a été supprimée");
            }
            $session->set("todos", $todos);
        }
        return $this->redirectToRoute("todos");
    }

    #[Route('/reset', name: "reset")]
    public function resetTodo(Request $req): RedirectResponse {
        $session = $req->getSession();
        if (!$session->has("todos")) {
            //show error msg : $todos not initialized
            $this->addFlash('error', "Le tableau todo n'existe pas");
        } else {
            $session->remove('todos');
            $this->addFlash('info', "Le tableau todos a été réinitialisé avec succés");
        }
        return $this->redirectToRoute('todos', ['reset' => true]);
    }

}
