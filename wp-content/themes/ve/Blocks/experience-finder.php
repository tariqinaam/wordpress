<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
 $post_type_name = 'experience';
 $tax_terms = get_terms('demography');?>
  <div><form action="/experiences">
                <select name="flingmatic" id="flingmatic-select" class="flingmatic-select" style="float: left" >
                    <?php
                    $selected = "";
                    $value = "selected = $tax";
                    if ($tax_terms) {

                        foreach ($tax_terms as $tax_terms) {
                            //echo $tax_terms->slug;
                            ?>
                            <?php
                            if ($tax_terms->slug == $tax) {
                                $selected = $value;
                                ?>
                                <option value="<?php echo $tax_terms->slug ?>" <?php echo $selected; ?>><?php echo $tax_terms->name ?></option>
                            <?php } else {
                                ?>
                                <option value="<?php echo $tax_terms->slug ?>"><?php echo $tax_terms->name ?></option>   
                                <?php
                            }
                        }
                    }
                    ?>


                </select>
                <div class="flingmatic-end">
                    <input type="submit" value="Generate" id="flingmatic-button" class="flingmatic-button button ctaButton">
                </div>
            </form>
        </div>