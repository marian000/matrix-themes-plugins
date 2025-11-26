<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



<div class="main-content">
  <div class="page-content" style="background-color: #F7F7F7">
    <div class="page-content-area">
      <div class="page-header">
        <h1>Add Blackout Frame</h1>
      </div>

      <div class="row">
        <div class="col-xs-12">


          <div class="row">
            <div class="col-sm-9">
              <strong>Order Reference:</strong> test
              <br/>
              <br/>
            </div>
            <div class="col-sm-3 pull-right">
              Total Square Meters&nbsp;
              <input class="input-small" disabled="disabled" id="property_total" name="property_total"
                type="text" value="" />
            </div>
          </div>
          <form accept-charset="UTF-8" action="http://tradeshutter.nexloc.com/order_products/add_single_product" data-type="json" enctype="multipart/form-data"
            id="add-product-single-form" method="post">

            <!-- <input id="order_product_id" name="order_product_id" type="hidden" value="" /> -->
            <!-- <input id="product_id" name="product_id" type="hidden" value="89" /> -->

            <!--
<div class="row" style="margin-top:1em;">
  <div class="col-sm-4">
    Style:<br/><input class="property-select" id="property_style" name="property_style" type="text" value="" />
  </div>
</div>
-->
            <div class="row">
              <div class="col-sm-12">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingStyle">
                      <h4 class="panel-title">
                        <a role="button" class="" data-toggle="collapse" data-parent="#accordion" href="#collapseStyle" aria-expanded="true" aria-controls="collapseStyle">
                          Your Measurements &amp; Style
                        </a>
                      </h4>
                    </div>
                    <div id="collapseStyle" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingStyle">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-3">
                            Room:
                            <br/>
                            <input class="property-select" id="property_room" name="property_room" type="text" value="" />
                          </div>
                          <div class="col-sm-3" id="room-other" style="display: none">
                            Room Other:
                            <br/>
                            <input class="input-medium" id="property_room_other" name="property_room_other" style="height: 30px"
                              type="text" value="" />
                          </div>
                        </div>


                        <div class="row" style="margin-top:1em;">
                          <div class="col-sm-12">
                            Style:
                            <div id="choose-style">
                              <label>
                                Bay Window Individual Shutter
                                <br/>
                                <input type="radio" name="property_style" data-code="individualshutter" data-title="Bay Window Individual Shutter" value="218"
                                />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Individual_Frames.jpg?1495195764">
                              </label>
                              <label>
                                Café Style Bay Window
                                <br/>
                                <input type="radio" name="property_style" data-code="cafe-bay" data-title="Café Style Bay Window" value="225" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Bay_Window_Cafe_Style_copy.jpg?1502277656">
                              </label>
                              <label>
                                <br/> Full Height
                                <br/>
                                <input type="radio" name="property_style" data-code="fullheight" data-title="Full Height" value="29" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Full_Height.png?1443989375">
                              </label>
                              <label>
                                Solid Flat&lt;br/&gt;Tier-on-Tier
                                <br/>
                                <input type="radio" name="property_style" data-code="solid-flat-tot" data-title="Solid Flat&lt;br/&gt;Tier-on-Tier" value="227"
                                />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Solid_Panel_Tier_on_Tier_Flat_bcf18cd5d2.png?1505088298">
                              </label>
                              <label>
                                Solid Raised Café Style
                                <br/>
                                <input type="radio" name="property_style" data-code="solid-raised-cafe-style" data-title="Solid Raised Café Style" value="226"
                                />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Solid-Panel-Raised-Cafe-Style-1.jpg?1502219645">
                              </label>
                              <label>
                                Solid Raised&lt;br/&gt;Tier-on-Tier
                                <br/>
                                <input type="radio" name="property_style" data-code="solid-raised-tot" data-title="Solid Raised&lt;br/&gt;Tier-on-Tier" value="228"
                                />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Solid_Panel_Tier_on_Tier_Raised_6ced5183ab.png?1505088344">
                              </label>
                            </div>
                          </div>
                          <div class="col-sm-5" id="shape-section" style="display: none">
                            Shape: (max size 1MB)
                            <br/>

                            <div id="shape-upload-container">
                              <span id="provided-shape">
                              </span>
                              <input id="attachment" name="attachment" type="file" />
                            </div>
                          </div>
                        </div>

                        <div class="row" style="margin-top:1em;">
                          <div class="col-sm-2">
                            Width (mm):
                            <br/>
                            <input class="required number input-medium" id="property_width" name="property_width" type="text"
                              value="" />
                          </div>
                          <div class="col-sm-2">
                            Height (mm):
                            <br/>
                            <input class="required number input-medium" id="property_height" name="property_height" type="text"
                              value="" />
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-sm-3 property_fit top10">
                            Fit:
                            <br/>
                            <div class="input-group-container">
                              <div class="input-group">
                                <span class="input-group-addon" title="IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. "
                                  data-toggle="tooltip" data-placement="top">?</span>
                                <input class="property-select" id="property_fit" name="property_fit" type="text"
                                  value="" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-12">
                            <hr/>
                            <button class="btn btn-info show-next-panel">
                              Next
                              <i class="fa fa-chevron-right"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                      <h4 class="panel-title">
                        <a role="button" class="" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          Frame Options
                        </a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-12">
                            Frame Type:
                            <p id="required-choices-frametype">
                              <i>Please select Material &amp; Style in order to view available Frame Type choices</i>
                            </p>
                            <div id="choose-frametype">
                              <label>
                                <br/> U Channel
                                <input type="radio" name="property_frametype" data-code="UCHANNEL" data-title="U Channel" value="217" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_main_D50.png?1491291705">
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="row frames" style="margin-top:1em;">
                          <div class="col-sm-12">
                            <div class="pull-left" id="frame-left">
                              Frame Left
                              <i class="fa fa-arrow-left"></i>
                              <br/>
                              <input class="property-select" id="property_frameleft" name="property_frameleft" type="text"
                                value="70" />
                            </div>
                            <div class="pull-left" id="frame-right">
                              Frame Right
                              <i class="fa fa-arrow-right"></i>
                              <br/>
                              <input class="property-select" id="property_frameright" name="property_frameright" type="text"
                                value="75" />
                            </div>
                            <div class="pull-left" id="frame-top">
                              Frame Top
                              <i class="fa fa-arrow-up"></i>
                              <br/>
                              <input class="property-select" id="property_frametop" name="property_frametop" type="text"
                                value="80" />
                            </div>
                            <div class="pull-left" id="frame-bottom">
                              Frame Bottom
                              <i class="fa fa-arrow-down"></i>
                              <br/>
                              <input class="property-select" id="property_framebottom" name="property_framebottom" type="text"
                                value="85" />
                            </div>




                          </div>
                        </div>

                        <div class="row">
                          <div class="col-sm-12">
                            <hr/>
                            <button class="btn btn-info show-next-panel">
                              Next
                              <i class="fa fa-chevron-right"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingColour">
                      <h4 class="panel-title">
                        <a role="button" class="" data-toggle="collapse" data-parent="#accordion" href="#collapseColour" aria-expanded="true" aria-controls="collapseColour">
                          Colour
                        </a>
                      </h4>
                    </div>
                    <div id="collapseColour" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingColour">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-3">
                            Frame Colour:
                            <br/>
                            <input class="property-select" id="property_shuttercolour" name="property_shuttercolour" type="text"
                              value="" />
                          </div>
                          <div class="col-sm-3" id="colour-other" style="display: none">
                            Other Colour:
                            <br/>
                            <input id="property_shuttercolour_other" name="property_shuttercolour_other" style="height: 30px"
                              type="text" value="" />
                          </div>

                        </div>

                        <div class="row">
                          <div class="col-sm-12">
                            <hr/>
                            <button class="btn btn-info show-next-panel">
                              Next
                              <i class="fa fa-chevron-right"></i>
                            </button>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingLayout">
                      <h4 class="panel-title">
                        <a role="button" class="" data-toggle="collapse" data-parent="#accordion" href="#collapseLayout" aria-expanded="true" aria-controls="collapseLayout">
                          Confirm Drawing
                        </a>
                      </h4>
                    </div>
                    <div id="collapseLayout" class="panel-collapse collapse drawing-panel" role="tabpanel" aria-labelledby="headingLayout">
                      <div class="panel-body">
                        <!-- drawing -->
                        <div class="row">
                          <div class="col-lg-8 col-md-11 col-sm-12">
                            <div style="display: none">
                              <textarea id="drawingConfig" style="width:300px;height:150px;"></textarea>
                              <input id="splitHeight" type="number" name="quantity_split" min="0" max="5000" step="10">
                              <button id="runButton">Run</button>
                              <textarea id="drawingConfigScaled" style="width:300px;height:150px;"></textarea>
                              <button id="runButtonScaled">Run</button>
                            </div>
                            <div id="canvas_container1" class="canvas_container" style="border: 1px solid #aaa;background-image: url('https://portal.tradeshutters.co.uk/assets/drawing_graph-6a40dd7277e59512fa3684b4777e84bb.png');"></div>
                            <br/>
                            <button class="btn btn-info print-drawing" style="z-index: 10;">
                              <i class="fa fa-print"></i> Print</button>
                            <textarea id="shutter_svg" name="shutter_svg" style="display:none">
                            </textarea>
                          </div>
                          <div class="col-lg-4 col-md-6">
                            Comments:
                            <br/>
                            <textarea id="comments_customer" name="comments_customer" rows="5" style="width: 100%">
                            </textarea>
                            <hr/>
                            <div id="nowarranty" style="display:none">
                              <input id="property_nowarranty" name="property_nowarranty" type="checkbox" value="Yes" /> I accept that because the frame is over 1600 it will have small puncture holes within the
                              blind
                            </div>
                            <input id="page_title" name="page_title" type="hidden" value="<?php echo get_the_title(); ?>" />
                            <button class="btn btn-success">
                              Add to Quote
                              <i class="fa fa-chevron-right"></i>
                            </button>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>


              </div>
            </div>

            <!-- the following is used as a prototype in order to add new fields while adding a layout code -->
            <div class="col-sm-2" id="extra-column" style="display: none">
              <span class="extra-column-label">Label</span>:
              <br/>

              <div class="input-group">
                <span data-placement="top" data-toggle="tooltip" title="" class="input-group-addon" data-original-title="Type in the layout code">?</span>
                <input type="text" name="property_extra_column" id="property_extra_column" class="input-small">
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">

              </div>
            </div>
            <hr/>

          </form>
          <style type="text/css">
            .input-group-addon-container {
              float: left
            }

            .input-group-addon-container .input-group-addon {
              height: 30px;
              border-left: 1px solid #cccccc;
            }

            #add-product-single-form .input-group {
              width: 100%
            }

            #choose-style label>input {
              /* HIDE RADIO */
              /* display: none; */
            }

            #choose-style label {
              display: block;
              float: left;
              width: 100px;
              text-align: center;
              border: 2px solid gray;
              margin-right: 1em;
              font-size: 10px
            }

            #choose-style label>input+img {
              /* IMAGE STYLES */
              cursor: pointer;
              border: 4px solid transparent;
            }

            #choose-style label>input:checked+img {
              /* (CHECKED) IMAGE STYLES */
              border: 4px solid #438EB9;
            }

            #choose-frametype label>input {
              /* HIDE RADIO */
              /* display: none; */
            }

            #choose-frametype label {
              display: block;
              float: left;
              width: 100px;
              text-align: center;
              border: 2px solid gray;
              margin-right: 1em;
              font-size: 10px
            }

            #choose-frametype label>input+img {
              /* IMAGE STYLES */
              cursor: pointer;
              border: 4px solid transparent;
            }

            #choose-frametype label>input:checked+img {
              /* (CHECKED) IMAGE STYLES */
              border: 4px solid #438EB9;
            }

            .extra-column input {
              width: 4em;
            }

            .extra-column {
              padding-right: 0.5em;
            }

            div.error-field {
              display: block
            }

            .error-field,
            input.error-field {
              border: 2px solid red;
            }

            .error-text {
              color: red;
              font-weight: bold;
            }

            .select2-result-label img,
            .select2-container img {
              display: inline;
            }

            .select2-container#s2id_property_style .select2-choice,
            #s2id_property_style .select2-result {
              height: 57px;
            }

            .extra-column-label {
              font-size: 0.8em
            }

            input.layout-code {
              font-size: 0.8em;
            }
          </style>

          <div class="show-prod-info">

          </div>


        </div>
      </div>
    </div>
  </div>
</div>
