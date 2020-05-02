@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-2">
            <div class="">
                <h4>Select Your Product</h4>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('product.index') }}" class="mt-4">
        @csrf
        <div class="form-group row">
            <label for="productName" class="col-md-2 col-form-label">Product Name</label>
            <div class="col-md-6">
                <select name="productNameId" id="productNameId" class="form-control dropdown-style input-field input-normal">
                    @foreach ($justProductNames as $productName)
                        <option value="{{ $productName->id}}" {{ old($productName->id) }} >{{ $productName->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="lineName" class="col-md-2 col-form-label">Department Name</label>
            <div class="col-md-6">
                <select name="lineId" id="lineId" class="form-control dropdown-style input-field input-normal">
                    @foreach ($justDepartments as $department)
                        <option value="{{ $department->id}}" >{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row mt-5">
            <div class="col-md-6 offset-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    Click
                </button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-6 offset-md-2">
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
