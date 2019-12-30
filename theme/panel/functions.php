<?php

use pinoox\component\File;
use pinoox\component\HelperString;
use pinoox\component\ImageProcess;
use pinoox\component\Template;
use pinoox\component\Uploader;
use pinoox\component\Url;
use pinoox\component\User;

function showPage($page, $url, $getPattern = '')
{
    $sample = '<a [add] class="no-border item" [url]>[name]</a>';
    $pattern = 'url=' . $url;
    if (!empty($getPattern)) {
        $pattern .= '|' . $getPattern;
    } else {
        $pattern .= '|add-active:class:active item|prev=' . rlang('~pagination.prev') . '|next=' . rlang('~pagination.next') . '|last=' . rlang('~pagination.last') . '|first=' . rlang('~pagination.first') . '|going=...|nourl=href="#" ';
    }

    echo $page->printHtml($sample, $pattern);
}

function includeView($path, $arr = [])
{
    Template::includeView($path, $arr);
}

function showImage($image_id, $thumbSize = null, $isAvatar = false)
{
    $default = $isAvatar ? furl('resources/avatar.png') : furl('resources/image-placeholder.jpg');

    if (empty($thumbSize))
        return Url::upload($image_id, $default);
    else
        return Url::thumb($image_id, $thumbSize, $default);
}
function showDate($date)
{
    if(app('lang')==='en'){
        echo \pinoox\component\Date::g('d F Y', $date);
    }else{
        echo \pinoox\component\Date::j('d F Y', $date);
    }

}

function tableEmpty()
{
    echo "<tr class='text-center'><td colspan='20'>" . rlang("panel.empty_table_result") . "</td></tr>";
}

function modalConfirm($info = array())
{
    if (!isset($info['id'])) $info['id'] = 'modal-delete';
    if (!isset($info['title'])) $info['title'] = "<strong>" . rlang('panel.warning') . "</strong>";
    if (!isset($info['message'])) $info['message'] = rlang('panel.delete_confirmation');
    if (!isset($info['btn_confirm'])) $info['btn_confirm'] = 'btnConfirm';
    if (!isset($info['btn_confirm_text'])) $info['btn_confirm_text'] = rlang('panel.yes_delete');
    if (!isset($info['btn_cancel_text'])) $info['btn_cancel_text'] = rlang('panel.cancel');
    if (!isset($info['class_header'])) $info['class_header'] = '';
    if (!isset($info['class_body'])) $info['class_body'] = '';
    if (!isset($info['class_footer'])) $info['class_footer'] = '';

    $modalHTML = '<div class="modal fade" id="' . @$info['id'] . '" role="dialog">' .
        '<div class="modal-dialog">' .
        '<div class="modal-content">' .
        '<div class="modal-header ' . $info['class_header'] . '">' .
        '<h5 class="modal-title">' . @$info['title'] . '</h5>' .
        '<span class="close" data-dismiss="modal" aria-hidden="true">&times;</span>' .
        '</div>' .
        '<div class="modal-body ' . $info['class_body'] . '">' . @$info['message'] . '<br>' .
        '</div>' .
        '<div class="modal-footer ' . $info['class_footer'] . '"> ' .
        '<a ' . @$info['btn_cancel'] . '  class="btn btn-outline" data-dismiss="modal" >' . @$info['btn_cancel_text'] . '</a >' .
        '<a class="btn primary-gradient" id="' . @$info['btn_confirm'] . '" >' . @$info['btn_confirm_text'] . '</a>' .
        '</div>' .
        '</div>' .
        '</div>' .
        '</div>';

    $script = "<script>" .
        "document.addEventListener('DOMContentLoaded', function(event) { " .
        "$('#" . $info['id'] . "').on('show.bs.modal', function (e) {" .
        "$(this).find('a#" . $info['btn_confirm'] . "').attr('href', $(e.relatedTarget).data('href'));" .
        "});" .
        "window.modalConfirm = function (run){" .
        "$('#" . $info['id'] . "').modal('show', function () {" .
        "$(this).find('a#" . $info['btn_confirm'] . "').off('click');" .
        "$(this).find('a#" . $info['btn_confirm'] . "').on('click',function () {" .
        "$('#" . $info['id'] . "').modal('hide');" .
        "run();});});};});" .
        "</script>";
    echo $modalHTML;
    echo $script;
}

function getUser($val)
{
    return User::get($val);
}