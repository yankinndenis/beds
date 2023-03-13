<div id="beds_filter" class="filter-wrap" style="border: rgba(0,0,0,.125) 1px solid; margin: 5px 0;">
    <div class="filter-btn">
        <div class="col-md-12" style="padding: 0;">
            <div class="col-md-3" style="padding: 7.5px;">
                <button class="btn" style="padding: 13.5px;font-size: 18px;width: 100%; color: white; background-color: black;" id="btn-filter">FILTER</button>
            </div>
        </div>
        <div class="col-md-12 row" id="filters-body" style="text-align: center; display: none; padding: 0;margin: 0;">
            <div class="range-block-filters">
                <div class="range-wrap">
                    <div class="range-wrap-label" style="font-weight: bold">
                        <label for="">Sovrum</label>
                    </div>
                    <form class="range-wrap-input" style="padding: 0" oninput="result.value=parseInt(bedrooms.value)">
                        <div style="width: 85%;">
                            <input id="sovrum" type="range" class="range-filter" name="bedrooms" max="8" step="1" min="1" value="4">
                        </div>
                        <div style="width: 15%; text-align: right;">
                            <output name="result">4</output>
                        </div>
                    </form>
                </div>
                <div class="range-wrap">
                    <div class="range-wrap-label" style="font-weight: bold">
                        <label for="">Avstånd till lift</label>
                    </div>
                    <form class="range-wrap-input" style="padding: 0" oninput="result.value=parseInt(ski.value)">
                        <div style="width: 85%;">
                            <input id="skidlift" type="range" class="range-filter" name="ski" max="2500" step="100" min="100" value="1300">
                        </div>
                        <div style="width: 15%; text-align: right;">
                            <output name="result">1300</output>
                        </div>
                    </form>

                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-3" style="font-weight: bold; text-align: left; text-transform: uppercase">
                    <label >Egenskaper</label>
                </div>
            </div>
            <div class="filter-blocks top_blocks">
                <div class="filter-case" >
                    <div data-item="_product_hundtillåtet" class="col-md-12 row filter-wrap-block">
                        <div class="col-md-3 filter-ico">
                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/img/dog.png" alt="dogs-allowed">
                        </div>
                        <div class="col-md-9 ico-text">
                            <label for="">Hundtillåtet</label>
                        </div>
                    </div>
                </div>

                <div class="filter-case" >
                    <div data-item="_product_wi_fi" class="col-md-12 row filter-wrap-block">
                        <div class="col-md-3 filter-ico">
                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/img/wifi.png" alt="wi-fi">
                        </div>
                        <div class="col-md-9 ico-text">
                            <label for="">WI-FI</label>
                        </div>
                    </div>
                </div>
                <div class="filter-case">
                    <div data-item="_product_bastu" class="col-md-12 row filter-wrap-block">
                        <div class="col-md-3 filter-ico">
                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/img/sauna.png" alt="sauna">
                        </div>
                        <div class="col-md-9 ico-text">
                            <label for="">Bastu</label>
                        </div>
                    </div>
                </div>
                <div class="filter-case">
                    <div data-item="_product_oppen_spis" class="col-md-12 row filter-wrap-block">
                        <div class="col-md-3 filter-ico">
                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/img/bonfire.png" alt="fireplace">
                        </div>
                        <div class="col-md-9 ico-text">
                            <label for="">Öppen spis</label>
                        </div>
                    </div>
                </div>

                <div class="filter-case" >
                    <div data-item="_product_skidförråd" class="col-md-12 row filter-wrap-block">
                        <div class="col-md-3 filter-ico">
                            <img class="icons-content" src="<?php echo BEDS_URL; ?>assets/img/ski.png" alt="area">
                        </div>
                        <div class="col-md-9 ico-text">
                            <label for="">Skidförråd</label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-12">
                <div class="col-md-3" style="font-weight: bold; text-align: left; text-transform: uppercase">
                    <label >Utrustning</label>
                </div>
            </div>

            <div class="filter-blocks bottom_blocks">
                <div class="filter-case">
                    <div data-item="_product_diskmaskin" class="col-md-12 row filter-wrap-block">
                        <div class="col-md-3 filter-ico">
                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/img/dishwasher.png" alt="dishwasher">
                        </div>
                        <div class="col-md-9 ico-text">
                            <label for="">Diskmaskin</label>
                        </div>
                    </div>
                </div>
                <div class="filter-case">
                    <div data-item="_product_tvättmaskin" class="col-md-12 row filter-wrap-block">
                        <div class="col-md-3 filter-ico">
                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/img/washing-machine-icon.png" alt="washing-machine">
                        </div>
                        <div class="col-md-9 ico-text">
                            <label for="">Tvättmaskin</label>
                        </div>
                    </div>
                </div>
                <div class="filter-case">
                    <div data-item="_product_torkskåp" class="col-md-12 row filter-wrap-block">
                        <div class="col-md-3 filter-ico">
                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/img/dry.png" alt="drying-cabinet">
                        </div>
                        <div class="col-md-9 ico-text">
                            <label for="">Torkskåp</label>
                        </div>
                    </div>
                </div>
                <div class="filter-case">
                    <div data-item="_product_barnstol" class="col-md-12 row filter-wrap-block">
                        <div class="col-md-3 filter-ico">
                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/img/highchair.png" alt="highchair">
                        </div>
                        <div class="col-md-9 ico-text">
                            <label for="">Barnstol</label>
                        </div>
                    </div>
                </div>
                <div class="filter-case">
                    <div data-item="_product_barnsäng" class="col-md-12 row filter-wrap-block">
                        <div class="col-md-3 filter-ico">
                            <img class="icons-content" src="<?php echo BEDS_URL;?>assets/img/cot.png" alt="Crib">
                        </div>
                        <div class="col-md-9 ico-text">
                            <label for="">Barnsäng</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>