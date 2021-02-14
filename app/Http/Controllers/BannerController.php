<?php

namespace App\Http\Controllers;

use Validator;
use Storage;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller as Controller;
use App\Http\RulesValidation\BannerRules;
use App\Repositories\BannerRepositoryInterface;
use Throwable;

class BannerController extends Controller
{
    use BannerRules;
    private $banner;

    public function __construct(BannerRepositoryInterface $banner_repo)
    {
        $this->banner = $banner_repo;
    }

    public function get(Request $request)
    {
        $banners = $request->all();
        $banners = $this->banner->getAllBanners();
        return response()->json($banners, 200);
    }

    public function getBannerById(Request $request)
    {
        $id = $request->all();
        $banner = $this->banner->getBannerById($id['banner_id']);
        return response()->json($banner, 200);
    }

    public function create(Request $request)
    {
        $data = request()->all();
        $validated = Validator::make($data, $this->ruleCreationBanner);
        if ($validated->fails()) {
            return response()->json($validated->messages(), 400);
        }
        try {
            $s3 = Storage::disk('s3');
            $bannerName = str_replace(' ', '-', time().'_'.rand(10000,99999));
            $file = $request->file('file_banner');
            if (!is_null($file)) {
                $uploaded = $s3->put('/banner/'.$bannerName, file_get_contents($file), 'public');
                $data['path_image'] = $bannerName;
            }
            $banner = $this->banner->createBanner($data);
        } catch (Throwable $e) {
            return "Something Wrong: ".$e;
        }
        return response()->json($banner, 200);
    }

    public function destroy(Request $request)
    {
        $id = request()->all();
        $banner_deleted =$this->banner->deleteBannerById($id);
        return response()->json($banner_deleted, 200);
    }
}
