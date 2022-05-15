<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class HomeController extends Controller
{
   public function FrontendHome()
   {
//  for ($i=0; $i < 10; $i++) { 
//      $users = [
//          'name' => 'user'.$i ,
//          'email' => 'usermail'.$i .'@mail.com',
//          'password' =>  bcrypt('123456789'),
//      ];
//     User::insert($users);
//  }

       return Inertia::render('Welcome', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]);
   }
}
