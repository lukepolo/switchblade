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
<ul style="display: none;position: absolute;" id="absplit-element-menu" class="ui-menu ui-widget ui-widget-content ui-corner-all widget-templates" role="menu" tabindex="0" aria-activedescendant="ui-id-8">
    <li class="ui-menu-title"><li>
    <li class="ui-menu-item" role="presentation">
        <span class="ui-menu-icon ui-icon ui-icon-carat-1-e"></span>
        <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Trigger State</a>
        <ul id="trigger_states" class="ui-menu ui-widget ui-widget-content ui-corner-all" role="menu" aria-hidden="true" aria-expanded="false" style="display: none;">
<!--            <li class="ui-menu-item" role="presentation">
                <a data-type="active" href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Active</a>
            </li>-->
            <li class="ui-menu-item" role="presentation">
                <a data-type="hover" href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Hover</a>
            </li>    
<!--            <li class="ui-menu-item" role="presentation">
                <a data-type="focus" href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Focus</a>
            </li>-->
<!--            <li class="ui-menu-item" role="presentation">
                <a data-type="visited" href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Visited</a>
            </li>-->
        </ul>
    </li>
    <li class="ui-menu-item" role="presentation">
        <span class="ui-menu-icon ui-icon ui-icon-carat-1-e"></span>
        <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Edit Element</a>
        <ul class="ui-menu ui-widget ui-widget-content ui-corner-all" role="menu" aria-hidden="true" aria-expanded="false" style="display: none;">
            <li class="ui-menu-item" role="presentation">
                <a href="javascript:void(0);" onclick="javascript:absplit_html_editor();" data-toggle="modal" class="ui-corner-all" role="menuitem">HTML</a>
            </li>
                <li class="ui-menu-item" role="presentation">
                <a href="javascript:void(0);" onclick="javascript:absplit_classes_editor();" class="ui-corner-all" tabindex="-1" role="menuitem">Classes</a>
            </li>
            
            <li class="ui-menu-item" role="presentation">
                <a href="javascript:void(0);" onclick="javascript:absplit_css_editor();" class="ui-corner-all" tabindex="-1" role="menuitem">Styles</a>
            </li>
            <span>
                <li id="replace_img" class="ui-menu-item" role="presentation" style="display:none">
                    <a href="javascript:void(0);" onclick="javascript:absplit_img_editor()" class="ui-corner-all" tabindex="-1" role="menuitem">Replace Image</a>
                </li>
                <li id="link" class="ui-menu-item" role="presentation" style="display:none">
                    <a href="javascript:void(0);" onclick="javascript:absplit_link_editor()" class="ui-corner-all" tabindex="-1" role="menuitem">Edit Link</a>
                </li>
            </span>
        </ul>
    </li>
    <hr>
    <li id="resize_move" class="ui-menu-item" role="presentation">
        <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Move and Resize</a>
    </li>
    <li class="ui-menu-item" role="presentation">
        <a id="remove_element" href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Remove</a>
    </li>
    <hr>
    <li class="ui-menu-item" role="presentation">
        <a href="javascript:void(0);" onclick="javascript:absplit_goal_creator()" class="ui-corner-all" tabindex="-1" role="menuitem">Create Goal</a>
    </li>
    <hr>
    <li class="ui-menu-item" role="presentation">
        <span class="ui-menu-icon ui-icon ui-icon-carat-1-e"></span>
        <a href="javascript:void(0);" class="ui-corner-all" tabindex="-1" role="menuitem">Select Container</a>
        <ul id="select_container" class="ui-menu ui-widget ui-widget-content ui-corner-all" role="menu" aria-hidden="true" aria-expanded="false" style="display: none;">
        </ul>
    </li>
    <hr>
    <li>
        <a href="javascript:void(0);" id="absplit-close" class="ui-corner-all" tabindex="-1" role="menuitem">Close Menu</a>
    </li>
</ul>