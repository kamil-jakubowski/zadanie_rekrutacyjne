<?php
declare(strict_types=1);

namespace App\Cart\UI\Api\Controller;

use App\Cart\Application\Command\AddProduct\AddProductToCartCommand;
use App\Cart\Application\Command\CreateCart\CreateCartCommand;
use App\Cart\Application\Command\RemoveProduct\RemoveProductCommand;
use App\Cart\Application\Query\Exception\CartNotFoundException;
use App\Cart\Application\Query\GetCart\GetCartQuery;
use App\Cart\Application\Query\ViewModel\CartViewModel;
use App\Product\Application\Command\CreateProduct\CreateProductCommand;
use App\Product\Application\Command\Exception\ProductNotFoundException;
use App\Shared\CQRS\Command\CommandBusInterface;
use App\Shared\CQRS\Query\InvalidCommandArgumentException;
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

/**
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class CartController extends AbstractFOSRestController
{
    #[Route('/carts', name: 'api_carts_add', methods: 'POST')]
    public function addCart(CommandBusInterface $commandBus, QueryBusInterface $queryBus): View
    {
        try {
            $command = new CreateCartCommand();

            $commandBus->dispatch($command);
        } catch (InvalidCommandArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (ValidationFailedException $e) {
            return $this->view([
                'errors' => $e->getViolations()
            ], 400);
        }

        $addedCart = $this->getCart($queryBus, $command->getNewResourceUuid());

        return $this->view([
            'carts' => [$addedCart]
        ], Response::HTTP_CREATED);
    }

    #[Route('/carts/{uuid}/addproduct', name: 'api_carts_add_product', methods: 'PATCH')]
    public function addProductToCart($uuid, CommandBusInterface $commandBus, QueryBusInterface $queryBus, Request $request): View
    {
        if (!Uuid::isValid($uuid)) {
            throw new BadRequestHttpException("Cart Uuid incorrect");
        }

        $productUuid = $request->request->get('productUuid', '');

        if (!Uuid::isValid($productUuid)) {
            throw new BadRequestHttpException("Product Uuid incorrect");
        }

        try {
            $command = new AddProductToCartCommand($uuid, $productUuid);

            $commandBus->dispatch($command);
        }  catch (HandlerFailedException $e) {
            $previous = $e->getPrevious();
            try {
                throw $previous;
            } catch (ProductNotFoundException $e) {
                throw new BadRequestHttpException("Product uuid does not exist");
            } catch(CartNotFoundException $e) {
                throw new NotFoundHttpException("Cart does not exist");
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

        $updatedCart = $this->getCart($queryBus, $uuid);

        return $this->view([
            'carts' => [$updatedCart]
        ], Response::HTTP_OK);
    }

    #[Route('/carts/{uuid}/removeproduct', name: 'api_carts_remove_product', methods: 'PATCH')]
    public function removeProductFromCart($uuid, CommandBusInterface $commandBus, QueryBusInterface $queryBus, Request $request): View
    {
        if (!Uuid::isValid($uuid)) {
            throw new BadRequestHttpException("Cart Uuid incorrect");
        }

        $productUuid = $request->request->get('productUuid', '');

        if (!Uuid::isValid($productUuid)) {
            throw new BadRequestHttpException("!Product Uuid incorrect ".$productUuid);
        }

        try {
            $command = new RemoveProductCommand($uuid, $productUuid);

            $commandBus->dispatch($command);
        }  catch (HandlerFailedException $e) {
            $previous = $e->getPrevious();
            try {
                throw $previous;
            } catch (ProductNotFoundException $e) {
                throw new BadRequestHttpException("Product uuid does not exist");
            } catch(CartNotFoundException $e) {
                throw new NotFoundHttpException("Cart does not exist");
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

        $updatedCart = $this->getCart($queryBus, $uuid);

        return $this->view([
            'carts' => [$updatedCart]
        ], Response::HTTP_OK);
    }

    #[Route('/carts/{uuid}', name: 'api_cart_get', methods: 'GET')]
    public function getOne($uuid, QueryBusInterface $queryBus): View
    {
        if (!Uuid::isValid($uuid)) {
            throw new BadRequestHttpException("Cart Uuid incorrect");
        }

        try {
            $cart = $this->getCart($queryBus, $uuid);
        } catch (HandlerFailedException $e) {
            try {
                throw $e->getPrevious();
            } catch (CartNotFoundException $e) {
                throw new NotFoundHttpException("Cart does not exists");
            } catch (\Exception $e) {
                throw $e; // unrecognised, go further
            }
        }

        return $this->view([
            'carts' => [$cart],
            'meta' => ['totalPrice' => $cart->getTotalPrice()]
        ], Response::HTTP_OK);
    }

    private function getCart(QueryBusInterface $queryBus, string $uuid): CartViewModel
    {
        return $queryBus->dispatch(new GetCartQuery($uuid));
    }
}