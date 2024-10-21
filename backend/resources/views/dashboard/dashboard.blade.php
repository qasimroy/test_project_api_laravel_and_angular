<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col items-auto justify-auto min-h-screen">
    <script>
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
        }
    </script>
    @include('navbar.navbar')
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-[100%] ">
        <h2 class="text-2xl font-bold text-start mb-6">Product Dashboard</h2>

        <div class="flex justify-end pb-2">
            <a href="{{ route('dashboard.create') }}" class="inline-block bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 transition duration-200" title="Create a new product">Create</a>
        </div>
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product
                            Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tags</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap">{{ $product->product_name }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">{{ $product->description1 }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @foreach ($product->categories as $category)
                                    <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @foreach ($product->tags as $tag)
                                    <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $tag->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <button onclick="deleteProduct(`{{ $product->id }}`)" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        async function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                try {
                    const response = await fetch(`/products/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', // Pass CSRF token
                            'Content-Type': 'application/json',
                        },
                    });

                    if (response.ok) {
                        alert('Product deleted successfully');
                        location.reload(); // Reload the page to refresh the product list
                    } else {
                        const data = await response.json();
                        alert(data.message || 'Failed to delete the product');
                    }
                } catch (error) {
                    alert('An error occurred while deleting the product');
                }
            }
        }

        async function logout() {
            localStorage.removeItem('token');
            
            alert('Logged out successfully!');
            
            window.location.href = '/login'; 
        }

    </script>

</body>

</html>
