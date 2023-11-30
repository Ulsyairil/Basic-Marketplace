<?php

namespace App\Repositories\Product;

use LaravelEasyRepository\Repository;

interface ProductRepository extends Repository
{
    public function pagination($request);
    public function get($id);
    public function store($request);
    public function update($id, $request);
    public function delete($id);
    public function restore($id);
    public function destroy($id);
}
