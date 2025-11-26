<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



<div class="main-content">
  <div class="page-content" style="background-color: #F7F7F7">
    <div class="page-content-area">
      <div class="page-header">
        <h1>Add Shutter</h1>
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
              <input class="input-small" disabled="disabled" id="property_total" name="property_total" type="text" value="" />
            </div>
          </div>
          <form accept-charset="UTF-8" action="http://matrix.lifetimeshutters.com/order_products/add_single_product" data-type="json" enctype="multipart/form-data"
            id="add-product-single-form" method="post">

            <!-- <input id="order_product_id" name="order_product_id" type="hidden" value="" /> -->

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
                          <div class="col-sm-3">
                            Material:
                            <br/>
                            <input class="property-select" id="property_material" name="property_material" type="text" value="" />
                            <input id="product_id" name="product_id" type="hidden" value="" />
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
                            Number of sections
                            <input class="required number input-medium" id="sections_count" name="sections_count"
                              type="text" value="" />
                            <br/>(2 or more):
                          </div>
                          <div class="col-sm-2">
                            Height (mm):
                            <br/>
                            <input class="required number input-medium" id="property_height" name="property_height" type="text"
                              value="" />
                          </div>

                          <div class="col-sm-2" id="midrail-height">
                            Midrail Height (mm):
                            <br/>
                            <input class="required number input-medium" id="property_midrailheight" name="property_midrailheight"
                              type="text" value="" />
                          </div>
                          <div class="col-sm-2" id="midrail-position-critical">
                            Position is Critical:
                            <br/>
                            <div class="input-group-container">
                              <div class="input-group">
                                <span class="input-group-addon" title="Please note, if mid rail is selected as critical, top and bottom rails will be different sizes. If not, they will be manufactured evenly but the mid rail location may change by +/- 25.4mm"
                                  data-toggle="tooltip" data-placement="top">?</span>
                                <input class="property-select" id="property_midrailpositioncritical" name="property_midrailpositioncritical"
                                  type="text" value="170" />
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-2 tot-height" style="display: none">
                            T-o-T Height (mm):
                            <br/>
                            <input class="required number input-medium" id="property_totheight" name="property_totheight"
                              type="text" value="" />
                          </div>
                          <div class="col-sm-2 tot-height horizontal-t-post" style="display: none">
                            Horizontal T Post
                            <br/>
                            <input id="property_horizontaltpost" name="property_horizontaltpost" type="checkbox" value="Yes"
                            />
                          </div>
                          <div class="col-sm-2">
                            Louvre Size:
                            <br/>
                            <input class="property-select" id="property_bladesize" name="property_bladesize" type="text"
                              value="" />
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
                    <div class="panel-heading" role="tab" id="headingLayoutcodes">
                      <h4 class="panel-title">
                        <a role="button" class="" data-toggle="collapse" data-parent="#accordion" href="#collapseLayoutcodes" aria-expanded="true"
                          aria-controls="collapseLayoutcodes">
                          Layout codes &amp; Width
                        </a>
                      </h4>
                    </div>
                    <div id="collapseLayoutcodes" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingColour">
                      <div class="panel-body">
                        <div class="row top10" id="prototype-row" style="display:none">
                          <div class="col-sm-3 pull-left">
                            <div class="pull-left" id="layoutcode-column">
                              Layout Code:
                              <br/>
                              <div class="input-group-container">
                                <div class="input-group">
                                  <span class="input-group-addon" title="Type in the layout code" data-toggle="tooltip" data-placement="top">?</span>
                                  <input class="required input-medium layoutcode" id="property_index_layoutcode"
                                    name="property_layoutcode[index]" style="margin-right:10px" type="text" value="" />
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-2 pull-left">
                            <div class="pull-left">
                              Width (mm):
                              <br/>
                              <input class="required number input-medium width" id="property_index_width" name="property_width[index]"
                                type="text" value="" />
                            </div>
                          </div>
                        </div>

                        <div id="sections">

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
                          Frame &amp; Stile Options
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
                                <br/> Bottom M Track
                                <input type="radio" name="property_frametype" data-code="L50" data-title="Bottom M Track" value="144" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Bottom_M_Track.png?1445502792">
                              </label>
                              <label>
                                <br/> D50
                                <input type="radio" name="property_frametype" data-code="D50" data-title="D50" value="142" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_D50.jpg?1445452908">
                              </label>
                              <label>
                                <br/> F50
                                <input type="radio" name="property_frametype" data-code="F50" data-title="F50" value="61" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_F50.jpg?1445450826">
                              </label>
                              <label>
                                <br/> F70
                                <input type="radio" name="property_frametype" data-code="F70" data-title="F70" value="62" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_F70.jpg?1445450840">
                              </label>
                              <label>
                                <br/> F90
                                <input type="radio" name="property_frametype" data-code="F90" data-title="F90" value="216" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_F90_square.png?1481385406">
                              </label>
                              <label>
                                <br/> L50
                                <input type="radio" name="property_frametype" data-code="L50" data-title="L50" value="59" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_L50.jpg?1445450852">
                              </label>
                              <label>
                                <br/> L50MF
                                <input type="radio" name="property_frametype" data-code="L50MF" data-title="L50MF" value="173" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_l50mf.jpg?1467718477">
                              </label>
                              <label>
                                <br/> L60SF
                                <input type="radio" name="property_frametype" data-code="L60SF" data-title="L60SF" value="141" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_L60-SF.jpg?1445452887">
                              </label>
                              <label>
                                <br/> L70
                                <input type="radio" name="property_frametype" data-code="L70" data-title="L70" value="60" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_L70.jpg?1445450980">
                              </label>
                              <label>
                                <br/> L90
                                <input type="radio" name="property_frametype" data-code="L90" data-title="L90" value="150" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_L90.jpg?1447614977">
                              </label>
                              <label>
                                <br/> SBS 50
                                <input type="radio" name="property_frametype" data-code="SBS50" data-title="SBS 50" value="63" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_SBS-50.jpg?1445451131">
                              </label>
                              <label>
                                <br/> Track in Board
                                <input type="radio" name="property_frametype" data-code="L50" data-title="Track in Board" value="143" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Track_in_Board.png?1445502778">
                              </label>
                              <label>
                                <br/> Z2BS
                                <input type="radio" name="property_frametype" data-code="Z2BS" data-title="Z2BS" value="67" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Z2BS.jpg?1445451278">
                              </label>
                              <label>
                                <br/> Z3CS
                                <input type="radio" name="property_frametype" data-code="Z3CS" data-title="Z3CS" value="66" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Z3CS.jpg?1445451318">
                              </label>
                              <label>
                                <br/> Z40
                                <input type="radio" name="property_frametype" data-code="Z40" data-title="Z40" value="64" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Z40.jpg?1445451330">
                              </label>
                              <label>
                                <br/> Z40SF
                                <input type="radio" name="property_frametype" data-code="Z40SF" data-title="Z40SF" value="140" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Z40-SF.jpg?1445452867">
                              </label>
                              <label>
                                <br/> Z50
                                <input type="radio" name="property_frametype" data-code="Z50" data-title="Z50" value="65" />
                                <img src="/wp-content/themes/storefront/imgs/thumb_Z50.jpg?1445451363">
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

                            <div class="pull-left">
                              <span id="add-buildout">
                                <br/>
                                <button class="btn btn-info" style="padding: 0 12px">Add buildout</button>
                              </span>
                              <span id="buildout" style="display: none">
                                Buildout:
                                <br/>
                                <input class="input-small" id="property_builtout" name="property_builtout" placeholder="Enter buildout" style="height: 30px;"
                                  type="text" value="" />
                                <button class="btn btn-danger btn-input" id="remove-buildout">
                                  <strong>X</strong>
                                </button>
                              </span>
                            </div>

                            <div class="pull-left">
                              Stile:
                              <br/>
                              <input class="property-select" id="property_stile" name="property_stile" type="text" value=""
                              />
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
                          Colour, Hinges, Control Options &amp; Layout Code
                        </a>
                      </h4>
                    </div>
                    <div id="collapseColour" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingColour">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-sm-3">
                            Hinge Colour:
                            <br/>
                            <input class="property-select" id="property_hingecolour" name="property_hingecolour" type="text"
                              value="" />
                          </div>
                          <div class="col-sm-3">
                            Shutter Colour:
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
                          <div class="col-sm-3">
                            Control Type:
                            <br/>
                            <input class="property-select" id="property_controltype" name="property_controltype" type="text"
                              value="" />
                          </div>
                          <div class="col-sm-3" id="control-split-height" style="display: none">
                            Control Split Height (mm):
                            <br/>
                            <input id="property_controlsplitheight" name="property_controlsplitheight" style="height: 30px" type="text" value="" />
                            <input id="property_controlsplitheight2" name="property_controlsplitheight2" style="display:none;height: 30px" type="text"
                              value="0" />
                          </div>

                        </div>

                        <div class="row" style="margin-top:1em;">
                          <div class="col-sm-4" id="spare-louvres">
                            <div>
                              <label>
                                <input class="ace" id="property_sparelouvres" name="property_sparelouvres" type="checkbox" value="Yes" />
                                <span class="lbl"> Include 2 x Spare Louvres (£2 + VAT)</span>
                              </label>
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
                              I accept that there is no warranty for this item
                              <br/>
                              <input id="property_nowarranty" name="property_nowarranty" type="checkbox" value="Yes" />
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