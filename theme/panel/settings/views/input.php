<div class="form-group row">
    <label for="<?php echo $s['s_key']; ?>"
           class="col-sm-12 col-form-label"><?php echo lang('panel.sett_' . $s['s_key']); ?></label>
    <div class="col-sm-12">

        <?php if ((!empty($s['s_value']) && !empty($s['s_default'])) || (empty($s['s_value']) && !empty($s['s_default'])))
            $placeholder = rlang('panel.default_value') . ' ' . $s['s_default'];
        else $placeholder = rlang('panel.sett_' . $s['s_key']); ?>

        <input type="<?php echo isset($attr) ? $attr : 'text'; ?>"
               placeholder=" <?php echo $placeholder; ?>"
               class="input-form"
               id="<?php echo $s['s_key']; ?>"
               name="<?php echo $s['s_key']; ?>"
               value="<?php echo $s['s_value']; ?>">
    </div>
</div>