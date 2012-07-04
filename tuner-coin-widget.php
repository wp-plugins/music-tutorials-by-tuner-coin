<?php
/*
Plugin Name: Music Tutorials By Tuner Coin
Plugin URI: http://www.tuner.co.in/goodies/wordpress-plugin/
Description: The Music Tutorials By Tuner Coin Plugin simply adds a customizable widget which displays the latest music Tutorials.
Version: 1.0
Author: Sandeep Tripathy
Author URI: http://www.stven.net
License: GPL2
*/

function MusicNews()
{
  $options = get_option("widget_MusicNews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Music News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed 
  $rss = simplexml_load_file( 
  'http://www.tuner.co.in/blog/feed'); 
  ?> 
  
  <ul> 
  
  <?php 
  // max number of news slots, with 0 (zero) all display
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary,
  $max_length = $options['chars'];
  
  // RSS Elements 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_MusicNews($args)
{
  extract($args);
  
  $options = get_option("widget_MusicNews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Music News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  MusicNews();
  echo $after_widget;
}

function MusicNews_control()
{
  $options = get_option("widget_MusicNews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Music News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['MusicNews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['MusicNews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['MusicNews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['MusicNews-CharCount']);
    update_option("widget_MusicNews", $options);
  }
?> 
  <p>
    <label for="MusicNews-WidgetTitle">Widget Title: </label>
    <input type="text" id="MusicNews-WidgetTitle" name="MusicNews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="MusicNews-NewsCount">Max. News: </label>
    <input type="text" id="MusicNews-NewsCount" name="MusicNews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="MusicNews-CharCount">Max. Characters: </label>
    <input type="text" id="MusicNews-CharCount" name="MusicNews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="MusicNews-Submit"  name="MusicNews-Submit" value="1" />
  </p>
  
<?php
}

function MusicNews_init()
{
  register_sidebar_widget(__('Music Tutorials'), 'widget_MusicNews');    
  register_widget_control('Music Tutorials', 'MusicNews_control', 300, 200);
}
add_action("plugins_loaded", "MusicNews_init");
?>
