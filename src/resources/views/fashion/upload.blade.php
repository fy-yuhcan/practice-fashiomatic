@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6">ファッションコーディネート</h1>
    <form method="POST" action="{{ route('fashion.upload') }}" enctype="multipart/form-data" id="upload-form">
        @csrf
        <div class="mb-4">
            <label for="upper" class="block text-gray-700">上の服の画像をアップロード</label>
            <input type="file" id="upper" name="upper[]" multiple class="mt-1 block w-full" required>
            <div id="upper-preview" class="mt-2 flex flex-wrap"></div>
        </div>
        <div class="mb-4">
            <label for="lower" class="block text-gray-700">下の服の画像をアップロード</label>
            <input type="file" id="lower" name="lower[]" multiple class="mt-1 block w-full" required>
            <div id="lower-preview" class="mt-2 flex flex-wrap"></div>
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">アップロード</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('upper').addEventListener('change', function(event) {
    appendFiles(event.target.files, 'upper-preview');
});

document.getElementById('lower').addEventListener('change', function(event) {
    appendFiles(event.target.files, 'lower-preview');
});

function appendFiles(files, previewElementId) {
    var previewElement = document.getElementById(previewElementId);
    var maxFileSize = 2 * 1024 * 1024; // 2MBの制限

    for (var i = 0; i < files.length; i++) {
        var file = files[i];

        if (file.size > maxFileSize) {
            alert("ファイルサイズが大きすぎます: " + file.name);
            continue;
        }

        var reader = new FileReader();

        reader.onload = (function(previewElement) {
            return function(e) {
                var imgContainer = document.createElement('div');
                imgContainer.classList.add('relative', 'inline-block', 'mr-2', 'mb-2');

                var img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('w-32', 'h-32', 'object-cover', 'border', 'border-gray-300');
                
                var removeButton = document.createElement('button');
                removeButton.innerText = '×';
                removeButton.classList.add('absolute', 'top-0', 'right-0', 'bg-red-500', 'text-white', 'rounded-full', 'w-6', 'h-6', 'flex', 'items-center', 'justify-center');
                removeButton.addEventListener('click', function() {
                    previewElement.removeChild(imgContainer);
                });

                imgContainer.appendChild(img);
                imgContainer.appendChild(removeButton);
                previewElement.appendChild(imgContainer);
            };
        })(previewElement);

        reader.onerror = function() {
            alert("ファイルの読み込みに失敗しました: " + file.name);
        };

        reader.readAsDataURL(file);
    }
}
</script>

@endsection


