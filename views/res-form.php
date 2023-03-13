<form method="GET" action="index.php/results">
    <div class="card">
        <div class="card-body">
            <div class="row col-md-12" style="margin: 0; padding: 0;">
                <div class="form_s d-flex" style="width: 100%;">
                    <select name="area" id="area-select">
                        <?php
                        if($_GET['area']){
                            echo '<option disabled selected hidden>'.$_GET['area'].'</option>';
                        }else{
                            echo '<option disabled selected hidden>Område</option>';
                        }
                        foreach($terms as $term){ ?>
                            <option value="<?php echo $term->slug ?>"><?php echo $term->name ?></option>
                        <?php } ?>
                    </select>
                    <input type="text" readonly name="date_start" id="date-3_1" class="form-control form-control-sm" value="<?php echo $_GET['date_start']; ?>" placeholder="Tillträde"/>
                    <input type="text" readonly name="date_end" id="date-3_2" class="form-control form-control-sm" value="<?php echo $_GET['date_end']; ?>"  placeholder="Avresa"/>
                    <select id="adult-select" name="adult" >
                        <?php
                        for ($i=1; $i<=12; $i++){
                            if ($i == $_GET['adult']){
                                echo '<option selected>'.$i.'</option>';
                            } else {
                                echo '<option>'.$i.'</option>';
                            }
                        }
                        ?>
                    </select>
                    <input type="submit" class="btn-sbm" value="Sök">
                </div>
                <div class="col-md-7" id="gist-3"></div>
            </div>

        </div>
    </div>
</form>