<?php

Class Helper {

  public static function preserve_input($name) {
    if ($val = Input::get($name)) {
      return "<input type='hidden' name='$name' value='$val' />";
    } else {
      return "";
    }
  }

  public static function url_with_query_and_sort_params($url) {
    $params = $_GET;
    unset($params["page"]);
    return $url . "?" . e(http_build_query($params));
  }

  public static function current_url_without_sort_params() {
    $params = $_GET;

    unset($params["page"]);
    unset($params["sort"]);
    unset($params["order"]);

    return URL::current() . "?" . e(http_build_query($params));
  }

  public static function current_url_without_search_params() {
    $params = $_GET;

    unset($params["q"]);

    return URL::current() . "?" . e(http_build_query($params));
  }

  public static function current_sort_link($sort, $title, $default_order = false) {
    $return_str = "<a href='".self::current_sort_url($sort, $default_order)."'>$title</a>";

    if ($sort == Input::get('sort')) {
      $return_str .= " " . (Input::get('order') == 'desc' ? "<i class='icon-chevron-down'></i>" : "<i class='icon-chevron-up'></i>");
    }

    return $return_str;
  }

  public static function current_sort_url($sort, $default_order = false) {

    $params = array('sort' => $sort);

    $params = array_merge($_GET, $params);

    if ($sort == Input::get('sort')) {
      $params["order"] = (Input::get('order') == 'desc') ? 'asc' : 'desc';
    } elseif ($default_order) {
      $params["order"] = $default_order;
    } else {
      unset($params["order"]);
    }

    unset($params["page"]);

    return URL::current() . "?" . e(http_build_query($params));
  }

  public static function current_url_with_params($parameters) {
    return URL::current() . "?" . e(http_build_query(array_merge($_GET, $parameters)));
  }

  public static function models_to_array($models)
  {
    if ($models instanceof Laravel\Database\Eloquent\Model)
    {
      return json_encode($models->to_array());
    }

    return array_map(function($m) { return $m->to_array(); }, $models);
  }

  public static function asset($n) {

    if (preg_match('/^css/', $n)) {
      $ext = Config::get('assets.use_minified') === false ? ".css" : ".min.css?t=".Config::get('deploy_timestamp');
      return HTML::style($n.$ext);
    } elseif (preg_match('/^js/', $n)) {
      $ext = Config::get('assets.use_minified') === false ? ".js" : ".min.js?t=".Config::get('deploy_timestamp');
      return HTML::script($n.$ext);
    } else {
      throw new \Exception("Can't handle that asset type.");
    }
  }

  public static function timeago($timestamp) {
    $str = strtotime($timestamp);
    return "<span class='timeago' title='".date('c', $str)."'>".date('r', $str)."</abbr>";
  }

  public static function helper_tooltip($title, $placement = "top", $pull_right = false, $no_margin = false) {
    return "<span class='helper-tooltip ".($pull_right ? 'pull-right' : '')."' " .($no_margin ? 'style="margin:0;"' : ''). " data-title=\"".htmlspecialchars($title)."\" data-trigger='manual' data-placement='$placement'>
        <i class='icon-question-sign icon-white'></i>
      </span>";
  }

  public static function datum($label, $content, $link = false) {
    if ($content) {
      $isEmail = filter_var($content, FILTER_VALIDATE_EMAIL);
      return "<div class='datum'>
                <label>$label</label>
                <div class='content'>".($link ? "<a href='".($isEmail ? "mailto:$content" : $content).
                  "' ".($isEmail ? '' : 'target="_blank"').">" : "")."$content".($link ? '</a>' : '')."</div>
              </div>";
    } else {
      return '';
    }
  }

  public static function flash_errors($errors) {
    if (!is_array($errors)) $errors = array($errors);

    if (Session::has('errors')) {
      Session::flash('errors', array_merge(Session::get('errors'), $errors));
    } else {
      Session::flash('errors', $errors);
    }
  }

  public static function active_nav($section) {
    return (Section::yield('active_nav') == $section) ? true : false;
  }

  public static function active_subnav($section) {
    return (Section::yield('active_subnav') == $section) ? true : false;
  }

  public static function active_sidebar($section) {
    return (Section::yield('active_sidebar') == $section) ? true : false;
  }

  public static function truncate($phrase, $max_words) {
    $phrase_array = explode(' ',$phrase);
    if(count($phrase_array) > $max_words && $max_words > 0) $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
    return $phrase;
  }

  public static function full_title($title = "", $action = "") {
    if ($title == "") {
      return __('r.app_name');
    } elseif ($action == "") {
      return "$title | " . __('r.app_name');
    } else {
      return "$action | $title | " . __('r.app_name');
    }
  }

  public static function all_us_states() {
    return array('AL'=>"Alabama",
                'AK'=>"Alaska",
                'AZ'=>"Arizona",
                'AR'=>"Arkansas",
                'CA'=>"California",
                'CO'=>"Colorado",
                'CT'=>"Connecticut",
                'DE'=>"Delaware",
                'DC'=>"District of Columbia",
                'FL'=>"Florida",
                'GA'=>"Georgia",
                'HI'=>"Hawaii",
                'ID'=>"Idaho",
                'IL'=>"Illinois",
                'IN'=>"Indiana",
                'IA'=>"Iowa",
                'KS'=>"Kansas",
                'KY'=>"Kentucky",
                'LA'=>"Louisiana",
                'ME'=>"Maine",
                'MD'=>"Maryland",
                'MA'=>"Massachusetts",
                'MI'=>"Michigan",
                'MN'=>"Minnesota",
                'MS'=>"Mississippi",
                'MO'=>"Missouri",
                'MT'=>"Montana",
                'NE'=>"Nebraska",
                'NV'=>"Nevada",
                'NH'=>"New Hampshire",
                'NJ'=>"New Jersey",
                'NM'=>"New Mexico",
                'NY'=>"New York",
                'NC'=>"North Carolina",
                'ND'=>"North Dakota",
                'OH'=>"Ohio",
                'OK'=>"Oklahoma",
                'OR'=>"Oregon",
                'PA'=>"Pennsylvania",
                'RI'=>"Rhode Island",
                'SC'=>"South Carolina",
                'SD'=>"South Dakota",
                'TN'=>"Tennessee",
                'TX'=>"Texas",
                'UT'=>"Utah",
                'VT'=>"Vermont",
                'VA'=>"Virginia",
                'WA'=>"Washington",
                'WV'=>"West Virginia",
                'WI'=>"Wisconsin",
                'WY'=>"Wyoming");
  }
}
