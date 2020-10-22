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
    const settings = 'com_pinoox_paper_settings';
    const post = 'com_pinoox_paper_post';
    const post_tag = 'com_pinoox_paper_post_tag';
    const tag = 'com_pinoox_paper_tag';
    const category = 'com_pinoox_paper_category';
    const contact = 'com_pinoox_paper_contact';
    const comment = 'com_pinoox_paper_comment';

}
