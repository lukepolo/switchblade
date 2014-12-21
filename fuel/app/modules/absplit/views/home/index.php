<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">
        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                <h2>Hide / Show Columns </h2>
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
                    <table id="datatable_col_reorder" class="table table-striped table-bordered table-hover" width="100%">
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
                                <td>1</td>
                                <td>35728</td>
                                <td>Fogo</td>
                                <td>
                                    <label class="input"> 
                                        <label class="toggle">
                                            <?php 
                                                echo \Form::input('active', "false", array('type' => 'hidden'));
                                                echo \Form::checkbox('active', 'false', 'false', array('toggle' => true));
                                            ?>
                                            Active
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
</section>
<!-- end widget grid -->
<script type="text/javascript">
    // PAGE RELATED SCRIPTS
    // pagefunction	
    $(document).ready(function()
    {
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