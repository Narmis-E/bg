<?php

$site_title = "Wallpapers";
$site_desc = "by narmis";
$site_style = "style.css";
$img_folder = "img/walls/";
$allowed_types = ["gif","jpg","jpeg","png","webp"];
$github_base_url = "https://raw.githubusercontent.com/Narmis-E/bg/refs/heads/main/img/walls/";

function create_slug($string) {
  $string = strtolower($string);
  $string = strip_tags($string);
  $string = stripslashes($string);
  $string = html_entity_decode($string);
  $string = str_replace('\'', '', $string);
  $string = trim(preg_replace('/[^a-z0-9]+/', '-', $string), '-');
  return $string;
}

$dimg = opendir($img_folder);
$grid = '';
$lightbox = '';

while($img_file = readdir($dimg)) {
  if(in_array(strtolower(@end(explode('.',$img_file))),$allowed_types))
  {$a_img[] = $img_file;} 
}

if(is_array($a_img)) sort($a_img, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);

$totimg = count($a_img);

for($x = 0; $x < $totimg; $x++) {
  
  $file_name = pathinfo($a_img[$x], PATHINFO_FILENAME); 
  $file_slug = create_slug($file_name);
  $github_url = $github_base_url . rawurlencode($a_img[$x]);
 
  $size = getimagesize($img_folder.'/'.$a_img[$x]);
  $width = $size[0];
  $height = $size[1];
  $aspect = $height / $width;
  if ($aspect >= 1) $orientation = 'portrait';
  else $orientation = 'landscape';

  $grid .= '
  <figure class="'.$orientation.'">
    <a href="#'.$file_slug.'" id="'.$file_slug.'-thumb">
      <img class="open" loading="lazy" width="'.$width.'" height="'.$height.'" src="'.$img_folder.'/'.$a_img[$x].'" alt="'.$file_name.'">
    </a>
    <figcaption>'.$file_name.'</figcaption>
  </figure>
  ';
  
  $lightbox .= '
  <figure tabindex="0" id="'.$file_slug.'" class="'.$orientation.'">
    <a tabindex="-1" href="#'.$file_slug.'" class="image">
      <img loading="lazy" width="'.$width.'" height="'.$height.'" src="'.$img_folder.'/'.$a_img[$x].'" alt="'.$file_name.'">
    </a>
    <a tabindex="-1" href="#'.$file_slug.'-thumb" class="close">Close</a>
    <a tabindex="-1" href="'.$github_url.'" target="_blank" rel="noopener" class="download-source" title="Download image" download>⬇</a>
  </figure>
  ';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" type="image/x-icon" href="bg.ico">
  <meta name="viewport" content="width=device-width">     
  <title>Wallpapers by narmis</title>
  <meta name="description" content="<?php echo $site_desc; ?>">
  <meta property="og:title" content="<?php echo $site_title; ?>">
  <meta property="og:image" content="https://github.com/Narmis-E/bg/blob/main/ogimage.png">
  <meta property="og:description" content="<?php echo $site_desc; ?>">
  <meta name="twitter:card" content="summary">
  <style type="text/css">
    <?php echo file_get_contents($site_style); ?>
  </style>
</head>
<body>
  <div class="top-bar">
    <h1>wallpapers <span class="inline-logo">by  <a href="https://github.com/Narmis-E"><img style="height:1em;vertical-align:middle;border-radius:3px;" src="bg.ico" alt="narmis"></a></span></h1>
    <div class="size-controls">
      <input type="radio" name="size" id="x-large">
      <label for="x-large">XL</label>
      <input type="radio" name="size" id="large">
      <label for="large">L</label>
      <input checked="" type="radio" name="size" id="medium">
      <label for="medium">M</label>
      <input type="radio" name="size" id="small">
      <label for="small">S</label>
    </div>
  </div>
  <main>
    <div class="grid">
      <?php echo $grid; ?>
    </div>
    <div class="lightbox">
      <?php echo $lightbox; ?>   
      <div class="counter"></div>        
    </div> 
  </main>
  <footer>
    <a target="_blank" rel="noopener" href="https://github.com/Narmis-E/bg/tree/main/img/">Gallery</a> last updated: <?php echo date("F j, Y"); ?>
  </footer>
  <script>
  // Get all lightbox figures for navigation
  const lightboxFigures = Array.from(document.querySelectorAll('.lightbox figure'));
  
  function getCurrentImageIndex() {
    return lightboxFigures.findIndex(figure => 
      figure.id === window.location.hash.substring(1)
    );
  }
  
  function navigateToImage(index) {
    if (index >= 0 && index < lightboxFigures.length) {
      const targetFigure = lightboxFigures[index];
      window.location.hash = '#' + targetFigure.id;
      targetFigure.focus();
    }
  }
  
  // show lightbox
  document.addEventListener('click', function(event) {
    if (!event.target.matches('.open')) return;
    document.querySelector('body').classList.add('fixed')
  }, false);

  document.addEventListener(
    "keydown", (e) => {
      if (e.keyCode == 13) {
        document.activeElement.click();
        document.querySelector('body').classList.add('fixed')
      }
    }, false);

  // hide lightbox
  document.addEventListener('click', function(event) {
    if (!event.target.matches('.close')) return;
    document.querySelector('body').classList.remove('fixed')
  }, false);

  // Enhanced keyboard navigation
  document.addEventListener(
    "keydown", (e) => {
      const currentIndex = getCurrentImageIndex();
      
      // ESC key to close
      if (e.keyCode == 27) {
        const activeElement = document.activeElement;
        if (activeElement && activeElement.classList.contains('lightbox')) {
          activeElement.querySelector('.close').click();
        } else {
          document.querySelector('.lightbox figure:target .close')?.click();
        }
        document.querySelector('body').classList.remove('fixed');
      }
      
      // Only handle arrow keys and tab when in lightbox mode
      if (currentIndex === -1) return;
      
      // Left arrow or Shift+Tab - previous image
      if ((e.keyCode == 37) || (e.keyCode == 9 && e.shiftKey)) {
        e.preventDefault();
        const prevIndex = currentIndex > 0 ? currentIndex - 1 : lightboxFigures.length - 1;
        navigateToImage(prevIndex);
      }
      
      // Right arrow or Tab - next image
      else if ((e.keyCode == 39) || (e.keyCode == 9 && !e.shiftKey)) {
        e.preventDefault();
        const nextIndex = currentIndex < lightboxFigures.length - 1 ? currentIndex + 1 : 0;
        navigateToImage(nextIndex);
      }
    }, false);

  </script>
</body>
</html>
