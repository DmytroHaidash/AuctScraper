<?php

namespace App\Services;

use App\Helpers\NavItem;

class Navigation
{
    public function backend()
    {
        $navigation = [
            new NavItem([
                'name' => 'Type Auctions',
                'route' => 'type',
                'icon' => 'i-newspaper',
                'compare' => 'type.index'
            ]),
            new NavItem([
                'name' => 'Scrapers',
                'route' => 'scraper',
                'icon' => 'i-newspaper',
                'compare' => 'scraper.index'
            ]),
            new NavItem([
                'name' => 'Products',
                'route' => 'product',
                'icon' => 'i-newspaper',
                'compare' => 'product.index'
            ]),
        ];
        return $navigation;
    }

}