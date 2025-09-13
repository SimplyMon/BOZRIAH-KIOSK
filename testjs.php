<!DOCTYPE html>
<html>

<head>
    <title>Load Image from Network Path</title>
</head>

<body>
    <div id="imageContainer">Loading image...</div>

    <script>
        var img = new Image();
        img.src = 'Images.php';
        img.alt = 'Network Image';

        img.onload = function() {
            var container = document.getElementById('imageContainer');
            container.innerHTML = '';
            container.appendChild(img);
        };

        img.onerror = function() {
            console.error('Failed to load image.');
            document.getElementById('   ').innerText = 'Failed to load image.';
        };
    </script>

    <script></script>
</body>

</html>