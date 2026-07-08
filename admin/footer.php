</main>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

<script src="../assets/js/news.js"></script>
<script src="../assets/js/image-manager.js"></script>

<script src="https://cdn.tiny.cloud/1/<?php echo TINYMCE_API_KEY; ?>/tinymce/7/tinymce.min.js"></script>

<script>

tinymce.init({

    selector:'textarea[name=content]',

    height:600,

    menubar:false,

    branding:false,

    plugins:[
        'lists',
        'link',
        'image',
        'table',
        'code',
        'fullscreen',
        'media',
        'wordcount',
        'autosave'
    ],

    toolbar:
    'undo redo | styles | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | image media table | link | fullscreen | code',

    content_style:
    'body{font-family:Arial;font-size:17px;line-height:1.8;}'

});

</script>

</body>
</html>