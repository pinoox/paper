<div class="form-group row">
    <label for="<?php echo $s['s_key']; ?>"
           class="col-sm-12 col-form-label"><?php lang('panel.sett_' . $s['s_key']); ?></label>
    <div class="col-sm-12">
        <textarea rows="6"
                  id="<?php echo $s['s_key']; ?>"
                  placeholder="<?php lang('panel.sett_' . $s['s_key']); ?>"
                  name="<?php echo $s['s_key']; ?>"
                  class="input-form"><?php echo $s['s_value']; ?></textarea>
    </div>
</div>