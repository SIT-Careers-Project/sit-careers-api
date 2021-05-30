<?php

namespace App\Repositories;

use App\Models\Banner;

class BannerRepository implements BannerRepositoryInterface
{
    public function getBannerById($id)
    {
        $banner = Banner::where('banner_id', $id)
                    ->first();
        return $banner;
    }

    public function getAllBanners()
    {
        $banners = Banner::all();
        return $banners;
    }

    public function createBanner($data)
    {
        $banner = new Banner();
        $banner->path_image = $data['path_image'] == "" ? "-": $data['path_image'];
        $banner->save();

        return array_merge($banner->toArray());
    }

    public function deleteBannerById($data)
    {
        $banner = Banner::find($data)
                ->first();
        if ($banner) {
            $deleted_banner = $banner->delete();
            return $deleted_banner;
        }

        return "Find not found banner";
    }
}
