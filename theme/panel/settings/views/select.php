<?php

if ((!empty($s['s_value']) && !empty($s['s_default'])) || (empty($s['s_value']) && !empty($s['s_default'])))
    $placeholder = rlang('panel.default_value') . ' ' . $s['s_default'];
else $placeholder = rlang('panel.sett_' . $s['s_key']);

if (isset($s['s_dataset']) && !empty($s['s_dataset'])) {
    $dataSet = json_decode($s['s_dataset'], true);
}

?>


<div class="form-group row">
    <label for="<?php echo $s['s_key']; ?>"
           class="col-sm-12 col-form-label"><?php echo lang('panel.sett_' . $s['s_key']); ?></label>
    <div class="col-sm-3">

        <?php if (isset($dataSet) && !empty($dataSet)) { ?>
            <select name="<?php echo $s['s_key']; ?>" id="<?php echo $s['s_key']; ?>" class="input-form">
                <?php foreach ($dataSet as $k => $v) { ?>
                    <option <?php echo $s['s_value'] == $k ? 'selected' : '' ?>
                            value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php } ?>
            </select>
        <?php } ?>
    </div>
</div>