<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $AppHelper;
    private $Product;
    private $Category;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->Product = new Product();
        $this->Category = new Category();
    }

    public function getAllProductInfo() {
        $product_list = $this->Product->findAllProducts();

        $formatedProductList = array();
        foreach ($product_list as $key => $value) {

            $category_info = $this->Category->find_by_id($value['category']);

            $formatedProductList[$key]['productName'] = $value['product_name'];
            $formatedProductList[$key]['categoryName'] = $category_info['category_name'];

            if ($value['status'] == 1) {
                $formatedProductList[$key]['inStock'] = true;
            } else {
                $formatedProductList[$key]['inStock'] = false;
            }

            $adminDomain = "https://adminapi.dropshipper.lk/images/";
            $noImageContent = request()->root() . "/images" . "/" . "noimage.jpg";
            
            $decodedImages = json_decode($value['images']);

            if (isset($decodedImages->image0)) {
                $formatedProductList[$key]['firstImage'] = $adminDomain . $decodedImages->image0;
            } else {
                $formatedProductList[$key]['firstImage'] = $noImageContent;
            }

            if (isset($decodedImages->image1)) {
                $formatedProductList[$key]['secondImage'] = $adminDomain . $decodedImages->image1;
            } else {
                $formatedProductList[$key]['secondImage'] = $noImageContent;
            }

            if (isset($decodedImages->image2)) {
                $formatedProductList[$key]['thirdImage'] = $adminDomain . $decodedImages->image2;
            } else {
                $formatedProductList[$key]['thirdImage'] = $noImageContent;
            }
        }

        return $this->AppHelper->responseEntityHandle(1, "Operation Successfully", $formatedProductList);
    }

    public function getAllCategories() {
        $category_info = $this->Category->find_all();

        $formated_categories = array();
        foreach ($category_info as $key => $value) {
            $formated_categories[$key]['id'] = $value['id'];
            $formated_categories[$key]['categoryName'] = $value['category_name'];
        }

        return $this->AppHelper->responseEntityHandle(1, "Operation Successfully", $formated_categories);
    }

    public function filterByCategory(Request $request) {

        $categoryId = (is_null($request->categoryId) || empty($request->categoryId)) ? "" : $request->categoryId;

        if ($categoryId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Invalid Category ID");
        } else {

            try {
                $resp = $this->Product->find_by_Cid($categoryId);

                $dataList = array();
                foreach ($resp as $key => $value) {
                    $category = $this->Category->find_by_id($value['category']);

                    $dataList[$key]['id'] = $value['id'];
                    $dataList[$key]['productName'] = $value['product_name'];
                    $dataList[$key]['categoryName'] = $category->category_name;
                    $dataList[$key]['description'] = $value['description'];
                    $dataList[$key]['price'] = $value['price'];

                    $adminDomain = "https://adminapi.dropshipper.lk/images/";
                    $noImageContent = request()->root() . "/images" . "/" . "noimage.jpg";
                    
                    $decodedImages = json_decode($value['images']);

                    if (isset($decodedImages->image0)) {
                        $dataList[$key]['firstImage'] = $adminDomain . $decodedImages->image0;
                    } else {
                        $dataList[$key]['firstImage'] = $noImageContent;
                    }

                    if (isset($decodedImages->image1)) {
                        $dataList[$key]['secondImage'] = $adminDomain . $decodedImages->image1;
                    } else {
                        $dataList[$key]['secondImage'] = $noImageContent;
                    }

                    if (isset($decodedImages->image2)) {
                        $dataList[$key]['thirdImage'] = $adminDomain . $decodedImages->image2;
                    } else {
                        $dataList[$key]['thirdImage'] = $noImageContent;
                    }

                    $dataList[$key]['createTime'] = $value['create_time'];

                    if ($value['status'] == 1) {
                        $dataList[$key]['inStock'] = true;
                    } else {
                        $dataList[$key]['inStock'] = false;
                    }
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
            } catch (Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, "Error Occured " . $e->getMessage());
            }
        }
    }
}
