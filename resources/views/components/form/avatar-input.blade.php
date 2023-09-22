@props(['name', 'labeltext' => $name, 'userid' => auth()->user()->id])
<div class="form-item-group">

    <div class="relative mx-auto max-w-fit">
        @php
            $user = \App\Models\User::find($userid);
            $avatar = 'default-avatar.png';
            if(isset($user)) {
                $avatar = $user->avatar;
            }
        @endphp
        <img
            src="{{ route('get-file', ['category' => 'avatars', 'filename' => $avatar])}}"

            class="h-20 w-20 cursor-pointer rounded-full bg-gray-300 object-cover" id="avatarImage">
        <i class="absolute right-0 bottom-0 text-gray-700 fa-solid fa-pen"></i>
    </div>


    <input type="file" name="{{$name}}" id="{{$name}}" class="hidden"
           onchange="previewAvatar(event)" {{$attributes(['value' => old($name)])}}>

    <x-form.error name="{{$name}}"/>
</div>

<script type="text/javascript">
    window.addEventListener('DOMContentLoaded', function () {
        document.getElementById('avatarImage').addEventListener('click', function () {
            document.getElementById('{{$name}}').click();
        });
    });

    function previewAvatar(event) {
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById('avatarImage').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
