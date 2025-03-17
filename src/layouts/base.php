<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
    
        if(isset($content['title'])) echo '<title>' . $content["title"] . '</title>';
        if(isset($content['head'])) include_once $content['head'];
        if(isset($static['css'])){
            foreach($static['css'] as $css){
                echo "<link rel='stylesheet' href='$css' />";


            }
        }
    ?>

</head>
<body>
    <?php 


        if(isset($content['body'])) include_once $content['body'];

        if(isset($static['js'])){
            //load global dependencies
            $dependencies = [
                'js/jquery.min.js'
            ];

            foreach($dependencies as $js){
                echo "<script src='" . $js . "'></script>";
            }

            foreach($static['js'] as $js){
                echo "<script src='" . $js . "'></script>";
            }
        }

    ?>
</body>
</html>