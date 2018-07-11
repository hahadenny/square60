<?php

namespace App\Http\Controllers;

use App\Repositories\OpenHouseRepo;
use App\Repositories\EstateInfoRepo;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class HomeController extends Controller
{
    private $user;
    private $estate;
    private $open;

    public function __construct(UserRepo $user, EstateInfoRepo $estateInfoRepo, OpenHouseRepo $openRepo)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->estate = $estateInfoRepo;
        $this->open = $openRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function listing(Request $request){

        $request->user()->authorizeRoles(['Owner', 'Agent', 'man']);

        $sellListing = $this->estate->showListing(Auth::user()->id, 1);

        $rentalListing = $this->estate->showListing(Auth::user()->id, 2);

        $openHours = $this->open->getHours();

        return view('listing',compact('sellListing','rentalListing'))->with('openHours', $openHours);
    }

    public function profileAgent(Request $request){

        $request->user()->authorizeRoles(['Agent']);

        $agentInfo = $this->user->userAgent($request->user()->id);

        $alreadyExpert = $agentInfo->subscribed('expert');

        $sellListing = $this->estate->showListing(Auth::user()->id, 1);

        $rentalListing = $this->estate->showListing(Auth::user()->id, 2);

        $openHours = $this->open->getHours();

       if($alreadyExpert){
           return view('profileExpert', compact('sellListing','rentalListing'))->with('agent', $agentInfo->userAgent)->with('openHours', $openHours);
       }else{
           return view('profile', compact('sellListing','rentalListing'))->with('agent', $agentInfo->userAgent)->with('openHours', $openHours);
       }
    }

    public function editProfileAgent(Request $request){

        $validator = Validator::make($request->all(), [
            'lastName' => 'required|min:1',
            'firstName' => 'required|min:1',
            'photo' => 'image|max:20480',
            'email' => 'required|email',
            'company' => 'required|min:1',
            'logo' => 'image|max:20480',
            //'webLink' => 'required|min:3',
            'weblink' => 'min:3',
            'officePhone' => 'required|numeric',
            'cellPhone' => 'required|numeric',
            //'fax' => 'required|numeric',
            //'fax' => 'numeric',
            //'description' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect('/home/profile')
                ->withErrors($validator)
                ->withInput();
        }

        if($this->user->updateAgentProfile($request)){

            $request->session()->flash('status', 'Your profile is updated successfully!');

            return redirect('/home/profile');
        }else{

            $request->session()->flash('status', 'Error, pleae try again later');

            return redirect('/home/profile');
        }
    }

    public function editProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'numeric',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'currentPassword' => 'required_with:password',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('/home')
                ->withErrors($validator)
                ->withInput();
        }

        if ($this->user->updateProfile($request)) {

            $request->session()->flash('status', 'Settings updated');

            return redirect()->route('home');

        } else {
            $request->session()->flash('error', 'Current password does not match');

            return redirect()->route('home');
        }
    }

    public function profileOwner(Request $request){

        $request->user()->authorizeRoles(['Owner']);

        $sellListing = $this->estate->showListing(Auth::user()->id, 1);

        $rentalListing = $this->estate->showListing(Auth::user()->id, 2);

        $openHours = $this->open->getHours();

        return view('profileOwner',compact('sellListing','rentalListing'))->with('openHours', $openHours);
    }

    public function editProfileOwner(Request $request){

        $request->user()->authorizeRoles(['Owner']);

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'email' => 'required|email',
            'cellPhone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect('/home/profile/owner')
                ->withErrors($validator)
                ->withInput();
        }

        if($this->user->updateOwnerProfile($request)){

            $request->session()->flash('status', 'Your profile is updated successfully!');

            return redirect('/home/profile/owner');
        }else{

            $request->session()->flash('status', 'Error, try again late');

            return redirect('/home/profile/owner');
        }
    }

    public function profileMan(Request $request){

        $request->user()->authorizeRoles(['man']);

        $sellListing = $this->estate->showListing(Auth::user()->id, 1);

        $rentalListing = $this->estate->showListing(Auth::user()->id, 2);

        $openHours = $this->open->getHours();

        return view('profileMan',compact('sellListing','rentalListing'))->with('openHours', $openHours);
    }

    public function editProfileMan(Request $request){

        $request->user()->authorizeRoles(['man']);

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'email' => 'required|email',
            'cellPhone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect('/home/profile/man')
                ->withErrors($validator)
                ->withInput();
        }

        if($this->user->updateOwnerProfile($request)){

            $request->session()->flash('status', 'Your profile is updated successfully!');

            return redirect('/home/profile/man');
        }else{

            $request->session()->flash('status', 'Error, try again late');

            return redirect('/home/profile/man');
        }
    }

    public function uploadWatermarkImages($images,$userId,$logo){

        $watermark = $logo;

        foreach ($images as $k=>$item){

            $img = Image::make($item);

            $resizePercentage = 70;//70% less then an actual image (play with this value)

            $watermarkSize = round($img->width() * ((100 - $resizePercentage) / 100), 2); //watermark will be $resizePercentage less then the actual width of the image

            // resize watermark width keep height auto
            $watermark->resize($watermarkSize, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            //insert resized watermark to image center aligned
            $img->insert($watermark, 'bottom-right', 10, 10)->encode('jpg');

            $imageFileName = uniqid(time()). '.jpg';

            $filePath = 'images-rental/unit-images/'.$userId.'/' . $imageFileName;

            $s3 = Storage::disk('s3');

            $s3->put($filePath, (string)$img, 'public');

            $fileNames[] = $imageFileName;
            $filePaths[] = $filePath;
        }

        return compact('fileNames', 'filePath');
    }
}
