<div class="form-group row">
    <label for="<?php echo $s['s_key']; ?>"
           class="col-sm-12 col-form-label"><?php lang('panel.sett_' . $s['s_key']); ?></label>
    <div class="col-sm-12">

        <input type="<?php echo isset($attr) ? $attr : 'text'; ?>"
               placeholder="<?php lang('panel.sett_' . $s['s_key']); ?>"
               class="input-form"
               id="<?php echo $s['s_key']; ?>"
               name="<?php echo $s['s_key']; ?>"
               value="<?php echo $s['s_value']; ?>">
    </div>
</div>