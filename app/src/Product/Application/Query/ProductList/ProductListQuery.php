<?php
declare(strict_types=1);

namespace App\Product\Application\Query\ProductList;

use App\Product\Application\Query\ProductList\Exception\MaxProductPerPageExceededException;
use App\Product\Application\Query\ProductList\Exception\WrongPageNumberException;
use App\Shared\CQRS\Query\QueryInterface;

/**
 * Class {ProductListQuery}
 * @author Kamil Jakubowski <kamil.jakubowski@gmail.com>
 */
class ProductListQuery implements QueryInterface
{
    public const MAX_PER_PAGE = 3;

    /**
     * Pagination page number
     * @var int
     */
    private int $page;

    /**
     * Items per page
     * Maximum is static::MAX_PER_PAGE
     * @var int
     */
    private int $itemsPerPage;

    public function __construct(int $page = 1, int $itemsPerPage = 3)
    {
        if ($page < 1) {
            throw WrongPageNumberException::create();
        }

        /**
         * // info: assumed that max 3 products per page its application requirement,
         * if it is UI/API requirement it should be moved to controller/console_command/etc
         */
        if ($itemsPerPage > static::MAX_PER_PAGE) {
            throw MaxProductPerPageExceededException::create($itemsPerPage);
        }

        $this->page = $page;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}