<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Repositories\Product\ProductRepositoryImplement;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductRepositoryImplement $productRepositoryImplement;

    public function __construct(ProductRepositoryImplement $productRepositoryImplement)
    {
        $this->productRepositoryImplement = $productRepositoryImplement;
    }

    public function paginate(ProductRequest $productRequest)
    {
        return $this->productRepositoryImplement->pagination($productRequest);
    }

    public function get(ProductRequest $productRequest)
    {
        return $this->productRepositoryImplement->get($productRequest->id);
    }

    public function store(ProductRequest $productRequest)
    {
        return $this->productRepositoryImplement->store($productRequest);
    }

    public function update(ProductRequest $productRequest)
    {
        $id = $productRequest->id;
        $productRequest->except('id');
        $productRequest->except('_method');
        return $this->productRepositoryImplement->update($id, $productRequest);
    }

    public function delete(ProductRequest $productRequest)
    {
        return $this->productRepositoryImplement->delete($productRequest->id);
    }

    public function restore(ProductRequest $productRequest)
    {
        return $this->productRepositoryImplement->restore($productRequest->id);
    }

    public function destroy(ProductRequest $productRequest)
    {
        return $this->productRepositoryImplement->destroy($productRequest->id);
    }
}
