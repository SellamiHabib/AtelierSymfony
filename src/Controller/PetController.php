<?php

namespace App\Controller;

use App\Entity\Pet;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/pets')]
class PetController extends AbstractController
{
    #[Route('/', name: "main_page")]
    public function index(ManagerRegistry $doctrine): Response {

      $repository = $doctrine->getRepository(Pet::class);
      $pets = $repository->findAll();
        return $this->render('pets/index.html.twig', [
            'pets' => $pets,
            'isPaginated'=>false

        ]);
    }
    #[Route('/alls/{page?1}/{nbre?12}', name: "pet.list.alls")]
    public function indexAlls(ManagerRegistry $doctrine,$page,$nbre): Response {

        $repository = $doctrine->getRepository(Pet::class);
        //$pets = $repository->findBy(['name'=>'Gabriela Pascual'], ['id' =>'ASC'],$nbre,($page-1)*$nbre);
        $pets = $repository->findBy([],[],$nbre,($page-1)*$nbre);
        $nbPets = $repository->count([]);
        $nbPages = ceil($nbPets/$nbre);

        return $this->render('pets/index.html.twig', [
            'pets' => $pets,
            'isPaginated' =>true,
            'nbPages'=> $nbPages,
            'nbre'=> $nbre,
            'page'=>$page
        ]);
    }

    #[Route('/{id}',
        name: "pet_detail",
        requirements: ['id' =>"\d+"],
        defaults: [""])]
    public function pet(Pet $pet): Response {

        if ($pet) {
            return $this->render('pets/detail.html.twig', [
                'pet' => $pet
            ]);
        } else {
            $this->addFlash("error", "Pet Doesnt exist");
            return $this->redirectToRoute('main_page');
        }
    }



    #[Route('/add', name: 'pets.add')]
    public function addPet(ManagerRegistry $doctrine): Response {
        $entityManager = $doctrine->getManager();
        $pet = new Pet();
        $pet->setName("Woof");
        $pet->setRace("Slougi");

        $entityManager->persist($pet);
        $entityManager->flush();
        return $this->render('pets/index.html.twig', [
            'pets' => $pet,
        ]);

    }
    #[Route('/delete/{id}', name: 'pets.delete')]
    public function deletePet(Pet $pet = null, ManagerRegistry $doctrine): RedirectResponse {

        if($pet) {
         $entityManager = $doctrine->getManager();
         $entityManager->remove($pet);
         $entityManager->flush();
         $this->addFlash("info","La personne a ete supprimé");
        }
        else {
            $this->addFlash("Error","La personne n'existe pas");
        }
        return $this->redirectToRoute('pet.list.alls');
    }
    #[Route('/update/{id}/{name}/{race}', name: 'pets.update')]
    public function updatePet(Pet $pet = null,$id,$name,$race, ManagerRegistry $doctrine): RedirectResponse {
        if($pet){
            $pet->setName($name);
            $pet->setRace($race);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($pet);
            $entityManager->flush();
            $this->addFlash("info","La personne a ete modifié");
        }
        else {
            $this->addFlash("Error","La personne n'existe pas");
        }
        return $this->redirectToRoute('pet.list.alls');
    }

}
