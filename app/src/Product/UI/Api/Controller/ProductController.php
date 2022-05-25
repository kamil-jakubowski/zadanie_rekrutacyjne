<?php

namespace App\Product\UI\Api\Controller;

use App\Product\Application\Command\CreateProduct\CreateProductCommand;
use App\Product\Application\Command\Exception\ProductNotFoundException;
use App\Product\Application\Command\RemoveProduct\RemoveProductCommand;
use App\Product\Application\Command\UpdateProductName\UpdateProductNameCommand;
use App\Product\Application\Command\UpdateProductPrice\UpdateProductPriceCommand;
use App\Product\Application\Query\Product\ProductQuery;
use App\Product\Application\Query\ProductList\ProductListQuery;
use App\Product\Application\Query\ProductList\ViewModel\ProductViewModel;
use App\Product\Application\Query\ProductList\ViewModel\ProductViewModelList;
use App\Shared\CQRS\Command\CommandBusInterface;
use App\Shared\CQRS\Query\InvalidCommandArgumentException;
use App\Shared\CQRS\Query\InvalidQueryArgumentException;
use App\Shared\CQRS\Query\QueryBusInterface;
use App\Shared\Exception\DomainInvalidException;
use App\Shared\Exception\InvalidDomainArgumentException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Ramsey\Uuid\Uuid;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractFOSRestController
{
    #[Route('/products', name: 'api_products_list', methods: 'GET')]
    public function getList(Request $request, QueryBusInterface $queryBus): View
    {
        $page = $request->query->getInt('page', 1);
        $items = $request->query->getInt('items', 3);

        if ($page < 1) {
            throw new BadRequestHttpException('Page number must be positive integer');
        }

        if ($items < 1) {
            throw new BadRequestHttpException('Items number must be positive integer');
        }

        try {
            $query = new ProductListQuery($page, $items);
            /** @var ProductViewModelList $list */
            $list = $queryBus->dispatch($query);
        } catch (InvalidQueryArgumentException $e) {
            // catch any validation errors at query and pass as http400 instead of http500 (which will be default behavior of symfony/fosrest)
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->view([
            'products' => $list->toArray(),
            'meta' => [
                'quantityOfAll' => $list->getQuantityOfAll() // calculate num of pages purpose
            ]
        ], Response::HTTP_OK);
    }

    #[Route('/products', name: 'api_products_add', methods: 'POST')]
    public function index(Request $request, CommandBusInterface $commandBus, QueryBusInterface $queryBus): View
    {
        $name = $request->request->get('name');
        $price = $request->request->get('price');

        if (!is_numeric($price)) {
            throw new BadRequestHttpException("Price has to be numeric.");
        }

        $price = (float) $price;

        try {
            $command = new CreateProductCommand($name, $price);

            $commandBus->dispatch($command);
        } catch (InvalidCommandArgumentException $e) {
            // catch any validation errors at query and pass as http400 instead of http500 (which will be default behavior of symfony/fosrest)
            throw new BadRequestHttpException($e->getMessage());
        } catch (ValidationFailedException $e) {
            // catch any validation errors from validation command middleware and pass violations as errors
            // normally I add some normalizer/translator to get errors more convenient for API clients {errors: {field: [its violations]}} it's easier to process at Javascript frameworks but sorry - limited time :)
            return $this->view([
                'errors' => $e->getViolations()
            ], 400);
        }

        $addedProduct = $this->getUpdatedProduct($queryBus, $command->getNewResourceUuid());

        return $this->view([
            'products' => [$addedProduct]
        ], Response::HTTP_CREATED);
    }

    #[Route('/products/{uuid}/updatename', name: 'api_products_patch_name', methods: 'PATCH')]
    public function updateName($uuid, Request $request, CommandBusInterface $commandBus, QueryBusInterface $queryBus): View
    {
        if (!Uuid::isValid($uuid)) {
            throw new BadRequestHttpException("Uuid incorrect");
        }

        $name = $request->request->get('name', '');

        try {
            $command = new UpdateProductNameCommand($uuid, $name);

            $commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $previous = $e->getPrevious();
            try {
                throw $previous;
            } catch (ProductNotFoundException $e) {
                throw new NotFoundHttpException();
            } catch (DomainInvalidException $e) {
                return $this->view([
                    'message' => $e->getMessage()
                ], 400);
            } catch (InvalidDomainArgumentException $e) {
                return $this->view([
                    'message' => $e->getMessage()
                ], 400);
            } catch (\Exception $e) {
                throw $e; // unknown error for handler throw further
            }
        } catch (InvalidCommandArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (ValidationFailedException $e) {
            return $this->view([
                'errors' => $e->getViolations()
            ], 400);
        }
        $updatedProduct = $this->getUpdatedProduct($queryBus, $uuid);

        return $this->view([
            'products' => [$updatedProduct]
        ], Response::HTTP_OK);
    }

    #[Route('/products/{uuid}/updateprice', name: 'api_products_patch_price', methods: 'PATCH')]
    public function updatePrice($uuid, Request $request, CommandBusInterface $commandBus, QueryBusInterface $queryBus): View
    {
        if (!Uuid::isValid($uuid)) {
            throw new BadRequestHttpException("Uuid incorrect");
        }

        $price = $request->request->get('price');

        if (!is_numeric($price)) {
            throw new BadRequestHttpException("Price has to be numeric.");
        }

        $price = (float) $price;

        try {
            $command = new UpdateProductPriceCommand($uuid, $price);

            $commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $previous = $e->getPrevious();
            try {
                throw $previous;
            } catch (ProductNotFoundException $e) {
                throw new NotFoundHttpException();
            } catch (DomainInvalidException $e) {
                return $this->view([
                    'message' => $e->getMessage()
                ], 400);
            } catch (InvalidDomainArgumentException $e) {
                return $this->view([
                    'message' => $e->getMessage()
                ], 400);
            } catch (\Exception $e) {
                throw $e; // unknown error for handler throw further
            }
        } catch (InvalidCommandArgumentException $e) {

            // catch any validation errors at query and pass as http400 instead of http500 (which will be default behavior of symfony/fosrest)
            throw new BadRequestHttpException($e->getMessage());
        } catch (ValidationFailedException $e) {
            // catch any validation errors from validation command middleware and pass violations as errors
            // normally I add some normalizer/translator to get errors more convenient for API clients {errors: {field: [its violations]}} it's easier to process at Javascript frameworks but sorry - limited time :)
            return $this->view([
                'errors' => $e->getViolations()
            ], 400);
        }

        $updatedProduct = $this->getUpdatedProduct($queryBus, $uuid);

        return $this->view([
            'products' => [$updatedProduct]
        ], Response::HTTP_OK);
    }

    /**
     * @param string $uuid
     * @return ProductViewModel
     * @throws BadRequestHttpException when product is not found
     */
    private function getUpdatedProduct(QueryBusInterface $queryBus, string $uuid): ProductViewModel
    {
        try {
            $productQuery = new ProductQuery($uuid);
            return $queryBus->dispatch($productQuery);
        } catch (InvalidQueryArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    #[Route('/products/{uuid}', name: 'api_products_delete', methods: 'DELETE')]
    public function deleteProduct($uuid, CommandBusInterface $commandBus): View
    {
        if (!Uuid::isValid($uuid)) {
            throw new BadRequestHttpException("Uuid incorrect");
        }

        try {
            $command = new RemoveProductCommand($uuid);

            $commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $previous = $e->getPrevious();
            try {
                throw $previous;
            } catch (ProductNotFoundException $e) {
                throw new NotFoundHttpException();
            } catch (DomainInvalidException $e) {
                return $this->view([
                    'message' => $e->getMessage()
                ], 400);
            } catch (InvalidDomainArgumentException $e) {
                return $this->view([
                    'message' => $e->getMessage()
                ], 400);
            } catch (\Exception $e) {
                throw $e; // unknown error for handler throw further
            }
        } catch (InvalidCommandArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (ValidationFailedException $e) {
            return $this->view([
                'errors' => $e->getViolations()
            ], 400);
        }

        return $this->view([

        ], Response::HTTP_OK);
    }
}
