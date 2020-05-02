@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-2">
            <div class="">
                <h4>Create New Order</h4>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('make.order') }}" class="mt-4">
        @csrf
        <div class="form-group row">
            <label for="productName" class="col-md-2 col-form-label">Product Name</label>
            <div class="col-md-4">
                <select name="productNameId" id="productNameId" class="form-control dropdown-style input-field input-normal">
                    @foreach ($justProductNames as $productName)
                        <option value="{{ $productName->id}}" >{{ $productName->name }}</option>
                    @endforeach
                </select>
            </div>
            <label for="lineName" class="col-md-2 col-form-label">Line Name</label>
            <div class="col-md-2">
                <select name="lineId" id="lineId" class="form-control dropdown-style input-field input-normal">
                    @foreach ($justDepartments as $department)
                        <option value="{{ $department->id}}" >{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="shiftWork" class="col-md-2 col-form-label">Shift Work</label>
            <div class="col-md-2">
                <select name="shiftId" id="shiftId" class="form-control dropdown-style input-field input-normal">
                    @foreach ($justShifts as $shift)
                        <option value="{{ $shift->id}}" >{{ $shift->name }}</option>
                    @endforeach
                </select>
            </div>
            <label for="shiftWork" class="col-md-2 col-form-label">Order Number</label>
            <div class="col-md-3">
                <input type="text" name="orderNumber" id="orderNumber" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-1">
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
    <div class="row">
        <div class="col-md-6 offset-md-2">
            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
