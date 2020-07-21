<?php

$router->group(['prefix' => 'v1', 'namespace' => 'v1'], function () use ($router) {
    $router->post('/incubator/register', 'Inkubator\AuthController@register');
    $router->post('/startup/register', 'Inkubator\AuthController@register');
    $router->get('/incubator/cek-expired', 'Admin\Inkubator\IncubatorController@cekExpired');
    
    // Admin
    $router->group(['prefix' => 'admin'], function () use ($router) {
        $router->post('login', 'Admin\AuthController@login');
        $router->group(['middleware' => 'auth:admin'], function () use ($router) {
            $router->get('/incubator', 'Admin\Inkubator\IncubatorController@index');            
            $router->get('/incubator/{id}', 'Admin\Inkubator\IncubatorController@show');            
            $router->put('/incubator/{id}', 'Admin\Inkubator\IncubatorController@update');            
            $router->delete('/incubator/{id}', 'Admin\Inkubator\IncubatorController@delete');          
            $router->post('/incubator/enable', 'Admin\Inkubator\IncubatorController@enable');            
            $router->get('/startup', 'Admin\Startup\StartupController@index');            
            $router->put('/startup', 'Admin\Startup\StartupController@update');            
            $router->delete('/startup/{id}', 'Admin\Startup\StartupController@delete');          
            $router->get('/total/{data}', 'Admin\Inkubator\IncubatorController@total');   
            
            // Paket
            $router->get('/incubator/paket/{id}', 'Admin\Inkubator\IncubatorController@getIncubatorByPaket');
            $router->post('/incubator/edit-paket/{id}', 'Admin\Inkubator\IncubatorController@updatePaketIncubator');
            $router->post('/incubator/aktifkan-paket', 'Admin\Inkubator\IncubatorController@aktifkanPaket');
            
            // Master Bank
            $router->get('/bank', 'Admin\BankController@index');
            $router->post('/bank', 'Admin\BankController@store');
            $router->post('/bank/{id}', 'Admin\BankController@update');
            $router->delete('/bank/{id}', 'Admin\BankController@destroy');
        });
    });
    
    // Incubator
    $router->group(['prefix' => 'incubator'], function () use ($router) {
        $router->post('login', 'Inkubator\AuthController@login');
        $router->post('register', 'Inkubator\AuthController@register');
        $router->group(['middleware' => 'auth:incubator'], function () use ($router) {
            $router->get('/', 'Inkubator\IncubatorController@show');            
            $router->get('/show-startup', 'Inkubator\IncubatorController@getStartup');            
            $router->post('/update', 'Inkubator\IncubatorController@update');            
            // award
            $router->get('/award', 'Inkubator\AwardController@index');            
            $router->post('/award', 'Inkubator\AwardController@store');            
            $router->post('/award/update/{id}', 'Inkubator\AwardController@update');            
            $router->delete('/award/{id}', 'Inkubator\AwardController@destroy');            
            
            // mentor
            $router->get('/mentor', 'Inkubator\MentorController@index');            
            $router->post('/mentor', 'Inkubator\MentorController@store');            
            $router->post('/mentor/update/{id}', 'Inkubator\MentorController@update');            
            $router->delete('/mentor/{id}', 'Inkubator\MentorController@destroy');
            
            // tenans
            $router->get('/tenan', 'Inkubator\TenanController@index');            
            $router->post('/tenan', 'Inkubator\TenanController@store');            
            $router->post('/tenan/update/{id}', 'Inkubator\TenanController@update');            
            $router->delete('/tenan/{id}', 'Inkubator\TenanController@destroy');
            // Kategori tenan         
            $router->get('/kategori-tenan', 'Inkubator\TenanController@kategoriTenan');            
            $router->post('/kategori-tenan', 'Inkubator\TenanController@storeKategoriTenan');            
            $router->post('/kategori-tenan/update/{id}', 'Inkubator\TenanController@updateKategoriTenan');            
            $router->delete('/kategori-tenan/{id}', 'Inkubator\TenanController@destroyKategori');            
            
            // Traction
            $router->get('/traction', 'Inkubator\TractionController@index');            
            $router->post('/traction', 'Inkubator\TractionController@store');            
            $router->put('/traction/{id}', 'Inkubator\TractionController@update');            
            $router->delete('/traction/{id}', 'Inkubator\TractionController@destroy');
            // view traction
            $router->get('/traction/{id}', 'Inkubator\TractionController@show');            
            $router->get('/traction/view/{id}', 'Inkubator\TractionController@view');
            $router->get('/traction/view/response/{judul}/{startup}', 'Inkubator\TractionController@viewRespon');
            
            // sow traction dahsboar
            $router->post('/traction/show', 'Inkubator\TractionController@showDashboard');

            // Sprint
            $router->get('/sprint/{id}', 'Inkubator\SprintController@board');
            $router->post('/sprint', 'Inkubator\SprintController@store');
            $router->post('/sprint/update/{id}', 'Inkubator\SprintController@update');
            $router->delete('/sprint/{id}', 'Inkubator\SprintController@destroy');

            // List Sprint
            $router->get('/sprint-list/{id}', 'Inkubator\SprintController@list');
            $router->post('/sprint-list', 'Inkubator\SprintController@storeList');
            $router->post('/sprint-list/update/{id}', 'Inkubator\SprintController@updateList');
            $router->delete('/sprint-list/{id}', 'Inkubator\SprintController@destroyList');
            $router->get('/sprint-list/move/list/{idListPindah}/{idListkePindah}', 'Inkubator\SprintController@moveList');

            // Card in List
            $router->get('/card/show/{id}', 'Inkubator\SprintController@getCardbyId');
            $router->get('/card/{id}', 'Inkubator\SprintController@card');
            $router->post('/card', 'Inkubator\SprintController@storeCard');
            $router->delete('/card/{id}/{idListAsal}', 'Inkubator\SprintController@destroyCard');
            
            // update
            $router->post('/card/update/nama/{id}', 'Inkubator\SprintController@updateCardNama');
            $router->post('/card/update/description/{id}', 'Inkubator\SprintController@updateCardDescription');
            $router->post('/card/update/berkas/{id}', 'Inkubator\SprintController@updateCardBerkas');
            
            // move/copy to card to sprint list
            $router->get('/card/move/list/{idcard}/{idlist}/{idListAsal}', 'Inkubator\SprintController@moveCardtoSprint');
            $router->get('/card/copy/list/{idcard}/{idlist}/{idStartup}', 'Inkubator\SprintController@copyCardtoSprint');
            
            // move card
            $router->get('/card/move/card/{idCardPindah}/{idCardkePindah}', 'Inkubator\SprintController@moveCard');
            $router->post('/card/move/tolist', 'Inkubator\SprintController@moveCardtoList');
            
            // Get Notification
            $router->get('/startup/show-join', 'Inkubator\NotifController@getJoin');
            $router->post('/startup/accept-join', 'Inkubator\NotifController@acceptJoin');
            
            // Pesan Paket
            $router->post('/pesan-paket', 'Inkubator\PembayaranController@bayar');
            $router->post('/konfirmasi-pembayaran', 'Inkubator\PembayaranController@konfirmasi');
            $router->get('/riwayat-pembayaran', 'Inkubator\PembayaranController@riwayat_menunggu');

            $router->post('/export-sprint', 'Inkubator\SprintController@export');
        });
    });
    
    // Startup
    $router->group(['prefix' => 'startup'], function () use ($router) {
        $router->post('login', 'Startup\AuthController@login');
        $router->post('register', 'Startup\AuthController@register');
        $router->group(['middleware' => 'auth:startup'], function () use ($router) {
            $router->get('/show-startup', 'Startup\StartupController@getStartup');
            $router->post('/update', 'Startup\StartupController@update');            
            $router->get('/profile', 'Startup\StartupController@show');            
            
            // award
            $router->get('/award', 'Startup\AwardController@index');            
            $router->post('/award', 'Startup\AwardController@store');            
            $router->post('/award/update/{id}', 'Startup\AwardController@update');            
            $router->delete('/award/{id}', 'Startup\AwardController@destroy');
            
            // founder
            $router->get('/founder', 'Startup\FounderController@index');            
            $router->post('/founder', 'Startup\FounderController@store');            
            $router->post('/founder/update/{id}', 'Startup\FounderController@update');            
            $router->delete('/founder/{id}', 'Startup\FounderController@destroy');
            
            // product
            $router->get('/product', 'Startup\ProductController@index');            
            $router->post('/product', 'Startup\ProductController@store');            
            $router->post('/product/update/{id}', 'Startup\ProductController@update');            
            $router->delete('/product/{id}', 'Startup\ProductController@destroy');
            
            // Traction
            $router->get('/traction', 'Startup\TractionController@index');
            $router->get('/traction/{id}', 'Startup\TractionController@show');            
            // view traction
            $router->get('/traction/view/{id}', 'Startup\TractionController@viewTraction');
            $router->post('/traction/submit', 'Startup\TractionController@submitTraction');
            $router->get('/traction/view/response/{judul}', 'Startup\TractionController@viewRespon');
            // Show dashboard
            $router->post('/traction/show', 'Startup\TractionController@showDashboard');
            
            // Sprint
            $router->get('/sprint', 'Startup\SprintController@board');
            $router->post('/sprint', 'Startup\SprintController@store');
            $router->post('/sprint/update/{id}', 'Startup\SprintController@update');
            $router->delete('/sprint/{id}', 'Startup\SprintController@destroy');

            // List Sprint
            $router->get('/sprint-list/{id}', 'Startup\SprintController@list');
            $router->post('/sprint-list', 'Startup\SprintController@storeList');
            $router->post('/sprint-list/update/{id}', 'Startup\SprintController@updateList');
            $router->delete('/sprint-list/{id}', 'Startup\SprintController@destroyList');
            $router->get('/sprint-list/move/list/{idListPindah}/{idListkePindah}', 'Startup\SprintController@moveList');

            // Card in List
            $router->get('/card/show/{id}', 'Startup\SprintController@getCardbyId');
            $router->get('/card/{id}', 'Startup\SprintController@card');
            $router->post('/card', 'Startup\SprintController@storeCard');
            $router->delete('/card/{id}/{idListAsal}', 'Startup\SprintController@destroyCard');
            
            // update
            $router->post('/card/update/nama/{id}', 'Startup\SprintController@updateCardNama');
            $router->post('/card/update/description/{id}', 'Startup\SprintController@updateCardDescription');
            $router->post('/card/update/berkas/{id}', 'Startup\SprintController@updateCardBerkas');
            
            // move/copy to card to sprint list
            $router->get('/card/move/list/{idcard}/{idlist}/{idListAsal}', 'Startup\SprintController@moveCardtoSprint');
            $router->get('/card/copy/list/{idcard}/{idlist}', 'Startup\SprintController@copyCardtoSprint');
            
            // move card
            $router->get('/card/move/card/{idCardPindah}/{idCardkePindah}', 'Startup\SprintController@moveCard');
            $router->post('/card/move/tolist', 'Startup\SprintController@moveCardtoList');
        });
    });
    
    
});
