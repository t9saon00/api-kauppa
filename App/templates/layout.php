<!doctype html>
<html>

<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link rel="stylesheet" href="<?php echo APP_BASEPATH . "/public/css/style.css"; ?>">

  <title>Api - koulu</title>
</head>

<body>

  <div class="topnav">
    <?php
    $menu = get_menu();

    foreach ($menu as $nav) {
      if (isset($nav["show_in_menu"]) && $nav["show_in_menu"] == true) { ?>
        <a href="<?php echo APP_BASEPATH . $nav["path"] ?>"><?php echo $nav["name"] ?></a>
    <?php }
    
    }; 
    ?> <a id="logout" href="<?php echo APP_BASEPATH . '/logout'; ?>">Log out</a>

    <?php
    $ajax_obj_array = array(
      'ajaxUrl' => APP_BASEPATH
    );
    ?>

    <?php echo $this->fetch('./template-parts' . $args["view_args"]["template_part"], $args); ?>
    <script>var ajaxObject = <?php echo json_encode($ajax_obj_array); ?>; </script>
    <script type="text/javascript" src=<?php echo APP_BASEPATH . "/public/js/js.js" ?>></script>
</body>

</html>