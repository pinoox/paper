<?php
//paper functions file

use pinoox\app\com_pinoox_paper\model\SettingsModel;
use pinoox\app\com_pinoox_paper\component\TemplateHelper;

function setting($name = null,$theme = null){
    return SettingsModel::getData($name,$theme);
}

function paper_head()
{
    TemplateHelper::printHead();
}

function paper_footer()
{

}