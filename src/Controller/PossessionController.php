<?php

namespace App\Controller;

use App\Entity\Possession;
use App\Entity\User;
use App\Repository\PossessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/possessions')]
class PossessionController extends AbstractController
{
    public function __construct(private PossessionRepository $porepo, private EntityManagerInterface $em) {}
    #[Route(methods: 'GET')]
    public function getAll(Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $pageSize = $request->query->get('pageSize', 5);

        // Utiliser la méthode findAll du repository pour récupérer toutes les possessions
        $possessions = $this->porepo->findBy([], limit: $pageSize, offset: ($page - 1) * $pageSize);

        // Convertir les possessions en tableau associatif
        $possessionsData = [];
        foreach ($possessions as $possession) {
            $possessionsData[] = [
                'id' => $possession->getId(),
                'nom' => $possession->getNom(),
                'valeur' => $possession->getValeur(),
                'type' => $possession->getType(),
            ];
        }

        // Retourner la réponse JSON avec les données des possessions
        return $this->json($possessionsData);
    }

    #[Route(methods: 'POST')]
    public function add(Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            // Désérialiser la demande JSON en un tableau associatif
            $requestData = json_decode($request->getContent(), true);
    
            // Créer une nouvelle entité Possession
            $possession = new Possession();
    
            // Vérifier si le champ "nom" est défini dans la demande JSON
            if (isset($requestData['nom'])) {
                $possession->setNom($requestData['nom']);
            } 
            $possession->setValeur($requestData['valeur'] ?? null);
            $possession->setType($requestData['type'] ?? null);
    
            // Persiste la nouvelle possession dans la base de données
            $this->em->persist($possession);
            $this->em->flush();
    
            // Retourne la réponse JSON avec l'objet possession créé et le code HTTP 201 Created
            return $this->json($possession, 201);
        } catch (\Exception $e) {
            // En cas d'erreur de désérialisation ou d'autres erreurs, retournez une réponse JSON avec le code HTTP 400 Bad Request
            return $this->json('Invalid Body', 400);
        }
    }

    #[Route('/{id}', methods: 'GET')]
    public function getOnePossession(Possession $possession): JsonResponse
    {
        // Récupérer les données de la possession
        $possessionData = [
            'id' => $possession->getId(),
            'nom' => $possession->getNom(),
            'valeur' => $possession->getValeur(),
            'type' => $possession->getType(),
            // Ajoutez d'autres propriétés si nécessaire
        ];

        // Retourner la réponse JSON avec les données de la possession
        return $this->json($possessionData);
    }

    #[Route('/{id}', methods: 'DELETE')]
    public function deletePossession(Possession $possession): JsonResponse
    {
        // Supprimer la possession de la base de données
        $this->em->remove($possession);
        $this->em->flush();

        // Retourner une réponse JSON vide avec le code HTTP 204 No Content
        return $this->json(null, 204);
    }
    #[Route('/{id}', methods: 'PATCH')]
    public function updatePossession(Possession $possession, Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            // Désérialiser la demande JSON en un tableau associatif
            $requestData = json_decode($request->getContent(), true);

            // Mettre à jour les propriétés de la possession avec les nouvelles données
            $possession->setNom($requestData['nom'] ?? $possession->getNom());
            $possession->setValeur($requestData['valeur'] ?? $possession->getValeur());
            $possession->setType($requestData['type'] ?? $possession->getType());

            // Persister la possession dans la base de données
            $this->em->flush();

            // Retourner la réponse JSON avec les données mises à jour de la possession
            return $this->json($possession);
        } catch (\Exception $e) {
            // En cas d'erreur de désérialisation ou d'autres erreurs, retournez une réponse JSON avec le code HTTP 400 Bad Request
            return $this->json('Invalid Body', 400);
        }
    }

   

}