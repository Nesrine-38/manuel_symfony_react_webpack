<?php

namespace App\Controller;

use App\Entity\Possession;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\PossessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users')]
class UserController extends AbstractController
{

    public function __construct(private UserRepository $repo,private PossessionRepository $possessionRepository, private EntityManagerInterface $em) {}

    #[Route(methods: 'GET')]
    public function all(SerializerInterface $serializer): JsonResponse
    {
        $users = $this->repo->findAll();
    
        $data = $serializer->serialize($users, 'json', ['groups' => 'user']);
    
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/{id}', methods: 'GET')]
    public function getById(User $user): JsonResponse
    {
        // Utilisez le convertisseur JSON pour renvoyer l'utilisateur au format JSON
        $data = $this->json($user, 200, [], ['groups' => 'user']);

        return $data;
    }

    #[Route(methods: 'POST')]
    public function addUserWithPossession(Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            $requestData = json_decode($request->getContent(), true);

            $user = new User();
            $user->setNom($requestData['nom'] ?? null);
            $user->setPrenom($requestData['prenom'] ?? null);
            $user->setEmail($requestData['email'] ?? null);
            $user->setAdresse($requestData['adresse'] ?? null);
            $user->setTel($requestData['tel'] ?? null);
            $user->setBithDate(new \DateTime($requestData['bithDate']));

            // Créer une nouvelle entité Possession
            $possession = new Possession();
            // Utiliser des valeurs par défaut si les champs de possession sont vides ou nuls
            $possession->setNom($this->validateString($requestData['possession']['nom'] ?? ''));
            $possession->setValeur($this->validateFloat($requestData['possession']['valeur'] ?? 0.0));
            $possession->setType($this->validateString($requestData['possession']['type'] ?? ''));

            // Ajouter la possession à l'utilisateur
            $user->addPossession($possession);

            // Persiste l'utilisateur dans la base de données
            $this->em->persist($user);
            $this->em->flush();

            return $this->json($user, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
    private function validateString($value): string
    {
        if (!is_string($value) || $value === '') {
            throw new \InvalidArgumentException('Invalid or missing string value');
        }

        return $value;
    }

    private function validateFloat($value): float
    {
        if (!is_float($value) || $value === '') {
            throw new \InvalidArgumentException('Invalid or missing float value');
        }

        return $value;
    }
    #[Route('/{id}/possessions', methods: 'GET')]
    public function getUserPossessions(User $user): JsonResponse
    {
        // Récupérer les possessions de l'utilisateur
        $possessions = $user->getPossessions();

        // Convertir les possessions en tableau associatif
        $possessionsData = [];
        foreach ($possessions as $possession) {
            $possessionsData[] = [
                'id' => $possession->getId(),
                'nom' => $possession->getNom(),
                'valeur' => $possession->getValeur(),
                'type' => $possession->getType(),
                // Ajoutez d'autres propriétés si nécessaire
            ];
        }

        // Retourner la réponse JSON avec les données des possessions de l'utilisateur
        return $this->json($possessionsData);
    }

    #[Route('/{id}', methods: 'DELETE')]
    public function delete(User $user): JsonResponse
    {
        try {
            // Supprime l'utilisateur de la base de données
            $this->em->remove($user);
            $this->em->flush();

            // Retourne une réponse JSON avec le code HTTP 204 No Content
            return $this->json(null, 204);
        } catch (\Exception $e) {
            // En cas d'erreur, retourne une réponse JSON avec le code HTTP 500 Internal Server Error
            return $this->json('Internal Server Error', 500);
        }
    }
    
    #[Route('/{id}', methods: 'PATCH')]
    public function update(User $user, Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            // Désérialiser la demande JSON et mettre à jour l'objet User
            $updatedUserData = json_decode($request->getContent(), true);

            // Mettre à jour les propriétés de l'utilisateur
            $user->setNom($updatedUserData['nom'] ?? $user->getNom());
            $user->setPrenom($updatedUserData['prenom'] ?? $user->getPrenom());
            $user->setEmail($updatedUserData['email'] ?? $user->getEmail());
            $user->setAdresse($updatedUserData['adresse'] ?? $user->getAdresse());
            $user->setTel($updatedUserData['tel'] ?? $user->getTel());
            $user->setBithDate($updatedUserData['bithDate'] ?? $user->getBithDate());

            // Persiste les modifications dans la base de données
            $this->em->flush();

            // Retourne la réponse JSON avec l'objet utilisateur mis à jour
            return $this->json($user);
        } catch (\Exception $e) {
            // En cas d'erreur, retourne une réponse JSON avec le code HTTP 400 Bad Request
            return $this->json('Invalid Body', 400);
        }
    }
}