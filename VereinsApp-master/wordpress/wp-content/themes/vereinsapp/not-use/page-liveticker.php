<?php

include_once WP_CONTENT_DIR . '/DBService/DBInteractionUtils.php';
include_once WP_CONTENT_DIR . '/DBService/DBInteractorService.php';

?>
<?php get_header();?>

    <div id="page-liveticker">
        <table>
            <tr>
                <td><strong>Published Date</strong></td>
                <td><strong>Content</strong></td>
            </tr>

                <?php
            $uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            if(substr($uri,-2,1)>=1)
                $page=substr($uri,-2,1);
            else
                $page=1;

            include_once WP_CONTENT_DIR . '/DBService/DBInteractorService.php';
            include_once WP_CONTENT_DIR . '/DBService/DBInteractionUtils.php';
            /*$servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "wpstudy";

            $conn = mysqli_connect($servername, $username, $password, $dbname);*/

            $sql = DBInteractionUtils::$_selectLivetickerEntries;

        /*	$sql = "SELECT live_id,
                            title,
                            content,
                            create_time FROM wp_liveticker
                            ORDER BY create_time, title ASC;
                            "; */
                //$result = mysqli_query($conn, $sql);
            $url_query=isset($parse_url["query"]) ? $parse_url["query"] : ""; //单独取出URL的查询字串
            if($url_query){
                $url_query=preg_replace("/page=[^&]*[&]?/i","",$url_query);
                $url=str_replace($parse_url["query"],$url_query,$url);//将处理后的URL的查询字串替换原来的URL的查询字串
                $url.="&page";//在URL后加page查询信息，但待赋值
            }else{
                $url.="?page";
            }
            $url = empty($url) ? $_SERVER["REQUEST_URI"] : $url;


            $resultSet = DBInteractorService::getInstance()->executeSelectStatement($sql);
            $result = DBInteractorService::getInstance()->executeGeneralStatement($sql);
            $totle = count($resultSet);
            //echo $totle;
            $count = 3;
            $lastpg=ceil($totle/$count); //最后页，也是总页数
            $lastpg=$lastpg ? $lastpg : 1; //没有显示条目，置最后页为1
            $page=min($lastpg,$page);
            $prepg=$page-1; //上一页
            $nextpg=($page==$lastpg ? 0 : $page+1); //下一页
            $firstcount=($page-1)*$count;
            
            $sql = DBInteractionUtils::selectLimitedLivetickerEntries($firstcount,$count);
            $resultSet = DBInteractorService::getInstance()->executeSelectStatement($sql);

                //if (mysqli_num_rows($result) > 0) :
                    //while($row = mysqli_fetch_assoc($result)):
                    foreach ($resultSet as $result) {
                        echo '<tr>';
                        echo '<td ROWSPAN="2" width="120" style="vertical-align:text-top;">';
                        echo $result->create_time.'</td>';
                        echo '<td><strong>'.$result->title.'</strong></td>';
                        echo '</tr><tr><td>'.$result->content.'</td>';
                        echo '</tr>';
                        echo '<tr><td colspan="2"><hr align="center"/></td></tr>';
                    }
                    //endwhile;
                ?></div>


            </table>
        <?php

        $pagenav="Display article ".($totle?($firstcount+1):0) . "/" . min($firstcount+$count,$total)." record，total $totle record<br />";

        $pagenav.=" <a href='$url=1'>first page</a> ";
        if($prepg) $pagenav.=" <a href='$url=$prepg'>previous page</a> "; else $pagenav.=" previous page ";
        if($nextpg) $pagenav.=" <a href='$url=$nextpg'>next page</a> "; else $pagenav.=" next page ";
        $pagenav.=" <a href='$url=$lastpg'>last page</a> ";

        //下拉跳转列表，循环列出所有页码
        $pagenav.="　到第 <select name='topage' size='1' onchange='window.location=\"$url=\"+this.value'>\n";
        for($i=1;$i<=$lastpg;$i++){
        if($i==$page){
        $pagenav.="<option value='$i' selected>$i</option>\n";
        }else{
        $pagenav.="<option value='$i'>$i</option>\n";
        }
        }
        $pagenav.="</select> page，total $lastpg pages";

        echo $pagenav;
            ?>
        </div>
    </div><!-- END .post-content -->
    </div><!-- END #main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>