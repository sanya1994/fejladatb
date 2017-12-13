<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
        <title><?php echo $site_title ?></title>
        <script src="https://code.jquery.com/jquery-1.10.2.js" nonce = <?php global $nonce; echo '"',$nonce,'"';?>></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" nonce = <?php global $nonce; echo '"',$nonce,'"';?>></script>
        <?php
        global $css_files,$css_inline,$js_files,$js_inline, $project_dir, $project_path;
        foreach($js_files as $js){
            if(file_exists($project_dir.$js)){
                ?><script <?php echo 'src="',$project_path.$js,'" nonce="',$nonce,'"';?>></script><?php
            }
        }
        foreach($js_inline as $js){
            ?><script <?php echo 'nonce="',$nonce,'"'?>>
                <?php echo $js;?>
            </script><?php
        }
        foreach($css_files as $css){
            if(file_exists($project_dir.$css)){
                ?><link rel="stylesheet" type="text/css" href=<?php echo '"',$project_path.$css,'"';?>><?php
            }
        }
        ?>
        <style>
            <?php
            foreach($css_inline as $css){
                echo $css, '
';
            }
            ?>
        </style>
        <link rel="shortcut icon" href=<?php global $image_path; echo $image_path.'/icon.png';?>>
    </head>
    <body>
        <div id="topmenu">UDFKJ üzletlánc</div>
        <div id="topmobilmenu"><?php echo $site_title ?></div>
        <div id="site">
            <div id="leftmenu">
                <div class="PageTitle">UDFKJ üzletlánc</div>
                <?php
                $menus = array(''=>'Főoldal','search' => 'Keresés','stats'=>'Statisztika');
                foreach($menus as $menu => $alias){
                    if(existSite($menu)){
                        echo '<div class="menu"><a href="',$project_path,'/',$menu,'">',$alias,'</a></div>';
                    }
                }
                ?>
            </div>
            <div id="centersite">
                <h1 id ="siteTitle"><?php echo $site_title ?></h1>
                <div id="contentBox">
                    <div id="messageBox">
                        <?php
                        global $messages;
                        foreach($messages as $type => &$msgs){
                            if(count($msgs)>0){
                            ?>
                        <div class=<?php echo '"',$type,' message"'?>>
                            <ul>
                            <?php
                                foreach($msgs as $key => $msg){
                                    echo '<li>',$msg,'</li>';
                                    unset($msgs[$key]);
                                }
                            ?>
                            </ul>
                        </div>
                            <?php
                            }
                        }
                        ?>
                    </div>
                    <div id="content">
                        <?php
                        global $content;
                        echo $content;
                        ?>
                    </div>
                </div>
            </div>
            <div id="rightmenu"></div>
        </div>
    </body>
</html>
    
