<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyBasketItems as NotifyBasketItemsMail;
use Illuminate\Support\Facades\DB;

class notifyBasketItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:basket-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending Notification to pending orders';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        // $users = DB::statement('SELECT users.username, dishes.dish_name from baskets INNER JOIN basket_dishes on baskets.basket_id = basket_dishes.basket_id INNER JOIN users ON users.user_id = baskets.user_id INNER JOIN dishes on dishes.dish_id = basket_dishes.dish_id;');

        $users = DB::table('baskets')
            ->join('basket_dishes', 'baskets.basket_id', '=', 'basket_dishes.basket_id')
            ->join('users', 'users.user_id', '=', 'baskets.user_id')
            ->join('dishes', 'dishes.dish_id', '=', 'basket_dishes.dish_id')
            ->select('users.username', 'dishes.dish_name')
            ->get();

        if (count($users) > 0) {
            foreach ($users as $user) {
                Mail::to($user->username)->send(new NotifyBasketItemsMail($user->username, $user->dish_name));
            }
        }
    }
}
