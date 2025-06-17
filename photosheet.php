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
  $github_url = $github_base_url . urlencode($a_img[$x]);
  
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
    <a tabindex="-1" href="'.$github_url.'" target="_blank" rel="noopener" class="github-source" title="View source on GitHub">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"/>
      </svg>
    </a>
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
    
    /* GitHub source button styles */
    .github-source {
      position: fixed;
      top: 60px;
      right: 20px;
      width: 40px;
      height: 40px;
      background: rgba(0, 0, 0, 0.8);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-decoration: none;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      transition: all 0.3s ease;
      z-index: 1001;
      opacity: 0;
      transform: scale(0.8);
    }
    
    .lightbox figure:target .github-source {
      opacity: 1;
      transform: scale(1);
    }
    
    .github-source:hover {
      background: rgba(0, 0, 0, 0.9);
      transform: scale(1.1);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    
    .github-source svg {
      width: 18px;
      height: 18px;
    }
    
    /* Adjust for mobile */
    @media (max-width: 768px) {
      .github-source {
        top: 70px;
        right: 15px;
        width: 36px;
        height: 36px;
      }
      
      .github-source svg {
        width: 16px;
        height: 16px;
      }
    }
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

  // esc key to close
  document.addEventListener(
    "keydown", (e) => {
      if (e.keyCode == 27) {
        document.activeElement.querySelector('.close').click();
        document.querySelector('body').classList.remove('fixed');
      }
    }, false);

  </script>
</body>
</html>
