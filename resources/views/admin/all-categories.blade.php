@extends('layouts.admin')

@section('content')
    <div class="category-page">
        <div class="container">
            <h1 class="mb-4">All Categories</h1>

            <table class="table custom-table"> <!-- Removed table-striped -->
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Created At</th>
                        <th style="width: 20%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category['category_name'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($category['created_at'])->diffForHumans() }}</td>
                            <td>
                                <div class="action-buttons">
                                    <!-- Edit Button -->
                                    <button onclick="window.location.href='{{ route('admin.categories.edit', $category['id']) }}'" class="icon-btn edit-btn" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                            
                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.categories.destroy', $category['id']) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this category?')" class="icon-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
     <!-- SweetAlert2 Script -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     @if (session('success'))
         <script>
             Swal.fire({
                 icon: 'success',
                 title: 'Success!',
                 text: '{{ session('success') }}',
                 showConfirmButton: false,
                 timer: 3000
             });
         </script>
     @endif
 
     @if ($errors->any())
         <script>
             Swal.fire({
                 icon: 'error',
                 title: 'Oops! Something went wrong.',
                 html: `{!! implode('<br>', $errors->all()) !!}`,
             });
         </script>
     @endif
@endsection
