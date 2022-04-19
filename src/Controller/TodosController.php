<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodosController extends AbstractController
{
    #[Route('/todos', name: 'todos')]
    public function index(Request $request): Response {
        $session = $request->getSession();
        if (!$session->has('todos')) {
            $todos = [
                'Lundi' => 'Faire du sport',
                'Mardi' => 'Dormir toute la journée',
                'Jeudi' => 'Se presenter au TP Web',
                "Vendredi" => 'Dormir encore'
            ];
            if ($request->get('reset') == false)
                $this->addFlash('info', "Le tableau des todos est initialisé");

            $session->set('todos', $todos);
        }
        return $this->render('todos/listeToDo.html.twig', [
            'controller_name' => 'TodosController',
            'todos' => $session->get('todos')
        ]);
    }

    #[Route('/addTodo/{name}/{content}', name: "add.name")]
    public function addToDo(Request $req, $name, $content) {
        $session = $req->getSession();
        if (!$session->has("todos")) {
            //show error msg : $todos not initialized
            $this->addFlash('error', "Le tableau todo n'existe pas");
        } else {
            $todos = $session->get('todos');
            if (isset($todos["$name"])) {
                //show msg that it has been updated
                $this->addFlash('info', "La todo $name a été modifiée avec succés");
                $todos["$name"] = $content;
            } else {
                //show msg that it has been added
                $this->addFlash('info', "La todo $name a été ajoutée avec succés");
                $todos["$name"] = $content;
                $session->set("todos", $todos);
            }
        }
        return $this->redirectToRoute("todos");
    }

    #[Route('/deleteTodo/{name}', name: "delete.name")]
    public function deleteTodo(Request $req, $name) {
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

    #[Route('/resetTodos', name: "reset")]
    public function resetTodo(Request $req) {
        $session = $req->getSession();
        if (!$session->has("todos")) {
            //show error msg : $todos not initialized
            $this->addFlash('error', "Le tableau todo n'existe pas");
        } else {
            $session->clear();
            $this->addFlash('info', "Le tableau todos a été réinitialisé avec succés");
        }
        return $this->redirectToRoute('todos', ['reset' => true]);
    }
}
