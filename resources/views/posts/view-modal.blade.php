<div x-show="isOpen" x-cloak class="fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-black bg-opacity-50 absolute inset-0" @click="closeModal"></div>
    <div class="bg-white rounded shadow-lg p-6 w-1/2 relative z-10">
        <h2 class="text-xl font-semibold mb-4">View Post</h2>

        <div x-show="isLoading">
            <!-- Loading animation here -->
            <div class="flex justify-center items-center">
                <div
                        class="animate-spin rounded-full h-12 w-12 border-t-2 border-blue-500 border-opacity-75"></div>
            </div>
        </div>

        <div x-show="postId !== null">
            <div class="flex justify-center items-center h-full">
                <img :src="shouldChangeImage ? newImagePreview : selectedPost.featured_image"
                     :alt="selectedPost.title" class="mb-3 rounded-lg shadow-lg max-h-72"
                     x-show="shouldChangeImage || selectedPost.featured_image"
                >
            </div>

            <h3 x-show="!isEditingTitle" @dblclick="startEditing('title')"
                class="text-xl font-semibold mb-4 cursor-pointer" x-text="selectedPost.title"></h3>
            <input x-show="isEditingTitle" x-model="selectedPost.title" @blur="stopEditing('title')"
                   @keydown.enter="stopEditing('title')" @keydown.escape="cancelEditing('title')"
                   class="text-xl font-semibold mb-4 w-full">

            <p x-show="!isEditingContent" @dblclick="startEditing('content')"
               class="text-gray-600 text-base leading-relaxed cursor-pointer" x-text="selectedPost.content"></p>
            <textarea x-show="isEditingContent" x-model="selectedPost.content" @blur="stopEditing('content')"
                      class="text-gray-600 text-base leading-relaxed w-full"></textarea>

            <div x-show="shouldChangeImage" @dragover.prevent="handleDragOver" @drop.prevent="handleDrop">
                <label class="block text-sm font-medium text-gray-700">Change Featured Image:</label>
                <div class="mt-1 border-2 border-dashed border-gray-300 rounded-md p-6 cursor-pointer">
                    <input type="file" @change="selectImage" class="hidden">
                    <div class="text-center relative">
                        <input type="file" @change="selectImage" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
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



            <div class="mt-3" x-show="!isLoading">
                <label class="inline-flex items-center">
                    <input type="checkbox" x-model="shouldChangeImage" class="form-checkbox">
                    <span class="ml-2">Change Image</span>
                </label>

                <button
                        x-show="shouldChangeImage"
                        @click="uploadImage" :disabled="!shouldChangeImage || !selectedImageFile"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Upload Image
                </button>
            </div>

            <button @click="closeModal" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Close
            </button>

            <button
                    x-show="!isLoading"
                    @click="deletePost(selectedPost.id)"
                    :disabled="isDeleting"
                    class="mt-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
            >
                <span x-show="!isDeleting">Delete</span>
                <span x-show="isDeleting">Deleting...</span>
            </button>

        </div>
        <div x-show="postId === null && !isLoading">
            No post selected.
        </div>
    </div>
</div>
