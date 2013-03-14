<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

  //reason to visit agent
            $reason1_text = get_post_meta(get_the_ID(), 'YOUR_PREFIX_text1', true);
            $reason2_text = get_post_meta(get_the_ID(), 'YOUR_PREFIX_text2', true);
            $reason3_text = get_post_meta(get_the_ID(), 'YOUR_PREFIX_text3', true);
            $reason4_text = get_post_meta(get_the_ID(), 'YOUR_PREFIX_text4', true);
            $reason5_text = get_post_meta(get_the_ID(), 'YOUR_PREFIX_text5', true);


           // vdump($reason1_text);
            
            echo "<div style='margin-bottom:10px;'>";
            echo "<div><b>Reason to visit Travel Agents</b></div>";
            foreach ($reason1_text as $key => $value) {
                 echo "<div>$value</div>";
            }
          
            echo "<div>$reason2_text</div>";
            echo "<div>$reason3_text</div>";
            echo "<div>$reason4_text</div>";
            echo "<div>$reason5_text</div>";
            echo "</div>";
            //reason to visit agent ends here.
