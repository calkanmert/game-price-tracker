<?php

namespace App\Core;

use App\Core\Stores\Steam;
use App\Core\Stores\Epic;
use App\Core\Stores\Gog;

class Product
{
  static function SyncWithSteam() {
    $steam = new Steam();
    $steam->sync_all();
  }

  static function SyncWithEpic() {
    $epic = new Epic();
    $epic->sync_all();
  }
  
  static function SyncWithGog() {
    $gog = new Gog();
    $gog->sync_all();
  }

  static function SyncAll() {
    $steam = new Steam();
    $steam->sync_all();

    $epic = new Epic();
    $epic->sync_all();
  }
}
