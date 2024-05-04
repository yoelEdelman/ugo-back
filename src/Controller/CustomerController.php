<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerController extends AbstractController
{
    #[Route('/api/customers', methods: ['GET'])]
    public function index(CustomerRepository $customerRepository, SerializerInterface $serializer): JsonResponse
    {
        $customerList = $customerRepository->findAll();
        $jsonCustomersList = $serializer->serialize($customerList, 'json', ['groups' => 'getOrders']);
        return new JsonResponse($jsonCustomersList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/customers/{id}/orders', methods: ['GET'])]
    public function show(int $id, CustomerRepository $customerRepository, SerializerInterface $serializer): JsonResponse
    {
        $customer = $customerRepository->findOneBy(['customer_id' => $id])->getOrders();

        $jsonCustomersList = $serializer->serialize($customer, 'json', ['groups' => 'getOrders']);
        return new JsonResponse($jsonCustomersList, Response::HTTP_OK, [], true);
    }
}
