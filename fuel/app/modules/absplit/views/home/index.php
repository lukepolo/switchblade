<div class="well">
    <?php echo Form::open(array('action' => Uri::Create('absplit/new_experiment'), 'id' => 'new_expiriement')); 
    ?>
        <div class="input-group">
            <input name="url" required class="form-control" type="text" placeholder="Experiment URL">
            <div class="input-group-btn">
                <button class="btn btn-default btn-primary" type="submit">
                    <i class="fa fa-plus"></i> New Experiment
                </button>
            </div>
        </div>
    <?php echo Form::close(); ?>
</div>
<!-- row -->
<div class="row">
    <!-- Widget ID (each widget will need unique ID)-->
    <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
        <header>
            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
            <h2>Experiments </h2>
        </header>
        <!-- widget div-->
        <div>
            <!-- widget edit box -->
            <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->
            </div>
            <!-- end widget edit box -->
            <!-- widget content -->
            <div class="widget-body no-padding">
                <table id="datatable_col_reorder" class="table table-striped table-bordered table-hover smart-form" width="100%">
                    <thead>
                        <tr>
                            <th>URL</th>
                            <th data-hide="phone,tablet">Type</th>
                            <th data-hide="phone,tablet">Confidence Level</th>
                            <th data-hide="phone,tablet">Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <a href="<?php echo Uri::Create('absplit/editor'); ?>">http://lukepolo.com</a>
                            </td>
                            <td>Multivariable Test</td>
                            <td>N/A</td>
                            <td>
                                <label class="input"> 
                                    <label class="toggle">
                                        <?php 
                                            echo \Form::input('active', "false", array('type' => 'hidden'));
                                            echo \Form::checkbox('active', 'false', 'false', array('toggle' => true));
                                        ?>
                                    </label>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- end widget content -->
        </div>
        <!-- end widget div -->
    </div>
    <!-- end widget -->
</div>
<!-- end widget grid -->
<script type="text/javascript">
    // PAGE RELATED SCRIPTS
    // pagefunction	
    $(document).ready(function()
    {
        $("#new_expiriement").validate({
            rules: {
                url: {
                    required: true,
                    url: true
                },
            },
            messages: {
                url: "Please enter a valid URL",
            },
            submitHandler: function(form) {
                form.submit();
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
        
        var responsiveHelper_datatable_col_reorder = undefined;

        var breakpointDefinition = 
        {
            tablet : 1024,
            phone : 480
        };

        /* COLUMN SHOW - HIDE */
        $('#datatable_col_reorder').dataTable(
        {
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>"+
                "t"+
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "autoWidth" : true,
            "preDrawCallback" : function() 
            {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_datatable_col_reorder) 
                {
                    responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#datatable_col_reorder'), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) 
            {
                // Creates an exapnd icon so they can use on the mobile
                responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) 
            {
                // makes the datatable responsive
                responsiveHelper_datatable_col_reorder.respond();
            }			
        });
    });
</script>
