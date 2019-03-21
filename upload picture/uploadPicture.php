<?php
/**
 * @author     Mostafa Aboutalebi <m.abotalebie@gmail.com>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://fararayanesh.ir
*/

class uploadPicture
{
  protected $file;
  protected $name;
  protected $thumb_w = 600;
  protected $thumb_h = 400;
  protected $quality = 90;
  protected $max_size = 10485760;
  protected $allowed = ["jpeg","jpg","png"];
  protected $thumb_path;
  protected $origin_path;
  protected $origin_width;
  protected $origin_height;

  public function __construct($file, $origin_path, $thumb_path = null, $allowed = null)
  {
    $this->file = $file;
    $this->origin_path = $origin_path;
    if ($thumb_path)
      $this->thumb_path = $thumb_path;
    if ($allowed)
      $this->allowed = $allowed;
  }

  public function saveOrigin()
  {
    $name = explode('.', $this->file['name']);
    $file_ext = strtolower(end($name));
    $this->name = uniqid() . '.' . $file_ext;

    if ($this->file['error']) {
      echo $this->file['error'];
      return false;
    }

    if(in_array($file_ext, $this->allowed)=== false) {
      echo 'extension not allowed.';
      return false;
    }

    if($this->file['size'] > $this->max_size){
      echo 'File size must be excately' . $this->max_size / 10485760 . 'MB';
      return false;
    }

    if (!move_uploaded_file($this->file['tmp_name'], $this->origin_path . $this->name)) {
      echo 'save file error!';
      return false;
    }

    return true;
  }

  public function createThumb()
  {
    $thumb = $this->thumb_path . explode('.', $this->name)[0] . '.jpg';

    list($src_w, $src_h) = getimagesize($this->origin_path . $this->name);

    $dst_x = $dst_y = 0;
    $w = $src_w / $this->thumb_w;
    $h = $src_h / $this->thumb_h;
    $scale = $w < $h ? $w : $h;

    $dst_w = $src_w / $scale;
    $dst_h = $src_h / $scale;

    if ($dst_w > $this->thumb_w)
      $dst_x = ($this->thumb_w - $dst_w) / 2;

    if ($dst_h > $this->thumb_h)
      $dst_y = ($this->thumb_h - $dst_h) / 2;

    $dst_image = imagecreatetruecolor($this->thumb_w, $this->thumb_h);
    $src_image = imagecreatefromjpeg($this->origin_path . $this->name);

    imagecopyresampled(
      $dst_image, $src_image,
      $dst_x, $dst_y,0,0,
      $dst_w, $dst_h, $src_w, $src_h
    );

    $save = imagejpeg($dst_image, $thumb, $this->quality);
    imagedestroy($src_image);
    imagedestroy($dst_image);

    return $save;
  }
}