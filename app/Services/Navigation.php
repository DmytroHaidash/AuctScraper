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
          /*  new NavItem([
                'name' => 'Scrapers',
                'route' => 'scrapers',
                'icon' => 'i-newspaper',
                'compare' => 'scrapers.index'
            ]),*/
//            new NavItem([
//                'name' => 'Products',
//                'route' => 'products',
//                'icon' => 'i-newspaper',
//                'compare' => 'products.index'
//            ]),
        ];
        return $navigation;
    }

}