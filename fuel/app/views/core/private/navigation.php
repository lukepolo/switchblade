<!-- Left panel : Navigation area -->
<aside id="left-panel">
    <!-- User info -->
    <div class="login-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as is -->
            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                <?php echo \Html::img($user_image); ?>
                <span>
                   <?php echo ucwords(Auth::get('username')); ?>
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
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-code txt-color-blue"></i> <span class="menu-item-parent">A/B Tester</span></a>
                <ul>
                    <li>
                        <a href="<?php echo Uri::Create('jumpsplit/editor'); ?>"><i class="fa fa-stack-overflow"></i>Editor</a>
                    </li>
                </ul>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-bar-chart txt-color-blue"></i> <span class="menu-item-parent">Analytics</span></a>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-fire txt-color-blue"></i> <span class="menu-item-parent">Heat Happing</span></a>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">Review System</span></a>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-dashboard txt-color-blue"></i> <span class="menu-item-parent">Pinger</span></a>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-exclamation txt-color-blue"></i> <span class="menu-item-parent">Error Reports</span></a>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-money txt-color-blue"></i> <span class="menu-item-parent">Bonus Program</span></a>
            </li>
            <li class="top-menu-hidden">
                <a href="<?php echo Uri::Create('jumpsplit'); ?>"><i class="fa fa-lg fa-fw fa-share-alt txt-color-blue"></i> <span class="menu-item-parent">Shortener</span></a>
            </li>
            <li class="top-menu-hidden">
                <a href="#"><i class="fa fa-lg fa-fw fa-gear txt-color-blue"></i><span class="menu-item-parent">Dev Menu</span></a>
                 <ul>
                    <li>
                        <a target="_blank" href="https://switchblade.slack.com"><i class="fa fa-stack-overflow"></i>Slack</a>
                    </li>
                    <li>
                        <a target="_blank" href="http://bladeswitch.io">JIRA</a>
                    </li>
                    <li>
                        <a target="_blank" href="http://box.bladeswitch.io">Share Drive</a>
                    </li>
                    <li>
                        <a href="<?php echo Uri::Create('settings'); ?>">Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo Uri::Create('settings/profiler'); ?>"><?php echo Session::Get('profiler') ? 'Disable' : 'Enable';?> Profiler</a>
                    </li>
                    <li>
                        <a href="<?php echo Uri::Create('settings/minify'); ?>"><?php echo Session::Get('minify') ? 'Enable' : 'Disable';?> Minify</a>
                    </li>
                    <li>
                        <a href="http://bootstraphunter.com/smartadmin/BUGTRACK/track_/" target="_blank">SmartAdmin CSS</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
</aside>
<!-- END NAVIGATION -->