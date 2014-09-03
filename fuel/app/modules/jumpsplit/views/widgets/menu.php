<style>
    .ui-menu-title {
        text-decoration: none;
        display: block;
        padding: 2px .4em;
        line-height: 1.5;
        min-height: 0;
        font-weight: 400;
        font-size: inherit;
        text-overflow: ellipsis;
        white-space: nowrap; 
        overflow: hidden;
        cursor: all-scroll;
    }
    .ui-menu-icons .ui-menu-item a {
        padding-left: 6px;
    }
</style>
<ul style="display: none;position: absolute;" id="jumpsplit-element-menu" class="ui-menu ui-widget ui-widget-content ui-corner-all widget-templates" role="menu" tabindex="0" aria-activedescendant="ui-id-8">
    <li class="ui-menu-title"><li>
    <li class="ui-menu-item" role="presentation">
        <span class="ui-menu-icon ui-icon ui-icon-carat-1-e"></span>
        <a href="javascript:void(0);" id="ui-id-3" class="ui-corner-all" tabindex="-1" role="menuitem">Trigger State</a>
        <ul class="ui-menu ui-widget ui-widget-content ui-corner-all" role="menu" aria-hidden="true" aria-expanded="false" style="display: none;">
            <li class="ui-menu-item" role="presentation">
                <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Active</a>
            </li>
            <li class="ui-menu-item" role="presentation">
                <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Hover</a>
            </li>    
            <li class="ui-menu-item" role="presentation">
                <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Focus</a>
            </li>
            <li class="ui-menu-item" role="presentation">
                <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Visited</a>
            </li>
        </ul>
    </li>
    <li class="ui-menu-item" role="presentation">
        <span class="ui-menu-icon ui-icon ui-icon-carat-1-e"></span>
        <a href="javascript:void(0);" id="ui-id-3" class="ui-corner-all" tabindex="-1" role="menuitem">Edit Element</a>
        <ul class="ui-menu ui-widget ui-widget-content ui-corner-all" role="menu" aria-hidden="true" aria-expanded="false" style="display: none;">
            <li class="ui-menu-item" role="presentation">
                <a href="#" onclick="javascript:jumpsplit_html_editor()" data-toggle="modal" class="ui-corner-all" role="menuitem">HTML</a>
            </li>
                <li class="ui-menu-item" role="presentation">
                <a href="#" onclick="javascript:jumpsplit_classes_editor()" class="ui-corner-all" tabindex="-1" role="menuitem">Classes</a>
            </li>
            <li class="ui-menu-item" role="presentation">
                <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Styles</a>
            </li>
            <li class="ui-menu-item" role="presentation">
                <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Update / Insert Link (change depending on options)</a>
            </li>
        </ul>
    </li>
    <li class="ui-menu-item" role="presentation">
        <a href="javascript:void(0);" id="ui-id-4" class="ui-corner-all" tabindex="-1" role="menuitem">Move</a>
    </li>
    <div></div>
    <li>
        <a href="javascript:void(0);" id="jumpsplit-close" class="ui-corner-all" tabindex="-1" role="menuitem">Close Menu</a>
    </li>
</ul>