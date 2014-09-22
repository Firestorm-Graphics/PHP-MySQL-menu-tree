<?php
/* 
    Get all menu items
    Columns in menu tabel should be:
    id - auto
    name - varchar() // Name of the link
    parent_id - int() // set to 0 if top level
    order - smallint(3) // the order of the items in the menu
*/
$sql = ('SELECT id,name,parent_id FROM menu ORDER BY parent_id ASC, `order` ASC, name ASC')
/*
    $items should be an array from your db query
*/
$items = $sql;

/*
    Create the $menuItems array
*/
$menuItems = array();
foreach($items as $data){
    if($data->parent_id == 0) {
        $menuItems[$data->id] = array();
        $menuItems[$data->id]['id'] = $data->id;
        $menuItems[$data->id]['name'] = $data->name;
        $menuItems[$data->id]['children'] = array();
    } else if($data->parent_id != 0) {
        $tmp = array();
        $tmp['id'] = $data->id;
        $tmp['name'] = $data->name;
        array_push($menuItems[$data->parent_id]['children'],$tmp);
        unset($tmp);
    }
}

/*
    The createMenuList function for the output
*/
function createMenuList($menuItems)
{
    $html = '';
    foreach($menuItems as $k => $v) {
        if(count($v['children']) > 0) {
            $html .= ('
                <li>
                    <a href="?p='.$v['id'].'" />'.$v['name'].'</a>
                    <ul>
                '."\r\n");

            foreach($v['children'] AS $child) {
                $html .= ('
                        <li>
                            <a href="?p='.$v['id'].'" />'.$v['name'].'</a>
                        </li>
                '."\r\n");
            }

            $html .= ('
                    </ul>
                </li>
            '."\r\n");
        } else{
            $html .= ('
                <a href="?p='.$v['id'].'" />'.$v['name'].'</a>
            '."\r\n");
        }
    }
    return $html;
}

echo '<nav><ul class="menu">'."\r\n";
    echo createMenuList($menuItems);
echo '</ul></nav>'; 
?>