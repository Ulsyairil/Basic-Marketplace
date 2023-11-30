<?php

namespace App\Repositories\Product;

use F9Web\ApiResponseHelpers;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Product;

class ProductRepositoryImplement extends Eloquent implements ProductRepository
{
    use ApiResponseHelpers;

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Product|mixed $model;
     */
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function pagination($request)
    {
        $user = auth('sanctum')->user();
        $query = Product::query()->with(['User']);

        if ($user->roles == 'seller') {
            $query->where('user_id', $user->id);
        }

        if ($request->trash) {
            $query->onlyTrashed();
        }

        if ($request->key != (null || '')) {
            $orderToBoolean = filter_var($request->order, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

            $orderBy = $orderToBoolean ? "desc" : "asc";
            $query->orderBy($request->key, $orderBy);
        }

        $result = $query->paginate($request->limit);
        return response()->json($result);
    }

    public function get($id)
    {
        $user = auth('sanctum')->user();
        $query = Product::query()
            ->with(['User'])
            ->where('id', $id);

        if ($user->roles == 'seller') {
            $query->where('user_id', $user->id);
        }

        $result = $query->first();

        if (!$result) {
            return $this->respondNotFound("Product Not Found");
        }

        return $this->respondWithSuccess([
            "message" => "Success get data",
            "data" => $result
        ]);
    }

    public function store($request)
    {
        $user = auth('sanctum')->user();

        if ($user->roles != ('seller' || 'admin')) {
            return $this->respondForbidden("Must Login Using Seller Account");
        }

        $query = Product::query()->create([
            "user_id" => $user->id,
            "name" => $request->name,
            "price" => $request->price,
            "description" => $request->description
        ]);

        return $this->respondWithSuccess([
            "message" => "Product Has Been Added",
            "data" => $query
        ]);
    }

    public function update($id, $request)
    {
        $user = auth('sanctum')->user();

        if ($user->roles != ('seller' || 'admin')) {
            return $this->respondForbidden("Must Login Using Seller Account");
        }

        $query = Product::query()->where('id', $id);

        $query->update([
            "user_id" => $user->id,
            "name" => $request->name,
            "price" => $request->price,
            "description" => $request->description
        ]);

        $result = $query->first();
        return $this->respondWithSuccess([
            "message" => "Product Has Been Updated",
            "data" => $result
        ]);
    }

    public function delete($id)
    {
        $user = auth('sanctum')->user();

        if ($user->roles != ('seller' || 'admin')) {
            return $this->respondForbidden("Must Login Using Seller Account");
        }

        $product = Product::where('id', $id);
        $product->delete();
        $result = Product::onlyTrashed()->where('id', $id)->first();
        return $this->respondWithSuccess([
            "message" => "Product Has Been Deleted",
            "data" => $result
        ]);
    }

    public function restore($id)
    {
        $user = auth('sanctum')->user();

        if ($user->roles != ('seller' || 'admin')) {
            return $this->respondForbidden("Must Login Using Seller Account");
        }

        $product = Product::onlyTrashed()->where('id', $id);
        $product->restore();
        $result = Product::where('id', $id)->first();
        return $this->respondWithSuccess([
            "message" => "Product Has Been Restored",
            "data" => $result
        ]);
    }

    public function destroy($id)
    {
        $user = auth('sanctum')->user();

        if ($user->roles != ('seller' || 'admin')) {
            return $this->respondForbidden("Must Login Using Seller Account");
        }

        $product = Product::query()->where('id', $id);
        $productTrashed = Product::onlyTrashed()->where('id', $id);

        if ($product->first()) {
            $product->forceDelete();
        }

        if ($productTrashed->first()) {
            $productTrashed->forceDelete();
        }

        return $this->respondOk("Product Has Been Deleted Permanently");
    }
}
