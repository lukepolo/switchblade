<div id="absplit-css" class="jarviswidget jarviswidget-color-blue widget-templates" style="min-width:450px;position: absolute;display: none;">
    <header role="heading" style="cursor: all-scroll">
        <div class="jarviswidget-ctrls" role="menu">
            <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Close">
                <i class="fa fa-times"></i>
            </a>
        </div>
            <span class="widget-icon">
                <i class="fa fa-pencil"></i>
            </span>
            <h2>Edit CSS</h2>
        <ul class="nav nav-tabs pull-right in">
            <li class="active">
                <a data-toggle="tab" href="#css_tab_1"><i class="fa fa-font"></i> <span>Text</span></a>
            </li>
            <li>
                <a data-toggle="tab" href="#css_tab_2"><i class="fa fa-pencil"></i> <span>Color & Background</span></a>
            </li>
            <li>
               <a data-toggle="tab" href="#css_tab_3"><i class="fa fa-expand"></i> <span>Dimensions</span></a>
            </li>
            <li>
               <a data-toggle="tab" href="#css_tab_4"><i class="fa fa-square-o"></i> <span>Borders</span></a>
            </li>
            <li>
               <a data-toggle="tab" href="#css_tab_5"><i class="fa fa-columns"></i> <span>Layout</span></a>
            </li>
                <li>
               <a data-toggle="tab" href="#css_tab_6"><i class="fa fa-cubes"></i> <span>Advanced</span></a>
            </li>
        </ul>
        <div class="drag">
            <i class="fa fa-align-justify"></i>
        </div>
    </header>
    <!-- widget div-->
    <div id="css_widget" class="no-padding" role="content" style="margin-top:-2px;">
        <!-- widget content -->
        <div class="widget-body">
            <div class="tab-content">
                <div class="tab-pane fade active in" id="css_tab_1">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Font Family</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Font Family" type="text" data-get="font-family">
                                </div>
                                <label class="col-md-3 control-label">Font Size</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Font Size" type="text" value="" data-get="font-size">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Font Style</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Font Style" type="text" list="list" data-get="font-style">
                                    <datalist id="list">
                                        <option></option>
                                        <option value="normal">normal</option>
                                        <option value="italic">italic</option>
                                        <option value="oblique">oblique</option>
                                        <option value="inherit">inherit</option>
                                    </datalist>
                                </div>
                                <label class="col-md-3 control-label">Text Alignment</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Font Alignment" type="text" list="list" data-get="text-align">
                                    <datalist id="list">
                                        <option></option>
                                        <option value="left">left</option>
                                        <option value="center">center</option>
                                        <option value="right">right</option>
                                        <option value="justify">justify</option>
                                        <option value="start">start</option>
                                        <option value="end">end</option>
                                        <option value="inherit">inherit</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Text Decoration</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Font Decoration" type="text" list="list" data-get="text-decoration">
                                    <datalist id="list">
                                        <option></option>
                                        <option value="left">none</option>
                                        <option value="center">inherit</option>
                                        <option value="right">underline</option>
                                        <option value="line-through">line through</option>
                                        <option value="overline">overline</option>
                                        <option value="blink">blink</option>
                                    </datalist>
                                </div>
                                <label class="col-md-3 control-label">Font Weight</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Font Weight" type="text" list="list" data-get="font-weight">
                                    <datalist id="list">
                                        <option></option>
                                        <option value="normal">normal</option>
                                        <option value="bold">bold</option>
                                        <option value="100">100 (thin)</option>
                                        <option value="200">200</option>
                                        <option value="300">300</option>
                                        <option value="400">400 (medium)</option>
                                        <option value="500">500</option>
                                        <option value="600">600</option>
                                        <option value="700">700</option>
                                        <option value="800">800</option>
                                        <option value="900">900 (thick)</option>
                                        <option value="inherit">inherit</option>
                                    </datalist>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="tab-pane fade" id="css_tab_2">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Font Color:</label>
                                <div class="col-md-3">
                                    <input class="form-control colorpicker" placeholder="Font Color" type="text" data-get="color">
                                    <div id="colorSelector"><div style="background-color: rgb(32, 32, 107);"></div></div>
                                </div>
                                <label class="col-md-3 control-label">Background Color:</label>
                                <div class="col-md-3">
                                    <input class="form-control colorpicker" placeholder="Background Color" type="text" data-get="background-color">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Background Image:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Background Image" type="text" data-get="background-image">
                                </div>
                                <label class="col-md-3 control-label">Background Position:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Background Position" type="text" data-get="background-position">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Background Repeat:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Background Repeat" type="text" list="list" data-get="background-repeat">
                                     <datalist id="list">
                                        <option></option>
                                        <option value="no-repeat">no-repeat</option>
                                        <option value="repeat">repeat</option>
                                        <option value="repeat-x">repeat (horizontal)</option>
                                        <option value="repeat-y">repeat (vertical)</option>
                                        <option value="inherit">inherit</option>
                                    </datalist>
                                </div>
                                 <label class="col-md-3 control-label">Opacity</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Opacity" type="text" data-get="opacity">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="tab-pane fade" id="css_tab_3">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Height:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Height" type="text" data-get="height">
                                </div>
                                <label class="col-md-3 control-label">Min Height:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Min Height" type="text" data-get="min-height">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Max Height:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Max Height" type="text" data-get="max-height">
                                </div>
                                <label class="col-md-3 control-label">Width:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Width" type="text" data-get="width">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Min Width:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Min Width" type="text" data-get="min-width">
                                </div>
                                 <label class="col-md-3 control-label">Max Width:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Max Width" type="text" data-get="max-width">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Margin: (format)</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Margin" type="text" data-get="margin">
                                </div>
                                <label class="col-md-3 control-label">Padding: (format</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Padding" type="text" data-get="padding">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="tab-pane fade" id="css_tab_4">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Border Color:</label>
                                <div class="col-md-3">
                                    <input class="form-control colorpicker" placeholder="Border Color" type="text" data-get="border-color">
                                </div>
                                <label class="col-md-3 control-label">Border Style:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Border Style" type="text" data-get="border-style">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Border Width:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Border Width" type="text" data-get="border-width">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="tab-pane fade" id="css_tab_5">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Top:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Top" type="text" data-get="top">
                                </div>
                                <label class="col-md-3 control-label">Bottom:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Bottom" type="text" data-get="bottom">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Left:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Left" type="text" data-get="left">
                                </div>
                                <label class="col-md-3 control-label">Right:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Right" type="text" data-get="right">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Z-Index:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Z-Index" type="text" data-get="z-index">
                                </div>
                                <label class="col-md-3 control-label">Display:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Display" type="text" data-get="display">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Visibility:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Visibility" type="text" data-get="visibility">
                                </div>
                                <label class="col-md-3 control-label">Position:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Position" type="text" data-get="position">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Float:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Float" type="text" data-get="float">
                                </div>
                                <label class="col-md-3 control-label">Clear:</label>
                                <div class="col-md-3">
                                    <input class="form-control" placeholder="Clear" type="text" data-get="clear">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="tab-pane fade" id="css_tab_6">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                TODO  - Dynamically Generated Options
                                TODO - Add search bar into jarvis widget, allow them to find things quickly = amazing
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <div class="widget-footer smart-form">
            <div class="btn-group">
                <button class="btn btn-sm btn-primary cancel" type="button">
                    <i class="fa fa-times"></i> Cancel
                </button>	
            </div>
            <div class="btn-group">
                <button class="btn btn-sm btn-success save" type="button">
                    <i class="fa fa-check"></i> Save
                </button>	
            </div>
        </div>
    </div>
    <!-- end widget content -->
</div>
<!-- end widget div -->