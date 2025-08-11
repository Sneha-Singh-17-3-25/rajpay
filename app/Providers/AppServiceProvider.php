<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Schema::defaultStringLength(191);

        try {
            // dd($_SERVER['HTTP_HOST']);
            view()->composer('*', function ($view){
                $mydata['sessionOut'] = 0;//\App\Model\PortalSetting::where('code', 'sessionout')->first()->value;
                $mydata['complaintsubject'] = [];
                $mydata['company'] = \App\Model\Company::where('website', $_SERVER['HTTP_HOST'])->first();
                $mydata['topheadcolor'] = \App\Model\PortalSetting::where('code', "topheadcolor")->first();
                $mydata['sidebarlightcolor'] = \App\Model\PortalSetting::where('code', "sidebarlightcolor")->first();
                $mydata['sidebardarkcolor'] = \App\Model\PortalSetting::where('code', "sidebardarkcolor")->first();
                $mydata['sidebariconcolor'] = \App\Model\PortalSetting::where('code', "sidebariconcolor")->first();
                $mydata['sidebarchildhrefcolor'] = \App\Model\PortalSetting::where('code', "sidebarchildhrefcolor")->first();
                //$mydata['schememanager'] = \App\Model\PortalSetting::where('code', "schememanager")->first();
//                 $pincheck = \App\Model\PortalSetting::where('code', "pincheck")->first();

//                 if($pincheck){
//                     $mydata['pincheck'] = $pincheck->value;
//                 }else{
//                     $mydata['pincheck'] =  "no";
//                 }
                
                $news = \App\Model\Companydata::where('company_id', $mydata['company'])->first();
                if($news){
                    $mydata['news'] = $news->news;
                    $mydata['notice'] = $news->notice;
                    $mydata['billnotice'] = $news->billnotice;
                    $mydata['supportnumber'] = $news->number;
                    $mydata['supportemail'] = $news->email;
                }else{
                    $mydata['news'] = "";
                    $mydata['notice'] = "";
                    $mydata['billnotice'] = "";
                    $mydata['supportnumber'] = "";
                    $mydata['supportemail'] = "";
                }
                if (\Auth::check()) {
                    $mydata['downlinebalance'] = 0;//\App\User::whereIn('id', session('parentData'))->where('id', "!=", \Auth::id())->sum('mainwallet');
                }
                $view->with('mydata', $mydata);    
            }); 
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
