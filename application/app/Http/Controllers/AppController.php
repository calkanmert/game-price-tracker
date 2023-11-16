<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class AppController extends Controller
{
    public function home()
    {
        $data['games'] = Game::all();
        // dd($data['games'][1]->products[0]->store);
        return view('app.pages.home', $data);
    }
    
    public function product(Request $request, string $id)
    {
        $game = Game::whereId($id)->first();

        if (!$game) {
            return redirect()->route('home');
        }
        $data['game'] = $game;
        return view('app.pages.product', $data);
    }
}
