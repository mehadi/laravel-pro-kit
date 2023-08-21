<!-- resources/views/components/post/card.blade.php -->
<div
        class="post-card flex p-4 border rounded shadow bg-white hover:shadow-lg transition-shadow duration-300"
        x-ref="postCard{{ $post->id }}">
    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
         class="w-16 h-16 object-cover rounded-full mr-6 self-center"
         x-ref="postCardImage{{ $post->id }}"
    >
    <div class="flex flex-col justify-center">
        <h3 class="text-2xl font-bold mb-3"
            x-ref="postCardTitle{{ $post->id }}">
            {{ $post->title }}
        </h3>
        <button @click="openModal({{ $post->id }})"
                class="mt-3 text-blue-500 hover:underline cursor-pointer">View Post
        </button>
    </div>
</div>
