<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/r', function()
{
    header('Content-Type: application/excel');
    header('Content-Disposition: attachment; filename="routes.csv"');
 
    $routes = Route::getRoutes();
    $fp = fopen('php://output', 'w');
    fputcsv($fp, ['METHOD', 'URI', 'NAME', 'ACTION']);
    foreach ($routes as $route) {
        fputcsv($fp, [head($route->methods()) , $route->uri(), $route->getName(), $route->getActionName()]);
    }
    fclose($fp);
});*/

Route::get( '/', 'FiltersController@index')->name('main');

Route::get('/terms', function () {
    return view('terms');
});

Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/rentals/', 'RentalSearchController@index');

Route::get('/sales/', 'SaleSearchController@index');

Route::post( '/search/', 'SearchController@search');

Route::get( '/search/address', 'SearchController@searchAddresses');
Route::get( '/search/building', 'SearchController@searchBuildings');
Route::get( '/search/agent', 'SearchController@searchAgents');

Route::get('/autocomplete/agent', 'SearchController@autocompleteAgent');
Route::get('/autocomplete/building', 'SearchController@autocompleteBuilding');

Route::get('/mapbox/polygon', 'MapController@jsonPolygon');

Route::post( '/contact-agent', 'SendController@sendEmail');

Route::get( '/search/{id}/{sort}/{page}', 'SearchController@results')->where('id', '[0-9]+');

Route::get( '/search/{id}/', 'SearchController@results')->where('id', '[0-9]+');
Route::get( '/search/{id}/{sort}', 'SearchController@results')->where('id', '[0-9]+');

Route::post( '/api/send/result', 'SendController@sendResult')->name('sendresult');

Route::post( '/api/send/listing/', '\App\Mail\ContactListingMail@build')->name('sendemaillisting');

Route::post( '/api/send/list', 'SendController@sendList')->name('sendlist');
Route::post( '/api/send/building', 'SendController@sendBuilding')->name('sendbuilding');
Route::post( '/api/log', 'UsersController@logvisit')->name('logvisit');

Route::post('/send', 'SendController@sendEmail')->name('send');

Route::match(['get', 'post'], '/parse', 'SearchController@parse'); //Temp controller for demo data
Route::match(['get', 'post'], '/parseRental', 'SearchController@parseRental'); //Temp controller for demo data

Route::match(['get', 'post'], '/parseb', 'SearchController@parsebuild'); //Temp controller for demo data
Route::match(['get', 'post'], '/parseAgents', 'SearchController@parseAgents'); //Temp controller for demo data
Route::match(['get', 'post'], '/spamClean', 'SearchController@spamClean'); //Temp controller for demo data
Route::match(['get', 'post'], '/parseLogo', 'SearchController@parseLogo'); //Temp controller for demo data

Route::get( '/show/{name}/{id}', 'ShowController@show')->where('id', '[0-9]+');

Route::get( '/agent/{name}/{id}', 'AgentsController@profile')->where('id', '[0-9]+');

Route::get( '/building/{name}/{city}', 'ShowBuildingController@index');

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/home/edit', 'HomeController@editProfile')->name('edit');

    Route::get('/home/profile', 'HomeController@profileAgent')->name('profile');
    Route::post('/home/profile/edit', 'HomeController@editProfileAgent')->name('editProfile');

    Route::get('/home/profile/owner', 'HomeController@profileOwner')->name('profileOwner');
    Route::post('/home/profile/owner/edit', 'HomeController@editProfileOwner')->name('editProfileOwner');

    Route::get('/home/profile/man', 'HomeController@profileMan')->name('profileMan');
    Route::post('/home/profile/man/edit', 'HomeController@editProfileMan')->name('editProfileMan');

    Route::get('/home/listing', 'HomeController@listing')->name('listing');
    Route::get('/home/listing/rental', 'RentalController@index')->name('rental');
    Route::get('/home/listing/sell', 'SellController@index')->name('sell');

    Route::post('/rental/store', 'RentalController@store')->name('addrental');
    Route::post('/rental/edit', 'RentalController@edit')->name('editrental');
    Route::post('/rental/submit', 'RentalController@submit')->name('submitrental');
    Route::post('/rental/delete', 'RentalController@delete')->name('deleterental');

    Route::post('/api/delete/image', 'RentalController@deleteImage')->name('deleteImage');
    Route::post('/api/delete/sell/image', 'SellController@deleteImage')->name('deleteSellImage');

    Route::post('/sell/store', 'SellController@store')->name('addsell');
    Route::post('/sell/show', 'SellController@show')->name('showSell');
    Route::post('/sell/edit', 'SellController@edit')->name('editsell');
    Route::post('/sell/submit', 'SellController@submit')->name('submitsell');
    Route::post('/sell/delete', 'SellController@delete')->name('deletesell');

    Route::post('/open/store', 'OpenHouseController@saveOpenHouse')->name('addopen');

    Route::post('/saveitem', 'ShowController@save')->name('saveitem'); //save listings
    Route::post('/savebuilding', 'ShowBuildingController@save')->name('savebuilding');    

    Route::post('/api/save-search/{id}/', 'SaveSearchController@store')->where('id', '[0-9]+');
    Route::post('/api/save-search/show', 'SaveSearchController@show')->name('getSave');
    Route::get('/saved-searches/{id}/{sort}/{page}', 'SearchController@saveSearch')->where('id', '[0-9]+');
    Route::get('/saved-searches/{id}/', 'SearchController@saveSearch')->where('id', '[0-9]+');
    Route::get('/saved-searches/{id}/{sort}', 'SearchController@saveSearch')->where('id', '[0-9]+');
    Route::post('/delete/search/', 'SaveSearchController@delete')->name('deleteSearch');
    Route::post('/deleteitem', 'SaveSearchController@deleteItem')->name('deleteItem');

    Route::get('/savesearch', 'SaveSearchController@index')->name('searchListing');

    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

    Route::get('/buildings', 'BuildingController@show')->name('buildings');
    Route::match(['get', 'post'],'/building/store', 'BuildingController@store')->name('addBuilding');
    Route::match(['get', 'post'],'/building/edit', 'BuildingController@edit')->name('editBuilding');
    Route::match(['get', 'post'],'/building/update', 'BuildingController@update')->name('updateBuilding');
    Route::match(['get', 'post'],'/building/delete', 'BuildingController@delete')->name('deleteBuilding');
    Route::match(['get', 'post'],'/deletebuildingimage', 'BuildingController@imageDelete')->name('deleteBuildingImage');
    Route::match(['get', 'post'],'/deletebuildimg', 'BuildingController@labelImageDelete')->name('deleteBuildingLabelImage');

    Route::get('/allsell', 'ShowListingController@showSell')->name('allSell');
    Route::get('/allrental', 'ShowListingController@showRental')->name('allRental');

    Route::get('/users', 'UsersController@usersList')->name('userList');
    Route::match(['get', 'post'],'/edituser', 'UsersController@edit')->name('editUser');
    Route::match(['get', 'post'],'/updateuser', 'UsersController@update')->name('updateUser');
    Route::post('/deleteuser', 'UsersController@delete')->name('deleteUser');
    Route::match(['get', 'post'],'/changerole', 'UsersController@changeRole')->name('changeRole');

    Route::get('/users', 'UsersController@usersList')->name('userList');
    Route::match(['get', 'post'],'/edituser', 'UsersController@edit')->name('editUser');
    Route::match(['get', 'post'],'/updateuser', 'UsersController@update')->name('updateUser');
    Route::post('/deleteuser', 'UsersController@delete')->name('deleteUser');

    Route::get('/agents', 'AgentsController@agentsList')->name('agentsList');
    Route::match(['get', 'post'],'/agentedit', 'AgentsController@edit')->name('editAgent');
    Route::match(['get', 'post'],'/agentupdate', 'AgentsController@update')->name('updateAgent');
    Route::match(['get', 'post'],'/agentdelete', 'AgentsController@delete')->name('deleteAgent');
    Route::match(['get', 'post'],'/deleteagentimage', 'AgentsController@deleteImage')->name('deleteAgentImage');

    Route::match(['get', 'post'],'/openhouse', 'OpenHouseController@index')->name('openHouse');
    Route::match(['get', 'post'],'/saveopenhouse', 'OpenHouseController@saveOpenHouse')->name('saveOpenHouse');
    Route::match(['get', 'post'],'/loadopenhouse', 'OpenHouseController@load')->name('loadOpenHouse');
    Route::match(['get', 'post'],'/addopenhouse', 'OpenHouseController@add')->name('addOpenHouse');
    Route::match(['get', 'post'],'/updateopenhouse', 'OpenHouseController@update')->name('updateOpenHouse');
    Route::match(['get', 'post'],'/deleteopen', 'OpenHouseController@delete')->name('updateOpen');
    Route::match(['get', 'post'],'/deleteopenhouse', 'OpenHouseController@deleteOpenHouse')->name('deleteOpenHouse');

    Route::match(['get', 'post'],'/billing', 'BillingController@index')->name('billing');
    Route::match(['get', 'post'],'/upgrade', 'BillingController@upgrade')->name('upgrade');
    Route::match(['get', 'post'],'/billing/agent', 'BillingController@agent')->name('agentBilling');
    Route::match(['get', 'post'],'/billing/expert', 'BillingController@expert')->name('expertBilling');
    Route::match(['get', 'post'],'/billing/namelabel', 'BillingController@nameLabel')->name('nameLabelBilling');
    Route::match(['get', 'post'],'/billing/namelabel/image', 'BillingController@nameLabelImage')->name('nameLabelImage');
    Route::match(['get', 'post'],'/billing/namelabel/description', 'BillingController@nameLabelDescription')->name('nameLabelDescription');
    Route::match(['get', 'post'],'/billing/namelabel/save', 'BillingController@saveNameLabel')->name('saveNameLabel');
    Route::match(['get', 'post'],'/billing/namelabel/update', 'BillingController@updateNameLabel')->name('updateNameLabel');
    Route::match(['get', 'post'],'/feature', 'BillingController@feature')->name('feature');
    Route::match(['get', 'post'],'/feature/{id}', 'BillingController@feature')->name('featureListing');
    Route::match(['get', 'post'],'/featuring', 'BillingController@featuring')->name('featuring');
    Route::match(['get', 'post'],'/premium', 'BillingController@premium')->name('premium');
    Route::match(['get', 'post'],'/premiuming', 'BillingController@premiuming')->name('premiuming');

    Route::match(['get', 'post'],'/upgradeForm', 'BillingController@upgradeForm')->name('upgradeForm');

    Route::match(['get', 'post'],'/news', 'ArticlesController@index')->name('news');
    Route::match(['get', 'post'],'/news/{id}', 'ArticlesController@singleArticle')->name('singleArticle')->where('id', '[0-9]+');;
    Route::match(['get'],'/article', 'ArticlesController@newArticle')->name('newArticle');
    Route::match(['get', 'post'],'/article/add', 'ArticlesController@addArticle')->name('addArticle');
    Route::match(['get', 'post'],'/articles', 'ArticlesController@articleList')->name('articlesList');
    Route::match(['get', 'post'],'/article/edit', 'ArticlesController@editArticle')->name('editArticle');
    Route::match(['get', 'post'],'/article/delete', 'ArticlesController@deleteArticle')->name('deleteArticle');

    Route::match(['get', 'post'],'/autocomplete/search', 'BuildingController@getAddress');

});

Route::match(['get', 'post'],'/test', 'TestController@index')->name('test');//Temp controller for demo data
Route::match(['get', 'post'],'/invite', 'SendController@sendInvitation')->name('invite');//Temp controller for demo data

Route::match(['get', 'post'],'/parsedistrict', 'SearchController@parseDistrict')->name('invite');//Temp controller for demo data

