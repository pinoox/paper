<div class="page-content">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="articles-list search-result">
                    <form id="miniSearchFrom" method="get" action="<?php echo $_app ?>search/">
                        <h2 class="section-title"><i class="fas fa-search"></i> <?php lang('front.search') ?>: <span>
                        <input name="q" type="search" value="<?php echo $queryValue ?>"/></span>
                            <b><?php echo $resultCount; ?>&nbsp;<?php lang('front.found_articles'); ?></b>
                        </h2>
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
                        <?php showPage($page, 'href="' . $_app . "search/[page]/?" . $queryString . '"'); ?>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-3 offset-md-1">
                <?php includeView('pages>sidebar',['mostVisited'=>$mostVisited]); ?>
            </div>
        </div>
    </div>
</div>
