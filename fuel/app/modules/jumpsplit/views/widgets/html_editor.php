<style>
    .note-editor {
        padding: 13px !important;
    }
</style>
<div id="jumpsplit-html-edit" class="jarviswidget jarviswidget-color-blue widget-templates" style="min-width:450px;position: absolute;display: none;">
    <header role="heading" style="cursor: all-scroll">
        <div class="jarviswidget-ctrls" role="menu">
            <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Close">
                <i class="fa fa-times"></i>
            </a>
        </div>
            <span class="widget-icon">
                <i class="fa fa-pencil"></i>
            </span>
            <h2>Edit HTML</h2>
        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span>
    </header>
    <!-- widget div-->
    <div role="content">
        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->
        </div>
        <!-- end widget edit box -->
        <!-- widget content -->
        <div class="widget-body no-padding">
            <div class="summernote"></div>
        </div>
        <div class="widget-footer smart-form">
            <div class="btn-group">
                <button class="btn btn-sm btn-primary" type="button">
                    <i class="fa fa-times"></i> Cancel
                </button>	
            </div>
            <div class="btn-group">
                <button class="btn btn-sm btn-success" type="button">
                    <i class="fa fa-check"></i> Save
                </button>	
            </div>
            <label class="checkbox pull-left">
                <input type="checkbox" checked="checked" name="autosave" id="autosave">
                <i></i>Auto Save 
            </label> 
        </div>
    </div>
    <!-- end widget content -->
</div>
<!-- end widget div -->