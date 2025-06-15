<?php

namespace App\Services;

use App\Models\ChildMenu;

class MenuService
{
    public function getAllMenus()
    {
        return ChildMenu::where('status', 1)
            ->orderBy('display_position', 'asc')
            ->get();
    }
}
