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

    <div class='popMessages'>
        <?php
            $msg = popMessage();

            while($msg != null){
                $colors = [
                    'success' => '#00ff00',
                    'warning' => '#ffff00',
                    'error' => '#ff0000',

                ];

                echo '<div class="popMsg flex justify-center align-center" style="color: ' . $colors[$msg[1]] . ';">' . $msg[0] .'</div>';
                $msg = popMessage();
            }
        ?>
    </div>

    <script>

            $(document).ready(function() {
            let count = $('.popMsg').length;

            $('.popMsg').each(function(index) {
                // Each popup fades out one after another, e.g., 3s apart
                $(this).delay(2000 * (count - index+1)).fadeOut(400, function() {
                    $(this).remove();
                });
            });
            });
    </script>
</body>
</html>