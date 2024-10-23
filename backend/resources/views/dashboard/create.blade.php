<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Create Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">
    <h2 class="text-2xl font-bold my-6">Create Product</h2>
    <form id="product-form" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <div class="mb-4">
            <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
            <input type="text" id="product_name" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>
        <div class="mb-4">
            <label for="description1" class="block text-sm font-medium text-gray-700">Description 1</label>
            <textarea id="description1" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
        </div>
        <div class="mb-4">
            <label for="description2" class="block text-sm font-medium text-gray-700">Description 2</label>
            <textarea id="description2" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
        </div>
        <div class="mb-4">
            <label for="link" class="block text-sm font-medium text-gray-700">Link</label>
            <input type="url" id="link" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>
        <div class="mb-4">
            <label for="photo" class="block text-sm font-medium text-gray-700">Upload Image</label>
            <input type="file" id="photo" accept="image/*" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>
        <div class="mb-4">
            <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
            <input type="number" id="rating" min="0" max="5" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>
        <div class="mb-4">
            <label for="categories" class="block text-sm font-medium text-gray-700">Categories</label>
            <select id="categories" multiple class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
            <select id="tags" multiple class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Create Product</button>
    </form>

    <script>
        document.getElementById('product-form').addEventListener('submit', async function (event) {
            event.preventDefault(); // Prevent form submission

            const product_name = document.getElementById('product_name').value;
            const description1 = document.getElementById('description1').value;
            const description2 = document.getElementById('description2').value;
            const link = document.getElementById('link').value;
            const photoInput = document.getElementById('photo');
            const rating = document.getElementById('rating').value;

            // Get selected categories
            const categories = Array.from(document.getElementById('categories').selectedOptions).map(option => option.value);
            
            // Get selected tags
            const tags = Array.from(document.getElementById('tags').selectedOptions).map(option => option.value);

            // Upload image to Imgur
            if (photoInput.files.length === 0) {
                alert('Please upload an image.');
                return;
            }

            const formData = new FormData();
            formData.append('image', photoInput.files[0]);

            try {
                const imgurResponse = await fetch('https://api.imgur.com/3/image', {
                    method: 'POST',
                    headers: {
                        Authorization: 'Client-ID 1e98000d4f6d1a9', // Use the Imgur client ID from the environment
                    },
                    body: formData,
                });

                const imgurData = await imgurResponse.json();
                if (imgurData.success) {
                    const imageUrl = imgurData.data.link;

                    // Send product data to your API
                    const payload = {
                        product_name,
                        description1,
                        description2,
                        link,
                        photo: imageUrl,
                        rating,
                        categories, // Add categories to the payload
                        tags, // Add tags to the payload
                    };

                    const apiResponse = await fetch('http://127.0.0.1:8000/api/products', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });

                    const apiData = await apiResponse.json();
                    if (apiResponse.ok) {
                        alert('Product created successfully!');
                        window.location.href = '/dashboard'; 
                    } else {
                        alert(apiData.message || 'Failed to create product');
                    }
                } else {
                    alert('Failed to upload image to Imgur');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while processing your request.');
            }
        });
    </script>
</body>

</html>
