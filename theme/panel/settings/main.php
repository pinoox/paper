<div class="page">
    <div class="box">
        <div class="box-header">
            <h2 class="title"><?php lang('panel.settings') ?></h2>
        </div>
        <div class="box-content">
            <ul class="nav nav-tabs">
                <?php if (isset($groups) && !empty($groups)) { ?>
                    <?php foreach ($groups as $g) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == $g['s_group'] ? 'active' : ''; ?>"
                               href="<?php echo $_app . 'settings/' . $g['s_group']; ?>"><?php lang('panel.s_group_' . $g['s_group']); ?></a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
            <br>
            <div class="tab-content">
                <div class="tab-pane fade show active">
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data"
                          action="<?php echo $_app ?>settings/save/<?php echo $active; ?>">
                        <?php if (isset($settings) && !empty($settings)) { ?>
                            <?php foreach ($settings as $s) { ?>
                                <?php
                                $parts = explode(':', $s['s_view']);
                                $view = isset($parts[0]) ? $parts[0] : null;
                                $attr = isset($parts[1]) ? $parts[1] : null;
                                includeView('settings>views>' . $view, ['s' => $s, 'attr' => $attr]);
                                ?>
                            <?php } ?>
                        <?php } ?>
                        <div class="fab-wrapper">
                            <button type="submit" class="fab-btn green-gradient"><i class="fa fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
