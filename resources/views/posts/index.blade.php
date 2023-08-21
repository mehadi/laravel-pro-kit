<x-app-layout>
    <div x-data="modalData()">

        <!-- Floating button to open the modal -->
        <button @click="openAddModal()"
                class="fixed bottom-8 right-8 bg-blue-500 hover:bg-blue-600 text-white px-4 py-4 rounded-full">
            {{ __('Add new') }}
        </button>

        <x-slot name="header">
            <div class="flex justify-between w-full">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-grow">
                    {{ __('Posts') }}
                </h2>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="container mx-auto p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Loop through posts -->
                        @foreach ($posts as $post)
                           <x-post.card :post="$post"/>
                        @endforeach
                    </div>
                    <br>
                    {{ $posts->links() }}
                </div>
            </div>
        </div>

        <!--View Modal Start-->
        @include('posts.view-modal')
        <!--View Modal End-->

        <!-- Add New Post Modal -->
        @include('posts.add-modal')
    </div>
</x-app-layout>

<script>
    function modalData() {
        return {
            isOpen: false,
            postId: null,
            selectedPost: {},
            isLoading: false,
            openModal(postId) {
                this.postId = postId
                this.fetchPostDetails(postId)
                this.isOpen = true
            },
            closeModal() {
                this.isOpen = false
                this.resetEveything()
            },
            async fetchPostDetails(postId) {
                this.isLoading = true
                try {
                    const response = await fetch(`/posts/${postId}`)
                    const data = await response.json()
                    this.selectedPost = data
                    this.newImagePreview = this.selectedPost.featured_image
                } catch (error) {
                    console.error(error)
                    this.selectedPost = {}
                } finally {
                    this.isLoading = false
                }
            },
            isDeleting: false, // Add this property
            deletePost(postId) {
                if (confirm("Are you sure you want to delete this post?")) {
                    this.isLoading = true;
                    this.isDeleting = true;
                    fetch(`/posts/${postId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    }).then(response => response.json())
                        .then(data => {
                            this.isLoading = false;
                            this.isDeleting = false;
                            if (data.success) {
                                // Remove the deleted post from the UI
                                const postCard = this.$refs[`postCard${postId}`];
                                if (postCard) {
                                    postCard.remove();
                                }

                                this.closeModal();
                                alert("Post deleted successfully");
                            } else {
                                console.error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            this.isLoading = false;
                            this.isDeleting = false;
                        });
                }
            },

            isEditingTitle: false,
            isEditingContent: false,

            updateTitle() {
                if (this.selectedPost.title.trim() !== "") {
                    this.updateFieldOnServer(this.selectedPost.id, 'title', this.selectedPost.title);
                }
                this.isEditingTitle = false;
            },

            updateContent() {
                if (this.selectedPost.content.trim() !== "") {
                    this.updateFieldOnServer(this.selectedPost.id, 'content', this.selectedPost.content);
                }
                this.isEditingContent = false;
            },

            async updateFieldOnServer(postId, fieldName, newValue) {
                const updateData = {};
                updateData[fieldName] = newValue;

                try {
                    const response = await fetch(`/posts/${postId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(updateData)
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            // Update the corresponding field locally
                            this.selectedPost[fieldName] = newValue;
                            if (fieldName === 'title') {
                                this.$refs[`postCardTitle${postId}`].textContent = newValue;
                                console.log(`Updated title in post card`);
                            }
                        } else {
                            console.error(data.message);
                        }
                    } else {
                        console.error(`Failed to update ${fieldName} on the server.`);
                    }

                    console.log(response)
                } catch (error) {
                    console.error(error);
                }
            },

            startEditing(field) {
                if (field === 'title') {
                    this.isEditingTitle = true;
                } else if (field === 'content') {
                    this.isEditingContent = true;
                }
            },

            stopEditing(field) {
                if (field === 'title') {
                    if (this.selectedPost.title.trim() !== "") {
                        this.updateTitle()
                    }
                    this.isEditingTitle = false;
                } else if (field === 'content') {
                    if (this.selectedPost.content.trim() !== "") {
                        this.updateContent()
                    }
                    this.isEditingContent = false;
                }
            },

            cancelEditing(field) {
                if (field === 'title') {
                    this.selectedPost.title = this.originalTitle;
                    this.isEditingTitle = false;
                } else if (field === 'content') {
                    this.selectedPost.content = this.originalContent;
                    this.isEditingContent = false;
                }
            },

            resetEveything() {
                this.isEditingTitle = false
                this.isEditingContent = false

                this.isLoading = false
                this.postId = null
                this.selectedPost = {}

                //image update
                this.shouldChangeImage = false
            },

            //Image update
            newImagePreview: null,
            selectedImageFile: null,
            shouldChangeImage: false,

            selectImage(event) {
                const file = event.target.files[0];
                this.handleFile(file);
            },

            handleDragOver(event) {
                event.preventDefault();
            },

            handleDrop(event) {
                event.preventDefault();
                const file = event.dataTransfer.files[0];
                this.handleFile(file);
            },

            handleFile(file) {
                if (file) {
                    this.selectedImageFile = file;
                    this.newImagePreview = URL.createObjectURL(file);
                }
            },

            async uploadImage() {
                if (this.selectedImageFile) {
                    const formData = new FormData();
                    formData.append('featured_image', this.selectedImageFile);
                    formData.append('_method', 'PUT'); // Simulate PUT request

                    try {
                        const response = await fetch(`/posts/${this.postId}`, {
                            method: 'POST', // Use POST method
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                this.selectedPost.featured_image = data.data.featured_image;
                                this.newImagePreview = null;
                                this.shouldChangeImage = false;

                                // Update the post card image source
                                const postCardImage = this.$refs[`postCardImage${this.postId}`];
                                if (postCardImage) {
                                    postCardImage.src = data.data.featured_image;
                                }

                            } else {
                                console.error(data.message);
                            }
                            console.log(data)
                        } else {
                            console.error('Image upload failed');
                        }
                    } catch (error) {
                        console.error(error);
                    }
                }
            },


            // Add new post modal
            isAdding: false,
            newPost: {
                title: '',
                content: '',
                featured_image: null,
            },

            // property for validation errors
            validationErrors: {},

            openAddModal() {
                this.isAdding = true;
            },

            closeAddModal() {
                this.isAdding = false;
                this.resetNewPost();
            },

            selectAddImage(event) {
                const file = event.target.files[0];
                this.handleAddFile(file);
            },

            handleAddDragOver(event) {
                event.preventDefault();
            },

            handleAddDrop(event) {
                event.preventDefault();
                const file = event.dataTransfer.files[0];
                this.handleAddFile(file);
            },

            handleAddFile(file) {
                if (file) {
                    this.selectedImageFile = file;
                    this.newPost.featured_image = file;
                }
            },

            async addNewPost() {
                // Clear previous validation errors
                this.validationErrors = {};

                // Validate form fields
                if (!this.newPost.title.trim()) {
                    this.validationErrors.title = 'Title is required';
                }

                if (!this.newPost.content.trim()) {
                    this.validationErrors.content = 'Content is required';
                }

                if (!this.selectedImageFile) {
                    this.validationErrors.featured_image = 'Featured image is required';
                }

                // If there are validation errors, don't proceed
                if (Object.keys(this.validationErrors).length > 0) {
                    return;
                }

                const formData = new FormData();
                formData.append('title', this.newPost.title);
                formData.append('content', this.newPost.content);
                formData.append('featured_image', this.selectedImageFile);


                try {
                    const response = await fetch('/posts', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    });

                    const responseData = await response.json();

                    if (response.ok && responseData.success) {

                        location.reload();
                        // Post added successfully, reset form and close modal
                        this.resetNewPost()
                        this.closeModal();
                        //alert("Post added successfully");
                    } else if (response.status === 422) {
                        // Validation errors
                        this.validationErrors = responseData.errors;
                        console.error(responseData.message);
                    } else {
                        console.error(responseData.message);
                    }
                } catch (error) {
                    console.error(error);
                }
            },

            resetNewPost() {
                this.newPost.title = '';
                this.newPost.content = '';
                this.newPost.featured_image = null;

            },

        };
    }
</script>
