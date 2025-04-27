<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Lấy danh sách menu cho sidebar
     * 
     * @return array
     */
    public static function getSidebarMenu(): array
    {
        return [
            [
                'title' => 'Home',
                'route' => 'admin.dashboard',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
                'active_pattern' => 'admin.dashboard',
            ],
            [
                'title' => 'Events',
                'route' => 'admin.events.index',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />',
                'active_pattern' => 'admin.events.*',
                'submenu' => [
                    [
                        'title' => 'All Events',
                        'route' => 'admin.events.index',
                    ],
                    [
                        'title' => 'Add New',
                        'route' => 'admin.events.create',
                    ],
                ],
            ],
            [
                'title' => 'Categories',
                'route' => 'admin.categories.index',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
                'active_pattern' => 'admin.categories.*',
            ],
            [
                'title' => 'Broadcasts',
                'route' => 'admin.products.index',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />',
                'active_pattern' => 'admin.products.*',
            ],
            [
                'title' => 'Settings',
                'route' => 'admin.names.index',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
                'active_pattern' => 'admin.names.*',
            ],
        ];
    }

    /**
     * Kiểm tra menu có active không
     * 
     * @param string $pattern
     * @return bool
     */
    public static function isActive(string $pattern): bool
    {
        if (is_array($pattern)) {
            foreach ($pattern as $value) {
                if (request()->routeIs($value)) {
                    return true;
                }
            }
            return false;
        }
        
        return request()->routeIs($pattern);
    }
} 