@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Danh mục sản phẩm</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">+ Thêm danh mục</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Slug</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <tr>
                <td>{{ $cat->id }}</td>
                <td>{{ $cat->name }}</td>
                <td>{{ $cat->slug }}</td>
                <td>{{ $cat->description }}</td>
                <td>
                    <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Xóa danh mục này?')" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
