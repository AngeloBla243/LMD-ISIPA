<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

use App\Models\NoticeReadModel;
use App\Models\NoticeBoardModel;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     Paginator::useBootstrap();
    // }

    public function boot(): void
    {
        Paginator::useBootstrap();

        // --- View Composer pour compteur notifications non lues ---
        View::composer('*', function ($view) {
            // Ne le faire que pour les étudiants connectés
            if (Auth::check() && Auth::user()->user_type == 3) {
                $user = Auth::user();

                // Toutes les notifications destinées à ce user_type
                // $notices = NoticeBoardModel::getRecordUser($user->user_type);
                $notices = NoticeBoardModel::getRecordUserAll($user->user_type); // ⚡ toutes les notices!


                // IDs des notifications déjà lues
                $readIds = NoticeReadModel::where('user_id', $user->id)
                    ->pluck('notice_board_id')->toArray();

                // Compteur des notices non lues
                $unread_notices = $notices->whereNotIn('id', $readIds)->count();

                // Rendre accessible à toutes les vues
                $view->with('unread_notices', $unread_notices);
            }
        });
    }
}
