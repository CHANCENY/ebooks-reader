<div id="book-navigation" class="book-navigation col-md-4">
    <?php if(!empty($nav)): ?>
        <ul class="tree">
            <?php foreach($nav as $keyy=>$item): ?>
                <li class="item-navigation">
                    <?php if(is_array($item)): ?>
                        <?php $first_item = array_shift($item); ?>
                        <?php if(count($item) > 1): ?>
                            <?php if(is_numeric($first_item['label'])): ?>
<!--                            <ul class="tree">-->
                                <li>
                                    <details>
                                        <summary class="chapter-main" data="<?= $keyy; ?>"><?php echo $item[1]['label'] ?? null; ?></summary>
                                        <ul>
                                            <?php for ($i = 1; $i < count($item); $i++): ?>
                                                <li><a class="navigation-inner-links" href="#<?php $d = explode('#',$item[$i]['href']); echo end($d) ?? null; ?>"><?php echo $item[$i]['label'] ?? null; ?></a></li>
                                            <?php endfor; ?>
                                        </ul>
                                    </details>
                                </li>
<!--                            </ul>-->
                            <?php else: ?>
                                <details>
                                    <summary class="chapter-main" data="<?= $keyy; ?>"><?php echo $first_item['label'] ?? null; ?></summary>
                                    <ul>
                                        <?php foreach($item as $child): ?>
                                            <li><a class="navigation-inner-links" href="#<?php $d = explode('#',$child['href']); echo end($d) ?? null; ?>"><?php echo $child['label'] ?? null; ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </details>
                            <?php endif; ?>

                        <?php else: ?>
                            <a class="inner-chapter-no-tree" href="#" data="<?= $keyy; ?>"><?php echo $first_item['label'] ?? null; ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
