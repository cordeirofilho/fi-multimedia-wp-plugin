<?php
/*
Template Name: LIS Detail
*/

$multi_config = get_option('multimedia_config');

$request_uri = $_SERVER["REQUEST_URI"];
$request_parts = explode('/', $request_uri);
$resource_id = end($request_parts);

$site_language = strtolower(get_bloginfo('language'));
$lang_dir = substr($site_language,0,2);

$plugin_slug = $multi_config['plugin_slug'];
$multi_service_url = $multi_config['service_url'];
$multi_disqus_id  = $multi_config['disqus_shortname'];
$multi_addthis_id = $multi_config['addthis_profile_id'];
$multi_service_request = $multi_service_url . 'api/multimedia/search/?id=multimedia.media.' .$resource_id . '&op=related&lang=' . $lang_dir;

//print $multi_service_request;

$response = @file_get_contents($multi_service_request);

if ($response){
    $response_json = json_decode($response);

    $resource = $response_json->diaServerResponse[0]->match->docs[0];
    $related_list = $response_json->diaServerResponse[0]->response->docs;
}

?>

<?php get_header('multimedia'); ?>

<div id="content" class="row-fluid">
        <div class="ajusta2">
            <div class="row-fluid breadcrumb">
                <a href="<?php echo real_site_url(); ?>"><?php _e('Home','multimedia'); ?></a> > 
                <a href="<?php echo real_site_url($plugin_slug); ?>"><?php _e('Multimedia', 'multimedia') ?> </a> > 
                <?php _e('Resource','multimedia'); ?>
            </div>

            <section id="conteudo">
                <header class="row-fluid border-bottom">
                    <h1 class="h1-header"><?php echo $resource->title; ?></h1>
                </header>
                <div class="row-fluid">
                    <article class="conteudo-loop">
                        <div class="conteudo-loop-rates">
                            <div class="star" data-score="1"></div>
                        </div>
                        
                        <p class="row-fluid margintop05">
                            <a href="<?php echo $resource->link[0]; ?>"><?php echo $resource->link[0]; ?></a><br/>
                        </p>

                        <p class="row-fluid">
                            <?php echo $resource->description[0]; ?>
                        </p>

                        <?php if ($resource->collection): ?>
                            <span class="row-fluid margintop05">
                                <span class="conteudo-loop-data-tit"><?php _e('Collection','multimedia'); ?>:</span>
                                <?php echo $resource->collection; ?> 
                            </span>
                        <?php endif; ?>

                        <?php if ($resource->authors): ?>
                            <span class="row-fluid margintop05">
                                <span class="conteudo-loop-data-tit"><?php _e('Author(s)','multimedia'); ?>:</span>
                                <strong><?php echo implode(", ", $resource->authors); ?></strong>
                            </span>
                        <?php endif; ?>

                        <?php if ($resource->authors): ?>
                            <span class="row-fluid margintop05">
                                <span class="conteudo-loop-data-tit"><?php _e('Contributor(s)','multimedia'); ?>:</span>
                                <strong><?php echo implode(", ", $resource->contributors); ?></strong>
                            </span>
                        <?php endif; ?>

                        <?php if ($resource->objective): ?>
                            <span class="row-fluid margintop05">
                                <span class="conteudo-loop-data-tit"><?php _e('Objective','multimedia'); ?>:</span>
                                <?php echo $resource->objective; ?> 
                            </span>
                        <?php endif; ?>

                        <?php if ($resource->language_display): ?>
                            <div id="conteudo-loop-idiomas" class="row-fluid">
                               <span class="conteudo-loop-idiomas-tit"><?php _e('Language','multimedia'); ?>:</span>
                               <?php print_lang_value($resource->language_display, $site_language); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($resource->duration): ?>
                            <div id="conteudo-loop-idiomas" class="row-fluid">
                               <span class="conteudo-loop-idiomas-tit"><?php _e('Duration','multimedia'); ?>:</span>
                               <?php echo $resource->duration[0]; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($resource->dimension): ?>
                            <div id="conteudo-loop-idiomas" class="row-fluid">
                               <span class="conteudo-loop-idiomas-tit"><?php _e('Dimension','multimedia'); ?>:</span>
                               <?php echo $resource->dimension[0]; ?>
                            </div>
                        <?php endif; ?>


                        <?php if ($resource->descriptor || $resource->keyword ) : ?>
                            <div id="conteudo-loop-tags" class="row-fluid margintop10">
                                <?php _e('Subject(s)','multimedia'); ?>:
                                <i class="ico-tags"> </i>   
                                    <?php 
                                        $descriptors = (array)$resource->descriptor;
                                        $keywords = (array)$resource->keyword;
                                    ?>                               
                                    <strong><?php echo implode(", ", array_merge( $descriptors, $keywords) ); ?></strong>
                              </div>
                        <?php endif; ?>

                        <?php if ($resource->link): ?>
                            <div id="conteudo-loop-data" class="row-fluid margintop05">
                                <?php display_thumbnail($resource->link[0]); ?>
                            </div>
                        <?php endif; ?>


                        <footer class="row-fluid margintop05">
                            <ul class="conteudo-loop-icons">
                                <li class="conteudo-loop-icons-li">
                                    <i class="ico-compartilhar"></i>
                                    <!-- AddThis Button BEGIN -->
                                    <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=<?php echo $multi_addthis_id; ?>"><?php _e('Share','multimedia'); ?></a>
                                    <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $multi_addthis_id; ?>"></script>
                                    <!-- AddThis Button END -->
                                    <!--
                                    <a href="#">                                       
                                        <?php _e('Share','multimedia'); ?>
                                    </a>
                                    -->
                                </li>

                                <li class="conteudo-loop-icons-li">
                                    <span class="reportar-erro-open">
                                        <i class="ico-reportar"></i>
                                        <?php _e('Report error','multimedia'); ?>
                                    </span>

                                    <div class="reportar-erro"> 
                                        <div class="erro-form">
                                            <form action="<?php echo $multi_service_url ?>report-error" id="reportErrorForm">
                                                <input type="hidden" name="resource_type" value="media"/>
                                                <input type="hidden" name="resource_id" value="<?php echo $resource_id; ?>"/>
                                                <div class="reportar-erro-close">[X]</div>
                                                <span class="reportar-erro-tit"><?php _e('Reason','multimedia'); ?></span>

                                                <div class="row-fluid margintop05">
                                                    <input type="radio" name="code" id="txtMotivo1" value="0">
                                                    <label class="reportar-erro-lbl" for="txtMotivo1"><?php _e('Invalid Link','multimedia'); ?></label>
                                                </div>

                                                <div class="row-fluid">
                                                    <input type="radio" name="code" id="txtMotivo2" value="1">
                                                    <label class="reportar-erro-lbl" for="txtMotivo2"><?php _e('Bad content','multimedia'); ?></label>
                                                </div>

                                                <div class="row-fluid">
                                                    <input type="radio" name="code" id="txtMotivo3" value="3">
                                                    <label class="reportar-erro-lbl" for="txtMotivo3"><?php _e('Other','multimedia'); ?></label>
                                                </div>

                                                <div class="row-fluid margintop05">
                                                    <textarea name="description" id="txtArea" class="reportar-erro-area" cols="20" rows="2"></textarea>
                                                </div>

                                                <div class="row-fluid margintop05">
                                                    <button class="pull-right reportar-erro-btn"><?php _e('Send','multimedia'); ?></button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="error-report-result">
                                            <div class="reportar-erro-close">[X]</div>
                                            <div id="result-ok">
                                                <?php _e('Thank you for your report.','multimedia'); ?>
                                            </div>
                                            <div id="result-problem">
                                                <?php _e('Communication problem. Please try again later.','multimedia'); ?>
                                            </div>
                                        </div>
                                    </div>

                                </li>
                            </ul>
                        </footer>

                        <?php if ($multi_disqus_id != '') :?>
                            <div id="disqus_thread" class="row-fluid margintop25"></div>
                            <script type="text/javascript">
                                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                                var disqus_shortname = '<?php echo $multi_disqus_id; ?>'; // required: replace example with your forum shortname

                                /* * * DON'T EDIT BELOW THIS LINE * * */
                                (function() {
                                    var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                                    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                                })();
                            </script>
                            <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                            <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
                        <?php endif; ?>
    
                    </article>
                </div>
            </section>

            <aside id="sidebar">
		      
                <?php if ($multi_config['show_form']) : ?>
                    <section class="header-search">
                        <form role="search" method="get" id="searchform" action="<?php echo real_site_url($plugin_slug); ?>">
                            <input value='<?php echo $query ?>' name="q" class="input-search" id="s" type="text" placeholder="<?php _e('Search', 'multimedia'); ?>...">
                            <input id="searchsubmit" value="<?php _e('Search', 'multimedia'); ?>" type="submit">
                        </form>
                    </section>
                <?php endif; ?>
                
                <?php if ( count($related_list) > 0 ) : ?>
                    <section class="row-fluid marginbottom25 widget_categories">
                        <header class="row-fluid border-bottom marginbottom15">
                            <h1 class="h1-header"><?php _e('Related','multimedia'); ?></h1>
                        </header>
                        <ul>
                            <?php foreach ( $related_list as $related) { ?>
                                <?php if ($related->django_ct == 'multimedia.media') : ?>
                                    <li class="cat-item">
                                        <a href="<?php echo real_site_url($plugin_slug); ?>resource/<?php echo $related->django_id; ?>"><?php echo $related->title ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php } ?>
                        </ul>
                    </section>
                <?php endif; ?>
            </aside>

        </div>
    </div>

<?php get_footer();?>