<?php
if( php_sapi_name() != 'cli' ){
        $enter = '<br />';
        $space = '&nbsp;&nbsp;&nbsp;&nbsp;';
}else{
        $enter = "\n";
        $space = '    ';
}

$file = file_get_contents('bookmarks.json');
$bookmark = json_decode($file);

/*
foreach($bookmark->children as $child){
    echo $enter . $child->title;
    echo $enter . $child->root;
}
 */

echo read($bookmark);

function read( $children, $node = 1, $result = '' ) {
    $space = '';
    for( $i=1; $i <= $node; $i++ ){
        $space .= $GLOBALS['space'];
    }

    if( gettype($children) == 'object' || @$children->type ){
        if( $result == '' )
            $result .= "** This is a firefox json bookmark export file.**" . $GLOBALS['enter'] . $GLOBALS['enter'];

        //if( @$children->children && $node <= 3 ){
        if( @$children->children ){
                $node++;
                $result .= ($space . $children->title . '(' . $children->id . ' ' .$children->type . ')');
                $result .= $GLOBALS['enter'];
                foreach( $children->children as $key => $child ){
                        $result = read( $child, $node, $result );
                        if( $key == (count($children->children)-1) ){
                                $result .= $GLOBALS['enter'];
                        }
                }

        }else{
                //$result .= ($space . $children->title . '(' . $children->id . ')');
                //$result .= ' -> ' . @$children->uri . $GLOBALS['enter'];
                $result .= echoresult( ($children->title.'('.$children->id.' '.$children->type.')'), @$children->uri, $space, $children->type );
                $node--;
        }
    }else{
        $result .= "This is not a json firefox bookmark file" . $GLOBALS['enter'] . $GLOBALS['enter'];
    }
    return $result;
}

function echoresult( $title, $href, $space, $var1 = '', $var2 = '' ){
        if( php_sapi_name() != 'cli' ){
                if( $href ){
                        $link = $space . '<a href="' . $href . '" target="_blank">' . $title . '</a>' . $GLOBALS['enter'];
                }else{
                        $link = $space . '<a href="javascript:void(0)" style="color:#ccc">' . $title . '</a>' . $GLOBALS['enter'];
                }
        }else{
                $link = $space . $title;
                if( $href )
                        $link .= ' -> ' . $href;
                $link .= $GLOBALS['enter'];
        }

        if( $var1 == 'text/x-moz-place-container' )
                $link .= $GLOBALS['enter'];
        return $link;
}

echo $GLOBALS['enter']; ?>
