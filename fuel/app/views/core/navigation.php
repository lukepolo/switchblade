<!-- Left panel : Navigation area -->
<aside id="left-panel">
    <!-- User info -->
    <div class="login-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as is -->
            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                <img src="/assets/img/avatars/sunny.png" alt="me" class="online" />
                <span>
                    john.doe
                </span>
                <i class="fa fa-angle-down"></i>
            </a>
        </span>
    </div>
    <!-- end user info -->
    <nav>
        <!-- NOTE: Notice the gaps after each icon usage <i></i>..
        Please note that these links work a bit different than
        traditional hre="" links. See documentation for details.
        -->
        <ul>
            <li class="active">
                <a href="ajax/dashboard.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">Jump/Split</span></a>
                <ul>
                    <li>
                        <a href="<?php echo Uri::Create('jumpsplit/editor'); ?>"><i class="fa fa-stack-overflow"></i>Editor</a>
                    </li>
                </ul>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">JumpDetail</span></a>
                <ul>
                    <li>
                        <a href="<?php echo Uri::Create('jumpsplit/editor'); ?>"><i class="fa fa-stack-overflow"></i>Editor</a>
                    </li>
                </ul>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">JumpHeat</span></a>
                <ul>
                    <li>
                        <a href="<?php echo Uri::Create('jumpsplit/editor'); ?>"><i class="fa fa-stack-overflow"></i>Editor</a>
                    </li>
                </ul>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">JumpRate</span></a>
                <ul>
                    <li>
                        <a href="<?php echo Uri::Create('jumpsplit/editor'); ?>"><i class="fa fa-stack-overflow"></i>Editor</a>
                    </li>
                </ul>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">JumpPing</span></a>
                <ul>
                    <li>
                        <a href="<?php echo Uri::Create('jumpsplit/editor'); ?>"><i class="fa fa-stack-overflow"></i>Editor</a>
                    </li>
                </ul>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">JumpCrash</span></a>
                <ul>
                    <li>
                        <a href="<?php echo Uri::Create('jumpsplit/editor'); ?>"><i class="fa fa-stack-overflow"></i>Editor</a>
                    </li>
                </ul>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">JumpHook</span></a>
                <ul>
                    <li>
                        <a href="<?php echo Uri::Create('jumpsplit/editor'); ?>"><i class="fa fa-stack-overflow"></i>Editor</a>
                    </li>
                </ul>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">JumpBonus</span></a>
                <ul>
                    <li>
                        <a href="<?php echo Uri::Create('jumpsplit/editor'); ?>"><i class="fa fa-stack-overflow"></i>Editor</a>
                    </li>
                </ul>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">JumpShare</span></a>
                <ul>
                    <li>
                        <a href="<?php echo Uri::Create('jumpsplit/editor'); ?>"><i class="fa fa-stack-overflow"></i>Editor</a>
                    </li>
                </ul>
            </li>
            <li class="top-menu-hidden">
                <a href="#"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i><span class="menu-item-parent">Dev Menu</span></a>
                 <ul>
                    <li>
                        <a target="_blank" href="https://switchblade.slack.com"><i class="fa fa-stack-overflow"></i>Slack</a>
                    </li>
                    <li>
                        <a target="_blank" href="https://switchblade.atlassian.net">JIRA</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
</aside>
<!-- END NAVIGATION -->