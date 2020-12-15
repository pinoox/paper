<div class="page-content">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="articles-list search-result">
                    <form id="miniSearchFrom" method="get" action="<?php echo $_app ?>search/">
                        <div class="section-search">
                            <h2 class="section-title"><i class="fas fa-search"></i> <?php lang('front.search') ?> </h2> <b> (<?php echo $count; ?> <?php lang('front.found_articles'); ?>)</b>
                            <input class="input-search" name="q" type="search" value="<?php echo @$fields['q'] ?>" placeholder="<?php lang('front.searching_for_what_write_down_here'); ?>"/></span>
                        </div>
                    </form>
                    <?php if (isset($posts) && !empty($posts)) { ?>
                        <?php foreach ($posts as $i) { ?>
                            <?php includeView('pages>post_row',['post'=>$i]); ?>
                        <?php } ?>
                    <?php } else{?>
                        <div class="searchResultNotFound"><?php lang('front.search_result_not_found'); ?></div>
                    <?php } ?>
                </div>
                <div class="paging">
                    <?php if (!empty($posts)) { ?>
                        <?php showPage($page, 'href="' . $_app . "search/[page]/" . $query . '"'); ?>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-3 offset-md-1">
                <?php includeView('pages>sidebar'); ?>
            </div>
        </div>
    </div>
</div>
