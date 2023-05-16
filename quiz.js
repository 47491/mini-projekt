window.addEventListener('DOMContentLoaded', function () {
    var bildContainer = document.getElementById('bild-container');
    var bild = document.getElementById('bild');
  
    // Adjust the size of the image to fit the container
    function adjustImageSize() {
      var containerWidth = bildContainer.offsetWidth;
      var containerHeight = bildContainer.offsetHeight;
      var imageWidth = bild.naturalWidth;
      var imageHeight = bild.naturalHeight;
  
      if (imageWidth > containerWidth || imageHeight > containerHeight) {
        var containerAspectRatio = containerWidth / containerHeight;
        var imageAspectRatio = imageWidth / imageHeight;
  
        if (imageAspectRatio > containerAspectRatio) {
          bild.style.width = '100%';
          bild.style.height = 'auto';
        } else {
          bild.style.width = 'auto';
          bild.style.height = '100%';
        }
      }
    }
  
    // Call the adjustImageSize function on window load and resize
    window.addEventListener('load', adjustImageSize);
    window.addEventListener('resize', adjustImageSize);
  });
  
  