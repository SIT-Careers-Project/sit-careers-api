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

    public function getBannerById(Request $request, $banner_id)
    {
        $id = $request->all();
        $banner = $this->banner->getBannerById($banner_id);
        if ($banner) {
            return response()->json($banner, 200);
        }
        return response()->json([
            "message" => "Not found."
        ], 404);
    }

    public function create(Request $request)
    {
        $data = request()->all();
        $validated = Validator::make($data, $this->ruleCreationBanner);
        if ($validated->fails()) {
            return response()->json($validated->messages(), 400);
        }
        try {
            $storage = Storage::disk('minio');
            $bannerName = str_replace(' ', '-', time().'_'.rand(10000,99999));
            $file = $request->file('file_banner');
            if (!is_null($file)) {
                $uploaded = $storage->put('/banner/'.$bannerName, file_get_contents($file), 'public');
                $data['path_image'] = $bannerName;
            }
            $banner = $this->banner->createBanner($data);
        }catch (Throwable $e) {
            return response()->json([
                "message" => "Something Wrong !",
                "error" => $e->getMessage()
            ], 500);
        }
        return response()->json($banner, 200);
    }

    public function destroy(Request $request)
    {
        $data = request()->all();
        $bannerName = $data['path_image'];

        $validated = Validator::make($data, $this->ruleDeletionBanner);
        if ($validated->fails()) {
            return response()->json($validated->messages(), 400);
        }

        if ($bannerName == '-') {
            $banner_deleted = $this->banner->deleteBannerById($data);
        } else {
            $storage = Storage::disk('minio');
            $deleted = $storage->delete('/banner/'.$bannerName);
            $banner_deleted = $this->banner->deleteBannerById($data);
        }

        return response()->json($banner_deleted, 200);
    }
}
