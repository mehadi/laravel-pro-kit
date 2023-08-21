<!-- Add New Post Modal -->
<div x-show="isAdding" x-cloak class="fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-black bg-opacity-50 absolute inset-0" @click="closeAddModal"></div>
    <div class="bg-white rounded shadow-lg p-6 w-1/2 relative z-10">
        <h2 class="text-xl font-semibold mb-4">Add New Post</h2>

        <label class="block text-sm font-medium text-gray-700 mb-1">Title:</label>
        <input type="text" x-model="newPost.title" class="mb-4 p-2 border rounded w-full">

        <label class="block text-sm font-medium text-gray-700 mb-1">Content:</label>
        <textarea x-model="newPost.content" class="mb-4 p-2 border rounded w-full"></textarea>

        <div class="mb-4" @dragover.prevent="handleAddDragOver" @drop.prevent="handleAddDrop">
            <label class="block text-sm font-medium text-gray-700">Featured Image:</label>
            <div class="mt-1 border-2 border-dashed border-gray-300 rounded-md p-6 cursor-pointer">
                <input type="file" @change="selectAddImage" class="hidden">
                <div class="text-center relative">
                    <input type="file" @change="selectAddImage" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                        <path
                                d="M23.99 6.21L24 6l-.01.21A4 4 0 0 1 24 2a4 4 0 0 1 4 4 4 4 0 0 1-1.19 2.8A4 4 0 0 1 28 10h-4.01zM40 16v22a6 6 0 0 1-6 6H14a6 6 0 0 1-6-6V16h8.59a5.98 5.98 0 0 1 4.24 1.76l1.41 1.42a1 1 0 0 0 1.42 0L27.18 19a6 6 0 0 1 8.48 0l1.41 1.42a1 1 0 0 0 1.42 0L37.41 17H40zm-2 2.82l-8.48 8.48-1.41-1.42 8.48-8.48 1.41 1.42z"/>
                    </svg>
                    <p class="mt-1 text-sm text-gray-600">
                        Upload a file or drag and drop here
                    </p>
                </div>
            </div>
        </div>

        <!-- Display validation errors -->
        <div x-show="Object.keys(validationErrors).length > 0" class="text-red-500">
            <ul>
                <template x-for="(error, field) in validationErrors" :key="field">
                    <li x-text="error"></li>
                </template>
            </ul>
        </div>

        <button @click="addNewPost" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Add Post
        </button>

        <button @click="closeAddModal" class="mt-4 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
            Cancel
        </button>
    </div>
</div>
