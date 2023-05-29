
function adjustImageSize($imagePath, $containerWidth, $containerHeight)
    {
        list($imageWidth, $imageHeight) = getimagesize($imagePath);

        if ($imageWidth > $containerWidth || $imageHeight > $containerHeight) {
            $containerAspectRatio = $containerWidth / $containerHeight;
            $imageAspectRatio = $imageWidth / $imageHeight;

            if ($imageAspectRatio > $containerAspectRatio) {
                $width = '100%';
                $height = 'auto';
            } else {
                $width = 'auto';
                $height = '100%';
            }
        } else {
            $width = 'auto';
            $height = 'auto';
        }

        return array($width, $height);
    }