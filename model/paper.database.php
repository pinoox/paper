<?php
/**
 *      ****  *  *     *  ****  ****  *    *
 *      *  *  *  * *   *  *  *  *  *   *  *
 *      ****  *  *  *  *  *  *  *  *    *
 *      *     *  *   * *  *  *  *  *   *  *
 *      *     *  *    **  ****  ****  *    *
 * @author   Pinoox
 * @license  https://opensource.org/licenses/MIT MIT License
 */
namespace pinoox\app\com_pinoox_paper\model;

use pinoox\model\PinooxDatabase;

class PaperDatabase extends PinooxDatabase
{

    //constants
    const active = "active";
    const pending = "pending";
    const suspend = "suspend";

    const draft = "draft";
    const publish = "publish";

    const seen = "seen";
    const unseen = "unseen";

    //tables
    const article = 'com_pinoox_paper_article';
    const tag = 'com_pinoox_paper_tag';
    const article_tag = 'com_pinoox_paper_article_tag';
    const menu = 'com_pinoox_paper_menu';
    const settings = 'com_pinoox_paper_settings';
    const comment = 'com_pinoox_paper_comment';
    const page = 'com_pinoox_paper_page';
    const contact = 'com_pinoox_paper_contact';

}
