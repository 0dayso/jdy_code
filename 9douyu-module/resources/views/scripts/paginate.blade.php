        <?php
            if( !empty($paginate) && $paginate['total'] > 0){

                if( $paginate['current_page']>1 && isset($paginate["page_url"]) ){

                    echo '<a href="'.$paginate["page_url"].'1">首页</a>';

                }

                echo '<a class="prev '.(($paginate['prev_page_url']===null) ? 'disabled' : null) . '" href="' . (($paginate['prev_page_url']===null) ? 'javascript:void(0)' : $paginate['prev_page_url']) . '">上一页</a>';
                for($i=1; $i<=$paginate['last_page']; $i++){

                    if($paginate['current_page'] == $i){

                        echo '<a class="on" href="javascript:void(0)">' . $i . '</a>';

                    }else{

                        if( ( $i < $paginate['current_page'] && ($i+4) > $paginate['current_page']) || ( $i >$paginate['current_page'] && ($i-4) < $paginate['current_page']) ){

                            if( isset($paginate["page_url"]) ){

                                echo '<a href="'.$paginate["page_url"].$i.'">' . $i . '</a>';

                            }

                        }

                    }
                }

                echo '<a class="next '.(($paginate['next_page_url']===null) ? 'disabled' : null) . '" href="' . (($paginate['next_page_url']===null) ? 'javascript:void(0)' : $paginate['next_page_url']) . '">下一页</a>';

                if( $paginate['current_page'] < $paginate['last_page'] && isset($paginate["page_url"]) ){

                    echo '<a href="'.$paginate["page_url"].$paginate['last_page'].'" class="next">尾页</a>';

                }
/*                echo '<a class="next" style="min-width: 70px;" href="javascript:void(0)">每页 ' . $paginate['per_page'] . ' 个</a>';*/
                echo '<a class="next" style="min-width: 70px;" href="javascript:void(0)">共 ' . $paginate['last_page'] . ' 页</a>';
            }
        ?>
